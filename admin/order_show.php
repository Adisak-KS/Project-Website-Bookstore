<?php
$titlePage = "รายการสั่งซื้อที่รอตรวจสอบ";

require_once("../db/connectdb.php");
require_once("includes/salt.php");
require_once("includes/functions.php");
require_once("../db/controller/OrderController.php");


$OrderController = new OrderController($conn);

$orderStatusUnderReview = $OrderController->getOrderStatusUnderReview();


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
                                    <h4 class="mt-0 header-title">ข้อมูลรายการสั่งซื้อที่รอตรวจสอบการชำระเงิน</h4>
                                    <hr>
                                    <?php if ($orderStatusUnderReview) { ?>
                                        <table id="MyTable" class="table  table-bordered table-hover dt-responsive table-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">วัน /เวลา</th>
                                                    <th class="text-center">ผู้ซื้อ</th>
                                                    <th class="text-start">จำนวนเงิน</th>
                                                    <th class="text-center">สถานะ</th>
                                                    <th>จัดการข้อมูล</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($orderStatusUnderReview as $row) { ?>
                                                    <tr>
                                                        <td class="text-start"><?php echo $row['ord_time_update']; ?></td>
                                                        <td class="text-start">
                                                            <?php echo $row['mem_username']; ?>
                                                        </td>
                                                        <td class="text-start">
                                                            <?php echo "฿" . number_format($row['ord_price'], 2); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($row['ord_status'] == "Under Review") { ?>
                                                                <span class="badge rounded-pill bg-warning fs-6">รอตรวจสอบ</span>
                                                            <?php } else { ?>
                                                                <span class="badge rounded-pill bg-danger fs-6">ไม่พบสถานะ</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $originalId = $row["ord_id"];
                                                            $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                            ?>


                                                            <a href="order_detail?id=<?php echo $base64Encoded ?>" class="btn btn-info">
                                                                <i class="fa-solid fa-eye me-1"></i>
                                                                <span>รายละเอียด</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <?php require_once("./includes/no_information.php") ?>
                                    <?php } ?>
                                    <a href="index" class="btn btn-secondary">
                                        <i class="fa-solid fa-right-from-bracket fa-rotate-180 me-1"></i>
                                        กลับหน้าแรก
                                    </a>
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