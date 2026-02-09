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
                 <div class="card">
                     <div class="card-body">
                         <h4 class="card-title">Компанийн мэдээлэл тохируулах</h4>
                         <h6 class="card-subtitle mt-1">Компанийн домэйн нэр, өгөгдлийн сан, лицензийн мэдээлэл тохируулах
                         </h6>
                         <form class="row p-3">
                             <div class="col-md-6 p-3">
                                 <label>Домэйн нэр</label>
                                 <input type="text" class="form-control">
                             </div>
                             <div class="col-md-6 p-3">
                                 <label>Өгөгдлийн сангийн нэр</label>
                                 <input type="text" class="form-control">
                             </div>
                             <div class="col-md-6 p-3">
                                 <label>Өгөгдлийн сангийн хэрэглэгчийн нэр</label>
                                 <input type="text" class="form-control">
                             </div>
                             <div class="col-md-6 p-3">
                                 <label>Өгөгдлийн сангийн нууц үг</label>
                                 <input type="text" class="form-control">
                             </div>
                             <div class="col-md-6 p-3">
                                 <label>Сервер дээрх хавтас</label>
                                 <input type="text" class="form-control">
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <?php
        require_once 'footer.php';
        ?>