<?php
$titlePage = "รายละเอียดรายการสั่งซื้อ";

require_once("db/connectdb.php");
require_once("db/controller/OrderController.php");
require_once("db/controller/ReviewController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$OrderController = new OrderController($conn);
$ReviewController = new ReviewController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header("Location: login_form");
    exit;
} else {
    $memId = $_SESSION['mem_id'];

    if (isset($_GET['id'])) {
        $_SESSION["base64Encoded"] = $_GET["id"];
        $base64Encoded =  $_SESSION["base64Encoded"];

        // ถอดรหัส Id
        $ordId = decodeBase64ID($base64Encoded, $salt1, $salt2);

        $order = $OrderController->getAccountOrderDetail($ordId, $memId);
        if ($order) {
            $orderItems = $OrderController->getOrderHistoryItemDetail($ordId);
            $orderPromotion = $OrderController->getOrderHistoryPromotionDetail($ordId);
            $orderShipping = $OrderController->getOrderHistoryShippingDetail($ordId);
            $orderAddress = $OrderController->getOrderHistoryAddressDetail($ordId);
            $orderPayment = $OrderController->getOrderHistoryPaymentDetail($ordId);
            $orderSlip = $OrderController->getOrderHistorySlipDetail($ordId);
        } else {
            header("Location:account_order_history");
            exit;
        }
    } else {
        header('Location:account_order_history');
        exit;
    }
}


