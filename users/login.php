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
                logTable("login", "rd", $phone, "Login user");
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Утасны дугаар эсвэл нууц үг буруу байна!';
                logTable("login", "rd", $phone, "Incorrect data");
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
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="auth-container">
        <div class="head">
            <img src="images/logo.png" alt="Logo" class="logo" width="100px">
            <h3>ETUSUL</h3>
            <label>Барилга, дэд бүтцийн ажил гүйцэтгэгч аж нэгжүүдэд зориулсан систем</label>
        </div>

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
                <input type="number" id="register_check" name="register_check" placeholder="7 оронтой тоо" required>
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
            let register_check = document.getElementById('register_check');
            register_check.disabled = false;
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
            $.ajax({
                url: "logtable.php",
                type: "POST",
                data: {
                    turul: "check_reg",
                    rd: $('#register_check').val(),
                    phone: "0",
                    action: "Checking registration number"
                },
                beforeSend: function() {

                },
                success: function(datatin) {
                    console.log(datatin);
                },
                async: true
            });

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
                                register_check.disabled = false;
                            },
                            beforeSend: function() {
                                register_check.disabled = true;
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