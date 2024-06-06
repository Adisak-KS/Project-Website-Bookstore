<?php
$titlePage = "ผู้ดูแลระบบ";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('./layouts/head.php') ?>
</head>

<!-- body start -->

<body class="loading" data-layout-color="light" data-layout-mode="default" data-layout-size="fluid" data-topbar-color="light" data-leftbar-position="fixed" data-leftbar-color="light" data-leftbar-size='default' data-sidebar-user='true'>

    <!-- Begin page -->
    <div id="wrapper">

        <!-- ========== Topbar ========== -->
        <?php require_once('./layouts/nav_topbar.php') ?>

        <!-- ========== Left bar ========== -->
        <?php require_once('./layouts/nav_leftbar.php') ?>

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
                                    <h4 class="mt-0 header-title">ข้อมูลผู้ดูแลระบบทั้งหมด</h4>
                                    <div class="my-3">
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                            <i class="fa-solid fa-user-plus"></i>
                                            <span> เพิ่มผู้ดูแลระบบ</span>
                                        </button>
                                        <hr>

                                        <!-- Scrollable modal -->
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
                                                                <input type="text" class="form-control" placeholder="ระบุ ชื่อผู้ใช้งาน" aria-describedby="inputGroupPrepend" maxlength="50">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="password" class="form-label">รหัสผ่าน :</label><span class="text-danger">*</span>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control" name="password" placeholder="ระบุ รหัสผ่าน" maxlength="255">
                                                                <button class="btn btn-outline-secondary" type="button">
                                                                    <i class="fa-solid fa-eye-slash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="password" class="form-label">ยืนยันรหัสผ่าน :</label><span class="text-danger">*</span>
                                                            <div class="input-group">
                                                                <input type="password" class="form-control" name="confirmPassword" placeholder="ระบุ รหัสผ่าน อีกครั้ง" maxlength="255">
                                                                <button class="btn btn-outline-secondary" type="button">
                                                                    <i class="fa-solid fa-eye-slash"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">อีเมล :</label><span class="text-danger">*</span>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                                                <input type="text" class="form-control" placeholder="ระบุ อีเมล" aria-describedby="inputGroupPrepend" maxlength="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fa-solid fa-xmark"></i>
                                                            <span> ยกเลิก</span>
                                                        </button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fa-solid fa-floppy-disk"></i>
                                                            <span> บันทึก</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <table id="MyTable" class="table table-bordered dt-responsive table-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th class="text-center">รูป</th>
                                                <th class="text-start">ชื่อ</th>
                                                <th class="text-start">นามสกุล</th>
                                                <th class="text-start">ชื่อผู้ใช้</th>
                                                <th class="text-start">อีเมล</th>
                                                <th class="text-center">สถานะ</th>
                                                <th>จัดการข้อมูล</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    <img class="rounded-circle" width="50px" height="50px" src="../uploads/img_employees/default.jpg">
                                                </td>
                                                <td class="text-start">System</td>
                                                <td class="text-start">Edinburgh</td>
                                                <td class="text-start">Edinburgh</td>
                                                <td class="text-start">2011/04/25</td>
                                                <td class="text-center">ใช้งานได้</td>
                                                <td>
                                                    <a href="#" class="btn btn-warning">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                        <span> แก้ไข</span>
                                                    </a>

                                                    <a href="#" class="btn btn-danger ms-2">
                                                        <i class="fa-solid fa-trash"></i>
                                                        <span> ลบ</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <?php require_once('./layouts/nav_footer.php') ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    <!-- ========== Right bar ========== -->
    <?php require_once('./layouts/nav_rightbar.php') ?>



    <?php require_once('./layouts/vender.php') ?>

</body>

</html>