<?php
// My Function
// require_once("includes/functions.php");
require_once('db/connectdb.php');
require_once('db/controller/SettingWebsiteController.php');
require_once('includes/salt.php');
require_once('includes/functions.php');

$SettingWebsiteController = new SettingWebsiteController($conn);
$settingsWebsite = $SettingWebsiteController->useSettingsWebsite();
?>


<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php
// ชื่อเว็บไซต์ที่ Tab (st_id = 1)
$websiteName = "ชื่อเว็บไซต์"; // ค่าเริ่มต้นถ้าไม่พบข้อมูล
$faviconUrl = "uploads/img_web_setting/default_favicon.ico"; // URL ของ favicon ค่าเริ่มต้น

// ค้นหาข้อมูลจาก $settingsWebsite เพียงครั้งเดียว
foreach ($settingsWebsite as $setting) {
    if ($setting['st_id'] == 1) {
        $websiteName = $setting['st_detail'];
    } elseif ($setting['st_id'] == 2) {
        $faviconUrl = "uploads/img_web_setting/" . $setting['st_detail'];
    }
}

// แสดงผลใน <title>
echo "<title>$titlePage | $websiteName</title>";
// แสดง favicon
echo '<link rel="shortcut icon" href="' . $faviconUrl . '" type="image/x-icon">';
?>


<!-- all css here -->

<!-- bootstrap v3.3.6 css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<!-- animate css -->
<!-- <link rel="stylesheet" href="css/animate.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- meanmenu css -->
<link rel="stylesheet" href="css/meanmenu.min.css">

<!-- owl.carousel css -->
<!-- <link rel="stylesheet" href="css/owl.carousel.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- font-awesome css -->
<!-- <link rel="stylesheet" href="css/font-awesome.min.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- flexslider.css-->
<!-- <link rel="stylesheet" href="css/flexslider.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexslider/2.7.2/flexslider.min.css" integrity="sha512-c7jR/kCnu09ZrAKsWXsI/x9HCO9kkpHw4Ftqhofqs+I2hNxalK5RGwo/IAhW3iqCHIw55wBSSCFlm8JP0sw2Zw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- chosen.min.css-->
<!-- <link rel="stylesheet" href="css/chosen.min.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- style css -->
<link rel="stylesheet" href="css/style.css">

<!-- responsive css -->
<link rel="stylesheet" href="css/responsive.css">

<!-- modernizr css -->
<script src="https://cdn.jsdelivr.net/npm/modernizr@3.8.3/lib/cli.min.js"></script>

