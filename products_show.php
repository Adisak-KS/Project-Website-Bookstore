<?php
$titlePage = "สินค้าทั้งหมด";

require_once("db/connectdb.php");
require_once('includes/salt.php');
require_once('includes/functions.php');
require_once("db/controller/ProductController.php");

$ProductController = new ProductController($conn);
$prdPreorder = 1; //สินค้าปกติ

if (isset($_GET['ptyId'])) {
    $base64Encoded = $_GET['ptyId'];
    $ptyId = decodeBase64ID($base64Encoded, $salt1, $salt2);
    $allProducts = $ProductController->getProductsAllFocusType($ptyId, $prdPreorder);
} elseif (isset($_GET['pubId'])) {
    $base64Encoded = $_GET['pubId'];
    $pubId = decodeBase64ID($base64Encoded, $salt1, $salt2);
    $allProducts = $ProductController->getProductsAllFocusPublisher($pubId, $prdPreorder,);
} elseif (isset($_GET['authId'])) {
    $base64Encoded = $_GET['authId'];
    $authId = decodeBase64ID($base64Encoded, $salt1, $salt2);
    $allProducts = $ProductController->getProductsAllFocusAuthor($authId, $prdPreorder);
} else {
    $allProducts = $ProductController->getProductsAll($prdPreorder);
}

