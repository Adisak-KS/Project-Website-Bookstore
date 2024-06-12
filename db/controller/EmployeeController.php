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


    function getDetailEmployee($Id)
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
                        bs_employees.emp_time_update,
                        GROUP_CONCAT(bs_employees_authority_type.eat_id ORDER BY bs_employees_authority_type.eat_id ASC) AS authority
                    FROM bs_employees
                    JOIN bs_employees_authority ON bs_employees.emp_id = bs_employees_authority.emp_id
                    JOIN bs_employees_authority_type ON bs_employees_authority.eat_id = bs_employees_authority_type.eat_id
                    WHERE bs_employees.emp_id = :emp_id GROUP BY bs_employees.emp_id ORDER BY bs_employees.emp_id ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailEmployee : " . $e->getMessage();
            return false;
        }
    }

    function updateDetailEmployee($Id, $fname, $lname, $status)
    {
        try {
            // Update Detail
            $sql = "UPDATE bs_employees
                    SET emp_fname = :emp_fname,
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

    function updateAuthorityEmployee($Id, $newEatId)
    {
        try {
            // ลบทุกสิทธิ์ที่มี emp_id ตรงกับ $Id ที่ส่งมา
            $sql = "DELETE FROM bs_employees_authority WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
            $stmt->execute();

            // Insert new authorities
            $sql = "INSERT INTO bs_employees_authority (emp_id, eat_id) 
                    VALUES (:emp_id, :eat_id)";
            $stmt = $this->db->prepare($sql);

            // Loop through the array and insert each eat_id
            foreach ($newEatId as $eatId) {
                $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
                $stmt->bindParam(':eat_id', $eatId, PDO::PARAM_INT);
                $stmt->execute();
            }

            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateAuthorityAdmin: " . $e->getMessage();
            return false;
        }
    }
}
