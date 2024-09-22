<?php
$titlePage = "รายงานยอดเข้าชม";

require_once("../db/connectdb.php");
require_once('../db/controller/LoginController.php');
require_once("../db/controller/ReportViewController.php");

$LoginController = new LoginController($conn);
$ReportViewController = new ReportViewController($conn);

$empId = $_SESSION['emp_id'];

// ตรวจสอบสิทธิ์การใช้งาน
$useAuthority = $LoginController->useLoginEmployees($empId);
$allowedAuthorities = [1, 2, 3, 4]; // [Super Admin, Owner, Admin, Accounting]
checkAuthorityEmployees($useAuthority, $allowedAuthorities);


if (isset($_GET['time_start']) || isset($_GET['time_end']) || isset($_GET['prd_name']) || isset($_GET['pty_name'])) {
    $time_start = isset($_GET["time_start"]) ? $_GET["time_start"] : null;
    $time_end = isset($_GET["time_end"]) ? $_GET["time_end"] : null;
    $prd_name = isset($_GET["prd_name"]) ? $_GET["prd_name"] : null;
    $pty_name = isset($_GET["pty_name"]) ? $_GET["pty_name"] : null;

    if (empty($_GET['time_start']) && empty($_GET['time_end']) && empty($_GET['prd_name']) && empty($_GET['pty_name'])) {
        header("Location: report_product_views");
        exit;
    } else {
        $reportViews = $ReportViewController->getSearchReportProductView($time_start, $time_end, $prd_name, $pty_name);
    }
} else {
    $reportViews = $ReportViewController->getReportProductView();
}

// ประเภทสินค้าที่มี
$productType = $ReportViewController->getProductTypeName();

