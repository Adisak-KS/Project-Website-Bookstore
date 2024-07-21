<?php
$titlePage = "ระงับการใช้งาน";

require_once("../db/connectdb.php");
require_once("../db/controller/LoginController.php");
$LoginController = new LoginController($conn);

$Id = $_SESSION["emp_id"];
$checkStatus = $LoginController->checkStatusBlockedEmployees($Id);

if ($checkStatus && $checkStatus['emp_status'] == 1) {
    header('Location: index.php');
    exit;
}

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
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="text-center">
                        <a href="index" class="logo">
                            <img src="assets/images/logo-dark.png" alt="" height="22" class="logo-light mx-auto">
                        </a>
                        <p class="text-muted mt-2 mb-4">ชื่อเว็บไซต์</p>
                    </div>
                    <div class="card">

                        <div class="card-body p-4">

                            <div class="text-center">
                                <h1 class="text-error text-danger">BLOCKED</h1>
                                <h3 class="mt-3 mb-2">บัญชีผู้ใช้ถูกระงับการใช้งาน</h3>
                                <p class="text-muted mb-3">โปรดติดต่อผู้ดูแลระบบ หากต้องการใช้งาน</p>

                                <a href="logout" class="btn btn-danger waves-effect waves-light">
                                    <i class="fa-solid fa-right-from-bracket fa-rotate-180 me-1"></i>
                                    <span>ออกจากระบบ</span>
                                </a>
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
<?php require_once('../includes/sweetalert2.php') ?>