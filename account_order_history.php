<?php
$titlePage = "ประวัติการสั่งซื้อ";

require_once("db/connectdb.php");
require_once("db/controller/MemberController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$MemberController = new MemberController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header("Location: login_form");
    exit;
} else {
    $memId = $_SESSION['mem_id'];
}


?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
    <link href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/searchpanes/2.3.1/css/searchPanes.bootstrap5.min.css" rel="stylesheet">



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
                                            <div class="myaccount-content">
                                                <h5>ประวัติการสั่งซื้อ</h5>
                                                <table id="myAccountTable" class="table table-bordered table-hover table-responsive">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th class="text-center">วัน เวลา</th>
                                                            <th class="text-center">ยอดสั่งซื้อรวม</th>
                                                            <th class="text-center">สถานะ</th>
                                                            <th class="text-center">หมายเลขติดตาม</th>
                                                            <th class="text-center">จัดการ</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>10</td>
                                                            <td>10</td>
                                                            <td>10</td>
                                                            <td>10</td>
                                                            <td>10</td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <!-- <div class="alert alert-secondary text-center" role="alert">
                                                        <h4 class="alert-heading">ไม่พบประวัติการสั่งซื้อ</h4>
                                                        <p>สามารถสั่งซื้อสินค้าได้ที่หน้าสินค้าทั้งหมด</p>
                                                        <hr>
                                                        <a href="products_show" class="btn btn-link">สินค้าทั้งหมด</a>
                                                    </div> -->
                                            </div>
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

    <script>
        new DataTable('#myAccountTable', {
            responsive: true
        });
    </script>

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
                            url: 'process/account_wishlist_del.php',
                            type: 'POST',
                            data: {
                                id: id,
                            },
                        })
                        .done(function() {
                            // การลบสำเร็จ ทำการ redirect ไปยังหน้า account_wishlist
                            return true;
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สำเร็จ',
                                text: 'เกิดข้อผิดพลาดที่ ajax !',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.location.href = 'account_wishlist';
                                }
                            });
                        });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = 'account_wishlist';
                }
            });
        }
    </script>
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>