<?php
$titlePage = "แก้ไขข้อมูลบัญชี";

require_once("../db/connectdb.php");
require_once("../db/controller/EmployeeController.php");
require_once("../includes/salt.php");
require_once("../admin/includes/functions.php");

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id 
    $Id = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $EmployeeController = new EmployeeController($conn);
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
                    <form id="formEmployeeAccount" action="process/account_acc_edit" method="post">
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
                                            <label for="username" class="form-label">ชื่อผู้ใช้เดิม :</label><span class="text-danger">*</span>
                                            <input type="hidden" name="old_username" id="old_username" class="form-control" value="<?php echo $employees['emp_username']; ?>">
                                            <p><?php echo $employees['emp_username']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">ชื่อผู้ใช้ใหม่ :</label><span class="text-danger">*</span>
                                            <input type="text" name="username" class="form-control" placeholder="ระบุ ชื่อผู้ใช้ใหม่" maxlength="50">
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>อีเมล</span>
                                        </h4>

                                        <input type="hidden" name="id" class="form-control" value="<?php echo $employees['emp_id']; ?>">

                                        <div class="mb-3">
                                            <label for="email" class="form-label">อีเมลเดิม :</label><span class="text-danger">*</span>
                                            <input type="hidden" name="old_email" id="old_email" class="form-control" value="<?php echo $employees['emp_email']; ?>">
                                            <p><?php echo $employees['emp_email']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">อีเมลใหม่ :</label><span class="text-danger">*</span>
                                            <input type="email" name="email" class="form-control" placeholder="ระบุ อีเมลใหม่" maxlength="50">
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <!-- end col -->

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

        <?php require_once('layouts/vender.php') ?>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>