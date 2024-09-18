<?php
$titlePage = "สิทธิ์พนักงานในระบบ";
//

require_once("../db/connectdb.php");
require_once("../db/controller/AuthorityTypeController.php");
require_once('../db/controller/LoginController.php');

$LoginController = new LoginController($conn);

$AuthorityTypeController = new AuthorityTypeController($conn);
$authorityType = $AuthorityTypeController->getAuthorityTypeEmployees();

$empId = $_SESSION['emp_id'];

// ตรวจสอบสิทธิ์การใช้งาน
$useAuthority = $LoginController->useLoginEmployees($empId);
$allowedAuthorities = [1]; // Super Admin
checkAuthorityEmployees($useAuthority, $allowedAuthorities)

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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mt-0 header-title">ข้อมูลประเภทสิทธิ์พนักงานทั้งหมด</h4>
                                    <hr>
                                    <?php if ($authorityType) { ?>
                                        <table id="MyTable" class="table  table-bordered table-hover dt-responsive table-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-start">ชื่อ</th>
                                                    <th class="text-start">รายละเอียด</th>
                                                    <th class="text-center">สถานะ</th>
                                                    <th class="text-center">วัน เวลา</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($authorityType as $row) { ?>
                                                    <tr>
                                                        <td class="text-start"><?php echo $row['eat_id']; ?></td>
                                                        <td class="text-start"><?php echo $row['eat_name']; ?></td>
                                                        <td class="text-start"><?php echo $row['eat_detail']; ?></td>
                                                        <td class="text-center">
                                                            <?php if ($row['eat_status'] == 1) { ?>
                                                                <span class="badge rounded-pill bg-success fs-6">ใช้งานได้</span>
                                                            <?php } else { ?>
                                                                <span class="badge rounded-pill bg-danger fs-6">ระงับการใช้งาน</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-start"><?php echo $row['eat_time']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <?php require_once("./includes/no_information.php") ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                </div> <!-- container -->

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