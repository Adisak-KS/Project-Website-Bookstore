<?php
$titlePage = "แก้ไขสินค้า";

require_once("../db/connectdb.php");
require_once("../db/controller/ProductController.php");
require_once("../includes/salt.php");
require_once("../admin/includes/functions.php");

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $Id = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $ProductController = new ProductController($conn);
    $product = $ProductController->getDetailProduct($Id);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($product);

    $productType = $ProductController->getProductType();
    $publisher = $ProductController->getPublisher();
    $author = $ProductController->getAuthor();
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

                    <form id="formProduct" action="process/product_edit" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>ข้อมูลสินค้า</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสสินค้า :</label>
                                            <p><?php echo  $product['prd_id']; ?></p>
                                            <input type="hidden" name="prd_id" class="form-control" value="<?php echo $product['prd_id']; ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_name" class="form-label">ชื่อสินค้า :</label><span class="text-danger">*</span>
                                            <input type="text" name="prd_name" class="form-control" placeholder="ระบุ ชื่อสินค้า" maxlength="100" value="<?php echo $product['prd_name']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_isbn" class="form-label">รหัส ISBN :</label><span class="text-danger">*</span>
                                            <input type="text" name="prd_isbn" class="form-control" placeholder="ระบุ รหัส ISBN สินค้า" maxlength="13" value="<?php echo $product['prd_isbn']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_coin" class="form-label">เหรียญที่จะได้รับ :</label><span class="text-danger">*</span>
                                            <input type="number" name="prd_coin" class="form-control" placeholder="ระบุ จำนวน เหรียญที่จะได้รับ" value="<?php echo $product['prd_coin']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="prd_quantity" class="form-label">จำนวนสินค้า :</label><span class="text-danger">*</span>
                                            <input type="number" name="prd_quantity" class="form-control" placeholder="ระบุ จำนวนสินค้า" value="<?php echo $product['prd_quantity']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_number_pages" class="form-label">จำนวนหน้าหนังสือ :</label><span class="text-danger">*</span>
                                            <input type="number" name="prd_number_pages" class="form-control" placeholder="ระบุ จำนวนหน้าหนังสือ" value="<?php echo $product['prd_number_pages']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_price" class="form-label">ราคาสินค้า :</label><span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" name="prd_price" class="form-control" placeholder="ระบุ ราคาสินค้า" value="<?php echo $product['prd_price']; ?>">
                                                <span class="input-group-text">บาท</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_percent_discount" class="form-label">ส่วนลดสินค้า (เฉพาะชิ้นนี้) :</label><span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" name="prd_percent_discount" class="form-control" placeholder="ระบุ ส่วนลดสินค้า (เฉพาะชิ้นนี้)" value="<?php echo $product['prd_percent_discount']; ?>">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pty_id" class="form-label">ประเภทสินค้า :</label><span class="text-danger">*</span>
                                            <input type="hidden" name="old_pty_id" value="<?php echo $product['pty_id']; ?>">
                                            <select class="form-select" name="pty_id">
                                                <option value="" selected>กรุณาระบุ ประเภทสินค้า</option>
                                                <?php foreach ($productType as $row) { ?>
                                                    <option value="<?php echo $row['pty_id'] ?>" <?php if ($product['pty_id'] == $row['pty_id']) {
                                                                                                        echo 'selected';
                                                                                                    } ?>><?php echo $row['pty_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="pub_id" class="form-label">ชื่อสำนักพิมพ์ :</label><span class="text-danger">*</span>
                                            <select class="form-select" name="pub_id">
                                                <option value="" selected>กรุณาระบุ สำนักพิมพ์</option>

                                                <?php foreach ($publisher as $row) { ?>
                                                    <option value="<?php echo $row['pub_id'] ?>" <?php if ($product['pub_id'] == $row['pub_id']) {
                                                                                                        echo 'selected';
                                                                                                    } ?>><?php echo $row['pub_name']; ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="auth_id" class="form-label">ชื่อผู้แต่ง :</label><span class="text-danger">*</span>
                                            <select class="form-select" name="auth_id">
                                                <option value="" selected>กรุณาระบุ ชื่อผู้แต่ง</option>
                                                <?php foreach ($author as $row) { ?>
                                                    <option value="<?php echo $row['auth_id'] ?>" <?php if ($product['auth_id'] == $row['auth_id']) {
                                                                                                        echo 'selected';
                                                                                                    } ?>><?php echo $row['auth_name']; ?></option>

                                                <?php } ?>

                                            </select>
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
                                            <span>รูปภาพหลักของสินค้า</span>
                                        </h4>
                                        <div class="">
                                            <?php if (empty($product['prd_img1'])) { ?>
                                                <p class="text-danger">*ไม่มีรูปภาพหลัก</p>
                                                <div id="img-container1" style="display: none;">
                                                    <img id="new_preview1" class="rounded mx-auto d-block img-fluid" style="width:150px; height:150px; object-fit: cover;">
                                                </div>
                                            <?php } else { ?>
                                                <img class="rounded mx-auto d-block img-fluid" id="prd_img1" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_product/<?php echo $product['prd_img1']; ?>">
                                            <?php } ?>
                                            <input type="hidden" name="prd_img1" value="<?php echo $product['prd_img1']; ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label mt-2">รูปภาพใหม่ :</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                            <input class="form-control" name="prd_newImg1" id="prd_newImg1" type="file" accept="image/png,image/jpg,image/jpeg">
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>รูปภาพรองของสินค้า</span>
                                        </h4>
                                        <div class="">
                                            <?php if (empty($product['prd_img2'])) { ?>
                                                <p class="text-danger">*ไม่มีรูปภาพรอง</p>
                                                <div id="img-container2" style="display: none;">
                                                    <img id="new_preview2" class="rounded mx-auto d-block img-fluid" style="width:150px; height:150px; object-fit: cover;">
                                                </div>
                                            <?php } else { ?>
                                                <img class="rounded mx-auto d-block img-fluid" id="prd_img2" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_product/<?php echo $product['prd_img2']; ?>">
                                                <button class="btn btn-danger float-end btn-delete-img" data-id="<?php echo $product["prd_id"]; ?>" data-img2="<?php echo $product["prd_img2"]; ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                    ลบรูปภาพ
                                                </button>
                                            <?php } ?>
                                            <input type="hidden" name="prd_img2" value="<?php echo $product['prd_img2']; ?>" readonly>

                                        </div>
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label mt-2">รูปภาพใหม่ :</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                            <input class="form-control" name="prd_newImg2" id="prd_newImg2" type="file" accept="image/png,image/jpg,image/jpeg">
                                        </div>
                                    </div> <!-- end card-body-->
                                </div>


                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-2 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>ชนิดสินค้า</span>
                                        </h4>

                                        <div class="mb-3">
                                            <div class="form-check mb-2 form-check-primary">
                                                <input class="form-check-input" type="radio" name="prd_preorder" id="1" value="1" <?php if ($product['prd_preorder'] == 1) {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?>>
                                                <label class="form-check-label" for="1">สินค้าปกติ</label>
                                            </div>
                                            <div class="form-check mb-2 form-check-warning">
                                                <input class="form-check-input" type="radio" name="prd_preorder" id="0" value="0" <?php if ($product['prd_preorder'] != 1) {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?>>
                                                <label class="form-check-label" for="0">สินค้าพรีออเดอร์</label>
                                            </div>
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-2 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>สถานะการแสดง</span>
                                        </h4>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="prd_status" id="1" value="1" <?php if ($product['prd_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="prd_status" id="0" value="0" <?php if ($product['prd_status'] != 1) {
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
                                        <div class="mb-3">
                                            <label for="prd_price" class="form-label">รายละเอียดสินค้า :</label><span class="text-danger">*</span>
                                            <textarea class="form-control" name="prd_detail" id="CKEditor" placeholder="กรุณาระบุ รายละเอียดสินค้า"><?php echo $product['prd_detail']; ?></textarea>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $product['prd_time_update'] ?></span></h4>
                                        <div>
                                            <a href="product_show" class="btn btn-secondary me-2">
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

        <!-- CKEditor -->
        <script type="importmap">
            {
                "imports": {
                    "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.js",
                    "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.0/"
                }
            }
        </script>
        <script type="module" src="assets/js/main-CKEditor.js"></script>

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
            handleFileChange('prd_newImg1', 'prd_img1', 'img-container1', 'new_preview1', '../uploads/img_product/<?php echo $product["prd_img1"]; ?>');
            handleFileChange('prd_newImg2', 'prd_img2', 'img-container2', 'new_preview2', '../uploads/img_product/<?php echo $product["prd_img2"]; ?>');
        </script>

        <script>
            $(document).ready(function() {
                $(".btn-delete-img").click(function(e) {
                    e.preventDefault();
                    let id = $(this).data('id');
                    let img2 = $(this).data('img2');

                    deleteConfirm(id, img2);
                });
            });

            function deleteConfirm(id, img2) {
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
                                url: 'process/product_img_del.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    img2: img2,
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า product_show
                                document.location.href = 'product_edit_form?id=<?php echo $base64Encoded; ?>';
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'product_edit_form?id=<?php echo $base64Encoded; ?>';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'product_edit_form?id=<?php echo $base64Encoded; ?>';
                    }
                });
            }
        </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>