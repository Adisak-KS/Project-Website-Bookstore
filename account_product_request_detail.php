<?php
$titlePage = "รายละเอียดตามหาหนังสือตามสั่ง";

require_once("db/connectdb.php");
require_once("db/controller/MemberController.php");
require_once("db/controller/ProductRequestController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$MemberController = new MemberController($conn);
$ProductRequestController = new ProductRequestController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header("Location: login_form");
    exit;
} else {
    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $prqId = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $detail = $ProductRequestController->getProductRequestDetail($prqId);

    if (!$detail) {
        $_SESSION['error'] = "ไม่พบรายการค้นหาหนังสือที่คุณต้องการ";
        header("Location: account_product_request");
        exit;
    }

    $prdResponse = $ProductRequestController->getProductResponse($prqId);
}


?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="cart">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- Add your site or application content here -->

    <!-- header-area-start -->
    <?php require_once("layouts/nav_topbar.php"); ?>
    <!-- header-area-end -->


    <!-- breadcrumbs-area-start -->
    <div class="breadcrumbs-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumbs-menu">
                        <ul>
                            <li><a href="index">หน้าแรก</a></li>
                            <li><a href="javascript:void(0)" class="active">บัญชีของฉัน</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs-area-end -->

    <!-- entry-header-area-start -->
    <div class="entry-header-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header-title">
                        <h2>บัญชีของฉัน</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- entry-header-area-end -->

    <!-- my account wrapper start -->
    <div class="my-account-wrapper mb-70">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- My Account Page Start -->
                        <div class="myaccount-page-wrapper">
                            <!-- My Account Tab Menu Start -->
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <?php require_once("layouts/nav_menu_account.php") ?>
                                </div>
                                <!-- My Account Tab Menu End -->

                                <!-- My Account Tab Content Start -->
                                <div class="col-lg-9 col-md-8">
                                    <div class="tab-content" id="myaccountContent">
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                            <div class="myaccount-content border-3" style="border-color:#f07c29;">
                                                <div class="d-flex justify-content-between">
                                                    <h5>รายละเอียดตามหาหนังสือตามสั่ง</h5>
                                                    <p><strong>วัน / เวลา : </strong> <?php echo $detail['prq_time_create'] ?></p>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <div class="row">
                                                        <h6><strong>หัวเรื่องการค้นหา : <?php echo $detail['prq_title'] ?></strong></h6>

                                                        <?php if ($detail['prq_prd_name']) { ?>
                                                            <p><strong>ชื่อหนังสือ : </strong> <?php echo $detail['prq_prd_name'] ?></p>
                                                        <?php } ?>

                                                        <?php if ($detail['prq_publisher']) { ?>
                                                            <p><strong>สำนักพิมพ์ : </strong> <?php echo $detail['prq_publisher'] ?></p>
                                                        <?php } ?>

                                                        <?php if ($detail['prq_author']) { ?>
                                                            <p><strong>ชื่อผู้เขียน : </strong> <?php echo $detail['prq_author'] ?></p>
                                                        <?php } ?>

                                                        <?php if ($detail['prq_prd_volume_number']) { ?>
                                                            <p><strong>เล่มที่ : </strong> <?php echo number_format($detail['prq_prd_volume_number']) ?></p>
                                                        <?php } ?>

                                                        <p><strong>รายละเอียดอื่น ๆ : </strong> <?php echo $detail['prq_detail'] ?></p>

                                                        <div>
                                                            <?php if ($detail['prq_status'] !== 'success') { ?>
                                                                <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#editProductRequestModal">แก้ไข</button>
                                                            <?php } ?>
                                                            <button type="button" class="btn btn-danger btn-delete" data-id="<?php echo $detail["prq_id"]; ?>" data-img="<?php echo $detail["prq_img"]; ?>">
                                                                <i class="fa-solid fa-trash"></i>
                                                                <span>ลบ</span>
                                                            </button>
                                                        </div>

                                                        <!-- Modal -->
                                                        <form action="process/account_product_request_edit.php" method="post" enctype="multipart/form-data">
                                                            <div class="modal fade" id="editProductRequestModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-scrollable">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                                แก้ไขรายละเอียดตามหาหนังสือ
                                                                            </h1>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="account-details-form">
                                                                                <div class="row">
                                                                                    <input type="hidden" name="prq_id" value="<?php echo $detail['prq_id'] ?>" readonly>
                                                                                    <input type="hidden" name="mem_id" value="<?php echo $detail['mem_id'] ?>" readonly>
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="recipient_id" class="form-label">หัวเรื่องการค้นหา</label>
                                                                                        <input type="text" class="form-control" name="prq_title" placeholder="กรุณาระบุ หัวเรื่องการค้นหา" value="<?php echo $detail['prq_title'] ?>" maxlength="200">
                                                                                    </div>
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="recipient_id" class="form-label">ชื่อหนังสือ</label>
                                                                                        <input type="text" class="form-control" name="prq_prd_name" placeholder="กรุณาระบุ ชื่อหนังสือ" value="<?php echo $detail['prq_prd_name'] ?>" maxlength="200">
                                                                                    </div>
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="recipient_id" class="form-label">สำนักพิมพ์</label>
                                                                                        <input type="text" class="form-control" name="prq_publisher" placeholder="กรุณาระบุ ชื่อผู้เขียน" value="<?php echo $detail['prq_publisher'] ?>" maxlength="100">
                                                                                    </div>
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="recipient_id" class="form-label">ชื่อผู้เขียน</label>
                                                                                        <input type="text" class="form-control" name="prq_author" placeholder="กรุณาระบุ ชื่อผู้เขียน" value="<?php echo $detail['prq_author'] ?>" maxlength="100">
                                                                                    </div>
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="recipient_id" class="form-label">เล่มที่</label>
                                                                                        <input type="number" class="form-control" name="prq_prd_volume_number" min="1" placeholder="กรุณาระบุ เล่มที่" value="<?php echo $detail['prq_prd_volume_number'] ?>">
                                                                                    </div>
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="recipient_id" class="form-label">รายละเอียด</label>
                                                                                        <textarea class="form-control" name="prq_detail" maxlength="255"><?php echo $detail['prq_detail'] ?></textarea>
                                                                                    </div>
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="recipient_id" class="form-label">รูปตัวอย่าง</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                                                                        <input type="hidden" name="prq_oldImg" value="<?php echo $detail['prq_img'] ?>" readonly>
                                                                                        <input type="file" class="form-control" name="prq_newImg" id="prq_newImg" accept="image/png, image/jpeg, image/jpg">

                                                                                        <img class="mx-auto d-block img-fluid mt-1" id="prq_oldImg" style="width:150px; height:180px; object-fit: cover; display: none;" src="">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" data-bs-dismiss="modal" class="btn">ยกเลิก</button>
                                                                            <button type="submit" name="btn-edit" class="btn btn-sqr"><i class="fa-solid fa-floppy-disk"></i> แก้ไข</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>

                                                    </div>
                                                    <?php if ($detail['prq_img']) { ?>
                                                        <div class="row">
                                                            <p><strong>รูปตัวอย่าง : </strong></p>
                                                            <img class="mx-auto" style="height: 250px; width:200px; object-fit: cover;" src="uploads/img_product_request/<?php echo $detail['prq_img'] ?>" alt="">
                                                        </div>
                                                    <?php } ?>


                                                </div>
                                            </div>

                                            <?php if ($prdResponse) { ?>
                                                <?php foreach ($prdResponse as $row) { ?>
                                                    <div class="myaccount-content my-3 <?php if ($row['prp_status'] == 'true') {
                                                                                            echo 'border-3 border-success';
                                                                                        } else {
                                                                                            echo 'border-5';
                                                                                        } ?>">
                                                        <div class="d-flex justify-content-between">
                                                            <h5>
                                                                ผลลัพธ์ตามหาหนังสือตามสั่ง
                                                                <?php
                                                                if ($row['prp_status'] == 'true') {
                                                                    echo '<span class="badge rounded-pill text-bg-success ms-2"><i class="fa-solid fa-check"></i></span>';
                                                                }
                                                                ?>
                                                            </h5>
                                                            <p><strong>วัน / เวลา : </strong> <?php echo $row['prp_time_create'] ?></p>
                                                        </div>

                                                        <div class="d-flex justify-content-between">
                                                            <div class="row">

                                                                <?php if ($row['prp_prd_name']) { ?>
                                                                    <p><strong>ชื่อหนังสือ : </strong> <?php echo $row['prp_prd_name'] ?></p>
                                                                <?php } ?>

                                                                <?php if ($row['prp_publisher']) { ?>
                                                                    <p><strong>สำนักพิมพ์ : </strong> <?php echo $row['prp_publisher'] ?></p>
                                                                <?php } ?>

                                                                <?php if ($row['prp_author']) { ?>
                                                                    <p><strong>ชื่อผู้เขียน : </strong> <?php echo $row['prp_author'] ?></p>
                                                                <?php } ?>

                                                                <?php if ($row['prp_prd_volume_number']) { ?>
                                                                    <p><strong>เล่มที่ : </strong> <?php echo number_format($row['prp_prd_volume_number']) ?></p>
                                                                <?php } ?>

                                                                <p><strong>รายละเอียดอื่น ๆ : </strong> <?php echo $row['prp_detail'] ?></p>

                                                                <div>
                                                                    <?php if ($row['prp_status'] == 'wait') { ?>
                                                                        <form action="process/product_request_add.php" method="post">
                                                                            <input type="number" name="prq_id" value="<?php echo $row['prq_id'] ?>" readonly>
                                                                            <input type="number" name="prp_id" value="<?php echo $row['prp_id'] ?>" readonly>

                                                                            <button type="submit" name="btn-confirm-product-request" class="btn btn-sqr">
                                                                                <i class="fa-solid fa-floppy-disk"></i>
                                                                                ใช่ สินค้าที่ตามหา
                                                                            </button>

                                                                            <button type="submit" name="btn-reset-product-request" class="btn btn-sqr">
                                                                                <i class="fa-solid fa-floppy-disk"></i>
                                                                                ไม่ใช่ สินค้าที่ตามหา ค้นหาใหม่
                                                                            </button>
                                                                        </form>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <?php if ($row['prp_img']) { ?>
                                                                <div class="row ps-5">
                                                                    <p class="ms-5"><strong>รูปตัวอย่าง : </strong></p>
                                                                    <img class="mx-auto" style="height: 250px; width:200px; object-fit: cover;" src="uploads/img_product_request/<?php echo $row['prp_img'] ?>" alt="">
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>

                                            <form action="process/product_request_add.php" method="post">
                                                <div class="myaccount-content my-3 border-5 d-flex justify-content-between">
                                                    <?php if ($detail['prq_status'] !== 'success' && $detail['prq_status'] !== 'cancel') { ?>
                                                        <button type="button" class="btn btn-danger btn-cancel" data-id="<?php echo $detail["prq_id"]; ?>">
                                                            <span> ยกเลิกรายการตามหานี้</span>
                                                        </button>
                                                    <?php } ?>

                                                    <a href="account_product_request" class="btn">
                                                        กลับ
                                                    </a>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                    <!-- Single Tab Content End -->
                                </div>
                            </div> <!-- My Account Tab Content End -->
                        </div>
                    </div> <!-- My Account Page End -->
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- my account wrapper end -->

    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>

    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.1/js/dataTables.searchPanes.min.js"></script>
    <script src="https://cdn.datatables.net/searchpanes/2.3.1/js/searchPanes.bootstrap5.min.js"></script>

    <!-- preview New Profile, check file type, file size  -->
    <script>
        // Function to update the image display
        function updateImageDisplay(imgSrc) {
            const imgElement = document.getElementById('prq_oldImg');
            if (imgSrc) {
                imgElement.src = imgSrc;
                imgElement.style.display = 'block';
            } else {
                imgElement.style.display = 'none';
            }
        }

        // Check if there is an existing image and set its display
        const oldImgSrc = '<?php echo $detail["prq_img"] ?>';
        updateImageDisplay(oldImgSrc ? 'uploads/img_product_request/' + oldImgSrc : '');

        document.getElementById('prq_newImg').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            const maxSize = 2 * 1024 * 1024; // 2 MB in bytes

            if (file && allowedTypes.includes(file.type) && file.size <= maxSize) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    updateImageDisplay(e.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                // Reset the input
                event.target.value = '';
                // Reset to the original image
                updateImageDisplay(oldImgSrc ? 'uploads/img_product_request/' + oldImgSrc : '');

                if (file && !allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'คำเตือน',
                        text: 'ต้องเป็นไฟล์ .png .jpg .jpeg เท่านั้น',
                    });
                }

                if (file && allowedTypes.includes(file.type) && file.size > maxSize) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'คำเตือน',
                        text: 'ขนาดไฟล์เกิน 2 MB',
                    });
                }
            }
        });
    </script>

    <!-- Delete  -->
    <script>
        $(document).ready(function() {
            $(".btn-delete").click(function(e) {
                e.preventDefault();
                let prqId = $(this).data('id');
                let img = $(this).data('img');

                deleteConfirm(prqId, img);
            });
        });

        function deleteConfirm(prqId, img) {
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
                            url: 'process/account_product_request_del',
                            type: 'POST',
                            data: {
                                prqId: prqId,
                                img: img
                            },
                        })
                        .done(function() {
                            // การลบสำเร็จ ทำการ redirect
                            return true;
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สำเร็จ',
                                text: 'เกิดข้อผิดพลาดที่ ajax !',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.location.href = 'account_product_request_detail?id=<?php echo $base64Encoded; ?>';
                                }
                            });
                        });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = 'account_product_request';
                }
            });
        }
    </script>

    <!-- Cancel  -->
    <script>
        $(document).ready(function() {
            $(".btn-cancel").click(function(e) {
                e.preventDefault();
                let prqId = $(this).data('id');

                cancelConfirm(prqId);
            });
        });

        function cancelConfirm(prqId) {
            Swal.fire({
                icon: "warning",
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการยกเลิกรายการนี้ใช่ไหม!",
                showCancelButton: true,
                confirmButtonColor: '#f34e4e',
                confirmButtonText: 'ใช่, ยกเลิกเลย!',
                cancelButtonText: 'ยกเลิก',
                preConfirm: function() {
                    return $.ajax({
                            url: 'process/account_product_request_cancel',
                            type: 'POST',
                            data: {
                                prqId: prqId,
                            },
                        })
                        .done(function() {
                            // การลบสำเร็จ ทำการ redirect
                            return true;
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สำเร็จ',
                                text: 'เกิดข้อผิดพลาดที่ ajax !',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.location.href = 'account_product_request_detail?id=<?php echo $base64Encoded; ?>';
                                }
                            });
                        });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = 'account_product_request';
                }
            });
        }
    </script>
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>