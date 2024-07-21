<?php
$titlePage = "ลบแบนเนอร์";

require_once("../db/connectdb.php");
require_once("../db/controller/BannerController.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $bnId = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $BannerController = new BannerController($conn);
    $banner = $BannerController->getDetailBanner($bnId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($banner);
} else {
    header('Location: banner_show');
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
                    <form action="process/banner_edit" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>ข้อมูลแบนเนอร์</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสแบนเนอร์ :</label>
                                            <p><?php echo  $banner['bn_id']; ?></p>
                                            <input type="hidden" name="bn_id" class="form-control" value="<?php echo $banner['bn_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="bn_name" class="form-label">ชื่อแบนเนอร์ :</label><span class="text-danger">*</span>
                                            <p><?php echo $banner['bn_name']; ?></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="bn_link" class="form-label">ลิงค์ URL :</label><span class="text-danger">*</span>
                                            <p><?php echo $banner['bn_link']; ?></p>
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
                                            <span>รูปภาพแบนเนอร์</span>
                                        </h4>

                                        <div class="">
                                            <img class="rounded mx-auto d-block img-fluid" id="bn_img" style="width:150px; height:150px; object-fit: cover;" src="../uploads/img_banner/<?php echo $banner['bn_img'] ?>">
                                            <input type="hidden" name="bn_img" value="<?php echo $banner['bn_img'] ?>" readonly>
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
                                            <input class="form-check-input" type="radio" name="bn_status" id="1" value="1" <?php if ($banner['bn_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?> disabled>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="bn_status" id="0" value="0" <?php if ($banner['bn_status'] != 1) {
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
                                        <h4 class="mb-3 header-title text-danger">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $banner['bn_time_update'] ?></span></h4>
                                        <div>
                                            <a href="banner_show" class="btn btn-secondary me-2">
                                                <i class="fa-solid fa-xmark me-1"></i>
                                                <span>ยกเลิก</span>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-delete" data-id="<?php echo $banner["bn_id"]; ?>" data-img="<?php echo $banner["bn_img"]; ?>">
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

        <?php require_once('layouts/vendor.php') ?>
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
                                url: 'process/banner_del.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                    img: img
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า banner_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'banner_del_form?id=<?php echo $base64Encoded; ?>';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'banner_show';
                    }
                });
            }
        </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>