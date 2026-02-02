<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$userId = getCurrentUserId();

// Компанийн мэдээлэл байгаа эсэхийг шалгах
if (!hasCompanyProfile($userId)) {
    header('Location: profile-form.php');
    exit();
}

$database = new Database();
$db = $database->connect();

$success = '';
$error = '';

// Мэдээлэл шинэчлэх
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $rd = trim($_POST['rd']);
    $comName = trim($_POST['comName']);
    $comDir = trim($_POST['comDir']);
    $country = $_POST['country'];
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $facebook = trim($_POST['facebook']);
    
    try {
        $query = "UPDATE companies SET 
                  rd = :rd,
                  comName = :comName,
                  comDir = :comDir,
                  country = :country,
                  address = :address,
                  phone = :phone,
                  email = :email,
                  facebook = :facebook
                  WHERE user_id = :user_id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':rd', $rd);
        $stmt->bindParam(':comName', $comName);
        $stmt->bindParam(':comDir', $comDir);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':facebook', $facebook);
        $stmt->bindParam(':user_id', $userId);
        
        if ($stmt->execute()) {
            $success = 'Мэдээлэл амжилттай шинэчлэгдлээ!';
        }
    } catch (PDOException $e) {
        $error = 'Алдаа гарлаа: ' . $e->getMessage();
    }
}

