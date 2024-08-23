<?php
$titlePage = "ค้นหาหนังสือตามสั่ง";
require_once('db/connectdb.php');

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: login_form');
    exit;
}
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="login">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- Add your site or application content here -->
    <!-- header-area-start -->
    <?php require_once("layouts/nav_topbar.php"); ?>
    <!-- header-area-end -->

    <!-- user-login-area-start -->
    <div class="user-login-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="login-title text-center mb-30 mt-3">
                        <h3>ตามหาหนังสือ</h3>
                        <p>บอกรายละเอียดที่พอจะจำได้ เกี่ยวกับสินค้าที่ต้องการ</p>
                    </div>
                </div>
                <div class="offset-lg-2 col-lg-8 col-md-12 col-12 border-1">
                    <div class="billing-fields mt-3">
                        <!-- id="formRegister" -->
                        <form id="formProductRequest" novalidate action="process/product_request_add.php" method="post" enctype="multipart/form-data">
                            <div class="account-details-form d-flex justify-content-center">
                                <div class="row d-flex justify-content-center col-lg-8">
                                    <div class="col-lg-12">
                                        <input type="hidden" name="mem_id" value="<?php echo $_SESSION['mem_id']; ?>" readonly>

                                        <div class="single-input-item mb-3">
                                            <label for="recipient_id" class="form-label">หัวเรื่องการค้นหา</label>
                                            <input type="text" class="form-control" name="prq_title" placeholder="กรุณาระบุ หัวเรื่องการค้นหา" maxlength="200">
                                        </div>
                                        <div class="single-input-item mb-3">
                                            <label for="recipient_id" class="form-label">ชื่อหนังสือ</label>
                                            <input type="text" class="form-control" name="prq_prd_name" placeholder="กรุณาระบุ ชื่อหนังสือ" maxlength="200">
                                        </div>
                                        <div class="single-input-item mb-3">
                                            <label for="recipient_id" class="form-label">สำนักพิมพ์</label>
                                            <input type="text" class="form-control" name="prq_publisher" placeholder="กรุณาระบุ ชื่อผู้เขียน" maxlength="100">
                                        </div>
                                        <div class="single-input-item mb-3">
                                            <label for="recipient_id" class="form-label">ชื่อผู้เขียน</label>
                                            <input type="text" class="form-control" name="prq_author" placeholder="กรุณาระบุ ชื่อผู้เขียน" maxlength="100">
                                        </div>
                                        <div class="single-input-item mb-3">
                                            <label for="recipient_id" class="form-label">เล่มที่</label>
                                            <input type="number" class="form-control" name="prq_prd_volume_number" min="1" placeholder="กรุณาระบุ เล่มที่">
                                        </div>
                                        <div class="single-input-item mb-3">
                                            <label for="recipient_id" class="form-label">รายละเอียด</label>
                                            <textarea class="form-control" name="prq_detail" maxlength="255"></textarea>
                                        </div>
                                        <div class="single-input-item mb-3">
                                            <label for="recipient_id" class="form-label">รูปตัวอย่าง</label>
                                            <input type="file" class="form-control" name="prq_img" accept="image/png, image/jpeg, image/jpg" onchange="previewImg(this)">
                                            <div class="d-flex justify-content-center">
                                                <img class="mt-3" id="previewImage" width="100%" style="object-fit: cover;" alt="">
                                            </div>
                                        </div>
                                        <div class="single-register">
                                            <hr>
                                            <button type="submit" name="btn-add" class="btn btn-primary">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                ส่งเรื่องค้นหาสินค้า
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- user-login-area-end -->


    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>
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
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>