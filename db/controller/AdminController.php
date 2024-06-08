 <!-- Admin  -->
 <?php
   class AdminController extends BaseController
   {
      public function __construct($db)
      {
          parent::__construct($db);
         //  echo "<br> เรียกใช้ Admin Controller สำเร็จ <br>";
      }

      function getAdmin()
      {
         try {
            $sql = "SELECT *
                     FROM bs_employees";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
         } catch (PDOException $e) {
            echo "<hr>Error in getAdmin : " . $e->getMessage();
         }
      }
   }

   ?>