<?php
$titlePage = "รายละเอียดรายการรอจัดส่ง";

require_once("../db/connectdb.php");
require_once("../db/controller/OrderController.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");

$OrderController = new OrderController($conn);

if (isset($_GET['id'])) {
    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $ordId = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $order = $OrderController->getOrderDetailStatusAwaitingShipment($ordId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($order);

    $orderItems = $OrderController->getOrderHistoryItemDetail($ordId);
    $orderPromotion = $OrderController->getOrderHistoryPromotionDetail($ordId);
    $orderShipping = $OrderController->getOrderHistoryShippingDetail($ordId);
    $orderAddress = $OrderController->getOrderHistoryAddressDetail($ordId);
    $orderPayment = $OrderController->getOrderHistoryPaymentDetail($ordId);
    $orderSlip = $OrderController->getOrderHistorySlipDetail($ordId);
} else {
    header('Location: order_show');
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

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title text-warning">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>รายละเอียดรายการสั่งซื้อที่ : <?php echo $ordId; ?></span>
                                    </h4>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">รูป</th>
                                                <th scope="col">ชื่อ</th>
                                                <th scope="col" class="text-center">ราคา</th>
                                                <th scope="col" class="text-center">จำนวน</th>
                                                <th scope="col" class="text-center">เหรียญที่ได้</th>
                                                <th scope="col" class="text-center">รวม</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $totalPriceAllProduct = 0; ?>
                                            <?php foreach ($orderItems as $row) { ?>
                                                <?php $totalPriceAllProduct += $row['price_sale'] ?>

                                                <tr>
                                                    <td class="text-center">
                                                        <?php if ($row['prd_img1']) { ?>
                                                            <img src="../uploads/img_product/<?php echo $row['prd_img1'] ?>" style="width: 50px; height:80px; object-fit:cover;" alt="">
                                                        <?php } else { ?>
                                                            <img src="../uploads/img_product/default.png" style="width: 50px; height:80px; object-fit:cover;" alt="">
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['prd_preorder'] == 0) { ?>
                                                            <?php echo "<span class='text-warning'>[พรีออเดอร์] </span>" . $row['oit_name'] ?>
                                                        <?php } else { ?>
                                                            <?php echo $row['oit_name'] ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="amount"><?php echo "฿" . number_format($row['price_sale'], 2) ?></span>
                                                        <?php if ($row['oit_percent_discount'] > 0) { ?>
                                                            <span class="text-danger"><sup><?php echo "-" . number_format($row['oit_percent_discount']) . "%" ?></sup></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo number_format($row['oit_quantity']) ?>
                                                    </td>
                                                    <td class="text-center"><?php echo number_format($row['coins_per_item']) ?></td>
                                                    <td class="text-center"><?php echo "฿" . number_format($row['total_price_sale'], 2) ?></td>

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
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>


                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title text-warning"><i class="fa-solid fa-house-user"></i> ที่อยู่จัดส่ง</h4>
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
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title text-warning"><i class="fa-brands fa-paypal"></i> ช่องทางที่ชำระเงิน</h4>
                                    <div class="ms-5">
                                        <p><strong>ชื่อธนาคาร : </strong><?php echo $orderPayment['opm_bank'] ?></p>
                                        <p><strong>ชื่อบัญชี : </strong><?php echo $orderPayment['opm_name'] ?></p>
                                        <p><strong>หมายเลขบัญชี : </strong><?php echo $orderPayment['opm_number'] ?></p>
                                    </div>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title text-warning"><i class="fa-brands fa-pied-piper-pp"></i> หลักฐานการชำระเงิน</h4>
                                    <?php if ($orderSlip) { ?>
                                        <div class="row d-flex justify-content-center">
                                            <?php foreach ($orderSlip as $row) { ?>
                                                <div class="col-3 mb-3">
                                                    <img src="../uploads/img_slip/<?php echo $row['osl_slip'] ?>" style="width:200px;" alt="">
                                                </div>
                                            <?php } ?>

                                        <?php } else { ?>
                                            <p class="text-danger">*ไม่พบหลักฐานการชำระเงิน</p>
                                        <?php } ?>
                                        </div>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title text-warning"><i class="fa-solid fa-truck-ramp-box"></i> หมายเลขติดตามสินค้า</h4>
                                    <div>
                                        <form id="formOrderAwaitingShipmentDetail" action="process/order_awaiting_shipment_detail_edit.php" method="post">
                                            <input type="hidden" name="ord_id" value="<?php echo $order['ord_id'] ?>" readonly>
                                            <div class="mb-3">
                                                <label for="ord_tracking_number" class="form-label">หมายเลขติดตามสินค้า :</label><span class="text-danger">*</span>
                                                <input type="text" name="ord_tracking_number" class="form-control" placeholder="ระบุ หมายเลขติดตามสินค้า" maxlength="30">
                                            </div>
                                            <button type="submit" name="btn-order-shipped" class="btn btn-success me-2">
                                                <i class="fa-solid fa-check"></i>
                                                <span>ยืนยันจัดส่งสินค้า</span>
                                            </button>
                                        </form>

                                    </div>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $order['ord_time_update'] ?></span></h4>
                                    <div>
                                        <a href="order_awaiting_shipment_detail" class="btn btn-secondary me-2">
                                            <i class="fa-solid fa-xmark me-1"></i>
                                            <span>กลับ</span>
                                        </a>
                                        <button type="submit" name="btn-order-cancel" class="btn btn-danger me-2 btn-order-cancel" data-ord_id="<?php echo $order['ord_id'] ?>" data-mem_id="<?php echo $order['mem_id'] ?>" data-ord_coins_discount="<?php echo $order['ord_coins_discount'] ?>">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>ยกเลิกรายการนี้</span>
                                        </button>
                                        <button type="submit" name="btn-order-payment-retry" class="btn btn-warning me-2 btn-order-payment-retry" data-ord_id="<?php echo $order['ord_id'] ?>">
                                            <i class="fa-solid fa-rotate-left"></i>
                                            <span>ชำระเงินใหม่</span>
                                        </button>

                                    </div>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
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
                    title: 'ยกเลิกรายการสั่งซื้อนี้?',
                    text: "คุณต้องการยกเลิกรายการสั่งซื้อนี้ใช่ไหม!",
                    showCancelButton: true,
                    confirmButtonColor: '#f34e4e',
                    confirmButtonText: 'ใช่, ยกเลิกรายการนี้เลย!',
                    cancelButtonText: 'ยกเลิก',
                    preConfirm: function() {
                        return $.ajax({
                                url: 'process/order_detail_cancel.php',
                                type: 'POST',
                                data: {
                                    ord_id: ordId,
                                    mem_id: memId,
                                    ord_coins_discount: ordCoinsDiscount
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า order_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'order_show';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'order_show';
                    }
                });
            }
        </script>

        <!-- Payment Retry Order -->
        <script>
            $(document).ready(function() {
                $(".btn-order-payment-retry").click(function(e) {
                    e.preventDefault();
                    let ordId = $(this).data('ord_id');
                    OrderPaymentRetryConfirm(ordId);
                });
            });

            function OrderPaymentRetryConfirm(ordId) {
                Swal.fire({
                    icon: "warning",
                    title: 'ให้ชำระเงินใหม่?',
                    text: "คุณต้องการให้รายการสั่งซื้อนี้ ชำระเงินใหม่ใช่ไหม!",
                    showCancelButton: true,
                    confirmButtonColor: '#f34e4e',
                    confirmButtonText: 'ใช่, ชำระเงินใหม่เลย!',
                    cancelButtonText: 'ยกเลิก',
                    preConfirm: function() {
                        return $.ajax({
                                url: 'process/order_detail_payment_retry.php',
                                type: 'POST',
                                data: {
                                    ord_id: ordId,
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า order_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'order_show';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'order_show';
                    }
                });
            }
        </script>

</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>