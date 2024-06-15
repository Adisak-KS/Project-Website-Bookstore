<!-- ================ Base Controller (Controller เริ่มต้น แลัรวม Function ที่ใช้งานร่วมกัน) ============
 
   1.  __construct
   2. getEmmpoyeesAuthorityTypeDefault
   3. insertEmployeesTypeDefault
   4. getEmployeesSuperAdminDefault
   5. checkUsernameEmailEmployees
   6. updateNewProfileEmployees
   7. deleteEmployees
   8. checkLoginEmployees

============================================================================================ -->
<?php

class BaseController
{
    protected $db;

    public function __construct($conn)
    {
        $this->db = $conn;
        // echo "<br> เรียกใช้ Base Controller สำเร็จ <br>";
    }



    // Check Employees Authority Type Default
    function getEmmpoyeesAuthorityTypeDefault()
    {
        try {
            $sql = "SELECT eat_id, eat_name
                    FROM bs_employees_authority_type
                    WHERE eat_name
                    IN ('Super Admin', 'Owner', 'Admin', 'Accounting', 'Sale', 'Employee')";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getEmmpoyeesAuthorityTypeDefault : " . $e->getMessage();
            return false;
        }
    }

    // Insert Employee Authority Type Default
    function insertEmployeesTypeDefault()
    {
        try {
            // ตรวจสอบ Employee Authority Type 
            $check = $this->getEmmpoyeesAuthorityTypeDefault();


            // สร้างรายการของสิทธิ์ที่ต้องการในตาราง
            $requiredAuthorityTypes = [
                ['id' => 1, 'name' => 'Super Admin',    'eat_detail' => 'เจ้าของระบบ'],
                ['id' => 2, 'name' => 'Owner',          'eat_detail' => 'เจ้าของร้าน / ผู้บริหาร'],
                ['id' => 3, 'name' => 'Admin',          'eat_detail' => 'ผู้ดูแลระบบ'],
                ['id' => 4, 'name' => 'Accounting',     'eat_detail' => 'พนักงานบัญชี'],
                ['id' => 5, 'name' => 'Sale',           'eat_detail' => 'พนักงานขาย'],
                ['id' => 6, 'name' => 'Employee',       'eat_detail' => 'พนักงานทั่วไป']
            ];


            // หาประเภทสิทธิ์ที่ขาดหายไปในฐานข้อมูล
            $missingAuthorityTypes = array_diff(array_column($requiredAuthorityTypes, 'name'), array_column($check, 'eat_name'));


            // หากมีสิทธิ์ที่หายไป ให้ทำการเพิ่มเข้าไปในฐานข้อมูล
            if (!empty($missingAuthorityTypes)) {

                foreach ($missingAuthorityTypes as $authorityType) {

                    // หาข้อมูลของสิทธิ์ที่ต้องการเพิ่ม
                    $type = array_filter($requiredAuthorityTypes, fn ($item) => $item['name'] === $authorityType);

                    // ถ้าพบข้อมูลสิทธิ์ที่ต้องการเพิ่ม
                    if (!empty($type)) {
                        $type = current($type);
                        // เพิ่มสิทธิ์ที่ไม่มีลงในฐานข้อมูล
                        $sql = "INSERT INTO bs_employees_authority_type (eat_id, eat_name, eat_detail) 
                                VALUES (:eat_id, :eat_name, :eat_detail)";
                        $stmt = $this->db->prepare($sql);
                        $stmt->bindParam(':eat_id', $type['id']);
                        $stmt->bindParam(':eat_name', $type['name']);
                        $stmt->bindParam(':eat_detail', $type['eat_detail']);
                        $stmt->execute();
                    }
                }
            }
        } catch (PDOException $e) {
            echo "<hr>Error in insertEmployeesTypeDefault : " . $e->getMessage();
            return false;
        }
    }

    // check Super Admin
    function getEmployeesSuperAdminDefault()
    {
        try {

            $sql = "SELECT 
                        bs_employees.emp_id,
                        bs_employees.emp_username,
                        bs_employees.emp_email,
                        bs_employees.emp_status,
                        GROUP_CONCAT(bs_employees_authority_type.eat_id) AS authority
                     FROM bs_employees
                     JOIN bs_employees_authority ON bs_employees.emp_id = bs_employees_authority.emp_id
                     JOIN bs_employees_authority_type ON bs_employees_authority.eat_id = bs_employees_authority_type.eat_id
                     WHERE bs_employees_authority.eat_id = 1 AND bs_employees.emp_status = 1
                     GROUP BY bs_employees.emp_id
                     LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getEmployeesSuperAdminDefault : " . $e->getMessage();
            return false;
        }
    }




