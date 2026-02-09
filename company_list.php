 <?php
    require_once 'header.php';
    require_once 'sidebar.php';
    $statusArr = ['paid' => 'Идэвхтэй', 'registered' => 'Бүртгүүлсэн', 'expired' => 'Хугацаа дууссан'];
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
                                 <th>Төлөв</th>
                                 <th>Үйлдэл</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php
                                $query = "SELECT comID, comName, RD, phone, ognoo, status, domainName FROM company ORDER BY comID DESC";
                                _selectNoParam(
                                    $stmt,
                                    $count,
                                    $query,
                                    $comID,
                                    $comName,
                                    $RD,
                                    $phone,
                                    $ognoo,
                                    $status,
                                    $domainName
                                );
                                $count = 1;
                                while (_fetch($stmt)) { ?>
                             <tr>
                                 <td>
                                     <?php echo $count++; ?>
                                 </td>
                                 <td>
                                     <?php echo $comName; ?>
                                     <br>
                                     <span class="font-12 text-nowrap d-block ">
                                         <?php 
                                             if ($domainName == null) {
                                                 $domainName = 'Домэйн нэр байхгүй';
                                             }
                                             else {
                                                 $domainName = '<a href="' . $domainName . '" target="_blank">' . $domainName . '</a>';
                                             }
                                             echo $domainName; ?>
                                     </span>
                                 </td>
                                 <td><?php echo $RD; ?></td>
                                 <td><?php echo $phone; ?></td>
                                 <td><?php echo $ognoo; ?></td>
                                 <td>
                                     <span
                                         class="badge bg-<?php echo $status == 'paid' ? 'success' : ($status == 'registered' ? 'warning' : 'danger'); ?> font-12 text-white font-weight-medium badge-pill border-0"><?php echo $statusArr[$status]; ?></span>
                                 <td class="text-center">
                                     <a href="settings.php?comID=<?php echo $comID; ?>">
                                         <button
                                             class="btn btn-sm waves-effect waves-light btn-rounded btn-primary border-0">Тохиргоо</button>
                                     </a>
                                 </td>
                             </tr>
                             <?php
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