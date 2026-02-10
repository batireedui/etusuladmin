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

// Төлбөрийн мэдээлэл татах
$paymentQuery = "SELECT * FROM payments 
                 WHERE comRD = :rd 
                 AND startDate <= NOW() 
                 AND expiredDate >= NOW()
                 ORDER BY startDate DESC 
                 LIMIT 1";
$paymentStmt = $db->prepare($paymentQuery);
$paymentStmt->bindParam(':rd', $company['RD']);
$paymentStmt->execute();
$payment = $paymentStmt->fetch();

// Үлдсэн хоног тооцоолох
$remainingDays = 0;
if ($payment && $payment['expiredDate']) {
    $today = new DateTime();
    $expireDate = new DateTime($payment['expiredDate']);
    $interval = $today->diff($expireDate);
    $remainingDays = $interval->days;
    if ($today > $expireDate) {
        $remainingDays = -$remainingDays; // Хугацаа дууссан бол сөрөг утга
    }
}
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
            <div class="header-content">
                <h1><?php echo htmlspecialchars($company['comName']); ?></h1>
                <p>Бүртгэлийн мэдээлэл</p>
                <div class="dashboard-actions">
                    <button class="btn btn-primary" onclick="toggleEditMode()">
                        <span id="editBtnText">Засах</span>
                    </button>
                    <a href="profile-form.php" class="btn btn-secondary">Мэдээлэл нэмэх</a>
                </div>
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
                            <span class="status-badge <?php 
    echo $company['status'] === 'registered' ? 'status-test' : 
        ($company['status'] === 'paid' ? 'status-active' : 'status-expired'); 
