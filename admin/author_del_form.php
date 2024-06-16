<?php
$titlePage = "ลบผู้แต่ง";

require_once("../db/connectdb.php");
require_once("../db/controller/AuthorController.php");

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

    $AuthorController = new AuthorController($conn);
    $author = $AuthorController->getDetailAuthor($Id);

    if (!$author) {
        header('Location: error_not_result');
        exit;
    }
} else {
    header('Location: product_type_show');
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
                    <form id="formAuthor" action="process/author_edit" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <!-- id="formProductType" -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>ข้อมูลผู้แต่ง</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสผู้แต่ง :</label>
                                            <p><?php echo  $author['auth_id']; ?></p>
                                            <input type="hidden" name="auth_id" class="form-control" value="<?php echo $author['auth_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="auth_name" class="form-label">ชื่อผู้แต่ง :</label><span class="text-danger">*</span>
                                            <p><?php echo $author['auth_name']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="auth_detail" class="form-label">รายละเอียดผู้แต่ง :</label><span class="text-danger">*</span>
                                            <p><?php echo $author['auth_detail']; ?></p>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->


                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>รูปภาพผู้แต่ง</span>
                                        </h4>

                                        <div class="">
                                            <img class="rounded mx-auto d-block img-fluid" id="auth_img" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_author/<?php echo $author['auth_img'] ?>">
                                            <input type="hidden" name="auth_img" value="<?php echo $author['auth_img'] ?>" readonly>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>สถานะการแสดง</span>
                                        </h4>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="auth_status" id="1" value="1" <?php if ($author['auth_status'] == 1) {
                                                                                                                                    echo 'checked';
                                                                                                                                } ?> disabled>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="auth_status" id="0" value="0" <?php if ($author['auth_status'] != 1) {
                                                                                                                                    echo 'checked';
                                                                                                                                } ?> disabled>
                                            <label class="form-check-label" for="0">ไม่แสดง</label>
                                        </div>

                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div>

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $author['auth_time_update'] ?></span></h4>
                                        <div>
                                            <a href="author_show" class="btn btn-secondary me-2">
                                                <i class="fa-solid fa-xmark me-1"></i>
                                                <span>ยกเลิก</span>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-delete" data-id="<?php echo $author["auth_id"]; ?>" data-img="<?php echo $author["auth_img"]; ?>">
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
                    let img = $(this).data('img');

                    deleteConfirm(id, img);
                });
            });

            function deleteConfirm(id, img) {
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
                                url: 'process/author_del.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    img: img
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า author_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'author_del_form?id=<?php echo $base64Encoded; ?>';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'author_show';
                    }
                });
            }
        </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>