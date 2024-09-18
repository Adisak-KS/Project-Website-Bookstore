<?php
$titlePage = "แก้ไขที่อยู่";

require_once("db/connectdb.php");
require_once("db/controller/MemberAddressController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$MemberAddressController = new MemberAddressController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header("Location: login_form");
    exit;
} else {
    $memId = $_SESSION['mem_id'];

    if (isset($_GET['id'])) {
        $_SESSION["base64Encoded"] = $_GET["id"];
        $base64Encoded =  $_SESSION["base64Encoded"];

        // ถอดรหัส Id
        $addrId = decodeBase64ID($base64Encoded, $salt1, $salt2);

        $detailMemberAddress = $MemberAddressController->getDetailMemberAddress($addrId, $memId);

        if (!$detailMemberAddress) {
            $_SESSION['error'] = "ไม่พบที่อยู่ที่คุณต้องการ";
            header("Location: account_address");
            exit;
        }
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
                                        <div class="tab-pane fade show active" role="tabpanel">
                                            <form id="formAddress" action="process/account_address_edit.php" method="post">
                                                <div class="account-details-form">
                                                    <div class="myaccount-content">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h5>แก้ไขที่อยู่</h5>
                                                        </div>

                                                        <div class="row">
                                                            <input type="hidden" class="form-control" name="mem_id" value="<?php echo $detailMemberAddress['mem_id'] ?>" readonly>
                                                            <input type="hidden" name="addr_id" value="<?php echo $addrId; ?>" readonly>
                                                            <div class="col-lg-12 ingle-input-item mb-3">
                                                                <label for="">ประเภทที่อยู่ <span class="text-danger">*</span></label>

                                                                <input class="form-check-input ms-3 me-1" type="radio" name="addr_type" id="1" value="1" <?php if ($detailMemberAddress['addr_type'] != 2) {
                                                                                                                                                                echo 'checked';
                                                                                                                                                            } ?>>
                                                                <label class="form-check-label me-2" for="flexRadioDefault1"> <i class="fa-solid fa-house"></i> บ้าน</label>

                                                                <input class="form-check-input ms-3 me-1" type="radio" name="addr_type" id="2" value="2" <?php if ($detailMemberAddress['addr_type'] == 2) {
                                                                                                                                                                echo 'checked';
                                                                                                                                                            } ?>>
                                                                <label class="form-check-label" for="flexRadioDefault2"><i class="fa-solid fa-building"></i> ที่ทำงาน </label>
                                                            </div>

                                                            <div class="col-lg-4 single-input-item mb-3">
                                                                <label for="fname" class="form-label">ชื่อจริง <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="addr_fname" placeholder="กรุณาระบุ ชื่อจริง" value="<?php echo $detailMemberAddress['addr_fname']; ?>" maxlength="50">
                                                            </div>
                                                            <div class="col-lg-4 single-input-item mb-3">
                                                                <label for="lname" class="form-label">นามสกุล <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="addr_lname" placeholder="กรุณาระบุ นามสกุล" value="<?php echo $detailMemberAddress['addr_lname']; ?>" maxlength="50">
                                                            </div>
                                                            <div class="col-lg-4 single-input-item mb-3">
                                                                <label for="phone" class="form-label">เบอร์โทร <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control" name="addr_phone" placeholder="กรุณาระบุ เบอร์โทรศัพท์" value="<?php echo $detailMemberAddress['addr_phone']; ?>">
                                                            </div>

                                                            <div class="col-lg-3 single-input-item mb-3">
                                                                <label for="province" class="form-label">จังหวัด <span class="text-danger">*</span></label>
                                                                <input type="hidden" id="province_name" name="province_name" value="<?php echo $detailMemberAddress['addr_province'] ?>" readonly>

                                                                <select class="form-select" id="province" name="province">
                                                                    <option selected>กรุณาระบุ จังหวัด</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 single-input-item mb-3">
                                                                <label for="district" class="form-label">อำเภอ/เขต <span class="text-danger">*</span></label>
                                                                <input type="hidden" id="district_name" name="district_name" value="<?php echo $detailMemberAddress['addr_district'] ?>" readonly>

                                                                <select class="form-select" id="district" name="district">
                                                                    <option selected>กรุณาระบุ อำเภอ/เขต</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3 single-input-item mb-3">
                                                                <label for="subdistrict" class="form-label">ตำบล/แขวง <span class="text-danger">*</span></label>
                                                                <input type="hidden" id="subdistrict_name" name="subdistrict_name" value="<?php echo $detailMemberAddress['addr_subdistrict'] ?>" readonly>

                                                                <select class="form-select" id="subdistrict" name="subdistrict">
                                                                    <option selected>กรุณาระบุ ตำบล/แขวง</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-lg-3 single-input-item mb-3">
                                                                <label for="zip_code" class="form-label">รหัสไปรษณีย์ <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control" id="zip_code" name="zip_code" placeholder="กรุณาระบุ รหัสไปราณีย์" readonly>
                                                            </div>
                                                            <div class="col-lg-12 single-input-item mb-3">
                                                                <label for="zip_code" class="form-label">รายละเอียดที่อยู่ <span class="text-danger">*</span></label>
                                                                <textarea name="addr_detail" class="form-control" placeholder="ระบุรายละเอียดที่อยู่ เช่น บ้านเลขที่ ถนน ซอย สถานที่ใกล้เคียง"><?php echo $detailMemberAddress['addr_phone']; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="col-lg-12 single-input-item mb-3">
                                                            <button type="submit" name="btn-edit" class="btn btn-sqr">
                                                                <i class="fa-solid fa-floppy-disk"></i>
                                                                บันทึกการแก้ไข
                                                            </button>
                                                            <a href="account_address" class="btn">
                                                                ยกเลิก
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
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

    <script>
        $(document).ready(function() {
            let provinceObject = $('#province');
            let districtObject = $('#district');
            let subdistrictObject = $('#subdistrict');

            let provinceNameInput = $('#province_name');
            let districtNameInput = $('#district_name');
            let subdistrictNameInput = $('#subdistrict_name');

            // Set initial dropdown options
            provinceObject.html('<option selected>กรุณาระบุ จังหวัด</option>');
            districtObject.html('<option selected>กรุณาระบุ อำเภอ</option>');
            subdistrictObject.html('<option selected>ตำบล/แขวง</option>');
            $('#zip_code').val('');

            // Load province data
            $.get('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_province.json', function(data) {
                let result = JSON.parse(data);
                let addrProvince = '<?php echo $detailMemberAddress['addr_province'] ?>';

                $.each(result, function(index, item) {
                    let option = $('<option></option>').val(item.id).html(item.name_th).data('name_th', item.name_th);
                    provinceObject.append(option);

                    // Check if the current item matches the addrProvince and select it
                    if (item.name_th === addrProvince) {
                        provinceObject.val(item.id);
                        provinceObject.trigger('change');
                    }
                });
            });

            //ค้นหาอำเภอในจังหวัดที่เลือก
            provinceObject.on('change', function() {
                let selectedProvince = $(this).find('option:selected').data('name_th');
                provinceNameInput.val(selectedProvince);
                let provinceId = $(this).val();

                districtObject.html('<option selected>กรุณาระบุ อำเภอ</option>');
                subdistrictObject.html('<option selected>ตำบล/แขวง</option>');
                $('#zip_code').val('');

                $.get('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_amphure.json', function(data) {
                    let result = JSON.parse(data);
                    let addrDistrict = '<?php echo $detailMemberAddress['addr_district'] ?>';

                    $.each(result, function(index, item) {
                        if (item.province_id == provinceId) {
                            let districtOption = $('<option></option>').val(item.id).html(item.name_th).data('name_th', item.name_th);
                            districtObject.append(districtOption);

                            // Check if the current item matches the addrDistrict and select it
                            if (item.name_th === addrDistrict) {
                                districtObject.val(item.id);
                                districtObject.trigger('change');
                            }
                        }
                    });
                });
            });


            //ค้นหาตำบลในอำเภอที่เลือก
            districtObject.on('change', function() {
                let selectedDistrict = $(this).find('option:selected').data('name_th');
                districtNameInput.val(selectedDistrict);
                let districtId = $(this).val();

                subdistrictObject.html('<option selected>ตำบล/แขวง</option>');
                $('#zip_code').val('');

                $.get('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_tambon.json', function(data) {
                    let result = JSON.parse(data);
                    let addrSubdistrict = '<?php echo $detailMemberAddress['addr_subdistrict'] ?>';

                    $.each(result, function(index, item) {
                        if (item.amphure_id == districtId) {
                            let subdistrictOption = $('<option></option>').val(item.id).html(item.name_th).data('name_th', item.name_th);
                            subdistrictObject.append(subdistrictOption);

                            // Check if the current item matches the addrSubdistrict and select it
                            if (item.name_th === addrSubdistrict) {
                                subdistrictObject.val(item.id);
                                subdistrictObject.trigger('change');
                            }
                        }
                    });
                });
            });

            //ค้นหารหัสไปรษณีย์
            subdistrictObject.on('change', function() {
                let selectedSubdistrict = $(this).find('option:selected').data('name_th');
                subdistrictNameInput.val(selectedSubdistrict);
                let subdistrictId = $(this).val();

                $.get('https://raw.githubusercontent.com/kongvut/thai-province-data/master/api_tambon.json', function(data) {
                    let result = JSON.parse(data);
                    $.each(result, function(index, item) {
                        if (item.id == subdistrictId) {
                            $('#zip_code').val(item.zip_code);
                        }
                    });
                });
            });

        });
    </script>

</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>