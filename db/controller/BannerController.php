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
class BannerController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        // echo "<br> เรียกใช้ Banner Controller สำเร็จ <br>";
    }

    function getBanner()
    {
        try {
            $sql = "SELECT * FROM bs_banners";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>getBanner : " . $e->getMessage();
            return false;
        }
    }

    function checkBannerName($bnName, $bnId = NULL)
    {
        try {
            $sql = "SELECT bn_name
                    FROM bs_banners
                    WHERE bn_name = :bn_name";

            if ($bnId !== null) {
                $sql .= " AND bn_id != :bn_id";
            }

            $sql .= " LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_name', $bnName, PDO::PARAM_STR);


            if ($bnId !== NULL) {
                $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in checkBannerName : " . $e->getMessage();
            return false;
        }
    }

    function insertBanner($bnName, $bnLink, $newImg, $bnStatus)
    {
        try {
            $sql = "INSERT INTO bs_banners(bn_name, bn_link, bn_img, bn_status)
                    VALUES(:bn_name, :bn_link, :bn_img, :bn_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_name', $bnName, PDO::PARAM_STR);
            $stmt->bindParam(':bn_link', $bnLink, PDO::PARAM_STR);
            $stmt->bindParam(':bn_img', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':bn_status', $bnStatus, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertBanner : " . $e->getMessage();
            return false;
        }
    }

    function getDetailBanner($bnId)
    {
        try {
            $sql = "SELECT * FROM bs_banners
                    WHERE bn_id = :bn_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailBanner : " . $e->getMessage();
            return false;
        }
    }

    function updateDetailBanner($bnName, $bnLink, $bnStatus, $bnId){
        try{
            $sql = "UPDATE bs_banners
                    SET bn_name = :bn_name,
                        bn_link = :bn_link,
                        bn_status = :bn_status
                    WHERE bn_id = :bn_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_name', $bnName, PDO::PARAM_STR);
            $stmt->bindParam(':bn_link', $bnLink, PDO::PARAM_STR);
            $stmt->bindParam(':bn_status', $bnStatus, PDO::PARAM_INT);
            $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
           $stmt->execute();
           return true;
        }catch(PDOException $e){
            echo "<hr>Error in updateDetailBanner : " . $e->getMessage();
            return false;
        }
    }

    function updateImgBanner($newImg, $bnId){
        try{
            $sql = "UPDATE bs_banners
                    SET bn_img = :bn_img
                    WHERE bn_id = :bn_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_img', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            echo "<hr>Error in updateImgBanner : " . $e->getMessage();
            return false;
        }
    }

    function deleteBanner($bnId){
        try{
            $sql ="DELETE FROM bs_banners
                    WHERE bn_id = :bn_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
            $stmt->execute();
            return true;

        }catch(PDOException $e){
            echo "<hr>Error in deleteBanner : " . $e->getMessage();
            return false;
        }
    }
}
