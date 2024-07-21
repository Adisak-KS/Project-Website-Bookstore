<?php
// ========================================== Employees ========================================== 
/* 
    1.  __construct
    2. getEmployee
    3. getDetailEmployee
    4. updateDetailEmployee
    5. updateAuthorityEmployee
    6. updateEmployeeDataProfile
    7. updateEmployeeUsername
    8. updateEmployeeEmail
    9. checkEmployeePassword
    10. updateEmployeePassword
*/
// ============================================================================================

class EmployeeController extends BaseController
{

    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        // echo "<br> เรียกใช้ Employee Controller สำเร็จ <br>";
    }

    // ============================= 2. getEmployee ===================================
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

    // ============================= 3. getDetailEmployee ===================================
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

    // ============================= 4. updateDetailEmployee ===================================
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

    // ============================= 5. updateAuthorityEmployee ===================================
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

    // ============================= 6. updateEmployeeDataProfile ===================================
    function updateEmployeeDataProfile($fname, $lname, $Id)
    {

        try {
            $sql = "UPDATE bs_employees 
                    SET emp_fname = :emp_fname,
                        emp_lname = :emp_lname
                    WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_fname', $fname, PDO::PARAM_STR);
            $stmt->bindParam(':emp_lname', $lname, PDO::PARAM_STR);
            $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateEmployeeAccount : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 7. updateEmployeeUsername ===================================
    function updateEmployeeUsername($username, $Id)
    {

        try {
            $sql = "UPDATE bs_employees 
                    SET emp_username = :emp_username
                    WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateEmployeeUsername : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 8. updateEmployeeEmail ===================================
    function updateEmployeeEmail($email, $Id)
    {

        try {
            $sql = "UPDATE bs_employees 
                    SET emp_email = :emp_email
                    WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateEmployeeEmail : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 9. checkEmployeePassword ===================================
    function checkEmployeePassword($Id)
    {
        try {
            $sql = "SELECT emp_password
            FROM bs_employees
            WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":emp_id", $Id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in checkEmployeePassword : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 10. updateEmployeePassword ===================================
    function updateEmployeePassword($newPassword, $Id)
    {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE bs_employees
                    SET emp_password = :emp_password
                    WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':emp_id', $Id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateEmployeePassword : " . $e->getMessage();
            return false;
        }
    }
}
