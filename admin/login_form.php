<?php
$titlePage = "เข้าสู่ระะบบ";
require_once("../db/connectdb.php");

if (!empty($_SESSION['emp_id'])) {
    header('Location: index');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once('layouts/head.php') ?>
</head>

<body class="loading authentication-bg authentication-bg-pattern">

    <div class="account-pages my-5">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="text-center">
                        <a href="">
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
                                    <img src="assets/images/logo-dark.png" alt="" height="22" class="mx-auto">
                                ';
                            }
                            ?>
                        </a>
                        <p class="text-muted mt-2 mb-4"><?php echo $websiteName; ?></p>

                    </div>
                    <div class="card">
                        <div class="card-body p-4">

                            <div class="text-center mb-4">
                                <h4 class="text-uppercase mt-0">เข้าสู่ระบบ สำหรับพนักงาน</h4>
                            </div>

                            <form id="formUser" action="process/login_chk" method="post">
                                <div class="mb-3">
                                    <label for="username" class="form-label">ชื่อผู้ใช้งาน หรือ อีเมล : </label><span class="text-danger">*</span>
                                    <input class="form-control" name="username_email" type="text" placeholder="กรุณาระบุ ชื่อผู้ใช้หรืออีเมลล">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">รหัสผ่าน : </label><span class="text-danger">*</span>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" placeholder="ระบุ รหัสผ่าน" maxlength="255">
                                        <button class="btn btn-outline-secondary password-toggle" type="button">
                                            <i class="fas fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkbox-signin">
                                        <label class="form-check-label" for="checkbox-signin">จดจำฉัน</label>
                                    </div>
                                </div> -->
                                <hr>
                                <div class="mb-3 d-grid text-center">
                                    <button class="btn btn-primary" name="btn-login" type="submit"> เข้าสู่ระบบ </button>
                                </div>
                            </form>

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

    <!-- Vendor -->
    <?php require_once('layouts/vender.php') ?>

</body>

</html>
<?php require_once('../includes/sweetalert2.php') ?>