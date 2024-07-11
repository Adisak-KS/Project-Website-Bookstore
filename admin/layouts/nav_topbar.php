<?php

require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/LoginController.php');

$LoginController = new LoginController($conn);

$empId = $_SESSION['emp_id'];
$useLoginEmployee = $LoginController->useLoginEmployees($empId);


if (!$useLoginEmployee || empty($empId)) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ ก่อนใช้งาน";
    header("Location: login_form");
    exit;
}
?>
<!--  ========== topbar Start ========== -->
<div class="navbar-custom">
    <ul class="list-unstyled topnav-menu float-end mb-0">

        <li class="dropdown notification-list topbar-dropdown">
            <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">

                <?php if (empty($useLoginEmployee['emp_profile'])) { ?>
                    <img src="../uploads/img_employees/default.png" alt="user-image" class="rounded-circle">
                <?php } else { ?>
                    <img src="../uploads/img_employees/<?php echo $useLoginEmployee['emp_profile'] ?>" alt="user-image" class="rounded-circle">
                <?php } ?>
                <span class="pro-user-name ms-1">
                    <?php echo "@" . $useLoginEmployee['emp_username'] ?>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">ยินดีต้อนรับ !</h6>
                </div>

                <!-- item-->
                <a href="account_show" class="dropdown-item notify-item">
                    <i class="fa-solid fa-user-gear"></i>
                    <span>บัญชีของฉัน</span>
                </a>
                <div class="dropdown-divider"></div>

                <!-- item-->
                <a href="logout" class="dropdown-item notify-item" onclick="confirmLogout(event)">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>ออกจากระบบ</span>
                </a>

            </div>
        </li>

        <li class="dropdown notification-list">
            <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                <i class="fa-solid fa-gear"></i>
            </a>
        </li>

    </ul>

    <!-- LOGO -->
    <div class="logo-box">
        <?php
        $logo = false;
        foreach ($settingsWebsite as $setting) {
            if ($setting['st_id'] == 3) {
                $logoDetail = $setting['st_detail'];
                echo '
                            <a href="index" class="d-flex justify-content-center mt-2">
                                <span class="logo-lg">
                                    <img class="img-fluid" style="max-height:30px" src="../uploads/img_web_setting/' . $logoDetail . '" alt="">
                                </span> 
                            </a>
                        ';
                $logo = true;
                break;
            }
        }

        if (!$logo) {
            echo '
                        <a href="index" class="logo logo-dark text-center">
                            <span class="logo-sm">
                                <img src="../uploads/img_web_setting/default_admin_logo-sm.png" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="../uploads/img_web_setting/default_admin_logo-dark.png" alt="" height="16">
                            </span> 
                        </a>
                     ';
        }
        ?>
    </div>


    <ul class="list-unstyled topnav-menu topnav-menu-left mb-0">
        <li>
            <button class="button-menu-mobile disable-btn waves-effect">
                <i class="fa-solid fa-bars"></i>
            </button>
        </li>

        <li>
            <h4 class="page-title-main"><?php echo $titlePage ?></h4>
        </li>

    </ul>

    <div class="clearfix"></div>

</div>
<!--  ========== topbar End ========== -->