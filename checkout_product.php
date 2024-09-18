<?php
$titlePage = "รถเข็นสินค้า";

require_once("db/connectdb.php");
require_once("db/controller/CartController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$CartController = new CartController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: login_form');
    exit;
} else {

    $memId = $_SESSION['mem_id'];
    $productCart = $CartController->getCartItem($memId);
    $shippings = $CartController->getShipping();
    $payments = $CartController->getPayment();
    $Promotions = $CartController->getPromotion();
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
                            <li><a href="#" class="active">รถเข็นสินค้า</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs-area-end -->

    <?php if ($productCart) { ?>
        <!-- entry-header-area-start -->
        <div class="entry-header-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="entry-header-title">
                            <h5>สรุปรายการสั่งซื้อ</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- entry-header-area-end -->
        <!-- cart-main-area-start -->
        <div class="cart-main-area mb-70">
            <div class="container">
                <form action="process/checkout_address_add" method="post">
                    <input type="hidden" name="mem_id" value="<?php echo $_SESSION['mem_id'] ?>" readonly>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-content table-responsive mb-15 border-1">
                                <table class="table table-responsive table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="product-thumbnail">รูป</th>
                                            <th class="product-name">ชื่อ</th>
                                            <th class="product-price">ราคา</th>
                                            <th class="product-quantity">จำนวน</th>
                                            <th class="product-subtotal">เหรียญที่ได้</th>
                                            <th class="product-subtotal">รวม</th>
                                            <th class="product-remove">ลบ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $totalPriceAllProduct = 0;
                                        $totalCoinAllProduct = 0;
                                        ?>
                                        <?php foreach ($productCart as $row) { ?>
                                            <?php
                                            $originalId = $row["prd_id"];
                                            require_once("includes/salt.php");
                                            $saltedId = $salt1 . $originalId . $salt2;
                                            $base64Encoded = base64_encode($saltedId);

                                            // บวกค่าของ $row['total_price_sale'] เข้าไปใน $totalPriceAllProduct
                                            $totalPriceAllProduct += $row['total_price_sale'];
                                            $totalCoinAllProduct += $row['coins_per_item'];
                                            ?>

                                            <input type="hidden" name="prd_id[]" value="<?php echo $row['prd_id'] ?>" readonly>
                                            <input type="hidden" name="prd_quantity[]" value="<?php echo $row['prd_quantity']; ?>" readonly>
                                            <tr>
                                                <td class="product-thumbnail">
                                                    <a href="product_detail?id=<?php echo $base64Encoded; ?>">
                                                        <img src="uploads/img_product/<?php echo $row['prd_img1'] ?>" style="height:80px; object-fit: cover;" alt="man" />
                                                    </a>
                                                </td>
                                                <td class="product-name">
                                                    <?php if ($row['prd_preorder'] == 0) { ?>
                                                        <a href="product_detail?id=<?php echo $base64Encoded; ?>"><?php echo "<span class='text-warning'>[พรีออเดอร์] </span>" . $row['prd_name'] ?></a>
                                                    <?php } else { ?>
                                                        <a href="product_detail?id=<?php echo $base64Encoded; ?>"><?php echo $row['prd_name'] ?></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="product-price">
                                                    <span class="amount"><?php echo "฿" . number_format($row['price_sale'], 2) ?></span>
                                                    <?php if ($row['prd_percent_discount'] > 0) { ?>
                                                        <span class="text-danger"><sup><?php echo "-" . number_format($row['prd_percent_discount']) . "%" ?></sup></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="product-quantity">
                                                    <input type="number" name="crt_qty[]" class="crt_qty" data-price="<?php echo $row['price_sale']; ?>" data-coin="<?php echo $row['prd_coin']; ?>" data-max="<?php echo $row['prd_quantity']; ?>" style="background-color: transparent;" value="<?php echo $row['crt_qty'] ?>" readonly>

                                                </td>
                                                <td class="product-subtotal coin" data-coin="<?php echo $row['prd_coin']; ?>"><?php echo number_format($row['coins_per_item']); ?></td>

                                                <td class="product-subtotal total-sum"><?php echo "฿" . number_format($row['total_price_sale'], 2) ?></td>
                                                <td class="product-remove">
                                                    <button type="button" data-id="<?php echo $row["crt_id"]; ?>" class="border border-0 btn-delete" style="background-color: transparent;">
                                                        <i class="fa-solid fa-trash text-danger"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8 col-md-6 col-12">
                            <div class="buttons-cart mb-30">
                                <ul>
                                    <li>
                                        <a href="cart">
                                            <i class="fa-solid fa-right-from-bracket fa-rotate-180"></i>
                                            กลับไปยังหน้ารถเข็นสินค้า
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <?php if ($row["mem_coin"] > 0) { ?>
                                <div class="coupon">
                                    <h3>ใช้เหรียญเป็นส่วนลด (คุณมีอยู่ <?php echo number_format($row["mem_coin"]); ?> เหรียญ)</h3>
                                    <input name="total_price_all_product" type="hidden" value="<?php echo $totalPriceAllProduct; ?>" readonly>
                                    <input name="my_coin" type="hidden" value="<?php echo $row["mem_coin"]; ?>" readonly>
                                    <input id="coin_discount" name="coin_discount" type="number" placeholder="ระบุจำนวนเหรียญ" min="1" step="1">
                                    <a href="#" id="confirmCoinUsage">
                                        <i class="fa-solid fa-right-left"></i>
                                        ยืนยันใช้เหรียญ
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="cart_totals">
                                <h2>สรุปรายการสั่งซื้อ</h2>
                                <table>
                                    <tbody>
                                        <tr class="cart-subtotal">
                                            <th>ค่าสินค้าทั้งหมด : </th>
                                            <td>
                                                <span class="amount text-dark total-price-cost"><?php echo "฿" . number_format($totalPriceAllProduct, 2); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="cart-subtotal">
                                            <th>เหรียญที่ใช้เป็นส่วนลด : </th>
                                            <td>
                                                <input type="hidden" name="coins_discount" value="0" readonly>
                                                <span class="amount text-warning coins-discount">0</span>
                                            </td>
                                        </tr>
                                        <?php if ($Promotions) { ?>
                                            <tr class="shipping">
                                                <th class="mt-1">โปรโมชั่นส่วนลด : </th>
                                                <td>
                                                    <select id="promotion-select" name="pro_id" class="form-select form-select-sm" aria-label="Default select example">
                                                        <option value="">ไม่ใช้โปรโมชั่น</option>
                                                        <?php foreach ($Promotions as $promo) { ?>
                                                            <option value="<?php echo $promo['pro_id']; ?>" data-pro-percent-discount="<?php echo $promo['pro_percent_discount']; ?>">
                                                                <?php echo $promo['pro_name'] . " (-" . $promo['pro_percent_discount'] . "%)"; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <tr class="shipping">
                                            <th class="mt-1">ช่องทางการส่ง : </th>
                                            <td>
                                                <select id="shipping-select" name="shp_id" class="form-select form-select-sm" aria-label="Default select example">

                                                    <?php if ($shippings) { ?>
                                                        <?php foreach ($shippings as $ship) { ?>
                                                            <option value="<?php echo $ship['shp_id']; ?>" data-shp-price="<?php echo $ship['shp_price']; ?>">
                                                                <?php echo $ship['shp_name'] . " (฿" . $ship['shp_price'] . ")" ?>
                                                            </option>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <option value="">ไม่พบช่องทางขนส่ง</option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="shipping">
                                            <th class="mt-1">ช่องทางชำระเงิน : </th>
                                            <td>
                                                <select name="pmt_id" class="form-select form-select-sm" aria-label="Default select example">
                                                    <?php foreach ($payments as $payment) { ?>
                                                        <option value="<?php echo $payment['pmt_id']; ?>"><?php echo $payment['pmt_bank']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="cart-subtotal">
                                            <th>เหรียญที่จะได้รับ : </th>
                                            <td>
                                                <span class="text-success coins-received"><?php echo "+" . number_format($totalCoinAllProduct) . " เหรียญ"; ?></span>
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <th class="text-danger">ราคาทั้งหมด</th>
                                            <td>
                                                <strong>
                                                    <span class="amount net-price"><?php echo "฿" . number_format($totalPriceAllProduct, 2); ?></span>
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="wc-proceed-to-checkout">
                                    <button type="submit" name="btn-checkout-product" class="w-100 text-center fs-6">
                                        <i class="fa-solid fa-check"></i>
                                        ยืนยันรายการสั่งซื้อสินค้า
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </div>
        <!-- cart-main-area-end -->

    <?php  } else { ?>
        <div class="entry-header-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="entry-header-title">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cart-main-area mb-70 h-25">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="entry-header text-center mb-20">
                            <h3 class="">ไม่มีสินค้าอยู่ในรถเข็น</h3>
                        </div>
                        <div class="entry-content text-center ms-3">
                            <a href="products_show" class="btn btn-sqr">เลือกซื้อสินค้าต่อ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php  } ?>

    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>

    <!-- Delete  -->
    <script>
        $(document).ready(function() {
            $(".btn-delete").click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                deleteConfirm(id);
            });
        });

        function deleteConfirm(id) {
            Swal.fire({
                icon: "warning",
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการลบสินค้านี้ออกจากรถเข็นใช่ไหม!",
                showCancelButton: true,
                confirmButtonColor: '#f34e4e',
                confirmButtonText: 'ใช่, ลบสินค้าเลย!',
                cancelButtonText: 'ยกเลิก',
                preConfirm: function() {
                    return $.ajax({
                            url: 'process/cart_del.php',
                            type: 'POST',
                            data: {
                                id: id,
                            },
                        })
                        .done(function() {
                            // การลบสำเร็จ ทำการ redirect ไปยังหน้า product_show
                            return true;
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สำเร็จ',
                                text: 'เกิดข้อผิดพลาดที่ ajax !',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.location.href = 'checkout_product';
                                }
                            });
                        });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = 'checkout_product';
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {

            // ฟังก์ชันสำหรับอัพเดทส่วนลดเหรียญ
            function updateCoinDiscount() {
                const coinDiscountInput = $('#coin_discount');
                let coinDiscount = parseFloat(coinDiscountInput.val().trim()) || 0;
                const totalPriceAllProduct = Math.floor(parseFloat($('input[name="total_price_all_product"]').val()));
                const myCoin = parseFloat($('input[name="my_coin"]').val());

                // ตรวจสอบและปรับค่า coinDiscount
                coinDiscount = validateCoinDiscount(coinDiscount, totalPriceAllProduct, myCoin);
                coinDiscountInput.val(coinDiscount); // อัพเดทค่าลงในฟิลด์อินพุต
            }

            // ฟังก์ชันตรวจสอบค่าเหรียญที่ใช้
            function validateCoinDiscount(coinDiscount, totalPriceAllProduct, myCoin) {
                if (isNaN(coinDiscount) || coinDiscount <= 0) {
                    return 0;
                }
                return Math.min(coinDiscount, totalPriceAllProduct, myCoin);
            }

            // ฟังก์ชันอัพเดทราคาสุทธิ
            function updateNetPrice() {
                const totalPriceAllProduct = parseFloat($('input[name="total_price_all_product"]').val());
                const coinDiscount = parseFloat($('input[name="coins_discount"]').val()) || 0;

                const netPriceAfterCoin = totalPriceAllProduct - coinDiscount;
                const discountPercent = getSelectedPromotionDiscount();
                const netPrice = netPriceAfterCoin * (1 - discountPercent / 100);

                const shippingPrice = getSelectedShippingPrice();
                const finalPrice = netPrice + shippingPrice;

                // แสดงราคาสุทธิที่คำนวณ
                displayNetPrice(finalPrice);
            }

            // ฟังก์ชันดึงเปอร์เซ็นต์ส่วนลดโปรโมชั่นที่เลือก
            function getSelectedPromotionDiscount() {
                const selectedPromotion = $('#promotion-select').find(':selected');
                return parseFloat(selectedPromotion.data('pro-percent-discount')) || 0;
            }

            // ฟังก์ชันดึงราคาค่าส่งสินค้าที่เลือก
            function getSelectedShippingPrice() {
                const selectedShipping = $('#shipping-select').find(':selected');
                return parseFloat(selectedShipping.data('shp-price')) || 0;
            }

            // ฟังก์ชันแสดงราคาสุทธิ
            function displayNetPrice(finalPrice) {
                $('.net-price').text("฿" + finalPrice.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }

            // ฟังก์ชันการยืนยันการใช้เหรียญ
            function confirmCoinUsage() {
                const coinDiscount = parseFloat($('#coin_discount').val().trim()) || 0;

                if ($('#coin_discount').val().trim() === '') {
                    Swal.fire({
                        title: 'คำเตือน',
                        text: 'กรุณากรอก จำนวนเหรียญที่ถูกต้อง',
                        icon: 'warning',
                        confirmButtonText: 'ตกลง'
                    });
                } else {
                    $('input[name="coins_discount"]').val(coinDiscount);
                    $('.coins-discount').text(coinDiscount.toLocaleString());

                    Swal.fire({
                        title: 'ยืนยันการใช้เหรียญ',
                        text: `คุณใช้ ${coinDiscount.toLocaleString()} เหรียญ เป็นส่วนลด`,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(updateNetPrice); // อัพเดทราคาสุทธิหลังจากการยืนยัน
                }
            }

            // กำหนด event handler สำหรับปุ่มและ dropdown
            $('#confirmCoinUsage').on('click', function(event) {
                event.preventDefault();
                confirmCoinUsage();
            });

            $('#promotion-select').on('change', updateNetPrice);
            $('#coin_discount').on('input', updateCoinDiscount);
            $('#shipping-select').on('change', updateNetPrice);

            // เรียกใช้ฟังก์ชัน updateNetPrice เมื่อโหลดหน้าเว็บ
            updateNetPrice();
        });
    </script>


    <!-- <script>
        $(document).ready(function() {
            function updateCoinDiscount() {
                let coinDiscountInput = $('#coin_discount');
                let coinDiscount = parseFloat(coinDiscountInput.val().trim()) || 0;
                let totalPriceAllProduct = parseFloat($('input[name="total_price_all_product"]').val());
                totalPriceAllProduct = Math.floor(totalPriceAllProduct); // ปัดลงเป็นจำนวนเต็ม
                let myCoin = parseFloat($('input[name="my_coin"]').val());

                // Validate and adjust coinDiscount based on conditions
                if (isNaN(coinDiscount) || coinDiscount <= 0) {
                    coinDiscount = 0;
                } else {
                    // Ensure coinDiscount does not exceed totalPriceAllProduct
                    coinDiscount = Math.min(coinDiscount, totalPriceAllProduct);
                    // Ensure coinDiscount does not exceed myCoin
                    coinDiscount = Math.min(coinDiscount, myCoin);
                }

                coinDiscountInput.val(coinDiscount); // Update the input field with new value


            }

            function updateNetPrice() {
                let totalPriceAllProduct = parseFloat($('input[name="total_price_all_product"]').val());
                let coinDiscount = parseFloat($('input[name="coins_discount"]').val()) || 0;

                // Calculate net price after coin discount
                let netPriceAfterCoin = totalPriceAllProduct - coinDiscount;

                // Get selected promotion discount
                let selectedPromotion = $('#promotion-select').find(':selected');
                let discountPercent = parseFloat(selectedPromotion.data('pro-percent-discount')) || 0;

                // Calculate new price after promotion
                let netPrice = netPriceAfterCoin * (1 - discountPercent / 100);

                // Get selected shipping price
                let selectedShipping = $('#shipping-select').find(':selected');
                let shippingPrice = parseFloat(selectedShipping.data('shp-price')) || 0;

                // Calculate final price including shipping
                let finalPrice = netPrice + shippingPrice;

                // Update final price display
                $('.net-price').text("฿" + finalPrice.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }

            $('#confirmCoinUsage').on('click', function(event) {
                event.preventDefault();
                let coinDiscount = parseFloat($('#coin_discount').val().trim()) || 0;

                if ($('#coin_discount').val().trim() === '') {
                    Swal.fire({
                        title: 'คำเตือน',
                        text: 'กรุณากรอก จำนวนเหรียญที่ถูกต้อง',
                        icon: 'warning',
                        confirmButtonText: 'ตกลง'
                    });
                } else {
                    // Update the input field and display (for confirmation)
                    $('input[name="coins_discount"]').val(coinDiscount);
                    $('.coins-discount').text(coinDiscount.toLocaleString());

                    // Update the display of coins-discount
                    $('.coins-discount').text(coinDiscount.toLocaleString());

                    Swal.fire({
                        title: 'ยืนยันการใช้เหรียญ',
                        text: `คุณใช้ ${coinDiscount.toLocaleString()} เหรียญ เป็นส่วนลด`,
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Update final price after confirmation
                        updateNetPrice();
                    });
                }
            });

            $('#promotion-select').on('change', function() {
                updateNetPrice();
            });

            $('#coin_discount').on('input', function() {
                // Update coin discount value in the input field but don't change <td> until confirmation
                updateCoinDiscount();
            });

            $('#shipping-select').on('change', function() {
                updateNetPrice();
            });

            // Call updateNetPrice on page load
            updateNetPrice();
        });
    </script> -->

</html>
<?php require_once('includes/sweetalert2.php') ?>