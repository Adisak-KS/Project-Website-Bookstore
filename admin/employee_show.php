<?php
$titlePage = "พนักงาน";

require_once("../db/connectdb.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");
require_once("../db/controller/EmployeeController.php");
require_once('../db/controller/LoginController.php');

$LoginController = new LoginController($conn);
$EmployeeController = new EmployeeController($conn);

$employees = $EmployeeController->getEmployee();

$empId = $_SESSION['emp_id'];

// ตรวจสอบสิทธิ์การใช้งาน
$useAuthority = $LoginController->useLoginEmployees($empId);
$allowedAuthorities = [1, 2, 3]; // [Super Admin, Owner, Admin]
checkAuthorityEmployees($useAuthority, $allowedAuthorities)

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
                                    <h4 class="mt-0 header-title">ข้อมูลพนักงานทั้งหมด</h4>
                                    <div class="my-3">
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                            <i class="fa-solid fa-user-plus"></i>
                                            <span> เพิ่มพนักงาน</span>
                                        </button>
                                        <hr>

                                        <!-- Scrollable modal -->
                                        <form id="formUser" action="process/employee_add" method="post">
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
                                                            <input type="hidden" name="eat_id" class="form-control" placeholder="รหัสสิทธิ์พนักงาน" maxlength="1" value="6" readonly>
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
                                    <?php if ($employees) { ?>
                                        <table id="MyTable" class="table  table-bordered table-hover dt-responsive table-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">รูป</th>
                                                    <th class="text-start">ชื่อ</th>
                                                    <th class="text-start">นามสกุล</th>
                                                    <th class="text-start">ชื่อผู้ใช้</th>
                                                    <th class="text-start">อีเมล</th>
                                                    <th class="text-center">สิทธิ์</th>
                                                    <th class="text-center">สถานะ</th>
                                                    <th>จัดการข้อมูล</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($employees as $row) { ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <img class="rounded-circle" width="50px" height="50px" src="../uploads/img_employees/<?php echo $row['emp_profile'] ?>">
                                                        </td>
                                                        <td class="text-start">
                                                            <?php
                                                            $originalName = $row['emp_fname'];
                                                            $shortFirstName = shortenName($originalName);
                                                            echo $shortFirstName;
                                                            ?>
                                                        </td>
                                                        <td class="text-start">
                                                            <?php
                                                            $originalName = $row['emp_lname'];
                                                            $shortLastName = shortenName($originalName);
                                                            echo $shortLastName;
                                                            ?>
                                                        </td>
                                                        <td class="text-start">
                                                            <?php
                                                            $originalName = $row['emp_username'];
                                                            $shortUsername = shortenName($originalName);
                                                            echo $shortUsername;
                                                            ?>
                                                        </td>
                                                        <td class="text-start">
                                                            <?php
                                                            $originalName = $row['emp_email'];
                                                            $shortUsername = shortenName($originalName);
                                                            echo $shortUsername;
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            $authorityArray = explode(',', $row['authority']);
                                                            $authorityMapping = array(
                                                                '4' => '<p class="badge bg-danger mb-1">Accounting</p>',
                                                                '5' => '<p class="badge bg-success mb-1">Sale</p>',
                                                                '6' => '<p class="badge bg-primary mb-1">Employee</p>'
                                                            );

                                                            foreach ($authorityArray as $authority) {
                                                                echo $authorityMapping[$authority] . "<br>";
                                                            }
                                                            ?>
                                                        </td>


                                                        <td class="text-center">
                                                            <?php if ($row['emp_status'] == 1) { ?>
                                                                <span class="badge rounded-pill bg-success fs-6">ใช้งานได้</span>
                                                            <?php } else { ?>
                                                                <span class="badge rounded-pill bg-danger fs-6">ระงับการใช้งาน</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $originalId = $row["emp_id"];
                                                            $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                            ?>

                                                            <a href="employee_edit_form?id=<?php echo $base64Encoded ?>" class="btn btn-warning">
                                                                <i class="fa-solid fa-pen-to-square me-1"></i>
                                                                <span>แก้ไข</span>
                                                            </a>

                                                            <a href="employee_del_form?id=<?php echo $base64Encoded ?>" class="btn btn-danger ms-2">
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