<?php
$titlePage = "รายการหนังสือตามสั่ง";

require_once("../db/connectdb.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");
require_once("../db/controller/ProductRequestController.php");

$ProductRequestController = new ProductRequestController($conn);

$ProductRequest = $ProductRequestController->getProductRequestStatusChecking();


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
                                    <h4 class="mt-0 header-title">ข้อมูลรายการหนังสือตามสั่ง</h4>
                                    <div class="my-3">
                                        <hr>

                                        <!-- Scrollable modal -->
                                        <form id="formUser" action="process/member_add.php" method="post">
                                            <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAdd" data-bs-backdrop="static" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มสมาชิกใหม่</h1>
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
                                                            <input type="hidden" name="eat_id" class="form-control" placeholder="รหัสสิทธิ์พนักงาน" maxlength="1" value="3" readonly>
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

                                    </div>
                                    <?php if ($ProductRequest) { ?>
                                        <table id="MyTable" class="table  table-bordered table-hover dt-responsive table-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">วัน /เวลา</th>
                                                    <th class="text-center">หัวเรื่องการค้นหา</th>
                                                    <th class="text-start">ชื่อผู้ค้นหา</th>
                                                    <th class="text-center">สถานะ</th>
                                                    <th>จัดการข้อมูล</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($ProductRequest as $row) { ?>
                                                    <tr>
                                                        <td class="text-start"><?php echo $row['prq_time_create']; ?></td>
                                                        <td class="text-start">
                                                            <?php
                                                            $originalName = $row['prq_title'];
                                                            $shortName = shortenName($originalName);
                                                            echo $shortName;
                                                            ?>
                                                        </td>
                                                        <td class="text-start">
                                                            <?php echo $row['mem_fname'] . " " . $row['mem_lname'] . " (" . $row['mem_username'] . ")"  ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($row['prq_status'] == "checking") { ?>
                                                                <span class="badge rounded-pill bg-warning fs-6">รอตรวจสอบ</span>
                                                            <?php } else { ?>
                                                                <span class="badge rounded-pill bg-danger fs-6">ไม่พบสถานะ</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $originalId = $row["prq_id"];
                                                            $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                            ?>

                                                            <a href="product_request_detail?id=<?php echo $base64Encoded ?>" class="btn btn-info">
                                                                <i class="fa-solid fa-eye me-1"></i>
                                                                <span>รายละเอียด</span>
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