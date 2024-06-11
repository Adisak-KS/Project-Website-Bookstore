<!-- ========================================== Admin ========================================== 
 
   1.  __construct
   2. getAdmin
   3. checkUsernameEmailEmployees
   4. insertAdmin
   5. getDetailAdmin
   6. updateDetailAdmin
   7. updateAuthorityAdmin
   8. UpdateNewProfileAdmin

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

   function updateDetailAdmin($Id, $fname, $lname, $status)
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
         echo "<hr>Error in updateDetailAdmin: " . $e->getMessage();
         return false;
      }
   }

   function updateAuthorityAdmin($Id, $newEatId)
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

   function deleteAdmin($Id)
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

?>