?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
    <link href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/searchpanes/2.3.1/css/searchPanes.bootstrap5.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.4/jquery.rateyo.min.css" integrity="sha512-JEUoTOcC35/ovhE1389S9NxeGcVLIqOAEzlpcJujvyUaxvIXJN9VxPX0x1TwSo22jCxz2fHQPS1de8NgUyg+nA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.4/jquery.rateyo.min.js" integrity="sha512-09bUVOnphTvb854qSgkpY/UGKLW9w7ISXGrN0FR/QdXTkjs0D+EfMFMTB+CGiIYvBoFXexYwGUD5FD8xVU89mw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


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
                                        <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                            <div class="myaccount-content">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5>รายละเอียดรายการสั่งซื้อที่ : <?php echo $order['ord_id'] ?></h5>
                                                    <p class="text-dark"><strong>วัน เวลา :</strong> <?php echo $order['ord_time_create'] ?> </p>
                                                </div>


                                                <p class="text-dark">
                                                    <strong>สถานะรายการ :</strong>
                                                    <?php
                                                    if ($order['ord_status'] == "Pending Payment") {
                                                        echo '<span class="badge text-warning" style="font-size:16px">รอชำระเงิน</span>';
                                                    } elseif ($order['ord_status'] == "Under Review") {
                                                        echo '<span class="text-secondary" style="font-size:16px">รอตรวจสอบ</span>';
                                                    } elseif ($order['ord_status'] == "Payment Retry") {
                                                        echo '<span class="text-warning" style="font-size:16px">ชำระเงินใหม่</span>';
                                                    } elseif ($order['ord_status'] == "Awaiting Shipment") {
                                                        echo '<span class="text-info" style="font-size:16px">รอจัดส่ง</span>';
                                                    } elseif ($order['ord_status'] == "Shipped") {
                                                        echo '<span class="text-primary" style="font-size:16px">จัดส่งแล้ว</span>';
                                                    } elseif ($order['ord_status'] == "Completed") {
                                                        echo '<span class="text-success" style="font-size:16px">สำเร็จ</span>';
                                                    } elseif ($order['ord_status'] == "Cancelled") {
                                                        echo '<span class="text-danger" style="font-size:16px">ยกเลิกแล้ว</span>';
                                                    } else {
                                                        echo '<span class="text-secondary">สถานะไม่ถูกต้อง</span>';
                                                    }
                                                    ?>
                                                </p>

                                                <?php if ($order['ord_tracking_number']) { ?>
                                                    <p>หมายเลขติดตามสินค้า : <a href=""><?php echo $order['ord_tracking_number'] ?></a></p>
                                                <?php } ?>

                                                <table class="table table-responsive table-striped table-bordered">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th class="product-thumbnail">รูป</th>
                                                            <th class="product-name">ชื่อ</th>
                                                            <th class="product-price">ราคา</th>
                                                            <th class="product-quantity">จำนวน</th>
                                                            <th class="product-subtotal">เหรียญที่ได้</th>
                                                            <th class="product-subtotal">รวม</th>
                                                            <?php if ($order['ord_status'] == 'Completed') { ?>
                                                                <th class="product-subtotal" style="border-right: none;">รีวิวสินค้า</th>
                                                            <?php } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-center">
                                                        <?php
                                                        $totalPriceAllProduct = 0;
                                                        ?>
                                                        <?php foreach ($orderItems as $row) { ?>
                                                            <tr>
                                                                <?php
                                                                $originalId = $row["prd_id"];
                                                                $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                                                $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64

                                                                $totalPriceAllProduct += $row['total_price_sale'];
                                                                ?>

                                                                <td class="product-thumbnail">
                                                                    <a href="product_detail?id=<?php echo $base64Encoded; ?>">
                                                                        <?php if (empty($row['prd_id'])) { ?>
                                                                            <img src="uploads/img_product/default.png" style="height:80px; width:60px; object-fit: cover;" alt="man" />
                                                                        <?php } else { ?>
                                                                            <img src="uploads/img_product/<?php echo $row['prd_img1'] ?>" style="height:80px; width:60px; object-fit: cover;" alt="man" />
                                                                        <?php } ?>
                                                                    </a>
                                                                </td>
                                                                <td class="product-name text-start">
                                                                    <?php if ($row['prd_preorder'] == 0) { ?>
                                                                        <a href="product_detail?id=<?php echo $base64Encoded; ?>"><?php echo "<span class='text-warning'>[พรีออเดอร์] </span>" . $row['oit_name'] ?></a>
                                                                    <?php } else { ?>
                                                                        <a href="product_detail?id=<?php echo $base64Encoded; ?>"><?php echo $row['oit_name'] ?></a>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="product-price">
                                                                    <span class="amount"><?php echo "฿" . number_format($row['price_sale'], 2) ?></span>
                                                                    <?php if ($row['oit_percent_discount'] > 0) { ?>
                                                                        <span class="text-danger"><sup><?php echo "-" . number_format($row['oit_percent_discount']) . "%" ?></sup></span>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class="product-quantity">
                                                                    <?php echo $row['oit_quantity'] ?>
                                                                </td>
                                                                <td class="product-subtotal coin"><?php echo number_format($row['coins_per_item']); ?></td>
                                                                <td class="product-subtotal total-sum"><?php echo "฿" . number_format($row['total_price_sale'], 2) ?></td>

                                                                <?php if ($order['ord_status'] == 'Completed') {
                                                                    // ตรวจสอบว่ามี prd_id, ord_id, mem_id หรือไม่ และเช็กว่ามีรีวิวไหม
                                                                    $prdId = $row['prd_id'];
                                                                    $ordId = $order['ord_id'];
                                                                    $memId = $order['mem_id'];

                                                                    $hasReview = $ReviewController->checkAccountOrderReview($prdId, $ordId, $memId);

                                                                    if ($prdId !== NULL) {
                                                                ?>
                                                                        <td class="product-subtotal" style="border-right: none;">
                                                                            <?php if (!$hasReview) { ?>
                                                                                <!-- Button trigger modal -->
                                                                                <button type="button" class="btn btn-review" data-bs-toggle="modal" data-bs-target="#reviewModal-<?php echo $row['prd_id']; ?>">
                                                                                    <i class="fa-solid fa-comment-dots"></i>
                                                                                    รีวิว
                                                                                </button>

                                                                                <!-- Modal -->
                                                                                <div class="modal fade" id="reviewModal-<?php echo $row['prd_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                                                    <form id="formReview" action="process/account_order_history_detail_review_add.php" method="post">

                                                                                        <div class="modal-dialog">
                                                                                            <div class="modal-content">
                                                                                                <div class="account-details-form">
                                                                                                    <div class="modal-header">
                                                                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">รีวิวสินค้า</h1>
                                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                    </div>
                                                                                                    <div class="modal-body">
                                                                                                        <input type="hidden" name="prd_id" value="<?php echo $row['prd_id']; ?>">
                                                                                                        <input type="hidden" name="ord_id" value="<?php echo $order['ord_id']; ?>">
                                                                                                        <input type="hidden" name="mem_id" value="<?php echo $_SESSION['mem_id']; ?>">
                                                                                                        <div class="row">
                                                                                                            <div class="col-lg-12 text-start">
                                                                                                                <div class="single-input-item mb-3">
                                                                                                                    <div class="mb-3 star-rating-form">
                                                                                                                        <label for="prv_detail" class="form-label mb-3">ระดับความพึงพอใจ <span class="text-danger">*</span></label>
                                                                                                                        <div class="d-flex justify-content-start starRatingGroup">
                                                                                                                            <div class="d-flex align-items-start starRating">
                                                                                                                                <div class="d-flex flex-column align-items-center mx-2">
                                                                                                                                    <i class="fa-solid fa-star fa-xl" data-rating="1"></i>
                                                                                                                                    <span class="mt-3">น้อยที่สุด</span>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex flex-column align-items-center mx-2">
                                                                                                                                    <i class="fa-solid fa-star fa-xl" data-rating="2"></i>
                                                                                                                                    <span class="mt-3">น้อย</span>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex flex-column align-items-center mx-2">
                                                                                                                                    <i class="fa-solid fa-star fa-xl" data-rating="3"></i>
                                                                                                                                    <span class="mt-3">ปานกลาง</span>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex flex-column align-items-center mx-2">
                                                                                                                                    <i class="fa-solid fa-star fa-xl" data-rating="4"></i>
                                                                                                                                    <span class="mt-3">มาก</span>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex flex-column align-items-center mx-2">
                                                                                                                                    <i class="fa-solid fa-star fa-xl" data-rating="5"></i>
                                                                                                                                    <span class="mt-3">มากที่สุด</span>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <input type="hidden" name="prv_rating" class="prv_rating">
                                                                                                                        </div>
                                                                                                                    </div>

                                                                                                                </div>
                                                                                                                <div class="single-input-item mb-3">
                                                                                                                    <div class="mb-3">
                                                                                                                        <label for="prv_detail" class="form-label">รีวิวสินค้า</label> <br>
                                                                                                                        <textarea name="prv_detail" class="form-control" placeholder="รายละเอียดการรีวิว"></textarea>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn" data-bs-dismiss="modal">ยกเลิก</button>
                                                                                                                    <button type="submit" name="btn-review-add" class="btn btn-sqr">
                                                                                                                        <i class="fa-solid fa-comment-dots"></i>
                                                                                                                        รีวิวสินค้า
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <button type="button" class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editReviewModal-<?php echo $row['prd_id']; ?>">
                                                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                                                    แก้ไขรีวิว
                                                                                </button>

                                                                                <!-- Modal -->
                                                                                <div class="modal fade" id="editReviewModal-<?php echo $row['prd_id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                                                    <form id="formReview" action="process/account_order_history_detail_review_edit.php" method="post">

                                                                                        <div class="modal-dialog">
                                                                                            <div class="modal-content">
                                                                                                <div class="account-details-form">
                                                                                                    <div class="modal-header">
                                                                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">รีวิวสินค้า</h1>
                                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                    </div>
                                                                                                    <div class="modal-body">
                                                                                                        <input type="hidden" name="prd_id" value="<?php echo $row['prd_id']; ?>">
                                                                                                        <input type="hidden" name="ord_id" value="<?php echo $order['ord_id']; ?>">
                                                                                                        <input type="hidden" name="mem_id" value="<?php echo $_SESSION['mem_id']; ?>">
                                                                                                        <div class="row">
                                                                                                            <div class="col-lg-12 text-start">
                                                                                                                <div class="single-input-item mb-3">
                                                                                                                    <div class="mb-3 star-rating-form">
                                                                                                                        <label for="prv_detail" class="form-label mb-3">ระดับความพึงพอใจ <span class="text-danger">*</span></label>
                                                                                                                        <div class="mb-3 d-flex justify-content-start starRatingGroup">
                                                                                                                            <div class="d-flex align-items-start starRating">
                                                                                                                                <div class="d-flex flex-column align-items-center mx-2">
                                                                                                                                    <i class="fa-solid fa-star fa-xl" data-rating="1"></i>
                                                                                                                                    <span class="mt-3">น้อยที่สุด</span>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex flex-column align-items-center mx-2">
                                                                                                                                    <i class="fa-solid fa-star fa-xl" data-rating="2"></i>
                                                                                                                                    <span class="mt-3">น้อย</span>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex flex-column align-items-center mx-2">
                                                                                                                                    <i class="fa-solid fa-star fa-xl" data-rating="3"></i>
                                                                                                                                    <span class="mt-3">ปานกลาง</span>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex flex-column align-items-center mx-2">
                                                                                                                                    <i class="fa-solid fa-star fa-xl" data-rating="4"></i>
                                                                                                                                    <span class="mt-3">มาก</span>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex flex-column align-items-center mx-2">
                                                                                                                                    <i class="fa-solid fa-star fa-xl" data-rating="5"></i>
                                                                                                                                    <span class="mt-3">มากที่สุด</span>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                            <input type="hidden" name="prv_rating" class="prv_rating" value="<?php echo $hasReview['prv_rating'] ?>">
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>

                                                                                                                <div class="single-input-item mb-3">
                                                                                                                    <div class="mb-3">
                                                                                                                        <label for="prv_detail" class="form-label">รีวิวสินค้า <span class="text-danger">*</span></label> <br>
                                                                                                                        <textarea name="prv_detail" class="form-control" placeholder="รายละเอียดการรีวิว" maxlength="250"><?php echo $hasReview['prv_detail'] ?></textarea>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn" data-bs-dismiss="modal">ยกเลิก</button>
                                                                                                                    <button type="submit" name="btn-review-add" class="btn btn-sqr">
                                                                                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                                                                                        แก้ไขรีวิวสินค้า
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </td>
                                                                <?php
                                                                    }
                                                                } ?>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>

                                                    <tfoot>
                                                        <tr>
                                                            <th class="text-end" colspan="5">ราคาสินค้าทั้งหมด</th>
                                                            <th class="text-center"><?php echo "฿" . number_format($totalPriceAllProduct, 2) ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-end" colspan="5">เหรียญที่ใช้เป็นส่วนลด</th>
                                                            <th class="text-center text-warning"><?php echo number_format($order['ord_coins_discount']) ?></th>
                                                        </tr>

                                                        <?php $totalPriceAfterCoinDiscount = $totalPriceAllProduct - $order['ord_coins_discount']; ?>

                                                        <?php if ($order['ord_coins_discount'] > 0) { ?>
                                                            <tr>
                                                                <th class="text-end" colspan="5">ราคาสินค้าหลังใช้เหรียญ</th>
                                                                <th class="text-center"><?php echo "฿" . number_format($totalPriceAfterCoinDiscount, 2) ?></th>
                                                            </tr>
                                                        <?php } ?>

                                                        <tr>

                                                            <?php $totalPriceAfterCoinDiscount = $totalPriceAllProduct - $order['ord_coins_discount']; ?>

                                                            <th class="text-end" colspan="5">โปรโมชั่นส่วนลดที่ใช้</th>
                                                            <?php if ($orderPromotion) { ?>
                                                                <th class="text-center"><?php echo $orderPromotion['opm_name'] . " (-" . $orderPromotion['opm_percent_discount'] . "%)";  ?></th>
                                                            <?php } else { ?>
                                                                <th class="text-center">ไม่ใช้โปรโมชั่น</th>
                                                            <?php } ?>
                                                        </tr>

                                                        <?php
                                                        if ($orderPromotion) {
                                                            $totalPriceAfterPromotion = $totalPriceAfterCoinDiscount - ($totalPriceAfterCoinDiscount * $orderPromotion['opm_percent_discount'] / 100);
                                                        } else {
                                                            $totalPriceAfterPromotion = $totalPriceAfterCoinDiscount;
                                                        }
                                                        ?>

                                                        <?php if ($orderPromotion) { ?>
                                                            <tr>
                                                                <th class="text-end" colspan="5">ราคาสินค้าหลังใช้โปรโมชั่นส่วนลด</th>
                                                                <th class="text-center"><?php echo "฿" . number_format($totalPriceAfterPromotion, 2) ?></th>
                                                            </tr>
                                                        <?php } ?>
                                                        <tr>
                                                            <?php $totalPriceAfterShipping = $totalPriceAfterPromotion + $orderShipping['osp_price'];  ?>
                                                            <th class="text-end" colspan="5">ราคาค่าขนส่ง</th>
                                                            <th class="text-center"><?php echo $orderShipping['osp_name'] . " (฿" . number_format($orderShipping['osp_price'], 2) . ")" ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-end" colspan="5">เหรียญที่ได้รับ</th>


                                                            <th class="text-center text-success"><?php echo "+" . number_format($order['ord_coins_earned']) ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th class="text-end" colspan="5" style="color: #f07c29;">ราคาทั้งสิ้น</th>


                                                            <th class="text-center" style="color: #f07c29;"><?php echo "฿" . number_format($totalPriceAfterShipping, 2) ?></th>
                                                        </tr>
                                                    </tfoot>
                                                </table>

                                                <h5><i class="fa-solid fa-house-user"></i> ที่อยู่จัดส่ง</h5>
                                                <div class="ms-5">
                                                    <p><strong>ชื่อ-นามสกุล : </strong> <?php echo $orderAddress['oad_fname'] . " " . $orderAddress['oad_lname'] . " | <strong>เบอร์โทรศัพท์ : </strong>" . $orderAddress['oad_phone'] ?></p>
                                                    <p>
                                                        <strong>จังหวัด : </strong> <?php echo $orderAddress['oad_province'] ?>
                                                        <strong>อำเภอ : </strong><?php echo $orderAddress['oad_district'] ?>
                                                        <strong>ตำบล : </strong><?php echo $orderAddress['oad_subdistrict'] ?>
                                                        <strong>รหัสไปรษณีย์ : </strong><?php echo $orderAddress['oad_zip_code'] ?>
                                                    </p>
                                                    <p><strong>รายละเอียด : </strong><?php echo $orderAddress['oad_detail'] ?></p>
                                                </div>

                                                <h5><i class="fa-brands fa-paypal"></i> ช่องทางที่ชำระเงิน</h5>
                                                <div class="ms-5">
                                                    <p><strong>ชื่อธนาคาร : </strong><?php echo $orderPayment['opm_bank'] ?></p>
                                                    <p><strong>ชื่อบัญชี : </strong><?php echo $orderPayment['opm_name'] ?></p>
                                                    <p><strong>หมายเลขบัญชี : </strong><?php echo $orderPayment['opm_number'] ?></p>
                                                </div>

                                                <h5><i class="fa-regular fa-file-image"></i> หลักฐานการชำระเงิน</h5>
                                                <?php if ($orderSlip) { ?>
                                                    <div class="row">
                                                        <?php foreach ($orderSlip as $row) { ?>
                                                            <div class="col-4 mb-4">
                                                                <img src="uploads/img_slip/<?php echo $row['osl_slip']; ?>" alt="">
                                                            </div>
                                                        <?php } ?>
                                                    </div>

                                                <?php } else { ?>
                                                    <p class="text-danger">*ไม่พบหลักฐานการชำระเงิน</p>
                                                <?php } ?>

                                                <?php if ($order['ord_status'] == 'Completed') { ?>
                                                    <div class='my-3'>
                                                        <form action="process/order_again_add.php" method="post">
                                                            <?php foreach ($orderItems as $row) { ?>
                                                                <input type="hidden" name="prd_id[]" value="<?php echo $row['prd_id'] ?>" readonly>
                                                                <input type="hidden" name="crt_qty[]" value="<?php echo $row['oit_quantity'] ?>" readonly>
                                                            <?php } ?>

                                                            <input type="hidden" name="ord_id" value="<?php echo $order['ord_id'] ?>" readonly>
                                                            <input type="hidden" name="mem_id" value="<?php echo $_SESSION['mem_id'] ?>" readonly>
                                                            <button type="submit" name="btn-add" class="btn btn-confirm mt-2">
                                                                <i class="fa-solid fa-cart-plus"></i>
                                                                สั่งซื้อสินค้ารายการนี้อีกครั้ง
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>


                                        <div class="tab-pane fade show active mt-3" role="tabpanel">
                                            <div class="myaccount-content d-flex justify-content-between">
                                                <div>
                                                    <?php if ($order['ord_status'] == 'Pending Payment' || $order['ord_status'] == 'Payment Retry') { ?>
                                                        <button type="button" class="btn btn-del btn-order-cancel" data-ord_id="<?php echo $order["ord_id"]; ?>" data-mem_id="<?php echo $order["mem_id"]; ?>" data-ord_coins_discount="<?php echo $order['ord_coins_discount'] ?>">
                                                            <i class="fa-solid fa-trash"></i>
                                                            <span>ยกเลิกรายการสั่งซื้อนี้</span>
                                                        </button>
                                                    <?php } ?>
                                                    <?php if ($order['ord_status'] == 'Shipped') { ?>
                                                        <button type="button" class="btn btn-confirm btn-order-confirm" data-ord_id="<?php echo $order["ord_id"]; ?>" data-mem_id="<?php echo $order["mem_id"]; ?>" data-ord_coins_earned="<?php echo $order['ord_coins_earned'] ?>">
                                                            <i class="fa-solid fa-check"></i>
                                                            <span>ยืนยันได้รับสินค้าแล้ว</span>
                                                        </button>
                                                    <?php } ?>

                                                </div>
                                                <div>
                                                    <a href="account_order_history" class="btn"><i class="fa-solid fa-right-from-bracket fa-rotate-180"></i> กลับหน้าหลัก</a>
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

    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.1/js/dataTables.searchPanes.min.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.1/js/searchPanes.bootstrap5.min.js"></script>

    <script>
        new DataTable('#myAccountTable', {
            responsive: true,
            "order": [
                [0, 'DESC']
            ]
        });
    </script>

    <!-- Cancel Order -->
    <script>
        $(document).ready(function() {
            $(".btn-order-cancel").click(function(e) {
                e.preventDefault();
                let ordId = $(this).data('ord_id');
                let memId = $(this).data('mem_id');
                let ordCoinsDiscount = $(this).data('ord_coins_discount');

                OrderCancelConfirm(ordId, memId, ordCoinsDiscount);
            });
        });

        function OrderCancelConfirm(ordId, memId, ordCoinsDiscount) {
            Swal.fire({
                icon: "warning",
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการยกเลิกรายการสั่งซื้อนี้ใช่ไหม!",
                showCancelButton: true,
                confirmButtonColor: '#f34e4e',
                confirmButtonText: 'ใช่, ยกเลิกรายการนี้เลย!',
                cancelButtonText: 'ยกเลิก',
                preConfirm: function() {
                    return $.ajax({
                            url: 'process/account_order_history_detail_cancel.php',
                            type: 'POST',
                            data: {
                                ord_id: ordId,
                                mem_id: memId,
                                ord_coins_discount: ordCoinsDiscount
                            },
                        })
                        .done(function() {
                            // การลบสำเร็จ ทำการ redirect ไปยังหน้า account_order_history
                            return true;
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สำเร็จ',
                                text: 'เกิดข้อผิดพลาดที่ ajax !',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.location.href = 'account_order_history';
                                }
                            });
                        });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = 'account_order_history';
                }
            });
        }
    </script>

    <!-- Confirm Order  -->
    <script>
        $(document).ready(function() {
            $(".btn-order-confirm").click(function(e) {
                e.preventDefault();
                let ordId = $(this).data('ord_id');
                let memId = $(this).data('mem_id');
                let ordCoinsEarned = $(this).data('ord_coins_earned');

                OrderConfirm(ordId, memId, ordCoinsEarned);
            });
        });

        function OrderConfirm(ordId, memId, ordCoinsEarned) {
            Swal.fire({
                icon: "warning",
                title: 'ยืนยันรายการสั่งซื้อ?',
                text: "คุณได้รับสินค้าตามรายการสั่งซื้อนี้แล้วใช่ไหม!",
                showCancelButton: true,
                confirmButtonColor: 'green',
                confirmButtonText: 'ใช่, ได้รับสินค้าแล้ว!',
                cancelButtonText: 'ยกเลิก',
                preConfirm: function() {
                    return $.ajax({
                            url: 'process/account_order_history_detail_confirm.php',
                            type: 'POST',
                            data: {
                                ord_id: ordId,
                                mem_id: memId,
                                ord_coins_earned: ordCoinsEarned
                            },
                        })
                        .done(function() {
                            // การลบสำเร็จ ทำการ redirect ไปยังหน้า account_order_history
                            return true;
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สำเร็จ',
                                text: 'เกิดข้อผิดพลาดที่ ajax !',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.location.href = 'account_order_history';
                                }
                            });
                        });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = 'account_order_history';
                }
            });
        }
    </script>


    <!-- star  -->
    <script>
        $(document).ready(function() {
            // ฟังก์ชันในการจัดการการให้คะแนนดาว
            function setupStarRating(form) {
                let selectedRating = parseInt($(form).find('.prv_rating').val()) || 0;

                // เมื่อ hover เม้าส์
                $(form).find('.starRating i').on('mouseover', function() {
                    let rating = $(this).data('rating');

                    // เพิ่มสีให้กับดาวทั้งหมดก่อนหน้าและดาวที่อยู่ปัจจุบัน
                    $(form).find('.starRating i').each(function() {
                        if ($(this).data('rating') <= rating) {
                            $(this).css('color', '#f07c29');
                        } else {
                            $(this).css('color', '');
                        }
                    });
                });

                // เมื่อเม้าส์ออกจากดาว (ถ้ายังไม่ได้คลิกจะกลับเป็นสถานะเดิม)
                $(form).on('mouseleave', function() {
                    $(form).find('.starRating i').each(function() {
                        if ($(this).data('rating') <= selectedRating) {
                            $(this).css('color', '#f07c29');
                        } else {
                            $(this).css('color', '');
                        }
                    });
                });

                // เมื่อคลิก
                $(form).find('.starRating i').on('click', function() {
                    selectedRating = $(this).data('rating');

                    // ใส่ค่าใน input ซ่อน
                    $(form).find('.prv_rating').val(selectedRating);

                    // เพิ่มสีค้างไว้หลังจากคลิก
                    $(form).find('.starRating i').each(function() {
                        if ($(this).data('rating') <= selectedRating) {
                            $(this).css('color', '#f07c29');
                        } else {
                            $(this).css('color', '');
                        }
                    });
                });

                // ตั้งค่าสีดาวตามค่า rating ที่มีอยู่
                $(form).find('.starRating i').each(function() {
                    if ($(this).data('rating') <= selectedRating) {
                        $(this).css('color', '#f07c29');
                    } else {
                        $(this).css('color', '');
                    }
                });
            }

            // ตั้งค่าให้กับฟอร์มทั้งหมดที่มีคลาส star-rating-form
            $('.star-rating-form').each(function() {
                setupStarRating(this);
            });
        });
    </script>

</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>