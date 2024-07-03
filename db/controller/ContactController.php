<!-- ========================================== Contact ========================================== 
 
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
class ContactController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Contact Controller สำเร็จ <br>";
    }

    function getContact()
    {
        try {
            $sql = "SELECT * FROM bs_contacts";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getContact : " . $e->getMessage();
            return false;
        }
    }

    function getDetailContact($ctId){
        try {
            $sql = "SELECT * FROM bs_contacts WHERE ct_id = :ct_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ct_id', $ctId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }catch(PDOException $e){
            echo "<hr>Error in getDetailContact : " . $e->getMessage();
            return false;
        }
    }

    function updateDetailContact($ctDetail, $ctStatus, $ctId){
        try{
            $sql = "UPDATE bs_contacts 
                    SET ct_detail = :ct_detail, 
                        ct_status = :ct_status
                    WHERE ct_id = :ct_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ct_detail', $ctDetail, PDO::PARAM_STR);
            $stmt->bindParam(':ct_status', $ctStatus, PDO::PARAM_INT);
            $stmt->bindParam(':ct_id', $ctId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            echo "<hr>Error in updateDetailContact : " . $e->getMessage();
            return false;
        }
    }
    function deleteDetailContact($ctId){
        try{
            $sql = "UPDATE bs_contacts 
                    SET ct_detail = NULL,
                        ct_status = 0
                    WHERE ct_id = :ct_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ct_id', $ctId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            echo "<hr>Error in deleteDetailContact : " . $e->getMessage();
            return false;
        }
    }
}
