<?php
// ========================================== Product Request (Employee) ========================================== 
/* 
    1.  __construct
    2. getProduct
    3. getProductType
    4. getPublisher
    5. getAuthor
    29. getSearchResult
*/
// ============================================================================================
class ProductRequestController extends BaseController
{
    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        // echo "<br> เรียกใช้ Product Request Controller สำเร็จ <br>";
    }

    // ============================= 12. getAccountProductRequest (Member)===================================
    function getAccountProductRequest($memId)
    {
        try {
            $sql = "SELECT prq_id, prq_title, prq_status, prq_time_create
                        FROM bs_products_request
                        WHERE mem_id = :mem_id
                        ORDER BY prq_id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getAccountProductRequest : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. getAccountProductRequest (Member)===================================
    function checkProductRequestTitle($prqTitle, $memId)
    {
        try {
            $sql = "SELECT COUNT(prq_id) AS total_request
                        FROM bs_products_request
                        WHERE prq_title = :prq_title AND mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_title', $prqTitle, PDO::PARAM_STR);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_request']; // คืนค่าจำนวนที่นับได้

        } catch (PDOException $e) {
            echo "<hr>Error in checkProductRequestTitle : " . $e->getMessage();
            return false;
        }
    }


    // ============================= 12. getAccountProductRequest (Member)===================================
    function insertProductRequest($memId, $prqTitle, $prqPrdName, $prqPublisher, $prqAuthor, $prqPrdVolumeNumber, $prqDetail, $prqImg)
    {
        try {
            $sql = "INSERT INTO bs_products_request (mem_id, prq_title, prq_prd_name, prq_publisher, prq_author, prq_prd_volume_number, prq_detail, prq_img)
                    VALUES (:mem_id, :prq_title, :prq_prd_name, :prq_publisher, :prq_author, :prq_prd_volume_number, :prq_detail, :prq_img)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prq_title', $prqTitle, PDO::PARAM_STR);
            $stmt->bindParam(':prq_prd_name', $prqPrdName, PDO::PARAM_STR);
            $stmt->bindParam(':prq_publisher', $prqPublisher, PDO::PARAM_STR);
            $stmt->bindParam(':prq_author', $prqAuthor, PDO::PARAM_STR);
            $stmt->bindParam(':prq_prd_volume_number', $prqPrdVolumeNumber, PDO::PARAM_INT);
            $stmt->bindParam(':prq_detail', $prqDetail, PDO::PARAM_STR);
            $stmt->bindParam(':prq_img', $prqImg, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertProductRequest : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. getAccountProductRequest (Member - Employee)===================================
    function getProductRequestDetail($prqId)
    {
        try {
            $sql = "SELECT 	bs_products_request.prq_id, 
                            bs_products_request.mem_id,
                            bs_products_request.prq_title,
                            bs_products_request.prq_prd_name,
                            bs_products_request.prq_publisher,
                            bs_products_request.prq_author,
                            bs_products_request.prq_prd_volume_number,
                            bs_products_request.prq_detail,
                            bs_products_request.prq_img,
                            bs_products_request.prq_status, 
                            bs_products_request.prq_time_create,
                            bs_members.mem_fname,
                            bs_members.mem_lname,
                            bs_members.mem_username
                    FROM bs_products_request
                    INNER JOIN bs_members ON bs_products_request.mem_id = bs_members.mem_id
                    WHERE bs_products_request.prq_id = :prq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_id', $prqId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getProductRequestDetail : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. getAccountProductRequest (Member)===================================
    function checkProductRequestEditTitle($prqId, $prqTitle, $memId)
    {
        try {
            $sql = "SELECT prq_id
                    FROM bs_products_request
                    WHERE prq_title = :prq_title AND mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_title', $prqTitle, PDO::PARAM_STR);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // ตรวจสอบว่า prq_id ตรงกับ $prqId หรือไม่
            if ($result) {
                if ($result['prq_id'] == $prqId) {
                    // ถ้า prq_id ตรงกับ $prqId, ให้แก้ไขได้
                    return false;
                } else {
                    // ถ้า prq_id ไม่ตรงกัน, ไม่อนุญาตให้แก้ไข
                    return true;
                }
            } else {
                // ถ้าไม่พบข้อมูลในฐานข้อมูล, ให้แก้ไขได้
                return false;
            }
        } catch (PDOException $e) {
            echo "<hr>Error in checkProductRequestEditTitle : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. getAccountProductRequest (Member)===================================
    function updateProductRequest($prqTitle, $prqPrdName, $prqPublisher, $prqAuthor, $prqPrdVolumeNumber, $prqDetail, $prqId)
    {
        try {
            $sql = "UPDATE bs_products_request
                    SET prq_title = :prq_title,
                        prq_prd_name = :prq_prd_name,
                        prq_publisher = :prq_publisher,
                        prq_author = :prq_author,
                        prq_prd_volume_number = :prq_prd_volume_number,
                        prq_detail = :prq_detail
                    WHERE prq_id = :prq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_title', $prqTitle, PDO::PARAM_STR);
            $stmt->bindParam(':prq_prd_name', $prqPrdName, PDO::PARAM_STR);
            $stmt->bindParam(':prq_publisher', $prqPublisher, PDO::PARAM_STR);
            $stmt->bindParam(':prq_author', $prqAuthor, PDO::PARAM_STR);
            $stmt->bindParam(':prq_prd_volume_number', $prqPrdVolumeNumber, PDO::PARAM_INT);
            $stmt->bindParam(':prq_detail', $prqDetail, PDO::PARAM_STR);
            $stmt->bindParam(':prq_id', $prqId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in  updateProductRequest : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. getAccountProductRequest (Member)===================================
    function updateNewImgProductRequest($prqId, $prqNewImg)
    {
        try {
            $sql = "UPDATE bs_products_request
                    SET prq_img = :prq_img
                    WHERE prq_id = :prq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_img', $prqNewImg, PDO::PARAM_STR);
            $stmt->bindParam(':prq_id', $prqId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in  updateNewImgProductRequest : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. getAccountProductRequest (Member)===================================
    function deleteProductRequest($prqId)
    {
        try {
            $sql = "DELETE FROM bs_products_request
                    WHERE prq_id = :prq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_id', $prqId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in  deleteProductRequest : " . $e->getMessage();
            return false;
        }
    }
    // ============================= 12. getAccountProductRequest (Member)===================================
    function cancelProductRequest($prqId)
    {
        try {
            $sql = "UPDATE bs_products_request
                    SET prq_status = 'cancel'
                    WHERE prq_id = :prq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_id', $prqId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in cancelProductRequest : " . $e->getMessage();
            return false;
        }
    }
    // ============================= 12. getAccountProductRequest (Member)===================================
    function updateProductRequestStatusTrue($prqId, $prpId)
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE bs_products_request
                    SET prq_status = 'success'
                    WHERE prq_id = :prq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_id', $prqId, PDO::PARAM_INT);
            $stmt->execute();

            $sql = "UPDATE bs_products_response
                    SET prp_status = 'true'
                    WHERE prp_id = :prp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prp_id', $prpId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in updateProductRequestStatusFalse : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. getAccountProductRequest (Member)===================================
    function updateProductRequestStatusFalse($prqId, $prpId)
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE bs_products_request
                    SET prq_status = 'checking'
                    WHERE prq_id = :prq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_id', $prqId, PDO::PARAM_INT);
            $stmt->execute();

            $sql = "UPDATE bs_products_response
                    SET prp_status = 'false'
                    WHERE prp_id = :prp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prp_id', $prpId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in updateProductRequestStatusFalse : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. getAccountProductRequest (Employee)===================================
    function getAmountProductRequest()
    {
        try {
            $sql = "SELECT COUNT(prq_id) AS total_product_request
                    FROM bs_products_request
                    WHERE prq_status = 'checking'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_product_request'];
        } catch (PDOException $e) {
            echo "<hr>Error in getAmountProductRequest : " . $e->getMessage();
            return false;
        }
    }

    function getProductRequestStatusChecking()
    {
        try {
            $sql = "SELECT 	bs_products_request.prq_id, 
                            bs_products_request.prq_title,
                            bs_products_request.prq_status, 
                            bs_products_request.prq_time_create,
                            bs_members.mem_fname,
                            bs_members.mem_lname,
                            bs_members.mem_username
                    FROM bs_products_request
                    INNER JOIN bs_members ON bs_products_request.mem_id = bs_members.mem_id
                    WHERE bs_products_request.prq_status = 'checking'
                    ORDER BY bs_products_request.prq_id DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getProductRequestStatusChecking : " . $e->getMessage();
            return false;
        }
    }


    function getProductResponse($prqId)
    {
        try {
            $sql = "SELECT * 
                    FROM bs_products_response
                    WHERE prq_id = :prq_id
                    ORDER BY prp_id ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_id', $prqId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getProductResponse : " . $e->getMessage();
            return false;
        }
    }

    function insertProductResponse($prqId, $empId, $prpPrdName, $prpPublisher, $prpAuthor, $prpPrdVolumeNumber, $prpDetail, $newImg)
    {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO bs_products_response (prq_id, emp_id, prp_prd_name, prp_publisher, prp_author, prp_prd_volume_number, prp_detail, prp_img) 
                    VALUES (:prq_id, :emp_id, :prp_prd_name, :prp_publisher, :prp_author, :prp_prd_volume_number, :prp_detail, :prp_img)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_id', $prqId, PDO::PARAM_INT);
            $stmt->bindParam(':emp_id', $empId, PDO::PARAM_INT);
            $stmt->bindParam(':prp_prd_name', $prpPrdName, PDO::PARAM_STR);
            $stmt->bindParam(':prp_publisher', $prpPublisher, PDO::PARAM_STR);
            $stmt->bindParam(':prp_author', $prpAuthor, PDO::PARAM_STR);
            $stmt->bindParam(':prp_prd_volume_number', $prpPrdVolumeNumber, PDO::PARAM_INT);
            $stmt->bindParam(':prp_detail', $prpDetail, PDO::PARAM_STR);
            $stmt->bindParam(':prp_img', $newImg, PDO::PARAM_STR);
            $stmt->execute();

            $sql = "UPDATE bs_products_request
                    SET prq_status = 'result'
                    WHERE prq_id = :prq_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prq_id', $prqId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollback();
            echo "<hr>Error in insertProductResponse : " . $e->getMessage();
            return false;
        }
    }
}
