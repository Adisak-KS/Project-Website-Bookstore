<?php
$titlePage = "รายละเอียดรีวิวสินค้า";

require_once("../db/connectdb.php");
require_once("../includes/salt.php");
require_once("../includes/functions.php");
require_once("../db/controller/ReviewController.php");

$ReviewController = new ReviewController($conn);

if (isset($_GET['id'])) {
    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $prvId = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $review = $ReviewController->getReviewStatusNotShowingDetail($prvId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($review);
} else {
    header('Location: review_show');
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

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title text-warning">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>รายละเอียดรีวิวสินค้า</span>
                                    </h4>
                                    <div class="mb-3">
                                        <label for="id" class="form-label">รหัสรีวิวสินค้า :</label>
                                        <p><?php echo $review['prv_id']; ?></p>
                                        <input type="hidden" name="prv_id" class="form-control" value="<?php echo $review['prv_id']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="id" class="form-label">ชื่อสินค้า :</label>
                                        <p><?php echo $review['prd_name']; ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="id" class="form-label">รหัสรายการสั่งซื้อ :</label>
                                        <p><?php echo $review['ord_id']; ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="id" class="form-label">ผู้รีวิวสินค้า :</label>
                                        <p><?php echo $review['mem_username']; ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="id" class="form-label">ความพึงพอใจ :</label><br>
                                        <?php
                                        $rating = $review['prv_rating'];

                                        for ($i = 1; $i <= 5; $i++) {
                                            $color = $i <= $rating ? '#f07c29' : ''; // สีตามค่าของ $row['prv_rating']
                                            echo '<i class="fa-solid fa-star" style="color:' . $color . '"></i>';
                                        }
                                        ?>
                                    </div>
                                    <div class="mb-3">
                                        <label for="id" class="form-label">รายละเอียดรีวิว :</label>
                                        <p><?php echo $review['prv_detail']; ?></p>
                                    </div>
                                </div> <!-- end card-->
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-3 header-title text-warning">จัดการข้อมูลล่าสุดเมื่อ : <span class="text-dark"> <?php echo $review['prv_time_update'] ?></span></h4>
                                    <div>
                                        <a href="review_show" class="btn btn-secondary me-2">
                                            <i class="fa-solid fa-xmark me-1"></i>
                                            <span>กลับ</span>
                                        </a>
                                        <button type="submit" class="btn btn-danger me-2 btn-review-delete" data-prv_id="<?php echo $review['prv_id'] ?>">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>ลบรีวิวสินค้านี้</span>
                                        </button>
                                        <button type="submit" class="btn btn-success me-2 btn-review-confirm" data-prv_id="<?php echo $review['prv_id'] ?>">
                                            <i class="fa-solid fa-check"></i>
                                            <span>อนุญาติให้แสดงรีวิวสินค้านี้</span>
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

        <!-- Review Delete -->
        <script>
            $(document).ready(function() {
                $(".btn-review-delete").click(function(e) {
                    e.preventDefault();
                    let prvId = $(this).data('prv_id');
                    reviewDeleteConfirm(prvId);
                });
            });

            function reviewDeleteConfirm(prvId) {
                Swal.fire({
                    icon: "warning",
                    title: 'ลบรีวิวสินค้า?',
                    text: "คุณต้องการลบรีวิวนี้ ใช่ไหม!",
                    showCancelButton: true,
                    confirmButtonColor: '#f34e4e',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก',
                    preConfirm: function() {
                        return $.ajax({
                                url: 'process/review_detail_delete.php',
                                type: 'POST',
                                data: {
                                    prv_id: prvId,
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า review_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'review_show';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'review_show';
                    }
                });
            }
        </script>

        <!-- Review Confirm -->
        <script>
            $(document).ready(function() {
                $(".btn-review-confirm").click(function(e) {
                    e.preventDefault();
                    let prvId = $(this).data('prv_id');
                    reviewConfirm(prvId);
                });
            });

            function reviewConfirm(prvId) {
                Swal.fire({
                    icon: "warning",
                    title: 'อนุญาตให้แสดง?',
                    text: "คุณอนุญาตให้แสดงรีวิวนี้ ใช่ไหม!",
                    showCancelButton: true,
                    confirmButtonColor: '#f34e4e',
                    confirmButtonText: 'ใช่, อนุญาต!',
                    cancelButtonText: 'ยกเลิก',
                    preConfirm: function() {
                        return $.ajax({
                                url: 'process/review_detail_confirm.php',
                                type: 'POST',
                                data: {
                                    prv_id: prvId,
                                },
                            })
                            .done(function() {
                                // การลบสำเร็จ ทำการ redirect ไปยังหน้า review_show
                                return true;
                            })
                            .fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ไม่สำเร็จ',
                                    text: 'เกิดข้อผิดพลาดที่ ajax !',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.location.href = 'review_show';
                                    }
                                });
                            });
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = 'review_show';
                    }
                });
            }
        </script>


</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>