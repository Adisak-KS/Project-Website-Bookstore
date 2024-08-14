<?php
$titlePage = "บัญชีของฉัน";

require_once("db/connectdb.php");
require_once("db/controller/MemberController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$MemberController = new MemberController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header("Location: login_form");
    exit;
} else {
    $memId = $_SESSION['mem_id'];

    $memProfile = $MemberController->getDetailAccountMember($memId);

    if (!$memProfile) {
        unset($_SESSION['mem_id']);
        $_SESSION['error'] = "ไม่พบข้อมูลบัญชี";
        header("Location: index");
        exit;
    }
}


?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php require_once("layouts/head.php"); ?>
</head>

<body class="cart">
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
                            <li><a href="index">หน้าแรก</a></li>
                            <li><a href="javascript:void(0)" class="active">บัญชีของฉัน</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbs-area-end -->

    <!-- entry-header-area-start -->
    <div class="entry-header-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="entry-header-title">
                        <h2>บัญชีของฉัน</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- entry-header-area-end -->

    <!-- my account wrapper start -->
    <div class="my-account-wrapper mb-70">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- My Account Page Start -->
                        <div class="myaccount-page-wrapper">
                            <!-- My Account Tab Menu Start -->
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <?php require_once("layouts/nav_menu_account.php") ?>
                                </div>
                                <!-- My Account Tab Menu End -->

                                <!-- My Account Tab Content Start -->
                                <div class="col-lg-9 col-md-8">
                                    <div class="tab-content" id="myaccountContent">
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h5>ข้อมูลส่วนตัว</h5>
                                                <div class="account-details-form">
                                                    <form id="formUpdateProfile" action="process/account_profile_edit.php" Method="post" enctype="multipart/form-data">
                                                        <div class="row">
                                                            <div class="col-lg-12 text-start">
                                                                <div class="single-input-item mb-3">
                                                                    <label for="profile" class="form-label">โปรไฟล์</label>
                                                                    <div class="d-flex justify-content-center">
                                                                        <div class="col-lg-6">
                                                                            <div class="text-center">
                                                                                <img class="rounded-circle mx-auto d-block img-fluid border" id="profile" src="uploads/img_member/<?php echo $memProfile['mem_profile']; ?>" style="height:150px; width:150px; object-fit:cover" alt="โปรเฟล์">
                                                                                <input type="hidden" name="old_profile" value="<?php echo $memProfile['mem_profile']; ?>" readonly>
                                                                            </div>
                                                                            <br>
                                                                            <label for="" class="form-label">รูปภาพโปรไฟล์ใหม่ :</label><span class="text-danger"> (ขนาดไฟล์ไม่เกิน 2 MB)</span>
                                                                            <input class="form-control" name="new_profile" id="newProfile" type="file" accept="image/png, image/jpeg">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <div class="single-input-item mb-3">
                                                                    <label for="mem_id" class="form-label">รหัสสมาชิก</label>
                                                                    <input type="text" class="form-control" name="mem_id" value="<?php echo $memProfile['mem_id'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item mb-3">
                                                                    <label for="coin" class="form-label">เหรีญที่มี</label>
                                                                    <input type="number" class="form-control" name="coin" placeholder="เหรีญที่มี" value="<?php echo $memProfile['mem_coin']; ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item mb-3">
                                                                    <label for="fname" class="form-label">ชื่อจริง</label>
                                                                    <input type="text" class="form-control" name="fname" placeholder="กรุณาระบุ ชื่อจริง" value="<?php echo $memProfile['mem_fname']; ?>"  maxlength="50">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item mb-3">
                                                                    <label for="lname" class="form-label">นามสกุล</label>
                                                                    <input type="text" class="form-control" name="lname" placeholder="กรุณาระบุ นามสกุล" value="<?php echo $memProfile['mem_lname']; ?>"  maxlength="50">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="single-input-item mb-3">
                                                            <label for="username" class="form-label">ชื่อผู้ใช้งาน (Username)</label>
                                                            <input type="text" class="form-control" name="username" placeholder="กรุณาระบุ ชื่อผู้ใช้งาน (Username)" value="<?php echo $memProfile['mem_username'] ?>" maxlength="50">
                                                        </div>
                                                        <div class="single-input-item mb-3">
                                                            <label for="email" class="form-label">อีเมล</label>
                                                            <input type="email" class="form-control" name="email" placeholder="กรุณาระบุ อีเมล" value="<?php echo $memProfile['mem_email']; ?>"  maxlength="100">
                                                        </div>
                                                        <hr>
                                                        <div class="single-input-item mb-3">
                                                            <button type="submit" name="btn-edit" class="btn btn-sqr">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                                บันทึกการแก้ไข
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Single Tab Content End -->

                                    <!-- Single Tab Content Start -->
                                    <!-- <div class="tab-pane fade" id="orders" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h5>Orders</h5>
                                                <div class="myaccount-table table-responsive text-center">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Order</th>
                                                                <th>Date</th>
                                                                <th>Status</th>
                                                                <th>Total</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Aug 22, 2018</td>
                                                                <td>Pending</td>
                                                                <td>$3000</td>
                                                                <td><a href="cart.html" class="btn btn-sqr">View</a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>July 22, 2018</td>
                                                                <td>Approved</td>
                                                                <td>$200</td>
                                                                <td><a href="cart.html" class="btn btn-sqr">View</a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>June 12, 2017</td>
                                                                <td>On Hold</td>
                                                                <td>$990</td>
                                                                <td><a href="cart.html" class="btn btn-sqr">View</a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div> -->
                                    <!-- Single Tab Content End -->

                                    <!-- Single Tab Content Start -->
                                    <!-- <div class="tab-pane fade" id="download" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h5>Downloads</h5>
                                                <div class="myaccount-table table-responsive text-center">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Product</th>
                                                                <th>Date</th>
                                                                <th>Expire</th>
                                                                <th>Download</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Haven - Free Real Estate PSD Template</td>
                                                                <td>Aug 22, 2018</td>
                                                                <td>Yes</td>
                                                                <td><a href="#" class="btn btn-sqr"><i class="fa fa-cloud-download"></i>
                                                                        Download File</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>HasTech - Profolio Business Template</td>
                                                                <td>Sep 12, 2018</td>
                                                                <td>Never</td>
                                                                <td><a href="#" class="btn btn-sqr"><i class="fa fa-cloud-download"></i>
                                                                        Download File</a></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div> -->
                                    <!-- Single Tab Content End -->

                                    <!-- Single Tab Content Start -->
                                    <!-- <div class="tab-pane fade" id="payment-method" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h5>Payment Method</h5>
                                                <p class="saved-message">You Can't Saved Your Payment Method yet.</p>
                                            </div>
                                        </div> -->
                                    <!-- Single Tab Content End -->

                                    <!-- Single Tab Content Start -->
                                    <!-- <div class="tab-pane fade" id="address-edit" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h5>Billing Address</h5>
                                                <address>
                                                    <p><strong>Erik Jhonson</strong></p>
                                                    <p>1355 Market St, Suite 900 <br>
                                                        San Francisco, CA 94103</p>
                                                    <p>Mobile: (123) 456-7890</p>
                                                </address>
                                                <a href="#" class="btn btn-sqr"><i class="fa fa-edit"></i>
                                                    Edit Address</a>
                                            </div>
                                        </div> -->
                                    <!-- Single Tab Content End -->

                                    <!-- Single Tab Content Start -->
                                    <!-- <div class="tab-pane fade" id="account-info" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h5>Account Details</h5>
                                                <div class="account-details-form">
                                                    <form action="#">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="first-name" class="required">First
                                                                        Name</label>
                                                                    <input type="text" id="first-name" placeholder="First Name" />
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="last-name" class="required">Last
                                                                        Name</label>
                                                                    <input type="text" id="last-name" placeholder="Last Name" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="single-input-item">
                                                            <label for="display-name" class="required">Display Name</label>
                                                            <input type="text" id="display-name" placeholder="Display Name" />
                                                        </div>
                                                        <div class="single-input-item">
                                                            <label for="email" class="required">Email Addres</label>
                                                            <input type="email" id="email" placeholder="Email Address" />
                                                        </div>
                                                        <fieldset>
                                                            <legend>Password change</legend>
                                                            <div class="single-input-item">
                                                                <label for="current-pwd" class="required">Current
                                                                    Password</label>
                                                                <input type="password" id="current-pwd" placeholder="Current Password" />
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="single-input-item">
                                                                        <label for="new-pwd" class="required">New
                                                                            Password</label>
                                                                        <input type="password" id="new-pwd" placeholder="New Password" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <div class="single-input-item">
                                                                        <label for="confirm-pwd" class="required">Confirm
                                                                            Password</label>
                                                                        <input type="password" id="confirm-pwd" placeholder="Confirm Password" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                        <div class="single-input-item">
                                                            <button class="btn btn-sqr">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>  -->
                                    <!-- Single Tab Content End -->
                                </div>
                            </div> <!-- My Account Tab Content End -->
                        </div>
                    </div> <!-- My Account Page End -->
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- my account wrapper end -->

    <!-- footer-area-start -->
    <?php require_once('layouts/nav_footer.php') ?>

    <!-- all js here -->
    <?php require_once("layouts/vendor.php") ?>
    <!-- preview New Profile, check file type, file size  -->
    <script>
        document.getElementById('newProfile').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            const maxSize = 2 * 1024 * 1024; // 2 MB in bytes

            if (file && allowedTypes.includes(file.type) && file.size <= maxSize) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile').src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                // Reset the input
                event.target.value = '';
                // Reset to the original image
                document.getElementById('profile').src = 'uploads/img_member/<?php echo $memProfile['mem_profile']; ?>';
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'คำเตือน',
                        text: 'ต้องเป็นไฟล์ .png .jpg .jpeg เท่านั้น',
                    });
                }

                // Show an alert if the file is not valid
                if (allowedTypes.includes(file.type) && file.size > maxSize) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'คำเตือน',
                        text: 'ขนาดไฟล์เกิน 2 MB',
                    });
                }
            }
        });
    </script>
</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>