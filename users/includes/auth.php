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
function login($phone, $password)
{
    $database = new Database();
    $db = $database->connect();

    $query = "SELECT comid, phone, password FROM company WHERE phone = :phone and password = :password";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();
        $_SESSION['user_id'] = $user['comid'];
        $_SESSION['phone'] = $user['phone'];
        return true;
    }

    return false;
}

// Бүртгүүлэх
function register($phone, $password)
{
    $database = new Database();
    $db = $database->connect();

    // Утасны дугаар аль хэдийн байгаа эсэхийг шалгах
    $checkQuery = "SELECT id FROM company WHERE phone = :phone";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':phone', $phone);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        return false; // Утасны дугаар аль хэдийн бүртгэлтэй
    }

    // Нууц үгийг hash хийх
    //$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Шинэ хэрэглэгч үүсгэх
    $query = "INSERT INTO company (phone, password) VALUES (:phone, :password)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $db->lastInsertId();
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
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// Компанийн мэдээлэл байгаа эсэхийг шалгах
function hasCompanyProfile($userId)
{
    /*$database = new Database();
    $db = $database->connect();

    $query = "SELECT comID FROM company WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    return $stmt->rowCount() > 0;*/
    return true;
}


function org_get($reg)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.ebarimt.mn/api/info/check/getTinInfo?regNo=$reg",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $response = json_decode($response);

    $item = new stdClass();

    if ($response->status == '200') {
        $tin = $response->data;
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.ebarimt.mn/api/info/check/getInfo?tin=$tin",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json"
            ],
        ]);

        $res = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $res = json_decode($res);
        //print_r($res);
        if ($res->status == '200') {

            $item->status = 200;
            $item->msg = $res->data->name;
            $item->tin = $tin;
            return $item;
        } else {
            $item->status = 500;
            $item->msg = $res->msg;
            return $item;
        }
    } else {
        $item->status = 500;
        $item->msg = $response->msg;
        return $item;
    }
}
