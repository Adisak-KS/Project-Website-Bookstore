<!-- ================================= Promotion ========================================== 
 
   1.  __construct
   2. getPromotion
   3. checkPublisherName
   4. insertPublisher
   5. getDetailPublisher
   6. updateDetailPublisher
   7. updateNewImgPublisher
   8. deletePublisher

============================================================================================ -->
<?php
class PromotionController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Promotion Controller สำเร็จ <br>";
    }

    function getPromotions()
    {
        try {
            $sql = "SELECT pro_id, pro_img, pro_name, pro_percent_discount, pro_time_start, pro_time_end, pro_status
                    FROM bs_promotions";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getPtomotion : " . $e->getMessage();
            return false;
        }
    }

    function checkPromotionName($proName, $proId = null)
    {
        try {
            $sql = "SELECT pro_name
                    FROM bs_promotions
                    WHERE pro_name = :pro_name ";

            if ($proId !== null) {
                $sql .= " AND pro_id != :pro_id ";
            }

            $sql .= " LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pro_name', $proName, PDO::PARAM_STR);

            if ($proId !== null) {
                $stmt->bindParam(':pro_id', $proId, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in heckPromotionName : " . $e->getMessage();
            return false;
        }
    }

    function insertPromotion($newImg, $proName, $proPercentDiscount, $proTimeStart, $proTimeEnd, $proDetail, $proStatus)
    {
        try{
            $sql = "INSERT INTO bs_promotions(pro_img, pro_name, pro_percent_discount, pro_time_start, pro_time_end, pro_detail, pro_status)
                    VALUES (:pro_img, :pro_name, :pro_percent_discount, :pro_time_start, :pro_time_end, :pro_detail, :pro_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pro_img', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':pro_name', $proName, PDO::PARAM_STR);
            $stmt->bindParam(':pro_percent_discount', $proPercentDiscount, PDO::PARAM_INT);
            $stmt->bindParam(':pro_time_start', $proTimeStart, PDO::PARAM_STR);
            $stmt->bindParam(':pro_time_end', $proTimeEnd, PDO::PARAM_STR);
            $stmt->bindParam(':pro_detail', $proDetail, PDO::PARAM_STR);
            $stmt->bindParam(':pro_status', $proStatus, PDO::PARAM_INT);
            $stmt->execute();
            return true;

        }catch(PDOException $e){
            echo "<hr>Error in insertPromotion : " . $e->getMessage();
            return false;
        }
    }

    function getDetailPromotion($Id){
        try{
            $sql = "SELECT * FROM bs_promotions WHERE pro_id = :pro_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pro_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            echo "<hr>Error in getDetailPromotionn : " . $e->getMessage();
            return false;
        }
    }

    function updateDetailPromotion($proName, $proPercentDiscount, $proTimeStart, $proTimeEnd, $proDetail, $proStatus, $proId){
        try{
            $sql = "UPDATE bs_promotions
                    SET pro_name = :pro_name,
                        pro_percent_discount = :pro_percent_discount,
                        pro_time_start = :pro_time_start,
                        pro_time_end = :pro_time_end,
                        pro_detail = :pro_detail,
                        pro_status = :pro_status,
                        pro_time_update = NOW()
                    WHERE pro_id = :pro_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pro_name', $proName, PDO::PARAM_STR);
            $stmt->bindParam(':pro_percent_discount', $proPercentDiscount, PDO::PARAM_INT);
            $stmt->bindParam(':pro_time_start', $proTimeStart, PDO::PARAM_STR);
            $stmt->bindParam(':pro_time_end', $proTimeEnd, PDO::PARAM_STR);
            $stmt->bindParam(':pro_detail', $proDetail, PDO::PARAM_STR);
            $stmt->bindParam(':pro_status', $proStatus, PDO::PARAM_INT);
            $stmt->bindParam(':pro_id', $proId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            echo "<hr>Error in updateDetailPromotion : " . $e->getMessage();
            return false;
        }
    }

    function updateImgPromotion($newImg, $proId){
        try{
            $sql = "UPDATE bs_promotions
                    SET pro_img = :pro_img,
                        pro_time_update = NOW()
                    WHERE pro_id = :pro_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pro_img', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':pro_id', $proId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            echo "<hr>Error in updateImgPromotion : " . $e->getMessage();
            return false;
        }
    }

    function deletePromotion($proId){
        try{
            $sql = "DELETE FROM bs_promotions
                    WHERE pro_id = :pro_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pro_id', $proId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            echo "<hr>Error in deletePromotion : " . $e->getMessage();
            return false;
        }
    }
}
