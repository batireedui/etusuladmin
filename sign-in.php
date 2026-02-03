<?php
include_once 'inc.php';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$odoo = date("Y-m-d H:i:s");
// Алдааг хадгалах массив үүсгэнэ
$errors = [];

// Хэрэв phone password алдаатай бол алдааг session-д бичээд логин хуудас руу үсэргэнэ
if (sizeof($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header('Location: /login.php');
    exit();
}
_selectRow(
    "select id, name, email from adminuser where email=? and password=?",
    'ss',
    [$email, $password],
    $user_id,
    $user_name,
    $user_email
);

if (!empty($user_id)) {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $_SESSION['userid'] = $user_id;
    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_email'] = $user_email;
    _exec(
        "INSERT INTO logtable (turul, rd, phone, ognoo, action) VALUES (?, ?, ?, ?, ?)",
        'sssss',
        ["AdminLogin", "", $email, $odoo, "successful login"],
        $count
    );
    header('Location: index.php');
    exit();
    
} else {
    _exec(
        "INSERT INTO logtable (turul, rd, phone, ognoo, action) VALUES (?, ?, ?, ?, ?)",
        'sssss',
        ["AdminLogin", "", $email, $odoo, "failed login"],
        $count
    );
    $_SESSION['errors'] = ["Таны нэвтрэх нэр эсвэл нууц үг буруу байна"];
    header('Location: /login.php');
    exit();
}
