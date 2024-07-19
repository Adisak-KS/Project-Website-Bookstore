<?php
$titlePage = "แก้ไขโปรโมชั่น";

require_once("../db/connectdb.php");
require_once("../db/controller/PromotionController.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $Id = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $PromotionController = new PromotionController($conn);
    $promotion = $PromotionController->getDetailPromotion($Id);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($promotion);
} else {
    header('Location: promotion_show');
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
                    <form action="process/promotion_edit" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>ข้อมูลโปรโมชั่น</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสโปรโมชั่น :</label>
                                            <p><?php echo  $promotion['pro_id']; ?></p>
                                            <input type="hidden" name="pro_id" class="form-control" value="<?php echo $promotion['pro_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pro_name" class="form-label">ชื่อโปรโมชั่น :</label><span class="text-danger">*</span>
                                            <input type="text" name="pro_name" class="form-control" placeholder="ระบุ ชื่อโปรโมชั่น" maxlength="50" value="<?php echo $promotion['pro_name']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pro_percent_discount" class="form-label">ส่วนลด :</label><span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" name="pro_percent_discount" class="form-control" placeholder="ระบุ ส่วนลดเป็นตัวเลข 0-100" aria-describedby="inputGroupPrepend" min="0" max="100" value="<?php echo $promotion['pro_percent_discount']; ?>">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pro_time_start" class="form-label">เดือน / วัน / ปี เริ่ม :</label><span class="text-danger">*</span>
                                            <input type="datetime-local" class="form-control" name="pro_time_start" id="pro_time_start" value="<?php echo $promotion['pro_time_start']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pro_time_end" class="form-label">เดือน / วัน / ปี สิ้นสุด :</label><span class="text-danger">*</span>
                                            <input type="datetime-local" class="form-control" name="pro_time_end" value="<?php echo $promotion['pro_time_end']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pro_detail" class="form-label">รายละเอียดโปรโมชั่น :</label><span class="text-danger">*</span>
                                            <textarea name="pro_detail" class="form-control" rows="4" placeholder="ระบุ รายละเอียดโปรโมชั่น" maxlength="100"><?php echo $promotion['pro_detail']; ?></textarea>
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
                                            <span>รูปภาพโปรโมชั่น</span>
                                        </h4>

                                        <div class="">
                                            <img class="rounded mx-auto d-block img-fluid" id="pro_img" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_promotion/<?php echo $promotion['pro_img'] ?>">
                                            <input type="hidden" name="pro_img" value="<?php echo $promotion['pro_img'] ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label mt-4">รูปภาพใหม่ :</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                            <input class="form-control" name="pro_newImg" id="pro_newImg" type="file" accept="image/png,image/jpg,image/jpeg">
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>สถานะการแสดง</span>
                                        </h4>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="pro_status" id="1" value="1" <?php if ($promotion['pro_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="pro_status" id="0" value="0" <?php if ($promotion['pro_status'] != 1) {
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
                                        <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $promotion['pro_time_update'] ?></span></h4>
                                        <div>
                                            <a href="promotion_show" class="btn btn-secondary me-2">
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

        <?php require_once('layouts/vender.php') ?>

        <!-- preview New Profile, check file type, file size  -->
        <script>
            document.getElementById('pro_newImg').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024; // 2 MB in bytes

                if (file && allowedTypes.includes(file.type) && file.size <= maxSize) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('pro_img').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Reset the input
                    event.target.value = '';
                    // Reset to the original image
                    document.getElementById('pro_img').src = '../uploads/img_promotion/<?php echo $promotion['pro_img'] ?>';
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'คำเตือน',
                            text: 'ต้องเป็นไฟล์ .png .jpg .jpeg เท่านั้น',
                        });
                    }

                    // Show an alert if the file is not valid
                    if (allowedTypes.includes(file.type) && file.size > maxSize) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'คำเตือน',
                            text: 'ขนาดไฟล์เกิน 2 MB',
                        });
                    }
                }
            });
        </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>