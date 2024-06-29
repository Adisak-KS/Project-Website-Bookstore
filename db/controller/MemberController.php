<!-- ========================================== Member ========================================== 
 
   1.  __construct
   2. getMember
   3. checkUsernameEmailMember
   4. insertMember
   5. getDetailMember
   6. updateDetailMember
   7. updateNewProfileMember
   8. updateNewProfileMember

============================================================================================ -->

<?php
class MemberController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Member Controller สำเร็จ <br>";
    }

    function getMember()
    {
        try {
            $sql = "SELECT mem_id, mem_profile, mem_fname, mem_lname, mem_username, mem_email, mem_status
                    FROM bs_member";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getAdmin : " . $e->getMessage();
            return false;
        }
    }

    function checkUsernameEmailMember($username, $email, $id = null)
    {
        try {
            $sql = " SELECT mem_username, mem_email
                      FROM bs_member
                      WHERE mem_username = :mem_username OR mem_email = :mem_email";

            // หากมีการส่ง $id มาด้วย
            if ($id !== null) {
                $sql .= " AND id != :id";
            }

            $sql .= " LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":mem_username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":mem_email", $email, PDO::PARAM_STR);

            // หากมีการส่ง $id มาด้วย
            if ($id !== null) {
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in  checkUsernameEmailMember : " . $e->getMessage();
        }
    }

    function insertMember($newProfile, $fname, $lname, $username, $password, $email)
    {
        try {

            // Hashed Password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


            // แทรกข้อมูลพนักงานใหม่
            $sql = " INSERT INTO bs_member (mem_profile, mem_fname, mem_lname, mem_username, mem_password, mem_email)
                    VALUES (:mem_profile, :mem_fname, :mem_lname, :mem_username, :mem_password, :mem_email)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":mem_profile", $newProfile, PDO::PARAM_STR);
            $stmt->bindParam(":mem_fname", $fname, PDO::PARAM_STR);
            $stmt->bindParam(":mem_lname", $lname, PDO::PARAM_STR);
            $stmt->bindParam(":mem_username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":mem_password", $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(":mem_email", $email, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertMember : " . $e->getMessage();
            return false;
        }
    }

    function getDetailMember($Id)
    {
        try {
            $sql = "SELECT * FROM bs_member
                    WHERE mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailMember : " . $e->getMessage();
            return false;
        }
    }

    function updateDetailMember($Id, $fname, $lname, $status)
    {
        try {
            // Update Detail
            $sql = "UPDATE bs_member
                    SET     mem_fname = :mem_fname,
                            mem_lname = :mem_lname,
                            mem_status = :mem_status,
                            mem_time_update = NOW()
                    WHERE   mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_fname', $fname, PDO::PARAM_STR);
            $stmt->bindParam(':mem_lname', $lname, PDO::PARAM_STR);
            $stmt->bindParam(':mem_status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':mem_id', $Id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateDetailMember : " . $e->getMessage();
            return false;
        }
    }

    function updateNewProfileMember($Id, $newProfile)
    {
        try {
            $sql = "UPDATE bs_member
                    SET mem_profile = :mem_profile
                    WHERE mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_profile', $newProfile);
            $stmt->bindParam(':mem_id', $Id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateNewProfileMember : " . $e->getMessage();
            return false;
        }
    }

    function deleteMember($Id)
    {
        try {
            $sql = " DELETE  FROM bs_member
                  WHERE mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":mem_id", $Id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteMember : " . $e->getMessage();
            return false;
        }
    }
}

?>