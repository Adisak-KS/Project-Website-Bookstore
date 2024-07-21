<?php
$titlePage = "ออกจากระบบ";
session_start();

if (empty($_SESSION['emp_id'])) {
    header('Location: login_form');
    exit;
}

// ลบ session ทั้งหมด
session_unset();
// ทำลาย session
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('layouts/head.php') ?>
</head>

<body class="loading authentication-bg authentication-bg-pattern">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card bg-pattern">

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <div class="auth-logo">
                                    <a href="" class="logo logo-dark text-center">
                                        <span class="logo-lg">
                                            <?php
                                            $logo = false;
                                            foreach ($settingsWebsite as $setting) {
                                                if ($setting['st_id'] == 3) {
                                                    $logoDetail = $setting['st_detail'];
                                                    echo '
                                                        <img src="../uploads/img_web_setting/' . $logoDetail . '" alt="" style="height:40px" class="mx-auto" >
                                                    ';
                                                    $logo = true;
                                                    break;
                                                }
                                            }

                                            if (!$logo) {
                                                echo '
                                                    <img src="assets/images/logo-dark.png" alt="" height="22">
                                                ';
                                            }
                                            ?>
                                        </span>
                                    </a>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="mt-4">
                                    <div class="logout-checkmark">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                            <circle class="path circle" fill="none" stroke="#4bd396" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />
                                            <polyline class="path check" fill="none" stroke="#4bd396" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 " />
                                        </svg>
                                    </div>
                                </div>

                                <h3>See you again !</h3>

                                <p class="text-muted"> ออกจากระบบสำเร็จ </p>
                                <a href="login_form" class="btn btn-primary">เข้าสู่ระบบอีกครั้ง</a>
                            </div>

                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <?php require_once('layouts/vendor.php') ?>
</body>

</html>