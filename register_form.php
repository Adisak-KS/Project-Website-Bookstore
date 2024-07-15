<?php
$titlePage = "เข้าสู่ระบบ";

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
                        <h3>สมัครสมาชิก</h3>
                    </div>
                </div>
                <div class="offset-lg-2 col-lg-8 col-md-12 col-12">
                    <div class="billing-fields">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label"><strong>ชื่อจริง :</strong><span class="text-danger">*</span></label>
                                    <input class="form-control" name="mem_fname" type="text" placeholder="กรุณาระบุ ชื่อจริง" maxlength="50">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="mb-3">
                                    <labe class="form-label"><strong>นามสกุล :</strong><span class="text-danger">*</span></labe>
                                    <input class="form-control" name="mem_lname" type="text" placeholder="กรุณาระบุ นามสกุล" maxlength="50">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label"><strong>ชื่อผู้ใช้งาน :</strong><span class="text-danger">*</span></label>
                                    <input class="form-control" name="mem_username" type="text" placeholder="กรุณาระบุ ชื่อผู้ใช้งาน" maxlength="50">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label"><strong>อีเมล :</strong><span class="text-danger">*</span></label>
                                    <input class="form-control" name="mem_email" type="text" placeholder="กรุณาระบุ อีเมล" maxlength="100">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="mb-3">
                                    <label for="password" class="form-label"><strong>รหัสผ่าน : </strong></label><span class="text-danger">*</span>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="mem_password" placeholder="ระบุ รหัสผ่าน" maxlength="255">
                                        <button class="btn btn-outline-secondary password-toggle" type="button">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="mb-3">
                                    <label for="password" class="form-label"><strong>ยืนยันรหัสผ่าน : </strong></label><span class="text-danger">*</span>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" placeholder="ระบุ รหัสผ่านอีกครั้ง" maxlength="255">
                                        <button class="btn btn-outline-secondary password-toggle" type="button">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="single-register">
                                <button type="submit" name="btn-register">
                                    <i class="fa-solid fa-user-plus me-1"></i>
                                    ยืนยันการสมัครสมาชิก
                                </button>
                            </div>

                            <div class="d-flex justify-content-between">

                                <p>หากมีบัญชีผู้ใช้แล้ว คุณสามารถเข้าสู่ระบบ<a href="login_form"> ที่นี่</a></p>

                            </div>
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