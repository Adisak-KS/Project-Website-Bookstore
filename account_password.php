<?php
$titlePage = "จัดการรหัสผ่าน";

require_once("db/connectdb.php");

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header("Location: login_form");
    exit;
}
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="cart">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- Add your site or application content here -->

    <!-- header-area-start -->
    <?php require_once("layouts/nav_topbar.php"); ?>
    <!-- header-area-end -->


    <!-- breadcrumbs-area-start -->
    <div class="breadcrumbs-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs-menu">
                        <ul>
                            <li><a href="index">หน้าแรก</a></li>
                            <li><a href="javascript:void(0)" class="active">บัญชีของฉัน</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs-area-end -->

    <!-- entry-header-area-start -->
    <div class="entry-header-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header-title">
                        <h2>บัญชีของฉัน</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- entry-header-area-end -->

    <!-- my account wrapper start -->
    <div class="my-account-wrapper mb-70">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- My Account Page Start -->
                        <div class="myaccount-page-wrapper">
                            <!-- My Account Tab Menu Start -->
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <?php require_once("layouts/nav_menu_account.php") ?>
                                </div>
                                <!-- My Account Tab Menu End -->

                                <!-- My Account Tab Content Start -->
                                <div class="col-lg-9 col-md-8">
                                    <div class="tab-content" id="myaccountContent">

                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade show active" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h5>จัดการรหัสผ่าน</h5>
                                                <div class="account-details-form">
                                                    <form id="formUpdatePassword" action="process/account_password_edit.php" method="post">
                                                        <fieldset>
                                                            <input type="hidden" name="mem_id" value="<?php echo $_SESSION['mem_id'] ?>" readonly>
                                                            <div class="single-input-item mb-3">
                                                                <label for="current-pwd" class="form-label">รหัสผ่านปัจจุบัน</label>
                                                                <div class="input-group">
                                                                    <input type="password" name="password" id="password" class="form-control" placeholder="ระบุ รหัสผ่านปัจจุบัน" maxlength="255">
                                                                    <button class="btn btn-outline-secondary password-toggle" type="button">
                                                                        <i class="fas fa-eye-slash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="single-input-item mb-3">
                                                                        <label for="new-pwd" class="form-label">รหัสผ่านใหม่</label>
                                                                        <div class="input-group">
                                                                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="ระบุ รหัสผ่านใหม่" maxlength="255">
                                                                            <button class="btn btn-outline-secondary password-toggle" type="button">
                                                                                <i class="fas fa-eye-slash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="single-input-item mb-3">
                                                                        <label for="confirm-pwd" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                                                                        <div class="input-group">
                                                                            <input type="password" name="confirm_password" class="form-control" placeholder="ระบุ รหัสผ่านใหม่ อีกครั้ง" maxlength="255">
                                                                            <button class="btn btn-outline-secondary password-toggle" type="button">
                                                                                <i class="fas fa-eye-slash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <hr>
                                                        <div class="single-input-item">
                                                            <button type="submit" name="btn-edit" class="btn btn-sqr">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                                บันทึกการแก้ไข
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Single Tab Content End -->
                                </div>
                            </div> <!-- My Account Tab Content End -->
                        </div>
                    </div> <!-- My Account Page End -->
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- my account wrapper end -->

    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>