    <!-- ========================================== Owner ========================================== 
 
   1.  __construct
   2. getOwner
   3. insertOwner
   4. getDetailOwner
   5. updateDetailOwner
   6. updateAuthorityOwner

============================================================================================ -->
    <?php
    class SettingWebsiteController extends BaseController
    {
        public function __construct($db)
        {
            parent::__construct($db);
            // echo "<br> เรียกใช้ Setting Website Controller สำเร็จ <br>";
        }

        // Wesite Settings
        function getSettingsWebsite()
        {
            try {
                $sql = "SELECT * FROM bs_settings_website
                        WHERE st_id != 4";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "<hr>Error in getSettingsWebsite : " . $e->getMessage();
                return false;
            }
        }

        function getDetailSettingWebsite($stId)
        {
            try {
                $sql = "SELECT * 
                        FROM bs_settings_website 
                        WHERE st_id = :stId";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':stId', $stId, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
            } catch (PDOException $e) {
                echo "<hr>Error in getDetailSettingWebsite : " . $e->getMessage();
                return false;
            }
        }

        function updateDetailSettingWebsite($stStatus, $stId, $stDetail = null)
        {
            try {
                $sql = "UPDATE bs_settings_website
                        SET st_status = :st_status";

                // Check if $stDetail is provided and append it to the SQL query
                if ($stDetail !== null) {
                    $sql .= ", st_detail = :st_detail";
                }

                $sql .= " WHERE st_id = :st_id";

                $stmt = $this->db->prepare($sql);

                // Bind parameters
                $stmt->bindParam(':st_status', $stStatus, PDO::PARAM_INT);
                $stmt->bindParam(':st_id', $stId, PDO::PARAM_INT);

                // Bind st_detail parameter if $stDetail is provided
                if ($stDetail !== null) {
                    $stmt->bindParam(':st_detail', $stDetail, PDO::PARAM_STR);
                }

                // Execute the statement
                $stmt->execute();

                return true;
            } catch (PDOException $e) {
                echo "<hr>Error in updateDetailSettingWebsite : " . $e->getMessage();
                return false;
            }
        }

        // Use Website Sitting 
        function useSettingsWebsite()
        {
            try {
                $sql = "SELECT st_id, st_name, st_detail
                        FROM bs_settings_website
                        WHERE st_status = 1 AND st_detail IS NOT NULL AND st_detail != '' ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "<hr>Error in getSettingsWebsite : " . $e->getMessage();
                return false;
            }
        }


        function getProductNumberLow()
        {
           try{
            $sql = "SELECT st_detail FROM bs_settings_website WHERE st_id = 4  LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            return $result;
           }catch(PDOException $e){
            echo "<hr>Error in getProductNumberLow : " . $e->getMessage();
            return false;
           }
        }
       

        function updateProductNumberLow($stDetail)
        {
           try{
            $sql = "UPDATE bs_settings_website
                    SET st_detail = :st_detail
                    WHERE st_id = 4";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':st_detail', $stDetail, PDO::PARAM_INT);
            $stmt->execute();
            return true;
           }catch(PDOException $e){
            echo "<hr>Error in updateProductNumberLow : " . $e->getMessage();
            return false;
           }
        }

        function getProductPercentDiscount()
        {
           try{
            $sql = "SELECT st_detail FROM bs_settings_website WHERE st_id = 5  LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            return $result;
           }catch(PDOException $e){
            echo "<hr>Error in getProductPercentDiscount : " . $e->getMessage();
            return false;
           }
        }

        function updateProductPercentDiscount($stDetail)
        {
           try{
            $sql = "UPDATE bs_settings_website
                    SET st_detail = :st_detail
                    WHERE st_id = 5";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':st_detail', $stDetail, PDO::PARAM_INT);
            $stmt->execute();
            return true;
           }catch(PDOException $e){
            echo "<hr>Error in updateProductPercentDiscount : " . $e->getMessage();
            return false;
           }
        }
    }
