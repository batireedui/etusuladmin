<?php
require_once __DIR__ . '/includes/auth.php';
if (isLoggedIn()) {

    $userId = getCurrentUserId();
    // ajax/upload_file.php
    // Зөвхөн AJAX POST хүсэлтийг хүлээж авна

    header('Content-Type: application/json; charset=utf-8');

    // Зөвхөн POST хүсэлт зөвшөөрнө
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Зөвшөөрөгдөөгүй хүсэлт.']);
        exit;
    }

    // AJAX хүсэлт мөн эсэхийг шалгах
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        echo json_encode(['success' => false, 'message' => 'Зөвхөн AJAX хүсэлт зөвшөөрнө.']);
        exit;
    }

    // Файл ирсэн эсэхийг шалгах
    if (!isset($_FILES['file'])) {
        echo json_encode(['success' => false, 'message' => 'Файл олдсонгүй.']);
        exit;
    }

    $file      = $_FILES['file'];
    $fieldName = isset($_POST['field']) ? preg_replace('/[^a-z_]/', '', $_POST['field']) : 'file';
    $userId    = isset($_POST['userId']) ? (int)$_POST['userId'] : 0;
    $folder    = isset($_POST['folder']) ? $_POST['folder'] : '0';

    // Upload очих хавтас — ROOT тодорхойлогдсон байх ёстой
    // Хэрэв тодорхойлогдоогүй бол энд тохируулна
    if (!defined('ROOT')) {
        define('ROOT', dirname(dirname(dirname(__FILE__)))); // ajax/ хавтасны дээр
    }
    
    // Зөвшөөрөгдсөн төрлүүд
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

    // ── Шалгалтууд ────────────────────────────────────────────

    if ($folder == '0') {
        echo json_encode(['success' => false, 'message' => 'Сервер дээрх ROOT хавтас олдсонгүй.']);
        exit;
    }

    // 1. Upload алдаа шалгах
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'Файл хэт том байна (php.ini хязгаар).',
            UPLOAD_ERR_FORM_SIZE  => 'Файл хэт том байна (form хязгаар).',
            UPLOAD_ERR_PARTIAL    => 'Файл бүрэн upload хийгдсэнгүй.',
            UPLOAD_ERR_NO_FILE    => 'Файл илгээгдсэнгүй.',
            UPLOAD_ERR_NO_TMP_DIR => 'Түр хавтас олдсонгүй.',
            UPLOAD_ERR_CANT_WRITE => 'Файл хадгалах боломжгүй.',
        ];
        $msg = $errors[$file['error']] ?? 'Тодорхойгүй алдаа гарлаа.';
        echo json_encode(['success' => false, 'message' => $msg]);
        exit;
    }

    // 2. MIME төрөл шалгах
    $finfo    = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Зөвхөн зураг файл (JPG, PNG, GIF, WebP, SVG) оруулна уу.']);
        exit;
    }

    // 3. Хэмжээ шалгах (5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Файлын хэмжээ 5MB-аас хэтрэхгүй байна.']);
        exit;
    }  

    // ── Файлын нэр үүсгэх ────────────────────────────────────
    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)); 
    $prefixes = [
        'avatar'    => 'propic',
        'companyLogo' => 'companyLogo',
        'signature' => 'signature',
        'stamp'     => 'stamp',
        'blank'     => 'blank',
        'ztushaal'     => 'ztushaal',
    ];
    $prefix   = $prefixes[$fieldName] ?? $fieldName;
    if ($prefix == 'propic') {
        $uploadDir = ROOT . '/' . $folder . '/dist/uploads/company/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); }
        $destPath = $uploadDir . 'avatar.jpg';
        $newName = 'avatar.jpg';
    } else if ($prefix == 'logo') {
        $uploadDir = ROOT . '/' . $folder . '/dist/uploads/company/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); }
        $destPath = $uploadDir . 'companylogo.jpg';
        $newName = 'companylogo.jpg';
    }  else if ($prefix == 'signature') {
        $uploadDir = ROOT . '/' . $folder . '/dist/uploads/alban/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); }
        $destPath = $uploadDir . 'sign.png';
        $newName = 'sign.png';
    } else if ($prefix == 'stamp') {
        $uploadDir = ROOT . '/' . $folder . '/dist/uploads/alban/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); }
        $destPath = $uploadDir . 'tamga.png';
        $newName = 'tamga.png';
    } else if ($prefix == 'blank') {
        $uploadDir = ROOT . '/' . $folder . '/dist/uploads/alban/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); }
        $destPath = $uploadDir . 'blank1.jpg';
        $newName = 'blank1.jpg';
    } else if ($prefix == 'ztushaal') {
        $uploadDir = ROOT . '/' . $folder . '/dist/uploads/alban/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); }
        $destPath = $uploadDir . 'blank2.jpg';
        $newName = 'blank2.jpg';
    } else {
        echo json_encode(['success' => false, 'message' => "Тодорхойгүй зургийн төрөл. $prefix "]);
        exit;
    }
    //$newName  = $prefix . '_' . $userId . '_' . time() . '.' . $ext;
    //$destPath = $uploadDir . $newName;

    // ── Файл хадгалах ─────────────────────────────────────────
    if (move_uploaded_file($file['tmp_name'], $destPath)) {
        // Вэб замыг буцаана (ROOT-оос харьцангуй)
        $webPath = '/uploads/company_' . $userId . '/' . $newName;

        echo json_encode([
            'success'  => true,
            'name'     => $file['name'],
            'newName'  => $newName,
            'path'     => $webPath,
            'size'     => $file['size'],
            'mimeType' => $mimeType,
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Файл хадгалахад алдаа гарлаа. Хавтасны зөвшөөрөл шалгана уу.']);
    }
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Нэвтрээгүй байна.']);
    exit;
}