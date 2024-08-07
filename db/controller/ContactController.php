
<?php
// ========================================== Contact ========================================== 
/* 
    1.  __construct
    2. getContact
    3. getDetailContact
    4. updateDetailContact
    5. deleteDetailContact
    6. useContact
*/
// ============================================================================================


class ContactController extends BaseController
{

    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Contact Controller สำเร็จ <br>";
    }

    // ============================= 2. getContact ===================================
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

    // ============================= 3. getDetailContact ===================================
    function getDetailContact($ctId)
    {
        try {
            $sql = "SELECT * FROM bs_contacts WHERE ct_id = :ct_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ct_id', $ctId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailContact : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 4. updateDetailContact ===================================
    function updateDetailContact($ctDetail, $ctNameLink = null, $ctStatus, $ctId)
    {
        try {
            $sql = "UPDATE bs_contacts 
                    SET ct_detail = :ct_detail, 
                        ct_status = :ct_status";

            if ($ctNameLink !== null) {
                $sql .= ", ct_name_link = :ct_name_link";
            }

            $sql .= " WHERE ct_id = :ct_id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ct_detail', $ctDetail, PDO::PARAM_STR);
            $stmt->bindParam(':ct_status', $ctStatus, PDO::PARAM_INT);

            if ($ctNameLink !== null) {
                $stmt->bindParam(':ct_name_link', $ctNameLink, PDO::PARAM_STR);
            }
            $stmt->bindParam(':ct_id', $ctId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateDetailContact : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 5. deleteDetailContact ===================================
    function deleteDetailContact($ctId)
    {
        try {
            $sql = "UPDATE bs_contacts 
                    SET ct_detail = NULL,
                        ct_name_link = NULL,
                        ct_status = 0
                    WHERE ct_id = :ct_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ct_id', $ctId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteDetailContact : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 6. useContact ===================================
    function useContact()
    {
        try {
            $sql = "SELECT ct_id, ct_name, ct_detail, ct_name_link
                    FROM bs_contacts
                    WHERE ct_status = 1 AND ct_detail IS NOT NULL AND ct_detail != '' ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in useContact : " . $e->getMessage();
            return false;
        }
    }
}
