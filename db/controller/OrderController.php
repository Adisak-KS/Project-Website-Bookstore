<?php
// ========================================== Cart ========================================== 
/* 
    1.  __construct
    2. deleteCartItem
    3. getOrderMemberAddress
    4. insertOrder
    5. getAccountOrderHistory
    6. getAccountOrderDetail
    7. getOrderHistoryItemDetail
    8. getOrderHistoryPromotionDetail
    9. getOrderHistoryShippingDetail
    10. getOrderHistoryAddressDetail
    11. getOrderHistoryPaymentDetail
    12. getOrderHistorySlipDetail
    13. getOrderLatest
    14. getOrderPendingPayment
    15. getPaymentForOrder
    16. getOrderItems
    17. cancelOrder
    18. cancelOrdersOlderThanThreeDays
    19. insertOrderSlip
    20. confirmOrder
    21. confirmOrdersOlderThanFourteenDays
    22. getAmountOrderStatusUnderReview
    23. getOrderStatusUnderReview
    24. getOrderDetailStatusUnderReview
    25. updateOrderStatusPaymentRetry
    26. updateOrderStatusAwaitingShipment
    27. getAmountOrderStatusAwaitingShipment
    28. getOrderStatusAwaitingShipment
    29. getOrderDetailStatusAwaitingShipment (Admin)
    30. updateOrderDetailStatusShipped

*/
// ============================================================================================

class OrderController extends BaseController
{
    // ============================= 1. __construct ===================================
    public function __construct($db)
    {
        parent::__construct($db);
        //  echo "<br> เรียกใช้ Order Controller สำเร็จ <br>";
    }

    // ============================= 2. deleteCartItem ===================================
    function deleteCartItem($memId, $prdId)
    {
        try {
            $sql = "DELETE FROM bs_cart
                    WHERE mem_id = :mem_id AND prd_id = :prd_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':prd_id', $prdId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in deleteCartItem : " . $e->getMessage();
            return false;
        }
    }

