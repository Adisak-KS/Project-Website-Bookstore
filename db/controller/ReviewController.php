<?php
// ========================================== Review ========================================== 
/* 
    1.  __construct
    2. getMemberAddress
    3. insertMemberAddress
    4. getDetailMemberAddress
    5. updateMemberAddress
    6. updateMemberAddressStatus
    7. deleteAddress
*/
// ============================================================================================
class ReviewController extends BaseController
{
    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Review Controller สำเร็จ <br>";
    }


    function checkAccountOrderReview($prdId, $ordId, $memId)
    {
        try {
            $sql = "SELECT *
                    FROM bs_products_reviews
                    WHERE prd_id = :prd_id AND ord_id = :ord_id AND mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in checkAccountOrderReview : " . $e->getMessage();
            return false;
        }
    }

    function insertAccountReview($prdId, $ordId, $memId, $prvRating, $prvDetail)
    {
        try {
            $sql = "INSERT INTO bs_products_reviews(prd_id, ord_id, mem_id, prv_rating, prv_detail)
                    VALUES (:prd_id, :ord_id, :mem_id, :prv_rating, :prv_detail)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prv_rating', $prvRating, PDO::PARAM_INT);
            $stmt->bindParam(':prv_detail', $prvDetail, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertAccountReview : " . $e->getMessage();
            return false;
        }
    }

    function updateAccountReview($prdId, $ordId, $memId, $prvRating, $prvDetail)
    {
        try {
            $sql = "UPDATE bs_products_reviews
                    SET prv_rating = :prv_rating,
                        prv_detail = :prv_detail,
                        prv_status = 0
                    WHERE prd_id = :prd_id AND ord_id = :ord_id AND mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prv_rating', $prvRating, PDO::PARAM_INT);
            $stmt->bindParam(':prv_detail', $prvDetail, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateAccountReview : " . $e->getMessage();
            return false;
        }
    }

    function getReviewInProductDetail($prdId)
    {
        try {
            $sql = "SELECT 	bs_products_reviews.prv_rating,
                            bs_products_reviews.prv_detail,
                            bs_products_reviews.prv_time_create,
                            bs_members.mem_username
                    FROM bs_products_reviews
                    INNER JOIN bs_members ON bs_products_reviews.mem_id = bs_members.mem_id
                    WHERE bs_products_reviews.prv_status = 1 AND prd_id = :prd_id
                    ORDER BY bs_products_reviews.prv_time_create DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getReviewInProductDetail : " . $e->getMessage();
            return false;
        }
    }
    function checkReviewInProductDetail($prdId)
    {
        try {
            $sql = "SELECT COUNT(prv_id) AS total_review
                    FROM bs_products_reviews
                    WHERE prd_id = :prd_id AND prv_status = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // ตรวจสอบว่า total_review มีค่ามากกว่า 0 หรือไม่
            return $result['total_review'] > 0;
        } catch (PDOException $e) {
            echo "<hr>Error in checkReviewInProductDetail : " . $e->getMessage();
            return false;
        }
    }


    function getAmountReviewStatusNotShowing()
    {
        try {
            $sql = "SELECT COUNT(prv_id) AS total_review
                    FROM bs_products_reviews
                    WHERE prv_status =  0";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_review'];
        } catch (PDOException $e) {
            echo "<hr>Error in getAmountProductRequest : " . $e->getMessage();
            return false;
        }
    }

    function getReviewStatusNotShowing()
    {
        try {
            $sql = "SELECT  bs_products_reviews.prv_id,  
                            bs_products_reviews.ord_id,
                            bs_products_reviews.prv_status, 
                            bs_products_reviews.prv_time_create,
                            bs_members.mem_username,
                            bs_products.prd_name
                    FROM bs_products_reviews
                    INNER JOIN bs_members ON  bs_products_reviews.mem_id = bs_members.mem_id
                    INNER JOIN bs_products ON  bs_products_reviews.prd_id = bs_products.prd_id
                    WHERE prv_status =  0";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getReviewStatusNotShowing : " . $e->getMessage();
            return false;
        }
    }

    function getReviewStatusNotShowingDetail($prvId)
    {
        try {
            $sql = "SELECT  bs_products_reviews.prv_id,  
                            bs_products_reviews.ord_id,
                            bs_products_reviews.prv_rating,
                            bs_products_reviews.prv_detail,
                            bs_products_reviews.prv_status, 
                            bs_products_reviews.prv_time_create,
                            bs_products_reviews.prv_time_update,
                            bs_members.mem_username,
                            bs_products.prd_name
                    FROM bs_products_reviews
                    INNER JOIN bs_members ON  bs_products_reviews.mem_id = bs_members.mem_id
                    INNER JOIN bs_products ON  bs_products_reviews.prd_id = bs_products.prd_id
                    WHERE prv_status =  0 AND prv_id = :prv_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prv_id', $prvId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getReviewStatusNotShowingDetail : " . $e->getMessage();
            return false;
        }
    }

    function deleteReview($prvId)
    {
        try {
            $sql = "DELETE FROM bs_products_reviews
                    WHERE prv_id = :prv_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prv_id', $prvId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteReview : " . $e->getMessage();
            return false;
        }
    }

    function updateReviewStatusShow($prvId){
        try {
            $sql = "UPDATE bs_products_reviews
                    SET  prv_status = 1
                    WHERE prv_id = :prv_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prv_id', $prvId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateReviewStatusShow : " . $e->getMessage();
            return false;
        }
    }
}
