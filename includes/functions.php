<?php
// ======================== My Function For Admin and User =====================
/*
    1. Message Error
    2. Validation Form Add Employee
    3. Check Default Image
    4. Decode Base64 ID
    5. Check Result Detail
    6. Delete Profile Employees
    7. Generate Unique Profile Employees
    8. Validation Form Update Employees
    9. Validation Form Delete Employees
    10. Validation Form Add Member
    11. Validation Form Update Member
    12. Delete Profile Member
    13. Generate Unique Image
    14. Delete Image
    15. validation Form Add Product Type
    16. validation Form update Product Type
    17. validation Form Publisher
    18. validation Form Author
    19. validation Form Promotion
    20. validation from Add Product
    21. validation from Update Product
    22. validation from Add Payment
    23. validation from Shipping
    24. validation from Contact
    25. validation Add Banner
    26. validation Update Banner
    27. valiDate Form Update Account Member
    
*/


// ======================== 1. Message Error  ==========================================================

function messageError($message, $locationError)
{
    $_SESSION['error'] = $message;

    // เปลี่ยนเส้นทางไปยังหน้า $locationError
    header($locationError);
    exit();
}

// ======================== 2. Validation Form Add Employee  =============================================
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

// ======================== 3. Check Default Image  =============================================
function checkDefaultImg($defaultImagePath, $allowedExtensions, $maxFileSize, $locationError)
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

// ======================== 4. Decode Base64 ID  =============================================
function decodeBase64ID($base64Encoded, $salt1, $salt2)
{
    // ถอดรหัส base64 เพื่อให้ได้ข้อมูลเป็นข้อความธรรมดา
    $base64Decoded = base64_decode($base64Encoded);

    // แยกส่วนของ salt และข้อมูลที่ไม่ถูกเข้ารหัส
    $salt1Length = mb_strlen($salt1, 'UTF-8');
    $salt2Length = mb_strlen($salt2, 'UTF-8');

    $extractedSalt1 = substr($base64Decoded, 0, $salt1Length);
    $saltedId = substr($base64Decoded, $salt1Length, -$salt2Length);
    $extractedSalt2 = substr($base64Decoded, -$salt2Length);

    // สร้างค่า originalId โดยตัดค่า salt ออก
    $originalId = str_replace([$extractedSalt1, $extractedSalt2], '', $saltedId);

    return $originalId;
}

// ======================== 5. Check Result Detail  =============================================
function checkResultDetail($resultDetail)
{
    if (!$resultDetail) {
        header('Location: error_not_result');
        exit;
    }
}

// ======================== 6. Delete Profile Employees =============================================
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

// ======================== 7. Generate Unique Profile Employees =====================================
function generateUniqueProfileEmployees($extension, $folder)
{
    do {
        $fileName = 'profile_' . uniqid() . bin2hex(random_bytes(10)) . time() . '.' . $extension;
    } while (file_exists($folder . $fileName));
    return $fileName;
}

// ======================== 8. Validation Form Update Employees =====================================
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

// ======================== 9. Validation Form Delete Employees =====================================
function validateFormDeleteEmployees($Id, $locationError)
{
    if (empty($Id) || !filter_var($Id, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1)))) {
        // ตรวจสอบว่ารหัสพนักงานว่างหรือไม่ และตรวจสอบว่ารหัสพนักงานเป็นจำนวนเต็มบวกหรือไม่
        messageError("ไม่พบรหัสพนักงานนี้หรือรหัสพนักงานไม่ถูกต้อง", $locationError);
    }
}

// ======================== 10. Validation Form Add Member =====================================
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

// ======================== 11. Validation Form Update Member =====================================
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

// ======================== 12. Delete Profile Member =====================================
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

// ======================== 13. Generate Unique Image =====================================
function generateUniqueImg($fileExtension, $folderUploads)
{
    do {
        $fileName = 'img_' . uniqid() . bin2hex(random_bytes(10)) . time() . '.' . $fileExtension;
    } while (file_exists($folderUploads . $fileName));
    return $fileName;
}

// ======================== 14. Delete Image =====================================
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

// ======================== 15. validation Form Add Product Type =====================================
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

// ======================== 16. validation Form update Product Type =====================================
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

