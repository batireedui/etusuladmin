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
        $query = "UPDATE company SET 
                  comDir = :comDir,
                  country = :country,
                  address = :address,
                  phone = :phone,
                  email = :email,
                  facebook = :facebook
                  WHERE rd = :rd";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':comDir', $comDir);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':facebook', $facebook);
        $stmt->bindParam(':rd', $rd);

        if ($stmt->execute()) {
            $success = 'Мэдээлэл амжилттай шинэчлэгдлээ!';
        }
    } catch (PDOException $e) {
        $error = 'Алдаа гарлаа: ' . $e->getMessage();
    }
}

// Компанийн мэдээлэл татах
$query = "SELECT * FROM company WHERE comID = :user_id";
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
    <title>Etusul - <?php echo htmlspecialchars($company['comName']); ?></title>
    <link rel="stylesheet" href="css/dashboard.css">
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
                        <span class="info-value-non"><?php echo htmlspecialchars($company['RD']); ?></span>
                        <input type="hidden" name="rd" value="<?php echo htmlspecialchars($company['RD']); ?>" maxlength="7">
                    </div>
                    <div class="info-item">
                        <span class="info-label">Компанийн нэр:</span>
                        <span class="info-value-non"><?php echo htmlspecialchars($company['comName']); ?></span>
                        <input type="hidden" name="comName" value="<?php echo htmlspecialchars($company['comName']); ?>">
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
        <!--   
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
        -->
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