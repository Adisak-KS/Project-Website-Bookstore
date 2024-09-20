<?php
$titlePage  = "ติดต่อเรา";
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="contact">
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
                            <li><a href="javascript:void()" class="active">ติดต่อเรา</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs-area-end -->

    <?php
    $googleMap = '';
    foreach ($contacts as $contact) {
        if ($contact['ct_id'] == 7) {
            $googleMap = $contact['ct_detail'];
            break;
        }
    }
    ?>

    <?php if ($googleMap) { ?>
        <!-- googleMap-area-start -->
        <div class="map-area mb-70">
            <div class="container">
                <div class="col-lg-12">
                    <div id="googleMap" class="ratio ratio-16x9 w-100">
                        <?php echo $googleMap ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- googleMap-end -->

    <!-- contact-area-start -->
    <div class="contact-area mb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="contact-info">
                        <h3>ติดต่อเราได้ที่ :</h3>
                        <ul>
                            <?php
                            $details = [
                                'address' => '',
                                'phone' => '',
                                'email' => ''
                            ];

                            foreach ($contacts as $contact) {
                                switch ($contact['ct_id']) {
                                    case 6:
                                        $details['address'] = $contact['ct_detail'];
                                        break;
                                    case 5:
                                        $details['phone'] = $contact['ct_detail'];
                                        break;
                                    case 4:
                                        $details['email'] = $contact['ct_detail'];
                                        break;
                                }
                            }

                            if ($details['address']) {
                                echo '
                                 <li>
                                    <i class="fa fa-map-marker"></i>
                                    <span>ที่อยู่ : </span>'
                                    . $details['address'] . '
                                </li>
                                ';
                            }
                            if ($details['phone']) {
                                echo '
                                <li>
                                   <i class="fa-solid fa-phone"></i>
                                   <span>เบอร์โทร : </span>'
                                    . $details['phone'] . '
                               </li>
                               ';
                            }
                            if ($details['email']) {
                                echo '
                                <li>
                                   <i class="fa-solid fa-envelope"></i>
                                   <span>อีเมล : </span>'
                                    . $details['email'] . '
                               </li>
                               ';
                            }
                            ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- contact-area-end -->

    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>
</body>

</html>