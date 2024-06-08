<!-- Base Controller  -->
<?php

class BaseController
{
    protected $db;

    public function __construct($conn)
    {
        $this->db = $conn;
        // echo "<br> เรียกใช้ Base Controller สำเร็จ <br>";
    }


    // ==================================  เมธอดที่ใช้ร่วมกันสามารถใส่ไว้ในนี้ได้ ======================================================= 

    // Check Employees Authority Type Default
    function getEmmpoyeesAuthorityTypeDefault()
    {
        try {
            $sql = "SELECT eat_id, eat_name
                    FROM bs_employees_authority_type
                    WHERE eat_name
                    IN ('Super Admin', 'Owner', 'Admin', 'Accounting', 'Sale', 'Employee')";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<hr>Error in getEmmpoyeesAuthorityTypeDefault : " . $e->getMessage();
        }
    }

    // Insert Employee Authority Type Default
    function insertEmployeesTypeDefault()
    {
        try {
            // ตรวจสอบ Employee Authority Type 
            $check = $this->getEmmpoyeesAuthorityTypeDefault();


            // สร้างรายการของสิทธิ์ที่ต้องการในตาราง
            $requiredAuthorityTypes = [
                ['id' => 1, 'name' => 'Super Admin',    'eat_detail' => 'เจ้าของระบบ'],
                ['id' => 2, 'name' => 'Owner',          'eat_detail' => 'เจ้าของร้าน / ผู้บริหาร'],
                ['id' => 3, 'name' => 'Admin',          'eat_detail' => 'ผู้ดูแลระบบ'],
                ['id' => 4, 'name' => 'Accounting',     'eat_detail' => 'พนักงานบัญชี'],
                ['id' => 5, 'name' => 'Sale',           'eat_detail' => 'พนักงานขาย'],
                ['id' => 6, 'name' => 'Employee',       'eat_detail' => 'พนักงานทั่วไป']
            ];


            // หาประเภทสิทธิ์ที่ขาดหายไปในฐานข้อมูล
            $missingAuthorityTypes = array_diff(array_column($requiredAuthorityTypes, 'name'), array_column($check, 'eat_name'));


            // หากมีสิทธิ์ที่หายไป ให้ทำการเพิ่มเข้าไปในฐานข้อมูล
            if (!empty($missingAuthorityTypes)) {

                foreach ($missingAuthorityTypes as $authorityType) {

                    // หาข้อมูลของสิทธิ์ที่ต้องการเพิ่ม
                    $type = array_filter($requiredAuthorityTypes, fn ($item) => $item['name'] === $authorityType);

                    // ถ้าพบข้อมูลสิทธิ์ที่ต้องการเพิ่ม
                    if (!empty($type)) {
                        $type = current($type);
                        // เพิ่มสิทธิ์ที่ไม่มีลงในฐานข้อมูล
                        $sql = "INSERT INTO bs_employees_authority_type (eat_id, eat_name, eat_detail) 
                                VALUES (:eat_id, :eat_name, :eat_detail)";
                        $stmt = $this->db->prepare($sql);
                        $stmt->bindParam(':eat_id', $type['id']);
                        $stmt->bindParam(':eat_name', $type['name']);
                        $stmt->bindParam(':eat_detail', $type['eat_detail']);
                        $stmt->execute();
                    }
                }
            }
        } catch (PDOException $e) {
            echo "<hr>Error in insertEmployeesTypeDefault : " . $e->getMessage();
        }
    }


    // check Super Admin
    function getEmployeesSuperAdminDefault()
    {
        try {
        } catch (PDOException $e) {
            echo "<hr>Error in getEmployeesSuperAdminDefault : " . $e->getMessage();
        }
    }
}
