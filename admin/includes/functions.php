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
    }elseif (!in_array($eatId, [2, 3, 6])) { // 2 = Owner, 3 = Admin, 6 = Employee
        messageError("รหัสประเภทสิทธิ์พนักงานไม่ถูกต้อง", $locationError);
    }
}

// ==================================================================================

function checkDefaultProfileEmployees($defaultImagePath, $allowedExtensions, $maxFileSize, $locationError)
{
    if (!file_exists($defaultImagePath)) {
        messageError("ไม่มีรูปภาพ default.jpg ในโฟลเดอร์ uploads/img_employees/", $locationError);
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
    if (!isset($authority)) {
        messageError("กรุณาระบุ สิทธิ์การใช้งาน", $locationError);
    } elseif (!in_array($authority, $validAuthorities)) {
        messageError("กรุณาระบุ สิทธิ์การใช้งานที่ถูกต้อง โปรดแก้ไข Code", $locationError);
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
