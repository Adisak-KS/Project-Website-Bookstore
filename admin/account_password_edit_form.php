<?php
$titlePage = "แก้ไขข้อมูลรหัสผ่าน";

require_once("../db/connectdb.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");
require_once("../db/controller/EmployeeController.php");

$EmployeeController = new EmployeeController($conn);

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id 
    $Id = decodeBase64ID($base64Encoded, $salt1, $salt2);
    $employees = $EmployeeController->getDetailEmployee($Id);

    //ตรวจสอบว่ามีข้อมูลหรือไม่
    checkResultDetail($employees);
} else {
    header('Location: account_show');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('layouts/head.php') ?>
</head>

<!-- body start -->

<body class="loading" data-layout-color="light" data-layout-mode="default" data-layout-size="fluid" data-topbar-color="light" data-leftbar-position="fixed" data-leftbar-color="light" data-leftbar-size='default' data-sidebar-user='true'>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- ========== Topbar ========== -->
        <?php require_once('layouts/nav_topbar.php') ?>

        <!-- ========== Left bar ========== -->
        <?php require_once('layouts/nav_leftbar.php') ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">
                    <form id="formEmployeeAccount" action="process/account_password_edit" method="post">
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>ข้อมูลชื่อผู้ใช้งาน</span>
                                        </h4>

                                        <input type="hidden" name="id" class="form-control" value="<?php echo $employees['emp_id']; ?>">

                                        <div class="mb-3">
                                            <label for="password" class="form-label">รหัสผ่านเดิม :</label><span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password" id="oldPassword" placeholder="ระบุ รหัสผ่านเดิม" maxlength="255">
                                                <button class="btn btn-outline-secondary password-toggle" type="button">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="confirmPassword" class="form-label">รหัสผ่านใหม่ :</label><span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="ระบุ รหัสผ่านใหม่" maxlength="255">
                                                <button class="btn btn-outline-secondary password-toggle" type="button">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="confirmPassword" class="form-label">ยืนยันรหัสผ่านใหม่ :</label><span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="confirmNewPassword" placeholder="ระบุ รหัสผ่านใหม่ อีกครั้ง" maxlength="255">
                                                <button class="btn btn-outline-secondary password-toggle" type="button">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $employees['emp_time_update'] ?></span></h4>
                                        <div>
                                            <a href="account_show" class="btn btn-secondary me-2">
                                                <i class="fa-solid fa-xmark me-1"></i>
                                                <span>ยกเลิก</span>
                                            </a>
                                            <button type="submit" name="btn-edit" class="btn btn-warning">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>บันทึกการแก้ไข</span>
                                            </button>
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <!-- end row -->

                        </div> <!-- container -->
                    </form>
                </div> <!-- content -->

                <?php require_once('layouts/nav_footer.php') ?>

            </div>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->

        <!-- ========== Right bar ========== -->
        <?php require_once('layouts/nav_rightbar.php') ?>

        <?php require_once('layouts/vendor.php') ?>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>