<?php
$titlePage = "แก้ไขข้อมูลช่องทางติดต่อ";

require_once("../db/connectdb.php");
require_once("../db/controller/ContactController.php");
require_once("../includes/salt.php");
require_once("../admin/includes/functions.php");

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $ctId = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $ContactController = new ContactController($conn);
    $contact = $ContactController->getDetailContact($ctId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($contact);
} else {
    header('Location: contact_show');
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
                    <form id="formContact" novalidate action="process/contact_edit" method="post">
                        <div class="row">
                            <!-- id="formProductType" -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>ข้อมูลช่องทางติดต่อ</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสช่องทางติดต่อ :</label>
                                            <p><?php echo  $contact['ct_id']; ?></p>
                                            <input type="hidden" name="ct_id" class="form-control" value="<?php echo $contact['ct_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shp_name" class="form-label">ชื่อช่องทางติดต่อ :</label>
                                            <p><?php echo $contact['ct_name']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="ct_detail" class="form-label">ลิงค์ช่องทางติดต่อ :</label><span class="text-danger">*</span>
                                            <input type="url" name="ct_detail" class="form-control" value="<?php echo $contact['ct_detail']; ?>" placeholder="กรุณาระบุ ลิงค์ช่องทางติดต่อ เช่น">
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
                                            <span>สถานะการแสดง</span>
                                        </h4>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="ct_status" id="1" value="1" <?php if ($contact['ct_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="ct_status" id="0" value="0" <?php if ($contact['ct_status'] != 1) {
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
                                        <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $contact['ct_time'] ?></span></h4>
                                        <div>
                                            <a href="contact_show" class="btn btn-secondary me-2">
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