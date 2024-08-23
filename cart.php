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
                            <h5>สินค้าในตะกร้า</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- entry-header-area-end -->
        <!-- cart-main-area-start -->
        <div class="cart-main-area mb-70">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="#">
                            <div class="table-content table-responsive mb-15 border-1">
                                <table class="table table-hover table-responsive">
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
                                        <?php foreach ($productCart as $row) { ?>
                                            <?php
                                            $originalId = $row["prd_id"];
                                            require_once("includes/salt.php");   // รหัส Salt 
                                            $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                            $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                            ?>
                                            <tr>
                                                <td class="product-thumbnail">
                                                    <a href="product_detail?id=<?php echo $base64Encoded; ?>">
                                                        <img src="uploads/img_product/<?php echo $row['prd_img1'] ?>" style="height:80px; object-fit: cover;" alt="man" />
                                                    </a>
                                                </td>
                                                <td class="product-name">
                                                    <a href="product_detail?id=<?php echo $base64Encoded; ?>"><?php echo $row['prd_name'] ?></a>
                                                </td>
                                                <td class="product-price">
                                                    <span class="amount"><?php echo "฿" . number_format($row['price_sale'], 2) ?></span>
                                                    <?php if ($row['prd_percent_discount'] > 0) { ?>
                                                        <span class="text-danger"><sup><?php echo "-" . number_format($row['prd_percent_discount']) . "%" ?></sup></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="product-quantity">
                                                    <input name="crt_qty"
                                                        data-max="<?php echo $row['prd_quantity']; ?>"
                                                        data-price="<?php echo $row['price_sale']; ?>"
                                                        data-coin="<?php echo $row['prd_coin']; ?>"
                                                        style="background-color: transparent;"
                                                        type="number"
                                                        value="<?php echo $row['crt_qty'] ?>">
                                                </td>
                                                <td class="product-subtotal coin"><?php echo number_format($row['prd_coin'] * $row['crt_qty']) ?></td>
                                                <td class="product-subtotal total-sum"><?php echo "฿" . number_format($row['price_sale'] * $row['crt_qty'], 2) ?></td>
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
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-6 col-12">
                        <div class="buttons-cart mb-30">
                            <ul>
                                <!-- <li><a href="#">Update Cart</a></li> -->
                                <li><a href="products_show">ซื้อสินค้าต่อ</a></li>
                            </ul>
                        </div>
                        <?php if ($row["mem_coin"] > 0) { ?>
                            <div class="coupon">
                                <h3>ใช้เหรียญเป็นส่วนลด (คุณมีอยู่ <?php echo $row["mem_coin"] ?> เหรียญ)</h3>
                                <form id="coinForm" action="#">
                                    <input type="number" id="coinInput" placeholder="ระบุจำนวนเหรียญ" min="0">
                                    <a href="#" id="applyCoin">ยืนยันใช้เหรียญ</a>
                                </form>
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
                                            <span class="amount total-price-cost"></span>
                                        </td>
                                    </tr>
                                    <tr class="cart-subtotal">
                                        <th>เหรียญที่ใช้เป็นส่วนลด : </th>
                                        <td>
                                            <span class="amount coins-discount">0</span>
                                        </td>
                                    </tr>
                                    <?php if ($Promotions) { ?>
                                        <tr class="shipping">
                                            <th class="mt-1">โปรโมชั่นส่วนลด : </th>
                                            <td>
                                                <ul id="">
                                                    <li class="d-flex justify-content-end">
                                                        <select id="promotion-select" class="form-select form-select-sm" aria-label="Default select example">
                                                            <option value="">ไม่ใช้โปรโมชั่น</option>
                                                            <?php foreach ($Promotions as $row) { ?>
                                                                <option value="<?php echo $row['pro_percent_discount']; ?>">
                                                                    <?php echo $row['pro_name'] . " (-" . $row['pro_percent_discount'] . "%)"; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </li>
                                                    <!-- <li>
                                                    <input type="radio">
                                                    <label> Free Shipping </label>
                                                </li> -->
                                                </ul>
                                                <!-- <a href="#">Calculate Shipping</a> -->
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr class="shipping">
                                        <th class="mt-1">ช่องทางการส่ง : </th>
                                        <td>
                                            <ul id="shipping_method">
                                                <li class="d-flex justify-content-end">
                                                    <select id="shipping-select" class="form-select form-select-sm" aria-label="Default select example">
                                                        <?php foreach ($shippings as $row) { ?>
                                                            <option value="<?php echo $row['shp_price']; ?>">
                                                                <?php echo $row['shp_name'] . " (฿" . $row['shp_price'] . ")" ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </li>
                                                <!-- <li>
                                                    <input type="radio">
                                                    <label> Free Shipping </label>
                                                </li> -->
                                            </ul>
                                            <!-- <a href="#">Calculate Shipping</a> -->
                                        </td>
                                    </tr>
                                    <tr class="shipping">
                                        <th class="mt-1">ช่องทางชำระเงิน : </th>
                                        <td>
                                            <ul id="">
                                                <li class="d-flex justify-content-end">
                                                    <select class="form-select w-75 form-select-sm" aria-label="Default select example">
                                                        <?php foreach ($payments as $row) { ?>
                                                            <option value="<?php echo $row['pmt_id']; ?>"><?php echo $row['pmt_bank']; ?></option>
                                                        <?php } ?>
                                                        <!-- <option selected>Open this select menu</option> -->
                                                    </select>
                                                </li>
                                                <!-- <li>
                                                    <input type="radio">
                                                    <label> Free Shipping </label>
                                                </li> -->
                                            </ul>
                                            <!-- <a href="#">Calculate Shipping</a> -->
                                        </td>
                                    </tr>


                                    <tr class="cart-subtotal">
                                        <th>เหรียญที่จะได้รับ : </th>
                                        <td>
                                            <span class="text-success">+215</span>
                                        </td>
                                    </tr>
                                    <tr class="order-total">
                                        <th class="text-danger">ราคาทั้งหมด</th>
                                        <td>
                                            <strong>
                                                <span class="amount net-price"></span>
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="wc-proceed-to-checkout">
                                <a href="#" class="w-100 text-center fs-6">ยืนยันรายการสั่งซื้อสินค้า</a>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    document.location.href = 'cart';
                                }
                            });
                        });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = 'cart';
                }
            });
        }
    </script>


    <script>
        $(document).ready(function() {
            function validateQty(input) {
                let maxQty = parseInt(input.data('max'));
                let value = parseFloat(input.val());

                value = parseInt(value);

                if (isNaN(value) || value < 1) {
                    input.val(1);
                } else if (value > maxQty) {
                    input.val(maxQty);
                } else {
                    input.val(value);
                }

                updateTotalSum(input);
                updateCoin(input);
                updateGrandTotal();
            }

            function updateTotalSum(input) {
                let quantity = parseInt(input.val());
                let pricePerItem = parseFloat(input.data('price'));
                let totalSum = quantity * pricePerItem;
                input.closest('tr').find('.total-sum').text("฿" + totalSum.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
            }

            function updateCoin(input) {
                let quantity = parseInt(input.val());
                let coinPerItem = parseInt(input.data('coin'));
                let totalCoin = quantity * coinPerItem;
                input.closest('tr').find('.coin').text(totalCoin.toLocaleString());
            }

            function updateGrandTotal() {
                let grandTotal = 0;
                $(".total-sum").each(function() {
                    let sum = parseFloat($(this).text().replace('฿', '').replace(',', ''));
                    grandTotal += sum;
                });
                $(".total-price-cost").data('value', grandTotal);
                $(".total-price-cost").text("฿" + grandTotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
                updateNetPrice();
            }

            function updateNetPrice() {
                let totalPrice = parseFloat($('.total-price-cost').data('value')) || 0;
                let shippingPrice = parseFloat($('#shipping-select').val()) || 0;
                let discountPercent = parseFloat($('#promotion-select').val()) || 0;

                // คำนวณราคาหลังหักส่วนลด
                let discountAmount = totalPrice * (discountPercent / 100);
                let discountedTotal = totalPrice - discountAmount;

                // คำนวณราคาสุทธิรวมค่าขนส่ง
                let netPrice = discountedTotal + shippingPrice;
                $('.net-price').text("฿" + netPrice.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
            }

            $("input[name='crt_qty']").on('input change keyup', function(e) {
                if (e.key === '.' || e.key === ',' || (e.key === '-' && $(this).val().length > 0)) {
                    e.preventDefault();
                }
                validateQty($(this));
            });

            $('#shipping-select, #promotion-select').on('change', function() {
                updateNetPrice();
            });

            updateGrandTotal();
        });
    </script>

    <!-- <script>
        $(document).ready(function() {
            function validateQty(input) {
                let maxQty = parseInt(input.data('max'));
                let value = parseFloat(input.val());

                value = parseInt(value);

                if (isNaN(value) || value < 1) {
                    input.val(1);
                } else if (value > maxQty) {
                    input.val(maxQty);
                } else {
                    input.val(value);
                }

                updateTotalSum(input);
                updateCoin(input);
                updateGrandTotal();
            }

            function updateTotalSum(input) {
                let quantity = parseInt(input.val());
                let pricePerItem = parseFloat(input.data('price'));
                let totalSum = quantity * pricePerItem;
                input.closest('tr').find('.total-sum').text("฿" + totalSum.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
            }

            function updateCoin(input) {
                let quantity = parseInt(input.val());
                let coinPerItem = parseInt(input.data('coin'));
                let totalCoin = quantity * coinPerItem;
                input.closest('tr').find('.coin').text(totalCoin.toLocaleString());
            }

            function updateGrandTotal() {
                let grandTotal = 0;
                $(".total-sum").each(function() {
                    let sum = parseFloat($(this).text().replace('฿', '').replace(',', ''));
                    grandTotal += sum;
                });
                $(".total-price-cost").data('value', grandTotal);
                $(".total-price-cost").text("฿" + grandTotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
                updateNetPrice();
            }

            function updateNetPrice() {
                let totalPrice = parseFloat($('.total-price-cost').data('value')) || 0;
                let shippingPrice = parseFloat($('#shipping-select').val()) || 0;
                let netPrice = totalPrice + shippingPrice;
                $('.net-price').text("฿" + netPrice.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
            }

            $("input[name='crt_qty']").on('input change keyup', function(e) {
                if (e.key === '.' || e.key === ',' || (e.key === '-' && $(this).val().length > 0)) {
                    e.preventDefault();
                }
                validateQty($(this));
            });

            $('#shipping-select').on('change', function() {
                updateNetPrice();
            });

            updateGrandTotal();
        });
    </script> -->

    <!-- <script>
        $(document).ready(function() {
            function validateQty(input) {
                let maxQty = parseInt(input.data('max'));
                let value = parseFloat(input.val());

                value = parseInt(value);

                if (isNaN(value) || value < 1) {
                    input.val(1);
                } else if (value > maxQty) {
                    input.val(maxQty);
                } else {
                    input.val(value);
                }

                updateTotalSum(input);
                updateCoin(input);
                updateGrandTotal();
            }

            function updateTotalSum(input) {
                let quantity = parseInt(input.val());
                let pricePerItem = parseFloat(input.data('price'));
                let totalSum = quantity * pricePerItem;
                input.closest('tr').find('.total-sum').text("฿" + totalSum.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
            }

            function updateCoin(input) {
                let quantity = parseInt(input.val());
                let coinPerItem = parseInt(input.data('coin'));
                let totalCoin = quantity * coinPerItem;
                input.closest('tr').find('.coin').text(totalCoin.toLocaleString());
            }

            function updateGrandTotal() {
                let grandTotal = 0;
                $(".total-sum").each(function() {
                    let sum = parseFloat($(this).text().replace('฿', '').replace(',', ''));
                    grandTotal += sum;
                });
                $(".total-price-cost, .net-price").text("฿" + grandTotal.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
            }

            $("input[name='crt_qty']").on('input change keyup', function(e) {
                if (e.key === '.' || e.key === ',' || (e.key === '-' && $(this).val().length > 0)) {
                    e.preventDefault();
                }
                validateQty($(this));
            });

            $("input[name='crt_qty']").on('change', function() {
                let currentValue = parseInt($(this).val());
                let maxQty = parseInt($(this).data('max'));
                if (currentValue < 1) {
                    $(this).val(1);
                } else if (currentValue > maxQty) {
                    $(this).val(maxQty);
                }
                updateTotalSum($(this));
                updateCoin($(this));
                updateGrandTotal();
            });

            // เรียกใช้ฟังก์ชัน updateGrandTotal เมื่อโหลดหน้าเว็บครั้งแรก
            updateGrandTotal();
        });

        $(document).ready(function() {
            function updateNetPrice() {
                let totalPrice = parseFloat($('.total-price').data('value'));
                let shippingPrice = parseFloat($('#shipping-select').val());

                let netPrice = totalPrice + shippingPrice;
                $('.net-price').text("฿" + netPrice.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
            }

            // เมื่อผู้ใช้เลือกค่าขนส่งใหม่
            $('#shipping-select').on('change', function() {
                updateNetPrice();
            });

            // เรียกใช้ฟังก์ชัน updateNetPrice เมื่อเริ่มต้นหน้า
            updateNetPrice();
        });
    </script> -->


    <!-- <script>
        $(document).ready(function() {
            // ฟังก์ชันในการตรวจสอบและปรับค่าของ crt_qty
            function validateQty(input) {
                let maxQty = parseInt(input.data('max'));
                let value = parseFloat(input.val());

                // ตัดจุดทศนิยมออก
                value = parseInt(value);

                if (isNaN(value) || value < 1) {
                    input.val(1);
                } else if (value > maxQty) {
                    input.val(maxQty);
                } else {
                    input.val(value); // อัปเดตฟิลด์ให้แสดงเฉพาะเลขด้านหน้า
                }

                // อัปเดตราคารวม
                updateTotalSum(input);
                // อัปเดตจำนวนเหรียญ
                updateCoin(input);
            }

            // ฟังก์ชันในการอัปเดตราคารวม
            function updateTotalSum(input) {
                let quantity = parseInt(input.val());
                let pricePerItem = parseFloat(input.data('price'));
                let totalSum = quantity * pricePerItem;
                input.closest('tr').find('.total-sum').text("฿" + totalSum.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));
            }

            // ฟังก์ชันในการอัปเดตจำนวนเหรียญ
            function updateCoin(input) {
                let quantity = parseInt(input.val());
                let coinPerItem = parseInt(input.data('coin'));
                let totalCoin = quantity * coinPerItem;
                input.closest('tr').find('.coin').text(totalCoin.toLocaleString());
            }

            // ตรวจสอบค่าเมื่อมีการเปลี่ยนแปลง input
            $("input[name='crt_qty']").on('input change keyup', function(e) {
                // ป้องกันการใส่ค่าเลขทศนิยมหรือค่าที่ไม่เหมาะสมผ่านคีย์บอร์ด
                if (e.key === '.' || e.key === ',' || (e.key === '-' && $(this).val().length > 0)) {
                    e.preventDefault();
                }
                validateQty($(this));
            });

            // ปรับการเพิ่ม/ลดค่าผ่านปุ่มเพิ่ม/ลดใน input type number
            $("input[name='crt_qty']").on('change', function() {
                let currentValue = parseInt($(this).val());
                let maxQty = parseInt($(this).data('max'));
                if (currentValue < 1) {
                    $(this).val(1);
                } else if (currentValue > maxQty) {
                    $(this).val(maxQty);
                }
                updateTotalSum($(this));
                updateCoin($(this));
            });
        });
    </script> -->






</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>