<?php
require_once __DIR__ . '/includes/auth.php';
if (isset($_POST['register'])) {
    // Бүртгүүлэх
    $number = trim($_POST['register_number']);
    $name = trim($_POST['register_name']);
    $phone = trim($_POST['register_phone']);
    $password = $_POST['register_password'];
    $confirmPassword = $_POST['confirm_password'];
    $status = 200;
    $error = '';
    if (empty($number) || empty($name) || (empty($phone) || empty($password) || empty($confirmPassword))) {
        $status = 500;
        $error = 'Бүх талбарыг бөглөнө үү!';
    } elseif ($password !== $confirmPassword) {
        $status = 500;
        $error = 'Нууц үг таарахгүй байна!';
    } elseif (strlen($password) < 6) {
        $status = 500;
        $error = 'Нууц үг хамгийн багадаа 6 тэмдэгт байх ёстой!';
    } else {
        if (register($number, $name, $phone, $password)) {
            logTable("register", $number, $phone, "Registering new user");
            //header('Location: profile-form.php');
            //exit();
        } else {
            logTable("register", $number, $phone, "Failed to register user - duplicate");
            $status = 500;
            $error = 'Энэ утасны дугаар эсвэл тухайн компани аль хэдийн бүртгэлтэй байна!';
        }
    }
    echo json_encode(['status' => $status, 'msg' => $error]);
}
