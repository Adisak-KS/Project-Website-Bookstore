<?php
//  ================================= Shipping =============================================== 
/*
    1.  __construct
    2. getShipping
    3. checkShippingName
    4. insertShipping
    5. getDetailShipping
    6. updateDetailShipping
    7. updateShippingLogo
    8. deleteShipping
*/
// ============================================================================================

class ShippingController extends BaseController
{

    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Shipping Controller สำเร็จ <br>";
    }

    // ============================= 2. getShipping ===================================
    function getShipping()
    {
        try {
            $sql = "SELECT shp_id, shp_logo, shp_name, shp_price, shp_status 
                    FROM bs_shipping";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getShipping : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 3. checkShippingName ===================================
    function checkShippingName($shpName, $shpId = null)
    {
        try {
            $sql = "SELECT shp_id, shp_name
                        FROM bs_shipping
                        WHERE shp_name = :shp_name";

            if ($shpId !== null) {
                $sql .= " AND shp_id != :shp_id";
            }

            $sql .= " LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':shp_name', $shpName, PDO::PARAM_STR);

            if ($shpId !== null) {
                $stmt->bindParam(':shp_id', $shpId, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in checkShippingName : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 4. insertShipping ===================================
    function insertShipping($shpName, $newImg, $shpPrice, $shpDetail, $shpStatus)
    {
        try {
            $sql = "INSERT INTO bs_shipping(shp_name, shp_logo, shp_price, shp_detail, shp_status)
                        VALUES (:shp_name, :shp_logo, :shp_price, :shp_detail, :shp_status)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':shp_name', $shpName);
            $stmt->bindParam(':shp_logo', $newImg);
            $stmt->bindParam(':shp_price', $shpPrice);
            $stmt->bindParam(':shp_detail', $shpDetail);
            $stmt->bindParam(':shp_status', $shpStatus);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertShipping : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 5. getDetailShipping ===================================
    function getDetailShipping($shpId)
    {
        try {
            $sql = "SELECT * FROM bs_shipping
                    WHERE shp_id = :shp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':shp_id', $shpId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getDetailShipping : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 6. updateDetailShipping ===================================
    function updateDetailShipping($shpName, $shpPrice, $shpDetail, $shpStatus, $shpId)
    {
        try {
            $sql = "UPDATE bs_shipping
                    SET shp_name = :shp_name,
                        shp_price = :shp_price,
                        shp_detail = :shp_detail,
                        shp_status = :shp_status
                    WHERE shp_id = :shp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':shp_name', $shpName);
            $stmt->bindParam(':shp_price', $shpPrice);
            $stmt->bindParam(':shp_detail', $shpDetail);
            $stmt->bindParam(':shp_status', $shpStatus);
            $stmt->bindParam(':shp_id', $shpId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateDetailShipping : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 7. updateShippingLogo ===================================
    function updateShippingLogo($newImg, $shpId)
    {
        try {
            $sql = "UPDATE bs_shipping
                    SET shp_logo = :shp_logo
                    WHERE shp_id = :shp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':shp_logo', $newImg, PDO::PARAM_STR);
            $stmt->bindParam(':shp_id', $shpId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateShippingLogo : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 8. deleteShipping ===================================
    function deleteShipping($shpId)
    {
        try {
            $sql = "DELETE FROM bs_shipping
                    WHERE shp_id = :shp_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':shp_id', $shpId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteShipping : " . $e->getMessage();
            return false;
        }
    }
}
