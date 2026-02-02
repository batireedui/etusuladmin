<?php
require_once __DIR__ . '/includes/auth.php';
if (isset($_POST['register'])) {
    // Бүртгүүлэх
    $phone = trim($_POST['register_phone']);
    $password = $_POST['register_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($phone) || empty($password) || empty($confirmPassword)) {
        $error = 'Бүх талбарыг бөглөнө үү!';
    } elseif ($password !== $confirmPassword) {
        $error = 'Нууц үг таарахгүй байна!';
    } elseif (strlen($password) < 6) {
        $error = 'Нууц үг хамгийн багадаа 6 тэмдэгт байх ёстой!';
    } else {
        if (register($phone, $password)) {
            header('Location: profile-form.php');
            exit();
        } else {
            $error = 'Энэ утасны дугаар аль хэдийн бүртгэлтэй байна!';
        }
    }
}
