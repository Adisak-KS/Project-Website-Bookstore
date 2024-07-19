<?php
$titlePage = "ลบประเภทสินค้า";

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

    $qtyProduct = $ProductTypeController->amountProductInProductType($Id);

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
                    <form action="process/product_type_del" method="post">
                        <div class="row">
                            <!-- id="formProductType" -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
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
                                            <p><?php echo $productType['pty_name']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pty_detail" class="form-label">รายละเอียดประเภทสินค้า :</label><span class="text-danger">*</span>
                                            <p><?php echo $productType['pty_detail']; ?></p>
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
                                            <span>รูปปก</span>
                                        </h4>

                                        <div class="">
                                            <img class="rounded mx-auto d-block img-fluid" id="pty_cover" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_product_type/<?php echo $productType['pty_cover']; ?>">
                                            <input type="hidden" name="pty_cover" value="<?php echo $productType['pty_cover']; ?>" readonly>
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>สถานะการแสดง</span>
                                        </h4>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="pty_status" id="1" value="1" <?php if ($productType['pty_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?> disabled>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="pty_status" id="0" value="0" <?php if ($productType['pty_status'] != 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?> disabled>
                                            <label class="form-check-label" for="0">ไม่แสดง</label>
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $productType['pty_time_update'] ?></span></h4>
                                        <?php if ($qtyProduct['amount'] > 0) { ?>
                                            <p class="text-danger">มีสินค้าอยู่ในประเภทนี้ <?php echo number_format($qtyProduct['amount']) ?> รายการ กรุณาลบ หรือเปลี่ยนแปลงประเภทสินค้าก่อน</p>
                                        <?php } else { ?>
                                            <p class="text-danger">มีสินค้าอยู่ในประเภทนี้ <?php echo number_format($qtyProduct['amount']) ?> รายการ</p>
                                        <?php } ?>
                                        <div>
                                            <a href="product_type_show" class="btn btn-secondary me-2">
                                                <i class="fa-solid fa-xmark me-1"></i>
                                                <span>ยกเลิก</span>
                                            </a>
                                            <?php if ($qtyProduct['amount'] > 0) { ?>
                                                <button type="button" class="btn btn-danger btn-delete" disabled>
                                                    <i class="fa-solid fa-trash"></i>
                                                    <span>ลบข้อมูล</span>
                                                </button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-danger btn-delete" data-id="<?php echo $productType["pty_id"]; ?>" data-img="<?php echo $productType["pty_cover"]; ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                    <span>ลบข้อมูล</span>
                                                </button>
                                            <?php } ?>
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

        <!-- Delete  -->
        <script>
            $(document).ready(function() {
                $(".btn-delete").click(function(e) {
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
                    text: "คุณต้องการลบข้อมูลนี้ใช่ไหม!",
                    showCancelButton: true,
                    confirmButtonColor: '#f34e4e',
                    confirmButtonText: 'ใช่, ลบข้อมูลเลย!',
                    cancelButtonText: 'ยกเลิก',
                    preConfirm: function() {
                        return $.ajax({
                                url: 'process/product_type_del.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    img: img
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า member_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'product_type_del_form?id=<?php echo $base64Encoded; ?>';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'product_type_show';
                    }
                });
            }
        </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>