<?php
$titlePage = "รายละเอียดสินค้า";

require_once("db/connectdb.php");
require_once("includes/salt.php");
require_once("includes/functions.php");
require_once("db/controller/ProductController.php");
require_once("db/controller/WishlistController.php");
require_once("db/controller/CartController.php");
require_once("db/controller/ReviewController.php");
require_once("db/controller/ReportViewController.php");


$ProductController = new ProductController($conn);
$CartController = new CartController($conn);
$ReviewController = new ReviewController($conn);
$ReportViewController = new ReportViewController($conn);

if (isset($_GET['id'])) {

    $_SESSION["base64Encoded"] = $_GET["id"];
    $base64Encoded =  $_SESSION["base64Encoded"];

    // ถอดรหัส Id
    $prdId = decodeBase64ID($base64Encoded, $salt1, $salt2);


    $productDetail = $ProductController->getProductDetail($prdId);

    // ตรวจสอบว่ามีข้อมูลที่ตรงกับ id ไหม
    checkResultDetail($productDetail);

    $ptyId = $productDetail['pty_id'];
    $prdPreorder = $productDetail['prd_preorder'];

    //คุณอาจจะชอบสิ่งนี้
    $productsSameType = $ProductController->getProductsSameType($ptyId, $prdId);

    // สินค้าแนะนำ
    $productAdvertising = $ProductController->getProductAdvertising($prdPreorder, $prdId);


    $inertReportView = $ReportViewController->insertReportView($prdId, $ptyId);


    // ตรวจสอบรายการ wishlist
    if (isset($_SESSION['mem_id'])) {
        $WishlistController = new WishlistController($conn);
        $memId = $_SESSION['mem_id'];

        $myWishlist = $WishlistController->getDetailWishlist($prdId, $memId);

        $myCartQty = $CartController->getCartItemQty($memId, $prdId); // จำนวนสินค้าที่อยู่ในตะกร้า
    } else {
        $myCartQty = 0;  // จำนวนสินค้าที่อยู่ในตะกร้า ในกรณีไม่ได้ login
    }


    $checkReview = $ReviewController->checkReviewInProductDetail($prdId);

    if ($checkReview) {
        $review = $ReviewController->getReviewInProductDetail($prdId);
    } else {
        $review = null;
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

                                        <div class="rating-summary d-flex list-unstyled flex-row">
                                            <?php
                                            $reviewCount = $productDetail['review_count']; // จำนวนคนรีวิว
                                            $totalRating = $productDetail['total_rating']; // คะแนน
                                            // แสดง star
                                            reviewRatingStars($reviewCount, $totalRating)
                                            ?>
                                        </div>

                                        <div class="reviews-actions">
                                            <?php if ($reviewCount > 0) { ?>
                                                <span><?php echo "(" . number_format($reviewCount) . " รีวิว)" ?> </span>
                                            <?php } else { ?>
                                                <span>(ยังไม่มีการรีวิว)</span>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <?php if ($productDetail['prd_coin'] > 0) { ?>
                                        <p class="text-warning"><i class="fa-brands fa-gg-circle mt-3 me-1"></i>คุณจะได้รับ <?php echo number_format($productDetail['prd_coin']) ?> เหรียญ</p>
                                    <?php } ?>

                                    <div class="product-info-price">
                                        <div class="price-final">

                                            <span><?php echo "฿" . number_format($productDetail['price_sale'], 2) ?></span>

                                            <?php if ($productDetail['prd_percent_discount'] > 0) { ?>
                                                <span class="old-price">
                                                    <small><?php echo "฿" . number_format($productDetail['prd_price'], 2) ?></small>
                                                </span>
                                                <sup class="text-danger">
                                                    <small><?php echo "-" . number_format($productDetail['prd_percent_discount']) . "%" ?></small>
                                                </sup>
                                            <?php } ?>
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
                                        <h2>รีวิวจากผู้ซื้อจริง</h2>
                                    </div>
                                    <?php if ($review) { ?>
                                        <?php foreach ($review as $row) { ?>
                                            <div class="border border-3 py-3 px-3 mt-3">
                                                <div class="d-flex justify-content-between">
                                                    <p><strong>ผู้ซื้อ : </strong><?php echo $row['mem_username']; ?></p>
                                                    <p><strong>วัน / เวลา : </strong><?php echo $row['prv_time_create']; ?></p>
                                                </div>
                                                <p>
                                                    <strong>ระดับความพึงพอใจ : </strong>
                                                    <?php
                                                    $rating = $row['prv_rating'];

                                                    for ($i = 1; $i <= 5; $i++) {
                                                        $isFilled = $i <= $rating ? 'color:#f07c29;' : ''; // ใช้สีเมื่อเป็นดาวที่ถูกเลือก
                                                        echo '<i class="fa-solid fa-star" style="' . $isFilled . '"></i>';
                                                    }
                                                    ?>

                                                </p>
                                                <p><strong>รายละเอียด : </strong><?php echo $row['prv_detail']; ?></p>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <p class="text-danger">*ไม่พบรีวิวสินค้าจากผู้ซื้อ สั่งซื้อสินค้าเพื่อรีวิว</p>
                                    <?php } ?>
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
                                <?php foreach ($productsSameType as $row) { ?>
                                    <?php
                                    $originalId = $row["prd_id"];
                                    $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                    $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                    ?>
                                    <div class="product-wrapper me-1">
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
                                                    <?php if ($row['prd_preorder'] == 1) { ?>
                                                        <li><span class="sale">พรีออเดอร์</span></li>
                                                    <?php } ?>
                                                    <?php if ($row['prd_percent_discount'] > 0) { ?>
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
                                                            <small class="text-secondary ms-2" style="font-size: 0.7em;">
                                                                <del><?php echo "฿" . number_format($row['prd_price'], 2) ?></del>
                                                            </small>
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
                                        <?php foreach ($productAdvertising as $row) { ?>
                                            <?php
                                            $originalId = $row["prd_id"];
                                            $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                            $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                            ?>
                                            <div class="single-most-product bd mb-18">
                                                <div class="most-product-img">
                                                    <a href="product_detail?id=<?php echo $base64Encoded; ?>"><img src="uploads/img_product/<?php echo $row['prd_img1'] ?>" alt="book" style="height: 88px; object-fit:cover;" /></a>
                                                </div>
                                                <div class="most-product-content">
                                                    <div class="product-rating">
                                                        <ul>
                                                            <?php
                                                            $reviewCount = $row['review_count']; // จำนวนคนรีวิว
                                                            $totalRating = $row['total_rating']; // คะแนน
                                                            // แสดง star
                                                            reviewRatingStars($reviewCount, $totalRating)
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <?php
                                                    $originalName = $row['prd_name'];
                                                    $shortName = shortenName($originalName);
                                                    ?>
                                                    <h4><a href="product_detail?id=<?php echo $base64Encoded; ?>"><?php echo $shortName; ?></a></h4>
                                                    <div class="product-price">
                                                        <ul>

                                                            <li><?php echo "฿" . number_format($row['price_sale'], 2) ?></li>
                                                            <?php if ($row['prd_percent_discount'] > 0) { ?>
                                                                <li class="old-price"><?php echo "฿" . number_format($row['prd_price'], 2) ?></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="product-total-2">
                                        <?php foreach ($productAdvertising as $row) { ?>
                                            <?php
                                            $originalId = $row["prd_id"];
                                            $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                            $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                            ?>
                                            <div class="single-most-product bd mb-18">
                                                <div class="most-product-img">
                                                    <a href="product_detail?id=<?php echo $base64Encoded; ?>"><img src="uploads/img_product/<?php echo $row['prd_img1'] ?>" alt="book" style="height: 88px; object-fit:cover;" /></a>
                                                </div>
                                                <div class="most-product-content">
                                                    <div class="product-rating">
                                                        <ul>
                                                            <?php
                                                            $reviewCount = $row['review_count']; // จำนวนคนรีวิว
                                                            $totalRating = $row['total_rating']; // คะแนน
                                                            // แสดง star
                                                            reviewRatingStars($reviewCount, $totalRating)
                                                            ?>
                                                        </ul>
                                                    </div>

                                                    <?php
                                                    $originalName = $row['prd_name'];
                                                    $shortName = shortenName($originalName);
                                                    ?>
                                                    <h4><a href="product_detail?id=<?php echo $base64Encoded; ?>"><?php echo $shortName; ?></a></h4>

                                                    <div class="product-price">
                                                        <ul>
                                                            <li><?php echo "฿" . number_format($row['price_sale'], 2) ?></li>
                                                            <?php if ($row['prd_percent_discount'] > 0) { ?>
                                                                <li class="old-price"><?php echo "฿" . number_format($row['prd_price'], 2) ?></li>
                                                            <?php } ?>
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
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>