<?php
$titlePage = "ช่องทางชำระเงิน";

require_once("../db/connectdb.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");
require_once("../db/controller/PaymentController.php");
require_once('../db/controller/LoginController.php');

$LoginController = new LoginController($conn);
$PaymentController = new PaymentController($conn);

$payments = $PaymentController->getPayment();

$empId = $_SESSION['emp_id'];

// ตรวจสอบสิทธิ์การใช้งาน
$useAuthority = $LoginController->useLoginEmployees($empId);
$allowedAuthorities = [1, 3, 4]; // [Super Admin, Admin, Accounting]
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
                                    <h4 class="mt-0 header-title">ข้อมูลช่องทางชำระเงิน</h4>
                                    <div class="my-3">
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                            <i class="fa-regular fa-square-plus me-1"></i>
                                            <span>เพิ่มช่องทางชำระเงิน</span>
                                        </button>
                                        <hr>

                                        <!-- Scrollable modal -->
                                        <form id="formPayment" action="process/payment_add.php" method="post">
                                            <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAdd" data-bs-backdrop="static" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มช่องทางชำระเงิน</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="pmt_bank" class="form-label">ชื่อธนาคาร :</label><span class="text-danger">*</span>
                                                                <input type="text" name="pmt_bank" class="form-control" placeholder="ระบุ ชื่อธนาคาร" maxlength="100">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="pmt_name" class="form-label">ชื่อบัญชี :</label><span class="text-danger">*</span>
                                                                <input type="text" name="pmt_name" class="form-control" placeholder="ระบุ ชื่อบัญชี" maxlength="100">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="pmt_number" class="form-label">หมายเลขบัญชี :</label><span class="text-danger">*</span>
                                                                <input type="text" name="pmt_number" class="form-control" placeholder="ระบุ หมายเลขบัญชี" maxlength="10">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="pmt_detail" class="form-label">รายละเอียด :</label><span class="text-danger">*</span>
                                                                <textarea name="pmt_detail" class="form-control" placeholder="ระบุ รายละเอียด" maxlength="255"></textarea>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="pmt_status" class="form-label">สถานะการแสดง :</label><span class="text-danger">*</span>
                                                                <div class="form-check mb-2 form-check-success">
                                                                    <input class="form-check-input" type="radio" name="pmt_status" id="1" value="1">
                                                                    <label class="form-check-label" for="1">แสดง</label>
                                                                </div>
                                                                <div class="form-check mb-2 form-check-danger">
                                                                    <input class="form-check-input" type="radio" name="pmt_status" id="0" value="0" checked>
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

                                    </div>
                                    <?php if ($payments) { ?>
                                        <table id="MyTable" class="table  table-bordered table-hover dt-responsive table-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">รูป</th>
                                                    <th class="text-center">ธนาคาร</th>
                                                    <th class="text-center">ชื่อบัญชี</th>
                                                    <th class="text-center">เลขบัญชี</th>
                                                    <th class="text-center">สถานะ</th>
                                                    <th class="text-center">จัดการข้อมูล</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($payments as $row) { ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <img class="rounded" width="50px" height="50px" src="../uploads/img_payment/<?php echo $row['pmt_bank_logo'] ?>">
                                                        </td>
                                                        <td class="text-start">
                                                            <?php
                                                            $originalName = $row['pmt_bank'];
                                                            $shortBankName = shortenName($originalName);
                                                            echo $shortBankName;
                                                            ?>
                                                        </td>
                                                        <td class="text-start">
                                                            <?php
                                                            $originalName = $row['pmt_name'];
                                                            $shortName = shortenName($originalName);
                                                            echo $shortName;
                                                            ?>
                                                        </td>
                                                        <td class="text-start"><?php echo $row['pmt_number']; ?></td>
                                                        <td class="text-center">
                                                            <?php if ($row['pmt_status'] == 1) { ?>
                                                                <span class="badge rounded-pill bg-success fs-6">แสดง</span>
                                                            <?php } else { ?>
                                                                <span class="badge rounded-pill bg-danger fs-6">ไม่แสดง</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">

                                                            <?php
                                                            $originalId = $row["pmt_id"];
                                                            $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                            ?>

                                                            <a href="payment_edit_form?id=<?php echo $base64Encoded ?>" class="btn btn-warning">
                                                                <i class="fa-solid fa-pen-to-square me-1"></i>
                                                                <span>แก้ไข</span>
                                                            </a>

                                                            <a href="payment_del_form?id=<?php echo $base64Encoded ?>" class="btn btn-danger ms-2">
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