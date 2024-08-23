<?php
$titlePage = "รายละเอียดรายการตามหาสินค้าตามสั่ง";

require_once("../db/connectdb.php");
require_once("../db/controller/ProductRequestController.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $prqId = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $ProductRequestController = new ProductRequestController($conn);
    $productRequest = $ProductRequestController->getProductRequestDetail($prqId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($productRequest);

    $productResponse = $ProductRequestController->getProductResponse($prqId);
} else {
    header('Location: member_show');
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
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title text-primary">
                                        <i class="fa-solid fa-bullhorn"> <span class="text-dark">หัวข้อการค้นหา :</span> </i>
                                        <span><?php echo $productRequest['prq_title']; ?></span>
                                        <hr>
                                    </h4>
                                    <div class="d-flex justify-content-between">
                                        <div class="row">
                                            <div class="">
                                                <p><strong>ชื่อสินค้า : </strong><?php echo $productRequest['prq_prd_name'] ?></p>
                                            </div>
                                            <div class="">
                                                <p><strong>สำนักพิมพ์ : </strong><?php echo $productRequest['prq_publisher'] ?></p>
                                            </div>
                                            <div class="">
                                                <p><strong>ชื่อผู้เขียน : </strong><?php echo $productRequest['prq_author'] ?></p>
                                            </div>
                                            <div class="">
                                                <p><strong>เล่มที่ : </strong><?php echo number_format($productRequest['prq_prd_volume_number']) ?></p>
                                            </div>
                                            <div class="">
                                                <p><strong>รายละเอียด : </strong><?php echo $productRequest['prq_detail']; ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <p><strong>รูปเพิ่มเติม</strong></p>
                                            <img style="width: 200px; height:230px; object-fit:cover" src="../uploads/img_product_request/<?php echo $productRequest['prq_img'] ?>" alt="">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <hr>
                                        <div class="">
                                            <p><strong>จาก : </strong><?php echo $productRequest['mem_fname'] . " " . $productRequest['mem_lname'] . " (@" . $productRequest['mem_username'] . ")" ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p>
                                            <str ong>วัน / เวลา : </str><?php echo $productRequest['prq_time_create'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div> <!-- end card-body-->
                        </div>

                        <?php if ($productResponse) { ?>
                            <?php foreach ($productResponse as $row) { ?>
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="mb-3 header-title text-warning">
                                                <i class="fa-solid fa-share fa-rotate-180"></i>
                                                <span>ผลการค้นหา</span>
                                                <?php
                                                if ($row['prp_status'] == 'wait') {
                                                    echo '<span class="badge rounded-pill text-bg-warning ms-2">รอสมาชิกตอบกลับ</span>';
                                                } elseif ($row['prp_status'] == 'success') {
                                                    echo '<span class="badge rounded-pill text-bg-success ms-2">สินค้าที่สมาชิกตามหา</span>';
                                                } else {
                                                    echo '<span class="badge rounded-pill text-bg-danger ms-2">ไม่ใช่สินค้าที่ตามหา</span>';
                                                }
                                                ?>
                                                <hr>
                                            </h4>
                                            <div class="d-flex justify-content-between">
                                                <div class="row">
                                                    <div class="">
                                                        <p><strong>ชื่อสินค้า : </strong><?php echo $row['prp_prd_name'] ?></p>
                                                    </div>
                                                    <div class="">
                                                        <p><strong>สำนักพิมพ์ : </strong><?php echo $row['prp_publisher'] ?></p>
                                                    </div>
                                                    <div class="">
                                                        <p><strong>ชื่อผู้เขียน : </strong><?php echo $row['prp_author'] ?></p>
                                                    </div>
                                                    <div class="">
                                                        <p><strong>เล่มที่ : </strong><?php echo number_format($row['prp_prd_volume_number']) ?></p>
                                                    </div>
                                                    <div class="">
                                                        <p><strong>รายละเอียด : </strong><?php echo $row['prp_detail'] ?></p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <p><strong>รูปเพิ่มเติม</strong></p>
                                                    <img style="width: 200px; height:230px; object-fit:cover" src="../uploads/img_product_request/<?php echo $row['prp_img'] ?>" alt="">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <hr>
                                                <p><strong>วัน / เวลา : </strong><?php echo $row['prp_time_create'] ?></p>
                                            </div>
                                        </div>
                                    </div> <!-- end card-body-->
                                </div>
                            <?php } ?>
                        <?php } ?>


                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <form id="formProductResponse" novalidate action="process/product_request_add.php" method="post" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <h4 class="mb-3 header-title text-warning">
                                            <i class="fa-solid fa-share fa-rotate-180"></i>
                                            <span>ตอบกลับการค้นหา</span>
                                        </h4>
                                        <input type="hidden" name="prq_id" value="<?php echo $productRequest['prq_id'] ?>" readonly>
                                        <input type="hidden" name="emp_id" value="<?php echo $_SESSION['emp_id'] ?>" readonly>

                                        <div class="mb-3">
                                            <label for="" class="form-label">ชื่อหนังสือ :</label><span class="text-danger">*</span>
                                            <input type="text" name="prp_prd_name" class="form-control" placeholder="กรุณาระบุ ชื่อหนังสือ" maxlength="200">
                                        </div>
                                        <div class="mb-3">
                                            <label for="" class="form-label">สำนักพิมพ์ :</label><span class="text-danger">*</span>
                                            <input type="text" name="prp_publisher" class="form-control" placeholder="กรุณาระบุ ชื่อผู้เขียน" maxlength="100">
                                        </div>
                                        <div class="mb-3">
                                            <label for="" class="form-label">ชื่อผู้เขียน :</label><span class="text-danger">*</span>
                                            <input type="text" name="prp_author" class="form-control" placeholder="กรุณาระบุ ชื่อผู้เขียน" maxlength="100">
                                        </div>
                                        <div class="mb-3">
                                            <label for="" class="form-label">เล่มที่ :</label>
                                            <input type="number" name="prp_prd_volume_number" class="form-control" min="0" placeholder="กรุณาระบุ เล่มที่">
                                        </div>
                                        <div class="mb-3">
                                            <label for="" class="form-label">รายละเอียดอื่น ๆ :</label><span class="text-danger">*</span>
                                            <textarea name="prp_detail" class="form-control" placeholder="กรุณาระบุ รายละเอียดอื่น ๆ"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="" class="form-label">รูปสินค้า :</label><span class="text-danger">*</span>
                                            <input type="file" name="prp_img" class="form-control" accept="image/png, image/jpeg, image/jpg" onchange="previewImg(this)">
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <img class="mt-3" id="previewImage" width="100%" style="object-fit: cover;" alt="">
                                        </div>

                                        <hr>

                                        <button type="submit" name="btn-add" class="btn btn-warning me-2"">
                                            <i class=" fa-solid fa-paper-plane"></i>
                                            <span>ตอบกลับ</span>
                                        </button>
                                    </div> <!-- end card-body-->
                                </form>
                            </div> <!-- end card-->
                        </div>

                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="mb-3 header-title text-warning">จัดการข้อมูลรายการนี้</h4>
                                <div>
                                    <a href="product_request_show" class="btn btn-secondary me-2">
                                        <i class="fa-solid fa-xmark me-1"></i>
                                        <span>กลับ</span>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-cancel" data-id="<?php echo $productRequest["prq_id"]; ?>">
                                        <i class="fa-solid fa-trash"></i>
                                        <span> ยกเลิกรายการตามหานี้</span>
                                    </button>
                                </div>

                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
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
                    document.getElementById('previewImage').style.width = '150px'; // Set height to 360px
                }
                reader.readAsDataURL(file);
            }
        }
    </script>

    <!-- Cancel  -->
    <script>
        $(document).ready(function() {
            $(".btn-cancel").click(function(e) {
                e.preventDefault();
                let prqId = $(this).data('id');
                deleteConfirm(prqId);
            });
        });

        function deleteConfirm(prqId) {
            Swal.fire({
                icon: "warning",
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการยกเลิกรายการนี้ใช่ไหม!",
                showCancelButton: true,
                confirmButtonColor: '#f34e4e',
                confirmButtonText: 'ใช่, ยกเลิก!',
                cancelButtonText: 'ยกเลิก',
                preConfirm: function() {
                    return $.ajax({
                            url: 'process/product_request_cancel',
                            type: 'POST',
                            data: {
                                prqId: prqId,
                            },
                        })
                        .done(function() {
                            // การลบสำเร็จ ทำการ redirect ไปยังหน้า product_request_show
                            return true;
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สำเร็จ',
                                text: 'เกิดข้อผิดพลาดที่ ajax !',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.location.href = 'product_request_detail?id=<?php echo $base64Encoded; ?>';
                                }
                            });
                        });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = 'product_request_show';
                }
            });
        }
    </script>

</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>