// 
$chartReportProductViews = $ReportViewController->getChartReportProductView();
$chartReportProductTypeViews = $ReportViewController->getChartReportProductTypeView();

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
                                    <h4 class="mt-0 header-title">ข้อมูลยอดเข้าชมสินค้า</h4>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                            ค้นหาขั้นสูง
                                        </button>
                                        <a href="report_product_views" class="btn btn-danger">
                                            <i class="fa-solid fa-rotate-left"></i>
                                            เริ่มใหม่
                                        </a>
                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form id="formSearchView" action="report_product_views" method="get">
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
                                                                <label for="">ชื่อสินค้า :</label>
                                                                <input type="text" class="form-control" name="prd_name" placeholder="ระบุชื่อสินค้า" value="<?php if (!empty($_GET['prd_name'])) {
                                                                                                                                                                echo $_GET['prd_name'];
                                                                                                                                                            }  ?>">
                                                            </div>
                                                            <div class="col mb-3">
                                                                <label for="">ประเภทสินค้า :</label>
                                                                <!-- <input type="text" class="form-control" name="pty_name" placeholder="ระบุประเภทสินค้า" value="<?php if (!empty($_GET['pty_name'])) {
                                                                                                                                                                        echo $_GET['pty_name'];
                                                                                                                                                                    }  ?>"> -->
                                                                <select class="form-select" name="pty_name" aria-label="Default select example">
                                                                    <option value="">ไม่ระบุประเภทสินค้า</option>
                                                                    <?php foreach ($productType  as $row) { ?>
                                                                        <option value="<?php echo $row['pty_name'] ?>" <?php if( !empty($_GET['pty_name']) && $_GET['pty_name'] == $row['pty_name']){ echo 'selected'; } ?> ><?php echo $row['pty_name'] ?></option>
                                                                    <?php } ?>
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
                                    <?php if (isset($_GET['time_start']) || isset($_GET['time_end']) || isset($_GET['prd_name']) || isset($_GET['pty_name'])) { ?>
                                        <div class="mt-1">
                                            <?php if (!empty($_GET['time_start']) || !empty($_GET['time_end']) || !empty($_GET['prd_name']) || !empty($_GET['pty_name'])) { ?>
                                                <div class="mt-2">
                                                    <P>ผลการค้นหา : </P>
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
                                                <?php if (!empty($_GET['prd_name'])) { ?>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="">ชื่อสินค้า :</label>
                                                        <p><?php echo $_GET['prd_name']; ?></p>
                                                    </div>
                                                <?php } ?>
                                                <?php if (!empty($_GET['pty_name'])) { ?>
                                                    <div class="col-md-3 mb-3">
                                                        <label for="">ประเภทสินค้า :</label>
                                                        <p><?php echo $_GET['pty_name']; ?></p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <hr>

                                    <?php if ($reportViews) { ?>
                                        <table id="MyTableExport" class="table table-bordered table-hover dt-responsive table-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">วัน เวลา</th>
                                                    <th class="text-center">ชื่อสินค้า</th>
                                                    <th class="text-center">ประเภทสินค้า</th>
                                                    <th class="text-center">ยอดเข้าชม (ครั้ง)</th>
                                                </tr>
                                            </thead>

                                            <tbody class="">
                                                <?php foreach ($reportViews as $row) { ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $row['prv_time']; ?></td>
                                                        <td class="text-center"><?php echo $row['prd_name']; ?></td>
                                                        <td class="text-center"><?php echo $row['pty_name']; ?></td>
                                                        <td class="text-center"><?php echo $row['prv_view']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <?php
                                                    $totalViews = 0; // ตัวแปรสำหรับเก็บผลรวมของ prv_view
                                                    foreach ($reportViews as $row) {
                                                        $totalViews += $row['prv_view'];
                                                        // เพิ่มค่า prv_view ของแต่ละแถวในผลรวม
                                                    }
                                                    ?>
                                                    <td colspan="3" class="text-end"><strong>ยอดเช้าชมรวม : </strong></td>
                                                    <td class="text-center text-primary"><?php echo number_format($totalViews); ?></td>
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
                                    <h4 class="mt-0 header-title">แผนภูมิแสดง ยอดเข้าชมสินค้า 20 อันดับสูงสุด</h4>
                                    <?php if ($chartReportProductViews) { ?>
                                        <div>
                                            <canvas id="chartProductViews"></canvas>
                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-secondary text-center" role="alert">
                                            <h2 class="alert-heading">ไม่มียอดเข้าชม!</h2>
                                            <i class="fa-solid fa-face-frown fa-6x my-2 text-warning"></i>
                                            <hr>
                                            <p class="mb-0">เมื่อมีการเข้าชมสินค้าจะแสดงที่นี่ !!!</p>
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
                                    <h4 class="mt-0 header-title">แผนภูมิแสดง ยอดเข้าชมประเภทสินค้า 20 อันดับสูงสุด</h4>
                                    <?php if ($chartReportProductTypeViews) { ?>
                                        <div class="d-flex justify-content-center align-items-center" style="width: 100%; height: 500px;">
                                            <canvas id="chartProductTypeViews"></canvas>
                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-secondary text-center" role="alert">
                                            <h2 class="alert-heading">ไม่มียอดเข้าชม!</h2>
                                            <i class="fa-solid fa-face-frown fa-6x my-2 text-warning"></i>
                                            <hr>
                                            <p class="mb-0">เมื่อมีการเข้าชมประเภทสินค้าจะแสดงที่นี่ !!!</p>
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
        const ctx = document.getElementById('chartProductViews');
        // สร้าง arrays เพื่อเก็บข้อมูลสำหรับกราฟ
        const productLabels = []; // เก็บชื่อสินค้า
        const productViews = []; // เก็บจำนวนการเข้าชม

        // วน loop ผ่านข้อมูลที่ได้มาจาก PHP
        <?php foreach ($chartReportProductViews as $index => $row) : ?>
            productLabels.push("<?php echo $row['prd_name']; ?>"); // เพิ่มชื่อสินค้าลงใน array
            productViews.push(<?php echo $row['total_views']; ?>); // เพิ่มจำนวนการเข้าชมลงใน array
        <?php endforeach; ?>


        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'ยอดเข้าชม (ครั้ง)',
                    data: productViews,
                    borderWidth: 1
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



        const ctx2 = document.getElementById('chartProductTypeViews');
        // สร้าง arrays เพื่อเก็บข้อมูลสำหรับกราฟ
        const productTypeLabels = []; // เก็บชื่อสินค้า
        const productTypeViews = []; // เก็บจำนวนการเข้าชม

        // วน loop ผ่านข้อมูลที่ได้มาจาก PHP
        <?php foreach ($chartReportProductTypeViews as $index => $row) : ?>
            productTypeLabels.push("<?php echo $row['pty_name']; ?>"); // เพิ่มชื่อสินค้าลงใน array
            productTypeViews.push(<?php echo $row['total_views']; ?>); // เพิ่มจำนวนการเข้าชมลงใน array
        <?php endforeach; ?>


        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: productTypeLabels,
                datasets: [{
                    label: 'ยอดเข้าชม (ครั้ง)',
                    data: productTypeViews,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 205, 86)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    hoverOffset: 4
                }]
            },
        });
    </script>
    </script>
</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>