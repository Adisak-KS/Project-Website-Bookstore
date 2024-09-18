<?php
// ========================================== Cart ========================================== 
/* 
    1.  __construct
    2. getMemberCoinHistory
    3. checkRecipientId
    4. transferCoinToMember
*/
// ============================================================================================
class CoinHistoryController extends BaseController
{
    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Coin History Controller สำเร็จ <br>";
    }

    // ============================= 2. getMemberCoinHistory ===================================
    function getMemberCoinHistory($memId)
    {
        try {
            $sql = "SELECT
                        bs_members_history_coins.mhc_from_mem_id,
                        bs_members_history_coins.mhc_to_mem_id,
                        bs_members_history_coins.mhc_coin_amount,
                        bs_members_history_coins.mhc_transaction_type,
                        bs_members_history_coins.ord_id,
                        bs_members_history_coins.mhc_time,
                        from_member.mem_username AS from_mem_username,
                        to_member.mem_username AS to_mem_username
                    FROM bs_members_history_coins
                    LEFT JOIN bs_members from_member ON bs_members_history_coins.mhc_from_mem_id = from_member.mem_id
                    LEFT JOIN bs_members to_member ON bs_members_history_coins.mhc_to_mem_id = to_member.mem_id
                    WHERE bs_members_history_coins.mhc_from_mem_id = :mem_id OR bs_members_history_coins.mhc_to_mem_id = :mem_id
                    ORDER BY bs_members_history_coins.mhc_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getMemberCoinHistory : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 3. checkRecipientId ===================================
    function checkRecipientId($mhcToMemId)
    {
        try {
            $sql = "SELECT mem_id
                    FROM bs_members
                    WHERE mem_id = :mem_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $mhcToMemId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in checkRecipientId : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 4. transferCoinToMember ===================================
    function transferCoinToMember($mhcFromMemId, $mhcToMemId, $mhcCoinAmount)
    {
        try {
            $this->db->beginTransaction();

            // ลดเหรียญของผู้โอน
            $sql = "UPDATE bs_members
                    SET mem_coin = mem_coin - :mhc_coin_amount
                    WHERE mem_id = :mhc_from_mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mhc_coin_amount', $mhcCoinAmount, PDO::PARAM_INT);
            $stmt->bindParam(':mhc_from_mem_id', $mhcFromMemId, PDO::PARAM_INT);
            $stmt->execute();

            // เพิ่มเหรียญให้กับผู้รับ
            $sql = "UPDATE bs_members
                    SET mem_coin = mem_coin + :mhc_coin_amount
                    WHERE mem_id = :mhc_to_mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mhc_coin_amount', $mhcCoinAmount, PDO::PARAM_INT);
            $stmt->bindParam(':mhc_to_mem_id', $mhcToMemId, PDO::PARAM_INT);
            $stmt->execute();

            // บันทึกประวัติการโอนเหรียญ
            $sql = "INSERT INTO bs_members_history_coins(mhc_from_mem_id, mhc_to_mem_id, mhc_coin_amount, mhc_transaction_type)
                    VALUES(:mhc_from_mem_id, :mhc_to_mem_id, :mhc_coin_amount, 'transfer')";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mhc_from_mem_id', $mhcFromMemId, PDO::PARAM_INT);
            $stmt->bindParam(':mhc_to_mem_id', $mhcToMemId, PDO::PARAM_INT);
            $stmt->bindParam(':mhc_coin_amount', $mhcCoinAmount, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in transferCoinToMember : " . $e->getMessage();
            return false;
        }
    }
}
