<?php
$titlePage = "โอนเหรียญ";

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

    $memProfile = $MemberController->getDetailAccountMember($memId);

    if (!$memProfile) {
        unset($_SESSION['mem_id']);
        $_SESSION['error'] = "ไม่พบข้อมูลบัญชี";
        header("Location: index");
        exit;
    }
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
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5>โอนเหรียญ</h5>
                                                    <?php if ($memProfile['mem_coin'] > 0) { ?>
                                                        <button href="#" class="btn btn-sqr mb-3" data-bs-toggle="modal" data-bs-target="#addAddrModal">
                                                            <i class="fa-regular fa-square-plus"></i>
                                                            โอนเหรียญ
                                                        </button>
                                                    <?php } ?>


                                                    <!-- Modal -->
                                                    <div class="modal fade" id="addAddrModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                        <form id="formTransfer_coin" action="process/account_transfer_coins_edit.php" Method="post">
                                                            <div class="modal-dialog modal-dialog-scrollable">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                            โอนเหรียญ
                                                                        </h1>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="account-details-form">
                                                                            <div class="row">
                                                                                <input type="text" name="my_id" value="<?php echo $memProfile['mem_id'] ?>" readonly>
                                                                                <input type="number" name="my_coin" value="<?php echo $memProfile['mem_coin'] ?>" readonly>
                                                                                <div class="col-lg-12">
                                                                                    <div class="col-lg-6">
                                                                                        <div class="single-input-item">
                                                                                            <h6><i class="fa-brands fa-gg-circle mt-3 me-1 text-warning"></i>เหรียญที่คุณมี : <?php echo number_format($memProfile['mem_coin']) . " เหรียญ"; ?></h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="recipient_id" class="form-label">รหัสสมาชิกผู้รับเหรียญ</label>
                                                                                        <input type="number" class="form-control" name="recipient_id" placeholder="กรุณาระบุ รหัสสมาชิกผู้รับเหรียญ" maxlength="50">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="coin" class="form-label">จำนวนเหรียญที่จะโอน</label>
                                                                                        <input type="number" class="form-control" name="coin" placeholder="กรุณาระบุ จำนวนเหรียญที่จะโอน" maxlength="50">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="new-pwd" class="form-label">รหัสผ่าน</label>
                                                                                        <div class="input-group">
                                                                                            <input type="password" name="password" class="form-control" placeholder="ระบุ รหัสผ่าน" maxlength="255">
                                                                                            <button class="btn btn-outline-secondary password-toggle" type="button">
                                                                                                <i class="fas fa-eye-slash"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <hr>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" data-bs-dismiss="modal" class="btn">ยกเลิก</button>
                                                                        <button type="submit" name="btn-edit" class="btn btn-sqr"><i class="fa-solid fa-floppy-disk"></i> บันทึก</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <?php if ($memProfile['mem_coin'] > 0) { ?>
                                                        <!-- <div class="account-details-form">
                                                            <form id="formTransfer_coin" action="process/account_transfer_coins_edit.php" Method="post">
                                                                <div class="row">
                                                                    <input type="text" name="my_id" value="<?php echo $memProfile['mem_id'] ?>" readonly>
                                                                    <input type="number" name="my_coin" value="<?php echo $memProfile['mem_coin'] ?>" readonly>
                                                                    <div class="col-lg-12">
                                                                        <div class="col-lg-6">
                                                                            <div class="single-input-item">
                                                                                <h6><i class="fa-brands fa-gg-circle mt-3 me-1 text-warning"></i>เหรียญที่คุณมี : <?php echo number_format($memProfile['mem_coin']) . " เหรียญ"; ?></h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="col-lg-6">
                                                                            <div class="single-input-item mb-3">
                                                                                <label for="recipient_id" class="form-label">รหัสสมาชิกผู้รับเหรียญ</label>
                                                                                <input type="number" class="form-control" name="recipient_id" placeholder="กรุณาระบุ รหัสสมาชิกผู้รับเหรียญ" maxlength="50">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="col-lg-6">
                                                                            <div class="single-input-item mb-3">
                                                                                <label for="coin" class="form-label">จำนวนเหรียญที่จะโอน</label>
                                                                                <input type="number" class="form-control" name="coin" placeholder="กรุณาระบุ จำนวนเหรียญที่จะโอน" maxlength="50">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="col-lg-6">
                                                                            <div class="single-input-item mb-3">
                                                                                <label for="new-pwd" class="form-label">รหัสผ่าน</label>
                                                                                <div class="input-group">
                                                                                    <input type="password" name="password" class="form-control" placeholder="ระบุ รหัสผ่าน" maxlength="255">
                                                                                    <button class="btn btn-outline-secondary password-toggle" type="button">
                                                                                        <i class="fas fa-eye-slash"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="single-input-item mb-3">
                                                                        <button type="submit" name="btn-edit" class="btn btn-sqr">
                                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                                            บันทึกการแก้ไข
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div> -->
                                                    <?php } else { ?>
                                                        <div class="alert alert-secondary text-center" role="alert">
                                                            <h4 class="alert-heading">ไม่มีเหรียญ</h4>
                                                            <p>คุณไม่มีเหรียญที่สามารถโอนได้ กรุณาซื้อสินค้าเพื่อรับเหรียญ</p>
                                                            <hr>
                                                            <a href="products_show">สินค้าทั้งหมด</a>
                                                        </div>
                                                    <?php } ?>

                                                </div>
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
                                                        <tr>
                                                            <td>10</td>
                                                            <td>10</td>
                                                            <td>10</td>
                                                            <td>10</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
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
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>