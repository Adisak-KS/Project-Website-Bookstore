<?php
$titlePage = "ลบพนักงาน";

require_once("../db/connectdb.php");
require_once("../db/controller/EmployeeController.php");
require_once("../includes/salt.php");
require_once("../admin/includes/functions.php");

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id 
    $Id = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $EmployeeController = new EmployeeController($conn);
    $employees = $EmployeeController->getDetailEmployee($Id);

    //ตรวจสอบว่ามีข้อมูลหรือไม่
    checkResultDetail($employees);
} else {
    header('Location: owner_show');
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
                    <form id="formUser" action="process/owner_del" method="post">
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>ข้อมูลส่วนตัว</span>
                                        </h4>
                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสพนักงาน :</label>
                                            <p><?php echo $employees['emp_id']; ?></p>
                                            <input type="hidden" name="id" class="form-control" value="<?php echo $employees['emp_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">ชื่อผู้ใช้งาน :</label>
                                            <p><?php echo $employees['emp_username']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">อีเมล :</label>
                                            <div class="mb-3">
                                                <p><?php echo $employees['emp_email']; ?></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="fname" class="form-label">ชื่อ :</label><span class="text-danger">*</span>
                                            <div class="mb-3">
                                                <p><?php echo $employees['emp_fname']; ?></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="lname" class="form-label">นามสกุล :</label><span class="text-danger">*</span>
                                            <div class="mb-3">
                                                <p><?php echo $employees['emp_lname']; ?></p>
                                            </div>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>สถานะบัญชี</span>
                                        </h4>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="status" id="1" value="1" <?php if ($employees['emp_status'] == 1) {
                                                                                                                            echo 'checked';
                                                                                                                        } ?> disabled>
                                            <label class="form-check-label" for="1">ใช้งานได้</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="status" id="0" value="0" <?php if ($employees['emp_status'] != 1) {
                                                                                                                            echo 'checked';
                                                                                                                        } ?> disabled>
                                            <label class="form-check-label" for="0">ระงับการใช้งาน</label>
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <div class="card pb-5">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>โปรไฟล์</span>
                                        </h4>

                                        <div class="">
                                            <img class="rounded-circle mx-auto d-block img-fluid" id="profile" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_employees/<?php echo $employees['emp_profile']; ?>">
                                            <input type="hidden" name="profile" value="<?php echo $employees['emp_profile'] ?>" readonly>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->

                                <div class="card pb-3">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>สิทธิ์การใช้งาน</span>
                                        </h4>
                                        <input type="hidden" name="old_eat_id" value="<?php echo $employees['authority']; ?>" readonly>
                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="checkbox" name="new_eat_id[]" value="4" id="4" <?php if (in_array(4, explode(',', $employees['authority']))) {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?> disabled>
                                            <label class="form-check-label" for="4">Accounting (พนักงานฝ่ายการเงิน)</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="checkbox" name="new_eat_id[]" value="5" id="5" <?php if (in_array(5, explode(',', $employees['authority']))) {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?> disabled>
                                            <label class="form-check-label" for="5">Sale (พนักงานฝ่ายขาย)</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-primary">
                                            <input class="form-check-input" type="checkbox" name="new_eat_id[]" value="6" id="6" <?php if (in_array(6, explode(',', $employees['authority']))) {
                                                                                                                                        echo 'checked';
                                                                                                                                    } ?> disabled>
                                            <label class="form-check-label" for="6">employee (สิทธิ์เริ่มต้น)</label>
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $employees['emp_time_update'] ?></span></h4>
                                        <div>
                                            <a href="employee_show" class="btn btn-secondary me-2">
                                                <i class="fa-solid fa-xmark me-1"></i>
                                                <span>ยกเลิก</span>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-delete" data-id="<?php echo $employees["emp_id"]; ?>" data-profile="<?php echo $employees["emp_profile"]; ?>">
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

        <?php require_once('layouts/vender.php') ?>


        <!-- Delete  -->
        <script>
            $(document).ready(function() {
                $(".btn-delete").click(function(e) {
                    e.preventDefault();
                    let id = $(this).data('id');
                    let profile = $(this).data('profile');

                    deleteConfirm(id, profile);
                });
            });

            function deleteConfirm(id, profile) {
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
                                url: 'process/employee_del',
                                type: 'POST',
                                data: {
                                    id: id,
                                    profile: profile
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า employee_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'employee_del_form?id=<?php echo $base64Encoded; ?>';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'employee_show';
                    }
                });
            }
        </script>

</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>