<?php
$titlePage = "ชำระเงิน";

require_once("db/connectdb.php");
require_once("db/controller/OrderController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$OrderController = new OrderController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: login_form');
    exit;
} else {

    $memId = $_SESSION['mem_id'];

    if (isset($_GET['id'])) {
        // กำหนดค่า session สำหรับ base64Encoded
        $_SESSION["base64Encoded"] = $_GET["id"];
        $base64Encoded = $_SESSION["base64Encoded"];

        // ถอดรหัส Id
        $ordId = decodeBase64ID($base64Encoded, $salt1, $salt2);
        $order = $OrderController->getOrderPendingPayment($ordId, $memId);
    } else {
        // หากไม่พบ id ใน URL จะดึงรายการล่าสุดของสมาชิก
        $order = $OrderController->getOrderLatest($memId);
    }

    if ($order) {
        // กำหนดค่าของรายการสั่งซื้อ
        $ordId = $order['ord_id'];
        $memId = $order['mem_id'];
        $ordPrice = $order['ord_price'];
        $ordCoinsDiscount = $order['ord_coins_discount'];
        $ordTimeUpdate = $order['ord_time_update'];
        $pmtId = $order['pmt_id'];

        // ดึงข้อมูลช่องทางชำระเงิน
        $payment = $OrderController->getPaymentForOrder($pmtId);

        if (!$payment) {
            $cancelOrder = $OrderController->cancelOrder($ordId, $memId, $ordCoinsDiscount);

            $_SESSION['error'] = "ไม่พบช่องทางชำระเงินนี้แล้ว เราจึงยกเลิกรายการสั่งซื้อนี้";
            header("Location: account_order_history");
            exit;
        }
    } else {
        header("Location: index");
        exit;
    }
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

    <!-- entry-header-area-start -->
    <div class="entry-header-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header-title text-center">
                        <h5>ชำระเงินรายการสั่งซื้อที่ : <?php echo $ordId; ?></h5>

                        <?php
                        // เพิ่ม 3 วัน
                        $threeDaysLater = strtotime($ordTimeUpdate . ' +3 days');

                        // แปลงและแสดงผลลัพธ์เป็น "วัน / เดือน / เวลา : ชั่วโมง:นาที:วินาที"
                        $formattedDate = date('d / m / Y เวลา : H:i:s', $threeDaysLater);
                        ?>
                        <p><strong>กรุณาขำระเงินภายใน <span class="text-danger"><?php echo  $formattedDate; ?></span> มิเช่นนั้นรายการสั่งซื้อ<span class="text-danger"> จะถูกยกเลิกอัตโนมัติ</span></strong></p>
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
                <div class="table-content table-responsive mb-15 border-1">
                    <div class="my-3 text-center">

                        <?php if ($payment['pmt_qrcode']) { ?>
                            <img src="uploads/img_payment/<?php echo $payment['pmt_qrcode']; ?>" style="width: 250px; height: 250px;" alt="">
                        <?php } ?>

                        <p class="mt-2"><strong>ธนาคาร :</strong> <?php echo $payment['pmt_bank']; ?></p>
                        <p><strong>ชื่อบัญชี :</strong> <?php echo $payment['pmt_name']; ?></p>
                        <p><strong>หมายเลขบัญชี : </strong><?php echo $payment['pmt_number']; ?></p>
                        <h6 style="color:#f07c29;">จำนวนเงิน : <?php echo "฿" . number_format($ordPrice, 2) . " บาท" ?></h6>

                        <form id="formUploadSlip" action="process/checkout_payment_add.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="ord_id" value="<?php echo $ordId ?>" readonly>
                            <input type="hidden" name="mem_id" value="<?php echo $_SESSION['mem_id']; ?>" readonly>
                            <div class="account-details-form">
                                <div class="col-4 mx-auto">
                                    <hr>
                                    <div class="single-input-item mb-3 text-start">
                                        <label for="" class="form-label text-start d-block">หลักฐานการชำระเงิน <span class="text-danger">* (สลิปต้องไม่เกิน 2 MB)</span></label>
                                        <input type="file" name="osl_slip" class="form-control mx-auto" accept="image/png, image/jpg, image/jpeg" onchange="previewImg(this)">
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <img class="mt-3 previewImage" width="100%" style="object-fit: cover;" alt="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 mx-auto mt-3 wc-proceed-to-checkout">
                                <button type="submit" name="btn-upload-slip" class="w-100">
                                    <i class="fa-solid fa-upload"></i>
                                    ส่งหลักฐานการชำระเงิน
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    <!-- cart-main-area-end -->

    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>

    <script>
        function previewImg(input) {
            const file = input.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // in MB
                const validTypes = ["image/png", "image/jpeg", "image/jpg"];
                const previewImage = document.querySelector('.previewImage'); // ใช้ querySelector แทน id

                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด!',
                        text: 'กรุณาอัปโหลดไฟล์รูปภาพเฉพาะประเภท PNG, JPG, หรือ JPEG เท่านั้น',
                    });
                    input.value = ''; // Clear input file
                    previewImage.src = ''; // Clear preview image
                    previewImage.style.height = ''; // Clear height if not valid
                    return false;
                }
                if (fileSize > 2) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด!',
                        text: 'ขนาดไฟล์รูปภาพต้องไม่เกิน 2MB',
                    });
                    input.value = ''; // Clear input file
                    previewImage.src = ''; // Clear preview image
                    previewImage.style.height = ''; // Clear height if not valid
                    return false;
                }
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.height = '300px'; // Set height
                    previewImage.style.width = '200px'; // Set width
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>