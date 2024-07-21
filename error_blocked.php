<?php
$titlePage = "ระงับการใช้งาน";
require_once('db/connectdb.php');

?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="page">
    <!-- header-area-start -->
    <?php //require_once("layouts/nav_topbar.php"); 
    ?>
    <!-- header-area-end -->

    <!-- section-element-area-start -->
    <div class="section-element-area ptb-70">
        <div class="container d-flex align-items-center justify-content-center mt-5 vh-80">
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="entry-header text-center mb-20">
                        <p class="mt-2"><?php echo $websiteName ?></p>
                        <h1 class="text-error text-danger">BLOCKED</h1>
                        <i class="fa-solid fa-face-tired text-warning" style="font-size:150px"></i>
                        <p class="mt-2">บัญชีผู้ใช้ถูกระงับการใช้งาน</p>
                    </div>
                    <div class="entry-content text-center mb-30">
                        <p>โปรดติดต่อผู้ดูแลระบบ หากต้องการใช้งาน</p>
                        <a href="logout">ออกจากระบบ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- section-element-area-end -->

    <!-- footer-area-start -->
    <?php //require_once('layouts/nav_footer.php') 
    ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>
</body>

</html>