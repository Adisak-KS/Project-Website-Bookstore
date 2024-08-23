<?php
// ========================================== Member ========================================== 
/* 
    1.  __construct
    2. getMember
    3. checkUsernameEmailMember
    4. insertMember
    5. getDetailMember
    6. updateDetailMember
    7. updateNewProfileMember
    8. deleteMember
    9. getDetailAccountMember 
*/
// ============================================================================================
class MemberAddressController extends BaseController
{

    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Member Address Controller สำเร็จ <br>";
    }

    // ============================= 12. insertMemberAddress ===================================
    function getMemberAddress($memId)
    {
        try {
            $sql = "SELECT *
                        FROM bs_members_address 
                        WHERE mem_id = :mem_id
                        ORDER BY addr_status DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getMemberAddress : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. insertMemberAddress ===================================
    function insertMemberAddress($memId, $addrType, $addrFname, $addrLname, $addrPhone, $province, $district, $subDistrict, $zipCode, $addrDetail, $addrStatus)
    {
        try {
            $sql = "INSERT INTO bs_members_address(mem_id, addr_type, addr_fname, addr_lname, addr_phone, addr_province, addr_district, addr_subdistrict, addr_zip_code, addr_detail, addr_status)
                    VALUES(:mem_id, :addr_type, :addr_fname, :addr_lname, :addr_phone, :addr_province, :addr_district, :addr_subdistrict, :addr_zip_code, :addr_detail, :addr_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':addr_type', $addrType, PDO::PARAM_INT);
            $stmt->bindParam(':addr_fname', $addrFname, PDO::PARAM_STR);
            $stmt->bindParam(':addr_lname', $addrLname, PDO::PARAM_STR);
            $stmt->bindParam(':addr_phone', $addrPhone, PDO::PARAM_STR);
            $stmt->bindParam(':addr_province', $province, PDO::PARAM_STR);
            $stmt->bindParam(':addr_district', $district, PDO::PARAM_STR);
            $stmt->bindParam(':addr_subdistrict', $subDistrict, PDO::PARAM_STR);
            $stmt->bindParam(':addr_zip_code', $zipCode, PDO::PARAM_INT);
            $stmt->bindParam(':addr_detail', $addrDetail, PDO::PARAM_STR);
            $stmt->bindParam(':addr_status', $addrStatus, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertMemberAddress : " . $e->getMessage();
            return false;
        }
    }


    // ============================= 12. updateMemberPassword ===================================
    function getDetailMemberAddress($addrId, $memId)
    {
        try {
            $sql = "SELECT * 
                        FROM bs_members_address 
                        WHERE addr_id = :addr_id AND mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':addr_id', $addrId, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailMemberAddress : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. insertMemberAddress ===================================
    function updateMemberAddress($addrId, $memId, $addrType, $addrFname, $addrLname, $addrPhone, $province, $district, $subDistrict, $zipCode, $addrDetail)
    {
        try {
            $sql = "UPDATE bs_members_address
                    SET addr_type = :addr_type,
                        addr_fname = :addr_fname,
                        addr_lname = :addr_lname,
                        addr_phone = :addr_phone,
                        addr_province = :addr_province,
                        addr_district = :addr_district,
                        addr_subdistrict = :addr_subdistrict,
                        addr_zip_code = :addr_zip_code,
                        addr_detail = :addr_detail
                    WHERE addr_id = :addr_id AND mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':addr_type', $addrType, PDO::PARAM_INT);
            $stmt->bindParam(':addr_fname', $addrFname, PDO::PARAM_STR);
            $stmt->bindParam(':addr_lname', $addrLname, PDO::PARAM_STR);
            $stmt->bindParam(':addr_phone', $addrPhone, PDO::PARAM_STR);
            $stmt->bindParam(':addr_province', $province, PDO::PARAM_STR);
            $stmt->bindParam(':addr_district', $district, PDO::PARAM_STR);
            $stmt->bindParam(':addr_subdistrict', $subDistrict, PDO::PARAM_STR);
            $stmt->bindParam(':addr_zip_code', $zipCode, PDO::PARAM_INT);
            $stmt->bindParam(':addr_detail', $addrDetail, PDO::PARAM_STR);
            $stmt->bindParam(':addr_id', $addrId, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateMemberAddress : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. updateMemberPassword ===================================
    function updateMemberAddressStatus($addrId, $memId)
    {
        try {
            $this->db->beginTransaction();

            // แก้ไข status ให้เป็น 1
            $sql = "UPDATE bs_members_address
                        SET addr_status = 1
                        WHERE addr_id = :addr_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':addr_id', $addrId, PDO::PARAM_INT);
            $stmt->execute();


            // แก้ไข status ของรายการอื่น ๆ เป็น 0
            $sql = "UPDATE bs_members_address
                        SET addr_status = 0
                        WHERE mem_id = :mem_id AND addr_id != :addr_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':addr_id', $addrId, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in updateMemberAddressStatus : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. insertMemberAddress ===================================
    function deleteAddress($addrId)
    {
        try {
            $sql = "DELETE FROM bs_members_address 
                    WHERE addr_id = :addr_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':addr_id', $addrId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteAddress : " . $e->getMessage();
            return false;
        }
    }

}
