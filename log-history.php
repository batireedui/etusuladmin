 <?php
    require_once 'header.php';
    require_once 'sidebar.php';
    $logArray = ['login' => 'Нэвтрэх', 'register' => 'Бүртгүүлэх', 'check_reg' => 'РД шалгах', 'AdminLogin' => 'Админ нэвтрэх'];
    
    ?>
 <div class="page-wrapper">
     <!-- ============================================================== -->
     <!-- Bread crumb and right sidebar toggle -->
     <!-- ============================================================== -->
     <div class="container-fluid">
         <div class="row">
             <div class="col-md-12">
                 <h4 class="page-title">Үйлдлийн түүх</h4>
                 <div class="table-responsive">
                     <table class="table table-striped table-bordered" id="companyTable">
                         <thead>
                             <tr>
                                 <th>№</th>
                                 <th>Төрөл</th>
                                 <th>РД</th>
                                 <th>Утасны дугаар</th>
                                 <th>Үйлдэл</th>
                                 <th>Огноо</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php
                                $query = "SELECT turul, rd, phone, ognoo, action FROM logtable ORDER BY ognoo DESC";
                                _selectNoParam(
                                    $stmt,
                                    $count,
                                    $query,
                                    $turul, $rd, $phone, $ognoo, $action
                                );
                                $count = 1;
                                while (_fetch($stmt)) {
                                    echo "<tr>";
                                    echo "<td>" . $count++ . "</td>";
                                    echo "<td>" . $logArray[$turul] . "</td>";
                                    echo "<td>" . $rd . "</td>";
                                    echo "<td>" . $phone . "</td>";
                                    echo "<td>" . $action . "</td>";
                                    echo "<td>" . $ognoo . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </div>
     <?php
        require_once 'footer.php';
        ?>