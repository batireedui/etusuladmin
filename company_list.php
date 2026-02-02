 <?php
    require_once 'header.php';
    require_once 'sidebar.php';
    ?>
 <div class="page-wrapper">
     <!-- ============================================================== -->
     <!-- Bread crumb and right sidebar toggle -->
     <!-- ============================================================== -->
     <div class="container-fluid">
         <div class="row">
             <div class="col-md-12">
                 <h4 class="page-title">Компанийн жагсаалт</h4>
                 <div class="table-responsive">
                     <table class="table table-striped table-bordered" id="companyTable">
                         <thead>
                             <tr>
                                 <th>№</th>
                                 <th>Компанийн нэр</th>
                                 <th>РД</th>
                                 <th>Утасны дугаар</th>
                                 <th>Бүртгэсэн огноо</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php
                                $query = "SELECT comID, comName, RD, phone, ognoo FROM company ORDER BY comID DESC";
                                _selectNoParam(
                                    $stmt,
                                    $count,
                                    $query,
                                    $comID,
                                    $comName,
                                    $RD,
                                    $phone,
                                    $ognoo
                                );
                                $count = 1;
                                while (_fetch($stmt)) {
                                    echo "<tr>";
                                    echo "<td>" . $count++ . "</td>";
                                    echo "<td>" . $comName . "</td>";
                                    echo "<td>" . $RD . "</td>";
                                    echo "<td>" . $phone . "</td>";
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