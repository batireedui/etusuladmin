<?php
require_once 'inc.php';
if (isset($_SESSION['userid'])) {
    if (isset($_POST['mainData'])) {
        $domainName = $_POST['domainName'] ?? null;
        $dbname = $_POST['dbname'] ?? null;
        $dbuser = $_POST['dbuser'] ?? null;
        $dbpass = $_POST['dbpass'] ?? null;
        $folder = $_POST['folder'] ?? null;
        $comID = $_POST['comID'] ?? null;
        if (!isset($comID)) {
            header('Location: index.php');
            exit();
        }
        if ($dbpass !== '******') {
            $query = "UPDATE company SET domainName = ?, dbname = ?, dbuser = ?, dbpass = ?, folder = ? WHERE comID = ?";
            _exec(
                $query,
                "sssssi",
                [
                    $domainName,
                    $dbname,
                    $dbuser,
                    $dbpass,
                    $folder,
                    $comID
                ],
                $count
            );
        } else {
            $query = "UPDATE company SET domainName = ?, dbname = ?, dbuser = ?, folder = ? WHERE comID = ?";
            _exec(
                $query,
                "ssssi",
                [
                    $domainName,
                    $dbname,
                    $dbuser,
                    $folder,
                    $comID
                ],
                $count
            );
        }
        header('Location: settings.php?comID=' . $comID);
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
