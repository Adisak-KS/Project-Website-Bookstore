<?php
$titlePage = "แก้ไขข้อมูลส่วนตัว";

require_once("../db/connectdb.php");
require_once("../db/controller/EmployeeController.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");

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
    header('Location: account_show');
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
                    <form id="formUser" action="process/account_profile_edit" method="post" enctype="multipart/form-data">
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>ข้อมูลส่วนตัว</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสพนักงาน :</label>
                                            <p><?php echo $employees['emp_id']; ?></p>
                                            <input type="hidden" name="id" class="form-control" value="<?php echo $employees['emp_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="fname" class="form-label">ชื่อ :</label><span class="text-danger">*</span>
                                            <input type="text" name="fname" class="form-control" placeholder="ระบุ ชื่อจริง" maxlength="50" value="<?php echo $employees['emp_fname']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="lname" class="form-label">นามสกุล :</label><span class="text-danger">*</span>
                                            <input type="text" name="lname" class="form-control" placeholder="ระบุ นามสกุล" maxlength="50" value="<?php echo $employees['emp_lname']; ?>">
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
                                            <span>โปรไฟล์</span>
                                        </h4>

                                        <div class="">
                                            <img class="rounded-circle mx-auto d-block img-fluid" id="profile" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_employees/<?php echo $employees['emp_profile']; ?>">
                                            <input type="hidden" name="profile" value="<?php echo $employees['emp_profile'] ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label mt-4">รูปภาพผู้ใช้ใหม่ :</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                            <input class="form-control" name="newProfile" id="newProfile" type="file" accept="image/png,image/jpg,image/jpeg">
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $employees['emp_time_update'] ?></span></h4>
                                        <div>
                                            <a href="account_show" class="btn btn-secondary me-2">
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

        <?php require_once('layouts/vendor.php') ?>

        <!-- preview New Profile, check file type, file size  -->
        <script>
            document.getElementById('newProfile').addEventListener('change', function(event) {
                const file = event.target.files[0];
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024; // 2 MB in bytes

                if (file && allowedTypes.includes(file.type) && file.size <= maxSize) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('profile').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Reset the input
                    event.target.value = '';
                    // Reset to the original image
                    document.getElementById('profile').src = '../uploads/img_employees/<?php echo $employees["emp_profile"]; ?>';
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'คำเตือน',
                            text: 'ต้องเป็นไฟล์ .png .jpg .jpeg เท่านั้น',
                        });
                    }

                    // Show an alert if the file is not valid
                    if (allowedTypes.includes(file.type) && file.size > maxSize) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'คำเตือน',
                            text: 'ขนาดไฟล์เกิน 2 MB',
                        });
                    }
                }
            });
        </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>