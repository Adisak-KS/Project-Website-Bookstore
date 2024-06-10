<?php

class AuthorityTypeController extends BaseController
{
    public function __construct($db)
    {
        parent::__construct($db);
        // echo "<br> เรียกใช้ Authority Type Controller สำเร็จ <br>";
    }

    function getAuthorityTypeEmployees()
    {
        try {
            $sql = "SELECT * 
                    FROM bs_employees_authority_type";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getAdmin : " . $e->getMessage();
            return false;
        }
    }
}
