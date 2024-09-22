<?php
$titlePage = "รายงานยอดขายสินค้า";

require_once("../db/connectdb.php");
require_once('../db/controller/LoginController.php');
require_once("../db/controller/ReportSaleController.php");


$LoginController = new LoginController($conn);
$ReportSaleController = new ReportSaleController($conn);

$empId = $_SESSION['emp_id'];

// ตรวจสอบสิทธิ์การใช้งาน
$useAuthority = $LoginController->useLoginEmployees($empId);
$allowedAuthorities = [1, 2, 3, 4]; // [Super Admin, Owner, Admin, Accounting]
checkAuthorityEmployees($useAuthority, $allowedAuthorities);



if (isset($_GET['time_start']) || isset($_GET['time_end']) || isset($_GET['price_start']) || isset($_GET['price_end']) || isset($_GET['mem_username']) || isset($_GET['ord_status'])) {
    $time_start = isset($_GET["time_start"]) ? $_GET["time_start"] : null;
    $time_end = isset($_GET["time_end"]) ? $_GET["time_end"] : null;
    $price_start = isset($_GET["price_start"]) && $_GET["price_start"] !== '' ? $_GET["price_start"] : null;
    $price_end = isset($_GET["price_end"]) && $_GET["price_end"] !== '' ? $_GET["price_end"] : null;
    $mem_username = isset($_GET["mem_username"]) ? $_GET["mem_username"] : null;
    $ord_status = isset($_GET["ord_status"]) ? $_GET["ord_status"] : null;

    if (is_null($time_start) && is_null($time_end) && is_null($price_start) && is_null($price_end) && empty($mem_username) && empty($ord_status)) {
        header("Location: report_product_sales");
        exit;
    } else {
        $reportSales = $ReportSaleController->getSearchReportProductSale($time_start, $time_end, $price_start, $price_end, $mem_username, $ord_status);
    }
} else {
    $reportSales = $ReportSaleController->getReportProductSale();
}

