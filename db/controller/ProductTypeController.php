<!-- ================================= Product Type ========================================== 
 
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
class ProductTypeController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Product Type Controller สำเร็จ <br>";
    }


    function getProductType()
    {
        try {
            $sql = "SELECT
                        bs_product_type.pty_id,
                        bs_product_type.pty_cover,
                        bs_product_type.pty_name,
                        bs_product_type.pty_detail,
                        bs_product_type.pty_status,
                        bs_product_type.pty_time_update,
                        SUM( bs_product_views.prv_view) AS total_view
                    FROM bs_product_type
                    LEFT JOIN bs_product_views ON bs_product_type.pty_id =  bs_product_views.pty_id
                    GROUP BY bs_product_type.pty_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getProductType : " . $e->getMessage();
            return false;
        }
    }

    function checkProductTypeName($ptyName, $Id = null)
    {
        try {
            $sql = "SELECT pty_name
                    FROM bs_product_type
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

    function insertProductType($newCover, $ptyName, $ptyDetail, $ptyStatus)
    {
        try {
            $sql = "INSERT INTO bs_product_type(pty_cover, pty_name, pty_detail, pty_status)
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

    function getDetailProductType($Id)
    {
        try {
            $sql = "SELECT
                        bs_product_type.pty_id,
                        bs_product_type.pty_cover,
                        bs_product_type.pty_name,
                        bs_product_type.pty_detail,
                        bs_product_type.pty_status,
                        bs_product_type.pty_time_update,
                        SUM( bs_product_views.prv_view) AS total_view
            FROM bs_product_type
            LEFT JOIN bs_product_views ON bs_product_type.pty_id =  bs_product_views.pty_id
            WHERE bs_product_type.pty_id = :pty_id
            GROUP BY bs_product_type.pty_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pty_id', $Id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailProductType : " . $e->getMessage();
            return false;
        }
    }

    function updateDetailProductType($ptyName, $ptyDetail, $ptyStatus, $Id)
    {
        try {
            $sql = "UPDATE bs_product_type
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

    function updateNewCoverProductType($newCover, $Id)
    {
        try {
            $sql = "UPDATE bs_product_type
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

    function deleteProductType($Id){
        try{
            $sql = "DELETE FROM bs_product_type
                    WHERE pty_id = :pty_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pty_id', $Id);
            $stmt->execute();
            return true;

        }catch(PDOException $e){
            echo "<hr>Error in deleteProductType : " . $e->getMessage();
            return false;
        }
    }
}
?>