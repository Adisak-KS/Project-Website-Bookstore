
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

class MemberController extends BaseController
{

    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Member Controller สำเร็จ <br>";
    }

    // ============================= 2. getMember ===================================
    function getMember()
    {
        try {
            $sql = "SELECT mem_id, mem_profile, mem_fname, mem_lname, mem_username, mem_email, mem_status
                    FROM bs_members";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getAdmin : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 3. checkUsernameEmailMember ===================================

    function checkUsernameEmailMember($username, $email, $id = null)
    {
        try {
            // เริ่มต้น SQL Query
            $sql = "SELECT mem_username, mem_email
                    FROM bs_members
                    WHERE mem_username = :mem_username
                      OR mem_email = :mem_email";

            // หากมีการส่ง $id มาด้วย เพิ่มเงื่อนไข AND
            if ($id !== null) {
                $sql .= " AND id != :id";
            }

            // จำกัดผลลัพธ์แค่ 1 แถว
            $sql .= " LIMIT 1";

            // เตรียม SQL statement
            $stmt = $this->db->prepare($sql);

            // ผูกค่าพารามิเตอร์
            $stmt->bindParam(":mem_username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":mem_email", $email, PDO::PARAM_STR);

            // หากมีการส่ง $id มาด้วย
            if ($id !== null) {
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            }

            // ทำการ execute statement
            $stmt->execute();

            // ดึงข้อมูลผลลัพธ์
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // แสดงข้อผิดพลาด
            echo "<hr>Error in checkUsernameEmailMember: " . $e->getMessage();
            return null; // เพิ่มการคืนค่าถ้าเกิดข้อผิดพลาด
        }
    }

    // ============================= 4. insertMember ===================================
    function insertMember($newProfile, $fname, $lname, $username, $password, $email)
    {
        try {

            // Hashed Password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


            // แทรกข้อมูลพนักงานใหม่
            $sql = " INSERT INTO bs_members (mem_profile, mem_fname, mem_lname, mem_username, mem_password, mem_email)
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

    // ============================= 5. getDetailMember ===================================
    function getDetailMember($Id)
    {
        try {
            $sql = "SELECT * FROM bs_members
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

    // ============================= 6. updateDetailMember ===================================
    function updateDetailMember($Id, $fname, $lname, $status)
    {
        try {
            // Update Detail
            $sql = "UPDATE bs_members
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

    // ============================= 7. updateNewProfileMember ===================================
    function updateNewProfileMember($Id, $newProfile)
    {
        try {
            $sql = "UPDATE bs_members
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

    // ============================= 8. deleteMember ===================================
    function deleteMember($Id)
    {
        try {
            $sql = " DELETE  FROM bs_members
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

    // ============================= 9. getDetailAccountMember ===================================
    function getDetailAccountMember($memId)
    {
        try {
            $sql = "SELECT mem_id, mem_profile, mem_fname, mem_lname, mem_coin, mem_username, mem_email
                    FROM bs_members
                    WHERE mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailAccountMember : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 10. updateDetailAccountMember ===================================
    function updateDetailAccountMember($fname, $lname, $username, $email, $id)
    {
        try {
            $sql = "UPDATE bs_members
                    SET mem_fname = :mem_fname,
                        mem_lname = :mem_lname,
                        mem_username = :mem_username,
                        mem_email = :mem_email
                    WHERE mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_fname', $fname, PDO::PARAM_STR);
            $stmt->bindParam(':mem_lname', $lname, PDO::PARAM_STR);
            $stmt->bindParam(':mem_username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':mem_email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':mem_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateDetailAccountMember : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 11. updateDetailAccountMember ===================================
    function checkPasswordAccountMember($id)
    {
        try {
            $sql = "SELECT mem_password
                    FROM bs_members
                    WHERE mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in checkPasswordAccountMember : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. updateMemberPassword ===================================
    function updateMemberPassword($newPassword, $id)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        try {
            $sql = "UPDATE bs_members
                    SET mem_password = :mem_password
                    WHERE mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':mem_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateMemberPassword : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. updateMemberPassword ===================================
    function checkRecipientId($recipientId)
    {
        try {
            $sql = "SELECT mem_id
                    FROM bs_members
                    WHERE mem_id = :recipient_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in checkRecipientId : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. updateMemberPassword ===================================
    function getMemberAccountHistoryCoins($memId)
    {
        try {
            $sql = "SELECT * FROM bs_members_history_coins
                    WHERE mhc_from_mem_id = :mem_id OR 	mhc_to_mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getMemberAccountHistoryCoin : " . $e->getMessage();
            return false;
        }
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
            $sql = " INSERT INTO bs_members_address(mem_id, addr_type, addr_fname, addr_lname, addr_phone, addr_province, addr_district, addr_subdistrict, addr_zip_code, addr_detail, addr_status)
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

    // ============================= 12. updateMemberPassword ===================================
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


    // ============================= 12. updateMemberPassword ===================================
    // function updateTransferCoinMember($remainCoin, $myId, $coin, $recipientId)
    // {
    //     try {
    //         $this->db->beginTransaction();

    //         // ตรวจสอบว่ามี id ของผู้รับหรือไม่
    //         $sql = "SELECT mem_id
    //                 FROM bs_members
    //                 WHERE mem_id = :recipient_id";
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
    //         $stmt->execute();
    //         $checkId = $stmt->fetch(PDO::FETCH_ASSOC);

    //         // มี id ผู้รับ
    //         if ($checkId) {
    //             // ลบเหรียญของผู้ส่ง
    //             $sql = "UPDATE bs_members
    //                     SET mem_coin = :mem_coin
    //                     WHERE mem_id = :mem_id";
    //             $stmt = $this->db->prepare($sql);
    //             $stmt->bindParam(':mem_coin', $remainCoin, PDO::PARAM_INT);
    //             $stmt->bindParam(':mem_id', $myId, PDO::PARAM_INT);
    //             $stmt->execute();


    //             // เพิ่มเหรียญของผู้ส่ง
    //             $sql = "UPDATE bs_member
    //                     SET mem_coin += :transfer_coin
    //                     WHERE mem_id = :recipient_id";
    //             $stmt = $this->db->prepare($sql);
    //             $stmt->bindParam(':transfer_coin', $coin, PDO::PARAM_INT);
    //             $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
    //             $stmt->execute();

    //             return true;
    //         }else{
    //             //ไม่มี id ผู้รับ
    //             return false;
    //         }

    //         $this->db->commit();
    //     } catch (PDOException $e) {
    //         $this->db->rollBack();
    //         echo "<hr>Error in updateTransferCoinMember : " . $e->getMessage();
    //         return false;
    //     }
    // }
}

?>