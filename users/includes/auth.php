<?php
session_start();
require_once __DIR__ . '/../api/aaa.php';

// Хэрэглэгч нэвтэрсэн эсэхийг шалгах
function isLoggedIn()
{
    return isset($_SESSION['comID']);
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
function login($phone, $password)
{
    $database = new Database();
    $db = $database->connect();

    $query = "SELECT comID, phone, password FROM company WHERE phone = :phone and password = :password";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['comID'] = $user['comID'];
        $_SESSION['phone'] = $user['phone'];
        return true;
    }

    return false;
}

// Бүртгүүлэх
function register($number, $name, $phone, $password)
{
    $database = new Database();
    $db = $database->connect();
    $ognoo = date('Y-m-d H:i:s');
    // Утасны дугаар аль хэдийн байгаа эсэхийг шалгах
    $checkQuery = "SELECT comID FROM company WHERE phone = :phone OR RD = :RD";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':phone', $phone);
    $checkStmt->bindParam(':RD', $number);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        return false; // Утасны дугаар аль хэдийн бүртгэлтэй
    }

    // Нууц үгийг hash хийх
    //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Шинэ хэрэглэгч үүсгэх
    $query = "INSERT INTO company (RD, comName, phone, password, status, ognoo) VALUES (:RD, :comName, :phone, :password, 'test', :ognoo)";
    $stmt = $db->prepare($query);
    
    $stmt->bindParam(':RD', $number);
    $stmt->bindParam(':comName', $name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':ognoo', $ognoo);
    if ($stmt->execute()) {
        $_SESSION['comID'] = $db->lastInsertId();
        $_SESSION['phone'] = $phone;
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
    return isset($_SESSION['comID']) ? $_SESSION['comID'] : null;
}

// Компанийн мэдээлэл байгаа эсэхийг шалгах
function hasCompanyProfile($userId)
{
    /*$database = new Database();
    $db = $database->connect();

    $query = "SELECT comID FROM company WHERE comID = :comID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':comID', $userId);
    $stmt->execute();

    return $stmt->rowCount() > 0;*/
    return true;
}

