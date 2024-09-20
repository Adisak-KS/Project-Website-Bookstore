<?php
$titlePage = "สินค้าเหลือยน้อย";

require_once("../db/connectdb.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");
require_once("../db/controller/SettingWebsiteController.php");
require_once("../db/controller/ProductController.php");

$SettingWebsiteController = new SettingWebsiteController($conn);
$ProductController = new ProductController($conn);

$QueryProductNumber = $SettingWebsiteController->getProductNumberLow();
$prdNumberLow = $QueryProductNumber;

$productsLow = $ProductController->getProductLow($prdNumberLow);

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
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="mt-0 header-title">ข้อมูลสินค้า เหลือน้อยกว่า <?php echo number_format($QueryProductNumber) ?> รายการ</h4>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                            <i class="fa-solid fa-gear me-1"></i>
                                            <span>ตั้งค่าแจ้งเตือน</span>
                                        </button>
                                    </div>
                                    <hr>

                                    <!-- Scrollable modal -->
                                    <form id="formProductStockLow" novalidate action="process/product_stock_setting.php" method="post">
                                        <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAdd" data-bs-backdrop="static" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">แจ้งเตือนสินค้าเหลือน้อย</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="prd_number_low" class="form-label">จำนวนสินค้าที่จะแจ้งเตือน :</label><span class="text-danger">*</span>
                                                            <input type="hidden" name="old_prd_number_low" class="form-control" value="<?php echo $prdNumberLow ?>" readonly>
                                                            <input type="number" name="prd_number_low" class="form-control" placeholder="ระบุ จำนวนสินค้า" value="<?php echo $prdNumberLow ?>">
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


                                    <?php if ($productsLow) { ?>
                                        <table id="MyTable" class="table  table-bordered table-hover dt-responsive table-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">รูป</th>
                                                    <th class="text-center">ชื่อสินค้า</th>
                                                    <th class="text-center">จำนวนสินค้า</th>
                                                    <th class="text-center">ประเภทสินค้า</th>
                                                    <th class="text-center">ชนิดสินค้า</th>
                                                    <th class="text-center">สถานะ</th>
                                                    <th class="text-center">จัดการข้อมูล</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($productsLow as $row) { ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <img class="rounded" width="40px" height="50px" src="../uploads/img_product/<?php echo $row['prd_img1'] ?>">
                                                        </td>
                                                        <td class="text-start">
                                                            <?php
                                                            $originalName = $row['prd_name'];
                                                            $shortName = shortenName($originalName);
                                                            echo  $shortName;
                                                            ?>
                                                        </td>

                                                        <td class="text-center"><?php echo number_format($row['prd_quantity']); ?></td>
                                                        <td class="text-center">
                                                            <?php
                                                            $originalName = $row['pty_name'];
                                                            $shortTypeName = shortenName($originalName);
                                                            echo  $shortTypeName;
                                                            ?>
                                                        </td>
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

                                                            $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                                            $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                                            ?>


                                                            <a href="product_stock_edit_form?id=<?php echo $base64Encoded ?>" class="btn btn-warning">
                                                                <i class="fa-solid fa-pen-to-square me-1"></i>
                                                                <span>แก้ไข</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <?php require_once("./includes/no_information.php") ?>
                                    <?php } ?>
                                    <a href="index" class="btn btn-secondary">
                                        <i class="fa-solid fa-right-from-bracket fa-rotate-180 me-1"></i>
                                        กลับหน้าแรก
                                    </a>
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