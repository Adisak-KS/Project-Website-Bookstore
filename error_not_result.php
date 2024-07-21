<?php
$titlePage = "ไม่พบข้อมูล";
require_once('db/connectdb.php');

?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="page">
    <!-- header-area-start -->
    <?php require_once("layouts/nav_topbar.php"); ?>
    <!-- header-area-end -->

    <!-- section-element-area-start -->
    <div class="section-element-area ptb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header text-center mb-20">
                        <i class="fa-solid fa-face-tired text-warning" style="font-size:150px"></i>
                        <p class="mt-2">ไม่พบข้อมูล</p>
                    </div>
                    <div class="entry-content text-center mb-30">
                        <p>ดูเหมือนหน้าที่คุณต้องการไม่มีแล้ว เข้าหน้าหลักเพื่อค้นหาสิ่งที่ต้องการเลย!</p>
                        <a href="index">กลับหน้าหลัก</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- section-element-area-end -->

    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>
</body>

</html>