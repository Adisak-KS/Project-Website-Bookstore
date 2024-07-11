<?php
$titlePage = "ลบข้อมูลช่องทางติดต่อ";

require_once("../db/connectdb.php");
require_once("../db/controller/ContactController.php");
require_once("../includes/salt.php");
require_once("../admin/includes/functions.php");

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $ctId = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $ContactController = new ContactController($conn);
    $contact = $ContactController->getDetailContact($ctId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($contact);
} else {
    header('Location: contact_show');
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
        <!-- ============================================================== -->d

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">
                    <form id="formContact" novalidate action="process/contact_del" method="post">
                        <div class="row">
                            <!-- id="formProductType" -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>ข้อมูลช่องทางติดต่อ</span>
                                        </h4>

                                        <div class="mb-3">
                                            <label for="id" class="form-label">รหัสช่องทางติดต่อ :</label>
                                            <p><?php echo  $contact['ct_id']; ?></p>
                                            <input type="hidden" name="ct_id" class="form-control" value="<?php echo $contact['ct_id']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shp_name" class="form-label">ชื่อช่องทางติดต่อ :</label>
                                            <p><?php echo $contact['ct_name']; ?></p>
                                        </div>
                                        <?php if ($contact['ct_id'] == 1 || $contact['ct_id'] == 2 || $contact['ct_id'] == 3) { ?>
                                            <div class="mb-3">
                                                <label for="ct_detail" class="form-label">ลิงค์ช่องทางติดต่อ :</label><span class="text-danger">*</span>
                                                <?php if (empty($contact['ct_detail'])) { ?>
                                                    <p class="text-danger">*ไม่ได้ระบุ ลิงค์ช่องทางติดต่อ</p>
                                                <?php } else { ?>
                                                    <p><?php echo $contact['ct_detail']; ?></p>
                                                <?php } ?>
                                            </div>
                                            <div class="mb-3">
                                                <label for="ct_detail" class="form-label">ข้อความแสดงแทนลิงค์ :</label><span class="text-danger">*</span>
                                                <?php if (empty($contact['ct_name_link'])) { ?>
                                                    <p class="text-danger">*ไม่ได้ระบุ ข้อความแสดงแทนลิงค์</p>
                                                <?php } else { ?>
                                                    <p><?php echo $contact['ct_name_link']; ?></p>
                                                <?php } ?>
                                            </div>

                                        <?php } elseif ($contact['ct_id'] == 4) { ?>
                                            <div class="mb-3">
                                                <label for="ct_detail" class="form-label">อีเมล :</label><span class="text-danger">*</span>
                                                <?php if (empty($contact['ct_detail'])) { ?>
                                                    <p class="text-danger">*ไม่ได้ระบุ อีเมล</p>
                                                <?php } else { ?>
                                                    <p><?php echo $contact['ct_detail']; ?></p>
                                                <?php } ?>
                                            </div>
                                        <?php } elseif ($contact['ct_id'] == 5) { ?>
                                            <div class="mb-3">
                                                <label for="ct_detail" class="form-label">เบอร์โทร :</label><span class="text-danger">*</span>
                                                <?php if (empty($contact['ct_detail'])) { ?>
                                                    <p class="text-danger">*ไม่ได้ระบุ เบอร์โทร</p>
                                                <?php } else { ?>
                                                    <p><?php echo $contact['ct_detail']; ?></p>
                                                <?php } ?>
                                            </div>
                                        <?php } elseif ($contact['ct_id'] == 6) { ?>
                                            <div class="mb-3">
                                                <label for="ct_detail" class="form-label">ที่อยู่ :</label><span class="text-danger">*</span>
                                                <?php if (empty($contact['ct_detail'])) { ?>
                                                    <p class="text-danger">*ไม่ได้ระบุ ที่อยู่</p>
                                                <?php } else { ?>
                                                    <p><?php echo $contact['ct_detail']; ?></p>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->


                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <?php if ($contact['ct_id'] == 7) { ?>
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="mb-3 header-title text-warning">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <span>ตำแหน่งสถานที่</span>
                                            </h4>
                                            <?php if (empty($contact['ct_detail'])) { ?>
                                                <p class="text-danger">*ไม่ได้ระบุ ตำแหน่งสถานที่</p>
                                            <?php } else { ?>
                                                <div class="ratio ratio-16x9">
                                                    <?php echo $contact['ct_detail'] ?>
                                                </div>
                                            <?php } ?>
                                        </div> <!-- end card-body-->
                                    </div> <!-- end card-->
                                <?php } ?>

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-danger">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>สถานะการแสดง</span>
                                        </h4>

                                        <div class="form-check mb-2 form-check-success">
                                            <input class="form-check-input" type="radio" name="ct_status" id="1" value="1" <?php if ($contact['ct_status'] == 1) {
                                                                                                                                echo 'checked';
                                                                                                                            } ?> disabled>
                                            <label class="form-check-label" for="1">แสดง</label>
                                        </div>

                                        <div class="form-check mb-2 form-check-danger">
                                            <input class="form-check-input" type="radio" name="ct_status" id="0" value="0" <?php if ($contact['ct_status'] != 1) {
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
                                        <h4 class="mb-3 header-title text-danger">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $contact['ct_time'] ?></span></h4>
                                        <div>
                                            <a href="contact_show" class="btn btn-secondary me-2">
                                                <i class="fa-solid fa-xmark me-1"></i>
                                                <span>ยกเลิก</span>
                                            </a>
                                            <?php if (empty($contact['ct_detail'])) { ?>
                                                <button type="button" class="btn btn-danger" disabled>
                                                    <i class="fa-solid fa-trash"></i>
                                                    <span>ลบข้อมูล</span>
                                                </button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-danger btn-delete" data-id="<?php echo $contact["ct_id"]; ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                    <span>ลบข้อมูล</span>
                                                </button>
                                            <?php } ?>
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

                    deleteConfirm(id);
                });
            });

            function deleteConfirm(id) {
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
                                url: 'process/contact_del.php',
                                type: 'POST',
                                data: {
                                    id: id,
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า contact_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'contact_del_form?id=<?php echo $base64Encoded; ?>';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'contact_show';
                    }
                });
            }
        </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>