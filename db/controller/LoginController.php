<!-- ============================== Login ==========================================================
 
   1.  __construct
   2. checkLoginEmployees
   3. insertEmployeesTypeDefault
   4. getEmployeesSuperAdminDefault
   5. checkUsernameEmailEmployees
   6. updateNewProfileEmployees
   7. deleteEmployees
   8. checkLoginEmployees

================================================================================================ -->
<?php
class LoginController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        // echo "<br> เรียกใช้ Login Controller สำเร็จ <br>";
    }

    function checkLoginEmployees($usernameEmail, $password)
    {
        try {
            $sql = "SELECT emp_id, emp_username, emp_password, emp_email, emp_status
                    FROM bs_employees
                    WHERE emp_username = :emp_username OR emp_email = :emp_email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_username', $usernameEmail);
            $stmt->bindParam(':emp_email', $usernameEmail);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in checkLoginEmployees : " . $e->getMessage();
            return false;
        }
    }

    function checkStatusBlockedEmployees($Id)
    {
        try {
            $sql = "SELECT emp_id, emp_status
                    FROM bs_employees
                    WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in checkStatusEmployees : " . $e->getMessage();
            return false;
        }
    }

    function useLoginEmployees($empId)
    {
        try {
            $sql = "SELECT 
                        bs_employees.emp_id,
                        bs_employees.emp_profile,
                        bs_employees.emp_fname,
                        bs_employees.emp_lname,
                        bs_employees.emp_username,
                        bs_employees.emp_status,
                        GROUP_CONCAT(bs_employees_authority_type.eat_id) AS authority
                    FROM bs_employees
                    JOIN bs_employees_authority ON bs_employees.emp_id = bs_employees_authority.emp_id
                    JOIN bs_employees_authority_type ON bs_employees_authority.eat_id = bs_employees_authority_type.eat_id
                    WHERE bs_employees.emp_id = :emp_id AND  bs_employees.emp_status = 1
                    GROUP BY bs_employees.emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_id', $empId);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in useLoginEmployees : " . $e->getMessage();
            return false;
        }
    }
}

?>