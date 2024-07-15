<!-- ================================= Product =============================================== 
 
   1.  __construct
   2. getProductType
   3. checkProductTypeName
   4. insertProductTyp
   5. getDetailProductType
   6. updateDetailProductType
   7. updateNewCoverProductType
   8. DeleteProductType

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
                        bs_products.prd_id,
                        bs_products.prd_name,
                        bs_products.prd_img1,
                        bs_products.prd_price,
                        bs_products.prd_percent_discount,
                        bs_products.prd_preorder,
                        bs_products.pty_id,
                        bs_products.prd_status,
                        bs_products_type.pty_name
                    FROM bs_products
                    JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id;";
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

            $sql = "SELECT pty_id, pty_name FROM bs_products_type";
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

            $sql = "SELECT pub_id, pub_name FROM bs_publishers";
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
            $sql = "SELECT auth_id, auth_name FROM bs_authors";
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
                FROM bs_products
                WHERE (prd_name = :prd_name OR prd_isbn = :prd_isbn) ";

            if ($prdId != null) {
                $sql .= " AND prd_id != :prd_id ";
            }

            $sql .= " LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_name', $prdName, PDO::PARAM_STR);
            $stmt->bindParam(':prd_isbn', $prdISBN, PDO::PARAM_STR);

            if ($prdId !== null) {
                $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in checkProductName: " . $e->getMessage());
            return false;
        }
    }
    function insertProduct($prdName, $newImg, $prdISBN, $prdCoin, $prdQuantity, $prdNumberPages, $prdPrice, $prdPercentDiscount, $ptyId, $pubId, $authId, $prdPreorder, $prdStatus)
    {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO bs_products(prd_name, prd_img1, prd_isbn, prd_coin, prd_quantity, prd_number_pages, prd_price, prd_percent_discount, pty_id, pub_id, auth_id, prd_preorder, prd_status)
                    VALUES (:prd_name, :prd_img1, :prd_isbn, :prd_coin, :prd_quantity, :prd_number_pages, :prd_price, :prd_percent_discount, :pty_id, :pub_id, :auth_id, :prd_preorder, :prd_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_name', $prdName, PDO::PARAM_STR);
            $stmt->bindParam(':prd_img1', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':prd_isbn', $prdISBN, PDO::PARAM_STR);
            $stmt->bindParam(':prd_coin', $prdCoin, PDO::PARAM_INT);
            $stmt->bindParam(':prd_quantity', $prdQuantity, PDO::PARAM_INT);
            $stmt->bindParam(':prd_number_pages', $prdNumberPages, PDO::PARAM_INT);
            $stmt->bindParam(':prd_price', $prdPrice, PDO::PARAM_STR);
            $stmt->bindParam(':prd_percent_discount', $prdPercentDiscount, PDO::PARAM_INT);
            $stmt->bindParam(':pty_id', $ptyId, PDO::PARAM_INT);
            $stmt->bindParam(':pub_id', $pubId, PDO::PARAM_INT);
            $stmt->bindParam(':auth_id', $authId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_preorder', $prdPreorder, PDO::PARAM_INT);
            $stmt->bindParam(':prd_status', $prdStatus, PDO::PARAM_INT);
            $stmt->execute();

            $lastInsertId = $this->db->lastInsertId();

            $sql = "INSERT INTO bs_products_views(prd_id, pty_id)
                    VALUES (:prd_id, :pty_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $lastInsertId, PDO::PARAM_INT);
            $stmt->bindParam(':pty_id', $ptyId, PDO::PARAM_INT);
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
            $sql = "SELECT * FROM bs_products
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

    function updateDetailProduct($prdId, $prdName, $prdISBN, $prdCoin, $prdQuantity, $prdNumberPages, $prdPrice, $prdDetail, $prdPercentDiscount, $ptyId, $pubId, $authId, $prdPreorder, $prdStatus)
    {
        try {
            $sql = "UPDATE bs_products
                    SET prd_name = :prd_name,
                        prd_isbn = :prd_isbn,
                        prd_coin = :prd_coin,
                        prd_quantity = :prd_quantity,
                        prd_number_pages = :prd_number_pages,
                        prd_price = :prd_price,
                        prd_detail = :prd_detail,
                        prd_percent_discount = :prd_percent_discount,
                        pty_id = :pty_id,
                        pub_id = :pub_id,
                        auth_id = :auth_id,
                        prd_preorder = :prd_preorder,
                        prd_status = :prd_status
                    WHERE prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_name', $prdName, PDO::PARAM_STR);
            $stmt->bindParam(':prd_isbn', $prdISBN, PDO::PARAM_STR);
            $stmt->bindParam(':prd_coin', $prdCoin, PDO::PARAM_INT);
            $stmt->bindParam(':prd_quantity', $prdQuantity, PDO::PARAM_INT);
            $stmt->bindParam(':prd_number_pages', $prdNumberPages, PDO::PARAM_INT);
            $stmt->bindParam(':prd_price', $prdPrice, PDO::PARAM_STR);
            $stmt->bindParam(':prd_detail', $prdDetail, PDO::PARAM_STR);
            $stmt->bindParam(':prd_percent_discount', $prdPercentDiscount, PDO::PARAM_INT);
            $stmt->bindParam(':pty_id', $ptyId, PDO::PARAM_INT);
            $stmt->bindParam(':pub_id', $pubId, PDO::PARAM_INT);
            $stmt->bindParam(':auth_id', $authId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_preorder', $prdPreorder, PDO::PARAM_INT);
            $stmt->bindParam(':prd_status', $prdStatus, PDO::PARAM_INT);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateDetailProduct : " . $e->getMessage();
            return false;
        }
    }

    function updateProductView($prdId, $ptyId)
    {
        try {
            $sql = "UPDATE bs_products_views
                    SET pty_id = :pty_id
                    WHERE prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pty_id', $ptyId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateProductView : " . $e->getMessage();
            return false;
        }
    }

    function updateProductImg1($newImg, $prdId)
    {
        try {
            $sql = "UPDATE bs_products
                    SET prd_img1 = :prd_img1
                    WHERE prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_img1', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateProductImg1 : " . $e->getMessage();
            return false;
        }
    }
    function updateProductImg2($newImg, $prdId)
    {
        try {
            $sql = "UPDATE bs_products
                    SET prd_img2 = :prd_img2
                    WHERE prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_img2', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateProductImg2 : " . $e->getMessage();
            return false;
        }
    }

    function deleteProductImg2($prdId)
    {
        try {
            $sql = "UPDATE bs_products
                    SET prd_img2 = null
                    WHERE prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteProductImg2 : " . $e->getMessage();
            return false;
        }
    }

    function deleteProduct($prdId)
    {
        try {
            $this->db->beginTransaction();

            $sql = "DELETE FROM bs_products WHERE prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();


            $sql = "DELETE FROM bs_products_views WHERE prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();

            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in deleteProduct : " . $e->getMessage();
            return false;
        }
    }

    function getProductLow($prdNumberLow = null)
    {
        try {

            $sql = "SELECT
                        bs_products.prd_id,
                        bs_products.prd_name,
                        bs_products.prd_img1,
                        bs_products.prd_quantity,
                        bs_products.prd_preorder,
                        bs_products.pty_id,
                        bs_products.prd_status,
                        bs_products_type.pty_name
                    FROM bs_products
                    JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id";

            if ($prdNumberLow !== null) {
                $sql .= " WHERE bs_products.prd_quantity <= :prd_number_low";
            }

            $stmt = $this->db->prepare($sql);

            if ($prdNumberLow !== null) {
                $stmt->bindParam(':prd_number_low', $prdNumberLow, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getProductLow : " . $e->getMessage();
            return false;
        }
    }

    function getProductLowNumber($prdNumberLow)
    {
        try {
            $sql = "SELECT COUNT(prd_id) AS total_products_low
                    FROM bs_products
                    WHERE prd_quantity <= :prd_number_low";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_number_low', $prdNumberLow, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_products_low'];
        } catch (PDOException $e) {
            echo "<hr>Error in getProductLowNumber : " . $e->getMessage();
            return false;
        }
    }

    function getProductNew()
    {
        try {

            $sql = "SELECT
                        bs_products.prd_id,
                        bs_products.prd_name,
                        bs_products.prd_img1,
                        bs_products.prd_price,
                        bs_products.prd_percent_discount,
                        bs_products.prd_preorder,
                        COUNT(bs_products_reviews.prd_id) AS review_count,
                        SUM(bs_products_reviews.prs_rating) AS total_rating
                    FROM bs_products
                    INNER JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id
                    INNER JOIN bs_publishers ON bs_products.pub_id = bs_publishers.pub_id
                    INNER JOIN bs_authors ON bs_products.auth_id = bs_authors.auth_id
                    LEFT JOIN bs_products_reviews ON bs_products.prd_id = bs_products_reviews.prd_id
                    WHERE bs_products.prd_status = 1
                    AND bs_products_type.pty_status = 1
                    AND bs_publishers.pub_status = 1
                    AND bs_authors.auth_status = 1
                    GROUP BY bs_products.prd_id
                    ORDER BY bs_products.prd_time_create DESC
                    LIMIT 10";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getProduct : " . $e->getMessage();
            return false;
        }
    }

    function getRecommendedProducts()
    {
        try {

            $sql = "SELECT
                        bs_products.prd_id,
                        bs_products.prd_name,
                        bs_products.prd_img1,
                        bs_products.prd_price,
                        bs_products.prd_percent_discount,
                        bs_products.prd_preorder,
                        COUNT(bs_products_reviews.prd_id) AS review_count,
                        SUM(bs_products_reviews.prs_rating) AS total_rating
                    FROM bs_products
                    INNER JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id
                    INNER JOIN bs_publishers ON bs_products.pub_id = bs_publishers.pub_id
                    INNER JOIN bs_authors ON bs_products.auth_id = bs_authors.auth_id
                    LEFT JOIN bs_products_reviews ON bs_products.prd_id = bs_products_reviews.prd_id
                    WHERE bs_products.prd_status = 1
                    AND bs_products_type.pty_status = 1
                    AND bs_publishers.pub_status = 1
                    AND bs_authors.auth_status = 1
                    GROUP BY bs_products.prd_id
                    ORDER BY RAND()
                    LIMIT 10";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getRecommendedProducts : " . $e->getMessage();
            return false;
        }
    }

    function getPopularProducts()
    {
        try {

            $sql = "SELECT
                        bs_products.prd_id,
                        bs_products.prd_name,
                        bs_products.prd_img1,
                        bs_products.prd_price,
                        bs_products.prd_percent_discount,
                        bs_products.prd_preorder,
                        COUNT(bs_products_reviews.prd_id) AS review_count,
                        SUM(bs_products_reviews.prs_rating) AS total_rating
                    FROM bs_products
                    INNER JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id
                    INNER JOIN bs_publishers ON bs_products.pub_id = bs_publishers.pub_id
                    INNER JOIN bs_authors ON bs_products.auth_id = bs_authors.auth_id
                    LEFT JOIN bs_products_reviews ON bs_products.prd_id = bs_products_reviews.prd_id
                    WHERE bs_products.prd_status = 1
                    AND bs_products_type.pty_status = 1
                    AND bs_publishers.pub_status = 1
                    AND bs_authors.auth_status = 1
                    GROUP BY bs_products.prd_id
                    ORDER BY review_count DESC
                    LIMIT 10";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getPopularProducts : " . $e->getMessage();
            return false;
        }
    }

    function getMostViewedProducts()
    {
        try {

            $sql = "SELECT
                        bs_products.prd_id,
                        bs_products.prd_name,
                        bs_products.prd_img1,
                        bs_products.prd_price,
                        bs_products.prd_percent_discount,
                        bs_products.prd_preorder,
                        COUNT(bs_products_reviews.prd_id) AS review_count,
                        SUM(bs_products_reviews.prs_rating) AS total_rating,
                        SUM(bs_products_views.prv_view) AS total_views
                      	
                    FROM bs_products
                    INNER JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id
                    INNER JOIN bs_publishers ON bs_products.pub_id = bs_publishers.pub_id
                    INNER JOIN bs_authors ON bs_products.auth_id = bs_authors.auth_id
                    LEFT JOIN bs_products_reviews ON bs_products.prd_id = bs_products_reviews.prd_id
                    LEFT JOIN bs_products_views ON bs_products.prd_id = bs_products_views.prd_id
                    WHERE bs_products.prd_status = 1
                    AND bs_products_type.pty_status = 1
                    AND bs_publishers.pub_status = 1
                    AND bs_authors.auth_status = 1
                    GROUP BY bs_products.prd_id
                    ORDER BY total_views DESC
                    LIMIT 10";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getMostViewedProducts : " . $e->getMessage();
            return false;
        }
    }
}
