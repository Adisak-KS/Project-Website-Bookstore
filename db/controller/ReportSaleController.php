<?php
//  ================================= Report Product Sale ===============================
/* 
1.  __construct
2. getReportProductView
3. getSearchReportProductView
4. getChartReportProductView
5. getChartReportProductTypeView
*/
// ============================================================================================

class ReportSaleController extends BaseController
{

    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Report Sale Controller สำเร็จ <br>";
    }

    // ============================= 2. getReportProductSale ===================================
    function getReportProductSale()
    {
        try {
            $sql = "SELECT bs_orders.ord_time_create,
                            bs_orders.ord_price,
                            bs_orders.ord_status,
                            bs_members.mem_username
                    FROM bs_orders
                    INNER JOIN bs_members On bs_orders.mem_id = bs_members.mem_id
                    WHERE bs_orders.ord_status = 'Completed'
                    ORDER BY bs_orders.ord_time_create DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getReportProductSale : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 3. getSearchReportProductView ===================================
    // function getSearchReportProductSale($time_start, $time_end, $price_start, $price_end, $mem_username, $ord_status)
    // {
    //     try {
    //         $sql = "SELECT  bs_orders.ord_time_create,
    //                         bs_orders.ord_price,
    //                         bs_orders.ord_status,
    //                         bs_members.mem_username
    //                 FROM bs_orders
    //                 INNER JOIN bs_members On bs_orders.mem_id = bs_members.mem_id
    //                 WHERE 1=1"; // ใช้ 1=1 เป็นเงื่อนไขเริ่มต้นเพื่อให้สามารถต่อ AND ได้

    //         // Adding conditions based on parameters
    //         if ($time_start) {
    //             $sql .= " AND bs_orders.ord_time_create >= :time_start";
    //         }

    //         if ($time_end) {
    //             $sql .= " AND bs_orders.ord_time_create <= :time_end";
    //         }
    //         if ($price_start) {
    //             $sql .= " AND bs_orders.ord_price >= :price_start";
    //         }
    //         if ($price_end) {
    //             $sql .= " AND bs_orders.ord_price <= :price_end";
    //         }

    //         if ($mem_username) {
    //             $sql .= " AND bs_members.mem_username LIKE :mem_username";
    //         }

    //         if ($ord_status) {
    //             $sql .= " AND bs_orders.ord_status = :ord_status"; // เปลี่ยนเป็น = เพื่อการค้นหาตรงตัว
    //         }

    //         $sql .= " ORDER BY bs_orders.ord_time_create DESC";

    //         $stmt = $this->db->prepare($sql);

    //         // Binding parameters
    //         if ($time_start) {
    //             $stmt->bindParam(':time_start', $time_start);
    //         }
    //         if ($time_end) {
    //             $stmt->bindParam(':time_end', $time_end);
    //         }
    //         if ($price_start) {
    //             $stmt->bindParam(':price_start', $price_start);
    //         }
    //         if ($price_end) {
    //             $stmt->bindParam(':price_end', $price_end);
    //         }

    //         if ($mem_username) {
    //             $mem_username = '%' . $mem_username . '%';
    //             $stmt->bindParam(':mem_username', $mem_username);
    //         }

    //         if ($ord_status) {
    //             $stmt->bindParam(':ord_status', $ord_status);
    //         }

    //         $stmt->execute();
    //         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //         return $result;
    //     } catch (PDOException $e) {
    //         echo "<hr>Error in getSearchReportProductSale : " . $e->getMessage();
    //         return false;
    //     }
    // }
    function getSearchReportProductSale($time_start, $time_end, $price_start, $price_end, $mem_username, $ord_status)
    {
        try {
            $sql = "SELECT  bs_orders.ord_time_create,
                            bs_orders.ord_price,
                            bs_orders.ord_status,
                            bs_members.mem_username
                    FROM bs_orders
                    INNER JOIN bs_members On bs_orders.mem_id = bs_members.mem_id
                    WHERE 1=1"; // ใช้ 1=1 เป็นเงื่อนไขเริ่มต้นเพื่อให้สามารถต่อ AND ได้

            // Adding conditions based on parameters
            if ($time_start) {
                $sql .= " AND bs_orders.ord_time_create >= :time_start";
            }

            if ($time_end) {
                $sql .= " AND bs_orders.ord_time_create <= :time_end";
            }
            if (isset($price_start)) { // รองรับกรณีที่เป็น 0 ได้
                $sql .= " AND bs_orders.ord_price >= :price_start";
            }

            if (isset($price_end)) { // รองรับกรณีที่เป็น 0 ได้
                $sql .= " AND bs_orders.ord_price <= :price_end";
            }

            if ($mem_username) {
                $sql .= " AND bs_members.mem_username LIKE :mem_username";
            }

            if ($ord_status) {
                $sql .= " AND bs_orders.ord_status = :ord_status"; // เปลี่ยนเป็น = เพื่อการค้นหาตรงตัว
            }

            $sql .= " ORDER BY bs_orders.ord_time_create DESC";

            $stmt = $this->db->prepare($sql);

            // Binding parameters
            if ($time_start) {
                $stmt->bindParam(':time_start', $time_start);
            }
            if ($time_end) {
                $stmt->bindParam(':time_end', $time_end);
            }
            if (isset($price_start)) { // รองรับกรณีที่เป็น 0 ได้
                $stmt->bindParam(':price_start', $price_start);
            }
            if (isset($price_end)) { // รองรับกรณีที่เป็น 0 ได้
                $stmt->bindParam(':price_end', $price_end);
            }

            if ($mem_username) {
                $mem_username = '%' . $mem_username . '%';
                $stmt->bindParam(':mem_username', $mem_username);
            }

            if ($ord_status) {
                $stmt->bindParam(':ord_status', $ord_status);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getSearchReportProductSale : " . $e->getMessage();
            return false;
        }
    }


    // ============================= 4. getChartReportProductView ===================================
    function getChartReportProductView()
    {
        try {
            $sql = "SELECT bs_products.prd_id, bs_products.prd_name, SUM(bs_products_views.prv_view) AS total_views
                    FROM bs_products_views
                    JOIN bs_products ON bs_products_views.prd_id = bs_products.prd_id
                    WHERE bs_products_views.prv_view != 0
                    GROUP BY bs_products_views.prd_id
                    ORDER BY total_views DESC
                    LIMIT 20";


            // WHERE bs_products_views.prv_view != 0
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getReportProductView : " . $e->getMessage();
            return false;
        }
    }


    function getReportSales12Months()
    {
        try {
            $sql = "SELECT 
                        DATE_FORMAT(ord_time_create, '%Y') AS year,
                        CASE MONTH(ord_time_create)
                            WHEN 1 THEN 'มกราคม'
                            WHEN 2 THEN 'กุมภาพันธ์'
                            WHEN 3 THEN 'มีนาคม'
                            WHEN 4 THEN 'เมษายน'
                            WHEN 5 THEN 'พฤษภาคม'
                            WHEN 6 THEN 'มิถุนายน'
                            WHEN 7 THEN 'กรกฎาคม'
                            WHEN 8 THEN 'สิงหาคม'
                            WHEN 9 THEN 'กันยายน'
                            WHEN 10 THEN 'ตุลาคม'
                            WHEN 11 THEN 'พฤศจิกายน'
                            WHEN 12 THEN 'ธันวาคม'
                        END AS month_name,
                        SUM(ord_price) AS total_revenue
                    FROM bs_orders
                    WHERE ord_time_create >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                    GROUP BY year, month_name
                    ORDER BY year DESC, MONTH(ord_time_create)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getReportSales12Months : " . $e->getMessage();
            return false;
        }
    }


    // ============================= 5. getChartReportProductTypeView ===================================
    function getChartReportProductPopular()
    {
        try {
            $sql = "SELECT bs_orders_items.prd_id,
                        bs_products.prd_name,
                        SUM(bs_orders_items.oit_quantity) AS total_quantity
                    FROM bs_orders
                    INNER JOIN bs_orders_items ON bs_orders.ord_id = bs_orders_items.ord_id
                    INNER JOIN bs_products ON bs_orders_items.prd_id = bs_products.prd_id
                    GROUP BY bs_orders_items.prd_id, bs_products.prd_name
                    ORDER BY total_quantity DESC
                    LIMIT 20";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getReportProductView : " . $e->getMessage();
            return false;
        }
    }
}
