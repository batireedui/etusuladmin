<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$userId = getCurrentUserId();
$error = '';
$success = '';

// Хэрэв аль хэдийн компанийн мэдээлэл бөглөсөн бол dashboard руу шилжүүлэх
/*
if (hasCompanyProfile($userId)) {
    header('Location: dashboard.php');
    exit();
}
*/
// Form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->connect();
    
    // Талбаруудыг авах
    $rd = trim($_POST['rd']);
    $comName = trim($_POST['comName']);
    $comDir = trim($_POST['comDir']);
    $country = $_POST['country'];
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $facebook = trim($_POST['facebook']);
    $status = $_POST['status'];
    $ognoo = date('Y-m-d');
    
    // Файл upload хийх
    $uploadDir = __DIR__ . '/uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $propic = null;
    $companyLogo = null;
    $signature = null;
    $stamp = null;
    
    // Upload files
    if (isset($_FILES['propic']) && $_FILES['propic']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['propic']['name'], PATHINFO_EXTENSION);
        $propic = 'propic_' . $userId . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['propic']['tmp_name'], $uploadDir . $propic);
    }
    
    if (isset($_FILES['companyLogo']) && $_FILES['companyLogo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['companyLogo']['name'], PATHINFO_EXTENSION);
        $companyLogo = 'logo_' . $userId . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['companyLogo']['tmp_name'], $uploadDir . $companyLogo);
    }
    
    if (isset($_FILES['signature']) && $_FILES['signature']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['signature']['name'], PATHINFO_EXTENSION);
        $signature = 'signature_' . $userId . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['signature']['tmp_name'], $uploadDir . $signature);
    }
    
    if (isset($_FILES['stamp']) && $_FILES['stamp']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['stamp']['name'], PATHINFO_EXTENSION);
        $stamp = 'stamp_' . $userId . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['stamp']['tmp_name'], $uploadDir . $stamp);
    }
    
    // Мэдээллийг хадгалах
    try {
        $query = "INSERT INTO companies (user_id, rd, comName, comDir, country, address, phone, email, facebook, ognoo, propic, status, company_logo, signature, stamp) 
                  VALUES (:user_id, :rd, :comName, :comDir, :country, :address, :phone, :email, :facebook, :ognoo, :propic, :status, :company_logo, :signature, :stamp)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':rd', $rd);
        $stmt->bindParam(':comName', $comName);
        $stmt->bindParam(':comDir', $comDir);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':facebook', $facebook);
        $stmt->bindParam(':ognoo', $ognoo);
        $stmt->bindParam(':propic', $propic);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':company_logo', $companyLogo);
        $stmt->bindParam(':signature', $signature);
        $stmt->bindParam(':stamp', $stamp);
        
        if ($stmt->execute()) {
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Мэдээлэл хадгалахад алдаа гарлаа!';
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = 'Энэ регистрийн дугаар аль хэдийн бүртгэлтэй байна!';
        } else {
            $error = 'Алдаа гарлаа: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Компанийн мэдээлэл бөглөх</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8eaf6 0%, #f5f5f5 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .profile-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            max-width: 900px;
            margin: 50px auto;
            padding: 40px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }

        .profile-header h2 {
            color: #424242;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .profile-header p {
            color: #757575;
            font-size: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row.single {
            grid-template-columns: 1fr;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #424242;
            font-size: 14px;
            font-weight: 500;
        }

        .required {
            color: #d32f2f;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d0d0d0;
            border-radius: 6px;
            font-size: 15px;
            color: #424242;
            background: #fafafa;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #5c6bc0;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(92, 107, 192, 0.1);
        }

        .file-upload-section {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
        }

        .file-upload-section h3 {
            color: #424242;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #5c6bc0 0%, #7986cb 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #4e5bb5 0%, #6a78c1 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(92, 107, 192, 0.3);
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        .logout-link {
            text-align: center;
            margin-top: 20px;
        }

        .logout-link a {
            color: #5c6bc0;
            text-decoration: none;
            font-size: 14px;
        }

        .logout-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h2>Компанийн мэдээлэл бөглөх</h2>
            <p>Та доорх талбаруудыг анхааралтай бөглөнө үү</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="rd">Регистрийн дугаар (РД) <span class="required">*</span></label>
                    <input type="text" id="rd" name="rd" placeholder="7 оронтой дугаар" maxlength="7" required>
                </div>
                <div class="form-group">
                    <label for="comName">Компанийн нэр <span class="required">*</span></label>
                    <input type="text" id="comName" name="comName" placeholder="Кирилээр бичнэ" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="comDir">Захиралын овог нэр <span class="required">*</span></label>
                    <input type="text" id="comDir" name="comDir" placeholder="Жишээ нь: Жавхлан Бат-Ирээдүй" required>
                </div>
                <div class="form-group">
                    <label for="country">Улс <span class="required">*</span></label>
                    <select id="country" name="country" required>
                        <option value="Монгол улс" selected>Монгол улс</option>
                        <option value="Бусад">Бусад</option>
                    </select>
                </div>
            </div>

            <div class="form-row single">
                <div class="form-group">
                    <label for="address">Хаяг <span class="required">*</span></label>
                    <textarea id="address" name="address" placeholder="Албан бичгийн бланк дээрх хаяг" required></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Утасны дугаар <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" placeholder="99xx-xxxx" required>
                </div>
                <div class="form-group">
                    <label for="email">И-мэйл хаяг <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="company@email.com" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="facebook">Хоёр дахь утасны дугаар</label>
                    <input type="tel" id="facebook" name="facebook" placeholder="Нэмэлт холбоо барих дугаар">
                </div>
                <div class="form-group">
                    <label for="status">Статус <span class="required">*</span></label>
                    <select id="status" name="status" required>
                        <option value="registered" selected>Бүртгэлтэй</option>
                        <option value="test">Туршилтын</option>
                    </select>
                </div>
            </div>

            <!-- File Upload Section -->
            <div class="file-upload-section">
                <h3>Файлууд</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="propic">Өөрийн зураг</label>
                        <input type="file" id="propic" name="propic" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="companyLogo">Компанийн лого <span class="required">*</span></label>
                        <input type="file" id="companyLogo" name="companyLogo" accept="image/*" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="signature">Гарын үсэг <span class="required">*</span></label>
                        <input type="file" id="signature" name="signature" accept="image/*" required>
                    </div>

                    <div class="form-group">
                        <label for="stamp">Тамга <span class="required">*</span></label>
                        <input type="file" id="stamp" name="stamp" accept="image/*" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn">Хадгалах</button>
        </form>

        <div class="logout-link">
            <a href="logout.php">Гарах</a>
        </div>
    </div>
</body>
</html>