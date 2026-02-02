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
        $phone = trim($_POST['phone']);
        $password = $_POST['password'];

        if (empty($phone) || empty($password)) {
            $error = 'Утасны дугаар болон нууц үгээ оруулна уу!';
        } else {
            if (login($phone, $password)) {
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Утасны дугаар эсвэл нууц үг буруу байна!';
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
        input[type="number"],
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
                    <label for="phone">Утасны дугаар <span class="required">*</span></label>
                    <input type="number" id="phone" name="phone" placeholder="88998899" required>
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
            <div class="form-group">
                <label for="register_check">Байгууллагын регистрийн дугаар <span class="required">*</span></label>
                <input type="number" id="register_check" name="register_check" placeholder="3755662" required>
            </div>
            <button type="button" class="submit-btn" id="btn_check" onclick="get_reg()">Шалгах</button>
            <form method="POST" action="" style="margin-top: 20px; display: none;" id="registerDetailsForm">
                <input type="hidden" id="register_number" name="register_number" required>

                <div class="form-group">
                    <label for="register_name">Байгуулагын нэр <span class="required">*</span></label>
                    <input type="text" id="register_name" name="register_name" required readonly>
                </div>
                <div class="form-group">
                    <label for="register_phone">Утасны дугаар <span class="required">*</span></label>
                    <input type="number" id="register_phone" name="register_phone" placeholder="88998899" required>
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

                <button type="button" name="register" onclick="register_send()" class="submit-btn">Бүртгүүлэх</button>
                <button type="button" class="submit-btn" style="background: #666666" onclick="newReg()">Болих</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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

        function newReg() {
            const btn_check = document.getElementById('btn_check');
            btn_check.classList.remove('hidden');
            $('#register_check').val('');
            $('#registerDetailsForm').hide();
        }

        function register_send() {
            let reg_number = $('#register_number').val();
            let reg_name = $('#register_name').val();
            let reg_phone = $('#register_phone').val();
            let reg_password = $('#register_password').val();
            let confirm_password = $('#confirm_password').val();
            let agreeTerms = document.getElementById('agreeTerms').checked;
            if (reg_number == '' || reg_name == '' || reg_phone == '' || reg_password == '' || confirm_password == '') {
                alert("Бүх талбарыг бөглөнө үү!");
                return;
            } else if (!agreeTerms) {
                alert("Үйлчилгээний нөхцөлтэй зөвшөөрнө үү!");
                return;
            } else if (reg_password.length < 6) {
                alert("Нууц үг хамгийн багадаа 6 тэмдэгттэй байх ёстой!");
                return;
            } else if (reg_password !== confirm_password) {
                alert("Нууц үг таарахгүй байна!");
                return;
            }

            $.ajax({
                url: "register.php",
                type: "POST",
                data: {
                    register: true,
                    register_number: reg_number,
                    register_name: reg_name,
                    register_phone: reg_phone,
                    register_password: reg_password,
                    confirm_password: confirm_password
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("Алдаа гарлаа !");
                },
                beforeSend: function() {
                    // You can add a loading indicator here
                },
                success: function(data) {
                    data = JSON.parse(data);
                    console.log(data);
                    if (data.status == 200) {
                        window.location.reload();
                    } else {
                        alert(data.msg);
                    }
                },
                async: true
            });
        }

        function get_reg() {
            let reg = $('#register_check').val();
            $.ajax({
                url: "https://api.ebarimt.mn/api/info/check/getTinInfo?regNo=" + reg,
                type: "GET",
                error: function(xhr, textStatus, errorThrown) {
                    console.log("Алдаа гарлаа !");
                    $("#btn_check").removeAttr("disabled");
                },
                beforeSend: function() {
                    $("#btn_check").attr("disabled", "disabled");
                },
                success: function(data) {
                    console.log(data);
                    if (data.status == 200) {
                        $.ajax({
                            url: "https://api.ebarimt.mn/api/info/check/getInfo?tin=" + data.data,
                            type: "GET",
                            error: function(xhr, textStatus, errorThrown) {
                                console.log("Алдаа гарлаа !");
                            },
                            beforeSend: function() {
                                $("#table").html("Түр хүлээнэ үү ...");
                            },
                            success: function(datatin) {
                                console.log(datatin);
                                if (data.status == 200) {
                                    const btn_check = document.getElementById('btn_check');
                                    btn_check.classList.add('hidden');
                                    $('#register_number').val(reg);
                                    $('#register_name').val(datatin.data.name);
                                    $("#registerDetailsForm").show();
                                } else {
                                    alert(data.msg);
                                }
                            },
                            async: true
                        });

                    } else {
                        alert(data.msg);
                    }
                    $("#btn_check").removeAttr("disabled");
                },
                async: true
            });
        }
    </script>
</body>

</html>