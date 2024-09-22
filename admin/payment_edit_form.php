<?php
$titlePage = "แก้ไขช่องทางชำระเงิน";

require_once("../db/connectdb.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");
require_once("../db/controller/PaymentController.php");
require_once('../db/controller/LoginController.php');

$LoginController = new LoginController($conn);
$PaymentController = new PaymentController($conn);


if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $pmtId = decodeBase64ID($base64Encoded, $salt1, $salt2);
    
    $payment = $PaymentController->getDetailPayment($pmtId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($payment);

    $empId = $_SESSION['emp_id'];
    
    // ตรวจสอบสิทธิ์การใช้งาน
    $useAuthority = $LoginController->useLoginEmployees($empId);
    $allowedAuthorities = [1, 3, 4]; // [Super Admin, Admin, Accounting]
    checkAuthorityEmployees($useAuthority, $allowedAuthorities);
} else {
    header('Location: product_show');
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
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>ข้อมูลช่องทางชำระเงิน</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสช่องทางชำระเงิน :</label>
                                            <p><?php echo  $payment['pmt_id']; ?></p>
                                            <input type="hidden" name="pmt_id" class="form-control" value="<?php echo $payment['pmt_id']; ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pmt_bank" class="form-label">ชื่อธนาคาร :</label><span class="text-danger">*</span>
                                            <input type="text" name="pmt_bank" class="form-control" placeholder="ระบุ ชื่อธนาคาร" maxlength="100" value="<?php echo $payment['pmt_bank'] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pmt_name" class="form-label">ชื่อบัญชี :</label><span class="text-danger">*</span>
                                            <input type="text" name="pmt_name" class="form-control" placeholder="ระบุ ชื่อบัญชี" maxlength="100" value="<?php echo $payment['pmt_name'] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pmt_number" class="form-label">หมายเลขบัญชี :</label><span class="text-danger">*</span>
                                            <input type="text" name="pmt_number" class="form-control" placeholder="ระบุ หมายเลขบัญชี" maxlength="10" value="<?php echo $payment['pmt_number'] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pmt_detail" class="form-label">รายละเอียด :</label><span class="text-danger">*</span>
                                            <textarea name="pmt_detail" class="form-control" placeholder="ระบุ รายละเอียด" maxlength="255"><?php echo $payment['pmt_detail'] ?></textarea>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->


                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
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
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label mt-2">รูปภาพใหม่ :</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                            <input class="form-control" name="pmt_bank_new_logo" id="pmt_bank_new_logo" type="file" accept="image/png,image/jpg,image/jpeg">
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
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
                                                <button class="btn btn-danger float-end btn-delete-img" data-id="<?php echo $payment["pmt_id"]; ?>" data-img="<?php echo $payment['pmt_qrcode']; ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                    ลบรูปภาพ
                                                </button>
                                            <?php } ?>
                                            <input type="hidden" name="pmt_qrcode" value="<?php echo $payment['pmt_qrcode']; ?>" readonly>

                                        </div>
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label mt-2">รูปภาพใหม่ :</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                            <input class="form-control" name="pmt_new_qrcode" id="pmt_new_qrcode" type="file" accept="image/png,image/jpg,image/jpeg">
                                        </div>
                                    </div> <!-- end card-body-->
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-2 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>สถานะการแสดง <small class="text-danger">(แสดงได้ 1 ช่องทาง เท่านั้น)</small></span>
                                        </h4>
                                        <input type="hidden" name="pmt_old_status" value="<?php echo $payment['pmt_status']; ?>" readonly>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="pmt_status" id="1" value="1" <?php if ($payment['pmt_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="pmt_status" id="0" value="0" <?php if ($payment['pmt_status'] != 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="0">ไม่แสดง</label>
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $payment['pmt_time_update'] ?></span></h4>
                                        <div>
                                            <a href="payment_show" class="btn btn-secondary me-2">
                                                <i class="fa-solid fa-xmark me-1"></i>
                                                <span>ยกเลิก</span>
                                            </a>
                                            <button type="submit" name="btn-edit" class="btn btn-warning">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>บันทึกการแก้ไข</span>
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

        <!-- preview New Profile, check file type, file size  -->
        <script>
            const handleFileChange = (inputId, imgId, containerId, previewId, originalSrc) => {
                document.getElementById(inputId).addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                    const maxSize = 2 * 1024 * 1024; // 2 MB in bytes
                    const imgElement = document.getElementById(imgId);
                    const imgContainer = document.getElementById(containerId);
                    const newPreviewElement = document.getElementById(previewId);

                    const resetToOriginal = () => {
                        if (imgElement) {
                            imgElement.src = originalSrc;
                        } else {
                            imgContainer.style.display = 'none';
                            newPreviewElement.src = '';
                        }
                    };

                    const showWarning = (message) => {
                        Swal.fire({
                            icon: 'warning',
                            title: 'คำเตือน',
                            text: message
                        });
                    };

                    if (file && allowedTypes.includes(file.type) && file.size <= maxSize) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (imgElement) {
                                imgElement.src = e.target.result;
                            } else {
                                newPreviewElement.src = e.target.result;
                                imgContainer.style.display = 'block';
                            }
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Reset the input
                        event.target.value = '';
                        // Reset to the original image or hide the preview if no original image
                        resetToOriginal();

                        // Show alert if the file type or size is invalid
                        if (!allowedTypes.includes(file.type)) {
                            showWarning('ต้องเป็นไฟล์ .png .jpg .jpeg เท่านั้น');
                        } else if (file.size > maxSize) {
                            showWarning('ขนาดไฟล์เกิน 2 MB');
                        }
                    }
                });
            };

            // Initialize file change handlers
            handleFileChange('pmt_bank_new_logo', 'pmt_bank_logo', 'img-container1', 'new_preview1', '../uploads/img_payment/<?php echo $payment["pmt_bank_logo"]; ?>');
            handleFileChange('pmt_new_qrcode', 'pmt_qrcode', 'img-container2', 'new_preview2', '../uploads/img_payment/<?php echo $payment["pmt_qrcode"]; ?>');
        </script>


        // Delete Img
        <script>
            $(document).ready(function() {
                $(".btn-delete-img").click(function(e) {
                    e.preventDefault();
                    let id = $(this).data('id');
                    let img = $(this).data('img');

                    deleteConfirm(id, img);
                });
            });

            function deleteConfirm(id, img) {
                Swal.fire({
                    icon: "warning",
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "คุณต้องการลบรูปภาพนี้ใช่ไหม!",
                    showCancelButton: true,
                    confirmButtonColor: '#f34e4e',
                    confirmButtonText: 'ใช่, ลบรูปภาพเลย!',
                    cancelButtonText: 'ยกเลิก',
                    preConfirm: function() {
                        return $.ajax({
                                url: 'process/payment_img_del.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    img: img,
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า payment_edit_form
                                document.location.href = 'payment_edit_form?id=<?php echo $base64Encoded; ?>';
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'payment_edit_form?id=<?php echo $base64Encoded; ?>';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'payment_edit_form?id=<?php echo $base64Encoded; ?>';
                    }
                });
            }
        </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>