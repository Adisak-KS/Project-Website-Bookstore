<?php
$titlePage = "โอนเหรียญ";

require_once("db/connectdb.php");
require_once("db/controller/CoinHistoryController.php");
require_once("db/controller/MemberController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$MemberController = new MemberController($conn);
$CoinHistoryController = new CoinHistoryController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header("Location: login_form");
    exit;
} else {
    $memId = $_SESSION['mem_id'];

    $memProfile = $MemberController->getDetailAccountMember($memId);

    if ($memProfile) {
        $coinHistory = $CoinHistoryController->getMemberCoinHistory($memId);
    } else {
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
                                                            <i class="fa-solid fa-right-left"></i>
                                                            โอนเหรียญ
                                                        </button>
                                                    <?php } else { ?>
                                                        <button href="#" class="btn btn-sqr mb-3" disabled>
                                                            <i class="fa-solid fa-right-left"></i>
                                                            คุณไม่มีเหรียญที่โอนได้
                                                        </button>
                                                    <?php } ?>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="addAddrModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                        <form id="formTransferCoin" action="process/account_transfer_coins_edit.php" Method="post">
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
                                                                                <input type="hidden" id="mhc_from_mem_id" name="mhc_from_mem_id" value="<?php echo $memProfile['mem_id'] ?>" readonly>
                                                                                <input type="hidden" id="my_coin" name="my_coin" value="<?php echo $memProfile['mem_coin'] ?>" readonly>
                                                                                
                                                                                <h6><i class="fa-brands fa-gg-circle mt-3 me-1 text-warning"></i>เหรียญที่คุณมี : <?php echo number_format($memProfile['mem_coin']) . " เหรียญ"; ?></h6>
                                                                                
                                                                                <div class="col-lg-12">
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="mhc_to_mem_id" class="form-label">รหัสสมาชิกผู้รับเหรียญ<span class="text-danger">*</span></label>
                                                                                        <input type="number" class="form-control" name="mhc_to_mem_id" placeholder="กรุณาระบุ รหัสสมาชิกผู้รับเหรียญ" maxlength="50">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="mhc_coin_amount" class="form-label">จำนวนเหรียญที่จะโอน<span class="text-danger">*</span></label>
                                                                                        <input type="number" class="form-control" name="mhc_coin_amount" placeholder="กรุณาระบุ จำนวนเหรียญที่จะโอน" min="1">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-12">
                                                                                    <div class="single-input-item mb-3">
                                                                                        <label for="new-pwd" class="form-label">รหัสผ่าน<span class="text-danger">*</span></label>
                                                                                        <div class="input-group">
                                                                                            <input type="password" name="password" class="form-control" placeholder="ระบุ รหัสผ่าน" maxlength="255">
                                                                                            <button class="btn btn-outline-secondary password-toggle" type="button">
                                                                                                <i class="fas fa-eye-slash"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" data-bs-dismiss="modal" class="btn">ยกเลิก</button>
                                                                        <button type="submit" name="btn-edit" class="btn btn-sqr"><i class="fa-solid fa-right-left"></i> ยืนยันการโอนเหรียญ</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <p class="mb-3"><strong><i class="fa-brands fa-gg-circle mt-3 me-1 text-warning"></i>เหรียญที่คุณมี : <?php echo number_format($memProfile['mem_coin']) . " เหรียญ"; ?></strong></p>

                                                <?php if ($coinHistory) { ?>
                                                    <table id="myAccountTable" class="table table-bordered table-hover table-responsive">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th class="text-center">วัน เวลา</th>
                                                                <th class="text-center">รายละเอียด</th>
                                                                <th class="text-center">จำนวนเหรียญ</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($coinHistory as $row) { ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $row['mhc_time'] ?></td>
                                                                    <td>
                                                                        <?php
                                                                        if ($row['mhc_transaction_type'] == 'transfer') {
                                                                            // กรณีเป็นการโอนเหรียญ
                                                                            if ($row['mhc_from_mem_id'] == $_SESSION['mem_id']) {
                                                                                echo 'โอนเหรียญให้กับสมาชิก : ' . $row['to_mem_username'] . " (ID : " . $row['mhc_to_mem_id'] . ")";
                                                                            } else {
                                                                                echo 'ได้รับเหรียญจากสมาชิก : ' . $row['from_mem_username'] . " (ID : " . $row['mhc_from_mem_id'] . ")";
                                                                            }
                                                                        } elseif ($row['mhc_transaction_type'] == 'purchase') {
                                                                            // กรณีเป็นการซื้อสินค้า
                                                                            echo 'ได้รับเหรียญจากการซื้อสินค้า';
                                                                            if (!empty($row['ord_id'])) {
                                                                                echo " รายการสั่งซื้อที่: " . $row['ord_id'];
                                                                            }
                                                                        } elseif ($row['mhc_transaction_type'] == 'discount') {
                                                                            // กรณีใช้เหรียญเป็นส่วนลด
                                                                            echo 'ใช้เหรียญเป็นส่วนลด';
                                                                            if (!empty($row['ord_id'])) {
                                                                                echo " รายการสั่งซื้อที่ : " . $row['ord_id'];
                                                                            }
                                                                        } elseif ($row['mhc_transaction_type'] == 'refund') {
                                                                            // กรณีคืนเหรียญจากการยกเลิก
                                                                            echo 'ได้รับเหรียญจากการยกเลิก';
                                                                            if (!empty($row['ord_id'])) {
                                                                                echo " รายการสั่งซื้อที่ : " . $row['ord_id'];
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php
                                                                        if ($row['mhc_transaction_type'] == 'transfer') {
                                                                            if ($row['mhc_from_mem_id'] == $_SESSION['mem_id']) {
                                                                                echo "<span class='text-danger'>-" . number_format($row['mhc_coin_amount']) . "</span>";
                                                                            } else {
                                                                                echo "<span class='text-success'>+" . number_format($row['mhc_coin_amount']) . "</span>";
                                                                            }
                                                                        } elseif ($row['mhc_transaction_type'] == 'discount') {
                                                                            echo "<span class='text-danger'>-" . number_format($row['mhc_coin_amount']) . "</span>";
                                                                        } elseif ($row['mhc_transaction_type'] == 'purchase' || $row['mhc_transaction_type'] == 'refund') {
                                                                            echo "<span class='text-success'>+" . number_format($row['mhc_coin_amount']) . "</span>";
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                <?php } else { ?>
                                                    <div class="alert alert-secondary text-center" role="alert">
                                                        <h4 class="alert-heading">ไม่มีประวัติการใช้เหรียญ</h4>
                                                        <p>ประวัติการใช้เหรียญจะแสดงเมื่อคุณมีการได้รับหรือใช้เหรียญที่นี่</p>
                                                        <hr>
                                                        <a href="products_show">สินค้าทั้งหมด</a>
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
            order: [
                [0, 'DESC']
            ]
        });
    </script>
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>