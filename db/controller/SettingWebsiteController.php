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
                $sql = "SELECT * FROM bs_settings_website";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "<hr>Error in getSettingsWebsite : " . $e->getMessage();
                return false;
            }
        }

        function getDetailSettingWebsite($stId){
            try{
                $sql = "SELECT * 
                        FROM bs_settings_website 
                        WHERE st_id = :stId";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':stId', $stId, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
            }catch(PDOException $e){
                echo "<hr>Error in getDetailSettingWebsite : " . $e->getMessage();
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
    }
