<?php
$titlePage = "แบนเนอร์";

require_once("../db/connectdb.php");
require_once("../db/controller/BannerController.php");
$BannerController = new BannerController($conn);

$banners = $BannerController->getBanner();


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
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body taskboard-box">
                                    <h4 class="header-title mt-0 mb-3">ข้อมูลแบนเนอร์ / โปรโมท</h4>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
                                        <i class="fa-solid fa-plus"></i>
                                        <span> เพิ่มแบนเนอร์</span>
                                    </button>

                                    <!-- Scrollable modal -->
                                    <form id="formBanner" novalidate action="process/banner_add" method="post" enctype="multipart/form-data">
                                        <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAdd" data-bs-backdrop="static" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">เพิ่มแบนเนอร์</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="bn_name" class="form-label">ชื่อแบนเนอร์ :</label><span class="text-danger">*</span>
                                                            <input type="text" name="bn_name" class="form-control" placeholder="ระบุ ชื่อแบนเนอร์" maxlength="100">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="bn_link" class="form-label">ลิงค์ URL :</label><span class="text-danger">*</span>
                                                            <input type="url" name="bn_link" class="form-control" placeholder="ระบุ ชื่อแบนเนอร์">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="bn_img" class="form-label">รูปภาพ :</label><span class="text-danger">*</span>
                                                            <input type="file" id="bn_img" name="bn_img" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="previewImg(this)">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="bn_status" class="form-label">สถานะการแสดง :</label><span class="text-danger">*</span>
                                                            <div class="form-check mb-2 form-check-success">
                                                                <input class="form-check-input" type="radio" name="bn_status" id="1" value="1">
                                                                <label class="form-check-label" for="1">แสดง</label>
                                                            </div>
                                                            <div class="form-check mb-2 form-check-danger">
                                                                <input class="form-check-input" type="radio" name="bn_status" id="0" value="0" checked>
                                                                <label class="form-check-label" for="0">ไม่แสดง</label>
                                                            </div>
                                                        </div>
                                                        <div style="max-height: 360px; overflow: hidden;">
                                                            <img id="previewImage" width="100%" style="object-fit: cover;" alt="">
                                                        </div>


                                                        <!-- <div class="mb-3">
                                                            <label for="auth_status" class="form-label">สถานะการแสดง :</label><span class="text-danger">*</span>
                                                            <div class="form-check mb-2 form-check-success">
                                                                <input class="form-check-input" type="radio" name="auth_status" id="1" value="1" checked>
                                                                <label class="form-check-label" for="1">แสดง</label>
                                                            </div>
                                                            <div class="form-check mb-2 form-check-danger">
                                                                <input class="form-check-input" type="radio" name="auth_status" id="0" value="0">
                                                                <label class="form-check-label" for="0">ไม่แสดง</label>
                                                            </div>
                                                        </div> -->
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
                                        </div>
                                    </form>
                                    <hr>

                                    <?php if ($banners) { ?>
                                        <div class="d-flex align-items-center mb-2">
                                            <p class="text-danger mb-0 me-2" style="flex: 1 1 auto;">*สามารถลากวางเพื่อกำหนดการแสดงก่อน-หลังได้</p>
                                            <i class="fa-solid fa-arrow-up-1-9 fa-lg text-danger"></i>
                                        </div>


                                        <ul class="sortable-list list-unstyled taskList" id="upcoming">
                                            <?php foreach ($banners as $row) { ?>
                                                <li data-id="<?php echo $row['bn_id']; ?>">
                                                    <div class="kanban-box">
                                                        <div class="">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <ul class="list-inline d-flex justify-content-center align-items-center">
                                                                    <li class="list-inline-item">
                                                                        <img src="../uploads/img_banner/<?php echo $row['bn_img']; ?>" alt="img" class="avatar-sm rounded" style="width: 100px; height: 60px; object-fit: cover;">
                                                                    </li>
                                                                    <li class="list-inline-item mx-3">
                                                                        <h5 class="mt-0 text-dark">
                                                                            <?php
                                                                            $name = $row['bn_name'];
                                                                            echo (mb_strlen($name, 'UTF-8') > 15) ? mb_substr($name, 0, 15, 'UTF-8') . '...' : $name;
                                                                            ?>
                                                                        </h5>

                                                                        <?php if ($row['bn_status'] == 1) { ?>
                                                                            <span class="badge bg-success float-start">แสดง</span>
                                                                        <?php } else { ?>
                                                                            <span class="badge bg-danger float-start">ไม่แสดง</span>
                                                                        <?php } ?>
                                                                    </li>
                                                                </ul>
                                                                <ul class="list-inline align-self-end">
                                                                    <?php
                                                                    $originalId = $row["bn_id"];
                                                                    require_once("../includes/salt.php");
                                                                    $saltedId = $salt1 . $originalId . $salt2;
                                                                    $base64Encoded = base64_encode($saltedId);
                                                                    ?>
                                                                    <li class="list-inline-item">
                                                                        <a href="banner_edit_form?id=<?php echo $base64Encoded ?>" class="btn btn-warning">
                                                                            <i class="fa-solid fa-pen-to-square me-1"></i>
                                                                            แก้ไข
                                                                        </a>
                                                                    </li>
                                                                    <li class="list-inline-item">
                                                                        <a href="banner_del_form?id=<?php echo $base64Encoded ?>" class="btn btn-danger">
                                                                            <i class="fa-solid fa-trash me-1"></i>
                                                                            ลบ
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } else { ?>
                                        <?php require_once("./includes/no_information.php") ?>
                                    <?php } ?>

                                    <div class="text-center pt-2">
                                        <button type="button" id="saveOrder" class="btn btn-warning">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>
                                            บันทึกการแก้ไขลำดับ
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div><!-- end col -->
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

    <!-- List slide show  -->
    <script>
        $(function() {
            $(".sortable-list").sortable();

            $("#saveOrder").click(function() {
                const order = []; // ใช้ const สำหรับอาร์เรย์ที่ไม่เปลี่ยนแปลงอ้างอิง

                $(".sortable-list li").each(function(index) {
                    const id = $(this).data('id'); // ใช้ const สำหรับค่าที่ไม่เปลี่ยนแปลง
                    if (id !== undefined) {
                        order.push({
                            id: id,
                            bn_number_list: order.length + 1 // นับลำดับตามที่แสดงใน UI
                        });
                    }
                });

                console.log(order); // ตรวจสอบอาร์เรย์ order

                $.ajax({
                    url: 'process/banner_number_list_edit.php',
                    method: 'POST',
                    data: {
                        order: order
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: 'ลำดับถูกบันทึกเรียบร้อยแล้ว'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: xhr.responseText
                        });
                    }
                });
            });
        });
    </script>

    <script>
        function previewImg(input) {
            const file = input.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024; // in MB
                const validTypes = ["image/png", "image/jpeg", "image/jpg"];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด!',
                        text: 'กรุณาอัปโหลดไฟล์รูปภาพเฉพาะประเภท PNG, JPG, หรือ JPEG เท่านั้น',
                    });
                    input.value = ''; // Clear input file
                    document.getElementById('previewImage').src = ''; // Clear preview image
                    document.getElementById('previewImage').style.height = ''; // Clear height if not valid
                    return false;
                }
                if (fileSize > 2) {
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด!',
                        text: 'ขนาดไฟล์รูปภาพต้องไม่เกิน 2MB',
                    });
                    input.value = ''; // Clear input file
                    document.getElementById('previewImage').src = ''; // Clear preview image
                    document.getElementById('previewImage').style.height = ''; // Clear height if not valid
                    return false;
                }
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('previewImage').style.height = '250px'; // Set height to 360px
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>