<?php
// ========================================== Wishlist ========================================== 
/* 
    1.  __construct
    2. getMemberWishlist
    3. getDetailWishlist
    4. insertWishlist (Product Detail)
    5. deleteWishlist (Product Detail)
    6. deleteProductWishlist (My Account Wishlist)

*/
// ============================================================================================
class WishlistController extends BaseController
{

    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Wishlist Controller สำเร็จ <br>";
    }

    // ============================= 2. getMemberWishlist ===================================
    function getMemberWishlist($memId)
    {
        try {
            $sql = "SELECT 
                        bs_members_wishlist.mwl_id,
                        bs_members_wishlist.prd_id,
                        bs_products.prd_id, 
                        bs_products.prd_name, 
                        bs_products.prd_img1, 
                        bs_products.prd_price, 
                        bs_products.prd_percent_discount 
                    FROM bs_members_wishlist
                    INNER JOIN bs_products ON bs_members_wishlist.prd_id = bs_products.prd_id
                    INNER JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id
                    INNER JOIN bs_publishers ON bs_products.pub_id = bs_publishers.pub_id
                    INNER JOIN bs_authors ON bs_products.auth_id = bs_authors.auth_id
                    WHERE bs_products.prd_status = 1 
                        AND bs_products_type.pty_status = 1 
                        AND bs_publishers.pub_status = 1
                        AND bs_authors.auth_status = 1
                        AND bs_members_wishlist.mem_id = :mem_id
                    ORDER BY bs_members_wishlist.mwl_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error getMemberWishlist : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 3. getDetailWishlist ===================================
    function getDetailWishlist($prdId, $memId)
    {
        try {
            $sql = "SELECT mwl_id, COUNT(mwl_id) AS wishlist
                    FROM bs_members_wishlist
                    WHERE prd_id = :prd_id AND mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['wishlist'] > 0; // คืนค่า true ถ้ามีรายการใน Wishlist, false ถ้าไม่มี
        } catch (PDOException $e) {
            echo "<hr>Error getDetailWishlist : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 4. insertWishlist (Product Detail) ===================================
    function insertWishlist($memId, $prdId)
    {
        try {
            $sql = "INSERT INTO bs_members_wishlist(mem_id, prd_id)
                    VALUES (:mem_id, :prd_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error insertWishlist : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 5. deleteWishlist (Product Detail)===================================
    function deleteWishlist($memId, $prdId)
    {
        try {
            $sql = "DELETE FROM bs_members_wishlist 
                    WHERE mem_id = :mem_id AND prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteProductWishlist : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 6. deleteProductWishlist (My Account Wishlist) ===================================
    function deleteProductWishlist($mwlId)
    {
        try {
            $sql = "DELETE FROM bs_members_wishlist 
                    WHERE mwl_id = :mwl_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mwl_id', $mwlId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteProductWishlist : " . $e->getMessage();
            return false;
        }
    }
}
