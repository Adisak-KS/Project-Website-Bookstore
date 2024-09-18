<div class="myaccount-tab-menu nav" role="tablist">
    <?php
    // รับ URL ปัจจุบัน
    $current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), ".php");

    ?>

    <a href="account_show" class="<?php echo ($current_page == 'account_show') ? 'active' : ''; ?>">
        <i class="fa-solid fa-user-gear"></i>
        ข้อมูลส่วนตัว
    </a>

    <a href="account_password" class="<?php echo ($current_page == 'account_password') ? 'active' : ''; ?>">
        <i class="fa-solid fa-key"></i>
        จัดการรหัสผ่าน
    </a>

    <a href="account_address" class="<?php echo ($current_page == 'account_address' || $current_page == 'account_address_edit_form') ? 'active' : ''; ?>">
        <i class="fa-solid fa-house-user"></i>
        ที่อยู่
    </a>

    <a href="account_wishlist" class="<?php echo ($current_page == 'account_wishlist') ? 'active' : ''; ?>">
        <i class="fa-solid fa-heart"></i>
        รายการที่ชอบ
    </a>

    <a href="account_order_history" class="<?php echo ($current_page == 'account_order_history' || $current_page == 'account_order_history_detail') ? 'active' : ''; ?>">
        <i class="fa-solid fa-cart-arrow-down"></i>
        ประวัติการสั่งซื้อ
    </a>

    <a href="account_product_request" class="<?php echo ($current_page == 'account_product_request' || $current_page == 'account_product_request_detail') ? 'active' : ''; ?>">
    <i class="fa-solid fa-book"></i>
        ประวัติรายการหาหนังสือตามสั่ง
    </a>

    <a href="account_transfer_coins" class="<?php echo ($current_page == 'account_transfer_coins') ? 'active' : ''; ?>">
    <i class="fa-brands fa-gg-circle"></i>
        โอนเหรียญ
    </a>
</div>