?>">
    <?php 
        echo $company['status'] === 'registered' ? 'Туршилтын' : 
            ($company['status'] === 'paid' ? 'Идэвхтэй' : 'Хугацаа дууссан'); 
    ?>
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

            <!-- Төлбөрийн мэдээлэл болон Татах хэсэг -->
            <div class="info-cards">
                <div class="info-card">
                    <h3>Төлбөрийн мэдээлэл</h3>
                    <?php if ($payment): ?>
                        <div class="info-item">
                            <span class="info-label">Статус:</span>
                            <span class="info-value">
                                <span class="status-badge <?php 
                                    echo $remainingDays > 0 ? 'status-active' : 'status-expired'; 
                                ?>">
                                    <?php echo $remainingDays > 0 ? 'Идэвхтэй' : 'Хугацаа дууссан'; ?>
                                </span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Эхэлсэн огноо:</span>
                            <span class="info-value"><?php echo date('Y-m-d', strtotime($payment['startDate'])); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Дуусах огноо:</span>
                            <span class="info-value"><?php echo date('Y-m-d', strtotime($payment['expiredDate'])); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Үлдсэн хугацаа:</span>
                            <span class="info-value">
                                <?php 
                                    if ($remainingDays > 0) {
                                        echo $remainingDays . ' хоног';
                                    } else {
                                        echo '<span style="color: red;">Дууссан (' . abs($remainingDays) . ' хоног)</span>';
                                    }
                                ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Сүүлийн төлбөр:</span>
                            <span class="info-value"><?php echo date('Y-m-d', strtotime($payment['paidDate'])); ?></span>
                        </div>
                        <div class="payment-actions">
                            <a href="payment.php" class="btn btn-primary">Багц сунгах</a>
                            <a href="payment-history.php" class="btn btn-secondary">Түүх харах</a>
                        </div>
                    <?php else: ?>
                        <div class="info-item">
                            <span class="info-label">Статус:</span>
                            <span class="info-value">
                                <span class="status-badge status-test">Туршилтын</span>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Мэдээлэл:</span>
                            <span class="info-value">Төлбөрийн мэдээлэл байхгүй байна</span>
                        </div>
                        <div class="payment-actions">
                            <a href="payment.php" class="btn btn-primary">Багц идэвхжүүлэх</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="info-card">
                    <h3>Татах материал</h3>
                    <div class="download-list">
                        <div class="download-item">
                            <div class="download-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="2" y="3" width="20" height="18" rx="2" stroke-width="2"/>
                                    <line x1="8" y1="7" x2="16" y2="7" stroke-width="2"/>
                                    <line x1="8" y1="11" x2="16" y2="11" stroke-width="2"/>
                                    <line x1="8" y1="15" x2="12" y2="15" stroke-width="2"/>
                                </svg>
                            </div>
                            <div class="download-info">
                                <h4>Windows Desktop Software</h4>
                                <p>Тендер, төсөв, баримтын систем</p>
                            </div>
                            <a href="downloads/etusul-setup.exe" class="download-link" download>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke-width="2"/>
                                    <polyline points="7 10 12 15 17 10" stroke-width="2"/>
                                    <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"/>
                                </svg>
                                Татах
                            </a>
                        </div>

                        <div class="download-item">
                            <div class="download-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="5" y="2" width="14" height="20" rx="2" stroke-width="2"/>
                                    <line x1="12" y1="18" x2="12" y2="18" stroke-width="2"/>
                                </svg>
                            </div>
                            <div class="download-info">
                                <h4>iOS App</h4>
                                <p>iPhone болон iPad-д зориулсан</p>
                            </div>
                            <a href="https://apps.apple.com/us/app/etusul/id6756805524" class="download-link" target="_blank">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke-width="2"/>
                                    <polyline points="15 3 21 3 21 9" stroke-width="2"/>
                                    <line x1="10" y1="14" x2="21" y2="3" stroke-width="2"/>
                                </svg>
                                App Store
                            </a>
                        </div>

                        <div class="download-item">
                            <div class="download-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="5" y="2" width="14" height="20" rx="2" stroke-width="2"/>
                                    <line x1="12" y1="18" x2="12" y2="18" stroke-width="2"/>
                                </svg>
                            </div>
                            <div class="download-info">
                                <h4>Android App</h4>
                                <p>Android утсанд зориулсан</p>
                            </div>
                            <a href="https://play.google.com/store/apps/details?id=com.etusul" class="download-link" target="_blank">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke-width="2"/>
                                    <polyline points="15 3 21 3 21 9" stroke-width="2"/>
                                    <line x1="10" y1="14" x2="21" y2="3" stroke-width="2"/>
                                </svg>
                                Play Store
                            </a>
                        </div>

                        <div class="download-item">
                            <div class="download-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke-width="2"/>
                                    <polyline points="14 2 14 8 20 8" stroke-width="2"/>
                                    <line x1="12" y1="18" x2="12" y2="12" stroke-width="2"/>
                                    <line x1="9" y1="15" x2="15" y2="15" stroke-width="2"/>
                                </svg>
                            </div>
                            <div class="download-info">
                                <h4>Гарын авлага</h4>
                                <p>Системийн хэрэглэх заавар (PDF)</p>
                            </div>
                            <a href="downloads/etusul-manual.pdf" class="download-link" download>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke-width="2"/>
                                    <polyline points="7 10 12 15 17 10" stroke-width="2"/>
                                    <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"/>
                                </svg>
                                Татах
                            </a>
                        </div>
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

    <!-- Footer -->
    <div class="dashboard-footer">
        <div class="footer-content">
            
            <div class="footer-description">
                Барилгын төслийн удирдлагын цогц систем<br>
                Төсөв, тендер, өдөр тутмын тайлан, зардал, төлбөр, баримтын менежмент
            </div>
            
            <div class="footer-links">
                <a href="about.php">Бидний тухай</a>
                <a href="features.php">Боломжууд</a>
                <a href="pricing.php">Үнийн мэдээлэл</a>
                <a href="support.php">Дэмжлэг</a>
                <a href="contact.php">Холбоо барих</a>
            </div>

            <div class="footer-social">
                <a href="https://facebook.com/etusul" class="social-link" target="_blank" title="Facebook">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                <a href="mailto:info@etusul.mn" class="social-link" title="Email">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke-width="2"/>
                        <polyline points="22,6 12,13 2,6" stroke-width="2"/>
                    </svg>
                </a>
                <a href="tel:+97699009900" class="social-link" title="Утас">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke-width="2"/>
                    </svg>
                </a>
            </div>

            <div class="footer-copyright">
                © 2025 FutureInnovation LLC. Бүх эрх хуулиар хамгаалагдсан.
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