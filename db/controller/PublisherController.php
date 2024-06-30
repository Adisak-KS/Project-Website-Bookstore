<!-- ================================= Publicher ========================================== 
 
   1.  __construct
   2. getPublisher
   3. checkPublisherName
   4. insertPublisher
   5. getDetailPublisher
   6. updateDetailPublisher
   7. updateNewImgPublisher
   8. deletePublisher

============================================================================================ -->
<?php
class PublisherController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Product Type Controller สำเร็จ <br>";
    }

    function getPublisher()
    {
        try {
            $sql = "SELECT pub_id, pub_img, pub_name, pub_status
                    FROM bs_publisher";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getPublisher : " . $e->getMessage();
            return false;
        }
    }

    function checkPublisherName($pubName, $pubId = null)
    {
        try {
            $sql = "SELECT pub_name
                    FROM bs_publisher
                    WHERE pub_name = :pub_name";

            if ($pubId !== null) {
                $sql .= "AND pub_id != :pub_id";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pub_name', $pubName);

            if ($pubId !== null) {
                $stmt->bindParam(':pub_name', $pubName);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in checkPublisherName : " . $e->getMessage();
            return false;
        }
    }

    function insertPublisher($newImg, $pubName, $pubDetail, $pubStatus)
    {
        try {
            $sql = "INSERT INTO bs_publisher(pub_img, pub_name, pub_detail, pub_status)
                    VALUES (:pub_img, :pub_name, :pub_detail, :pub_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pub_img', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':pub_name', $pubName, PDO::PARAM_STR);
            $stmt->bindParam(':pub_detail', $pubDetail, PDO::PARAM_STR);
            $stmt->bindParam(':pub_status', $pubStatus, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertPublisher : " . $e->getMessage();
            return false;
        }
    }

    function getDetailPublisher($Id)
    {
        try {
            $sql = "SELECT * FROM bs_publisher WHERE pub_id = :pub_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pub_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailPublisher : " . $e->getMessage();
            return false;
        }
    }

    function updateDetailPublisher($pubName, $pubDetail, $pubStatus, $pubId)
    {
        try {
            $sql = "UPDATE bs_publisher
                    SET pub_name = :pub_name,
                        pub_detail = :pub_detail,
                        pub_status = :pub_status,
                        pub_time_update = NOW()
                    WHERE pub_id = :pub_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pub_name', $pubName);
            $stmt->bindParam(':pub_detail', $pubDetail);
            $stmt->bindParam(':pub_status', $pubStatus);
            $stmt->bindParam(':pub_id', $pubId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateDetailPublisher : " . $e->getMessage();
            return false;
        }
    }

    function updateNewImgPublisher($newImg, $pubId)
    {
        try {
            $sql = "UPDATE bs_publisher
                        SET pub_img = :pub_img,
                            pub_time_update = NOW()
                        WHERE pub_id = :pub_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pub_img', $newImg);
            $stmt->bindParam(':pub_id', $pubId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateNewImgPublisher : " . $e->getMessage();
            return false;
        }
    }

    function deletePublisher($pubId)
    {
        try {
            $sql = "DELETE FROM bs_publisher
                    WHERE pub_id = :pub_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pub_id', $pubId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deletePublisher : " . $e->getMessage();
            return false;
        }
    }

    function amountProductInPublisher($Id)
    {
        try {
            $sql = "SELECT COUNT(pub_id) AS amount 
                    FROM bs_product
                    WHERE pub_id = :pub_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pub_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in amountProductInPublisher : " . $e->getMessage();
        }
    }
}
