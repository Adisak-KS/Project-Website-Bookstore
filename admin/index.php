<?php
$titlePage = "หน้าแรก";
require_once("../db/connectdb.php");
require_once("../db/controller/SettingWebsiteController.php");
require_once("../db/controller/OrderController.php");
require_once("../db/controller/ProductController.php");
require_once("../db/controller/ProductRequestController.php");
require_once("../db/controller/ReviewController.php");


$SettingWebsiteController = new SettingWebsiteController($conn);
$OrderController = new OrderController($conn);
$ProductController = new ProductController($conn);
$ProductRequestController = new ProductRequestController($conn);
$ReviewController = new ReviewController($conn);

// order รอตรวจสอบการชำระเงิน
$amountOrderUnderReview = $OrderController->getAmountOrderStatusUnderReview();
$amountOrderAwaitingShipment = $OrderController->getAmountOrderStatusAwaitingShipment();

// หาหนังสือ
$amountProductRequest = $ProductRequestController->getAmountProductRequest();

// ความคิดเห็น
$amountReviewNotShowing = $ReviewController->getAmountReviewStatusNotShowing();

// สินค้าในคลัง
$QueryProductNumber = $SettingWebsiteController->getProductNumberLow();
$prdNumberLow = $QueryProductNumber;

$productsLowNumber = $ProductController->getProductLowNumber($prdNumberLow);

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

                        <div class="col-xl-3 col-md-6">
                            <a href="order_show">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="header-title mt-0 mb-3">รอตรวจสอบชำระเงิน</h4>

                                        <div class="widget-box-2">
                                            <div class="widget-detail-2 text-center">
                                                <h2 class="fw-normal mb-1 text-center"> <i class="fa-solid fa-cart-shopping"></i> </h2>
                                                <?php if ($amountOrderUnderReview) { ?>
                                                    <span class="badge bg-danger rounded-pill mt-2"><?php echo number_format($amountOrderUnderReview) ?> รายการ</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-secondary rounded-pill mt-2"><?php echo number_format($amountOrderUnderReview) ?> รายการ</span>
                                                <?php } ?>
                                            </div>
                                            <div class="progress progress-bar-alt-pink progress-sm">
                                                <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                                    <span class="visually-hidden">77% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6">
                            <a href="order_awaiting_shipment_show">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="header-title mt-0 mb-3">รอการจัดส่ง</h4>

                                        <div class="widget-box-2">
                                            <div class="widget-detail-2 text-center">

                                                <h2 class="fw-normal mb-1 text-center"> <i class="fa-solid fa-truck-fast"></i> </h2>
                                                <?php if ($amountOrderAwaitingShipment) { ?>
                                                    <span class="badge bg-danger rounded-pill mt-2"><?php echo number_format($amountOrderAwaitingShipment) ?> รายการ</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-secondary rounded-pill mt-2"><?php echo number_format($amountOrderAwaitingShipment) ?> รายการ</span>
                                                <?php } ?>
                                            </div>
                                            <div class="progress progress-bar-alt-pink progress-sm">
                                                <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                                    <span class="visually-hidden">77% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6">
                            <a href="product_request_show">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="header-title mt-0 mb-3">หาหนังสือ</h4>

                                        <div class="widget-box-2">
                                            <div class="widget-detail-2 text-center">
                                                <h2 class="fw-normal mb-1 text-center">
                                                    <i class="fa-solid fa-book"></i>
                                                </h2>

                                                <?php if ($amountProductRequest) { ?>
                                                    <span class="badge bg-danger rounded-pill mt-2"><?php echo number_format($amountProductRequest) ?> รายการ</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-secondary rounded-pill mt-2"><?php echo number_format($amountProductRequest) ?> รายการ</span>
                                                <?php } ?>
                                            </div>
                                            <div class="progress progress-bar-alt-pink progress-sm">
                                                <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                                    <span class="visually-hidden">77% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6">
                            <a href="review_show">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="header-title mt-0 mb-3">ความคิดเห็นรอตรวจสอบ</h4>

                                        <div class="widget-box-2">
                                            <div class="widget-detail-2 text-center">

                                                <h2 class="fw-normal mb-1 text-center"> <i class="fa-solid fa-comment-dots"></i> </h2>
                                                <?php if ($amountReviewNotShowing) { ?>
                                                    <span class="badge bg-danger rounded-pill mt-2"><?php echo number_format($amountReviewNotShowing) ?> รายการ</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-secondary rounded-pill mt-2"><?php echo number_format($amountReviewNotShowing) ?> รายการ</span>
                                                <?php } ?>
                                            </div>
                                            <div class="progress progress-bar-alt-pink progress-sm">
                                                <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                                                    <span class="visually-hidden">77% Complete</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div><!-- end col -->

                        <div class="col-xl-3 col-md-6">
                            <a href="product_stock_show">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h4 class="header-title mt-0 mb-3">สินค้าที่น้อยกว่า <?php echo number_format($prdNumberLow) ?> รายการ</h4>

                                        <div class="widget-box-2">
                                            <div class="widget-detail-2 text-center">
                                                <h2 class="fw-normal mb-1 text-center"> <i class="fa-solid fa-arrow-trend-down"></i> </h2>

                                                <?php if ($productsLowNumber) { ?>
                                                    <span class="badge bg-danger rounded-pill mt-2"><?php echo number_format($productsLowNumber) ?> รายการ</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-secondary rounded-pill mt-2"><?php echo number_format($productsLowNumber) ?> รายการ</span>
                                                <?php } ?>
                                            </div>
                                            <div class="progress progress-bar-alt-pink progress-sm">
                                                <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#">
                        </div><!-- end col -->

                    </div>
                    <!-- end row -->
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



    <?php require_once('layouts/vendor.php') ?>

</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>