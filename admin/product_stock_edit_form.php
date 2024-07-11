<?php
$titlePage = "แก้ไขจำนวนสินค้า";

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

                    <form id="formProduct" action="process/product_stock_edit" method="post">
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
                                            <p><?php echo $product['prd_name']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_isbn" class="form-label">รหัส ISBN :</label><span class="text-danger">*</span>
                                            <P><?php echo $product['prd_isbn']; ?></P>
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_coin" class="form-label">เหรียญที่จะได้รับ :</label><span class="text-danger">*</span>
                                            <p><?php echo $product['prd_coin']; ?> เหรียญ</p>

                                        </div>

                                        <div class="mb-3">
                                            <label for="prd_quantity" class="form-label">จำนวนสินค้า :</label><span class="text-danger">*</span>
                                            <input type="number" name="prd_quantity" class="form-control" placeholder="ระบุ จำนวนสินค้า" value="<?php echo $product['prd_quantity']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_number_pages" class="form-label">จำนวนหน้าหนังสือ :</label><span class="text-danger">*</span>
                                            <p><?php echo number_format($product['prd_number_pages']) ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_price" class="form-label">ราคาสินค้า :</label><span class="text-danger">*</span>
                                            <p><?php echo number_format($product['prd_price'], 2) . " บาท" ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="prd_percent_discount" class="form-label">ส่วนลดสินค้า (เฉพาะชิ้นนี้) :</label><span class="text-danger">*</span>
                                            <p><?php echo $product['prd_percent_discount']; ?> %</p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pty_id" class="form-label">ประเภทสินค้า :</label><span class="text-danger">*</span>
                                            <input type="hidden" name="old_pty_id" value="<?php echo $product['pty_id']; ?>">
                                            <select class="form-select" name="pty_id" disabled>
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
                                            <select class="form-select" name="pub_id" disabled>
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
                                            <select class="form-select" name="auth_id" disabled>
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
                                            <?php } ?>

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
                                                                                                                                    } ?> disabled>
                                                <label class="form-check-label" for="1">สินค้าปกติ</label>
                                            </div>
                                            <div class="form-check mb-2 form-check-warning">
                                                <input class="form-check-input" type="radio" name="prd_preorder" id="0" value="0" <?php if ($product['prd_preorder'] != 1) {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?> disabled>
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
                                            <?php echo $product['prd_detail']; ?>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $product['prd_time_update'] ?></span></h4>
                                        <div>
                                            <a href="product_stock_show" class="btn btn-secondary me-2">
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

</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>