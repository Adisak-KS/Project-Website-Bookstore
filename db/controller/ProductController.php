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

            $sql = "SELECT prd_id, prd_name, prd_price, prd_percent_discount, prd_preorder, prd_status
                    FROM bs_product";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getProduct : " . $e->getMessage();
            return false;
        }
    }
}
