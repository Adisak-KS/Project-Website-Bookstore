<?php
$titlePage = "ตั้งค่าเว็บไซต์";

require_once("../db/connectdb.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");
require_once('../db/controller/LoginController.php');
require_once("../db/controller/SettingWebsiteController.php");


$LoginController = new LoginController($conn);
$SettingWebsiteController = new SettingWebsiteController($conn);

$settings = $SettingWebsiteController->getSettingsWebsite();

$empId = $_SESSION['emp_id'];

// ตรวจสอบสิทธิ์การใช้งาน
$useAuthority = $LoginController->useLoginEmployees($empId);
$allowedAuthorities = [1, 3, 6]; // [Super Admin, Owner, Admin, Employee]
checkAuthorityEmployees($useAuthority, $allowedAuthorities);

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
                                    <h4 class="mt-0 header-title">ตั้งค่าเว็บไซต์ทั้งหมด</h4>
                                    <hr>

                                    <!-- Scrollable modal -->
                                    <form id="formUser" action="process/owner_add" method="post">
                                        <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAdd" data-bs-backdrop="static" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มผู้ดูแลระบบ</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="fname" class="form-label">ชื่อ :</label><span class="text-danger">*</span>
                                                            <input type="text" name="fname" class="form-control" placeholder="ระบุ ชื่อจริง" maxlength="50">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="lname" class="form-label">นามสกุล :</label><span class="text-danger">*</span>
                                                            <input type="text" name="lname" class="form-control" placeholder="ระบุ นามสกุล" maxlength="50">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="username" class="form-label">ชื่อผู้ใช้งาน :</label><span class="text-danger">*</span>
                                                            <div class="input-group">
                                                                <span class="input-group-text">@</span>
                                                                <input type="text" name="username" class="form-control" placeholder="ระบุ ชื่อผู้ใช้งาน" aria-describedby="inputGroupPrepend" maxlength="50">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="password" class="form-label">รหัสผ่าน :</label><span class="text-danger">*</span>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control" name="password" placeholder="ระบุ รหัสผ่าน" maxlength="255">
                                                                <button class="btn btn-outline-secondary password-toggle" type="button">
                                                                    <i class="fas fa-eye-slash"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="confirmPassword" class="form-label">ยืนยันรหัสผ่าน :</label><span class="text-danger">*</span>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control" name="confirmPassword" placeholder="ระบุ รหัสผ่าน อีกครั้ง" maxlength="255">
                                                                <button class="btn btn-outline-secondary password-toggle" type="button">
                                                                    <i class="fas fa-eye-slash"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">อีเมล :</label><span class="text-danger">*</span>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                                                <input type="text" name="email" class="form-control" placeholder="ระบุ อีเมล" aria-describedby="inputGroupPrepend" maxlength="100">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="eat_id" class="form-control" placeholder="รหัสสิทธิ์พนักงาน" maxlength="1" value="2" readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fa-solid fa-xmark me-1"></i>
                                                            <span> ยกเลิก</span>
                                                        </button>
                                                        <button type="submit" name="btn-add" class="btn btn-success">
                                                            <i class="fa-solid fa-floppy-disk me-1"></i>
                                                            <span> บันทึก</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>


                                    <?php if ($settings) { ?>
                                        <table id="MyTable" class="table  table-bordered table-hover dt-responsive table-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">รายการ</th>
                                                    <th class="text-center">การกำหนดข้อมูล</th>
                                                    <th class="text-center">สถานะ</th>
                                                    <th class="text-center">จัดการข้อมูล</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($settings as $row) { ?>
                                                    <tr>
                                                        <td class="text-start"><?php echo $row['st_id']; ?></td>
                                                        <td class="text-start"><?php echo $row['st_name']; ?></td>
                                                        <td class="text-center">
                                                            <?php if (empty($row['st_detail'])) { ?>
                                                                <span class="badge rounded-pill bg-danger fs-6">ไม่ได้กำหนด</span>
                                                            <?php } else { ?>
                                                                <span class="badge rounded-pill bg-success fs-6">กำหนดแล้ว</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($row['st_status'] == 1) { ?>
                                                                <span class="badge rounded-pill bg-success fs-6">แสดง</span>
                                                            <?php } else { ?>
                                                                <span class="badge rounded-pill bg-danger fs-6">ไม่แสดง</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">

                                                            <?php
                                                            $originalId = $row["st_id"];
                                                            $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                            ?>
                                                            
                                                            <a href="setting_website_edit_form?id=<?php echo $base64Encoded ?>" class="btn btn-warning">
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