<?php
$titlePage = "แก้ไขประเภทสินค้า";

require_once("../db/connectdb.php");
require_once("../db/controller/ProductTypeController.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");

if (isset($_GET['id'])) {
    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $Id = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $ProductTypeController = new ProductTypeController($conn);
    $productType = $ProductTypeController->getDetailProductType($Id);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($productType);
} else {
    header('Location: product_type_show');
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
                    <form id="formProductType" action="process/product_type_edit" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- id="formProductType" -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>ข้อมูลประเภทสินค้า</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสประเภทสินค้า :</label>
                                            <p><?php echo  $productType['pty_id']; ?></p>
                                            <input type="hidden" name="pty_id" class="form-control" value="<?php echo $productType['pty_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="id" class="form-label">ยอดเข้าชม (ครั้ง) :</label>
                                            <p>
                                                <?php
                                                if (empty($productType['total_view'])) {
                                                    echo 0;
                                                } else {
                                                    echo  number_format($productType['total_view']);
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pty_name" class="form-label">ชื่อประเภทสินค้า :</label><span class="text-danger">*</span>
                                            <input type="text" name="pty_name" class="form-control" placeholder="ระบุ ชื่อประเภทสินค้า" maxlength="50" value="<?php echo $productType['pty_name']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pty_detail" class="form-label">รายละเอียดประเภทสินค้า :</label><span class="text-danger">*</span>
                                            <textarea name="pty_detail" class="form-control" placeholder="ระบุ รายละเอียดประเภทสินค้า" maxlength="100"><?php echo $productType['pty_detail']; ?></textarea>
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
                                            <span>รูปปก</span>
                                        </h4>

                                        <div class="">
                                            <img class="rounded mx-auto d-block img-fluid" id="pty_cover" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_product_type/<?php echo $productType['pty_cover']; ?>">
                                            <input type="hidden" name="pty_cover" value="<?php echo $productType['pty_cover']; ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label mt-4">รูปภาพปกใหม่ :</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                            <input class="form-control" name="pty_newCover" id="pty_newCover" type="file" accept="image/png,image/jpg,image/jpeg">
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
                                            <input class="form-check-input" type="radio" name="pty_status" id="1" value="1" <?php if ($productType['pty_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="pty_status" id="0" value="0" <?php if ($productType['pty_status'] != 1) {
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
                                        <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $productType['pty_time_update'] ?></span></h4>
                                        <div>
                                            <a href="product_type_show" class="btn btn-secondary me-2">
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
            document.getElementById('pty_newCover').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024; // 2 MB in bytes

                if (file && allowedTypes.includes(file.type) && file.size <= maxSize) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('pty_cover').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Reset the input
                    event.target.value = '';
                    // Reset to the original image
                    document.getElementById('pty_cover').src = '../uploads/img_product_type/<?php echo $productType["pty_cover"]; ?>';
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