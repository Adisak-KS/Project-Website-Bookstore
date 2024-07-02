<!-- ================================= Payment =============================================== 
 
   1.  __construct
   2. getPayment
   3. checkProductTypeName
   4. insertProductTyp
   5. getDetailProductType
   6. updateDetailProductType
   7. updateNewCoverProductType
   8. DeleteProductType

============================================================================================ -->
<?php
class PaymentController extends BaseController
{

    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Payment Controller สำเร็จ <br>";
    }

    function getPayment()
    {
        try {
            $sql = "SELECT pmt_id, pmt_bank, pmt_bank_logo, pmt_name, pmt_number, pmt_status
                    FROM bs_payments
                    ORDER BY pmt_status DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>getPayment : " . $e->getMessage();
            return false;
        }
    }

    function checkPaymentNumber($pmtNumber, $pmtId = null)
    {
        try {
            $sql = "SELECT pmt_id, pmt_number 
                        FROM bs_payments
                        WHERE pmt_number = :pmt_number ";

            if ($pmtId !== null) {
                $sql .= " AND pmt_id != :pmt_id";
            }

            $sql .= " LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pmt_number', $pmtNumber, PDO::PARAM_STR);

            if ($pmtId !== null) {
                $stmt->bindParam(':pmt_id', $pmtId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in checkPaymentNumber : " . $e->getMessage();
            return false;
        }
    }

    function insertPayment($pmtBank, $newImg, $pmtName, $pmtNumber, $pmtDetail, $pmtStatus)
    {
        try {
            $this->db->beginTransaction();

            // เพิ่มข้อมูล
            $sql = "INSERT INTO bs_payments (pmt_bank, pmt_bank_logo, pmt_name, pmt_number, pmt_detail, pmt_status)
                    VALUES (:pmt_bank, :pmt_bank_logo, :pmt_name, :pmt_number, :pmt_detail, :pmt_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pmt_bank', $pmtBank, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_bank_logo', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_name', $pmtName, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_number', $pmtNumber, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_detail', $pmtDetail, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_status', $pmtStatus, PDO::PARAM_INT);
            $stmt->execute();

            $lastInsertId = $this->db->lastInsertId();

            if ($pmtStatus == 1) {
                // update Status อื่นให้เป็น 0 ทุก Id ที่ไม่ใช่ Id ตัวเอง
                $sql = "UPDATE bs_payments 
                        SET pmt_status = 0
                        WHERE pmt_id != :pmt_id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':pmt_id', $lastInsertId, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in insertPayment : " . $e->getMessage();
            return false;
        }
    }

    function updatePaymentStatus($pmtStatus, $pmtId)
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE bs_payments
                    SET pmt_status = :pmt_status
                    WHERE pmt_id = :pmt_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pmt_status', $pmtStatus, PDO::PARAM_INT);
            $stmt->bindParam(':pmt_id', $pmtId, PDO::PARAM_INT);
            $stmt->execute();

            if ($pmtStatus == 1) {
                // อัปเดตสถานะการชำระเงินอื่นทั้งหมดเป็น 0
                $sql = "UPDATE bs_payments
                        SET pmt_status = 0
                        WHERE pmt_id != :pmt_id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':pmt_id', $pmtId, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in updatePaymentStatus : " . $e->getMessage();
            return true;
        }
    }

    function getDetailPayment($pmtId)
    {
        try {
            $sql = "SELECT * FROM bs_payments WHERE pmt_id = :pmt_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pmt_id', $pmtId, PDO::PARAM_INT);
            $stmt->execute();
            $result =  $stmt->fetch(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailPayment : " . $e->getMessage();
            return false;
        }
    }

    function updatePaymentDetail($pmtBank, $pmtName, $pmtNumber, $pmtDetail, $pmtId)
    {
        try {
            $sql = "UPDATE bs_payments 
                    SET pmt_bank = :pmt_bank,
                        pmt_name = :pmt_name,
                        pmt_number = :pmt_number,
                        pmt_detail = :pmt_detail
                    WHERE pmt_id = :pmt_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pmt_bank', $pmtBank, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_name', $pmtName, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_number', $pmtNumber, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_detail', $pmtDetail, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_id', $pmtId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updatePaymentDetail : " . $e->getMessage();
            return false;
        }
    }

    function updatePaymentNewLogo($newImg, $pmtId)
    {
        try {
            $sql = "UPDATE bs_payments
                    SET pmt_bank_logo = :pmt_bank_logo
                    WHERE pmt_id = :pmt_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pmt_bank_logo', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_id', $pmtId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updatePaymentNewLogo : " . $e->getMessage();
            return false;
        }
    }

    function updatePaymentNewQrcode($newImg, $pmtId)
    {
        try {
            $sql = "UPDATE bs_payments
                    SET pmt_qrcode = :pmt_qrcode
                    WHERE pmt_id = :pmt_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pmt_qrcode', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':pmt_id', $pmtId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updatePaymentNewLogo : " . $e->getMessage();
            return false;
        }
    }

    function deleteQrcode($pmtId){
        try {
            $sql = "UPDATE bs_payments
                    SET pmt_qrcode = NULL
                    WHERE pmt_id = :pmt_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pmt_id', $pmtId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            echo "<hr>Error in deleteQrcode : " . $e->getMessage();
            return false;
        }
    }

    function deletePayment($pmtId){
        try{
            $sql = "DELETE FROM bs_payments
                    WHERE pmt_id = :pmt_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pmt_id', $pmtId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            echo "<hr>Error in deletePayment : " . $e->getMessage();
            return false;
        }
    }
}