//
$chartReportProductSales12Month = $ReportSaleController->getReportSales12Months();
$chartReportProductPopular = $ReportSaleController->getChartReportProductPopular();

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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mt-0 header-title">ข้อมูลยอดขายสินค้า</h4>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                            ค้นหาขั้นสูง
                                        </button>
                                        <a href="report_product_sales" class="btn btn-danger">
                                            <i class="fa-solid fa-rotate-left"></i>
                                            เริ่มใหม่
                                        </a>
                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form id="formSearchSale" novalidate action="report_product_sales" method="get">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">ตั้งค่าการค้นหาขั้นสูง</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row align-items-start">
                                                            <div class="col mb-3">
                                                                <label for="">วันที่เริ่ม :</label>
                                                                <input type="datetime-local" name="time_start" id="time_start" class="form-control" value="<?php if (!empty($_GET['time_start'])) {
                                                                                                                                                                echo $_GET['time_start'];
                                                                                                                                                            }  ?>">
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="">วันที่สิ้นสุด :</label>
                                                                <input type="datetime-local" name="time_end" class="form-control" value="<?php if (!empty($_GET['time_end'])) {
                                                                                                                                                echo $_GET['time_end'];
                                                                                                                                            }  ?>">
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-start">
                                                            <div class="col mb-3">
                                                                <label for="">ราคาต่ำสุด :</label>
                                                                <input type="number" name="price_start" id="price_start" class="form-control" placeholder="ราคาต่ำสุด" value="<?php echo (isset($_GET['price_start']) && $_GET['price_start'] !== '') ? $_GET['price_start'] : ''; ?>">

                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="">ราคาสูงสุด :</label>
                                                                <input type="number" name="price_end" class="form-control" placeholder="ราคาสูงสุด" value="<?php echo (isset($_GET['price_end']) && $_GET['price_end'] !== '') ? $_GET['price_end'] : ''; ?>" value="<?php echo (isset($_GET['price_end']) && $_GET['price_end'] !== '') ? $_GET['price_end'] : ''; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="row align-items-start">
                                                            <div class="col mb-3">
                                                                <label for="">ชื่อผู้ใช้ :</label>
                                                                <input type="text" class="form-control" name="mem_username" placeholder="ระบุชื่อผู้ใช้" value="<?php if (!empty($_GET['mem_username'])) {
                                                                                                                                                                    echo $_GET['mem_username'];
                                                                                                                                                                }  ?>" maxlength="50">
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="">สถานะรายการสั่งซื้อ :</label>
                                                                <select class="form-select" name="ord_status" aria-label="Default select example">
                                                                    <option value="" <?php if (!empty($_GET['ord_status']) && $_GET['ord_status'] == '') echo 'selected'; ?>>ไม่ระบุสถานะ</option>
                                                                    <option value="Pending Payment" <?php if (!empty($_GET['ord_status']) && $_GET['ord_status'] == 'Pending Payment') echo 'selected'; ?>>รอชำระเงิน</option>
                                                                    <option value="Under Review" <?php if (!empty($_GET['ord_status']) && $_GET['ord_status'] == 'Under Review') echo 'selected'; ?>>รอตรวจสอบ</option>
                                                                    <option value="Payment Retry" <?php if (!empty($_GET['ord_status']) && $_GET['ord_status'] == 'Payment Retry') echo 'selected'; ?>>ชำระเงินใหม่</option>
                                                                    <option value="Awaiting Shipment" <?php if (!empty($_GET['ord_status']) && $_GET['ord_status'] == 'Awaiting Shipment') echo 'selected'; ?>>รอจัดส่ง</option>
                                                                    <option value="Shipped" <?php if (!empty($_GET['ord_status']) && $_GET['ord_status'] == 'Shipped') echo 'selected'; ?>>จัดส่งแล้ว</option>
                                                                    <option value="Completed" <?php if (!empty($_GET['ord_status']) && $_GET['ord_status'] == 'Completed') echo 'selected'; ?>>สำเร็จ</option>
                                                                    <option value="Cancelled" <?php if (!empty($_GET['ord_status']) && $_GET['ord_status'] == 'Cancelled') echo 'selected'; ?>>ยกเลิก</option>
                                                                </select>

                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                                        <button type="submit" class="btn btn-primary me-1">
                                                            <i class="fa-solid fa-magnifying-glass"> ค้นหา</i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ผลการค้นหา  -->
                                    <?php if (isset($_GET['time_start']) || isset($_GET['time_end']) || isset($_GET['price_start']) || isset($_GET['price_end']) || isset($_GET['mem_username']) || isset($_GET['ord_status'])) { ?>
                                        <div class="mt-1">
                                            <?php if (!empty($_GET['time_start']) ||  !empty($_GET['time_end']) || (isset($_GET['price_start']) && $_GET['price_start'] !== '') || (isset($_GET['price_end']) && $_GET['price_end'] !== '') || !empty($_GET['mem_username']) ||  !empty($_GET['ord_status'])) { ?>
                                                <div class="mt-2">
                                                    <p>ผลการค้นหา : </p>
                                                </div>
                                            <?php } ?>


                                            <div class="row">

                                                <?php if (!empty($_GET['time_start'])) { ?>
                                                    <?php
                                                    $dateTime = new DateTime($_GET['time_start']);
                                                    $formattedDateStart = $dateTime->format('Y-m-d H:i'); // รูปแบบใหม่ที่ไม่มีตัว "T"
                                                    ?>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="">วันที่เริ่ม :</label>
                                                        <p><?php echo $formattedDateStart; ?></p>
                                                    </div>
                                                <?php } ?>

                                                <?php if (!empty($_GET['time_end'])) { ?>
                                                    <?php
                                                    $dateTime = new DateTime($_GET['time_end']);
                                                    $formattedDateEnd = $dateTime->format('Y-m-d H:i'); // รูปแบบใหม่ที่ไม่มีตัว "T"
                                                    ?>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="">วันที่สิ้นสุด :</label>
                                                        <p><?php echo $formattedDateEnd; ?></p>
                                                    </div>
                                                <?php } ?>

                                                <?php if (isset($_GET['price_start']) && $_GET['price_start'] !== '') { ?>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="">ราคาต่ำสุด :</label>
                                                        <p><?php echo "฿" . number_format($_GET['price_start'], 2); ?></p>
                                                    </div>
                                                <?php } ?>


                                                <?php if (isset($_GET['price_end']) && $_GET['price_end'] !== '') { ?>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="">ราคาสูงสุด :</label>
                                                        <p><?php echo "฿" . number_format($_GET['price_end'], 2); ?></p>
                                                    </div>
                                                <?php } ?>

                                                <?php if (!empty($_GET['mem_username'])) { ?>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="">ชื่อผู้ใช้ :</label>
                                                        <p><?php echo $_GET['mem_username']; ?></p>
                                                    </div>
                                                <?php } ?>
                                                <?php if (!empty($_GET['ord_status'])) { ?>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="">สถานะรายการสั่งซื้อ :</label>
                                                        <p>
                                                            <?php
                                                            if ($_GET['ord_status'] == 'Pending Payment') {
                                                                echo 'รอชำระเงิน';
                                                            } elseif ($_GET['ord_status'] == 'Under Review') {
                                                                echo 'รอตรวจสอบ';
                                                            } elseif ($_GET['ord_status'] == 'Payment Retry') {
                                                                echo 'ชำระเงินใหม่';
                                                            } elseif ($_GET['ord_status'] == 'Awaiting Shipment') {
                                                                echo 'รอจัดส่ง';
                                                            } elseif ($_GET['ord_status'] == 'Shipped') {
                                                                echo 'จัดส่งแล้ว';
                                                            } elseif ($_GET['ord_status'] == 'Completed') {
                                                                echo 'สำเร็จ';
                                                            } elseif ($_GET['ord_status'] == 'Cancelled') {
                                                                echo 'ยกเลิก';
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <hr>

                                    <?php if ($reportSales) { ?>
                                        <table id="MyTableExport" class="table table-bordered table-hover dt-responsive table-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">วัน เวลา</th>
                                                    <th class="text-center">ชื่อผู้ใช้</th>
                                                    <th class="text-center">สถานะ</th>
                                                    <th class="text-center">ราคารวม</th>
                                                </tr>
                                            </thead>

                                            <tbody class="">

                                                <?php foreach ($reportSales as $row) { ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $row['ord_time_create']; ?></td>
                                                        <td class="text-center"><?php echo $row['mem_username']; ?></td>
                                                        <td class="text-center">
                                                            <?php
                                                            if ($row['ord_status'] == "Pending Payment") {
                                                                echo '<span class="badge text-bg-warning">รอชำระเงิน</span>';
                                                            } elseif ($row['ord_status'] == "Under Review") {
                                                                echo '<span class="badge text-bg-secondary">รอตรวจสอบ</span>';
                                                            } elseif ($row['ord_status'] == "Payment Retry") {
                                                                echo '<span class="badge text-bg-warning">ชำระเงินใหม่</span>';
                                                            } elseif ($row['ord_status'] == "Awaiting Shipment") {
                                                                echo '<span class="badge text-bg-info">รอจัดส่ง</span>';
                                                            } elseif ($row['ord_status'] == "Shipped") {
                                                                echo '<span class="badge text-bg-primary">จัดส่งแล้ว</span>';
                                                            } elseif ($row['ord_status'] == "Completed") {
                                                                echo '<span class="badge text-bg-success">สำเร็จ</span>';
                                                            } elseif ($row['ord_status'] == "Cancelled") {
                                                                echo '<span class="badge text-bg-danger">ยกเลิกแล้ว</span>';
                                                            } else {
                                                                echo '<span class="badge text-bg-secondary">สถานะไม่ถูกต้อง</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="text-center"><?php echo "฿" . number_format($row['ord_price'], 2); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <?php
                                                    $totalPrice = 0; // ตัวแปรสำหรับเก็บผลรวมของ ord_price
                                                    foreach ($reportSales as $row) {
                                                        $totalPrice += $row['ord_price'];
                                                        // เพิ่มค่า prv_view ของแต่ละแถวในผลรวม
                                                    }
                                                    ?>
                                                    <td colspan="3" class="text-end"><strong>ยอดขายรวม : </strong></td>
                                                    <td class="text-center text-primary"><?php echo "฿" . number_format($totalPrice, 2); ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    <?php } else { ?>
                                        <?php require_once("./includes/no_information.php") ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->


                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mt-0 header-title">แผนภูมิแสดง ยอดขายรวมต่อเดือน</h4>
                                    <?php if ($chartReportProductSales12Month) { ?>
                                        <div>
                                            <canvas id="chartProductSale12Months"></canvas>
                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-secondary text-center" role="alert">
                                            <h2 class="alert-heading">ไม่มีข้อมูลรายได้!</h2>
                                            <i class="fa-solid fa-face-frown fa-6x my-2 text-warning"></i>
                                            <hr>
                                            <p class="mb-0">เมื่อมีการซื้อสินค้าจะแสดงรายได้ที่นี่ !!!</p>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- end row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mt-0 header-title">แผนภูมิแสดง สินค้าที่ขายดี 20 อันดับสูงสุด</h4>
                                    <?php if ($chartReportProductPopular) { ?>
                                        <div class="d-flex justify-content-center align-items-center" style="width: 100%; height: 500px;">
                                            <canvas id="chartReportProductPopular"></canvas>
                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-secondary text-center" role="alert">
                                            <h2 class="alert-heading">ไม่มียอดเข้าชม!</h2>
                                            <i class="fa-solid fa-face-frown fa-6x my-2 text-warning"></i>
                                            <hr>
                                            <p class="mb-0">เมื่อมีการเข้าชมสถานะรายการสั่งซื้อจะแสดงที่นี่ !!!</p>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

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
        const ctx = document.getElementById('chartProductSale12Months').getContext('2d');
        const monthLabels = []; // Store month names
        const totalRevenue = []; // Store revenue data

        <?php foreach ($chartReportProductSales12Month as $index => $row) : ?>
            monthLabels.push("<?php echo addslashes($row['month_name']); ?>"); // Add month names to array
            totalRevenue.push(<?php echo $row['total_revenue']; ?>); // Add revenue data to array
        <?php endforeach; ?>

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'ยอดขาย (บาท)', // Adjust the label if needed
                    data: totalRevenue,
                    borderWidth: 1,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        const ctx2 = document.getElementById('chartReportProductPopular').getContext('2d');
        const productNameLabels = []; // Store month names
        const totalQuantity = [];

        <?php foreach ($chartReportProductPopular as $index => $row) { ?>
            productNameLabels.push("<?php echo $row['prd_name']; ?>"); // Add month names to array
            totalQuantity.push(<?php echo $row['total_quantity']; ?>); // Add revenue data to array
        <?php } ?>

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: productNameLabels,
                datasets: [{
                    label: 'จำนวน (ชิ้น)', // Adjust the label if needed
                    data: totalQuantity,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 1,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>