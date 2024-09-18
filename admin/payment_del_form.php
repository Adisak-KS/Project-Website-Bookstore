<?php
$titlePage = "ลบช่องทางชำระเงิน";

require_once("../db/connectdb.php");
require_once("../db/controller/PaymentController.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");
require_once('../db/controller/LoginController.php');

$LoginController = new LoginController($conn);


if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $pmtId = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $PaymentController = new PaymentController($conn);
    $payment = $PaymentController->getDetailPayment($pmtId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($payment);

    $empId = $_SESSION['emp_id'];

    // ตรวจสอบสิทธิ์การใช้งาน
    $useAuthority = $LoginController->useLoginEmployees($empId);
    $allowedAuthorities = [1, 3, 4]; // [Super Admin, Admin, Accounting]
    checkAuthorityEmployees($useAuthority, $allowedAuthorities);
} else {
    header('Location: payment_show');
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

                    <form id="formPayment" action="process/payment_edit" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>ข้อมูลช่องทางชำระเงิน</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสช่องทางชำระเงิน :</label>
                                            <p><?php echo  $payment['pmt_id']; ?></p>
                                            <input type="hidden" name="pmt_id" class="form-control" value="<?php echo $payment['pmt_id']; ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pmt_bank" class="form-label">ชื่อธนาคาร :</label><span class="text-danger">*</span>
                                            <p><?php echo $payment['pmt_bank'] ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pmt_name" class="form-label">ชื่อบัญชี :</label><span class="text-danger">*</span>
                                            <p><?php echo $payment['pmt_name'] ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pmt_number" class="form-label">หมายเลขบัญชี :</label><span class="text-danger">*</span>
                                            <p><?php echo $payment['pmt_number'] ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pmt_detail" class="form-label">รายละเอียด :</label><span class="text-danger">*</span>
                                            <p><?php echo $payment['pmt_detail'] ?></p>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->


                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>รูปภาพโลโก้ ธนาคาร</span>
                                        </h4>
                                        <div class="">
                                            <?php if (empty($payment['pmt_bank_logo'])) { ?>
                                                <p class="text-danger">*ไม่มีรูปภาพโลโก้ ธนาคาร</p>
                                                <div id="img-container1" style="display: none;">
                                                    <img id="new_preview1" class="rounded mx-auto d-block img-fluid" style="width:150px; height:150px; object-fit: cover;">
                                                </div>
                                            <?php } else { ?>
                                                <img class="rounded mx-auto d-block img-fluid" id="pmt_bank_logo" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_payment/<?php echo $payment['pmt_bank_logo']; ?>">
                                            <?php } ?>
                                            <input type="hidden" name="pmt_bank_logo" value="<?php echo $payment['pmt_bank_logo']; ?>" readonly>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>QR Code</span>
                                        </h4>
                                        <div class="">
                                            <?php if (empty($payment['pmt_qrcode'])) { ?>
                                                <p class="text-danger">*ไม่มีรูปภาพ QR Code</p>
                                                <div id="img-container2" style="display: none;">
                                                    <img id="new_preview2" class="rounded mx-auto d-block img-fluid" style="width:150px; height:150px; object-fit: cover;">
                                                </div>
                                            <?php } else { ?>
                                                <img class="rounded mx-auto d-block img-fluid" id="pmt_qrcode" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_payment/<?php echo $payment['pmt_qrcode']; ?>">
                                            <?php } ?>
                                            <input type="hidden" name="pmt_qrcode" value="<?php echo $payment['pmt_qrcode']; ?>" readonly>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-2 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>สถานะการแสดง <small class="text-danger">(แสดงได้ 1 ช่องทาง เท่านั้น)</small></span>
                                        </h4>
                                        <input type="hidden" name="pmt_old_status" value="<?php echo $payment['pmt_status']; ?>" readonly>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="pmt_status" id="1" value="1" <?php if ($payment['pmt_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?> disabled>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="pmt_status" id="0" value="0" <?php if ($payment['pmt_status'] != 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>disabled>
                                            <label class="form-check-label" for="0">ไม่แสดง</label>
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $payment['pmt_time_update'] ?></span></h4>
                                        <div>
                                            <a href="payment_show" class="btn btn-secondary me-2">
                                                <i class="fa-solid fa-xmark me-1"></i>
                                                <span>ยกเลิก</span>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-delete" data-id="<?php echo $payment["pmt_id"]; ?>" data-logo="<?php echo $payment["pmt_bank_logo"]; ?>" data-qrcode="<?php echo $payment["pmt_qrcode"]; ?>">
                                                <i class="fa-solid fa-trash"></i>
                                                <span>ลบข้อมูล</span>
                                            </button>
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <!-- end row -->

                        </div> <!-- container -->
                    </form>
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

        <!-- Delete  -->
        <script>
            $(document).ready(function() {
                $(".btn-delete").click(function(e) {
                    e.preventDefault();
                    let id = $(this).data('id');
                    let pmtLogo = $(this).data('logo');
                    let pmtQrcode = $(this).data('qrcode');

                    deleteConfirm(id, pmtLogo, pmtQrcode);
                });
            });

            function deleteConfirm(id, pmtLogo, pmtQrcode) {
                Swal.fire({
                    icon: "warning",
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณต้องการลบข้อมูลนี้ใช่ไหม!",
                    showCancelButton: true,
                    confirmButtonColor: '#f34e4e',
                    confirmButtonText: 'ใช่, ลบข้อมูลเลย!',
                    cancelButtonText: 'ยกเลิก',
                    preConfirm: function() {
                        return $.ajax({
                                url: 'process/payment_del.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    pmtLogo: pmtLogo,
                                    pmtQrcode: pmtQrcode,
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า payment_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'payment_del_form?id=<?php echo $base64Encoded; ?>';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'payment_show';
                    }
                });
            }
        </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>