     // ============================= 3. getOrderMemberAddress ===================================
    function getOrderMemberAddress($addrId)
    {
        try {
            $sql = "SELECT * 
                    FROM bs_members_address
                    WHERE addr_id = :addr_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':addr_id', $addrId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in getOrderMemberAddress : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 4. insertOrder ===================================
    function insertOrder($memId, $ordCoinsDiscount, $ordCoinsEarned, $ordPrice, $productId, $productName, $productCoin, $cartQuantity, $productPrice, $productPercentDiscount, $addrId, $addrType, $addrFname, $addrLname, $addrPhone, $province, $district, $subDistrict, $zipCode, $addrDetail, $proId = NULL, $proName = NULL, $proPercentDiscount = NULL, $shpId, $shpName, $shpPrice, $pmtId, $pmtBank, $pmtName, $pmtNumber)
    {
        try {

            $this->db->beginTransaction();

            // Insert Order
            $sql = "INSERT INTO bs_orders (mem_id, ord_coins_discount, ord_coins_earned, ord_price)
                    VALUES (:mem_id, :ord_coins_discount, :ord_coins_earned, :ord_price)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':ord_coins_discount', $ordCoinsDiscount, PDO::PARAM_INT);
            $stmt->bindParam(':ord_coins_earned', $ordCoinsEarned, PDO::PARAM_INT);
            $stmt->bindParam(':ord_price', $ordPrice, PDO::PARAM_STR);
            $stmt->execute();

            $ordId = $this->db->lastInsertId(); // Get the order ID of the inserted order

            // Loop through products in the cart
            for ($i = 0; $i < count($productId); $i++) {
                $prdId = $productId[$i];
                $prdName = $productName[$i];
                $crtQty = $cartQuantity[$i];
                $prdCoin = $productCoin[$i];
                $prdPrice = $productPrice[$i];
                $prdPercentDiscount = $productPercentDiscount[$i];

                // Reduce product quantity
                $sql = "UPDATE bs_products
                        SET prd_quantity = prd_quantity - :crt_qty
                        WHERE prd_id = :prd_id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":crt_qty", $crtQty, PDO::PARAM_INT);
                $stmt->bindParam(":prd_id", $prdId, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount()) {
                    // Insert product items into order
                    $sql = "INSERT INTO bs_orders_items (ord_id, prd_id, oit_name, oit_coin, oit_quantity, oit_price, oit_percent_discount)
                            VALUES (:ord_id, :prd_id, :oit_name, :oit_coin, :oit_quantity, :oit_price, :oit_percent_discount)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(":ord_id", $ordId, PDO::PARAM_INT);
                    $stmt->bindParam(":prd_id", $prdId, PDO::PARAM_INT);
                    $stmt->bindParam(":oit_name", $prdName, PDO::PARAM_STR);
                    $stmt->bindParam(":oit_coin", $prdCoin, PDO::PARAM_INT);
                    $stmt->bindParam(":oit_quantity", $crtQty, PDO::PARAM_INT);
                    $stmt->bindParam(":oit_price", $prdPrice, PDO::PARAM_STR); // Ensure price is a string (for decimals)
                    $stmt->bindParam(":oit_percent_discount", $prdPercentDiscount, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

            // Insert Order Address
            $sql = "INSERT INTO bs_orders_address(ord_id, addr_id, oad_type, oad_fname, oad_lname, oad_phone, oad_province, oad_district, oad_subdistrict, oad_zip_code, oad_detail) 
                    VALUES (:ord_id, :addr_id, :oad_type, :oad_fname, :oad_lname, :oad_phone, :oad_province, :oad_district, :oad_subdistrict, :oad_zip_code, :oad_detail)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ord_id", $ordId, PDO::PARAM_INT);
            $stmt->bindParam(":addr_id", $addrId, PDO::PARAM_INT);
            $stmt->bindParam(":oad_type", $addrType, PDO::PARAM_INT);
            $stmt->bindParam(":oad_fname", $addrFname, PDO::PARAM_STR);
            $stmt->bindParam(":oad_lname", $addrLname, PDO::PARAM_STR);
            $stmt->bindParam(":oad_phone", $addrPhone, PDO::PARAM_STR);
            $stmt->bindParam(":oad_province", $province, PDO::PARAM_STR);
            $stmt->bindParam(":oad_district", $district, PDO::PARAM_STR);
            $stmt->bindParam(":oad_subdistrict", $subDistrict, PDO::PARAM_STR);
            $stmt->bindParam(":oad_zip_code", $zipCode, PDO::PARAM_INT);
            $stmt->bindParam(":oad_detail", $addrDetail, PDO::PARAM_STR);
            $stmt->execute();

            // Insert Order Promotion (if any)
            if ($proId !== NULL) {
                $sql = "INSERT INTO bs_orders_promotions(ord_id, pro_id, opm_name, opm_percent_discount)
                        VALUES (:ord_id, :pro_id, :opm_name, :opm_percent_discount)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":ord_id", $ordId, PDO::PARAM_INT);
                $stmt->bindParam(":pro_id", $proId, PDO::PARAM_INT);
                $stmt->bindParam(":opm_name", $proName, PDO::PARAM_STR);
                $stmt->bindParam(":opm_percent_discount", $proPercentDiscount, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Insert Order Shipping
            $sql = "INSERT INTO bs_orders_shippings(ord_id, shp_id, osp_name, osp_price)
                    VALUES (:ord_id, :shp_id, :osp_name, :osp_price)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ord_id", $ordId, PDO::PARAM_INT);
            $stmt->bindParam(":shp_id", $shpId, PDO::PARAM_INT);
            $stmt->bindParam(":osp_name", $shpName, PDO::PARAM_STR);
            $stmt->bindParam(":osp_price", $shpPrice, PDO::PARAM_STR); // Ensure price is a string (for decimals)
            $stmt->execute();

            // Insert Order Payment
            $sql = "INSERT INTO bs_orders_payments(ord_id, pmt_id, opm_bank, opm_name, opm_number)
                    VALUES (:ord_id, :pmt_id, :opm_bank, :opm_name, :opm_number)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ord_id", $ordId, PDO::PARAM_INT);
            $stmt->bindParam(":pmt_id", $pmtId, PDO::PARAM_INT);
            $stmt->bindParam(":opm_bank", $pmtBank, PDO::PARAM_STR);
            $stmt->bindParam(":opm_name", $pmtName, PDO::PARAM_STR);
            $stmt->bindParam(":opm_number", $pmtNumber, PDO::PARAM_INT);
            $stmt->execute();


            if ($ordCoinsDiscount > 0) {
                // Reduce coins of member
                $sql = "UPDATE bs_members
                        SET mem_coin = mem_coin - :mem_coin
                        WHERE mem_id = :mem_id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":mem_coin", $ordCoinsDiscount, PDO::PARAM_INT);
                $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
                $stmt->execute();

                // Reduce coins  History
                $sql = "INSERT INTO bs_members_history_coins(mhc_from_mem_id, mhc_coin_amount, mhc_transaction_type, ord_id)
                        VALUES (:mhc_from_mem_id, :mhc_coin_amount, 'discount', :ord_id)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':mhc_from_mem_id', $memId, PDO::PARAM_INT);
                $stmt->bindParam(':mhc_coin_amount', $ordCoinsDiscount, PDO::PARAM_INT);
                $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
                $stmt->execute();
            }


            // Delete Cart Items
            $sql = "DELETE FROM bs_cart
                    WHERE mem_id = :mem_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":mem_id", $memId, PDO::PARAM_INT);
            $stmt->execute();

            // Commit transaction
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollback();
            echo "<hr>Error in insertOrder: " . $e->getMessage();
            return false;
        }
    }

    // ============================= 5. getAccountOrderHistory ===================================
    function getAccountOrderHistory($memId)
    {
        try {
            $sql = " SELECT  ord_id,
                            ord_time_create,
                            ord_price,
                            ord_tracking_number,
                            ord_status
                    FROM bs_orders
                    WHERE mem_id = :mem_id
                    ORDER BY bs_orders.ord_time_create DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":mem_id", $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderHistory : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 6. getAccountOrderDetail ===================================
    function getAccountOrderDetail($ordId, $memId)
    {
        try {
            $sql = "SELECT  ord_id,
                            mem_id,
                            ord_coins_discount,
            	            ord_coins_earned,
                            ord_price,
                            ord_tracking_number,
                            ord_status,
                            ord_time_create
                    FROM bs_orders 
                    WHERE ord_id = :ord_id AND mem_id = :mem_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderDetail : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 7. getOrderHistoryItemDetail ===================================
    function getOrderHistoryItemDetail($ordId)
    {
        try {
            $sql = "SELECT  bs_orders_items.ord_id,
                            bs_orders_items.prd_id,
                            bs_orders_items.oit_name,
                            bs_orders_items.oit_coin,
                            bs_orders_items.oit_quantity,
                            bs_orders_items.oit_price,
                            bs_orders_items.oit_percent_discount,
                            (bs_orders_items.oit_price * (100 - bs_orders_items.oit_percent_discount) / 100) AS price_sale,
                            (bs_orders_items.oit_quantity * bs_orders_items.oit_coin) AS coins_per_item,
                            ((bs_orders_items.oit_price * (100 - bs_orders_items.oit_percent_discount) / 100) * bs_orders_items.oit_quantity) AS total_price_sale,
                            bs_products.prd_img1,
                            bs_products.prd_preorder
                    FROM bs_orders_items
                    LEFT JOIN bs_products ON bs_orders_items.prd_id = bs_products.prd_id
                    WHERE bs_orders_items.ord_id = :ord_id
                    ORDER BY bs_orders_items.oit_time_create DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in   getOrderHistoryItemDetail : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 8. getOrderHistoryPromotionDetail ===================================
    function getOrderHistoryPromotionDetail($ordId)
    {
        try {
            $sql = "SELECT  opm_name,
                            opm_percent_discount
                    FROM bs_orders_promotions
                    WHERE ord_id = :ord_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderHistoryPromotionDetail : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 9. getOrderHistoryShippingDetail ===================================
    function getOrderHistoryShippingDetail($ordId)
    {
        try {
            $sql = "SELECT  osp_name,
                            osp_price
                    FROM bs_orders_shippings
                    WHERE ord_id = :ord_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderHistoryShippingDetail : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 10. getOrderHistoryAddressDetail ===================================
    function getOrderHistoryAddressDetail($ordId)
    {
        try {
            $sql = "SELECT *
                    FROM bs_orders_address
                    WHERE ord_id = :ord_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getAccountOrderHistoryAddressDetail : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 11. getOrderHistoryPaymentDetail ===================================
    function getOrderHistoryPaymentDetail($ordId)
    {
        try {
            $sql = "SELECT  opm_bank,
                            opm_name,
                            opm_number
                    FROM bs_orders_payments
                    WHERE ord_id = :ord_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderHistoryPaymentDetail : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 12. getOrderHistorySlipDetail ===================================
    function getOrderHistorySlipDetail($ordId)
    {
        try {
            $sql = "SELECT osl_slip
                    FROM bs_orders_slips
                    WHERE ord_id = :ord_id
                    ORDER BY osl_time DESC ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderHistorySlipDetail : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 13. getOrderLatest ===================================
    function getOrderLatest($memId)
    {
        try {
            $sql = "SELECT  bs_orders.ord_id,
                            bs_orders.mem_id,
                            bs_orders.ord_price,
                            bs_orders.ord_coins_discount,
                            bs_orders.ord_time_update,
                            bs_orders_payments.pmt_id
                    FROM bs_orders
                    INNER JOIN bs_orders_payments ON bs_orders.ord_id = bs_orders_payments.ord_id
                    WHERE (bs_orders.ord_status = 'Pending Payment' OR bs_orders.ord_status = 'Payment Retry') 
                        AND bs_orders.mem_id = :mem_id
                    ORDER BY bs_orders.ord_id DESC
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":mem_id", $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderLatestPayment : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 14. getOrderPendingPayment ===================================
    function getOrderPendingPayment($ordId, $memId)
    {

        try {
            $sql = "SELECT  bs_orders.ord_id,
                            bs_orders.mem_id,
                            bs_orders.ord_price,
                            bs_orders.ord_coins_discount,
                            bs_orders.ord_time_update,
                            bs_orders_payments.pmt_id
                    FROM bs_orders
                    INNER JOIN bs_orders_payments ON bs_orders.ord_id = bs_orders_payments.ord_id
                    WHERE (bs_orders.ord_status = 'Pending Payment' OR bs_orders.ord_status = 'Payment Retry') 
                        AND bs_orders.ord_id = :ord_id
                        AND bs_orders.mem_id = :mem_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ord_id", $ordId, PDO::PARAM_INT);
            $stmt->bindParam(":mem_id", $memId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderPendingPayment : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 15. getPaymentForOrder ===================================
    function getPaymentForOrder($pmtId)
    {
        try {
            $sql = "SELECT  pmt_bank,
                            pmt_qrcode,
                            pmt_name,
                            pmt_number
                    FROM bs_payments
                    WHERE pmt_status = 1 AND pmt_id = :pmt_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":pmt_id", $pmtId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderLatestPayment : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 16. getOrderItems ===================================
    function getOrderItems($ordId)
    {
        try {
            $sql = "SELECT prd_id, oit_quantity
                    FROM bs_orders_items
                    WHERE ord_id = :ord_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ord_id", $ordId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOderItem : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 17. cancelOrder ===================================
    function cancelOrder($ordId, $memId, $ordCoinsDiscount)
    {
        try {
            $this->db->beginTransaction();

            // อัปเดทสถานะ order เป็น Cancel
            $sql = "UPDATE bs_orders
                    SET ord_status = 'Cancelled'
                    WHERE ord_id = :ord_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ord_id", $ordId, PDO::PARAM_INT);
            $stmt->execute();


            // อัปเดทจำนวนสินค้ากลับเข้า stock
            $orderItems = $this->getOrderItems($ordId);

            foreach ($orderItems as $row) {
                $prdId = $row['prd_id'];
                $oitQuantity = $row['oit_quantity'];

                $sql = "UPDATE bs_products
                        SET prd_quantity = prd_quantity + :oit_quantity
                        WHERE prd_id = :prd_id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":oit_quantity", $oitQuantity, PDO::PARAM_INT);
                $stmt->bindParam(":prd_id", $prdId, PDO::PARAM_INT);
                $stmt->execute();
            }

            if ($ordCoinsDiscount > 0) {

                // refund ให้กับสมาชิก
                $sql = "UPDATE bs_members
                        SET mem_coin = mem_coin + :ord_coins_discount
                        WHERE mem_id = :mem_id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":ord_coins_discount", $ordCoinsDiscount, PDO::PARAM_INT);
                $stmt->bindParam(":mem_id", $memId, PDO::PARAM_INT);
                $stmt->execute();

                // บันทึกประวัติการ refund เหรียญ
                $sql = "INSERT INTO bs_members_history_coins(mhc_to_mem_id, mhc_coin_amount, mhc_transaction_type, ord_id)
                        VALUES(:mhc_to_mem_id, :mhc_coin_amount, 'refund', :ord_id)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':mhc_to_mem_id', $memId, PDO::PARAM_INT);
                $stmt->bindParam(':mhc_coin_amount', $ordCoinsDiscount, PDO::PARAM_INT);
                $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
                $stmt->execute();
            }


            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in  cancelOrder : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 18. cancelOrdersOlderThanThreeDays ===================================
    function cancelOrdersOlderThanThreeDays()
    {
        try {
            // SQL คำสั่งสำหรับการเลือกคำสั่งซื้อที่ ord_time_update + 3 วัน
            $sql = "SELECT ord_id, mem_id, ord_coins_discount, ord_status, ord_time_update
                    FROM bs_orders
                    WHERE ord_status IN ('Pending Payment', 'Payment Retry')
                        AND ord_time_update <= DATE_SUB(NOW(), INTERVAL 3 DAY)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                foreach ($result as $order) {
                    $ordId = $order['ord_id'];
                    $memId = $order['mem_id'];
                    $ordCoinsDiscount = $order['ord_coins_discount'];

                    // ส่งไปยกเลิก order
                    $cancelOrders = $this->cancelOrder($ordId, $memId, $ordCoinsDiscount);
                }
            }
        } catch (PDOException $e) {
            echo "<hr>Error in  cancelOrder : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 19. insertOrderSlip ===================================
    function insertOrderSlip($ordId, $memId, $newImg)
    {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO bs_orders_slips(ord_id, mem_id, osl_slip)
                    VALUES (:ord_id, :mem_id, :osl_slip)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->bindParam(':mem_id', $memId, PDO::PARAM_INT);
            $stmt->bindParam(':osl_slip', $newImg, PDO::PARAM_STR);
            $stmt->execute();

            // อัปเดทสถานะ order เป็น Under Review
            $sql = "UPDATE bs_orders
                    SET ord_status = 'Under Review'
                    WHERE ord_id = :ord_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ord_id", $ordId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in insertOrderSli : " . $e->getMessage();
            return false;
        }
    }
    // ============================= 20. confirmOrder ===================================
    function confirmOrder($ordId, $memId, $ordCoinsEarned)
    {
        try {
            $this->db->beginTransaction();

            // อัปเดทสถานะ order เป็น Cancel
            $sql = "UPDATE bs_orders
                    SET ord_status = 'Completed'
                    WHERE ord_id = :ord_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ord_id", $ordId, PDO::PARAM_INT);
            $stmt->execute();

            if ($ordCoinsEarned > 0) {

                // refund ให้กับสมาชิก
                $sql = "UPDATE bs_members
                        SET mem_coin = mem_coin + :ord_coins_earned
                        WHERE mem_id = :mem_id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":ord_coins_earned", $ordCoinsEarned, PDO::PARAM_INT);
                $stmt->bindParam(":mem_id", $memId, PDO::PARAM_INT);
                $stmt->execute();

                // บันทึกประวัติการ refund เหรียญ
                $sql = "INSERT INTO bs_members_history_coins(mhc_to_mem_id, mhc_coin_amount, mhc_transaction_type, ord_id)
                        VALUES(:mhc_to_mem_id, :mhc_coin_amount, 'purchase', :ord_id)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':mhc_to_mem_id', $memId, PDO::PARAM_INT);
                $stmt->bindParam(':mhc_coin_amount', $ordCoinsEarned, PDO::PARAM_INT);
                $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
                $stmt->execute();
            }


            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in  confirmOrder : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 21. confirmOrdersOlderThanFourteenDays ===================================
    function confirmOrdersOlderThanFourteenDays()
    {
        try {
            $sql = " SELECT ord_id, mem_id, ord_coins_earned, ord_status, ord_time_update
                    FROM bs_orders
                    WHERE ord_status = 'Shipped'
                    AND ord_time_update <= DATE_SUB(NOW(), INTERVAL 14 DAY)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                foreach ($result as $order) {
                    $ordId = $order['ord_id'];
                    $memId = $order['mem_id'];
                    $ordCoinsEarned = $order['ord_coins_earned'];

                    // ส่งไป confirm order
                    $confirmOrders = $this->confirmOrder($ordId, $memId, $ordCoinsEarned);
                }
            }
        } catch (PDOException $e) {
            $this->db->rollBack();
            echo "<hr>Error in  confirmOrder : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 22. getAmountOrderStatusUnderReview ===================================
    function getAmountOrderStatusUnderReview()
    {
        try {
            $sql = "SELECT COUNT(ord_id) as total_order_under_review
                    FROM bs_orders
                    WHERE  ord_status = 'Under Review'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_order_under_review'];
        } catch (PDOException $e) {
            echo "<hr>Error in  getAmountOrderStatusUnderReview : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 23. getOrderStatusUnderReview ===================================
    function getOrderStatusUnderReview()
    {
        try {
            $sql = "SELECT 	bs_orders.ord_id,
                            bs_orders.ord_price,
                            bs_orders.ord_status,
                            bs_orders.ord_time_update,
                            bs_members.mem_username
                    FROM bs_orders
                    INNER JOIN bs_members ON bs_orders.mem_id = bs_members.mem_id
                    WHERE bs_orders.ord_status = 'Under Review'
                    ORDER BY bs_orders.ord_time_update ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderStatusUnderReview : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 24. getOrderDetailStatusUnderReview ===================================
    function getOrderDetailStatusUnderReview($ordId)
    {
        try {
            $sql = "SELECT  ord_id,
                                 mem_id,
                                 ord_coins_discount,
                                 ord_coins_earned,
                                 ord_price,
                                 ord_tracking_number,
                                 ord_status,
                                 ord_time_update
                             FROM bs_orders 
                             WHERE ord_status = 'Under Review' AND ord_id = :ord_id
                             LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderDetail : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 25. updateOrderStatusPaymentRetry ===================================
    function updateOrderStatusPaymentRetry($ordId)
    {
        try {
            $sql = "UPDATE bs_orders
                    SET ord_status = 'Payment Retry'
                    WHERE ord_id = :ord_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in  updateOrderStatusPaymentRetry : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 26. updateOrderStatusAwaitingShipment ===================================
    function updateOrderStatusAwaitingShipment($ordId)
    {
        try {
            $sql = "UPDATE bs_orders
                     SET ord_status = 'Awaiting Shipment'
                     WHERE ord_id = :ord_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in  updateOrderStatusAwaitingShipment : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 27. getAmountOrderStatusAwaitingShipment ===================================
    function getAmountOrderStatusAwaitingShipment()
    {
        try {
            $sql = "SELECT COUNT(ord_id) as total_order_awaiting_shipment
                    FROM bs_orders
                    WHERE  ord_status = 'Awaiting Shipment'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_order_awaiting_shipment'];
        } catch (PDOException $e) {
            echo "<hr>Error in  getAmountOrderStatusAwaitingShipment : " . $e->getMessage();
            return false;
        }
    }
    
    // ============================= 28. getOrderStatusAwaitingShipment ===================================
    function getOrderStatusAwaitingShipment()
    {
        try {
            $sql = "SELECT 	bs_orders.ord_id,
                            bs_orders.ord_price,
                            bs_orders.ord_status,
                            bs_orders.ord_time_update,
                            bs_members.mem_username
                    FROM bs_orders
                    INNER JOIN bs_members ON bs_orders.mem_id = bs_members.mem_id
                    WHERE bs_orders.ord_status = 'Awaiting Shipment'
                    ORDER BY bs_orders.ord_time_update ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderStatusAwaitingShipment : " . $e->getMessage();
            return false;
        }
    }
    
    // ============================= 29. getOrderDetailStatusAwaitingShipment (Admin) ===================================
    function getOrderDetailStatusAwaitingShipment($ordId)
    {
        try {
            $sql = "SELECT  ord_id,
                                 mem_id,
                                 ord_coins_discount,
                                 ord_coins_earned,
                                 ord_price,
                                 ord_tracking_number,
                                 ord_status,
                                 ord_time_update
                             FROM bs_orders 
                             WHERE ord_status = 'Awaiting Shipment' AND ord_id = :ord_id
                             LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            echo "<hr>Error in  getOrderDetailStatusAwaitingShipment : " . $e->getMessage();
            return false;
        }
    }

    // ============================= 30. updateOrderDetailStatusShipped ===================================
    function updateOrderDetailStatusShipped($ordTrackingNumber, $ordId)
    {
        try {
            $sql = "UPDATE bs_orders
                    SET ord_tracking_number = :ord_tracking_number,
                        ord_status = 'Shipped'
                    WHERE ord_id = :ord_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ord_tracking_number', $ordTrackingNumber, PDO::PARAM_STR);
            $stmt->bindParam(':ord_id', $ordId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "<hr>Error in updateOrderDetailStatusShipped : " . $e->getMessage();
            return false;
        }
    }

}
