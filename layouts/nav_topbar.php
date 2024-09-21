<?php
require_once("db/connectdb.php");
require_once("includes/salt.php");
require_once("includes/functions.php");
require_once("db/controller/ContactController.php");
require_once("db/controller/ProductTypeController.php");
require_once("db/controller/PublisherController.php");
require_once("db/controller/AuthorController.php");
require_once("db/controller/ProductController.php");
require_once("db/controller/LoginController.php");
require_once("db/controller/CartController.php");

$ContactController = new ContactController($conn);
$ProductTypeController = new ProductTypeController($conn);
$PublisherController = new PublisherController($conn);
$AuthorController = new AuthorController($conn);
$ProductController = new ProductController($conn);
$LoginController = new LoginController($conn);
$CartController = new CartController($conn);

$contacts = $ContactController->useContact();
$prdPreorder = 1; //สินค้าปกติ

$productsType10 = $ProductTypeController->getProductsType10($prdPreorder);
$publishers10 = $PublisherController->getPublishers10($prdPreorder);
$authors10 = $AuthorController->getAuthors10($prdPreorder);

// ตรวจสิบว่ามีสินค้า preOrder ไหม
$MenuPreorder = $ProductController->getProductsAll($MenuPreorder = 0);

// ส่วนลด
$productPercentDiscount = $SettingWebsiteController->getProductPercentDiscount();

if (!empty($_SESSION['mem_id'])) {
    $memId = $_SESSION['mem_id'];
    $useLoginEmployee = $LoginController->useLoginMember($memId);

    // จำนวนสินค้าในตะกร้า
    $cartItem = $CartController->getCartItemCount($memId);

    if (!$useLoginEmployee) {
        unset($_SESSION['mem_id']);
    }
}

?>


