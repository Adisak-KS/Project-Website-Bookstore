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
                    FROM bs_author";
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
                    FROM bs_author
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
            $sql = "INSERT INTO bs_author(auth_img, auth_name, auth_detail, auth_status)
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
                    FROM bs_author
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
            $sql = "UPDATE bs_author
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
            $sql = "UPDATE bs_author
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
            $sql = "DELETE FROM bs_author
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
                    FROM bs_product
                    WHERE auth_id = :auth_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':auth_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in amountProductInAuthor : " . $e->getMessage();
        }
    }
}
