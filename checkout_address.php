<?php
$titlePage = "ที่อยู่จัดส่ง";

require_once("db/connectdb.php");
require_once("includes/salt.php");
require_once("includes/functions.php");
require_once("db/controller/CartController.php");

$CartController = new CartController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: login_form');
    exit;
} else {

    $memId = $_SESSION['mem_id'] ?? null;
    $coinsDiscount = $_SESSION['coins_discount'] ?? null;
    $proId = $_SESSION['pro_id'] ?? null;
    $shpId = $_SESSION['shp_id'] ?? null;
    $pmtId = $_SESSION['pmt_id'] ?? null;

    // ตรวจสอบค่าที่จำเป็นต้องมีใน session
    if (!$memId) {
        $_SESSION['error'] = "กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ";
        header("Location: login_page");
        exit;
    }

    // ใช้ค่าจาก $_SESSION โดยตรง
    $productCart = $CartController->getCartItem($memId);

    // ตรวจสอบและดึงข้อมูลโปรโมชั่น
    $promotion = $proId ? $CartController->getUsePromotion($proId) : null;

    // ตรวจสอบและดึงข้อมูลการจัดส่ง
    $shipping = $shpId ? $CartController->getUseShipping($shpId) : null;

    // ตรวจสอบและดึงข้อมูลการชำระเงิน
    $payment = $pmtId ? $CartController->getUsePayment($pmtId) : null;

    // ดึงที่อยู่หลักของสมาชิก
    $address = $CartController->getMemberAddressDefault($memId);

    // ตรวจสอบว่ามีข้อมูลที่จำเป็นครบถ้วนหรือไม่
    if (!$productCart) {
        $_SESSION['error'] = "กรุณาระบุข้อมูลสินค้า";
        header("Location: checkout_product");
        exit;
    } elseif (!$shipping) {
        $_SESSION['error'] = "กรุณาระบุข้อมูลการจัดส่ง";
        header("Location: checkout_product");
        exit;
    } elseif (!$payment) {
        $_SESSION['error'] = "กรุณาระบุข้อมูลการชำระเงิน";
        header("Location: checkout_product");
        exit;
    }
}

