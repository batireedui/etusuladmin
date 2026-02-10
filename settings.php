<?php
require_once 'header.php';
require_once 'sidebar.php';
$comID = $_GET['comID'] ?? null;
$info = $_GET['info'] ?? null;

if (!isset($comID) || $comID == '') {
    header('Location: index.php');
    exit();
}
$statusArr = ['paid' => 'Идэвхтэй', 'registered' => 'Бүртгүүлсэн', 'expired' => 'Хугацаа дууссан'];

_selectRowNoParam(
    "SELECT comName, RD, phone, ognoo, status, domainName,
                dbname, dbuser, dbpass, folder FROM company WHERE comID = $comID",
    $comName,
    $RD,
    $phone,
    $ognoo,
    $status,
    $domainName,
    $dbname,
    $dbuser,
    $dbpass,
    $folder
);
function checkDB($dbuser, $dbpass, $dbname)
{
    if (empty($dbuser) || empty($dbname)) {
        return false;
    }

    $connCheck = @new mysqli('localhost', $dbuser, $dbpass, $dbname);

    if ($connCheck->connect_error) {
        return false;
    }

    $connCheck->close();
    return true;
}

_selectNoParam(
    $sstt,
    $cctt,
    "SELECT id, name, query FROM table_list",
    $staticTablesID,
    $staticTablesName,
    $staticTablesQuery
);

$staticTables = [];
while (_fetch($sstt)) {
    array_push($staticTables, [
        'id' => $staticTablesID,
        'name' => $staticTablesName,
        'query' => $staticTablesQuery
    ]);
};

/*
Файлуудын хамт харуулах
function folderTreeHtml($dir)
{
    if (!is_dir($dir)) return;
    echo "<ul>";
    $items = array_diff(scandir($dir), ['.', '..']);
    foreach ($items as $item) {
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        // Давхар шалгалт
        if (!file_exists($path)) continue;
        echo "<li>";
        if (is_dir($path)) {
            echo "📁 " . htmlspecialchars($item);
            folderTreeHtml($path);
        } else {
            echo "📄 " . htmlspecialchars($item);
        }
        echo "</li>";
    }
    echo "</ul>";
}
*/

//Зөвхөн хавтас харуулах
function folderTreeHtml($dir)
{
    if (!is_dir($dir)) return;
    echo "<ul>";
    foreach (array_diff(scandir($dir), ['.', '..']) as $item) {
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        // Зөвхөн folder үед л харуулна
        if (is_dir($path)) {
            echo "<li>";
            echo "📁 " . htmlspecialchars($item);
            // Recursive call
            folderTreeHtml($path);
            echo "</li>";
        }
    }
    echo "</ul>";
}
?>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1><?= $comName ?> </h1>
                        <h4 class="card-title">Компанийн мэдээлэл тохируулах</h4>
                        <h6 class="card-subtitle mt-1">Компанийн домэйн нэр, өгөгдлийн сан, лицензийн мэдээлэл тохируулах
                        </h6>
                        <?php
                        if (!checkDB($dbuser, $dbpass, $dbname)) { ?>
                            <div class="alert alert-danger" role="alert">
                                Домэйн нэр, өгөгдлийн сангийн мэдээлэл зөв байх шаардлагатай. Домэйн нэр болон өгөгдлийн сангийн мэдээлэл буруу бол систем ажиллахгүй болно.
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-success" role="alert">
                                Домэйн нэр, өгөгдлийн сангийн мэдээлэл зөв байна. Систем хэвийн ажиллах боломжтой.
                            </div>
                        <?php }

                        if ($info !== null) { ?>
                            <div class="alert alert-info" role="alert">
                                <?= $info ?>
                            </div>
                        <?php } ?>
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Үндсэн мэдээлэл</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#db_tree" data-toggle="tab" aria-expanded="true" class="nav-link">
                                    <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Өгөгдлийн сангийн бүтэц</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#folder_tree" data-toggle="tab" aria-expanded="false" class="nav-link">
                                    <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Хавтасны бүтэц</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="home">
                                <form class="row p-3" action="action.php" method="POST">
                                    <div class="col-md-6 p-2">
                                        <input type="hidden" name="comID" value="<?= $comID ?>">
                                        <label>Домэйн нэр</label>
                                        <input type="text" value="<?= $domainName ?>" name="domainName" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 p-2">
                                        <label>Өгөгдлийн сангийн нэр</label>
                                        <input type="text" value="<?= $dbname ?>" name="dbname" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 p-2">
                                        <label>Өгөгдлийн сангийн хэрэглэгчийн нэр</label>
                                        <input type="text" value="<?= $dbuser ?>" name="dbuser" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 p-2">
                                        <label>Өгөгдлийн сангийн нууц үг</label>
                                        <input type="text" value="******" name="dbpass" class="form-control" required>
                                    </div>
                                    <div class="col-md-12 p-2 mt-3">
                                        <input type="submit" value="Хадгалах" name="mainData" class="btn btn-primary w-100">
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="db_tree">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Хүснэгтийн нэр</th>
                                            <th>Мөрийн тоо</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (checkDB($dbuser, $dbpass, $dbname)) {
                                            $rowNum = 1;
                                            $connCheck = new mysqli('localhost', $dbuser, $dbpass, $dbname);
                                            $tables = $connCheck->query("SHOW TABLES");
                                            while ($table = $tables->fetch_array()) {
                                                $tableName = $table[0];
                                                $result = $connCheck->query("SELECT COUNT(*) AS count FROM $tableName");
                                                $row = $result->fetch_assoc();

                                                foreach ($staticTables as $staticTable) {
                                                    if ($staticTable['name'] == $tableName) {
                                                        unset($staticTables[array_search($staticTable, $staticTables)]);
                                                    }
                                                } ?>
                                                <tr>
                                                    <td><?= $rowNum++ ?></td>
                                                    <td><?= $tableName ?></td>
                                                    <td><?= $row['count'] ?></td>
                                                </tr>
                                            <?php
                                            }
                                            $connCheck->close();
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="2">Өгөгдлийн сан руу холбогдож чадсангүй.</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Үүсээгүй системийн хүснэгтийн нэр</th>
                                            <th>Үйлдэл</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($staticTables as $index => $staticTable) { ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= $staticTable['name'] ?></td>
                                                <td>
                                                    <form id="createTableForm_<?= $staticTable['id'] ?>" method="POST" action="action.php">
                                                        <input type="hidden" name="comID" value="<?= $comID ?>">
                                                        <input type="hidden" name="tableID" value="<?= $staticTable['id'] ?>">
                                                        <input type="hidden" name="tableName" value="<?= $staticTable['name'] ?>">
                                                        <button class="btn btn-sm btn-danger" type="submit" name="createTable">Үүсгэх</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="folder_tree">
                                <form class="row p-3" action="action.php" method="POST">
                                    <div class="col-md-6 p-2">
                                        <input type="hidden" name="comID" value="<?= $comID ?>">
                                        <input type="text" value="<?= $folder ?>" name="folderName" placeholder="Сервер дээрх хавтасны нэр" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 p-2">
                                        <input type="submit" value="Хадгалах" name="folderSetup" class="btn btn-primary w-100">
                                    </div>
                                </form>

                                <?php
                                if (isset($folder) && $folder != '') {
                                    $fullPath = ROOT . '/' . $folder;
                                    if (is_dir($fullPath)) {
                                        folderTreeHtml($fullPath);
                                    } else {
                                        echo "<div class='alert alert-warning'>Хавтас олдсонгүй.</div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-warning'>Хавтас тохируулна уу.</div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once 'footer.php';
    ?>