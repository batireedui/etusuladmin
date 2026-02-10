<?php
require_once 'inc.php';
if (isset($_SESSION['userid'])) {
    if (isset($_POST['mainData'])) {
        $domainName = $_POST['domainName'] ?? null;
        $dbname = $_POST['dbname'] ?? null;
        $dbuser = $_POST['dbuser'] ?? null;
        $dbpass = $_POST['dbpass'] ?? null;
        $comID = $_POST['comID'] ?? null;
        if (!isset($comID)) {
            header('Location: settings.php?comID=' . $comID . '&info=Компанийн мэдээлэл олдсонгүй.');
            exit();
        }
        if ($dbpass !== '******') {
            $query = "UPDATE company SET domainName = ?, dbname = ?, dbuser = ?, dbpass = ? WHERE comID = ?";
            _exec(
                $query,
                "ssssi",
                [
                    $domainName,
                    $dbname,
                    $dbuser,
                    $dbpass,
                    $comID
                ],
                $count
            );
        } else {
            $query = "UPDATE company SET domainName = ?, dbname = ?, dbuser = ? WHERE comID = ?";
            _exec(
                $query,
                "sssi",
                [
                    $domainName,
                    $dbname,
                    $dbuser,
                    $comID
                ],
                $count
            );
        }
        header('Location: settings.php?comID=' . $comID . '&info=Мэдээлэл амжилттай хадгалагдлаа.');
        exit();
    } else if (isset($_POST['createTable'])) {
        $tableName = $_POST['tableName'] ?? null;
        $comID = $_POST['comID'] ?? null;
        $tableID = $_POST['tableID'] ?? null;
        if (empty($comID)) {
            header('Location: settings.php?comID=' . $comID . '&info=Компанийн мэдээлэл олдсонгүй.');
            exit();
        }
        _selectRowNoParam(
            "SELECT dbname, dbuser, dbpass FROM company WHERE comID = $comID",
            $dbname,
            $dbuser,
            $dbpass
        );
        $connCheck = @new mysqli('localhost', $dbuser, $dbpass, $dbname);

        if ($connCheck->connect_error) {
            header('Location: settings.php?comID=' . $comID . '&info=Өгөгдлийн сангийн холболт амжилтгүй болсон.');
            exit();
        }

        _selectRowNoParam(
            "SELECT query FROM table_list WHERE id = $tableID AND name = '$tableName'",
            $staticTablesQuery
        );

        if (empty($staticTablesQuery)) {
            header('Location: settings.php?comID=' . $comID . '&info=Хүснэгтийн мэдээлэл олдсонгүй.');
            exit();
        }

        if ($connCheck->query($staticTablesQuery) === FALSE) {
            header('Location: settings.php?comID=' . $comID . '&info=Хүснэгт үүсгэх явцад алдаа гарлаа.');
            exit();
        }
        $connCheck->close();

        header('Location: settings.php?comID=' . $comID . '&info=Хүснэгт амжилттай үүслээ.');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
