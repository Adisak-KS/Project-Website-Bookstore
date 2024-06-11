<!-- ========================================== Owner ========================================== 
 
   1.  __construct
   2. getOwner
   3. insertOwner
   4. getDetailOwner
   5. updateDetailOwner
   6. updateAuthorityOwner

============================================================================================ -->
<?php
class EmployeeController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        // echo "<br> เรียกใช้ Employee Controller สำเร็จ <br>";
    }

    function getEmployee()
    {
        try {
            $sql = "SELECT 
                        bs_employees.emp_id,
                        bs_employees.emp_profile,
                        bs_employees.emp_fname,
                        bs_employees.emp_lname,
                        bs_employees.emp_username,
                        bs_employees.emp_email,
                        bs_employees.emp_status,
                        GROUP_CONCAT(bs_employees_authority_type.eat_id 
                            ORDER BY bs_employees_authority_type.eat_id ASC) AS authority,
                        bs_employees_authority_type.eat_status
                    FROM bs_employees
                    JOIN bs_employees_authority ON bs_employees.emp_id = bs_employees_authority.emp_id
                    JOIN bs_employees_authority_type ON bs_employees_authority.eat_id = bs_employees_authority_type.eat_id
                    WHERE bs_employees_authority.eat_id IN (4, 5, 6)
                    GROUP BY bs_employees.emp_id;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getAdmin : " . $e->getMessage();
            return false;
        }
    }

    function insertOwner($newProfile, $fname, $lname, $username, $hashedPassword, $email)
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

            // แทรกข้อมูลสิทธิ์ของพนักงาน
            $authority = 2; // รหัสสิทธิ์ Owner
            $sql = " INSERT INTO bs_employees_authority (emp_id, eat_id) 
                      VALUES (:emp_id, :eat_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":emp_id", $lastInsertId, PDO::PARAM_INT);
            $stmt->bindParam(":eat_id", $authority, PDO::PARAM_INT);
            $stmt->execute();
            // ยืนยันการทำธุรกรรม
            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            // ยกเลิกการทำธุรกรรมในกรณีเกิดข้อผิดพลาด
            $this->db->rollBack();
            echo "<hr>Error in insertOwner : " . $e->getMessage();
            return false;
        }
    }

    function getDetailOwner($Id)
    {
        try {
            $sql = " SELECT 
                         bs_employees.emp_id,
                         bs_employees.emp_profile,
                         bs_employees.emp_fname,
                         bs_employees.emp_lname,
                         bs_employees.emp_username,
                         bs_employees.emp_email,
                         bs_employees.emp_status,
                         bs_employees.emp_time_update,
                         GROUP_CONCAT(bs_employees_authority_type.eat_id) AS authority,
                         bs_employees_authority_type.eat_status
                   FROM bs_employees
                   JOIN bs_employees_authority ON bs_employees.emp_id = bs_employees_authority.emp_id
                   JOIN bs_employees_authority_type ON bs_employees_authority.eat_id = bs_employees_authority_type.eat_id
                   WHERE bs_employees.emp_id = :emp_id AND bs_employees_authority.eat_id = 2 /* Owner */
                   GROUP BY bs_employees.emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailOwner : " . $e->getMessage();
        }
    }

    function updateDetailOwner($Id, $fname, $lname, $status)
    {
        try {
            // Update Detail
            $sql = " UPDATE bs_employees
                   SET   emp_fname = :emp_fname,
                         emp_lname = :emp_lname,
                         emp_status = :emp_status,
                         emp_time_update = NOW()
                   WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_fname', $fname, PDO::PARAM_STR);
            $stmt->bindParam(':emp_lname', $lname, PDO::PARAM_STR);
            $stmt->bindParam(':emp_status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateDetailOwner : " . $e->getMessage();
            return false;
        }
    }

    function updateAuthorityOwner($Id, $newEatId)
    {
        try {
            // ลบทุกสิทธิ์ที่มี emp_id ตรงกับ $Id ที่ส่งมา
            $sql = " DELETE FROM bs_employees_authority
                   WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
            $stmt->execute();

            // insert Authority 
            $sql = " INSERT INTO bs_employees_authority (emp_id, eat_id) 
                   VALUES (:emp_id, :eat_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
            $stmt->bindParam(':eat_id', $newEatId, PDO::PARAM_INT);
            $stmt->execute();

            // ถ้า $newEatId ที่ส่งมา เป็นเลข 4 หรือ 5 (Accounting, Sale) ให้ insert 6 (Employee) ลงไปด้วย
            if ($newEatId == 4 || $newEatId == 5) {
                $sql = " INSERT INTO bs_employees_authority (emp_id, eat_id) 
                      VALUES (:emp_id, 6)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
                $stmt->execute();
            }

            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateAuthorityAdmin : " . $e->getMessage();
            return false;
        }
    }
}
