<?php
require_once('../db/connectdb.php');
require_once('../db/controller/MemberAddressController.php');
require_once('../includes/functions.php');


$MemberAddressController = new MemberAddressController($conn);

if (isset($_POST['btn-add'])) {
    $memId = $_POST['mem_id'];
    $totalAddress = $_POST['total_address']; // จำนวนที่อยู่ที่มี
    $addrType = $_POST['addr_type'];
    $addrFname = $_POST['addr_fname'];
    $addrLname = $_POST['addr_lname'];
    $addrPhone = $_POST['addr_phone'];
    $province = $_POST['province_name'];
    $district  = $_POST['district_name'];
    $subDistrict = $_POST['subdistrict_name'];
    $zipCode = $_POST['zip_code'];
    $addrDetail = $_POST['addr_detail'];

    if($totalAddress == 0){
        // หากไม่มีข้อมูลที่อยู่
        $addrStatus = 1;
    }else{
        // หากมีข้อมูลที่อยู่
        $addrStatus = 0;
    }

    $locationError = "Location: ../account_address";
    $locationSuccess = "Location: ../account_address";


    echo "id สมาชิก : " . $memId . "<br>";
    echo "จำนวนที่อยู่ : " . $totalAddress . "<br>";

    echo "ประเภทที่อยู่ : " . $addrType . "<br>";
    echo "ชื่อ : " . $addrFname . "<br>";
    echo "นามสกุล : " . $addrLname . "<br>";
    echo "เบอร์ : " . $addrPhone . "<br>";
    echo "จังหวัด : " . $province . "<br>";
    echo "อำเภอ : " .  $district . "<br>";
    echo "ตำบล : " . $subDistrict . "<br>";
    echo "รหัสไปรษณีย์ : " . $zipCode . "<br>";
    echo "รายละเอียด : " . $addrDetail . "<br>";
    echo "สถานะ : " . $addrStatus . "<br>";

    validateFormAddress($memId, $addrType, $addrFname, $addrLname, $addrPhone, $province, $district, $subDistrict, $zipCode, $addrDetail, $locationError);

    //เพิ่มที่อยู่สมาชิก
    $addMemberAddress = $MemberAddressController->insertMemberAddress($memId, $addrType, $addrFname, $addrLname, $addrPhone, $province, $district, $subDistrict, $zipCode, $addrDetail, $addrStatus);

    if ($addMemberAddress) {
        $_SESSION['success'] = "เพิ่มที่อยู่ใหม่ สำเร็จ";
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
