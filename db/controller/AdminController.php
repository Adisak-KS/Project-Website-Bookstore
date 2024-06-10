<!-- ========================================== Admin ========================================== 
 
   1.  __construct
   2. getAdmin
   3. checkUsernameEmailEmployees
   4. insertAdmin
   5. getDetailAdmin
   6. updateDetailAdmin
   7. UpdateNewProfileAdmin

============================================================================================ -->

<?php
class AdminController extends BaseController
{
   public function __construct($db)
   {
      parent::__construct($db);
      //  echo "<br> เรียกใช้ Admin Controller สำเร็จ <br>";
   }

   function getAdmin()
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
                        GROUP_CONCAT(bs_employees_authority_type.eat_name) AS Authority,
                        bs_employees_authority_type.eat_status
                     FROM bs_employees
                     JOIN bs_employees_authority ON bs_employees.emp_id = bs_employees_authority.emp_id
                     JOIN bs_employees_authority_type ON bs_employees_authority.eat_id = bs_employees_authority_type.eat_id
                     WHERE bs_employees_authority.eat_id = 3
                     GROUP BY bs_employees.emp_id";
         $stmt = $this->db->prepare($sql);
         $stmt->execute();
         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
         return $result;
      } catch (PDOException $e) {
         echo "<hr>Error in getAdmin : " . $e->getMessage();
         return false;
      }
   }


   function checkUsernameEmailAdmin($username, $email, $id = null)
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

   function insertAdmin($newProfile, $fname, $lname, $username, $hashedPassword, $email)
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
         $authority = 3; // รหัสสิทธิ์ของพนักงานและผู้ดูแล
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
         echo "<hr>Error in insertEmployeeAdmin: " . $e->getMessage();
         return false;
      }
   }

   function getDetailAdmin($Id)
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
                  WHERE bs_employees.emp_id = :emp_id AND bs_employees_authority.eat_id = 3
                  GROUP BY bs_employees.emp_id";
         $stmt = $this->db->prepare($sql);
         $stmt->bindParam(':emp_id', $Id);
         $stmt->execute();
         return $stmt->fetch(PDO::FETCH_ASSOC);
      } catch (PDOException $e) {
         echo "<hr>Error in getDetailAdmin : " . $e->getMessage();
      }
   }

   function updateDetailAdmin($Id, $fname, $lname, $status, $authority)
   {
      $this->db->beginTransaction(); // เริ่ม transaction

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

         // Delete All Authority
         $sql = " DELETE FROM bs_employees_authority 
                     WHERE emp_id = :emp_id";
         $stmt = $this->db->prepare($sql);
         $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
         $stmt->execute();

         // Insert New Authority
         $sql = " INSERT INTO bs_employees_authority (emp_id, eat_id) 
                     VALUES (:emp_id, :eat_id)";
         $stmt = $this->db->prepare($sql);
         $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
         $stmt->bindParam(':eat_id', $authority, PDO::PARAM_INT);
         $stmt->execute();

         $this->db->commit();
         return true;
      } catch (PDOException $e) {
         $this->db->rollBack(); // ถ้าเกิดข้อผิดพลาด ให้ rollback การเปลี่ยนแปลงทั้งหมด
         echo "<hr>Error in updateDetailAdmin: " . $e->getMessage();
         return false;
      }
   }

   function updateNewProfileAdmin($Id, $newProfile)
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
}

?>