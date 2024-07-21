<?php
//  ================================= Publisher ========================================== 
/* 
    1.  __construct
    2. getPublisher
    3. checkPublisherName
    4. insertPublisher
    5. getDetailPublisher
    6. updateDetailPublisher
    7. updateNewImgPublisher
    8. deletePublisher
    9. amountProductInPublisher
    10. getPublishers10
*/
// ============================================================================================


class PublisherController extends BaseController
{

    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Product Type Controller สำเร็จ <br>";
    }

    // ============================= 2. getPublisher ===================================
    function getPublisher()
    {
        try {
            $sql = "SELECT pub_id, pub_img, pub_name, pub_status
                    FROM bs_publishers";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getPublisher : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 3. checkPublisherName ===================================
    function checkPublisherName($pubName, $pubId = null)
    {
        try {
            $sql = "SELECT pub_name
                    FROM bs_publishers
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

    // ============================= 4. insertPublisher ===================================
    function insertPublisher($newImg, $pubName, $pubDetail, $pubStatus)
    {
        try {
            $sql = "INSERT INTO bs_publishers(pub_img, pub_name, pub_detail, pub_status)
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

    // ============================= 5. getDetailPublisher ===================================
    function getDetailPublisher($Id)
    {
        try {
            $sql = "SELECT * FROM bs_publishers WHERE pub_id = :pub_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pub_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailPublisher : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 6. updateDetailPublisher ===================================
    function updateDetailPublisher($pubName, $pubDetail, $pubStatus, $pubId)
    {
        try {
            $sql = "UPDATE bs_publishers
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

    // ============================= 7. updateNewImgPublisher ===================================
    function updateNewImgPublisher($newImg, $pubId)
    {
        try {
            $sql = "UPDATE bs_publishers
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

    // ============================= 8. deletePublisher ===================================
    function deletePublisher($pubId)
    {
        try {
            $sql = "DELETE FROM bs_publishers
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

    // ============================= 9. amountProductInPublisher ===================================
    function amountProductInPublisher($Id)
    {
        try {
            $sql = "SELECT COUNT(pub_id) AS amount 
                    FROM bs_products
                    WHERE pub_id = :pub_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pub_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in amountProductInPublisher : " . $e->getMessage();
        }
    }

    // ============================= 10. getPublishers10 ===================================
    function getPublishers10($prdPreorder = null)
    {
        // ตรวจสอบค่าของ $prdPreorder และกำหนดเป็น 1 ถ้าค่าไม่ใช่ 0, 1, หรือ null
        if ($prdPreorder !== 0 && $prdPreorder !== 1 && $prdPreorder !== null) {
            $prdPreorder = 1; // ค่าเริ่มต้น
        }

        try {
            $sql = "SELECT
                        bs_publishers.pub_id,
                        bs_publishers.pub_name,
                        COUNT(bs_products.prd_id) AS product_count
                    FROM bs_publishers
                    INNER JOIN bs_products ON bs_products.pub_id = bs_publishers.pub_id
                    INNER JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id
                    INNER JOIN bs_authors ON bs_products.auth_id = bs_authors.auth_id
                    WHERE bs_products.prd_status = 1
                        AND bs_products_type.pty_status = 1
                        AND bs_publishers.pub_status = 1
                        AND bs_authors.auth_status = 1";

            // เพิ่มเงื่อนไขสำหรับ prd_preorder ถ้าค่าถูกต้อง
            if ($prdPreorder !== null) {
                $sql .= " AND bs_products.prd_preorder = :prd_preorder";
            }

            $sql .= " GROUP BY bs_publishers.pub_id, bs_publishers.pub_name
                      ORDER BY product_count DESC
                      LIMIT 10";

            // Preparing the statement
            $stmt = $this->db->prepare($sql);

            // Binding parameters
            if ($prdPreorder !== null) {
                $stmt->bindParam(':prd_preorder', $prdPreorder, PDO::PARAM_INT);
            }

            // Executing the statement
            $stmt->execute();

            // Fetching results
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Displaying error message
            echo "<hr>Error in getPublishers10 : " . $e->getMessage();
            return false;
        }
    }
}
