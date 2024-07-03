 <?php
    // My Function
    require_once("includes/functions.php");
    require_once(__DIR__ . '/../../db/connectdb.php');
    require_once(__DIR__ . '/../../db/controller/SettingWebsiteController.php');

    $SettingWebsiteController = new SettingWebsiteController($conn);
    $settingsWebsite = $SettingWebsiteController->useSettingsWebsite();
    ?>

 <!--  ========== Head ========== -->
 <meta charset="utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
 <meta content="Coderthemes" name="author" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge" />

 <?php
    // ชื่อเว็บไซต์ที่ Tab (st_id = 1)
    $websiteName = "ชื่อเว็บไซต์"; // ค่าเริ่มต้นถ้าไม่พบข้อมูล
    $faviconUrl = "../uploads/img_web_setting/default_favicon.ico"; // URL ของ favicon ค่าเริ่มต้น

    // ค้นหาข้อมูลจาก $settingsWebsite เพียงครั้งเดียว
    foreach ($settingsWebsite as $setting) {
        if ($setting['st_id'] == 1) {
            $websiteName = $setting['st_detail'];
        } elseif ($setting['st_id'] == 2) {
            $faviconUrl = "../uploads/img_web_setting/" . $setting['st_detail'];
        }
    }

    // แสดงผลใน <title>
    echo "<title>$titlePage | $websiteName</title>";
    // แสดง favicon
    echo '<link rel="shortcut icon" href="' . $faviconUrl . '" type="image/x-icon">';
    ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
 <!-- icons -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

 <!-- Data Table  -->
 <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
 <link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
 <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.min.css" rel="stylesheet">
 <link href="https://cdn.datatables.net/searchpanes/2.3.1/css/searchPanes.bootstrap5.min.css" rel="stylesheet">

 <!-- CKEditor  -->
 <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css">

 
 <!-- App css -->
 <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
 <link rel="stylesheet" href="assets/css/style.css">