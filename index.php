<?php
$titlePage = "หน้าแรก";

require_once("db/connectdb.php");
require_once("includes/salt.php");
require_once("includes/functions.php");
require_once("db/controller/BannerController.php");
require_once("db/controller/ProductController.php");

$BannerController = new BannerController($conn);
$ProductController = new ProductController($conn);

$banners = $BannerController->getSlideBanner();

$newProducts = $ProductController->getProductNew();
$recommendProducts = $ProductController->getRecommendedProducts();
$popularProducts = $ProductController->getPopularProducts();
$mostViewedProducts = $ProductController->getMostViewedProducts();

?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="home-1">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- Add your site or application content here -->

    <!-- header-area-start -->
    <?php require_once("layouts/nav_topbar.php"); ?>
    <!-- header-area-end -->

    <!-- slider-area-start -->
    <div class="slider-area mt-30">
        <div class="container">
            <div class="slider-active owl-carousel">
                <?php foreach ($banners as $banner) { ?>
                    <a href="<?php echo $banner['bn_link'] ?>">
                        <!-- <div class="single-slider pt-100 pb-145 bg-img" style="background-image:url(uploads/img_banner/<?php echo $banner['bn_img']; ?>); height:520px; background-size:cover;"></div> -->
                        <img src="uploads/img_banner/<?php echo $banner['bn_img']; ?>" style="width:100%; height:520px; object-fit: cover;"></img>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- slider-area-end -->

    <!-- featured-area-start -->
    <div class="new-book-area pt-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title section-title-res text-center mb-30">
                        <h2>สินค้าใหม่</h2>
                    </div>
                </div>
            </div>
            <div class="tab-active owl-carousel">
                <?php if ($newProducts) { ?>
                    <?php foreach ($newProducts as $row) { ?>
                        <div class="tab-total">

                            <?php
                            $originalId = $row["prd_id"];
                            $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                            ?>

                            <!-- single-product-start -->
                            <div class="product-wrapper">
                                <div class="product-img">
                                    <a href="#" onclick="return false;">
                                        <img src="uploads/img_product/<?php echo $row['prd_img1'] ?>" alt="book" class="primary" style="height: 250px; object-fit: cover;" />
                                    </a>
                                    <div class="quick-view">
                                        <a class="action-view" href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="product-flag">
                                        <ul>
                                            <li><span class="sale">ใหม่</span> <br></li>
                                            <?php if ($row['prd_preorder'] == 0) { ?>
                                                <li><span class="sale">พรีออเดอร์</span> <br></li>
                                            <?php } ?>
                                            <?php if ($row['prd_percent_discount'] > 0) { ?>
                                                <li><span class="discount-percentage"><?php echo "-" . $row['prd_percent_discount'] . "%" ?></span></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-details text-center">
                                    <div class="product-rating mt-1">
                                        <ul>
                                            <?php
                                            $reviewCount = $row['review_count']; // จำนวนคนรีวิว
                                            $totalRating = $row['total_rating']; // คะแนน
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
                                        <a href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด"><i class="fa-solid fa-eye me-1"></i>รายละเอียด</a>
                                    </div>
                                    <div class="add-to-link">
                                        <ul>
                                            <li><a href="product_detail?id=<?php echo $base64Encoded ?>" target="_blank" title="รายละเอียด"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- single-product-end -->
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="tab-total">
                        <!-- single-product-start -->
                        <div class="product-wrapper">
                            <div class="product-img">
                                <a href="#">
                                    <img src="img/product/1.jpg" alt="book" class="primary" />
                                </a>
                                <div class="quick-view">
                                    <a class="action-view" href="#" data-bs-target="#productModal" data-bs-toggle="modal" title="รายละเอียด">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                                <div class="product-flag">
                                    <ul>
                                        <li><span class="sale">new</span> <br></li>
                                        <li><span class="discount-percentage">-5%</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-details text-center">
                                <div class="product-rating">
                                    <ul>
                                        <li><a href="#"><i class="fa fa-star"></i></a></li>
                                        <li><a href="#"><i class="fa fa-star"></i></a></li>
                                        <li><a href="#"><i class="fa fa-star"></i></a></li>
                                        <li><a href="#"><i class="fa fa-star"></i></a></li>
                                        <li><a href="#"><i class="fa fa-star"></i></a></li>
                                    </ul>
                                </div>
                                <h4><a href="#">Joust Duffle Bag</a></h4>
                                <div class="product-price">
                                    <ul>
                                        <li>$60.00</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-link">
                                <div class="product-button">
                                    <a href="#" title="Add to cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                </div>
                                <div class="add-to-link">
                                    <ul>
                                        <li><a href="product-details.html" title="Details"><i class="fa fa-external-link"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- single-product-end -->
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- featured-area-start -->


    <!-- product-area-start -->
    <div class="product-area pb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title bt text-center pt-100 mb-50">
                        <h2>สินค้าของเรา</h2>
                        <p>สินค้าขายดีและน่าสนใจของเรา<br /> ค้นพบสินค้าที่หลากหลายที่คุณกำลังมองหา</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <!-- tab-menu-start -->
                    <div class="tab-menu mb-40 text-center">
                        <ul class="nav justify-content-center">
                            <li><a class="active" href="#recommendProducts" data-bs-toggle="tab">สินค้าแนะนำ</a></li>
                            <li><a href="#popularProducts" data-bs-toggle="tab">สินค้ายอดนิยม</a></li>
                            <li><a href="#mostViewedProducts" data-bs-toggle="tab">สินค้าที่มีผู้เข้าชมมาก</a></li>
                        </ul>
                    </div>
                    <!-- tab-menu-end -->
                </div>
            </div>
            <!-- tab-area-start -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="recommendProducts">
                    <div class="tab-active owl-carousel">
                        <?php if ($recommendProducts) { ?>
                            <!-- single-product-start -->
                            <?php foreach ($recommendProducts as $row) { ?>
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
                                            <a class="action-view" href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="product-flag">
                                            <ul>
                                                <li><span class="sale">แนะนำ</span> <br></li>
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
                                            <a href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด"><i class="fa-solid fa-eye me-1"></i>รายละเอียด</a>
                                        </div>
                                        <div class="add-to-link">
                                            <ul>
                                                <li><a href="product_detail?id=<?php echo $base64Encoded ?>" target="_blank" title="รายละเอียด"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <!-- single-product-end -->
                        <?php } else { ?>
                            <!-- single-product-start -->
                            <div class="product-wrapper">
                                <div class="product-img">
                                    <a href="#">
                                        <img src="img/product/3.jpg" alt="book" class="primary" />
                                    </a>
                                    <div class="quick-view">
                                        <a class="action-view" href="#" data-bs-target="#productModal" data-bs-toggle="modal" title="รายละเอียด">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="product-flag">
                                        <ul>
                                            <li><span class="sale">new</span> <br></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-details text-center">
                                    <div class="product-rating">
                                        <ul>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                        </ul>
                                    </div>
                                    <h4><a href="#">Chaz Kangeroo Hoodie</a></h4>
                                    <div class="product-price">
                                        <ul>
                                            <li>$52.00</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-link">
                                    <div class="product-button">
                                        <a href="#" title="Add to cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                    </div>
                                    <div class="add-to-link">
                                        <ul>
                                            <li><a href="product-details.html" title="Details"><i class="fa fa-external-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="popularProducts">
                    <div class="tab-active owl-carousel">
                        <?php if ($popularProducts) { ?>
                            <!-- single-product-start -->
                            <?php foreach ($popularProducts as $row) { ?>
                                <?php
                                $originalId = $row["prd_id"];
                                $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                ?>
                                <div class="product-wrapper">
                                    <div class="product-img">
                                        <a href="#" onclick="return false">
                                            <img src="uploads/img_product/<?php echo $row['prd_img1'] ?>" alt="book" class="primary" style="height: 250px; object-fit: cover;" />
                                        </a>
                                        <div class="quick-view">
                                            <a class="action-view" href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="product-flag">
                                            <ul>
                                                <li><span class="sale">ยอดนิยม</span> <br></li>
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
                                            <a href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด"><i class="fa-solid fa-eye me-1"></i>รายละเอียด</a>
                                        </div>
                                        <div class="add-to-link">
                                            <ul>
                                                <li><a href="product_detail?id=<?php echo $base64Encoded ?>" target="_blank" title="รายละเอียด"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <!-- single-product-end -->
                        <?php } else { ?>
                            <!-- single-product-start -->
                            <div class="product-wrapper">
                                <div class="product-img">
                                    <a href="#">
                                        <img src="img/product/3.jpg" alt="book" class="primary" />
                                    </a>
                                    <div class="quick-view">
                                        <a class="action-view" href="#" data-bs-target="#productModal" data-bs-toggle="modal" title="รายละเอียด">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="product-flag">
                                        <ul>
                                            <li><span class="sale">new</span> <br></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-details text-center">
                                    <div class="product-rating">
                                        <ul>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                        </ul>
                                    </div>
                                    <h4><a href="#">Chaz Kangeroo Hoodie</a></h4>
                                    <div class="product-price">
                                        <ul>
                                            <li>$52.00</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-link">
                                    <div class="product-button">
                                        <a href="#" title="Add to cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                    </div>
                                    <div class="add-to-link">
                                        <ul>
                                            <li><a href="product-details.html" title="Details"><i class="fa fa-external-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="mostViewedProducts">
                    <div class="tab-active owl-carousel">
                        <?php if ($mostViewedProducts) { ?>
                            <!-- single-product-start -->
                            <?php foreach ($mostViewedProducts as $row) { ?>
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
                                                <li><span class="sale">เข้าชมบ่อย</span> <br></li>
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
                            <?php } ?>
                            <!-- single-product-end -->
                        <?php } else { ?>
                            <!-- single-product-start -->
                            <div class="product-wrapper">
                                <div class="product-img">
                                    <a href="#">
                                        <img src="img/product/3.jpg" alt="book" class="primary" />
                                    </a>
                                    <div class="quick-view">
                                        <a class="action-view" href="#" data-bs-target="#productModal" data-bs-toggle="modal" title="รายละเอียด">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="product-flag">
                                        <ul>
                                            <li><span class="sale">new</span> <br></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-details text-center">
                                    <div class="product-rating">
                                        <ul>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                            <li><a href="#"><i class="fa fa-star"></i></a></li>
                                        </ul>
                                    </div>
                                    <h4><a href="#">Chaz Kangeroo Hoodie</a></h4>
                                    <div class="product-price">
                                        <ul>
                                            <li>$52.00</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-link">
                                    <div class="product-button">
                                        <a href="#" title="Add to cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
                                    </div>
                                    <div class="add-to-link">
                                        <ul>
                                            <li><a href="product-details.html" title="Details"><i class="fa fa-external-link"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- tab-area-end -->
        </div>
    </div>
    <!-- product-area-end -->

    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>

</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>