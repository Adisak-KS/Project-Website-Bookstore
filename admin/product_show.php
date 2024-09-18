<?php
$titlePage = "สินค้า";

require_once("../db/connectdb.php");
require_once("../db/controller/ProductController.php");
require_once("../db/controller/SettingWebsiteController.php");
require_once('../db/controller/LoginController.php');

$LoginController = new LoginController($conn);
$SettingWebsiteController = new SettingWebsiteController($conn);
$ProductController = new ProductController($conn);


$empId = $_SESSION['emp_id'];

// ตรวจสอบสิทธิ์การใช้งาน
$useAuthority = $LoginController->useLoginEmployees($empId);
$allowedAuthorities = [1, 3, 5]; // [Super Admin, Admin, Sale]
checkAuthorityEmployees($useAuthority, $allowedAuthorities);


// ส่วนลด
$productPercentDiscount = $SettingWebsiteController->getProductPercentDiscount();

$products = $ProductController->getProduct();
$productType = $ProductController->getProductType();
$publisher = $ProductController->getPublisher();
$author = $ProductController->getAuthor();

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

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="mt-0 header-title">ข้อมูลสินค้าทั้งหมด</h4>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditPromotion">
                                            <i class="fa-regular fa-square-plus me-1"></i>
                                            <span>ตั้งค่าเมนูส่วนลด</span>
                                        </button>
                                    </div>

                                    <div class="my-3">

                                        <?php if (empty($productType) || empty($publisher) || empty($author)) { ?>

                                            <?php if (empty($productType)) { ?>
                                                <p class="text-danger">* ต้องมีข้อมูลประเภทสินค้า อย่างน้อย 1 รายการ <a href="product_type_show">แสดงประเภทสินค้า</a></p>
                                            <?php } elseif (empty($publisher)) { ?>
                                                <p class="text-danger">* ต้องมีข้อมูลสำนักพิมพ์ อย่างน้อย 1 รายการ <a href="publisher_show">แสดงสำนักพิมพ์</a></p>
                                            <?php } elseif (empty($author)) { ?>
                                                <p class="text-danger">* ต้องมีข้อมูลชื่อผู้แต่ง อย่างน้อย 1 รายการ <a href="author_show">แสดงผู้แต่ง</a></p>
                                            <?php } ?>
                                            <button class="btn btn-success" disabled>
                                                <i class="fa-regular fa-square-plus me-1"></i>
                                                <span>เพิ่มสินค้า</span>
                                            </button>

                                        <?php } else { ?>
                                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                                <i class="fa-regular fa-square-plus me-1"></i>
                                                <span>เพิ่มสินค้า</span>
                                            </button>
                                        <?php } ?>


                                        <hr>

                                        <!-- Scrollable modal -->
                                        <form id="formProduct" novalidate action="process/product_add.php" method="post">
                                            <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAdd" data-bs-backdrop="static" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มสินค้า</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="prd_name" class="form-label">ชื่อสินค้า :</label><span class="text-danger">*</span>
                                                                <input type="text" name="prd_name" class="form-control" placeholder="ระบุ ชื่อสินค้า" maxlength="100">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="prd_isbn" class="form-label">รหัส ISBN :</label><span class="text-danger">*</span>
                                                                <input type="text" name="prd_isbn" class="form-control" placeholder="ระบุ รหัส ISBN สินค้า" maxlength="13">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="prd_coin" class="form-label">เหรียญที่จะได้รับ :</label><span class="text-danger">*</span>
                                                                <input type="number" name="prd_coin" class="form-control" placeholder="ระบุ จำนวน เหรียญที่จะได้รับ" value="0">
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="prd_quantity" class="form-label">จำนวนสินค้า :</label><span class="text-danger">*</span>
                                                                <input type="number" name="prd_quantity" class="form-control" placeholder="ระบุ จำนวนสินค้า">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="prd_number_pages" class="form-label">จำนวนหน้าหนังสือ :</label><span class="text-danger">*</span>
                                                                <input type="number" name="prd_number_pages" class="form-control" placeholder="ระบุ จำนวนหน้าหนังสือ">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="prd_price" class="form-label">ราคาสินค้า :</label><span class="text-danger">*</span>
                                                                <div class="input-group">
                                                                    <input type="number" name="prd_price" class="form-control" placeholder="ระบุ ราคาสินค้า">
                                                                    <span class="input-group-text">บาท</span>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="prd_percent_discount" class="form-label">ส่วนลดสินค้า (เฉพาะชิ้นนี้) :</label><span class="text-danger">*</span>
                                                                <div class="input-group">
                                                                    <input type="number" name="prd_percent_discount" class="form-control" placeholder="ระบุ ส่วนลดสินค้า (เฉพาะชิ้นนี้)" value="0">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="pty_id" class="form-label">ประเภทสินค้า :</label><span class="text-danger">*</span>
                                                                <select class="form-select" name="pty_id">
                                                                    <option value="" selected>กรุณาระบุ ประเภทสินค้า</option>
                                                                    <?php foreach ($productType as $row) { ?>
                                                                        <option value="<?php echo $row['pty_id'] ?>"><?php echo $row['pty_name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="pub_id" class="form-label">ชื่อสำนักพิมพ์ :</label><span class="text-danger">*</span>
                                                                <select class="form-select" name="pub_id">
                                                                    <option value="" selected>กรุณาระบุ สำนักพิมพ์</option>

                                                                    <?php foreach ($publisher as $row) { ?>
                                                                        <option value="<?php echo $row['pub_id'] ?>"><?php echo $row['pub_name']; ?></option>
                                                                    <?php } ?>

                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="auth_id" class="form-label">ชื่อผู้แต่ง :</label><span class="text-danger">*</span>
                                                                <select class="form-select" name="auth_id">
                                                                    <option value="" selected>กรุณาระบุ ชื่อผู้แต่ง</option>
                                                                    <?php foreach ($author as $row) { ?>
                                                                        <option value="<?php echo $row['auth_id'] ?>"><?php echo $row['auth_name']; ?></option>
                                                                    <?php } ?>

                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="prd_preorder" class="form-label"> ชนิดสินค้า :</label><span class="text-danger">*</span>
                                                                <div class="form-check mb-2 form-check-primary">
                                                                    <input class="form-check-input" type="radio" name="prd_preorder" id="1" value="1" checked>
                                                                    <label class="form-check-label" for="1">สินค้าปกติ</label>
                                                                </div>
                                                                <div class="form-check mb-2 form-check-warning">
                                                                    <input class="form-check-input" type="radio" name="prd_preorder" id="0" value="0">
                                                                    <label class="form-check-label" for="0">สินค้าพรีออเดอร์</label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="prd_status" class="form-label">สถานะการแสดง :</label><span class="text-danger">*</span>
                                                                <div class="form-check mb-2 form-check-success">
                                                                    <input class="form-check-input" type="radio" name="prd_status" id="1" value="1" checked>
                                                                    <label class="form-check-label" for="1">แสดง</label>
                                                                </div>
                                                                <div class="form-check mb-2 form-check-danger">
                                                                    <input class="form-check-input" type="radio" name="prd_status" id="0" value="0">
                                                                    <label class="form-check-label" for="0">ไม่แสดง</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                <i class="fa-solid fa-xmark me-1"></i>
                                                                <span>ยกเลิก</span>
                                                            </button>
                                                            <button type="submit" name="btn-add" class="btn btn-success">
                                                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                                                <span>บันทึก</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- Scrollable modal -->
                                        <form id="formSettingPercentDiscount" novalidate action="process/product_percent_discount_edit.php" method="post">
                                            <div class="modal fade" id="modalEditPromotion" tabindex="-1" aria-labelledby="modalEditPromotionLabel" data-bs-backdrop="static" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">ตั้งค่าการแสดงสินค้าในเมนูส่วนลด</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <input type="hidden" name="old_prd_percent_discount" value="<?php echo $productPercentDiscount ?>" readonly>
                                                            <div class="mb-3">
                                                                <label for="prd_percent_discount" class="form-label">ส่วนลดสินค้า :</label><span class="text-danger">*</span>
                                                                <div class="input-group">
                                                                    <input type="number" name="prd_percent_discount" class="form-control" placeholder="ระบุ ส่วนลดสินค้า" value="<?php echo $productPercentDiscount ?>">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                <i class="fa-solid fa-xmark me-1"></i>
                                                                <span>ยกเลิก</span>
                                                            </button>
                                                            <button type="submit" name="btn-edit" class="btn btn-warning">
                                                                <i class="fa-solid fa-gear me-1"></i>
                                                                <span>บันทึกการตั้งค่า</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                    <?php if ($products) { ?>
                                        <table id="MyTable" class="table  table-bordered table-hover dt-responsive table-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">รูป</th>
                                                    <th class="text-center">ชื่อสินค้า</th>
                                                    <th class="text-center">ราคา</th>
                                                    <th class="text-center">ส่วนลด</th>
                                                    <th class="text-center">ประเภทสินค้า</th>
                                                    <th class="text-center">ชนิดสินค้า</th>
                                                    <th class="text-center">สถานะ</th>
                                                    <th class="text-center">จัดการข้อมูล</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($products as $row) { ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <img class="rounded" width="40px" height="50px" src="../uploads/img_product/<?php echo $row['prd_img1'] ?>">
                                                        </td>
                                                        <td class="text-start">
                                                            <?php echo mb_substr($row['prd_name'], 0, 20, 'utf-8');
                                                            if (mb_strlen($row['prd_name'], 'utf-8') > 20) echo '...';
                                                            ?>
                                                        </td>

                                                        <td class="text-start"><?php echo "฿" . number_format($row['prd_price'], 2); ?></td>
                                                        <td class="text-start"><?php echo number_format($row['prd_percent_discount']) . " %"; ?></td>
                                                        <td class="text-center"><?php echo $row['pty_name'] ?></td>
                                                        <td class="text-center">
                                                            <?php if ($row['prd_preorder'] == 1) { ?>
                                                                <span class="badge rounded-pill bg-primary fs-6">ปกติ</span>
                                                            <?php } else { ?>
                                                                <span class="badge rounded-pill bg-warning text-dark fs-6">พรีออเดอร์</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($row['prd_status'] == 1) { ?>
                                                                <span class="badge rounded-pill bg-success fs-6">แสดง</span>
                                                            <?php } else { ?>
                                                                <span class="badge rounded-pill bg-danger fs-6">ไม่แสดง</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $originalId = $row["prd_id"];
                                                            require_once("../includes/salt.php");   // รหัส Salt 
                                                            $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                                            $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                                            ?>


                                                            <a href="product_edit_form?id=<?php echo $base64Encoded ?>" class="btn btn-warning">
                                                                <i class="fa-solid fa-pen-to-square me-1"></i>
                                                                <span>แก้ไข</span>
                                                            </a>

                                                            <a href="product_del_form?id=<?php echo $base64Encoded ?>" class="btn btn-danger ms-2">
                                                                <i class="fa-solid fa-trash me-1"></i>
                                                                <span>ลบข้อมูล</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <?php require_once("./includes/no_information.php") ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                </div> <!-- container -->

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
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>