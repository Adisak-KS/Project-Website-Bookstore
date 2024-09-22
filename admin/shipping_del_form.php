<?php
$titlePage = "ลบข้อมูลช่องทางจัดส่ง";

require_once("../db/connectdb.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");
require_once('../db/controller/LoginController.php');
require_once("../db/controller/ShippingController.php");


$LoginController = new LoginController($conn);
$ShippingController = new ShippingController($conn);

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $shpId = decodeBase64ID($base64Encoded, $salt1, $salt2);
    $shipping = $ShippingController->getDetailShipping($shpId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($shipping);

    $empId = $_SESSION['emp_id'];

    // ตรวจสอบสิทธิ์การใช้งาน
    $useAuthority = $LoginController->useLoginEmployees($empId);
    $allowedAuthorities = [1, 3, 4]; // [Super Admin, Admin, Accounting]
    checkAuthorityEmployees($useAuthority, $allowedAuthorities);

} else {
    header('Location: shipping_show');
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
                    <form id="formPublisher" action="process/shipping_edit" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- id="formProductType" -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>ข้อมูลช่องทางขนส่ง</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสช่องทางขนส่ง :</label>
                                            <p><?php echo  $shipping['shp_id']; ?></p>
                                            <input type="hidden" name="shp_id" class="form-control" value="<?php echo $shipping['shp_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shp_name" class="form-label">ชื่อขนส่ง :</label><span class="text-danger">*</span>
                                            <p><?php echo $shipping['shp_name']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="shp_price" class="form-label">ราคา :</label><span class="text-danger">*</span>
                                            <p><?php echo number_format($shipping['shp_price'], 2); ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="shp_detail" class="form-label">รายละเอียด :</label><span class="text-danger">*</span>
                                            <p><?php echo $shipping['shp_detail']; ?></p>
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
                                            <span>รูปภาพช่องทางขนส่ง</span>
                                        </h4>

                                        <div class="">
                                            <img class="rounded mx-auto d-block img-fluid" id="shp_logo" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_shipping/<?php echo $shipping['shp_logo']; ?>">
                                            <input type="hidden" name="shp_logo" value="<?php echo $shipping['shp_logo']; ?>" readonly>
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
                                            <input class="form-check-input" type="radio" name="shp_status" id="1" value="1" <?php if ($shipping['shp_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?> disabled>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="shp_status" id="0" value="0" <?php if ($shipping['shp_status'] != 1) {
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
                                        <h4 class="mb-3 header-title text-danger">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $shipping['shp_time_update'] ?></span></h4>
                                        <div>
                                            <a href="shipping_show" class="btn btn-secondary me-2">
                                                <i class="fa-solid fa-xmark me-1"></i>
                                                <span>ยกเลิก</span>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-delete" data-id="<?php echo $shipping["shp_id"]; ?>" data-logo="<?php echo $shipping["shp_logo"]; ?>">
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
                    let logo = $(this).data('logo');

                    deleteConfirm(id, logo);
                });
            });

            function deleteConfirm(id, logo) {
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
                                url: 'process/shipping_del.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    logo: logo
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า shipping_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'shipping_del_form?id=<?php echo $base64Encoded; ?>';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'shipping_show';
                    }
                });
            }
        </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>