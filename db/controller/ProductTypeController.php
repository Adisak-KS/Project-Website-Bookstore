<?php
//  ================================= Product Type ========================================== 
/*
    1.  __construct
    2. getProductType
    3. checkProductTypeName
    4. insertProductType
    5. getDetailProductType
    6. updateDetailProductType
    7. updateNewCoverProductType
    8. DeleteProductType
    9. amountProductInProductType
    10. getProductsType10
*/
// ============================================================================================

class ProductTypeController extends BaseController
{

    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Product Type Controller สำเร็จ <br>";
    }

    // ============================= 2. getProductType ===================================
    function getProductType()
    {
        try {
            $sql = "SELECT
                        bs_products_type.pty_id,
                        bs_products_type.pty_cover,
                        bs_products_type.pty_name,
                        bs_products_type.pty_detail,
                        bs_products_type.pty_status,
                        bs_products_type.pty_time_update,
                        SUM( bs_products_views.prv_view) AS total_view
                    FROM bs_products_type
                    LEFT JOIN bs_products_views ON bs_products_type.pty_id =  bs_products_views.pty_id
                    GROUP BY bs_products_type.pty_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getProductType : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 3. checkProductTypeName ===================================
    function checkProductTypeName($ptyName, $Id = null)
    {
        try {
            $sql = "SELECT pty_name
                    FROM bs_products_type
                    WHERE pty_name = :pty_name";

            if ($Id !== null) {
                $sql .= " AND pty_id != :pty_id";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pty_name', $ptyName);

            if ($Id !== null) {
                $stmt->bindParam(':pty_id', $Id);
            }

            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in checkProductTypeName : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 4. insertProductType ===================================
    function insertProductType($newCover, $ptyName, $ptyDetail, $ptyStatus)
    {
        try {
            $sql = "INSERT INTO bs_products_type(pty_cover, pty_name, pty_detail, pty_status)
                    VALUES (:pty_cover, :pty_name, :pty_detail, :pty_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pty_cover', $newCover);
            $stmt->bindParam(':pty_name', $ptyName);
            $stmt->bindParam(':pty_detail', $ptyDetail);
            $stmt->bindParam(':pty_status', $ptyStatus);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertProductType : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 5. getDetailProductType ===================================
    function getDetailProductType($Id)
    {
        try {
            $sql = "SELECT
                        bs_products_type.pty_id,
                        bs_products_type.pty_cover,
                        bs_products_type.pty_name,
                        bs_products_type.pty_detail,
                        bs_products_type.pty_status,
                        bs_products_type.pty_time_update,
                        SUM( bs_products_views.prv_view) AS total_view
            FROM bs_products_type
            LEFT JOIN bs_products_views ON bs_products_type.pty_id =  bs_products_views.pty_id
            WHERE bs_products_type.pty_id = :pty_id
            GROUP BY bs_products_type.pty_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pty_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailProductType : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 6. updateDetailProductTyp ===================================
    function updateDetailProductType($ptyName, $ptyDetail, $ptyStatus, $Id)
    {
        try {
            $sql = "UPDATE bs_products_type
                    SET pty_name = :pty_name,
                        pty_detail = :pty_detail,
                        pty_status = :pty_status, 
                        pty_time_update = NOW()
                    WHERE pty_id = :pty_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pty_name', $ptyName);
            $stmt->bindParam(':pty_detail', $ptyDetail);
            $stmt->bindParam(':pty_status', $ptyStatus);
            $stmt->bindParam(':pty_id', $Id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateDetailProductType : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 7. updateNewCoverProductType ===================================
    function updateNewCoverProductType($newCover, $Id)
    {
        try {
            $sql = "UPDATE bs_products_type
                    SET pty_cover = :pty_cover,
                        pty_time_update = NOW()
                    WHERE pty_id = :pty_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pty_cover', $newCover);
            $stmt->bindParam(':pty_id', $Id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateNewCoverProductType : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 8. deleteProductType ===================================
    function deleteProductType($Id)
    {
        try {
            $sql = "DELETE FROM bs_products_type
                    WHERE pty_id = :pty_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pty_id', $Id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteProductType : " . $e->getMessage();
            return false;
        }
    }
    // ============================= 9. amountProductInProductType ===================================
    function amountProductInProductType($Id){
        try {
            $sql = "SELECT COUNT(prd_id) AS amount 
                    FROM bs_products
                    WHERE pty_id = :pty_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pty_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in amountProduct : " . $e->getMessage();
        }
    }

    // ============================= 10. getProductsType10 ===================================
    function getProductsType10($prdPreorder = null)
    {
        // ตรวจสอบค่าของ $prdPreorder และกำหนดเป็น 1 ถ้าค่าไม่ใช่ 0, 1, หรือ null
        if ($prdPreorder !== 0 && $prdPreorder !== 1 && $prdPreorder !== null) {
            $prdPreorder = 1; // ค่าเริ่มต้น
        }

        try {
            $sql = "SELECT
                    bs_products_type.pty_id,
                    bs_products_type.pty_name,
                    COUNT(bs_products.prd_id) AS product_count
                FROM bs_products_type
                INNER JOIN bs_products ON bs_products.pty_id = bs_products_type.pty_id
                INNER JOIN bs_publishers ON bs_products.pub_id = bs_publishers.pub_id
                INNER JOIN bs_authors ON bs_products.auth_id = bs_authors.auth_id
                WHERE bs_products.prd_status = 1
                    AND bs_products_type.pty_status = 1
                    AND bs_publishers.pub_status = 1
                    AND bs_authors.auth_status = 1";

            // เพิ่มเงื่อนไขสำหรับ prd_preorder ถ้าค่าถูกต้อง
            if ($prdPreorder !== null) {
                $sql .= " AND bs_products.prd_preorder = :prd_preorder";
            }

            $sql .= " GROUP BY bs_products_type.pty_id
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
            echo "<hr>Error in getProductsType10 : " . $e->getMessage();
            return false;
        }
    }
}