    // check Usernname and Email  Employees
    function checkUsernameEmailEmployees($username, $email, $id = null)
    {
        try {
            $sql = " SELECT emp_username, emp_email
                      FROM bs_employees
                      WHERE emp_username = :emp_username OR emp_email = :emp_email";

            // หากมีการส่ง $id มาด้วย
            if ($id !== null) {
                $sql .= " AND id != :id";
            }

            $sql .= " LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":emp_username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":emp_email", $email, PDO::PARAM_STR);

            // หากมีการส่ง $id มาด้วย
            if ($id !== null) {
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in  checkUsernameEmailEmployees : " . $e->getMessage();
        }
    }

    function insertEmployees($newProfile, $fname, $lname, $username, $hashedPassword, $email, $eatId)
    {
        try {
            // เริ่มต้น transaction
            $this->db->beginTransaction();

            // แทรกข้อมูลพนักงานใหม่
            $sql = " INSERT INTO bs_employees (emp_profile, emp_fname, emp_lname, emp_username, emp_password, emp_email)
                    VALUES (:emp_profile, :emp_fname, :emp_lname, :emp_username, :emp_password, :emp_email)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":emp_profile", $newProfile, PDO::PARAM_STR);
            $stmt->bindParam(":emp_fname", $fname, PDO::PARAM_STR);
            $stmt->bindParam(":emp_lname", $lname, PDO::PARAM_STR);
            $stmt->bindParam(":emp_username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":emp_password", $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(":emp_email", $email, PDO::PARAM_STR);
            $stmt->execute();

            // ดึง ID ของพนักงานที่แทรกล่าสุด
            $lastInsertId = $this->db->lastInsertId();

            // เพิ่มสิทธิ์ของพนักงาน
            $sql = "INSERT INTO bs_employees_authority (emp_id, eat_id) 
                    VALUES (:emp_id, :eat_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":emp_id", $lastInsertId, PDO::PARAM_INT);
            $stmt->bindParam(":eat_id", $eatId, PDO::PARAM_INT);
            $stmt->execute();
            // ยืนยันการทำธุรกรรม
            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            // ยกเลิกการทำธุรกรรมในกรณีเกิดข้อผิดพลาด
            $this->db->rollBack();
            echo "<hr>Error in insertEmployees : " . $e->getMessage();
            return false;
        }
    }

    function insertSuperAdminDefault()
    {
        try {

            $check = $this->getEmployeesSuperAdminDefault();
            if (!$check) {

                $fname = "ผู้ดูแลระบบ";
                $lname = "สูงสุด";
                $username = "superAdmin";
                $hashedPassword = password_hash("superAdmin", PASSWORD_DEFAULT);
                $email = "Adisak@example@gmail.com";
                $eatId = 1;


                // เงื่อนไขรูปภาพ default
                $folderUploads = "../uploads/img_employees/";
                $imgDefault = "default.png";
                $defaultImagePath = $folderUploads . $imgDefault;
                $allowedExtensions = ['png', 'jpg', 'jpeg'];
                $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
                $fileExtension = pathinfo($defaultImagePath, PATHINFO_EXTENSION);

                function generateUniqueProfileSuperAdmin($extension, $folder)
                {
                    do {
                        $fileName = 'profile_' . uniqid() . bin2hex(random_bytes(10)) . time() . '.' . $extension;
                    } while (file_exists($folder . $fileName));
                    return $fileName;
                }

                // ส่มชื่อรูปภาพใหม่
                $newProfile = generateUniqueProfileSuperAdmin($fileExtension, $folderUploads);
                $targetFilePath = $folderUploads . $newProfile;

                // Copy default image to new file
                if (copy($defaultImagePath, $targetFilePath)) {

                    // Insert Employees
                    $insertSuperAdminDefault = $this->insertEmployees($newProfile, $fname, $lname, $username, $hashedPassword, $email, $eatId);
                }
            }
        } catch (PDOException $e) {
            echo "<hr>Error in insertSuperAdminDefault : " . $e->getMessage();
            return false;
        }
    }

    // update New PRofile Employees
    function updateNewProfileEmployees($Id, $newProfile)
    {
        try {
            $sql = " UPDATE bs_employees
                   SET emp_profile = :emp_profile
                   WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_profile', $newProfile);
            $stmt->bindParam(':emp_id', $Id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateNewProfileAdmin : " . $e->getMessage();
            return false;
        }
    }

    function deleteEmployees($Id)
    {
        try {
            $sql = " DELETE  FROM bs_employees
                   WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":emp_id", $Id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteAdmin : " . $e->getMessage();
            return false;
        }
    }
}
