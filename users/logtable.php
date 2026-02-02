<?php
require_once __DIR__ . '/includes/auth.php';
if (isset($_POST['turul']) && isset($_POST['rd']) && isset($_POST['phone']) && isset($_POST['action'])) {
    logTable($_POST['turul'], $_POST['rd'], $_POST['phone'], $_POST['action']);
    echo json_encode(['status' => 200, 'msg' => 'Log recorded']);
}
else {
    echo json_encode(['status' => 500, 'msg' => 'Invalid parameters']);
}
