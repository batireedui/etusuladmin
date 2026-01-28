<?php
require_once __DIR__ . '/includes/auth.php';

// Хэрэв аль хэдийн нэвтэрсэн бол dashboard руу шилжүүлэх
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$success = '';

// Form submit хийгдсэн эсэхийг шалгах
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Нэвтрэх
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        if (empty($email) || empty($password)) {
            $error = 'И-мэйл болон нууц үгээ оруулна уу!';
        } else {
            if (login($email, $password)) {
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'И-мэйл эсвэл нууц үг буруу байна!';
            }
        }
    } elseif (isset($_POST['register'])) {
        // Бүртгүүлэх
        $email = trim($_POST['register_email']);
        $password = $_POST['register_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        if (empty($email) || empty($password) || empty($confirmPassword)) {
            $error = 'Бүх талбарыг бөглөнө үү!';
        } elseif ($password !== $confirmPassword) {
            $error = 'Нууц үг таарахгүй байна!';
        } elseif (strlen($password) < 6) {
            $error = 'Нууц үг хамгийн багадаа 6 тэмдэгт байх ёстой!';
        } else {
            if (register($email, $password)) {
                header('Location: profile-form.php');
                exit();
            } else {
                $error = 'Энэ и-мэйл хаяг аль хэдийн бүртгэлтэй байна!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Нэвтрэх / Бүртгүүлэх</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .auth-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }

        .tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 2px solid #e0e0e0;
        }

        .tab {
            flex: 1;
            padding: 18px;
            text-align: center;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            color: #757575;
            transition: all 0.3s ease;
            position: relative;
        }

        .tab:hover {
            background: #f0f0f0;
        }

        .tab.active {
            color: #5c6bc0;
            background: #ffffff;
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #5c6bc0;
        }

        .form-container {
            padding: 40px 35px;
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
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d0d0d0;
            border-radius: 6px;
            font-size: 15px;
            color: #424242;
            background: #fafafa;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #5c6bc0;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(92, 107, 192, 0.1);
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
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #4e5bb5 0%, #6a78c1 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(92, 107, 192, 0.3);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
            cursor: pointer;
        }

        .checkbox-group label {
            margin-bottom: 0;
            font-size: 13px;
            color: #616161;
            font-weight: 400;
            cursor: pointer;
        }

        .hidden {
            display: none;
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

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #81c784;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="tabs">
            <button class="tab active" onclick="showForm('login')">Нэвтрэх</button>
            <button class="tab" onclick="showForm('register')">Бүртгүүлэх</button>
        </div>

        <!-- Login Form -->
        <div id="loginForm" class="form-container">
            <?php if ($error && isset($_POST['login'])): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">И-мэйл хаяг <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="example@email.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Нууц үг <span class="required">*</span></label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="rememberMe" name="remember">
                    <label for="rememberMe">Намайг санах</label>
                </div>

                <button type="submit" name="login" class="submit-btn">Нэвтрэх</button>
            </form>
        </div>

        <!-- Register Form -->
        <div id="registerForm" class="form-container hidden">
            <?php if ($error && isset($_POST['register'])): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="register_email">И-мэйл хаяг <span class="required">*</span></label>
                    <input type="email" id="register_email" name="register_email" placeholder="example@email.com" required>
                </div>

                <div class="form-group">
                    <label for="register_password">Нууц үг <span class="required">*</span></label>
                    <input type="password" id="register_password" name="register_password" placeholder="••••••••" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Нууц үг давтах <span class="required">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="agreeTerms" required>
                    <label for="agreeTerms">Үйлчилгээний нөхцөлтэй зөвшөөрч байна</label>
                </div>

                <button type="submit" name="register" class="submit-btn">Бүртгүүлэх</button>
            </form>
        </div>
    </div>

    <script>
        function showForm(formType) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const tabs = document.querySelectorAll('.tab');

            if (formType === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                tabs[0].classList.add('active');
                tabs[1].classList.remove('active');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                tabs[0].classList.remove('active');
                tabs[1].classList.add('active');
            }
        }

        // Check if there was a registration attempt to show the register tab
        <?php if (isset($_POST['register'])): ?>
            showForm('register');
        <?php endif; ?>
    </script>
</body>
</html>