// ======================== 17. validation Form Publisher =====================================
function valiDateFormPublisher($pubName, $pubDetail, $pubStatus, $locationError)
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

// ======================== 18. validation Form Author =====================================
function valiDateFormAuthor($authName, $authDetail, $authStatus, $locationError)
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

// ======================== 19. validation Form Promotion =====================================
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
    } elseif (strtotime($proTimeEnd) < strtotime($proTimeStart)) {
        messageError("วันสิ้นสุดโปรโมชันต้องมากกว่า วันเริ่มโปรโมชั่น", $locationError);
    }

    if (empty($proDetail)) {
        messageError("กรุณาระบุ รายละเอียดโปรโมชั่น", $locationError);
    } elseif (mb_strlen($proDetail, 'UTF-8') > 100) {
        messageError("รายละเอียดโปรโมชั่น ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (!isset($proStatus)) {
        messageError("กรุณาระบุ สถานะการแสดงโปรโมชั่น", $locationError);
    } elseif ($proStatus !== "1" && $proStatus !== "0") {
        messageError("สถานะการแสดโปรโมชั่นต้องเป็น 1 หรือ 0 เท่านั้น โปรดแก้ไข Code", $locationError);
    }
}

// ======================== 20. validation from Add Product =====================================
function validateFormAddProduct($prdName, $prdISBN, $prdCoin, $prdQuantity, $prdNumberPages, $prdPrice, $prdPercentDiscount, $ptyId, $pubId, $authId, $prdPreorder, $prdStatus, $locationError)
{

    if (empty($prdName)) {
        messageError("กรุณาระบุ ชื่อสินค้า", $locationError);
    } elseif (mb_strlen($prdName, 'UTF-8') > 100) {
        messageError("ชื่อสินค้า ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (empty($prdISBN)) {
        messageError("กรุณาระบุ รหัส ISBN สินค้า", $locationError);
    } elseif (!is_numeric($prdISBN)) {
        messageError("รหัส ISBN ต้องเป็นตัวเลขเท่านั้น", $locationError);
    } elseif ($prdISBN < 0) {
        messageError("รหัส ISBN ต้องเป็นเลข 0 ขึ้นไป", $locationError);
    } elseif (!(mb_strlen($prdISBN, 'UTF-8') == 10 || mb_strlen($prdISBN, 'UTF-8') == 13)) {
        messageError("รหัส ISBN ต้องมีตัวเลข 10 หรือ 13 ตัว", $locationError);
    }

    if ($prdCoin === null || $prdCoin === '') {
        messageError("กรุณาระบุ จำนวนเหรียญ", $locationError);
    } elseif (!is_numeric($prdCoin)) {
        messageError("จำนวนเหรียญ ต้องเป็นตัวเลข", $locationError);
    } elseif ($prdCoin < 0) {
        messageError("จำนวนเหรียญ ต้องเป็นเลข 0 ขึ้นไป", $locationError);
    } elseif (strpos($prdCoin, '.') !== false) {
        messageError("จำนวนเหรียญ ต้องเป็นจำนวนเต็ม", $locationError);
    }

    if ($prdQuantity === null || $prdQuantity === '') {
        messageError("กรุณาระบุ จำนวนสินค้า", $locationError);
    } elseif (!is_numeric($prdQuantity)) {
        messageError("จำนวนสินค้า ต้องเป็นตัวเลข", $locationError);
    } elseif ($prdQuantity < 0) {
        messageError("จำนวนสินค้า ต้องเป็นเลข 0 ขึ้นไป", $locationError);
    } elseif (strpos($prdQuantity, '.') !== false) {
        messageError("จำนวนสินค้า ต้องเป็นจำนวนเต็ม", $locationError);
    }

    if ($prdNumberPages === null || $prdNumberPages === '') {
        messageError("กรุณาระบุ จำนวนหน้าหนังสือ", $locationError);
    } elseif (!is_numeric($prdNumberPages)) {
        messageError("จำนวนหน้าหนังสือ ต้องเป็นตัวเลข", $locationError);
    } elseif ($prdNumberPages < 0) {
        messageError("จำนวนหน้าหนังสือ ต้องเป็นเลข 0 ขึ้นไป", $locationError);
    } elseif (strpos($prdNumberPages, '.') !== false) {
        messageError("จำนวนหน้าหนังสือ ต้องเป็นจำนวนเต็ม", $locationError);
    }

    if ($prdPrice === null || $prdPrice === '') {
        messageError("กรุณาระบุ ราคาสินค้า", $locationError);
    } elseif (!is_numeric($prdPrice) || $prdPrice < 0) {
        messageError("ราคาสินค้า ต้องเป็นตัวเลขมากกว่า 0", $locationError);
    }

    if ($prdPercentDiscount === null || $prdPercentDiscount === '') {
        messageError("กรุณาระบุ ส่วนลดสินค้า", $locationError);
    } elseif (!is_numeric($prdPercentDiscount) || $prdPercentDiscount < 0 || $prdPercentDiscount > 100) {
        messageError("ส่วนลดสินค้า ต้องเป็นตัวเลขระหว่าง 0 - 100", $locationError);
    } elseif (strpos($prdPercentDiscount, '.') !== false) {
        messageError("ส่วนลดสินค้า ต้องเป็นจำนวนเต็ม", $locationError);
    }

    if (empty($ptyId)) {
        messageError("กรุณาระบุ ประเภทสินค้า", $locationError);
    } elseif (!is_numeric($ptyId) || $ptyId <= 0) {
        messageError("ประเภทสินค้า ต้องเป็นตัวเลขมากกว่า 0", $locationError);
    }

    if (empty($pubId)) {
        messageError("กรุณาระบุ สำนักพิมพ์", $locationError);
    } elseif (!is_numeric($pubId) || $pubId <= 0) {
        messageError("สำนักพิมพ์ ต้องเป็นตัวเลขมากกว่า 0", $locationError);
    }

    if (empty($authId)) {
        messageError("กรุณาระบุ ชื่อผู้แต่ง", $locationError);
    } elseif (!is_numeric($authId) && $authId <= 0) {
        messageError("ชื่อผู้แต่ง ต้องเป็นตัวเลขมากกว่า 0", $locationError);
    }

    if (!isset($prdPreorder)) {
        messageError("กรุณาระบุ ชนิดสินค้า", $locationError);
    } elseif ($prdPreorder != 1 && $prdPreorder != 0) {
        messageError("ชนิดสินค้าไม่ถูกต้อง", $locationError);
    }

    if (!isset($prdStatus)) {
        messageError("กรุณาระบุ สถานะการแสดง", $locationError);
    } elseif ($prdStatus != 1 && $prdStatus != 0) {
        messageError("เลขสถานะการแสดงไม่ถูกต้อง", $locationError);
    }
}

// ======================== 21. validation from Update Product =====================================
function validateFormUpdateProduct($prdName, $prdISBN, $prdCoin, $prdQuantity, $prdNumberPages, $prdDetail, $prdPrice, $prdPercentDiscount, $ptyId, $pubId, $authId, $prdPreorder, $prdStatus, $locationError)
{

    if (empty($prdName)) {
        messageError("กรุณาระบุ ชื่อสินค้า", $locationError);
    } elseif (mb_strlen($prdName, 'UTF-8') > 100) {
        messageError("ชื่อสินค้า ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (empty($prdISBN)) {
        messageError("กรุณาระบุ รหัส ISBN สินค้า", $locationError);
    } elseif (!is_numeric($prdISBN)) {
        messageError("รหัส ISBN ต้องเป็นตัวเลขเท่านั้น", $locationError);
    } elseif ($prdISBN < 0) {
        messageError("รหัส ISBN ต้องเป็นเลข 0 ขึ้นไป", $locationError);
    } elseif (!(mb_strlen($prdISBN, 'UTF-8') == 10 || mb_strlen($prdISBN, 'UTF-8') == 13)) {
        messageError("รหัส ISBN ต้องมีตัวเลข 10 หรือ 13 ตัว", $locationError);
    }

    if ($prdCoin === null || $prdCoin === '') {
        messageError("กรุณาระบุ จำนวนเหรียญ", $locationError);
    } elseif (!is_numeric($prdCoin)) {
        messageError("จำนวนเหรียญ ต้องเป็นตัวเลข", $locationError);
    } elseif ($prdCoin < 0) {
        messageError("จำนวนเหรียญ ต้องเป็นเลข 0 ขึ้นไป", $locationError);
    }

    if ($prdQuantity === null || $prdQuantity === '') {
        messageError("กรุณาระบุ จำนวนสินค้า", $locationError);
    } elseif (!is_numeric($prdQuantity)) {
        messageError("จำนวนสินค้า ต้องเป็นตัวเลข", $locationError);
    } elseif ($prdQuantity < 0) {
        messageError("จำนวนสินค้า ต้องเป็นเลข 0 ขึ้นไป", $locationError);
    } elseif (strpos($prdQuantity, '.') !== false) {
        messageError("จำนวนสินค้า ต้องเป็นจำนวนเต็ม", $locationError);
    }

    if ($prdNumberPages === null || $prdNumberPages === '') {
        messageError("กรุณาระบุ จำนวนหน้าหนังสือ", $locationError);
    } elseif (!is_numeric($prdNumberPages)) {
        messageError("จำนวนหน้าหนังสือ ต้องเป็นตัวเลข", $locationError);
    } elseif ($prdNumberPages < 0) {
        messageError("จำนวนหน้าหนังสือ ต้องเป็นเลข 0 ขึ้นไป", $locationError);
    } elseif (strpos($prdNumberPages, '.') !== false) {
        messageError("จำนวนหน้าหนังสือ ต้องเป็นจำนวนเต็ม", $locationError);
    }

    if ($prdDetail === null || $prdDetail === '') {
        messageError("กรุณาระบุ รายละเอียดสินค้า", $locationError);
    }

    if ($prdPrice === null || $prdPrice === '') {
        messageError("กรุณาระบุ ราคาสินค้า", $locationError);
    } elseif (!is_numeric($prdPrice) || $prdPrice < 0) {
        messageError("ราคาสินค้า ต้องเป็นตัวเลขมากกว่า 0", $locationError);
    }

    if ($prdPercentDiscount === null || $prdPercentDiscount === '') {
        messageError("กรุณาระบุ ส่วนลดสินค้า", $locationError);
    } elseif (!is_numeric($prdPercentDiscount) || $prdPercentDiscount < 0 || $prdPercentDiscount > 100) {
        messageError("ส่วนลดสินค้า ต้องเป็นตัวเลขระหว่าง 0 - 100", $locationError);
    } elseif (strpos($prdPercentDiscount, '.') !== false) {
        messageError("ส่วนลดสินค้า ต้องเป็นจำนวนเต็ม", $locationError);
    }

    if (empty($ptyId)) {
        messageError("กรุณาระบุ ประเภทสินค้า", $locationError);
    } elseif (!is_numeric($ptyId) || $ptyId <= 0) {
        messageError("ประเภทสินค้า ต้องเป็นตัวเลขมากกว่า 0", $locationError);
    }

    if (empty($pubId)) {
        messageError("กรุณาระบุ สำนักพิมพ์", $locationError);
    } elseif (!is_numeric($pubId) || $pubId <= 0) {
        messageError("สำนักพิมพ์ ต้องเป็นตัวเลขมากกว่า 0", $locationError);
    }

    if (empty($authId)) {
        messageError("กรุณาระบุ ชื่อผู้แต่ง", $locationError);
    } elseif (!is_numeric($authId) && $authId <= 0) {
        messageError("ชื่อผู้แต่ง ต้องเป็นตัวเลขมากกว่า 0", $locationError);
    }

    if (!isset($prdPreorder)) {
        messageError("กรุณาระบุ ชนิดสินค้า", $locationError);
    } elseif ($prdPreorder != 1 && $prdPreorder != 0) {
        messageError("ชนิดสินค้าไม่ถูกต้อง", $locationError);
    }

    if (!isset($prdStatus)) {
        messageError("กรุณาระบุ สถานะการแสดง", $locationError);
    } elseif ($prdStatus != 1 && $prdStatus != 0) {
        messageError("เลขสถานะการแสดงไม่ถูกต้อง", $locationError);
    }
}

// ======================== 22. validation from Add Payment =====================================
function validateFormAddPayment($pmtBank, $pmtName, $pmtNumber, $pmtDetail, $pmtStatus, $locationError)
{

    if (empty($pmtBank)) {
        messageError("กรุณาระบุ ชื่อธนาคาร", $locationError);
    } elseif (mb_strlen($pmtBank, 'UTF-8') > 100) {
        messageError("ชื่อธนาคาร ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (empty($pmtName)) {
        messageError("กรุณาระบุ ชื่อบัญชี", $locationError);
    } elseif (mb_strlen($pmtName, 'UTF-8') > 100) {
        messageError("ชื่อบัญชี ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (empty($pmtNumber)) {
        messageError("กรุณาระบุ หมายเลขบัญชี", $locationError);
    } elseif (!is_numeric($pmtNumber)) {
        messageError("หมายเลขบัญชี ต้องเป็นตัวเลข", $locationError);
    } elseif (($pmtNumber < 0) || strpos($pmtNumber, '.') !== false) {
        messageError("หมายเลขบัญชี ต้องไม่มีเลขติดลบและทศนิยม", $locationError);
    } elseif (mb_strlen($pmtNumber, 'UTF-8') != 10) {
        messageError("หมายเลขบัญชี ต้องมี 10 ตัว", $locationError);
    }

    if (empty($pmtDetail)) {
        messageError("กรุณาระบุ รายละเอียด", $locationError);
    }

    if (!isset($pmtStatus)) {
        messageError("กรุณาระบุ สถานะการแสดง", $locationError);
    } elseif ($pmtStatus != 1 && $pmtStatus != 0) {
        messageError("เลขสถานะการแสดงไม่ถูกต้อง", $locationError);
    }
}

// ======================== 23. validation from Shipping =====================================
function validateFormShipping($shpName, $shpPrice, $shpDetail, $shpStatus, $locationError)
{

    if (!isset($shpName)) {
        messageError("กรุณาระบุ ชื่อขนส่ง", $locationError);
    } elseif (mb_strlen($shpName, 'UTF-8') > 100) {
        messageError("ชื่อขนส่ง ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (empty($shpPrice)) {
        messageError("กรุณาระบุ ราคาขนส่ง", $locationError);
    } elseif (!is_numeric($shpPrice) || $shpPrice < 0) {
        messageError("ราคาขนส่ง ต้องเป็นตัวเลข และมากกว่า 0", $locationError);
    }

    if (empty($shpDetail)) {
        messageError("กรุณาระบุ รายละเอียด", $locationError);
    }

    if (!isset($shpStatus)) {
        messageError("กรุณาระบุ สถานะการแสดง", $locationError);
    } elseif ($shpStatus != 1 && $shpStatus != 0) {
        messageError("สถานะการแสดงผิดพลาด", $locationError);
    }
}

// ======================== 24. validation from Contact =====================================
function valiDateFormContact($ctDetail, $ctStatus, $locationError)
{
    if (empty($ctDetail)) {
        messageError("กรุณาระบุ ลิงค์ช่องทางติดต่อ", $locationError);
    } elseif (!filter_var($ctDetail, FILTER_VALIDATE_URL)) {
        messageError("กรุณาระบุลิงค์ช่องทางติดต่อที่ถูกต้อง", $locationError);
    }
}

// ======================== 25. validation Add Banner =====================================
function  valiDateFormAddBanner($bnName, $bnLink, $bnImg,  $bnStatus, $locationError)
{
    if (empty($bnName)) {
        messageError("กรุณาระบุ ชื่อแบนเนอร์", $locationError);
    } elseif (mb_strlen($bnName, 'UTF-8') > 100) {
        messageError("ชื่อแบนเนอร์ ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (!empty($bnLink) && !filter_var($bnLink, FILTER_VALIDATE_URL)) {
        messageError("ลิงค์ URL แบนเนอร์ ไม่ถูกต้อง", $locationError);
    }

    if (empty($bnImg)) {
        messageError("กรุณาระบุ รูปภาพ", $locationError);
    } elseif (!empty($bnImg)) {
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $fileExtension = pathinfo($bnImg, PATHINFO_EXTENSION);

        // Check if file extension is allowed
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            messageError("กรุณาระบุไฟล์ภาพที่เป็นรูปแบบ png, jpg หรือ jpeg เท่านั้น", $locationError);
        }
    }

    if (!isset($bnStatus)) {
        messageError("กรุณาระบุสถานะ 1 หรือ 0", $locationError);
    } elseif ($bnStatus != 0 && $bnStatus != 1) {
        messageError("สถานะต้องเป็นเลข 1 หรือ 0 เท่านั้น", $locationError);
    }
}

// ======================== 26. validation Update Banner =====================================
function valiDateFormUpdateBanner($bnName, $bnLink, $bnStatus, $locationError)
{
    if (empty($bnName)) {
        messageError("กรุณาระบุ ชื่อแบนเนอร์", $locationError);
    } elseif (mb_strlen($bnName, 'UTF-8') > 100) {
        messageError("ชื่อแบนเนอร์ ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if (!empty($bnLink) && !filter_var($bnLink, FILTER_VALIDATE_URL)) {
        messageError("ลิงค์ URL แบนเนอร์ ไม่ถูกต้อง", $locationError);
    }

    if (!isset($bnStatus)) {
        messageError("กรุณาระบุสถานะ 1 หรือ 0", $locationError);
    } elseif ($bnStatus != 0 && $bnStatus != 1) {
        messageError("สถานะต้องเป็นเลข 1 หรือ 0 เท่านั้น", $locationError);
    }
}

// ======================== 27. valiDate Form Update Account Member =====================================
function valiDateFormUpdateAccountMember($id, $fname, $lname, $username, $email, $locationError)
{

    if (empty($id) || !is_numeric($id) || $id < 0) {
        messageError("ไม่พบรหัสผู้ใช้นี้", $locationError);
    }

    if (empty($fname)) {
        messageError("กรุณาระบุ ชื่อจริง", $locationError);
    } elseif (mb_strlen($fname, "UTF-8") > 50) {
        messageError("ชื่อจริง ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }
    if (empty($lname)) {
        messageError("กรุณาระบุ นามสกุล", $locationError);
    } elseif (mb_strlen($lname, "UTF-8") > 50) {
        messageError("นามสกุล ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    if (empty($username)) {
        messageError("กรุณาระบุ ชื่อผู้ใช้", $locationError);
    } elseif (mb_strlen($username, 'UTF-8') < 6 || mb_strlen($username, 'UTF-8') > 50) {
        messageError("ชื่อผู้ใช้ ต้องมี 6-50 ตัวอักษร", $locationError);
    }

    if (empty($email)) {
        messageError("กรุณาระบุอีเมล", $locationError);
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        messageError("รูปแบบของอีเมลไม่ถูกต้อง", $locationError);
    } elseif (mb_strlen($email, 'UTF-8') < 10 || mb_strlen($email, 'UTF-8') > 100) {
        messageError("อีเมล ต้องมี 10-100 ตัวอักษร", $locationError);
    }
}

// ======================== 27. valiDate Form Update Password Member =====================================

function  validateFormUpdatePassword($id, $password, $newPassword, $confirmPassword, $locationError)
{

    if (empty($id) || !is_numeric($id) || $id < 0 || $id == null) {
        messageError("ไม่พบรหัสผู้ใช้นี้", $locationError);
    }

    // Check Password
    if (empty($password) || $password == null) {
        messageError("กรุณาระบุ รหัสผ่านปัจจุบัน", $locationError);
    } elseif (mb_strlen($password, 'UTF-8') < 8 || mb_strlen($password, 'UTF-8') > 255) {
        messageError("รหัสผ่านปัจจุบัน ต้องมี 8-255 ตัวอักษร", $locationError);
    }

    if (empty($newPassword) || $newPassword == null) {
        messageError("กรุณาระบุ รหัสผ่านใหม่", $locationError);
    } elseif (mb_strlen($newPassword, 'UTF-8') < 8 || mb_strlen($newPassword, 'UTF-8') > 255) {
        messageError("รหัสผ่านใหม่ ต้องมี 8-255 ตัวอักษร", $locationError);
    } elseif ($newPassword == $password) {
        messageError("รหัสผ่านใหม่ ซ้ำกับรหัสผ่านเดิม", $locationError);
    }

    // Check Confirm Password
    if (empty($confirmPassword) || $confirmPassword == null) {
        messageError("กรุณายืนยัน รหัสผ่านใหม่ อีกครั้ง", $locationError);
    } elseif ($confirmPassword !== $newPassword) {
        messageError("ยืนยัน รหัสผ่านใหม่ ไม่ถูกต้อง", $locationError);
    }
}

// ======================== 27. valiDate Form Transfer coins (Member) =====================================
function  validateFormAddress($memId, $addrType, $addrFname, $addrLname, $addrPhone, $province, $district, $subDistrict, $zipCode, $addrDetail, $locationError)
{
    if (empty($memId) || !is_numeric($memId)) {
        messageError("ไม่พบรหัสผู้ใช้", $locationError);
    }

    if (empty($addrType) || !is_numeric($addrType)) {
        messageError("ไม่พบรหัสประเภทที่อยู่", $locationError);
    } elseif ($addrType != 1 && $addrType != 2) {
        messageError("ประเภทที่อยู่ ไม่ถูกต้อง", $locationError);
    }

    if (empty($addrFname)) {
        messageError("กรุณาระบุชื่อ", $locationError);
    } elseif (is_numeric($addrFname)) {
        messageError("ชื่อ ต้องเป็นข้อความ", $locationError);
    } elseif (mb_strlen($addrFname, 'UTF-8') > 50) {
        messageError("ชื่อ ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    if (empty($addrLname)) {
        messageError("กรุณาระบุ นามสกุล", $locationError);
    } elseif (is_numeric($addrLname)) {
        messageError("นามสกุล ต้องเป็นข้อความ", $locationError);
    } elseif (mb_strlen($addrLname, 'UTF-8') > 50) {
        messageError("นามสกุล ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    if(empty($addrPhone)){
        messageError("กรุณาระบุ เบอร์โทรศัพท์", $locationError);
    } elseif(!preg_match('/^\d{10}$/', $addrPhone)) {
        messageError("เบอร์โทรศัพท์ต้องมี 10 หลัก", $locationError);
    }

    if(empty($province)){
        messageError("กรุณาระบุ จังหวัด", $locationError);
    }elseif(mb_strlen($province, 'UTF-8') > 100){
        messageError("จังหวัด ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if(empty($district)){
        messageError("กรุณาระบุ อำเภอ", $locationError);
    }elseif(mb_strlen($district, 'UTF-8') > 100){
        messageError("อำเภอ ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if(empty($subDistrict)){
        messageError("กรุณาระบุ ตำบล", $locationError);
    }elseif(mb_strlen($subDistrict, 'UTF-8') > 100){
        messageError("ตำบล ต้องไม่เกิน 100 ตัวอักษร", $locationError);
    }

    if(empty($zipCode)){
        messageError("กรุณาระบุ รหัสไปรษณีย์", $locationError);
    }elseif(!is_numeric($zipCode)){
        messageError("รหัสไปรษณีย์ต้องเป็นตัวเลข", $locationError);
    }elseif(mb_strlen($zipCode, 'UTF-8') != 5){
        messageError("รหัสไปรษณีย์ต้องมี 5 หลัก", $locationError);
    }

    if(empty($addrDetail)){
        messageError("กรุณาระบุ รายละเอียดที่อยู่", $locationError);
    }elseif(mb_strlen($addrDetail, 'UTF-8') > 255){
        messageError("รายละเอียดที่อยู่ ต้องไม่เกิน 255 ตัวอักษร", $locationError);
    }
}

// ======================== 27. valiDate Form Transfer coins (Member) =====================================
function valiDateFormTransferCoinMember($myId, $myCoin, $recipientId, $coin, $password, $locationError)
{
    if (empty($myId) || $myId == null || !is_numeric($myId)) {
        messageError("ไม่พบรหัสสมาชิกของคุณ", $locationError);
    }

    if (empty($myCoin) || $myCoin == null || !is_numeric($myCoin)) {
        messageError("ไม่พบเหรียญของคุณ", $locationError);
    }

    if (empty($recipientId) || $recipientId == null || !is_numeric($recipientId)) {
        messageError("ไม่พบรหัสสมาชิกของผู้รับ", $locationError);
    }

    if (empty($coin) || !ctype_digit($coin) || (int)$coin <= 0) {
        messageError("กรุณาระบุ จำนวนเหรียญที่จะโอน ให้ถูกต้องและต้องมากกว่า 0", $locationError);
    } elseif ((int)$coin > $myCoin) {
        messageError("คุณมีเหรียญไม่เพียงพอ", $locationError);
    }

    if ($password == null) {
        messageError("กรุณาระบุ รหัสผ่าน", $locationError);
    }
}
