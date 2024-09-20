<?php
$titlePage = "ประวัติตามหาหนังสือตามสั่ง";

require_once("db/connectdb.php");
require_once("db/controller/ProductRequestController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$ProductRequestController = new ProductRequestController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header("Location: login_form");
    exit;
} else {
    $memId = $_SESSION['mem_id'];

    $ProductRequest = $ProductRequestController->getAccountProductRequest($memId);
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
                                                <h5>ประวัติตามหาหนังสือตามสั่ง</h5>
                                                <?php if ($ProductRequest) { ?>
                                                    <table id="myAccountTable" class="table table-bordered table-hover table-responsive">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th class="text-center">วัน เวลา</th>
                                                                <th class="text-center">หัวเรื่อง</th>
                                                                <th class="text-center">สถานะ</th>
                                                                <th class="text-center">จัดการ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($ProductRequest as $row) { ?>
                                                                <tr>
                                                                    <td class="text-start"><?php echo $row['prq_time_create'] ?></td>
                                                                    <td><?php echo $row['prq_title'] ?></td>
                                                                    <td class="text-center">
                                                                        <?php if ($row['prq_status'] == "checking") { ?>
                                                                            <span class="badge rounded-pill text-bg-warning">กำลังตรวจสอบ</span>
                                                                        <?php } elseif ($row['prq_status'] == "result") { ?>
                                                                            <span class="badge rounded-pill text-bg-primary">ผลการค้นหา</span>
                                                                        <?php } elseif ($row['prq_status'] == "success") { ?>
                                                                            <span class="badge rounded-pill text-bg-success">สำเร็จ</span>
                                                                        <?php } elseif ($row['prq_status'] == "cancel") { ?>
                                                                            <span class="badge rounded-pill text-bg-danger">ยกเลิก</span>
                                                                        <?php } else { ?>
                                                                            <span class="badge rounded-pill text-bg-secondary">สถานะไม่ถูกต้อง</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php
                                                                        $originalId = $row['prq_id'];
                                                                        $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                                                        $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                                                        ?>
                                                                        <a href="account_product_request_detail?id=<?php echo $base64Encoded; ?>" class="btn btn-detail">
                                                                            <i class="fa-solid fa-eye"></i>
                                                                            รายละเอียด
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                <?php } else { ?>
                                                    <div class="alert alert-secondary text-center" role="alert">
                                                        <h4 class="alert-heading">ไม่พบประวัติการตามหาหนังสือตามสั่ง</h4>
                                                        <p>เมื่อคุณมีการใช้บริการตามหาหนังสือตามสั่ง คุณสามารถติดตามความคืบหน้าได้ที่นี่</p>
                                                        <hr>
                                                        <a href="product_request_form" class="btn btn-link">ตามหาหนังสือตามสั่ง</a>
                                                    </div>
                                                <?php } ?>


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
            responsive: true,
            "order": [
                [0, 'DESC']
            ]
        });
    </script>
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>