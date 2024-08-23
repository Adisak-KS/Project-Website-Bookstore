<?php
require_once('../db/connectdb.php');
require_once('../db/controller/MemberAddressController.php');
require_once('../includes/functions.php');


$MemberAddressController = new MemberAddressController($conn);

if (isset($_POST['btn-edit'])) {
    $addrId = $_POST['addr_id'];
    $memId = $_POST['mem_id'];
    $addrType = $_POST['addr_type'];
    $addrFname = $_POST['addr_fname'];
    $addrLname = $_POST['addr_lname'];
    $addrPhone = $_POST['addr_phone'];
    $province = $_POST['province_name'];
    $district  = $_POST['district_name'];
    $subDistrict = $_POST['subdistrict_name'];
    $zipCode = $_POST['zip_code'];
    $addrDetail = $_POST['addr_detail'];

    $base64Encoded = $_SESSION["base64Encoded"];

    $locationError = "Location: ../account_address_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../account_address";

    echo "id ที่อยู่ : " . $addrId . "<br>";
    echo "id สมาชิก : " . $memId . "<br>";
    echo "ประเภทที่อยู่ : " . $addrType . "<br>";
    echo "ชื่อ : " . $addrFname . "<br>";
    echo "นามสกุล : " . $addrLname . "<br>";
    echo "เบอร์ : " . $addrPhone . "<br>";
    echo "จังหวัด : " . $province . "<br>";
    echo "อำเภอ : " .  $district . "<br>";
    echo "ตำบล : " . $subDistrict . "<br>";
    echo "รหัสไปรษณีย์ : " . $zipCode . "<br>";
    echo "รายละเอียด : " . $addrDetail . "<br>";

    validateFormAddress($memId, $addrType, $addrFname, $addrLname, $addrPhone, $province, $district, $subDistrict, $zipCode, $addrDetail, $locationError);

    //แก้ไขที่อยู่สมาชิก
    $updateMemberAddress = $MemberAddressController->updateMemberAddress($addrId, $memId, $addrType, $addrFname, $addrLname, $addrPhone, $province, $district, $subDistrict, $zipCode, $addrDetail);

    if ($updateMemberAddress) {
        $_SESSION['success'] = "แก้ไขที่อยู่ สำเร็จ";
        unset($base64Encoded);
        header($locationSuccess);
    }
} elseif (isset($_POST['btn-edit-status'])) {
    $memId = $_POST['mem_id'];
    $addrId = $_POST['addr_id'];
    $addrStatus = 1;


    $locationError = "Location: ../account_address";
    $locationSuccess = "Location: ../account_address";


    echo "Id ของสมาชิก : " . $memId . "<br>";
    echo "สถานะ : " . $addrStatus . "<br>";

    $updateMemberAddressStatus = $MemberAddressController->updateMemberAddressStatus($addrId, $memId);

    if ($updateMemberAddressStatus) {
        $_SESSION['success'] = "ตั้งค่าที่อยู่เริ่มต้นสำเร็จ";
        unset($base64Encoded);
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
