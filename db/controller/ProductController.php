<!-- ================================= Product =============================================== 
 
   1.  __construct
   2. getProductType
   3. checkProductTypeName
   4. insertProductTyp
   5. getDetailProductType
   6. updateDetailProductType
   7. updateNewCoverProductType
   8. eleteProductType

============================================================================================ -->
<?php

class ProductController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Product Controller สำเร็จ <br>";
    }

    function getProduct()
    {
        try {

            $sql = "SELECT
                        bs_product.prd_id,
                        bs_product.prd_name,
                        bs_product.prd_img1,
                        bs_product.prd_price,
                        bs_product.prd_percent_discount,
                        bs_product.prd_preorder,
                        bs_product.pty_id,
                        bs_product.prd_status,
                        bs_product_type.pty_name
                    FROM bs_product
                    JOIN bs_product_type ON bs_product.pty_id = bs_product_type.pty_id;";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getProduct : " . $e->getMessage();
            return false;
        }
    }

    function getProductType()
    {
        try {

            $sql = "SELECT pty_id, pty_name FROM bs_product_type";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in  getProductType : " . $e->getMessage();
            return false;
        }
    }

    function getPublisher()
    {
        try {

            $sql = "SELECT pub_id, pub_name FROM bs_publisher";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in  getProductType : " . $e->getMessage();
            return false;
        }
    }

    function getAuthor()
    {
        try {
            $sql = "SELECT auth_id, auth_name FROM bs_author";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in  getProductType : " . $e->getMessage();
            return false;
        }
    }

    function checkProductName($prdName, $prdISBN, $prdId = null)
    {
        try {
            $sql = "SELECT prd_id, prd_name, prd_isbn
                    FROM bs_product
                    WHERE prd_name = :prd_name OR prd_isbn = :prd_isbn";

            if ($prdId != null) {
                $sql .= " AND prd_id != :prd_id";
            }

            $sql .= " LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_name', $prdName, PDO::PARAM_STR);
            $stmt->bindParam(':prd_isbn', $prdISBN, PDO::PARAM_STR);

            // หากมีการส่ง $id มาด้วย
            if ($prdId !== null) {
                $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in  checkProductName : " . $e->getMessage();
            return false;
        }
    }

    function insertProduct($prdName, $newImg, $prdISBN, $prdCoin, $prdQuantity, $prdNumberPages, $prdPrice, $prdPercentDiscount, $ptyId, $pubId, $authId, $prdPreorder, $prdStatus)
    {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO bs_product(prd_name, prd_img1, prd_isbn, prd_coin, prd_quantity, prd_number_pages, prd_price, prd_percent_discount, pty_id, pub_id, auth_id, prd_preorder, prd_status)
                    VALUES (:prd_name, :prd_img1, :prd_isbn, :prd_coin, :prd_quantity, :prd_number_pages, :prd_price, :prd_percent_discount, :pty_id, :pub_id, :auth_id, :prd_preorder, :prd_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_name', $prdName);
            $stmt->bindParam(':prd_img1', $newImg);
            $stmt->bindParam(':prd_isbn', $prdISBN);
            $stmt->bindParam(':prd_coin', $prdCoin);
            $stmt->bindParam(':prd_quantity', $prdQuantity);
            $stmt->bindParam(':prd_number_pages', $prdNumberPages);
            $stmt->bindParam(':prd_price', $prdPrice);
            $stmt->bindParam(':prd_percent_discount', $prdPercentDiscount);
            $stmt->bindParam(':pty_id', $ptyId);
            $stmt->bindParam(':pub_id', $pubId);
            $stmt->bindParam(':auth_id', $authId);
            $stmt->bindParam(':prd_preorder', $prdPreorder);
            $stmt->bindParam(':prd_status', $prdStatus);
            $stmt->execute();

            $lastInsertId = $this->db->lastInsertId();

            $sql = "INSERT INTO bs_product_views(prd_id, pty_id)
                    VALUES (:prd_id, :pty_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $lastInsertId);
            $stmt->bindParam(':pty_id', $ptyId);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in insertProduct: " . $e->getMessage();
            return false;
        }
    }


    function getDetailProduct($Id)
    {
        try {
            $sql = "SELECT * FROM bs_product
                    WHERE prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindPAram(':prd_id', $Id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailProduct : " . $e->getMessage();
            return false;
        }
    }
}
