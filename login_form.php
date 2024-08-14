<?php
$titlePage = "เข้าสู่ระบบ";
require_once('db/connectdb.php');


if (!empty($_SESSION['emp_id'])) {
    header('Location: index');
    exit;
}

?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="login">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- Add your site or application content here -->
    <!-- header-area-start -->
    <?php require_once("layouts/nav_topbar.php"); ?>
    <!-- header-area-end -->

    <!-- user-login-area-start -->
    <div class="user-login-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="login-title text-center mb-30 mt-3">
                        <h3>เข้าสู่ระบบ</h3>
                    </div>
                </div>
                <div class="offset-lg-3 col-lg-6 col-md-12 col-12">
                    <div class="login-form">
                        <form id="formLogin" action="process/login_check.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label"><strong>ชื่อผู้ใช้งาน หรือ อีเมล :</strong> </label><span class="text-danger">*</span>
                                <input class="form-control" name="username_email" type="text" placeholder="กรุณาระบุ ชื่อผู้ใช้ หรือ อีเมล" maxlength="100">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><strong>รหัสผ่าน : </strong></label><span class="text-danger">*</span>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" placeholder="ระบุ รหัสผ่าน" maxlength="255">
                                    <button class="btn btn-outline-secondary password-toggle" type="button">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="single-login single-login-2">
                                <button type="submit" name="btn-login">
                                    <i class="fa-solid fa-right-to-bracket me-1"></i>
                                    เข้าสู่ระบบ
                                </button>
                            </div>
                        </form>

                        <div class="d-flex justify-content-between">
                            <a href="#">ลืมรหัสผ่าน?</a>
                            <a href="register_form">สมาชิกใหม่? สมัครสมาชิก ที่นี่</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- user-login-area-end -->


    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>