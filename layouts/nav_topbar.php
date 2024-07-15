<?php
require_once("db/connectdb.php");
require_once("db/controller/ContactController.php");

$ContactController = new ContactController($conn);

$contacts = $ContactController->useContact();
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
                                <li class="text-white"><small>ยินดีต้อนรับ : ชื่อ นามสกุล</small></li>
                                <li><a href="my-account.html">บัญชีของฉัน</a></li>
                                <li><a href="logout">ออกจากระบบ</a></li>
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
                    <div class="logo-area text-start logo-xs-mrg">
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
                        <form action="#">
                            <input type="text" placeholder="ค้นหาสินค้า..." />
                            <a href="#"><i class="fa fa-search"></i></a>
                        </form>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-12">
                    <div class="my-cart">
                        <ul class="list-unstyled d-flex">
                            <li class="me-3">
                                <a href="#"><i class="fa fa-shopping-cart"></i></a>
                                <span>2</span>
                            </li>
                            <?php if (!empty($_SESSION['mem_id'])) { ?>
                                <li>
                                    <a href="#">
                                        <img src="./uploads/img_member/default.png" style="width: 50px;" alt="">
                                    </a>
                                </li>
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

                                <li><a href="#">ประเภทสินค้า<i class="fa fa-angle-down"></i></a>
                                    <div class="sub-menu sub-menu-2">
                                        <ul>
                                            <li><a href="blog.html">blog</a></li>
                                            <li><a href="blog-details.html">blog-details</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li><a href="#">สำนักพิมพ์<i class="fa fa-angle-down"></i></a>
                                    <div class="sub-menu sub-menu-2">
                                        <ul>
                                            <li><a href="blog.html">blog</a></li>
                                            <li><a href="blog-details.html">blog-details</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li><a href="#">ผู้แต่ง<i class="fa fa-angle-down"></i></a>
                                    <div class="sub-menu sub-menu-2">
                                        <ul>
                                            <li><a href="blog.html">blog</a></li>
                                            <li><a href="blog-details.html">blog-details</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li><a href="preorder_show">พรีออเดอร์</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="safe-area">
                        <a href="product_sales">ลดเริ่มต้น 50 %</a>
                    </div>
                    <div class="safe-area">
                        <a href="find_product">ตามหาหนังสือ</a>
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
                                <li><a href="index">หน้าแรก</a></li>

                                <li><a href="product-details.html">Book</a>
                                    <ul>
                                        <li><a href="shop.html">Tops & Tees</a></li>
                                        <li><a href="shop.html">Polo Short Sleeve</a></li>
                                        <li><a href="shop.html">Graphic T-Shirts</a></li>
                                        <li><a href="shop.html">Jackets & Coats</a></li>
                                        <li><a href="shop.html">Fashion Jackets</a></li>
                                        <li><a href="shop.html">Crochet</a></li>
                                        <li><a href="shop.html">Sleeveless</a></li>
                                        <li><a href="shop.html">Stripes</a></li>
                                        <li><a href="shop.html">Sweaters</a></li>
                                        <li><a href="shop.html">hoodies</a></li>
                                        <li><a href="shop.html">Heeled sandals</a></li>
                                        <li><a href="shop.html">Polo Short Sleeve</a></li>
                                        <li><a href="shop.html">Flat sandals</a></li>
                                        <li><a href="shop.html">Short Sleeve</a></li>
                                        <li><a href="shop.html">Long Sleeve</a></li>
                                        <li><a href="shop.html">Polo Short Sleeve</a></li>
                                        <li><a href="shop.html">Sleeveless</a></li>
                                        <li><a href="shop.html">Graphic T-Shirts</a></li>
                                        <li><a href="shop.html">Hoodies</a></li>
                                        <li><a href="shop.html">Jackets</a></li>
                                    </ul>
                                </li>
                                <li><a href="product-details.html">Audio books</a>
                                    <ul>
                                        <li><a href="shop.html">Tops & Tees</a></li>
                                        <li><a href="shop.html">Sweaters</a></li>
                                        <li><a href="shop.html">Hoodies</a></li>
                                        <li><a href="shop.html">Jackets & Coats</a></li>
                                        <li><a href="shop.html">Long Sleeve</a></li>
                                        <li><a href="shop.html">Short Sleeve</a></li>
                                        <li><a href="shop.html">Polo Short Sleeve</a></li>
                                        <li><a href="shop.html">Sleeveless</a></li>
                                        <li><a href="shop.html">Sweaters</a></li>
                                        <li><a href="shop.html">Hoodies</a></li>
                                        <li><a href="shop.html">Wedges</a></li>
                                        <li><a href="shop.html">Vests</a></li>
                                        <li><a href="shop.html">Polo Short Sleeve</a></li>
                                        <li><a href="shop.html">Sleeveless</a></li>
                                        <li><a href="shop.html">Graphic T-Shirts</a></li>
                                        <li><a href="shop.html">Hoodies</a></li>
                                    </ul>
                                </li>
                                <li><a href="product-details.html">children’s books</a>
                                    <ul>
                                        <li><a href="shop.html">Shirts</a></li>
                                        <li><a href="shop.html">Florals</a></li>
                                        <li><a href="shop.html">Crochet</a></li>
                                        <li><a href="shop.html">Stripes</a></li>
                                        <li><a href="shop.html">Shorts</a></li>
                                        <li><a href="shop.html">Dresses</a></li>
                                        <li><a href="shop.html">Trousers</a></li>
                                        <li><a href="shop.html">Jeans</a></li>
                                        <li><a href="shop.html">Heeled sandals</a></li>
                                        <li><a href="shop.html">Flat sandals</a></li>
                                        <li><a href="shop.html">Wedges</a></li>
                                        <li><a href="shop.html">Ankle boots</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">blog</a>
                                    <ul>
                                        <li><a href="blog.html">Blog</a></li>
                                        <li><a href="blog-details.html">blog-details</a></li>
                                    </ul>
                                </li>
                                <li><a href="product-details.html">Page</a>
                                    <ul>
                                        <li><a href="shop.html">shop</a></li>
                                        <li><a href="shop-list.html">shop list view</a></li>
                                        <li><a href="product-details.html">product-details</a></li>
                                        <li><a href="product-details-affiliate.html">product-affiliate</a></li>
                                        <li><a href="blog.html">blog</a></li>
                                        <li><a href="blog-details.html">blog-details</a></li>
                                        <li><a href="contact.html">contact</a></li>
                                        <li><a href="about.html">about</a></li>
                                        <li><a href="login.html">login</a></li>
                                        <li><a href="register.html">register</a></li>
                                        <li><a href="my-account.html">my-account</a></li>
                                        <li><a href="cart.html">cart</a></li>
                                        <li><a href="compare.html">compare</a></li>
                                        <li><a href="checkout.html">checkout</a></li>
                                        <li><a href="wishlist.html">wishlist</a></li>
                                        <li><a href="404.html">404 Page</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- mobile-menu-area-end -->
</header>