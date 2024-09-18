
<?php
// ========================================== Cart ========================================== 
/* 
    1. __construct
    2. getCartItemCount
    3. getCartItem
    4. checkCartItem
    5. getCartItemQty
    6. insertCartItem
    7. plusCartItem
    8. removeCartItem
    9. updateCartItem
    10. getShipping
    11. getPayment
    12. getPromotion
    13. getUsePromotion
    14. getUseShipping
    15. getUsePayment
    16. getMemberAddressDefault
*/
// ============================================================================================

class CartController extends BaseController
{
    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Cart Controller สำเร็จ <br>";
    }

    // ============================= 2. getCartItemCount ===================================
    function getCartItemCount($memId)
    {
        try {
            $sql = "SELECT COUNT(crt_id) AS total_cart
                    FROM bs_cart
                    INNER JOIN bs_products ON bs_cart.prd_id = bs_products.prd_id
                    INNER JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id 
                    INNER JOIN bs_publishers ON bs_products.pub_id = bs_publishers.pub_id
                    INNER JOIN bs_authors ON bs_products.auth_id = bs_authors.auth_id
                    WHERE bs_products.prd_status = 1
                        AND bs_products_type.pty_status = 1
                        AND	bs_publishers.pub_status = 1
                        AND bs_authors.auth_status = 1
                        AND mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_cart'];
        } catch (PDOException $e) {
            echo "<hr>Error in getCartItemCount : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 3. getCartItem ===================================
    function getCartItem($memId)
    {
        try {
            $sql = "SELECT 
                        bs_cart.crt_id,
                        bs_cart.mem_id,
                        bs_cart.prd_id,
                        bs_cart.crt_qty,
                        bs_members.mem_coin,
                        bs_products.prd_img1,
                        bs_products.prd_name,
                        bs_products.prd_price,
                        bs_products.prd_percent_discount,
                        (bs_products.prd_price * (100 - bs_products.prd_percent_discount) / 100) AS price_sale,
                        bs_products.prd_quantity,
                        bs_products.prd_coin,
                        (bs_cart.crt_qty * bs_products.prd_coin) AS coins_per_item,
                        ((bs_products.prd_price * (100 - bs_products.prd_percent_discount) / 100) * bs_cart.crt_qty) AS total_price_sale,
                        bs_products.prd_preorder
                    FROM bs_cart
                    INNER JOIN bs_members ON bs_cart.mem_id = bs_members.mem_id
                    INNER JOIN bs_products ON bs_cart.prd_id = bs_products.prd_id
                    INNER JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id 
                    INNER JOIN bs_publishers ON bs_products.pub_id = bs_publishers.pub_id
                    INNER JOIN bs_authors ON bs_products.auth_id = bs_authors.auth_id
                    WHERE bs_products.prd_status = 1
                        AND bs_products.prd_quantity > 0
                        AND bs_products_type.pty_status = 1
                        AND bs_publishers.pub_status = 1
                        AND bs_authors.auth_status = 1
                        AND bs_cart.mem_id = :mem_id
                    ORDER BY bs_cart.crt_id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getCartItem : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 4. checkCartItem ===================================
    function checkCartItem($memId, $prdId)
    {
        try {
            $sql = "SELECT 1
                    FROM bs_cart
                    WHERE mem_id = :mem_id AND prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false;
        } catch (PDOException $e) {
            echo "<hr>Error in checkCartItem : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 5. getCartItemQty ===================================
    function getCartItemQty($memId, $prdId)
    {
        try {
            $sql = "SELECT crt_qty
                    FROM bs_cart
                    WHERE mem_id = :mem_id AND prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['crt_qty'] : 0;
        } catch (PDOException $e) {
            echo "<hr>Error in getCartItemQty : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 6. insertCartItem ===================================
    function insertCartItem($memId, $prdId, $crtQty)
    {
        try {
            $sql = "INSERT INTO bs_cart(mem_id, prd_id, crt_qty)
                    VALUES (:mem_id, :prd_id, :crt_qty)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->bindParam(':crt_qty', $crtQty, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertCartItem : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 7. plusCartItem ===================================
    function plusCartItem($memId, $prdId, $crtQty)
    {
        try {
            $sql = "UPDATE bs_cart
                    SET crt_qty = crt_qty + :crt_qty
                    WHERE mem_id = :mem_id AND prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':crt_qty', $crtQty, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in plusCartItem : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 8. removeCartItem ===================================
    function removeCartItem($crtId)
    {
        try {
            $sql = "DELETE FROM bs_cart
                    WHERE crt_id = :crt_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':crt_id', $crtId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in removeCartItem : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 9. updateCartItem ===================================
    function updateCartItem($memId, $prdId, $crtQty)
    {
        try {
            $sql = "UPDATE bs_cart
                    SET crt_qty = :crt_qty
                    WHERE mem_id = :mem_id AND prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':crt_qty', $crtQty, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateCartItem : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 10. getShipping ===================================
    function getShipping()
    {
        try {
            $sql = "SELECT shp_id, shp_name, shp_price
                    FROM bs_shipping
                    WHERE shp_status = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getShipping : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 11. getPayment ===================================
    function getPayment()
    {
        try {
            $sql = "SELECT pmt_id, pmt_bank
                    FROM bs_payments
                    WHERE pmt_status = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getPayment : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. getPromotion ===================================
    function getPromotion()
    {
        try {
            $sql = "SELECT pro_id, pro_name, pro_percent_discount 
                    FROM bs_promotions 
                    WHERE pro_status = 1 
                        AND NOW() BETWEEN pro_time_start AND pro_time_end";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getPayment : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 13. getUsePromotion ===================================
    function getUsePromotion($proId)
    {
        try {
            $sql = "SELECT pro_id, pro_name, pro_percent_discount 
                    FROM bs_promotions 
                    WHERE pro_status = 1 
                        AND pro_id = :pro_id
                        AND NOW() BETWEEN pro_time_start AND pro_time_end";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pro_id', $proId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getUsePromotion : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 14. getUseShipping ===================================
    function getUseShipping($shpId)
    {
        try {
            $sql = "SELECT shp_id, shp_name, shp_price
                    FROM bs_shipping
                    WHERE shp_status = 1 AND shp_id = :shp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':shp_id', $shpId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getUseShipping : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 15. getUsePayment ===================================
    function getUsePayment($pmtId)
    {
        try {
            $sql = "SELECT pmt_id, pmt_bank, pmt_name, pmt_number
                    FROM bs_payments
                    WHERE pmt_status = 1 AND pmt_id = :pmt_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':pmt_id', $pmtId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getUsePayment : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 16. getMemberAddressDefault ===================================
    function getMemberAddressDefault($memId)
    {
        try {
            $sql = "SELECT * 
                    FROM bs_members_address
                    WHERE addr_status = 1 AND mem_id = :mem_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getMemberAddressDefault : " . $e->getMessage();
            return false;
        }
    }
}
