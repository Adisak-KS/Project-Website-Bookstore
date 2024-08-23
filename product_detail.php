<?php
$titlePage = "รายละเอียดสินค้า";

require_once("db/connectdb.php");
require_once("db/controller/ProductController.php");
require_once("db/controller/WishlistController.php");
require_once("db/controller/CartController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$CartController = new CartController($conn);

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $prdId = decodeBase64ID($base64Encoded, $salt1, $salt2);

    $ProductController = new ProductController($conn);

    $productDetail = $ProductController->getProductDetail($prdId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($productDetail);

    $ptyId = $productDetail['pty_id'];
    $prdPreorder = $productDetail['prd_preorder'];

    //คุณอาจจะชอบสิ่งนี้
    $productsSameType = $ProductController->getProductsSameType($ptyId, $prdId);

    // สินค้าแนะนำ
    $productAdvertising = $ProductController->getProductAdvertising($prdPreorder, $prdId);


    // ตรวจสอบรายการ wishlist
    if (isset($_SESSION['mem_id'])) {
        $WishlistController = new WishlistController($conn);
        $memId = $_SESSION['mem_id'];

        $myWishlist = $WishlistController->getDetailWishlist($prdId, $memId);

        $myCartQty = $CartController->getCartItemQty($memId, $prdId); // จำนวนสินค้าที่อยู่ในตะกร้า
    } else {
        $myCartQty = 0;  // จำนวนสินค้าที่อยู่ในตะกร้า ในกรณีไม่ได้ login
    }
} else {
    header('Location: index');
    exit;
}

?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="product-details">
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
                            <li><a href="index">หน้าหลัก</a></li>
                            <li><a href="#" class="active">รายละเอียดสินค้า</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs-area-end -->
    <!-- product-main-area-start -->
    <div class="product-main-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12 col-12 order-lg-1 order-1">
                    <!-- product-main-area-start -->
                    <div class="product-main-area">
                        <div class="row">
                            <div class="col-lg-5 col-md-6 col-12">
                                <div class="flexslider">
                                    <ul class="slides">
                                        <li data-thumb="uploads/img_product/<?php echo $productDetail['prd_img1'] ?>">
                                            <img src="uploads/img_product/<?php echo $productDetail['prd_img1'] ?>" alt="woman" style="height: 414px; object-fit: cover;" />
                                        </li>
                                        <?php if (!empty($productDetail['prd_img2'])) { ?>
                                            <li data-thumb="uploads/img_product/<?php echo $productDetail['prd_img2'] ?>">
                                                <img src="uploads/img_product/<?php echo $productDetail['prd_img2'] ?>" alt="woman" style="height: 414px; object-fit: cover;" />
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-6 col-12">
                                <div class="product-info-main">
                                    <div class="page-title">
                                        <h1><?php echo $productDetail['prd_name'] ?></h1>
                                    </div>
                                    <div class="product-info-stock-sku">
                                        <?php if (empty($productDetail['prd_quantity'])) { ?>
                                            <span class="badge rounded-pill text-bg-danger"><i class="fa-solid fa-xmark me-1"></i></i>สินค้าหมด</span>
                                        <?php } else { ?>
                                            <span class="badge rounded-pill text-bg-success"><i class="fa-solid fa-check me-1"></i>มีสินค้า</span>
                                        <?php } ?>

                                        <?php if ($productDetail['prd_preorder'] == 0) { ?>
                                            <span class="badge rounded-pill text-bg-warning"><i class="fa-solid fa-clock-rotate-left me-1"></i>สินค้าพรีออเดอร์</span>
                                        <?php } ?>

                                        <div class="product-attribute">
                                            <span>รหัสสินค้า : </span>
                                            <span class="value"><?php echo $productDetail['prd_id'] ?></span>
                                        </div>
                                    </div>
                                    <div class="product-reviews-summary">
                                        <div class="rating-summary">
                                            <?php
                                            $review_count = $productDetail['review_count']; // จำนวนคนรีวิว
                                            $total_rating = $productDetail['total_rating']; // คะแนน
                                            $average_rating = ($review_count > 0) ? round($total_rating / $review_count) : 0;

                                            // แสดงดาวตามค่าเฉลี่ยการให้คะแนน
                                            for ($i = 0; $i < 5; $i++) {
                                                if ($i < $average_rating) {
                                                    echo '<span><i class="fa-solid fa-star" style="color: #f07c29;"></i></span>';
                                                } else {
                                                    echo '<span><i class="fa-solid fa-star"></i></span>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="reviews-actions">
                                            <?php if (empty($productDetail['reviews_count'])) { ?>
                                                <p>(ยังไม่มีการรีวิว)</p>
                                            <?php } else { ?>
                                                <p><?php echo "(" . number_format($productDetail['reviews_count']) . " รีวิว)" ?></p>
                                            <?php } ?>

                                        </div>
                                    </div>
                                    <?php if ($productDetail['prd_coin'] > 0) { ?>
                                        <p class="text-warning"><i class="fa-brands fa-gg-circle mt-3 me-1"></i>คุณจะได้รับ <?php echo number_format($productDetail['prd_coin']) ?> เหรียญ</p>
                                    <?php } ?>
                                    <div class="product-info-price">
                                        <div class="price-final">

                                            <?php
                                            $price = $productDetail['prd_price'];
                                            $prdPercentDiscount = $productDetail['prd_percent_discount']; // ส่วนลด(%)
                                            $priceSale = $price - ($price * ($prdPercentDiscount / 100));
                                            ?>
                                            <span><?php echo "฿" . number_format($priceSale, 2) ?></span>
                                            <span class="old-price"><small><?php echo "฿" . number_format($productDetail['prd_price'], 2) ?></small></span> <?php if ($productDetail['prd_percent_discount'] > 0) {
                                                                                                                                                                echo '<sup class="text-danger"><small>-' . $productDetail['prd_percent_discount'] . '%</small></sup>';
                                                                                                                                                            } ?>

                                        </div>
                                    </div>
                                    <div class="product-add-form">
                                        <form novalidate action="process/cart_add.php" method="post">
                                            <?php if ($productDetail['prd_quantity'] > 0) { ?>
                                                <div class="quality-button">

                                                    <input type="hidden" name="mem_id" value="<?php echo isset($_SESSION['mem_id']) ? $_SESSION['mem_id'] : null; ?>" readonly>
                                                    <input type="hidden" name="prd_id" value="<?php echo $productDetail['prd_id'] ?>" readonly>
                                                    <input type="hidden" name="prd_quantity" value="<?php echo $productDetail['prd_quantity']  ?>" readonly>
                                                    <input type="hidden" name="my_cart_qty" value="<?php echo  $myCartQty ?>" readonly>
                                                    <?php
                                                    $maxQty = $productDetail['prd_quantity'] - $myCartQty;
                                                    ?>
                                                    <input type="number" name="crt_qty" class="qty" <?php if ($myCartQty == $productDetail['prd_quantity']) {
                                                                                                        echo 'readonly';
                                                                                                    } ?>>
                                                </div>

                                                <?php if ($myCartQty == $productDetail['prd_quantity']) { ?>
                                                    <a onclick="return false" class="btnDisabled"><i class="fa-solid fa-cart-shopping me-1"></i>เพิ่มลงรถเข็น</a>

                                                <?php } else { ?>
                                                    <button type="submit" name="btn-add">
                                                        <i class="fa-solid fa-cart-shopping me-1"></i>
                                                        เพิ่มลงรถเข็น
                                                    </button>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <a onclick="return false" class="btnDisabled"><i class="fa-solid fa-cart-shopping me-1"></i>เพิ่มลงรถเข็น</a>
                                            <?php } ?>
                                        </form>
                                    </div>
                                    <div class="product-social-links">
                                        <div class="product-addto-links d-flex">
                                            <?php if (isset($_SESSION['mem_id']) && $myWishlist) { ?>
                                                <form action="process/wishlist_add_del.php" method="post">
                                                    <input type="hidden" name="prd_id" value="<?php echo $prdId; ?>">
                                                    <input type="hidden" name="action" value="0">
                                                    <button type="submit" name="btn-wishlist"><i class="fa-solid fa-heart text-danger"></i></button>
                                                </form>
                                            <?php } else { ?>
                                                <form action="process/wishlist_add_del.php" method="post">
                                                    <input type="hidden" name="prd_id" value="<?php echo $prdId; ?>">
                                                    <input type="hidden" name="action" value="1">
                                                    <button type="submit" name="btn-wishlist"><i class="fa-solid fa-heart"></i></button>
                                                </form>
                                            <?php } ?>

                                            <a href="#" title="แชร์สินค้า" onclick="copyURL(event)"><i class="fa-solid fa-share-nodes"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product-main-area-end -->
                    <!-- product-info-area-start -->
                    <div class="product-info-area mt-80">
                        <!-- Nav tabs -->
                        <ul class="nav">
                            <li><a class="active" href="#Details" data-bs-toggle="tab">รายละเอียดสินค้า</a></li>
                            <li><a href="#Reviews" data-bs-toggle="tab">รีวิวสินค้า</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="Details">
                                <div class="valu">
                                    <ul class="mb-2">
                                        <li><i class="fa-solid fa-circle"></i></i>ประเภทสินค้า : <?php echo $productDetail['pty_name']; ?></li>
                                        <li><i class="fa-solid fa-circle"></i></i>รหัส ISBN : <?php echo $productDetail['prd_isbn']; ?></li>
                                        <li><i class="fa-solid fa-circle"></i></i>จำนวนหน้า : <?php echo $productDetail['prd_number_pages'] . " หน้า" ?></li>
                                    </ul>
                                    <div class="mx-2 mt-3">
                                        <?php echo $productDetail['prd_detail']; ?>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="Reviews">
                                <div class="valu valu-2">
                                    <div class="section-title mb-60 mt-60">
                                        <h2>Customer Reviews</h2>
                                    </div>
                                    <ul>
                                        <li>
                                            <div class="review-title">
                                                <h3>themes</h3>
                                            </div>
                                            <div class="review-left">
                                                <div class="review-rating">
                                                    <span>Price</span>
                                                    <div class="rating-result">
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                    </div>
                                                </div>
                                                <div class="review-rating">
                                                    <span>Value</span>
                                                    <div class="rating-result">
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                    </div>
                                                </div>
                                                <div class="review-rating">
                                                    <span>Quality</span>
                                                    <div class="rating-result">
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                        <a href="#"><i class="fa fa-star"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-right">
                                                <div class="review-content">
                                                    <h4>themes </h4>
                                                </div>
                                                <div class="review-details">
                                                    <p class="review-author">Review by<a href="#">plaza</a></p>
                                                    <p class="review-date">Posted on <span>12/9/16</span></p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="review-add">
                                        <h3>You're reviewing:</h3>
                                        <h4>Joust Duffle Bag</h4>
                                    </div>
                                    <div class="review-field-ratings">
                                        <span>Your Rating <sup>*</sup></span>
                                        <div class="control">
                                            <div class="single-control">
                                                <span>Value</span>
                                                <div class="review-control-vote">
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                </div>
                                            </div>
                                            <div class="single-control">
                                                <span>Quality</span>
                                                <div class="review-control-vote">
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                </div>
                                            </div>
                                            <div class="single-control">
                                                <span>Price</span>
                                                <div class="review-control-vote">
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                    <a href="#"><i class="fa fa-star"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review-form">
                                        <div class="single-form">
                                            <label>Nickname <sup>*</sup></label>
                                            <form action="#">
                                                <input type="text" />
                                            </form>
                                        </div>
                                        <div class="single-form single-form-2">
                                            <label>Summary <sup>*</sup></label>
                                            <form action="#">
                                                <input type="text" />
                                            </form>
                                        </div>
                                        <div class="single-form">
                                            <label>Review <sup>*</sup></label>
                                            <form action="#">
                                                <textarea name="massage" cols="10" rows="4"></textarea>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="review-form-button">
                                        <a href="#">Submit Review</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product-info-area-end -->
                    <!-- new-book-area-start -->
                    <?php if ($productsSameType) { ?>
                        <div class="new-book-area mt-60">
                            <div class="section-title text-center mb-30">
                                <h3>คุณอาจจะชอบสิ่งนี้</h3>
                            </div>
                            <div class="tab-active-2 owl-carousel">
                                <!-- single-product-start -->
                                <?php foreach ($productsSameType as $pstProduct) { ?>
                                    <?php
                                    $originalId = $pstProduct["prd_id"];
                                    require_once("includes/salt.php");   // รหัส Salt 
                                    $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                    $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                    ?>
                                    <div class="product-wrapper">
                                        <div class="product-img">
                                            <a href="#" onclick="return false">
                                                <img src="uploads/img_product/<?php echo $pstProduct['prd_img1'] ?>" alt="book" class="primary" style="height: 250px; object-fit: cover;" />
                                            </a>
                                            <div class="quick-view">
                                                <a class="action-view" href="product_detail?id=<?php echo $base64Encoded ?>" title="รายละเอียด">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                            <div class="product-flag">
                                                <ul>
                                                    <li><span class="sale">ยอดนิยม</span> <br></li>
                                                    <?php if ($pstProduct['prd_preorder'] == 1) { ?>
                                                        <li><span class="sale">พรีออเดอร์</span></li>
                                                    <?php } ?>
                                                    <?php if ($pstProduct['prd_percent_discount']) { ?>
                                                        <li><span class="discount-percentage"><?php echo "-" . $pstProduct['prd_percent_discount'] . "%" ?></span></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="product-details text-center">
                                            <div class="product-rating">
                                                <ul>
                                                    <?php
                                                    $review_count = $pstProduct['review_count']; // จำนวนคนรีวิว
                                                    $total_rating = $pstProduct['total_rating']; // คะแนน
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
                                            $prd_name = $pstProduct['prd_name'];
                                            $max_length = 20;

                                            $short_name = (mb_strlen($prd_name) > $max_length) ? mb_substr($prd_name, 0, $max_length) . '...' : $prd_name;
                                            ?>
                                            <h4><a href="#" onclick="return false;"><?php echo $short_name ?></a></h4>
                                            <div class="product-price">
                                                <ul>
                                                    <?php
                                                    $price = $pstProduct['prd_price'];
                                                    $prdPercentDiscount = $pstProduct['prd_percent_discount']; // ส่วนลด(%)
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
                            </div>
                        </div>
                    <?php } ?>
                    <!-- new-book-area-start -->
                </div>
                <div class="col-lg-3 col-md-12 col-12 order-lg-2 order-2">
                    <div class="shop-left">
                        <?php if ($productAdvertising) { ?>
                            <div class="left-title mb-20">
                                <h4>สินค้าแนะนำ</h4>
                            </div>
                            <div class="random-area mb-30">
                                <div class="product-active-2 owl-carousel">
                                    <div class="product-total-2">
                                        <?php foreach ($productAdvertising as $advProduct) { ?>
                                            <?php
                                            $originalId = $advProduct["prd_id"];
                                            require_once("includes/salt.php");   // รหัส Salt 
                                            $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                            $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                            ?>
                                            <div class="single-most-product bd mb-18">
                                                <div class="most-product-img">
                                                    <a href="product_detail?id=<?php echo $base64Encoded; ?>"><img src="uploads/img_product/<?php echo $advProduct['prd_img1'] ?>" alt="book" style="height: 88px; object-fit:cover;" /></a>
                                                </div>
                                                <div class="most-product-content">
                                                    <div class="product-rating">
                                                        <ul>
                                                            <?php
                                                            $review_count = $advProduct['review_count']; // จำนวนคนรีวิว
                                                            $total_rating = $advProduct['total_rating']; // คะแนน
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
                                                        </ul>
                                                    </div>
                                                    <?php
                                                    $prd_name = $advProduct['prd_name'];
                                                    $max_length = 20;

                                                    $short_name = (mb_strlen($prd_name) > $max_length) ? mb_substr($prd_name, 0, $max_length) . '...' : $prd_name;
                                                    ?>
                                                    <h4><a href="product_detail?id=<?php echo $base64Encoded; ?>"><?php echo $short_name; ?></a></h4>
                                                    <div class="product-price">
                                                        <ul>
                                                            <?php
                                                            $price = $advProduct['prd_price'];
                                                            $prdPercentDiscount = $advProduct['prd_percent_discount']; // ส่วนลด(%)
                                                            $priceSale = $price - ($price * ($prdPercentDiscount / 100));
                                                            ?>
                                                            <li><?php echo "฿" . number_format($priceSale, 2) ?></li>
                                                            <li class="old-price"><?php echo "฿" . number_format($advProduct['prd_price'], 2) ?></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="product-total-2">
                                        <?php foreach ($productAdvertising as $advProduct) { ?>
                                            <?php
                                            $originalId = $advProduct["prd_id"];
                                            require_once("includes/salt.php");   // รหัส Salt 
                                            $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                            $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                            ?>
                                            <div class="single-most-product bd mb-18">
                                                <div class="most-product-img">
                                                    <a href="product_detail?id=<?php echo $base64Encoded; ?>"><img src="uploads/img_product/<?php echo $advProduct['prd_img1'] ?>" alt="book" style="height: 88px; object-fit:cover;" /></a>
                                                </div>
                                                <div class="most-product-content">
                                                    <div class="product-rating">
                                                        <ul>
                                                            <?php
                                                            $review_count = $advProduct['review_count']; // จำนวนคนรีวิว
                                                            $total_rating = $advProduct['total_rating']; // คะแนน
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
                                                        </ul>
                                                    </div>
                                                    <?php
                                                    $prd_name = $advProduct['prd_name'];
                                                    $max_length = 20;

                                                    $short_name = (mb_strlen($prd_name) > $max_length) ? mb_substr($prd_name, 0, $max_length) . '...' : $prd_name;
                                                    ?>
                                                    <h4><a href="product_detail?id=<?php echo $base64Encoded; ?>"><?php echo $short_name; ?></a></h4>
                                                    <div class="product-price">
                                                        <ul>
                                                            <?php
                                                            $price = $advProduct['prd_price'];
                                                            $prdPercentDiscount = $advProduct['prd_percent_discount']; // ส่วนลด(%)
                                                            $priceSale = $price - ($price * ($prdPercentDiscount / 100));
                                                            ?>
                                                            <li><?php echo "฿" . number_format($priceSale, 2) ?></li>
                                                            <li class="old-price"><?php echo "฿" . number_format($advProduct['prd_price'], 2) ?></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- product-main-area-end -->

    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>


    <!-- จัดการ input จำนวนสินค้า  -->
    <script>
        $(document).ready(function() {
            const maxQty = <?php echo $maxQty; ?>;

            function validateQty(input) {
                let value = parseFloat(input.val());

                if (isNaN(value) || value < 1 || !Number.isInteger(value)) {
                    input.val(1);
                } else if (value > maxQty) {
                    input.val(maxQty); 
                }
            }

            // ตรวจสอบเมื่อเริ่มต้น
            validateQty($("input[name='crt_qty']"));

            // เมื่อผู้ใช้กรอกค่าผ่านคีย์บอร์ดหรือมีการเปลี่ยนแปลงค่า
            $("input[name='crt_qty']").on('input change keyup', function(e) {
                // ป้องกันการใส่ค่าเลขทศนิยมหรือค่าที่ไม่เหมาะสมผ่านคีย์บอร์ด
                if (e.key === '.' || e.key === ',' || (e.key === '-' && $(this).val().length > 0)) {
                    e.preventDefault();
                }
                validateQty($(this));
            });
        });
    </script>

    <!-- <script>
        $(document).ready(function() {
            const maxQty = <?php echo $maxQty; ?>;

            function validateQty(input) {
                let value = parseFloat(input.val());

                if (isNaN(value) || value < 1 || !Number.isInteger(value)) {
                    input.val(1);
                } else if (value > maxQty) {
                    input.val(maxQty);
                }
            }

            // ตรวจสอบเมื่อเริ่มต้น
            validateQty($("input[name='crt_qty']"));

            // เมื่อผู้ใช้กดปุ่มเพิ่มหรือลด
            $("input[name='crt_qty']").on('input change', function() {
                validateQty($(this));
            });

            // เพิ่มการป้องกันเมื่อผู้ใช้กดปุ่มลูกศรเพิ่ม/ลด
            $("input[name='crt_qty']").on('keydown', function(e) {
                // ป้องกันการใส่ค่าเลขทศนิยมหรือคีย์ที่ไม่ใช่ตัวเลข
                if (e.key === '.' || e.key === ',' || (e.key === '-' && $(this).val().length > 0)) {
                    e.preventDefault();
                }

                setTimeout(() => {
                    validateQty($(this));
                }, 0);
            });

            // เมื่อผู้ใช้เลื่อน input field (เพิ่มหรือลดค่าด้วยปุ่มลูกศร)
            $("input[name='crt_qty']").on('mousewheel', function(e) {
                e.preventDefault();
                let currentValue = parseInt($(this).val());
                if (e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0) {
                    // Scroll up
                    if (currentValue < maxQty) {
                        $(this).val(currentValue + 1);
                    }
                } else {
                    // Scroll down
                    if (currentValue > 1) {
                        $(this).val(currentValue - 1);
                    }
                }
                validateQty($(this));
            });
        });
    </script> -->
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>