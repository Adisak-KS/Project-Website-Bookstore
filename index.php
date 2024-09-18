<?php
$titlePage = "หน้าแรก";

require_once("db/connectdb.php");
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
                    <?php foreach ($newProducts as $newProduct) { ?>
                        <div class="tab-total">

                            <?php
                            $originalId = $newProduct["prd_id"];
                            require_once("includes/salt.php");   // รหัส Salt 
                            $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                            $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                            ?>

                            <!-- single-product-start -->
                            <div class="product-wrapper">
                                <div class="product-img">
                                    <a href="#" onclick="return false;">
                                        <img src="uploads/img_product/<?php echo $newProduct['prd_img1'] ?>" alt="book" class="primary" style="height: 250px; object-fit: cover;" />
                                    </a>
                                    <div class="quick-view">
                                        <a class="action-view" href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="product-flag">
                                        <ul>
                                            <li><span class="sale">ใหม่</span> <br></li>
                                            <?php if ($newProduct['prd_preorder'] == 0) { ?>
                                                <li><span class="sale">พรีออเดอร์</span> <br></li>
                                            <?php } ?>
                                            <?php if ($newProduct['prd_percent_discount'] > 0) { ?>
                                                <li><span class="discount-percentage"><?php echo "-" . $newProduct['prd_percent_discount'] . "%" ?></span></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-details text-center">
                                    <div class="product-rating mt-1">
                                        <ul>
                                            <?php
                                            $review_count = $newProduct['review_count']; // จำนวนคนรีวิว
                                            $total_rating = $newProduct['total_rating']; // คะแนน
                                            $average_rating = ($review_count > 0) ? round($total_rating / $review_count) : 0;

                                            // แสดงดาวตามค่าเฉลี่ยการให้คะแนน
                                            for ($i = 0; $i < 5; $i++) {
                                                if ($i < $average_rating) {
                                                    echo '<li><i class="fa-solid fa-star" style="color: #f07c29;"></i></li>';
                                                } else {
                                                    echo '<li><i class="fa-solid fa-star"></i></li>'; // เพิ่มสีเพื่อแสดงดาวที่ว่าง
                                                }
                                            }
                                            ?>

                                            <?php if ($review_count > 0) { ?>
                                                <span><?php echo "(" . number_format($review_count) . ")" ?> </span>
                                            <?php } ?>

                                        </ul>

                                    </div>
                                    <?php
                                    $prd_name = $newProduct['prd_name'];
                                    $max_length = 20;

                                    $short_name = (mb_strlen($prd_name) > $max_length) ? mb_substr($prd_name, 0, $max_length) . '...' : $prd_name;
                                    ?>
                                    <h4><a href="#" onclick="return false;"><?php echo $short_name ?></a></h4>
                                    <div class="product-price">
                                        <ul>
                                            <?php
                                            $price = $newProduct['prd_price'];
                                            $prdPercentDiscount = $newProduct['prd_percent_discount']; // ส่วนลด(%)
                                            $priceSale = $price - ($price * ($prdPercentDiscount / 100));
                                            ?>
                                            <li>
                                                <?php echo "฿" . number_format($priceSale, 2) ?>
                                                <?php if ($prdPercentDiscount  > 0) { ?>
                                                    <small class="text-secondary ms-2" style="font-size: 0.7em;"><del><?php echo "฿" . number_format($price, 2) ?></del></small>
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
                            <?php foreach ($recommendProducts as $recProduct) { ?>
                                <?php
                                $originalId = $recProduct["prd_id"];
                                require_once("includes/salt.php");   // รหัส Salt 
                                $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                ?>
                                <div class="product-wrapper">
                                    <div class="product-img">
                                        <a href="#" onclick="return false;">
                                            <img src="uploads/img_product/<?php echo $recProduct['prd_img1'] ?>" alt="book" class="primary" style="height: 250px; object-fit: cover;" />
                                        </a>
                                        <div class="quick-view">
                                            <a class="action-view" href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="product-flag">
                                            <ul>
                                                <li><span class="sale">แนะนำ</span> <br></li>
                                                <?php if ($recProduct['prd_preorder'] == 0) { ?>
                                                    <li><span class="sale">พรีออเดอร์</span></li>
                                                <?php } ?>
                                                <?php if ($recProduct['prd_percent_discount']) { ?>
                                                    <li><span class="discount-percentage"><?php echo "-" . $recProduct['prd_percent_discount'] . "%" ?></span></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-details text-center">
                                        <div class="product-rating">
                                            <ul>
                                                <?php
                                                $review_count = $recProduct['review_count']; // จำนวนคนรีวิว
                                                $total_rating = $recProduct['total_rating']; // คะแนน
                                                $average_rating = ($review_count > 0) ? round($total_rating / $review_count) : 0;

                                                // แสดงดาวตามค่าเฉลี่ยการให้คะแนน
                                                for ($i = 0; $i < 5; $i++) {
                                                    if ($i < $average_rating) {
                                                        echo '<li><i class="fa-solid fa-star" style="color: #f07c29;"></i></li>';
                                                    } else {
                                                        echo '<li><i class="fa-solid fa-star"></i></li>'; // เพิ่มสีเพื่อแสดงดาวที่ว่าง
                                                    }
                                                }
                                                ?>

                                                <?php if ($review_count > 0) { ?>
                                                    <span><?php echo "(" . number_format($review_count) . ")" ?> </span>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <?php
                                        $prd_name = $recProduct['prd_name'];
                                        $max_length = 20;

                                        $short_name = (mb_strlen($prd_name) > $max_length) ? mb_substr($prd_name, 0, $max_length) . '...' : $prd_name;
                                        ?>
                                        <h4><a href="#" onclick="return false;"><?php echo $short_name ?></a></h4>
                                        <div class="product-price">
                                            <ul>
                                                <?php
                                                $price = $recProduct['prd_price'];
                                                $prdPercentDiscount = $recProduct['prd_percent_discount']; // ส่วนลด(%)
                                                $priceSale = $price - ($price * ($prdPercentDiscount / 100));
                                                ?>
                                                <li>
                                                    <?php echo "฿" . number_format($priceSale, 2) ?>
                                                    <?php if ($prdPercentDiscount  > 0) { ?>
                                                        <small class="text-secondary ms-2" style="font-size: 0.7em;"><del><?php echo "฿" . number_format($price, 2) ?></del></small>
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
                            <?php foreach ($popularProducts as $popProduct) { ?>
                                <?php
                                $originalId = $popProduct["prd_id"];
                                require_once("includes/salt.php");   // รหัส Salt 
                                $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                ?>
                                <div class="product-wrapper">
                                    <div class="product-img">
                                        <a href="#" onclick="return false">
                                            <img src="uploads/img_product/<?php echo $popProduct['prd_img1'] ?>" alt="book" class="primary" style="height: 250px; object-fit: cover;" />
                                        </a>
                                        <div class="quick-view">
                                            <a class="action-view" href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="product-flag">
                                            <ul>
                                                <li><span class="sale">ยอดนิยม</span> <br></li>
                                                <?php if ($popProduct['prd_preorder'] == 0) { ?>
                                                    <li><span class="sale">พรีออเดอร์</span></li>
                                                <?php } ?>
                                                <?php if ($popProduct['prd_percent_discount']) { ?>
                                                    <li><span class="discount-percentage"><?php echo "-" . $popProduct['prd_percent_discount'] . "%" ?></span></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-details text-center">
                                        <div class="product-rating">
                                            <ul>
                                                <?php
                                                $review_count = $popProduct['review_count']; // จำนวนคนรีวิว
                                                $total_rating = $popProduct['total_rating']; // คะแนน
                                                $average_rating = ($review_count > 0) ? round($total_rating / $review_count) : 0;

                                                // แสดงดาวตามค่าเฉลี่ยการให้คะแนน
                                                for ($i = 0; $i < 5; $i++) {
                                                    if ($i < $average_rating) {
                                                        echo '<li><i class="fa-solid fa-star" style="color: #f07c29;"></i></li>';
                                                    } else {
                                                        echo '<li><i class="fa-solid fa-star"></i></li>'; // เพิ่มสีเพื่อแสดงดาวที่ว่าง
                                                    }
                                                }
                                                ?>

                                                <?php if ($review_count > 0) { ?>
                                                    <span><?php echo "(" . number_format($review_count) . ")" ?> </span>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <?php
                                        $prd_name = $popProduct['prd_name'];
                                        $max_length = 20;

                                        $short_name = (mb_strlen($prd_name) > $max_length) ? mb_substr($prd_name, 0, $max_length) . '...' : $prd_name;
                                        ?>
                                        <h4><a href="#" onclick="return false;"><?php echo $short_name ?></a></h4>
                                        <div class="product-price">
                                            <ul>
                                                <?php
                                                $price = $popProduct['prd_price'];
                                                $prdPercentDiscount = $popProduct['prd_percent_discount']; // ส่วนลด(%)
                                                $priceSale = $price - ($price * ($prdPercentDiscount / 100));
                                                ?>
                                                <li>
                                                    <?php echo "฿" . number_format($priceSale, 2) ?>
                                                    <?php if ($prdPercentDiscount  > 0) { ?>
                                                        <small class="text-secondary ms-2" style="font-size: 0.7em;"><del><?php echo "฿" . number_format($price, 2) ?></del></small>
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
                            <?php foreach ($mostViewedProducts as $mvProduct) { ?>
                                <?php
                                $originalId = $mvProduct["prd_id"];
                                require_once("includes/salt.php");   // รหัส Salt 
                                $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                ?>
                                <div class="product-wrapper">
                                    <div class="product-img">
                                        <a href="#" onclick="return false;">
                                            <img src="uploads/img_product/<?php echo $mvProduct['prd_img1'] ?>" alt="book" class="primary" style="height: 250px; object-fit: cover;" />
                                        </a>
                                        <div class="quick-view">
                                            <a class="action-view" href="product_detail?id=<?php echo $base64Encoded; ?>" title="รายละเอียด">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="product-flag">
                                            <ul>
                                                <li><span class="sale">เข้าชมบ่อย</span> <br></li>
                                                <?php if ($mvProduct['prd_preorder'] == 0) { ?>
                                                    <li><span class="sale">พรีออเดอร์</span></li>
                                                <?php } ?>
                                                <?php if ($mvProduct['prd_percent_discount']) { ?>
                                                    <li><span class="discount-percentage"><?php echo "-" . $mvProduct['prd_percent_discount'] . "%" ?></span></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-details text-center">
                                        <div class="product-rating">
                                            <ul>
                                                <?php
                                                $review_count = $mvProduct['review_count']; // จำนวนคนรีวิว
                                                $total_rating = $mvProduct['total_rating']; // คะแนน
                                                $average_rating = ($review_count > 0) ? round($total_rating / $review_count) : 0;

                                                // แสดงดาวตามค่าเฉลี่ยการให้คะแนน
                                                for ($i = 0; $i < 5; $i++) {
                                                    if ($i < $average_rating) {
                                                        echo '<li><i class="fa-solid fa-star" style="color: #f07c29;"></i></li>';
                                                    } else {
                                                        echo '<li><i class="fa-solid fa-star"></i></li>'; // เพิ่มสีเพื่อแสดงดาวที่ว่าง
                                                    }
                                                }
                                                ?>

                                                <?php if ($review_count > 0) { ?>
                                                    <span><?php echo "(" . number_format($review_count) . ")" ?> </span>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <?php
                                        $prd_name = $mvProduct['prd_name'];
                                        $max_length = 20;

                                        $short_name = (mb_strlen($prd_name) > $max_length) ? mb_substr($prd_name, 0, $max_length) . '...' : $prd_name;
                                        ?>
                                        <h4><a href="#" onclick="return false;"><?php echo $short_name ?></a></h4>
                                        <div class="product-price">
                                            <ul>
                                                <?php
                                                $price = $mvProduct['prd_price'];
                                                $prdPercentDiscount = $mvProduct['prd_percent_discount']; // ส่วนลด(%)
                                                $priceSale = $price - ($price * ($prdPercentDiscount / 100));
                                                ?>
                                                <li>
                                                    <?php echo "฿" . number_format($priceSale, 2) ?>
                                                    <?php if ($prdPercentDiscount  > 0) { ?>
                                                        <small class="text-secondary ms-2" style="font-size: 0.7em;"><del><?php echo "฿" . number_format($price, 2) ?></del></small>
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