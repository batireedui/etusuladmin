<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon.png">
    <title>Admin</title>
    <link href="/dist/css/style.min.css" rel="stylesheet">
</head>

<body>
    <div class="main-wrapper">
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
            style="background:url(/assets/images/big/auth-bg.jpg) no-repeat center center;">
            <div class="auth-box row">
                <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url(/images/back.jpg);">
                </div>
                <div class="col-lg-5 col-md-7 bg-white">
                    <div class="p-3">
                        <div class="text-center">
                            <img src="/images/logo.png" alt="wrapkit" width="80">
                        </div>
                        <h2 class="mt-3 text-center">ETUSUL</h2>
                        <form class="mt-4" action="sign-in.php" method="post">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="uname">Хэрэглэгчийн нэр</label>
                                        <input class="form-control" id="uname" name="email" type="text">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-dark" for="pwd">Нууц үг</label>
                                        <input class="form-control" id="pwd" name="password" type="password">
                                    </div>
                                </div>
                                <?php if (isset($_SESSION['errors'])): ?>
                                 <div class="col-lg-12">
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        
                                    </div>
                                </div>
                                <?php unset($_SESSION['errors']); ?>
                                <?php endif; ?>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-block btn-dark">Нэвтрэх</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/libs/jquery/dist/jquery.min.js "></script>
    <script src="/assets/libs/popper.js/dist/umd/popper.min.js "></script>
    <script src="/assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
</body>

</html>