<?php
$titlePage = "แก้ไขข้อมูลตั้งค่าเว็บไซต์";

require_once("../db/connectdb.php");
require_once("../db/controller/SettingWebsiteController.php");
require_once("../includes/salt.php");
require_once("../admin/includes/functions.php");

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $stId = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $SettingWebsiteController = new SettingWebsiteController($conn);
    $settingWebsite = $SettingWebsiteController->getDetailSettingWebsite($stId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($settingWebsite);
} else {
    header('Location: setting_website_show');
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
                                            <span>ข้อมูลตั้งค่าเว็บไซต์</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสตั้งค่าเว็บไซต์ :</label>
                                            <p><?php echo $settingWebsite['st_id']; ?></p>
                                            <input type="hidden" name="st_id" class="form-control" value="<?php echo $settingWebsite['st_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shp_name" class="form-label">ชื่อตั้งค่าเว็บไซต์ :</label>
                                            <p><?php echo $settingWebsite['st_name']; ?></p>
                                        </div>
                                        <?php if ($settingWebsite['st_id'] == 1) { ?>
                                            <div class="mb-3">
                                                <label for="ct_detail" class="form-label">รายละเอียด :</label><span class="text-danger">*</span>
                                                <input type="text" name="st_detail" class="form-control" value="<?php echo $settingWebsite['st_detail']; ?>" placeholder="กรุณาระบุ รายละเอียด">
                                            </div>
                                        <?php } ?>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->


                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <?php if ($settingWebsite['st_detail'] != 1) { ?>
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="mb-3 header-title text-warning">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>รูปภาพโปรโมชั่น</span>
                                            </h4>

                                            <div class="">
                                                <img class="rounded mx-auto d-block img-fluid" id="pro_img" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_web_setting/<?php echo $settingWebsite['st_detail'] ?>">
                                                <input type="hidden" name="pro_img" value="<?php echo $settingWebsite['st_detail'] ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="formFile" class="form-label mt-4">รูปภาพใหม่ :</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                                <input class="form-control" name="pro_newImg" id="pro_newImg" type="file" accept="image/png,image/jpg,image/jpeg">
                                            </div>

                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                <?php } ?>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>สถานะการแสดง</span>
                                        </h4>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="st_status" id="1" value="1" <?php if ($settingWebsite['st_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="st_status" id="0" value="0" <?php if ($settingWebsite['st_status'] != 1) {
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
                                        <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $settingWebsite['st_time'] ?></span></h4>
                                        <div>
                                            <a href="setting_website_show" class="btn btn-secondary me-2">
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