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

    function checkStatusBlockedEmployees($Id){
        try{
            $sql = "SELECT emp_id, emp_status
                    FROM bs_employees
                    WHERE emp_id = :emp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':emp_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            echo "<hr>Error in checkStatusEmployees : " . $e->getMessage();
            return false;
        }
    }
}

?>