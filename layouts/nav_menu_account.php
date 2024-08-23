<div class="myaccount-tab-menu nav" role="tablist">
    <?php
    // รับ URL ปัจจุบัน
    $current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), ".php");

    ?>

    <a href="account_show" class="<?php echo ($current_page == 'account_show') ? 'active' : ''; ?>">
        <i class="fa fa-dashboard"></i>
        ข้อมูลส่วนตัว
    </a>

    <a href="account_password" class="<?php echo ($current_page == 'account_password') ? 'active' : ''; ?>">
        <i class="fa fa-cart-arrow-down"></i>
        จัดการรหัสผ่าน
    </a>

    <a href="account_address" class="<?php echo ($current_page == 'account_address' || $current_page == 'account_address_edit_form') ? 'active' : ''; ?>">
        <i class="fa fa-cloud-download"></i>
        ที่อยู่
    </a>

    <a href="account_wishlist" class="<?php echo ($current_page == 'account_wishlist') ? 'active' : ''; ?>">
        <i class="fa fa-cloud-download"></i>
        รายการที่ชอบ
    </a>

    <a href="account_order_history" class="<?php echo ($current_page == 'account_order_history') ? 'active' : ''; ?>">
        <i class="fa fa-credit-card"></i>
        ประวัติการสั่งซื้อ
    </a>

    <a href="account_product_request" class="<?php echo ($current_page == 'account_product_request' || $current_page == 'account_product_request_detail') ? 'active' : ''; ?>">
        <i class="fa fa-map-marker"></i>
        ประวัติรายการหาหนังสือตามสั่ง
    </a>

    <a href="account_transfer_coins" class="<?php echo ($current_page == 'account_transfer_coins') ? 'active' : ''; ?>">
        <i class="fa fa-user"></i>
        โอนเหรียญ
    </a>

    <a href="account_transfer_coins_history" class="<?php echo ($current_page == 'account_coin_history') ? 'active' : ''; ?>">
        <i class="fa fa-sign-out"></i>
        ประวัติรายการโอนเหรียญ
    </a>
</div>