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
                <form action="process/cart_edit.php" method="post">
                    <input type="hidden" name="mem_id" value="<?php echo $_SESSION['mem_id'] ?>" readonly>
                    <div class="row">
                        <div class="col-lg-12">
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
                                            require_once("includes/salt.php");
                                            $saltedId = $salt1 . $originalId . $salt2;
                                            $base64Encoded = base64_encode($saltedId);
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
                                                    <input type="number" name="crt_qty[]" class="crt_qty" data-price="<?php echo $row['price_sale']; ?>" data-coin="<?php echo $row['prd_coin']; ?>" data-max="<?php echo $row['prd_quantity']; ?>" style="background-color: transparent;" value="<?php echo $row['crt_qty'] ?>">

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
                                        <a href="products_show">
                                            <i class="fa-solid fa-right-from-bracket fa-rotate-180"></i>
                                            เลือกซื้อสินค้าต่อ
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="cart_totals">
                                <h2>สรุปรายการสินค้า</h2>
                                <table>
                                    <tbody>
                                        <tr class="cart-subtotal">
                                            <th class="pe-5">เหรียญที่จะได้รับ : </th>
                                            <td>
                                                <span class="text-success coins-received">+0</span>
                                            </td>
                                        </tr>
                                        <tr class="order-total">
                                            <th class="text-danger pe-5">ราคาทั้งหมด</th>
                                            <td>
                                                <strong>
                                                    <span class="amount total-price-product">0</span>
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="wc-proceed-to-checkout">
                                    <button type="submit" name="btn-cart-edit" class=" text-center fs-6">
                                        <i class="fa-solid fa-check"></i>
                                        ยืนยันรายการสินค้า
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
            function updateCart() {
                let totalPrice = 0;
                let totalCoins = 0;

                $('.crt_qty').each(function() {
                    const $this = $(this);
                    let quantity = $this.val();
                    const pricePerItem = parseFloat($this.data('price'));
                    const coinPerItem = parseFloat($this.data('coin'));
                    const maxQuantity = parseInt($this.data('max'), 10);

                    // ลบจุดทศนิยมออกและปรับค่าเป็นจำนวนเต็ม
                    quantity = quantity.replace(/\./g, '');

                    // ตรวจสอบค่าที่กรอก
                    if (quantity === '' || parseFloat(quantity) <= 0) {
                        quantity = 1;
                    } else if (parseFloat(quantity) > maxQuantity) {
                        quantity = maxQuantity;
                    }

                    // คำนวณราคาและเหรียญรวม
                    const itemTotalPrice = parseFloat(quantity) * pricePerItem;
                    const itemTotalCoins = parseFloat(quantity) * coinPerItem;

                    // อัปเดตค่าใน input, <td> ของเหรียญ, และ <td> ของราคา
                    $this.val(quantity);
                    $this.closest('tr').find('.product-subtotal.coin').text(number_format_coins(itemTotalCoins));
                    $this.closest('tr').find('.product-subtotal.total-sum').text('฿' + number_format_price(itemTotalPrice));

                    // รวมราคาและเหรียญทั้งหมด
                    totalPrice += itemTotalPrice;
                    totalCoins += itemTotalCoins;
                });

                // อัปเดตค่าเหรียญรวมใน <span>
                $('.coins-received').text('+' + number_format_coins(totalCoins));

                // อัปเดตราคาสุทธิรวมใน <span>
                $('.total-price-product').text('฿' + number_format_price(totalPrice));
            }

            // เรียกใช้ฟังก์ชันเมื่อมีการเปลี่ยนแปลงค่าใน input
            $('.crt_qty').on('input', updateCart);

            function number_format_price(number) {
                // ฟังก์ชันสำหรับการจัดรูปแบบจำนวนเงินให้มีทศนิยม 2 ตำแหน่ง
                return number.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            function number_format_coins(number) {
                // ฟังก์ชันสำหรับการจัดรูปแบบจำนวนเหรียญให้ไม่มีทศนิยม
                return Math.round(number).toLocaleString();
            }

            // เรียกใช้ฟังก์ชันเริ่มต้นเพื่อแสดงผลรวมเริ่มต้น
            updateCart();
        });
    </script>
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>