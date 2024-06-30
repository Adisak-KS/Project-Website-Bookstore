<?php
$titlePage = "บัญชีของฉัน";
require_once("../db/connectdb.php");

$originalId = $_SESSION["emp_id"];
require_once("../includes/salt.php");   // รหัส Salt 
$saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
$base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64

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

                    <div class="row">
                        <div class="col-xl-3 col-md-6 text-center">
                            <a href="account_profile_edit_form?id=<?php echo $base64Encoded ?>">
                                <div class="card">
                                    <div class="card-body widget-user">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <i class="fa-solid fa-user-gear fa-2xl mt-3"></i>
                                                <p class=" mt-2"><b>จัดการข้อมูลส่วนตัว</b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6 text-center">
                            <a href="account_acc_edit_form?id=<?php echo $base64Encoded ?>">
                                <div class="card">
                                    <div class="card-body widget-user">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <i class="fa-solid fa-envelope fa-2xl mt-3 text-success"></i>
                                                <p class="text-success mt-2"><b>จัดการข้อมูลบัญชี</b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div><!-- end col -->
                        <div class="col-xl-3 col-md-6 text-center">
                            <a href="account_password_edit_form?id=<?php echo $base64Encoded ?>">
                                <div class="card">
                                    <div class="card-body widget-user">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <i class="fa-solid fa-key fa-2xl mt-3 text-warning"></i>
                                                <p class="text-warning mt-2"><b>จัดการรหัสผ่าน</b></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div><!-- end col -->
                    </div>
                </div> <!-- container -->

            </div> <!-- content -->

            <?php require_once('./layouts/nav_footer.php') ?>

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