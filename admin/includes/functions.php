<?php
// ======================== My Function =====================
/*
    1. 


*/

// เก็บข้อความ error
function messageError($message, $locationError)
{
    $_SESSION['error'] = $message;

    // เปลี่ยนเส้นทางไปยังหน้า $locationError
    header($locationError);
    exit();
}

// ==================================================================================
// Validation Form Add Employee
function valiDateFormAddEmployees($fname, $lname, $username, $password, $confirmPassword, $email, $eatId, $locationError)
{

    // Check First name
    if (empty($fname)) {
        messageError("กรุณาระบุชื่อ", $locationError);
    } elseif (mb_strlen($fname, 'UTF-8') > 50) {
        messageError("ชื่อ ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    // Check Last name
    if (empty($lname)) {
        messageError("กรุณาระบุ นามสกุล", $locationError);
    } elseif (mb_strlen($lname, 'UTF-8') > 50) {
        messageError("นามสกุล ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    // Check Username
    if (empty($username)) {
        messageError("กรุณาระบุ ชื่อผู้ใช้", $locationError);
    } elseif (mb_strlen($username, 'UTF-8') < 6 || mb_strlen($username, 'UTF-8') > 50) {
        messageError("ชื่อผู้ใช้ ต้องมี 6-50 ตัวอักษร", $locationError);
    }

    // Check Password
    if (empty($password)) {
        messageError("กรุณาระบุ รหัสผ่าน", $locationError);
    } elseif (mb_strlen($password, 'UTF-8') < 8 || mb_strlen($password, 'UTF-8') > 255) {
        messageError("รหัสผ่าน ต้องมี 8-255 ตัวอักษร", $locationError);
    }

    // Check Confirm Password
    if (empty($confirmPassword)) {
        messageError("กรุณายืนยัน รหัสผ่าน อีกครั้ง", $locationError);
    } elseif ($password !== $confirmPassword) {
        messageError("ยืนยัน รหัสผ่าน ไม่ถูกต้อง", $locationError);
    }

    // Check Email
    if (empty($email)) {
        messageError("กรุณาระบุอีเมล", $locationError);
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        messageError("รูปแบบของอีเมลไม่ถูกต้อง", $locationError);
    } elseif (mb_strlen($email, 'UTF-8') < 10 || mb_strlen($email, 'UTF-8') > 100) {
        messageError("อีเมล ต้องมี 10-100 ตัวอักษร", $locationError);
    }

    if (empty($eatId)) {
        messageError("ไม่พบรหัสประเภทสิทธิ์พนักงาน", $locationError);
    } elseif (!in_array($eatId, [2, 3, 6])) { // 2 = Owner, 3 = Admin, 6 = Employee
        messageError("รหัสประเภทสิทธิ์พนักงานไม่ถูกต้อง", $locationError);
    }
}

// ==================================================================================

function checkDefaultProfileEmployees($defaultImagePath, $allowedExtensions, $maxFileSize, $locationError)
{
    if (!file_exists($defaultImagePath)) {
        messageError("ไม่มีรูปภาพ default.png ในโฟลเดอร์ uploads/", $locationError);
    }

    $fileExtension = strtolower(pathinfo($defaultImagePath, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        messageError("รูปภาพต้องมีนามสกุล .png, .jpg, หรือ .jpeg เท่านั้น", $locationError);
    }

    $fileSize = filesize($defaultImagePath);

    if ($fileSize === false) {
        messageError("ไม่สามารถตรวจสอบขนาดของไฟล์ default.jpg ได้", $locationError);
    }

    if ($fileSize > $maxFileSize) {
        messageError("ไฟล์ Default ต้องไม่เกิน 2 MB", $locationError);
    }
}


// ==================================================================================
function deleteProfileEmployees($profile)
{
    // โฟลเดอร์ที่เก็บไฟล์รูปภาพ
    $folderUploads = "../../uploads/img_employees/";

    // ตรวจสอบว่ามีชื่อไฟล์และไฟล์นั้นมีอยู่ในโฟลเดอร์ที่กำหนดหรือไม่
    if (!empty($profile) && file_exists($folderUploads . $profile)) {
        // ลบไฟล์รูปภาพ
        if (unlink($folderUploads . $profile)) {
            return true; // คืนค่า true เมื่อการลบสำเร็จ
        }
    }
}


// ==================================================================================
function generateUniqueProfileEmployees($extension, $folder)
{
    do {
        $fileName = 'profile_' . uniqid() . bin2hex(random_bytes(10)) . time() . '.' . $extension;
    } while (file_exists($folder . $fileName));
    return $fileName;
}

// ==================================================================================
function valiDateFormUpdateEmployees($fname, $lname, $status, $authority, $locationError)
{

    // Check First name
    if (empty($fname)) {
        messageError("กรุณาระบุชื่อ", $locationError);
    } elseif (mb_strlen($fname, 'UTF-8') > 50) {
        messageError("ชื่อ ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    // Check Last name
    if (empty($lname)) {
        messageError("กรุณาระบุ นามสกุล", $locationError);
    } elseif (mb_strlen($lname, 'UTF-8') > 50) {
        messageError("นามสกุล ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    if (!isset($status)) {
        messageError("กรุณาระบุ สถานะบัญชี", $locationError);
    } elseif ($status !== "1" && $status !== "0") {
        messageError("สถานะบัญชีต้องเป็น 1 หรือ 0 เท่านั้น โปรดแก้ไข Code", $locationError);
    }


    $validAuthorities = [2, 3, 4, 5, 6];  // Owner, Admin, Accounting, Sale, Employee (Default)

    // Ensure $authority is always treated as an array
    if (!isset($authority)) {
        messageError("กรุณาระบุ สิทธิ์การใช้งาน", $locationError);
    } else {
        // If $authority is not an array, convert it to an array
        if (!is_array($authority)) {
            $authority = explode(',', $authority);
        }

        // Validate each value in the $authority array
        foreach ($authority as $auth) {
            if (!in_array($auth, $validAuthorities)) {
                messageError("กรุณาระบุ สิทธิ์การใช้งานที่ถูกต้อง โปรดแก้ไข Code", $locationError);
                break;
            }
        }
    }
}

// ==================================================================================
function validateFormDeleteEmployees($Id, $locationError)
{
    if (empty($Id) || !filter_var($Id, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
        // ตรวจสอบว่ารหัสพนักงานว่างหรือไม่ และตรวจสอบว่ารหัสพนักงานเป็นจำนวนเต็มบวกหรือไม่
        messageError("ไม่พบรหัสพนักงานนี้หรือรหัสพนักงานไม่ถูกต้อง", $locationError);
    }
}

// ==================================================================================
function valiDateFormAddMember($fname, $lname, $username, $password, $confirmPassword, $email, $locationError)
{

    // Check First name
    if (empty($fname)) {
        messageError("กรุณาระบุชื่อ", $locationError);
    } elseif (mb_strlen($fname, 'UTF-8') > 50) {
        messageError("ชื่อ ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    // Check Last name
    if (empty($lname)) {
        messageError("กรุณาระบุ นามสกุล", $locationError);
    } elseif (mb_strlen($lname, 'UTF-8') > 50) {
        messageError("นามสกุล ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    // Check Username
    if (empty($username)) {
        messageError("กรุณาระบุ ชื่อผู้ใช้", $locationError);
    } elseif (mb_strlen($username, 'UTF-8') < 6 || mb_strlen($username, 'UTF-8') > 50) {
        messageError("ชื่อผู้ใช้ ต้องมี 6-50 ตัวอักษร", $locationError);
    }

    // Check Password
    if (empty($password)) {
        messageError("กรุณาระบุ รหัสผ่าน", $locationError);
    } elseif (mb_strlen($password, 'UTF-8') < 8 || mb_strlen($password, 'UTF-8') > 255) {
        messageError("รหัสผ่าน ต้องมี 8-255 ตัวอักษร", $locationError);
    }

    // Check Confirm Password
    if (empty($confirmPassword)) {
        messageError("กรุณายืนยัน รหัสผ่าน อีกครั้ง", $locationError);
    } elseif ($password !== $confirmPassword) {
        messageError("ยืนยัน รหัสผ่าน ไม่ถูกต้อง", $locationError);
    }

    // Check Email
    if (empty($email)) {
        messageError("กรุณาระบุอีเมล", $locationError);
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        messageError("รูปแบบของอีเมลไม่ถูกต้อง", $locationError);
    } elseif (mb_strlen($email, 'UTF-8') < 10 || mb_strlen($email, 'UTF-8') > 100) {
        messageError("อีเมล ต้องมี 10-100 ตัวอักษร", $locationError);
    }
}

// ==================================================================================
function valiDateFormUpdateMember($fname, $lname, $status,  $locationError)
{

    // Check First name
    if (empty($fname)) {
        messageError("กรุณาระบุชื่อ", $locationError);
    } elseif (mb_strlen($fname, 'UTF-8') > 50) {
        messageError("ชื่อ ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    // Check Last name
    if (empty($lname)) {
        messageError("กรุณาระบุ นามสกุล", $locationError);
    } elseif (mb_strlen($lname, 'UTF-8') > 50) {
        messageError("นามสกุล ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    if (!isset($status)) {
        messageError("กรุณาระบุ สถานะบัญชี", $locationError);
    } elseif ($status !== "1" && $status !== "0") {
        messageError("สถานะบัญชีต้องเป็น 1 หรือ 0 เท่านั้น โปรดแก้ไข Code", $locationError);
    }
}
// ==================================================================================
function deleteProfileMember($profile)
{
    // โฟลเดอร์ที่เก็บไฟล์รูปภาพ
    $folderUploads = "../../uploads/img_member/";

    // ตรวจสอบว่ามีชื่อไฟล์และไฟล์นั้นมีอยู่ในโฟลเดอร์ที่กำหนดหรือไม่
    if (!empty($profile) && file_exists($folderUploads . $profile)) {
        // ลบไฟล์รูปภาพ
        if (unlink($folderUploads . $profile)) {
            return true; // คืนค่า true เมื่อการลบสำเร็จ
        }
    }
}
// ==================================================================================



function generateUniqueImg($fileExtension, $folderUploads)
{
    do {
        $fileName = 'img_' . uniqid() . bin2hex(random_bytes(10)) . time() . '.' . $fileExtension;
    } while (file_exists($folderUploads . $fileName));
    return $fileName;
}
function deleteImg($img, $folderUploads)
{

    // ตรวจสอบว่ามีชื่อไฟล์และไฟล์นั้นมีอยู่ในโฟลเดอร์ที่กำหนดหรือไม่
    if (!empty($img) && file_exists($folderUploads . $img)) {
        // ลบไฟล์รูปภาพ
        if (unlink($folderUploads . $img)) {
            return true; // คืนค่า true เมื่อการลบสำเร็จ
        }
    }
}

function valiDateFormAddProductType($ptyName, $ptyDetail, $ptyStatus, $locationError)
{

    // Check Product Type Name
    if (empty($ptyName)) {
        messageError("กรุณาระบุชื่อประเภทสินค้า", $locationError);
    } elseif (mb_strlen($ptyName, 'UTF-8') > 100) {
        messageError("ชื่อประเภทสินค้า ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }


    // Check Product Type Detail
    if (empty($ptyDetail)) {
        messageError("กรุณาระบุ รายละเอียดประเภทสินค้า", $locationError);
    } elseif (mb_strlen($ptyDetail, 'UTF-8') > 100) {
        messageError("ชื่อประเภทสินค้า ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (!isset($ptyStatus)) {
        messageError("กรุณาระบุ สถานะประเภทสินค้า", $locationError);
    } elseif (!is_numeric($ptyStatus) || ($ptyStatus != 1 && $ptyStatus != 0)) {
        messageError("สถานะประเภทสินค้า ต้องเป็นเลข 1 หรือ 0", $locationError);
    }
}

function valiDateFormUpdateProductType($ptyName, $ptyDetail, $ptyStatus, $locationError)
{

    // Check Product Type name
    if (empty($ptyName)) {
        messageError("กรุณาระบุชื่อประเภทสินค้า", $locationError);
    } elseif (mb_strlen($ptyName, 'UTF-8') > 100) {
        messageError("ชื่อชื่อประเภทสินค้า ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    // Check Product Type Detail
    if (empty($ptyDetail)) {
        messageError("กรุณาระบุ รายละเอียดประเภทสินค้า", $locationError);
    } elseif (mb_strlen($ptyDetail, 'UTF-8') > 100) {
        messageError("รายละเอียดประเภทสินค้า ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (!isset($ptyStatus)) {
        messageError("กรุณาระบุ สถานะการแสดงประเภท", $locationError);
    } elseif ($ptyStatus !== "1" && $ptyStatus !== "0") {
        messageError("สถานะการแสดงประเภทต้องเป็น 1 หรือ 0 เท่านั้น โปรดแก้ไข Code", $locationError);
    }
}

function valiDateFormPublischer($pubName, $pubDetail,  $pubStatus, $locationError)
{
    // Check Product Type name
    if (empty($pubName)) {
        messageError("กรุณาระบุชื่อสำนักพิมพ์", $locationError);
    } elseif (mb_strlen($pubName, 'UTF-8') > 100) {
        messageError("ชื่อชื่อสำนักพิมพ์ ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    // Check Product Type Detail
    if (empty($pubDetail)) {
        messageError("กรุณาระบุ รายละเอียดสำนักพิมพ์", $locationError);
    } elseif (mb_strlen($pubDetail, 'UTF-8') > 100) {
        messageError("รายละเอียดสำนักพิมพ์ ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (!isset($pubStatus)) {
        messageError("กรุณาระบุ สถานะการแสดงประเภท", $locationError);
    } elseif ($pubStatus !== "1" && $pubStatus !== "0") {
        messageError("สถานะการแสดงประเภทต้องเป็น 1 หรือ 0 เท่านั้น โปรดแก้ไข Code", $locationError);
    }
}

function valiDateFormAuthor($authName, $authDetail,  $authStatus, $locationError)
{
    // Check Author name
    if (empty($authName)) {
        messageError("กรุณาระบุชื่อผู้แต่ง", $locationError);
    } elseif (mb_strlen($authName, 'UTF-8') > 100) {
        messageError("ชื่อชื่อผู้แต่ง ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    // Check Author Detail
    if (empty($authDetail)) {
        messageError("กรุณาระบุ รายละเอียดผู้แต่ง", $locationError);
    } elseif (mb_strlen($authDetail, 'UTF-8') > 100) {
        messageError("รายละเอียดผู้แต่ง ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (!isset($authStatus)) {
        messageError("กรุณาระบุ สถานะการแสดงประเภท", $locationError);
    } elseif ($authStatus !== "1" && $authStatus !== "0") {
        messageError("สถานะการแสดงประเภทต้องเป็น 1 หรือ 0 เท่านั้น โปรดแก้ไข Code", $locationError);
    }
}

function  validateFormPromotion($proName, $proPercentDiscount, $proTimeStart, $proTimeEnd, $proDetail, $proStatus, $locationError)
{
    if (empty($proName)) {
        messageError("กรุณาระบุ ชื่อโปรโมขั่น", $locationError);
    } elseif (mb_strlen($proName, 'UTF-8') > 100) {
        messageError("ชื่อโปรโมชั่น ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if ($proPercentDiscount === '' || $proPercentDiscount === null) {
        messageError("กรุณาระบุ ส่วนลดโปรโมขั่น", $locationError);
    } elseif (!is_numeric($proPercentDiscount) || $proPercentDiscount < 0 || $proPercentDiscount > 100) {
        messageError("ส่วนลดโปรโมชันต้องเป็นตัวเลข ระหว่าง 0 - 100", $locationError);
    }

    if (empty($proTimeStart) || strtotime($proTimeStart) === false) {
        messageError("กรุณาระบุ วันเริ่มโปรโมชันที่ถูกต้อง", $locationError);
    }

    if (empty($proTimeEnd) || strtotime($proTimeEnd) === false) {
        messageError("กรุณาระบุ วันสิ้นสุดโปรโมชันที่ถูกต้อง", $locationError);
    }elseif (strtotime($proTimeEnd) < strtotime($proTimeStart)) {
        messageError("วันสิ้นสุดโปรโมชันต้องมากกว่า วันเริ่มโปรโมชั่น", $locationError);
    }

    if(empty($proDetail)){
        messageError("กรุณาระบุ รายละเอียดโปรโมชั่น", $locationError);
    }elseif(mb_strlen($proDetail, 'UTF-8') > 100){
        messageError("รายละเอียดโปรโมชั่น ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (!isset($proStatus)) {
        messageError("กรุณาระบุ สถานะการแสดงโปรโมชั่น", $locationError);
    } elseif ($proStatus !== "1" && $proStatus !== "0") {
        messageError("สถานะการแสดโปรโมชั่นต้องเป็น 1 หรือ 0 เท่านั้น โปรดแก้ไข Code", $locationError);
    }
}