// Компанийн мэдээлэл татах
$query = "SELECT * FROM company WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$company = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars($company['comName']); ?></title>
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

        .dashboard-container {
            max-width: 1200px;
            margin: 20px auto;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #5c6bc0 0%, #7986cb 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(92, 107, 192, 0.3);
            position: relative;
        }

        .dashboard-header h1 {
            font-size: 32px;
            margin-bottom: 5px;
        }

        .dashboard-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .dashboard-actions {
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: white;
            color: #5c6bc0;
        }

        .btn-primary:hover {
            background: #f5f5f5;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid white;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .logout-btn {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid white;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .info-card h3 {
            color: #5c6bc0;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e8eaf6;
        }

        .info-item {
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .info-label {
            font-weight: 500;
            color: #616161;
            font-size: 13px;
            margin-right: 10px;
            min-width: 120px;
        }

        .info-value {
            color: #424242;
            font-size: 14px;
            flex: 1;
            text-align: right;
        }

        .company-images {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .image-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            text-align: center;
        }

        .image-box h4 {
            color: #616161;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .image-box img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            max-height: 200px;
        }

        .image-box .no-image {
            padding: 40px;
            background: #f5f5f5;
            border-radius: 6px;
            color: #9e9e9e;
            font-size: 13px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-registered {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-test {
            background: #fff3e0;
            color: #ef6c00;
        }

        /* Edit Mode */
        .edit-mode .info-value {
            display: none;
        }

        .edit-mode input,
        .edit-mode textarea,
        .edit-mode select {
            display: block !important;
        }

        .info-item input,
        .info-item textarea,
        .info-item select {
            display: none;
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d0d0d0;
            border-radius: 4px;
            font-size: 14px;
        }

        .info-item textarea {
            min-height: 60px;
            resize: vertical;
        }

        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #81c784;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        @media (max-width: 768px) {
            .dashboard-header h1 {
                font-size: 24px;
            }

            .logout-btn {
                position: static;
                display: block;
                margin-top: 20px;
            }

            .info-item {
                flex-direction: column;
            }

            .info-label {
                margin-bottom: 5px;
            }

            .info-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <a href="logout.php" class="logout-btn">Гарах</a>
            <h1><?php echo htmlspecialchars($company['comName']); ?></h1>
            <p>Бүртгэлийн мэдээлэл</p>
            <div class="dashboard-actions">
                <button class="btn btn-primary" onclick="toggleEditMode()">
                    <span id="editBtnText">Засах</span>
                </button>
                <a href="profile-form.php" class="btn btn-secondary">Мэдээлэл нэмэх</a>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="editForm">
            <div class="info-cards">
                <div class="info-card">
                    <h3>Үндсэн мэдээлэл</h3>
                    <div class="info-item">
                        <span class="info-label">РД:</span>
                        <span class="info-value"><?php echo htmlspecialchars($company['rd']); ?></span>
                        <input type="text" name="rd" value="<?php echo htmlspecialchars($company['rd']); ?>" maxlength="7">
                    </div>
                    <div class="info-item">
                        <span class="info-label">Компанийн нэр:</span>
                        <span class="info-value"><?php echo htmlspecialchars($company['comName']); ?></span>
                        <input type="text" name="comName" value="<?php echo htmlspecialchars($company['comName']); ?>">
                    </div>
                    <div class="info-item">
                        <span class="info-label">Захирал:</span>
                        <span class="info-value"><?php echo htmlspecialchars($company['comDir']); ?></span>
                        <input type="text" name="comDir" value="<?php echo htmlspecialchars($company['comDir']); ?>">
                    </div>
                    <div class="info-item">
                        <span class="info-label">Улс:</span>
                        <span class="info-value"><?php echo htmlspecialchars($company['country']); ?></span>
                        <select name="country">
                            <option value="Монгол улс" <?php echo $company['country'] === 'Монгол улс' ? 'selected' : ''; ?>>Монгол улс</option>
                            <option value="Бусад" <?php echo $company['country'] === 'Бусад' ? 'selected' : ''; ?>>Бусад</option>
                        </select>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Статус:</span>
                        <span class="info-value">
                            <span class="status-badge <?php echo $company['status'] === 'registered' ? 'status-registered' : 'status-test'; ?>">
                                <?php echo $company['status'] === 'registered' ? 'Бүртгэлтэй' : 'Туршилтын'; ?>
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Бүртгэсэн огноо:</span>
                        <span class="info-value"><?php echo date('Y-m-d', strtotime($company['ognoo'])); ?></span>
                    </div>
                </div>

                <div class="info-card">
                    <h3>Холбоо барих мэдээлэл</h3>
                    <div class="info-item">
                        <span class="info-label">Хаяг:</span>
                        <span class="info-value"><?php echo nl2br(htmlspecialchars($company['address'])); ?></span>
                        <textarea name="address"><?php echo htmlspecialchars($company['address']); ?></textarea>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Утас:</span>
                        <span class="info-value"><?php echo htmlspecialchars($company['phone']); ?></span>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($company['phone']); ?>">
                    </div>
                    <div class="info-item">
                        <span class="info-label">И-мэйл:</span>
                        <span class="info-value"><?php echo htmlspecialchars($company['email']); ?></span>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($company['email']); ?>">
                    </div>
                    <div class="info-item">
                        <span class="info-label">Хоёр дахь дугаар:</span>
                        <span class="info-value"><?php echo htmlspecialchars($company['facebook'] ?: '-'); ?></span>
                        <input type="tel" name="facebook" value="<?php echo htmlspecialchars($company['facebook']); ?>">
                    </div>
                </div>
            </div>
        </form>

        <div class="info-card">
            <h3>Зургууд</h3>
            <div class="company-images">
                <div class="image-box">
                    <h4>Профайл зураг</h4>
                    <?php if ($company['propic']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($company['propic']); ?>" alt="Профайл зураг">
                    <?php else: ?>
                        <div class="no-image">Зураг байхгүй</div>
                    <?php endif; ?>
                </div>

                <div class="image-box">
                    <h4>Компанийн лого</h4>
                    <?php if ($company['company_logo']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($company['company_logo']); ?>" alt="Компанийн лого">
                    <?php else: ?>
                        <div class="no-image">Зураг байхгүй</div>
                    <?php endif; ?>
                </div>

                <div class="image-box">
                    <h4>Гарын үсэг</h4>
                    <?php if ($company['signature']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($company['signature']); ?>" alt="Гарын үсэг">
                    <?php else: ?>
                        <div class="no-image">Зураг байхгүй</div>
                    <?php endif; ?>
                </div>

                <div class="image-box">
                    <h4>Тамга</h4>
                    <?php if ($company['stamp']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($company['stamp']); ?>" alt="Тамга">
                    <?php else: ?>
                        <div class="no-image">Зураг байхгүй</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isEditMode = false;

        function toggleEditMode() {
            isEditMode = !isEditMode;
            const cards = document.querySelectorAll('.info-card');
            const editBtn = document.getElementById('editBtnText');
            const form = document.getElementById('editForm');

            if (isEditMode) {
                cards.forEach(card => card.classList.add('edit-mode'));
                editBtn.textContent = 'Хадгалах';
            } else {
                // Submit form
                const formData = new FormData(form);
                formData.append('update', '1');
                
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(() => {
                    window.location.reload();
                });
            }
        }
    </script>
</body>
</html>