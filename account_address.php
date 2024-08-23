<?php
$titlePage = "จัดการที่อยู่";

require_once("db/connectdb.php");
require_once("db/controller/MemberController.php");
require_once("db/controller/MemberAddressController.php");
require_once("includes/salt.php");
require_once("includes/functions.php");

$MemberController = new MemberController($conn);
$MemberAddressController = new MemberAddressController($conn);

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

    $memberAddress = $MemberAddressController->getMemberAddress($memId);
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
                                            <div class="myaccount-content">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5>ที่อยู่ของท่าน</h5>
                                                    <?php
                                                    $total_address = count($memberAddress);
                                                    if ($total_address >= 3) {
                                                    ?>
                                                        <button class="btn btn-sqr mb-3" disabled>ที่อยู่มีได้สูงสุด 3 รายการ</button>

                                                    <?php } else { ?>
                                                        <button href="#" class="btn btn-sqr mb-3" data-bs-toggle="modal" data-bs-target="#addAddrModal">
                                                            <i class="fa-regular fa-square-plus"></i>
                                                            เพิ่มที่อยู่ใหม่
                                                        </button>
                                                    <?php } ?>
                                                </div>

                                                <!-- Modal -->
                                                <div class="modal fade" id="addAddrModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <form id="formAddress" action="process/account_address_add.php" method="post">
                                                        <div class="modal-dialog  modal-xl modal-dialog-scrollable">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                        เพิ่มที่อยู่ใหม่
                                                                    </h1>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="account-details-form">
                                                                        <div class="row">
                                                                            <input type="hidden" class="form-control" name="mem_id" value="<?php echo $memProfile['mem_id'] ?>" readonly>
                                                                            <input type="hidden" name="total_address" value="<?php echo $total_address; ?>" readonly>
                                                                            <div class="col-lg-12 ingle-input-item mb-3">
                                                                                <label for="">ประเภทที่อยู่</label>

                                                                                <input class="form-check-input ms-3 me-1" type="radio" name="addr_type" id="1" value="1" checked>
                                                                                <label class="form-check-label me-2" for="flexRadioDefault1"> <i class="fa-solid fa-house"></i> บ้าน</label>

                                                                                <input class="form-check-input ms-3 me-1" type="radio" name="addr_type" id="2" value="2">
                                                                                <label class="form-check-label" for="flexRadioDefault2"><i class="fa-solid fa-building"></i> ที่ทำงาน </label>
                                                                            </div>

                                                                            <div class="col-lg-4 single-input-item mb-3">
                                                                                <label for="fname" class="form-label">ชื่อจริง</label>
                                                                                <input type="text" class="form-control" name="addr_fname" placeholder="กรุณาระบุ ชื่อจริง" value="<?php echo $memProfile['mem_fname']; ?>" maxlength="50">
                                                                            </div>
                                                                            <div class="col-lg-4 single-input-item mb-3">
                                                                                <label for="lname" class="form-label">นามสกุล</label>
                                                                                <input type="text" class="form-control" name="addr_lname" placeholder="กรุณาระบุ นามสกุล" value="<?php echo $memProfile['mem_lname']; ?>" maxlength="50">
                                                                            </div>
                                                                            <div class="col-lg-4 single-input-item mb-3">
                                                                                <label for="phone" class="form-label">เบอร์โทร</label>
                                                                                <input type="number" class="form-control" name="addr_phone" placeholder="กรุณาระบุ เบอร์โทรศัพท์">
                                                                            </div>

                                                                            <div class="col-lg-3 single-input-item mb-3">
                                                                                <label for="province" class="form-label">จังหวัด</label>
                                                                                <input type="hidden" id="province_name" name="province_name">

                                                                                <select class="form-select" id="province" name="province">
                                                                                    <option selected>กรุณาระบุ จังหวัด</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-3 single-input-item mb-3">
                                                                                <label for="district" class="form-label">อำเภอ/เขต</label>
                                                                                <input type="hidden" id="district_name" name="district_name">

                                                                                <select class="form-select" id="district" name="district">
                                                                                    <option selected>กรุณาระบุ อำเภอ/เขต</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-3 single-input-item mb-3">
                                                                                <label for="subdistrict" class="form-label">ตำบล/แขวง</label>
                                                                                <input type="hidden" id="subdistrict_name" name="subdistrict_name">

                                                                                <select class="form-select" id="subdistrict" name="subdistrict">
                                                                                    <option selected>กรุณาระบุ ตำบล/แขวง</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="col-lg-3 single-input-item mb-3">
                                                                                <label for="zip_code" class="form-label">รหัสไปรษณีย์</label>
                                                                                <input type="number" class="form-control" id="zip_code" name="zip_code" placeholder="กรุณาระบุ รหัสไปราณีย์" readonly>
                                                                            </div>
                                                                            <div class="col-lg-12 single-input-item mb-3">
                                                                                <label for="zip_code" class="form-label">รายละเอียดที่อยู่</label>
                                                                                <textarea name="addr_detail" class="form-control" placeholder="ระบุรายละเอียดที่อยู่ เช่น บ้านเลขที่ ถนน ซอย สถานที่ใกล้เคียง"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" data-bs-dismiss="modal" class="btn">ยกเลิก</button>
                                                                    <button type="submit" name="btn-add" class="btn btn-sqr"><i class="fa-solid fa-floppy-disk"></i> บันทึก</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>


                                                <?php if ($memberAddress) { ?>

                                                    <?php foreach ($memberAddress as $row) { ?>
                                                        <div class="d-flex justify-content-between border border-2 px-3 pt-3 mb-3">
                                                            <div class="w-75 text-break">
                                                                <?php
                                                                $phone = $row['addr_phone'];
                                                                // แสดงผลในรูปแบบ 081-234-5678
                                                                $formattedPhone = substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
                                                                ?>
                                                                <h6>
                                                                    <?php echo $row['addr_fname'] . " " . $row['addr_lname'] . " | " .  $formattedPhone;  ?>
                                                                    <?php if ($row['addr_type'] == 2) { ?>
                                                                        <span class="badge rounded-pill text-bg-success ms-1">ที่ทำงาน</span>
                                                                    <?php } else { ?>
                                                                        <span class="badge rounded-pill text-bg-primary ms-1">บ้าน</span>
                                                                    <?php } ?>
                                                                </h6>
                                                                <p><?php echo " <strong>จังหวัด :  </strong>" . $row['addr_province'] . "  <strong>อำเภอ/เขต </strong> : " . $row['addr_district'] . "  <strong>ตำบล/แขวง :  </strong>" . $row['addr_subdistrict'] ?></p>
                                                                <p><?php echo $row['addr_detail'] ?></p>
                                                            </div>
                                                            <div class="w-20">
                                                                <div class="text-center mb-2">
                                                                    <?php if ($row['addr_status'] == 1) { ?>
                                                                        <button class="btn btn-secondary btn-sm w-100" disabled>เป็นค่าเริ่มต้นแล้ว</button>
                                                                    <?php } else { ?>
                                                                        <form action="process/account_address_edit.php" method="post">
                                                                            <input type="hidden" name="mem_id" value="<?php echo $row['mem_id'] ?>" readonly>
                                                                            <input type="hidden" name="addr_id" value="<?php echo $row['addr_id'] ?>" readonly>
                                                                            <button type="submit" name="btn-edit-status" class="btn btn-sm text-white border-0 mt-3 w-100" style="background-color: #f07c29;">ตั้งเป็นค่าเริ่มต้น</button>
                                                                        </form>
                                                                    <?php } ?>
                                                                </div>
                                                                <div class="d-flex justify-content-between">
                                                                    <?php
                                                                    $originalId = $row['addr_id'];
                                                                    require_once("includes/salt.php");   // รหัส Salt 
                                                                    $saltedId = $salt1 . $originalId . $salt2; // นำ salt มารวมกับ id เพื่อความปลอดภัย
                                                                    $base64Encoded = base64_encode($saltedId); // เข้ารหัสข้อมูลโดยใช้ Base64
                                                                    ?>

                                                                    <a href="account_address_edit_form?id=<?php echo $base64Encoded; ?>" class="btn btn-warning <?php if ($row['addr_status'] == 1) {
                                                                                                                                                                    echo 'w-100';
                                                                                                                                                                } else {
                                                                                                                                                                    echo 'w-45';
                                                                                                                                                                } ?>">
                                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                                        แก้ไข
                                                                    </a>

                                                                    <?php if ($row['addr_status'] != 1) { ?>
                                                                        <button type="button" class="btn btn-danger btn-delete w-45 ms-2" data-id="<?php echo $row["addr_id"]; ?>"">
                                                                            <i class=" fa-solid fa-trash"></i>
                                                                            <span>ลบ</span>
                                                                        </button>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <div class="alert alert-secondary text-center" role="alert">
                                                        <h4 class="alert-heading">ไม่พบที่อยู่ของท่าน</h4>
                                                        <p>สามาถเพิ่มที่อยู่ของท่าน ได้สูงสุด 3 แห่ง</p>
                                                    </div>
                                                <?php } ?>
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
                var result = JSON.parse(data);
                $.each(result, function(index, item) {
                    provinceObject.append(
                        $('<option></option>').val(item.id).html(item.name_th).data('name_th', item.name_th)
                    );
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
                    $.each(result, function(index, item) {
                        if (item.province_id == provinceId) {
                            districtObject.append(
                                $('<option></option>').val(item.id).html(item.name_th).data('name_th', item.name_th)
                            );
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
                    $.each(result, function(index, item) {
                        if (item.amphure_id == districtId) {
                            subdistrictObject.append(
                                $('<option></option>').val(item.id).html(item.name_th).data('name_th', item.name_th)
                            );
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

    <!-- Delete  -->
    <script>
        $(document).ready(function() {
            $(".btn-delete").click(function(e) {
                e.preventDefault();
                let id = $(this).data('id');

                deleteConfirm(id);
            });
        });

        function deleteConfirm(id) {
            Swal.fire({
                icon: "warning",
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการลบข้อมูลนี้ใช่ไหม!",
                showCancelButton: true,
                confirmButtonColor: '#f34e4e',
                confirmButtonText: 'ใช่, ลบข้อมูลเลย!',
                cancelButtonText: 'ยกเลิก',
                preConfirm: function() {
                    return $.ajax({
                            url: 'process/account_address_del',
                            type: 'POST',
                            data: {
                                id: id,
                            },
                        })
                        .done(function() {
                            // การลบสำเร็จ ทำการ redirect ไปยังหน้า address
                            return true;
                        })
                        .fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สำเร็จ',
                                text: 'เกิดข้อผิดพลาดที่ ajax !',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.location.href = 'account_address';
                                }
                            });
                        });
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = 'account_address';
                }
            });
        }
    </script>


</body>

</html>
<?php require_once('includes/sweetalert2.php') ?>