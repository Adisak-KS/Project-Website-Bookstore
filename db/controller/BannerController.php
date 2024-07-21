
<?php
// ================================= Payment =============================================== 
/* 
    1.  __construct
    2. getBanner
    3. checkBannerName
    4. insertBanner
    5. getDetailBanner
    6. updateDetailBanner
    7. updateImgBanner
    8. deleteBanner
    9. updateSortTableList
    10. getSlideBanner
*/
// ============================================================================================

class BannerController extends BaseController
{

    // =============================== 1. __construct ======================================
    public function __construct($db)
    {
        parent::__construct($db);
        // echo "<br> เรียกใช้ Banner Controller สำเร็จ <br>";
    }

    // =============================== 2. getBanner ======================================
    function getBanner()
    {
        try {
            $sql = "SELECT * FROM bs_banners
                    WHERE bn_number_list IS NOT NULL
                    ORDER BY bn_number_list ASC;
                    ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>getBanner : " . $e->getMessage();
            return false;
        }
    }

    // =============================== 3. checkBannerName ======================================
    function checkBannerName($bnName, $bnId = NULL)
    {
        try {
            $sql = "SELECT bn_name
                    FROM bs_banners
                    WHERE bn_name = :bn_name";

            if ($bnId !== null) {
                $sql .= " AND bn_id != :bn_id";
            }

            $sql .= " LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_name', $bnName, PDO::PARAM_STR);


            if ($bnId !== NULL) {
                $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in checkBannerName : " . $e->getMessage();
            return false;
        }
    }

    // =============================== 4. insertBanner ======================================
    function insertBanner($bnName, $bnLink, $newImg, $bnStatus)
    {
        try {
            $sql = "INSERT INTO bs_banners(bn_name, bn_link, bn_img, bn_status)
                    VALUES(:bn_name, :bn_link, :bn_img, :bn_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_name', $bnName, PDO::PARAM_STR);
            $stmt->bindParam(':bn_link', $bnLink, PDO::PARAM_STR);
            $stmt->bindParam(':bn_img', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':bn_status', $bnStatus, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertBanner : " . $e->getMessage();
            return false;
        }
    }

    // =============================== 5. getDetailBanner ======================================
    function getDetailBanner($bnId)
    {
        try {
            $sql = "SELECT * FROM bs_banners
                    WHERE bn_id = :bn_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailBanner : " . $e->getMessage();
            return false;
        }
    }

    // =============================== 6. updateDetailBanner ======================================
    function updateDetailBanner($bnName, $bnLink, $bnStatus, $bnId)
    {
        try {
            $sql = "UPDATE bs_banners
                    SET bn_name = :bn_name,
                        bn_link = :bn_link,
                        bn_status = :bn_status
                    WHERE bn_id = :bn_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_name', $bnName, PDO::PARAM_STR);
            $stmt->bindParam(':bn_link', $bnLink, PDO::PARAM_STR);
            $stmt->bindParam(':bn_status', $bnStatus, PDO::PARAM_INT);
            $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateDetailBanner : " . $e->getMessage();
            return false;
        }
    }

    // =============================== 7. updateImgBanner ======================================
    function updateImgBanner($newImg, $bnId)
    {
        try {
            $sql = "UPDATE bs_banners
                    SET bn_img = :bn_img
                    WHERE bn_id = :bn_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_img', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateImgBanner : " . $e->getMessage();
            return false;
        }
    }

    // =============================== 8. deleteBanner ======================================
    function deleteBanner($bnId)
    {
        try {
            $sql = "DELETE FROM bs_banners
                    WHERE bn_id = :bn_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteBanner : " . $e->getMessage();
            return false;
        }
    }

    // =============================== 9. updateSortTableList ======================================
    function updateSortTableList($bnNumberList, $bnId)
    {
        try {
            $sql = "UPDATE bs_banners
                    SET bn_number_list = :bn_number_list
                    WHERE bn_id = :bn_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':bn_number_list', $bnNumberList, PDO::PARAM_INT);
            $stmt->bindParam(':bn_id', $bnId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in SortTableList : " . $e->getMessage();
            return false;
        }
    }

    // =============================== 10. getSlideBanner ======================================
    function getSlideBanner()
    {
        try {
            $sql = "SELECT * FROM bs_banners
                    WHERE bn_status = 1
                    ORDER BY bn_number_list ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in showSlideBanner : " . $e->getMessage();
            return false;
        }
    }
}
