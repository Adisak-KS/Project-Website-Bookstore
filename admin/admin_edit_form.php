<?php
$titlePage = "แก้ไขผู้ดูแลระบบ";

require_once("../db/connectdb.php");
require_once("../db/controller/AdminController.php");

if (isset($_GET['id'])) {
    $_SESSION["base64Encoded"] = $_GET["id"];

    // เก็บค่า session  ใน base64Encoded
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส base64 เพื่อให้ได้ข้อมูลเป็นข้อความธรรมดา
    $base64Decoded = base64_decode($base64Encoded);

    // ดึงค่า salt จาก session
    $salt1 = $_SESSION["salt1"];
    $salt2 = $_SESSION["salt2"];

    // แยกส่วนของ salt และข้อมูลที่ไม่ถูกเข้ารหัส
    $salt1Length = mb_strlen($salt1, 'UTF-8');
    $salt2Length = mb_strlen($salt2, 'UTF-8');

    $salt1 = substr($base64Decoded, 0, $salt1Length);
    $saltedId = substr($base64Decoded, $salt1Length, -$salt2Length);
    $salt2 = substr($base64Decoded, -$salt2Length);

    // สร้างค่า originalId โดยตัดค่า salt ออก
    $originalId = str_replace([$salt1, $salt2], '', $saltedId);

    // ใช้ originalId ในการทำงานต่อ
    $Id = $originalId;

    $AdminController = new AdminController($conn);
    $admins = $AdminController->getDetailAdmin($Id);


    if (!$admins) {
        header('Location: error.php');
        exit;
    }
} else {
    header('Location: admin_show.php');
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
                    <form id="formEmployee" action="process/admin_edit.php" method="post" enctype="multipart/form-data">
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
                                            <p><?php echo $admins['emp_id']; ?></p>
                                            <input type="hidden" name="id" class="form-control" value="<?php echo $admins['emp_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">ชื่อผู้ใช้งาน :</label>
                                            <p><?php echo $admins['emp_username']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">อีเมล :</label>
                                            <div class="mb-3">
                                                <p><?php echo $admins['emp_email']; ?></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="fname" class="form-label">ชื่อ :</label><span class="text-danger">*</span>
                                            <input type="text" name="fname" class="form-control" placeholder="ระบุ ชื่อจริง" maxlength="50" value="<?php echo $admins['emp_fname']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="lname" class="form-label">นามสกุล :</label><span class="text-danger">*</span>
                                            <input type="text" name="lname" class="form-control" placeholder="ระบุ นามสกุล" maxlength="50" value="<?php echo $admins['emp_lname']; ?>">
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>สถานะบัญชี</span>
                                        </h4>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="status" id="1" value="1" <?php if ($admins['emp_status'] == 1) {
                                                                                                                            echo 'checked';
                                                                                                                        } ?>>
                                            <label class="form-check-label" for="1">ใช้งานได้</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="status" id="0" value="0" <?php if ($admins['emp_status'] != 1) {
                                                                                                                            echo 'checked';
                                                                                                                        } ?>>
                                            <label class="form-check-label" for="0">ระงับการใช้งาน</label>
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
                                            <img class="rounded-circle mx-auto d-block img-fluid" id="profile" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_employees/<?php echo $admins['emp_profile']; ?>">
                                            <input type="hidden" name="profile" value="<?php echo $admins['emp_profile'] ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label mt-4">รูปภาพผู้ใช้ใหม่ :</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                            <input class="form-control" name="newProfile" id="newProfile" type="file" accept="image/png,image/jpg,image/jpeg">
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->

                                <div class="card pb-3">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>สิทธิ์การใช้งาน</span>
                                        </h4>
                                       <input type="hidden" name="old_eat_id" value="<?php echo $admins['authority']; ?>" readonly>
                                        <div class="form-check mb-2 form-check-pink">
                                            <input class="form-check-input" type="radio" name="new_eat_id" id="1" value="2" <?php if ($admins['authority'] == 2) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">Owner (เจ้าของ / ผู้บริหาร)</label>
                                        </div>
                                        <div class="form-check mb-2 form-check-warning">
                                            <input class="form-check-input" type="radio" name="new_eat_id" id="3" value="3" <?php if ($admins['authority'] == 3) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">Admin (ผู้ดูแลระบบ)</label>
                                        </div>
                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="new_eat_id" id="4" value="4" <?php if ($admins['authority'] == 4) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">Accounting (พนักงานฝ่ายการเงิน)</label>
                                        </div>
                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="new_eat_id" id="5" value="5" <?php if ($admins['authority'] == 5) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">Sale (พนักงานฝ่ายขาย)</label>
                                        </div>
                                        <div class="form-check mb-2 form-check-primary">
                                            <input class="form-check-input" type="radio" name="new_eat_id" id="6" value="6" <?php if (!in_array($admins['authority'], [2, 3, 4, 5])) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?>>
                                            <label class="form-check-label" for="1">Employee (สิทธิ์เริ่มต้น)</label>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $admins['emp_time_update'] ?></span></h4>
                                        <div>
                                            <a href="admin_show.php" class="btn btn-secondary me-2">
                                                <i class="fa-solid fa-xmark"></i>
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
                    document.getElementById('profile').src = '../uploads/img_employees/<?php echo $admins["emp_profile"]; ?>';
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