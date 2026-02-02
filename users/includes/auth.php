<?php
session_start();
require_once __DIR__ . '/../api/aaa.php';

// Хэрэглэгч нэвтэрсэн эсэхийг шалгах
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Хэрэглэгч нэвтэрсэн эсэхийг шалгаж, нэвтрээгүй бол login хуудас руу шилжүүлэх
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Нэвтрэх
function login($email, $password)
{
    $database = new Database();
    $db = $database->connect();

    $query = "SELECT comid, email, password FROM company WHERE email = :email and password = :password";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['user_id'] = $user['comid'];
        $_SESSION['email'] = $user['email'];
        return true;
    }

    return false;
}

// Бүртгүүлэх
function register($email, $password)
{
    $database = new Database();
    $db = $database->connect();

    // И-мэйл хаяг аль хэдийн байгаа эсэхийг шалгах
    $checkQuery = "SELECT id FROM users WHERE email = :email";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        return false; // И-мэйл хаяг аль хэдийн бүртгэлтэй
    }

    // Нууц үгийг hash хийх
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Шинэ хэрэглэгч үүсгэх
    $query = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $db->lastInsertId();
        $_SESSION['email'] = $email;
        return true;
    }

    return false;
}

// Гарах
function logout()
{
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

// Одоогийн хэрэглэгчийн ID авах
function getCurrentUserId()
{
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// Компанийн мэдээлэл байгаа эсэхийг шалгах
function hasCompanyProfile($userId)
{
    $database = new Database();
    $db = $database->connect();

    $query = "SELECT id FROM company WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    return $stmt->rowCount() > 0;
}