?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="shop">
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
                            <li><a href="javascript:void(0)" class="active">สินค้าทั้งหมด</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs-area-end -->
    <!-- shop-main-area-start -->
    <div class="shop-main-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-12 col-12 order-lg-1 order-2 mt-sm-50 mt-xs-40">
                    <div class="shop-left">
                        <?php if ($productsType10 || $publishers10 || $authors10) { ?>
                            <div class="section-title-5 mb-30">
                                <h2>ตัวช่วยค้นหา</h2>
                            </div>
                        <?php } ?>
                        <?php if ($productsType10) { ?>
                            <div class="left-title mb-20">
                                <h4>ประเภทสินค้า</h4>
                            </div>
                            <div class="left-menu mb-30">
                                <ul>
                                    <?php foreach ($productsType10 as $row) { ?>
                                        <?php
                                        $originalId = $row["pty_id"];
                                        $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                        ?>
                                        <li>
                                            <a href="products_show?ptyId=<?php echo $base64Encoded ?>">
                                                <?php
                                                $originalName = $row['pty_name'];
                                                $shortName = shortenName($originalName);
                                                echo $shortName;
                                                ?>
                                                <span><?php echo "(" . number_format($row['product_count']) . ")" ?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>

                            </div>
                        <?php } ?>
                        <?php if ($publishers10) { ?>
                            <div class="left-title mb-20">
                                <h4>สำนักพิมพ์</h4>
                            </div>
                            <div class="left-menu mb-30">
                                <ul>
                                    <?php foreach ($publishers10 as $row) { ?>
                                        <?php
                                        $originalId = $row["pub_id"];
                                        $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                        ?>
                                        <li>
                                            <a href="products_show?pubId=<?php echo $base64Encoded ?>">
                                                <?php
                                                $originalName = $row['pub_name'];
                                                $shortName = shortenName($originalName);
                                                echo $shortName;
                                                ?>
                                                <span><?php echo "(" . number_format($row['product_count']) . ")" ?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>

                        <?php if ($authors10) { ?>
                            <div class="left-title mb-20">
                                <h4>ผู้แต่ง</h4>
                            </div>
                            <div class="left-menu mb-30">
                                <ul>
                                    <?php foreach ($authors10 as $row) { ?>
                                        <?php
                                        $originalId = $row["auth_id"];
                                        $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                        ?>
                                        <li>
                                            <a href="products_show?authId=<?php echo $base64Encoded ?>">
                                                <?php
                                                $originalName = $row['auth_name'];
                                                $shortName = shortenName($originalName);
                                                echo $shortName;
                                                ?>
                                                <span><?php echo "(" . number_format($row['product_count']) . ")" ?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-lg-9 col-md-12 col-12 order-lg-2 order-1">
                    <div class="section-title-5 mb-30">
                        <h2>
                            <?php
                            if (isset($_GET['ptyId'])) {
                                $_SESSION["base64Encoded"] = $_GET["ptyId"];
                                $base64Encoded =  $_SESSION["base64Encoded"];
                                // ถอดรหัส Id
                                $Id = decodeBase64ID($base64Encoded, $salt1, $salt2);
                                $ptyId = $Id;
                                $ptyName = null;

                                foreach ($productsType10 as $row) {
                                    if ($row['pty_id'] == $ptyId) {
                                        $ptyName = $row['pty_name'];
                                        break;
                                    }
                                }

                                if ($ptyName !== null) {
                                    echo "ประเภทสินค้า : " . htmlspecialchars($ptyName);
                                } else {
                                    echo "ประเภทสินค้า : ไม่พบข้อมูล";
                                }
                            } elseif (isset($_GET['pubId'])) {

                                $_SESSION["base64Encoded"] = $_GET["pubId"];
                                $base64Encoded =  $_SESSION["base64Encoded"];
                                // ถอดรหัส Id
                                $Id = decodeBase64ID($base64Encoded, $salt1, $salt2);
                                $pubId = $Id;
                                $pubName = null;

                                foreach ($publishers10 as $row) {
                                    if ($row['pub_id'] == $pubId) {
                                        $pubName = $row['pub_name'];
                                        break;
                                    }
                                }

                                if ($pubName !== null) {
                                    echo "สำนักพิมพ์ : " . htmlspecialchars($pubName);
                                } else {
                                    echo "สำนักพิมพ์ : ไม่พบข้อมูล";
                                }
                            } elseif (isset($_GET['authId'])) {
                                $_SESSION["base64Encoded"] = $_GET["authId"];
                                $base64Encoded =  $_SESSION["base64Encoded"];
                                // ถอดรหัส Id
                                $Id = decodeBase64ID($base64Encoded, $salt1, $salt2);
                                $authId = $Id;
                                $authName = null;

                                foreach ($authors10 as $row) {
                                    if ($row['auth_id'] == $authId) {
                                        $authName = $row['auth_name'];
                                        break;
                                    }
                                }

                                if ($authName !== null) {
                                    echo "ชื่อผู้แต่ง : " . htmlspecialchars($authName);
                                } else {
                                    echo "ชื่อผู้แต่ง : ไม่พบข้อมูล";
                                }
                            } else {
                                echo "สินค้าทั้งหมด";
                            }
                            ?>
                        </h2>
                    </div>
                    <div class="toolbar mb-30">
                        <div class="shop-tab">
                            <div class="tab-3">
                                <ul class="nav">
                                    <li><a class="active" href="#th" data-bs-toggle="tab"><i class="fa-solid fa-table-cells-large"></i>Grid</a></li>
                                    <li><a href="#list" data-bs-toggle="tab"><small><i class="fa-solid fa-list"></i></small>List</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- tab-area-start -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="th">
                            <div class="row">
                                <?php if ($allProducts) { ?>
                                    <?php foreach ($allProducts as $row) { ?>
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                                            <!-- single-product-start -->
                                            <?php
                                            $originalId = $row["prd_id"];
                                            $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                            ?>
                                            <div class="product-wrapper">
                                                <div class="product-img">
                                                    <a href="#" onclick="return false;">
                                                        <img src="uploads/img_product/<?php echo $row['prd_img1'] ?>" alt="book" class="primary" style="height: 250px; object-fit: cover;" />
                                                    </a>
                                                    <div class="quick-view">
                                                        <a class="action-view" href="product_detail?id=<?php echo $base64Encoded; ?>" title="รายละเอียด">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    </div>
                                                    <div class="product-flag">
                                                        <ul>
                                                            <?php if ($row['prd_preorder'] == 0) { ?>
                                                                <li><span class="sale">พรีออเดอร์</span></li>
                                                            <?php } ?>
                                                            <?php if ($row['prd_percent_discount']) { ?>
                                                                <li><span class="discount-percentage"><?php echo "-" . $row['prd_percent_discount'] . "%" ?></span></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="product-details text-center">
                                                    <div class="product-rating">
                                                        <ul>
                                                            <?php
                                                            $reviewCount = $row['review_count']; // จำนวนคนรีวิว
                                                            $totalRating = $row['total_rating']; // คะแนน
                                                            // แสดง star
                                                            reviewRatingStars($reviewCount, $totalRating)
                                                            ?>

                                                            <?php if ($reviewCount > 0) { ?>
                                                                <span><?php echo "(" . number_format($reviewCount) . ")" ?> </span>
                                                            <?php } ?>

                                                        </ul>
                                                    </div>

                                                    <?php
                                                    $originalName = $row['prd_name'];
                                                    $shortName = shortenName($originalName);
                                                    ?>
                                                    <h4><a href="#" onclick="return false;"><?php echo $shortName ?></a></h4>

                                                    <div class="product-price">
                                                        <ul>
                                                            <li>
                                                                <?php echo "฿" . number_format($row['price_sale'], 2) ?>
                                                                <?php if ($row['prd_percent_discount']  > 0) { ?>
                                                                    <small class="text-secondary ms-2" style="font-size: 0.7em;"><del><?php echo "฿" . number_format($row['prd_price'], 2) ?></del></small>
                                                                <?php } ?>

                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="product-link">
                                                    <div class="product-button">
                                                        <a href="product_detail?id=<?php echo $base64Encoded; ?>" title="รายละเอียด"><i class="fa-solid fa-eye me-1"></i>รายละเอียด</a>
                                                    </div>
                                                    <div class="add-to-link">
                                                        <ul>
                                                            <li><a href="product_detail?id=<?php echo $base64Encoded; ?>" target="_blank" title="รายละเอียด"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- single-product-end -->
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div class="alert alert-secondary text-center" role="alert">
                                            <h4 class="alert-heading text-danger">ไม่พบสินค้า!</h4>
                                            <p><i class="fa-solid fa-face-tired my-3" style="font-size: 100px;"></i></p>

                                            <p class="mb-0">
                                                <?php
                                                if (isset($_GET['ptyId'])) {
                                                    echo "ไม่พบรายการสินค้าที่อยู่ใน ประเภทสินค้า นี้";
                                                } elseif (isset($_GET['pubId'])) {
                                                    echo "ไม่พบรายการสินค้าที่อยู่ใน สำนักพิมพิ์ นี้";
                                                } elseif (isset($_GET['authId'])) {
                                                    echo "ไม่พบรายการสินค้าของผุู้แต่ง นี้";
                                                } else {
                                                    echo "ไม่พบรายการสินค้า";
                                                }
                                                ?>
                                            </p>
                                            <div class="">
                                                <hr>
                                                <a href="index" class="me-5">กลับหน้าแรก</a>
                                                <a href="products_show" class="ms-5">สินค้าทั้งหมด</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="list">
                            <?php if ($allProducts) { ?>
                                <?php foreach ($allProducts as $row) { ?>
                                    <?php
                                    $originalId = $row["prd_id"];
                                    $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                    ?>
                                    <!-- single-shop-start -->
                                    <div class="single-shop mb-30">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-12">
                                                <div class="product-wrapper-2">
                                                    <div class="product-img">
                                                        <a href="product_detail?id=<?php echo $base64Encoded ?>">
                                                            <img src="uploads/img_product/<?php echo $row['prd_img1'] ?>" alt="book" class="primary" style="height: 320px; object-fit: cover; " />
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-12">
                                                <div class="product-wrapper-content">
                                                    <div class="product-details">
                                                        <div class="product-rating">
                                                            <ul>
                                                                <?php
                                                                $reviewCount = $row['review_count']; // จำนวนคนรีวิว
                                                                $totalRating = $row['total_rating']; // คะแนน
                                                                // แสดง star
                                                                reviewRatingStars($reviewCount, $totalRating)
                                                                ?>

                                                                <?php if ($reviewCount > 0) { ?>
                                                                    <span><?php echo "(" . number_format($reviewCount) . ")" ?> </span>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>

                                                        <?php
                                                        $originalName = $row['prd_name'];
                                                        $shortName = shortenName($originalName);
                                                        ?>

                                                        <h4><a href="product_detail?id=<?php echo $base64Encoded ?>"><?php echo $shortName ?></a></h4>
                                                        <div class="product-price">
                                                            <ul>
                                                                <li><?php echo "฿" . number_format($row['price_sale'], 2) ?></li>
                                                                <li>
                                                                    <?php if ($row['prd_percent_discount']  > 0) { ?>
                                                                        <small class="text-secondary ms-2" style="font-size: 0.7em;">
                                                                            <del><?php echo "฿" . number_format($row['prd_price'], 2) ?></del>
                                                                        </small>
                                                                    <?php } ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <p class="my-5"></p>
                                                    </div>
                                                    <div class="product-link">
                                                        <div class="product-button">
                                                            <a href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด"><i class="fa-solid fa-eye me-1"></i>รายละเอียด</a>
                                                        </div>
                                                        <div class="add-to-link">
                                                            <ul>
                                                                <li><a href="product_detail?id=<?php echo $base64Encoded ?>" target="_blank" title="รายละเอียด"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- single-shop-end -->
                                <?php } ?>
                            <?php } else { ?>
                                <!-- single-shop-start -->
                                <div class="single-shop mb-30">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">

                                            <div class="alert alert-secondary text-center" role="alert">
                                                <h4 class="alert-heading text-danger">ไม่พบสินค้า!</h4>
                                                <p><i class="fa-solid fa-face-tired my-3" style="font-size: 100px;"></i></p>

                                                <p class="mb-0">
                                                    <?php
                                                    if (isset($_GET['ptyId'])) {
                                                        echo "ไม่พบรายการสินค้าที่อยู่ใน ประเภทสินค้า นี้";
                                                    } elseif (isset($_GET['pubId'])) {
                                                        echo "ไม่พบรายการสินค้าที่อยู่ใน สำนักพิมพิ์ นี้";
                                                    } elseif (isset($_GET['authId'])) {
                                                        echo "ไม่พบรายการสินค้าของผุู้แต่ง นี้";
                                                    } else {
                                                        echo "ไม่พบรายการสินค้า";
                                                    }
                                                    ?>
                                                </p>
                                                <div class="">
                                                    <hr>
                                                    <a href="index" class="me-5">กลับหน้าแรก</a>
                                                    <a href="products_show" class="ms-5">สินค้าทั้งหมด</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- single-shop-end -->
                            <?php } ?>

                        </div>
                    </div>
                    <!-- tab-area-end -->
                    <!-- pagination-area-start -->
                    <!-- <div class="pagination-area mt-50">
                        <div class="list-page-2">
                            <p>Items 1-9 of 11</p>
                        </div>
                        <div class="page-number">
                            <ul>
                                <li><a href="#" class="active">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#" class="angle"><i class="fa fa-angle-right"></i></a></li>
                            </ul>
                        </div>
                    </div> -->
                    <!-- pagination-area-end -->
                </div>
            </div>
        </div>
    </div>
    <!-- shop-main-area-end -->
    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>
</body>

</html>