<!-- ========================================== Author ========================================== 
 
   1.  __construct
   2. getAuthor
   3. checkAuthorName
   4. insertAdmin
   5. getDetailAuthor
   6. updateDetailAuthor
   7. updateImgAuthor
   8. deleteAuthor

============================================================================================ -->

<?php
class AuthorController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Author Controller สำเร็จ <br>";
    }

    function getAuthor()
    {
        try {
            $sql = "SELECT auth_id, auth_img, auth_name, auth_status
                    FROM bs_authors";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getAuthor : " . $e->getMessage();
            return false;
        }
    }

    function checkAuthorName($authName, $authId = null)
    {
        try {
            $sql = "SELECT auth_name  
                    FROM bs_authors
                    WHERE auth_name = :auth_name ";

            if ($authId !== null) {
                $sql .= "AND auth_id != :auth_id ";
            }

            $sql .= "LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':auth_name', $authName, PDO::PARAM_STR);

            if ($authId !== null) {
                $stmt->bindParam(':auth_id', $authId);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in checkAuthorName : " . $e->getMessage();
            return false;
        }
    }

    function insertAuthor($newImg, $authName, $authDetail, $authStatus)
    {
        try {
            $sql = "INSERT INTO bs_authors(auth_img, auth_name, auth_detail, auth_status)
                    VALUES (:auth_img, :auth_name, :auth_detail, :auth_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':auth_img', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':auth_name', $authName, PDO::PARAM_STR);
            $stmt->bindParam(':auth_detail', $authDetail, PDO::PARAM_STR);
            $stmt->bindParam(':auth_status', $authStatus, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertAuthor : " . $e->getMessage();
            return false;
        }
    }

    function getDetailAuthor($Id)
    {
        try {
            $sql = "SELECT * 
                    FROM bs_authors
                    WHERE auth_id = :auth_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':auth_id', $Id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailAuthor : " . $e->getMessage();
            return false;
        }
    }

    function updateDetailAuthor($authName, $authDetail, $authStatus, $authId)
    {
        try {
            $sql = "UPDATE bs_authors
                    SET auth_name = :auth_name,
                        auth_detail = :auth_detail,
                        auth_status = :auth_status,
                        auth_time_update = NOW()
                    WHERE auth_id = :auth_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':auth_name', $authName, PDO::PARAM_STR);
            $stmt->bindParam(':auth_detail', $authDetail, PDO::PARAM_STR);
            $stmt->bindParam(':auth_status', $authStatus, PDO::PARAM_INT);
            $stmt->bindParam(':auth_id', $authId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateDetailAuthor : " . $e->getMessage();
            return false;
        }
    }

    function updateImgAuthor($newImg, $authId)
    {
        try {
            $sql = "UPDATE bs_authors
                    SET auth_img = :auth_img,
                        auth_time_update = NOW()
                    WHERE auth_id = :auth_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':auth_img', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':auth_id', $authId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateImgAuthor : " . $e->getMessage();
            return false;
        }
    }

    function deleteAuthor($authId)
    {
        try {
            $sql = "DELETE FROM bs_authors
                    WHERE auth_id = :auth_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':auth_id', $authId);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteAuthor : " . $e->getMessage();
            return false;
        }
    }

    function amountProductInAuthor($Id)
    {
        try {
            $sql = "SELECT COUNT(auth_id) AS amount 
                    FROM bs_products
                    WHERE auth_id = :auth_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':auth_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in amountProductInAuthor : " . $e->getMessage();
        }
    }

    function getAuthors10($prdPreorder = null)
    {
        // ตรวจสอบค่าของ $prdPreorder และกำหนดเป็น 1 ถ้าค่าไม่ใช่ 0, 1, หรือ null
        if ($prdPreorder !== 0 && $prdPreorder !== 1 && $prdPreorder !== null) {
            $prdPreorder = 1; // ค่าเริ่มต้น
        }
    
        try {
            $sql = "SELECT
                        bs_authors.auth_id,
                        bs_authors.auth_name,
                        COUNT(bs_products.prd_id) AS product_count
                    FROM bs_authors
                    INNER JOIN bs_products ON bs_products.auth_id = bs_authors.auth_id
                    INNER JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id
                    INNER JOIN bs_publishers ON bs_products.pub_id = bs_publishers.pub_id
                    WHERE bs_products.prd_status = 1 
                        AND bs_products_type.pty_status = 1
                        AND bs_publishers.pub_status = 1
                        AND bs_authors.auth_status = 1";
    
            // เพิ่มเงื่อนไขสำหรับ prd_preorder ถ้าค่าถูกต้อง
            if ($prdPreorder !== null) {
                $sql .= " AND bs_products.prd_preorder = :prd_preorder";
            }
    
            $sql .= " GROUP BY bs_authors.auth_id
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
            echo "<hr>Error in getAuthors10 : " . $e->getMessage();
            return false;
        }
    }
    
}
