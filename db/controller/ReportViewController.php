<?php
//  ================================= Report Product Views ===============================
/* 
1.  __construct
2. getReportProductView
3. getSearchReportProductView
4. getChartReportProductView
5. getChartReportProductTypeView
*/
// ============================================================================================

class ReportViewController extends BaseController
{

    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Report View Controller สำเร็จ <br>";
    }

    // ============================= 2. getReportProductView ===================================
    function getReportProductView()
    {
        try {
            $sql = "SELECT bs_products_views.*, bs_products.prd_name, bs_products_type.pty_name
                    FROM bs_products_views
                    JOIN bs_products ON bs_products_views.prd_id = bs_products.prd_id
                    JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id
                    WHERE bs_products_views.prv_view != 0
                    ORDER BY bs_products_views.prv_time DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getReportProductView : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 3.  getProductTypeName ===================================
    function getProductTypeName()
    {
        try {
            $sql = "SELECT pty_name
                    FROM bs_products_type";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getProductTypeName : " . $e->getMessage();
            return false;
        }
    }
    // ============================= 3. getSearchReportProductView ===================================
    function getSearchReportProductView($time_start, $time_end, $prd_name, $pty_name)
    {
        try {
            $sql = "SELECT bs_products_views.*, bs_products.prd_name, bs_products_type.pty_name
                    FROM bs_products_views
                    JOIN bs_products ON bs_products_views.prd_id = bs_products.prd_id
                    JOIN bs_products_type ON bs_products.pty_id = bs_products_type.pty_id
                    WHERE 1=1";

            // Adding conditions based on parameters
            if ($time_start) {
                $sql .= " AND bs_products_views.prv_time >= :time_start";
            }
            if ($time_end) {
                $sql .= " AND bs_products_views.prv_time <= :time_end";
            }
            if ($prd_name) {
                $sql .= " AND bs_products.prd_name LIKE :prd_name";
            }
            if ($pty_name) {
                $sql .= " AND bs_products_type.pty_name LIKE :pty_name";
            }

            $sql .= " AND bs_products_views.prv_view != 0";

            $stmt = $this->db->prepare($sql);

            // Binding parameters
            if ($time_start) {
                $stmt->bindParam(':time_start', $time_start);
            }
            if ($time_end) {
                $stmt->bindParam(':time_end', $time_end);
            }
            if ($prd_name) {
                $prd_name = '%' . $prd_name . '%';
                $stmt->bindParam(':prd_name', $prd_name);
            }
            if ($pty_name) {
                $pty_name = '%' . $pty_name . '%';
                $stmt->bindParam(':pty_name', $pty_name);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getSearchReportProductView : " . $e->getMessage();
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

    // ============================= 5. getChartReportProductTypeView ===================================
    function getChartReportProductTypeView()
    {
        try {
            $sql = "SELECT bs_products_type.pty_id, bs_products_type.pty_name, SUM(bs_products_views.prv_view) AS total_views
                    FROM bs_products_views
                    JOIN bs_products_type ON bs_products_views.pty_id = bs_products_type.pty_id
                    WHERE bs_products_views.prv_view != 0
                    GROUP BY bs_products_views.pty_id
                    ORDER BY total_views DESC
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


    function insertReportView($prdId, $ptyId)
    {
        try {
            $sql = "INSERT INTO bs_products_views(prd_id, pty_id, prv_view)
                    VALUES (:prd_id, :pty_id, 1)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->bindParam(':pty_id', $ptyId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in insertReportView : " . $e->getMessage();
            return false;
        }
    }
}