<header>
    <!-- header-top-area-start -->
    <div class="header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="language-area">
                        <ul>
                            <?php
                            foreach ($contacts as $contact) {
                                $ctDetail = $contact['ct_detail'];
                                $ctNameLink = $contact['ct_name_link'];
                                $icon = '';

                                if ($contact['ct_id'] == 1) {
                                    $icon = 'fa-facebook';
                                } elseif ($contact['ct_id'] == 2) {
                                    $icon = 'fa-youtube';
                                } elseif ($contact['ct_id'] == 3) {
                                    $icon = 'fa-twitter';
                                }

                                if ($icon) {
                                    echo '
                                        <li>
                                            <a href="' . $ctDetail . '" target="_blank">
                                                <i class="fa-brands ' . $icon . ' me-1"></i>
                                                ' . $ctNameLink . '
                                            </a>
                                        </li>';
                                }
                            }
                            ?>
                        </ul>

                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="account-area text-end">
                        <ul>
                            <!-- <li><a href="my-account.html">My Account</a></li> -->
                            <?php if (empty($_SESSION['mem_id'])) { ?>
                                <li><a href="register_form">สมัครสมาชิก</a></li>
                                <li><a href="login_form">เข้าสู่ระบบ</a></li>
                            <?php } else { ?>
                                <li><a><?php echo "ยินดีต้อนรับ : " . $useLoginEmployee['mem_fname'] . " " . $useLoginEmployee['mem_lname']; ?></a></li>
                                <li><a href="account_show">บัญชีของฉัน</a></li>
                                <li><a href="logout" onclick="confirmLogout(event)">ออกจากระบบ</a></li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- header-top-area-end -->

    <!-- header-mid-area-start -->
    <div class="header-mid-area ptb-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12">
                    <div class="logo-area text-center logo-xs-mrg">
                        <?php
                        $logo = false;
                        foreach ($settingsWebsite as $setting) {
                            if ($setting['st_id'] == 3) {
                                $logoDetail = $setting['st_detail'];
                                echo '
                                        <a href="">
                                            <img src="uploads/img_web_setting/' . $logoDetail . '" alt="logo"  style="height:80px">
                                        </a>
                                    ';
                                $logo = true;
                                break;
                            }
                        }
                        ?>

                    </div>
                </div>

                <div class="col-lg-6 col-md-5 col-12">
                    <div class="header-search">
                        <form action="search_show" method="get">
                            <input type="text" name="search" placeholder="ค้นหา ชื่อสินค้า, รหัสสินค้า, ISBN, ชื่อประเภทสินค้า, สำนักพิมพ์ หรือ ผู้แต่ง ได้ที่นี่" value="<?php if (isset($_GET['search'])) {
                                                                                                                                                                                echo $_GET['search'];
                                                                                                                                                                            } ?>">
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="my-cart">
                        <ul class="list-unstyled d-flex">
                            <li class="me-3">
                                <a href="cart"><i class="fa fa-shopping-cart mt-2"></i></a>
                                <?php if (!empty($_SESSION['mem_id']) && $cartItem) { ?>
                                    <span><?php echo number_format($cartItem) ?></span>
                                <?php } ?>
                            </li>
                            <?php if (!empty($_SESSION['mem_id'])) { ?>
                                <!-- <li>
                                    <a href="#">
                                        <img src="./uploads/img_member/<?php echo $useLoginEmployee['mem_profile'] ?>" style="width: 50px;" alt="">
                                    </a>
                                </li> -->
                            <?php } ?>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- header-mid-area-end -->

    <!-- main-menu-area-start -->
    <div class="main-menu-area d-md-none d-none d-lg-block sticky-header-1" id="header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="menu-area">
                        <nav>
                            <ul>
                                <li class="active"><a href="index">หน้าแรก</a></li>

                                <li><a href="products_show">สินค้าทั้งหมด</a> </li>

                                <li><a href="javascript:void(0)">ประเภทสินค้า<i class="fa fa-angle-down"></i></a>
                                    <?php if ($productsType10) { ?>
                                        <div class="sub-menu sub-menu-2">
                                            <ul>
                                                <?php foreach ($productsType10 as $row) { ?>
                                                    <?php
                                                    $originalId = $row["pty_id"];
                                                    $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                    ?>
                                                    <li><a href="products_show?ptyId=<?php echo $base64Encoded ?>"><?php echo $row['pty_name'] ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </li>

                                <li><a href="#">สำนักพิมพ์<i class="fa fa-angle-down"></i></a>
                                    <?php if ($publishers10) { ?>
                                        <div class="sub-menu sub-menu-2">
                                            <ul>
                                                <?php foreach ($publishers10 as $row) { ?>
                                                    <?php
                                                    $originalId = $row["pub_id"];
                                                    $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                    ?>
                                                    <li><a href="products_show?pubId=<?php echo $base64Encoded ?>"><?php echo $row['pub_name'] ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </li>
                                <li><a href="#">ผู้แต่ง<i class="fa fa-angle-down"></i></a>
                                    <?php if ($authors10) { ?>
                                        <div class="sub-menu sub-menu-2">
                                            <ul>
                                                <?php foreach ($authors10 as $author) { ?>
                                                    <?php
                                                    $originalId = $author["auth_id"];
                                                    $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                    ?>
                                                    <li><a href="products_show?authId=<?php echo $base64Encoded ?>"><?php echo $author['auth_name'] ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </li>
                                <?php if ($MenuPreorder) { ?>
                                    <li>
                                        <a href="products_preorder_show">พรีออเดอร์</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="safe-area">
                        <a href="products_promotions">ลดเริ่มต้น <?php echo $productPercentDiscount . " %" ?></a>
                    </div>
                    <div class="safe-area">
                        <a href="product_request_form">ตามหาหนังสือ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main-menu-area-end -->

    <!-- mobile-menu-area-start -->
    <div class="mobile-menu-area d-lg-none d-block fix">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mobile-menu">
                        <nav id="mobile-menu-active">
                            <ul id="nav">
                                <li class="active"><a href="index">หน้าแรก</a></li>

                                <li><a href="products_show">สินค้าทั้งหมด</a> </li>

                                <li><a href="javascript:void(0)">ประเภทสินค้า</i></a>
                                    <?php if ($productsType10) { ?>
                                        <div class="sub-menu sub-menu-2">
                                            <ul>
                                                <?php foreach ($productsType10 as $row) { ?>
                                                    <?php
                                                    $originalId = $row["pty_id"];
                                                    $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                    ?>
                                                    <li><a href="products_show?ptyId=<?php echo $base64Encoded ?>"><?php echo $row['pty_name'] ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </li>

                                <li><a href="#">สำนักพิมพ์</i></a>
                                    <?php if ($publishers10) { ?>
                                        <div class="sub-menu sub-menu-2">
                                            <ul>
                                                <?php foreach ($publishers10 as $row) { ?>
                                                    <?php
                                                    $originalId = $row["pub_id"];
                                                    $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                    ?>
                                                    <li><a href="products_show?pubId=<?php echo $base64Encoded ?>"><?php echo $row['pub_name'] ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </li>
                                <li><a href="#">ผู้แต่ง</i></a>
                                    <?php if ($authors10) { ?>
                                        <div class="sub-menu sub-menu-2">
                                            <ul>
                                                <?php foreach ($authors10 as $author) { ?>
                                                    <?php
                                                    $originalId = $author["auth_id"];
                                                    $base64Encoded   = encodeBase64ID($originalId, $salt1, $salt2);
                                                    ?>
                                                    <li><a href="products_show?authId=<?php echo $base64Encoded ?>"><?php echo $author['auth_name'] ?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </li>
                                <?php if ($MenuPreorder) { ?>
                                    <li>
                                        <a href="products_preorder_show">พรีออเดอร์</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- mobile-menu-area-end -->
</header>