?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="checkout">
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
                            <li><a href="checkout_address" class="active">ที่อยู่จัดส่ง</a></li>
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
                        <h2>เลือกที่อยู่จัดส่ง</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- entry-header-area-end -->

    <!-- coupon-area-area-start -->
    <!-- <div class="coupon-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="coupon-accordion">
                        <h3>Returning customer? <span id="showlogin">Click here to login</span></h3>
                        <div class="coupon-content" id="checkout-login">
                            <div class="coupon-info">
                                <p class="coupon-text">Quisque gravida turpis sit amet nulla posuere lacinia. Cras sed est sit amet ipsum luctus.</p>
                                <form action="#">
                                    <p class="form-row-first">
                                        <label>Username or email <span class="required">*</span></label>
                                        <input type="text">
                                    </p>
                                    <p class="form-row-last">
                                        <label>Password <span class="required">*</span></label>
                                        <input type="text">
                                    </p>
                                    <p class="form-row">
                                        <input type="submit" value="Login">
                                        <label>
                                            <input type="checkbox">
                                            Remember me
                                        </label>
                                    </p>
                                    <p class="lost-password">
                                        <a href="#">Lost your password?</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                        <h3>Have a coupon? <span id="showcoupon">Click here to enter your code</span></h3>
                        <div class="coupon-checkout-content" id="checkout_coupon">
                            <div class="coupon-info">
                                <form action="#">
                                    <p class="checkout-coupon">
                                        <input type="text" placeholder="Coupon code">
                                        <input type="submit" value="Apply Coupon">
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- coupon-area-area-end -->

    <!-- checkout-area-start -->
    <div class="checkout-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="process/order_add.php" method="POST">

                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-12">
                                <div class="checkbox-form">
                                    <h6>ที่อยู่จัดส่งหลัก</h6>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <?php if ($address) { ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="addr_id" id="1" value="<?php echo $address['addr_id']; ?>" checked>
                                                    <p>
                                                        <?php if ($address['addr_type'] == 2) { ?>
                                                            <span class="badge text-bg-success me-2">ที่ทำงาน</span>
                                                        <?php } else { ?>
                                                            <span class="badge text-bg-primary me-2">บ้าน</span>
                                                        <?php } ?>

                                                        <?php echo $address['addr_fname'] . " " . $address['addr_lname']; ?>
                                                        |
                                                        <?php
                                                        $phone = $address['addr_phone'];
                                                        // แสดงผลในรูปแบบ 081-234-5678
                                                        $formattedPhone = substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);

                                                        echo $formattedPhone;
                                                        ?>
                                                    </p>

                                                    <div class="ms-4">
                                                        <span><strong>จังหวัด : </strong> <?php echo $address['addr_province']; ?></span>
                                                        <span><strong>อำเภอ/เขต : </strong><?php echo $address['addr_district']; ?></span>
                                                        <span><strong>ตำบล/แขวง : </strong><?php echo $address['addr_subdistrict']; ?></span>
                                                        <p><strong>รหัสไปรษณีย์ : </strong><?php echo $address['addr_zip_code']; ?></p>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div class="ms-2">
                                                    <p>ไม่พบที่อยู่เริ่มต้นของท่าน <a href="account_address">สามารถเพิ่มที่อยู่เริ่มต้นได้ ที่นี่</a></p>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6>ที่อยู่จัดส่งชั่วคราว</h6>
                                    <div class="row">
                                        <div class="col-lg-12 my-3">
                                            <input class="form-check-input" type="radio" name="addr_id" id="2" value="0" <?php if (!$address) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label for="" class="form-label">ใช้ที่อยู่จัดส่งชั่วคราว<span class="text-danger"> *(กรุณาเช็ค เพื่อใช้ที่อยู่จัดส่งชั่วคราว)</span></label>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-12">
                                            <div class="checkout-form-list">
                                                <label class="form-label">ชื่อ <span class="required">*</span></label>
                                                <input type="text" name="addr_fname" placeholder="กรุณาระบุ ชื่อจริง" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-12">
                                            <div class="checkout-form-list">
                                                <label class="form-label">นามสกุล <span class="required">*</span></label>
                                                <input type="text" name="addr_lname" placeholder="กรุณาระบุ นามสกุล" maxlength="50">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-12">
                                            <div class="checkout-form-list">
                                                <label class="form-label">เบอร์โทรศัพท์ <span class="text-danger">*</span></label>
                                                <input type="text" name="addr_phone" placeholder="กรุณาระบุ เบอร์โทรศัพท์">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-12">
                                            <div class="country-select">
                                                <label class="form-label">ประเภทที่อยู่ <span class="text-danger">*</span< /label>
                                                        <select class="form-select" name="addr_type">
                                                            <option value="1" selected>บ้าน</option>
                                                            <option value="2">ที่ทำงาน</option>
                                                        </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="country-select">
                                                <label class="form-label">จังหวัด <span class="required">*</span></label>
                                                <input type="hidden" id="province_name" name="province_name">

                                                <select class="form-select" id="province" name="province">
                                                    <option selected>กรุณาระบุ จังหวัด</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="country-select">
                                                <label class="form-label">อำเภอ/เขต <span class="required">*</span></label>
                                                <input type="hidden" id="district_name" name="district_name">

                                                <select class="form-select" id="district" name="district">
                                                    <option selected>กรุณาระบุ อำเภอ/เขต</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="country-select">
                                                <label class="form-label">ตำบล/แขวง <span class="required">*</span></label>
                                                <input type="hidden" id="subdistrict_name" name="subdistrict_name">

                                                <select class="form-select" id="subdistrict" name="subdistrict">
                                                    <option selected>กรุณาระบุ ตำบล/แขวง</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="checkout-form-list">
                                                <label class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span< /label>
                                                        <input type="number" class="form-control" id="zip_code" name="zip_code" placeholder="รหัสไปราณีย์ จะแสดงเมื่อ จังหวัด อำเภอ ตำบล" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="checkout-form-list">
                                                <label class="form-label">รายละเอียดเพิ่มเติม <span class="text-danger">*</span< /label>
                                                        <textarea name="addr_detail" placeholder="กรุณาระบุ รายละเอียดเพิ่มเติม เช่น บ้านเลขที่ ซอย"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-12">
                                <div class="your-order">
                                    <h3>รายการสินค้า</h3>
                                    <div class="your-order-table table-responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="product-name">สินค้า</th>
                                                    <th class="product-name">จำนวน</th>
                                                    <th class="product-total">ราคารวม</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $totalPriceAllProduct = 0;
                                                $totalCoinAllProduct = 0;
                                                $preorderCount = 0;
                                                ?>

                                                <?php foreach ($productCart as $row) { ?>
                                                    <?php
                                                    $originalId = $row["prd_id"];
                                                    $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);

                                                    // บวกค่าของ $row['total_price_sale'] เข้าไปใน $totalPriceAllProduct
                                                    $totalPriceAllProduct += $row['total_price_sale'];
                                                    $totalCoinAllProduct += $row['coins_per_item'];

                                                    if ($row['prd_preorder'] == 0) {
                                                        $preorderCount++;
                                                    }
                                                    ?>

                                                    <input type="hidden" name="prd_id[]" value="<?php echo $row['prd_id'] ?>" readonly>
                                                    <input type="hidden" name="prd_name[]" value="<?php echo $row['prd_name'] ?>" readonly>
                                                    <input type="hidden" name="prd_coin[]" value="<?php echo $row['prd_coin'] ?>" readonly>
                                                    <input type="hidden" name="crt_qty[]" value="<?php echo $row['crt_qty'] ?>" readonly>
                                                    <input type="hidden" name="prd_quantity[]" value="<?php echo $row['prd_quantity'] ?>" readonly>
                                                    <input type="hidden" name="prd_price[]" value="<?php echo $row['prd_price'] ?>" readonly>
                                                    <input type="hidden" name="prd_percent_discount[]" value="<?php echo $row['prd_percent_discount'] ?>" readonly>

                                                    <tr class="cart_item">
                                                        <td class="product-name">
                                                            <a href="product_detail?id=<?php echo $base64Encoded ?>">
                                                                <?php
                                                                if ($row['prd_preorder'] == 0) {
                                                                    echo '<span class="text-warning me-1">[พรีออเดอร์]</span>' . (mb_strlen($row['prd_name'], 'UTF-8') > 21 ? mb_substr($row['prd_name'], 0, 21, 'UTF-8') . '...' : $row['prd_name']);
                                                                } else {
                                                                    echo mb_strlen($row['prd_name'], 'UTF-8') > 30 ? mb_substr($row['prd_name'], 0, 30, 'UTF-8') . '...' : $row['prd_name'];
                                                                }
                                                                ?>
                                                            </a>
                                                        </td>
                                                        <td><?php echo number_format($row['crt_qty']) ?></td>
                                                        <td class="product-total"><span class="amount"><?php echo "฿" . number_format($row['total_price_sale'], 2); ?></span> </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="cart-subtotal">
                                                    <th>ราคาสินค้าทั้งหมด</th>
                                                    <th></th>
                                                    <td><span class="amount"><?php echo "฿" . number_format($totalPriceAllProduct, 2) ?></span></td>
                                                </tr>


                                                <?php if ($coinsDiscount > 0) { ?>

                                                    <?php $totalPriceAfterCoinDiscount = $totalPriceAllProduct - $coinsDiscount ?>

                                                    <tr class="cart-subtotal">
                                                        <th>เหรียญที่ใช้เป็นส่วนลด</th>
                                                        <th></th>
                                                        <td><span class="amount"><?php echo number_format($coinsDiscount) ?></span></td>
                                                    </tr>
                                                    <tr class="cart-subtotal">
                                                        <th>ราคาสินค้าหลังใช้เหรียญ</th>
                                                        <th></th>
                                                        <td><span class="amount"><?php echo "฿" . number_format($totalPriceAfterCoinDiscount, 2) ?></span></td>
                                                    </tr>
                                                <?php } else { ?>
                                                    <?php $totalPriceAfterCoinDiscount = $totalPriceAllProduct ?>
                                                    <tr class="cart-subtotal">
                                                        <th>เหรียญที่ใช้เป็นส่วนลด</th>
                                                        <th></th>
                                                        <td><span class="amount">ไม่ใช้เหรียญเป็นส่วนลด</span></td>
                                                    </tr>
                                                <?php } ?>

                                                <?php if ($promotion) { ?>
                                                    <?php $totalPriceAfterPromotion = $totalPriceAfterCoinDiscount - ($totalPriceAfterCoinDiscount * $promotion['pro_percent_discount'] / 100) ?>
                                                    <tr class="cart-subtotal">
                                                        <th>โปรโมชั่นที่ใช้</th>
                                                        <th></th>
                                                        <td><span class="amount"><?php echo $promotion['pro_name'] . " (ลด " . $promotion['pro_percent_discount'] . "%)" ?></span></td>
                                                    </tr>
                                                    <tr class="cart-subtotal">
                                                        <th>ราคาสินค้าหลังใช้โปรโมชั่น</th>
                                                        <th></th>
                                                        <td><span class="amount"><?php echo "฿" . number_format($totalPriceAfterPromotion, 2) ?></span></td>
                                                    </tr>
                                                <?php } else { ?>
                                                    <?php $totalPriceAfterPromotion = $totalPriceAfterCoinDiscount ?>

                                                    <tr class="cart-subtotal">
                                                        <th>โปรโมชั่นที่ใช้</th>
                                                        <th></th>
                                                        <td><span class="amount">ไม่ใช้โปรโมชั่น</span></td>
                                                    </tr>
                                                <?php } ?>

                                                <tr class="cart-subtotal">
                                                    <th>ค่าขนส่ง</th>
                                                    <th></th>
                                                    <td><span class="amount"><?php echo $shipping['shp_name'] . " (฿" . number_format($shipping['shp_price'], 2) . ")" ?></span></td>
                                                </tr>

                                                <tr class="cart-subtotal">
                                                    <th>เหรียญที่ได้รับ</th>
                                                    <th></th>
                                                    <td><span class="amount text-success"><?php echo "+" . number_format($totalCoinAllProduct) ?></span></td>
                                                </tr>

                                                <tr class="order-total">
                                                    <th>ราคาสุทธิ</th>
                                                    <th></th>
                                                    <?php $totalPriceAfterShipping = $totalPriceAfterPromotion + $shipping['shp_price'];  ?>
                                                    <td><strong><span class="amount"><?php echo "฿" . number_format($totalPriceAfterShipping, 2) ?></span></strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="payment-method">
                                        <div class="payment-accordion">
                                            <div class="collapses-group">
                                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                                    <?php if ($preorderCount) { ?>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" role="tab" id="headingOne">
                                                                <h4 class="panel-title">
                                                                    <a class="collapsed" role="button" data-bs-toggle="collapse" data-bs-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                                        คุณมีสินค้าพรีออเดอร์ <?php echo "(" . number_format($preorderCount) . " รายการ)" ?>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                                <div class="panel-body">
                                                                    <p>สินค้าพรีออเดอร์ มักใช้เวลานานกว่าปกติ เราแนะนำให้ท่าน แยกรายกาสั่งซื้อสินค้าปกติกับสินค้าพรีออเดอร์</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>

                                                    <?php if ($preorderCount) { ?>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" role="tab" id="headingTwo">
                                                                <h4 class="panel-title">
                                                                    <a class="collapsed" role="button" data-bs-toggle="collapse" data-bs-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                                        ขำระเงินผ่าน <?php echo $payment['pmt_bank'] ?>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                                <div class="panel-body">
                                                                    <p>การชำระเงิน ผู้ใช้ต้องทำการชำระเงินโดยการโอน และอัปโหลดสลิปหลักฐานการชำระเงิน</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="mem_id" value="<?php echo $_SESSION['mem_id'] ?>" readonly>
                                        <input type="hidden" name="ord_coins_discount" value="<?php echo $coinsDiscount ?>" readonly>
                                        <input type="hidden" name="ord_coins_earned" value="<?php echo $totalCoinAllProduct ?>" readonly>
                                        <input type="hidden" name="ord_price" value="<?php echo $totalPriceAfterShipping ?>" readonly>

                                        <input type="hidden" name="pro_id" value="<?php echo $promotion ? $promotion['pro_id'] : null; ?>" readonly>
                                        <input type="hidden" name="pro_name" value="<?php echo $promotion ? $promotion['pro_name'] : null; ?>" readonly>
                                        <input type="hidden" name="pro_percent_discount" value="<?php echo $promotion ? $promotion['pro_percent_discount'] : null; ?>" readonly>

                                        <input type="hidden" name="shp_id" value="<?php echo $shipping['shp_id']  ?>" readonly>
                                        <input type="hidden" name="shp_name" value="<?php echo $shipping['shp_name']  ?>" readonly>
                                        <input type="hidden" name="shp_price" value="<?php echo $shipping['shp_price']  ?>" readonly>

                                        <input type="hidden" name="pmt_id" value="<?php echo $payment['pmt_id']  ?>" readonly>
                                        <input type="hidden" name="pmt_bank" value="<?php echo $payment['pmt_bank']  ?>" readonly>
                                        <input type="hidden" name="pmt_name" value="<?php echo $payment['pmt_name']  ?>" readonly>
                                        <input type="hidden" name="pmt_number" value="<?php echo $payment['pmt_number']  ?>" readonly>

                                        <div class="order-button-payment">
                                            <input type="submit" name="btn-add" value="ยืนยันรายการสั่งซื้อ">
                                            <div class="buttons-cart">
                                                <a href="checkout_product" style="font-size:16px" class="w-100 text-center mt-3">กลับ</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- checkout-area-end -->
    <!-- footer-area-start -->

    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>

    <script>
        $(document).ready(function() {
            let provinceObject = $('#province');
            let districtObject = $('#district');
            let subdistrictObject = $('#subdistrict');

            let provinceNameInput = $('#province_name');
            let districtNameInput = $('#district_name');
            let subdistrictNameInput = $('#subdistrict_name');

            // Set initial dropdown options
            provinceObject.html('<option selected>กรุณาระบุ จังหวัด</option>');
            districtObject.html('<option selected>กรุณาระบุ อำเภอ</option>');
            subdistrictObject.html('<option selected>ตำบล/แขวง</option>');
            $('#zip_code').val('');

            // Load province data
            $.get('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_province.json', function(data) {
                var result = JSON.parse(data);
                $.each(result, function(index, item) {
                    provinceObject.append(
                        $('<option></option>').val(item.id).html(item.name_th).data('name_th', item.name_th)
                    );
                });
            });

            //ค้นหาอำเภอในจังหวัดที่เลือก
            provinceObject.on('change', function() {
                let selectedProvince = $(this).find('option:selected').data('name_th');
                provinceNameInput.val(selectedProvince);
                let provinceId = $(this).val();

                districtObject.html('<option selected>กรุณาระบุ อำเภอ</option>');
                subdistrictObject.html('<option selected>ตำบล/แขวง</option>');
                $('#zip_code').val('');

                $.get('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_amphure.json', function(data) {
                    let result = JSON.parse(data);
                    $.each(result, function(index, item) {
                        if (item.province_id == provinceId) {
                            districtObject.append(
                                $('<option></option>').val(item.id).html(item.name_th).data('name_th', item.name_th)
                            );
                        }
                    });
                });
            });


            //ค้นหาตำบลในอำเภอที่เลือก
            districtObject.on('change', function() {
                let selectedDistrict = $(this).find('option:selected').data('name_th');
                districtNameInput.val(selectedDistrict);
                let districtId = $(this).val();

                subdistrictObject.html('<option selected>ตำบล/แขวง</option>');
                $('#zip_code').val('');

                $.get('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_tambon.json', function(data) {
                    let result = JSON.parse(data);
                    $.each(result, function(index, item) {
                        if (item.amphure_id == districtId) {
                            subdistrictObject.append(
                                $('<option></option>').val(item.id).html(item.name_th).data('name_th', item.name_th)
                            );
                        }
                    });
                });
            });

            //ค้นหารหัสไปรษณีย์
            subdistrictObject.on('change', function() {
                let selectedSubdistrict = $(this).find('option:selected').data('name_th');
                subdistrictNameInput.val(selectedSubdistrict);
                let subdistrictId = $(this).val();

                $.get('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_tambon.json', function(data) {
                    let result = JSON.parse(data);
                    $.each(result, function(index, item) {
                        if (item.id == subdistrictId) {
                            $('#zip_code').val(item.zip_code);
                        }
                    });
                });
            });

        });
    </script>
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>