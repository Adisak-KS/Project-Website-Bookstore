<!-- ========== Left Sidebar Start ========== -->


<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            <?php if (empty($useLoginEmployee['emp_profile'])) { ?>
                <img src="../uploads/img_employees/default.png ?>" alt="user-img" title="Mat Helme" class="rounded-circle img-thumbnail avatar-md">
            <?php } else { ?>
                <img src="../uploads/img_employees/<?php echo $useLoginEmployee['emp_profile'] ?>" alt="user-img" title="Mat Helme" class="rounded-circle img-thumbnail avatar-md">
            <?php } ?>

            <div class="dropdown">
                <a href="" class="user-name h5 mt-2 mb-1 d-block"><?php echo $useLoginEmployee['emp_fname'] . " " . $useLoginEmployee['emp_lname']; ?></a>
            </div>

            <p class="text-muted left-user-info">
                <?php
                // อาเรย์สำหรับแมพตัวเลขกับชื่อบทบาท
                $roleMap = [
                    1 => 'super Admin',
                    2 => 'Owner',
                    3 => 'Admin',
                    4 => 'Accounting',
                    5 => 'Sale',
                    6 => 'Employee'
                ];

                // สมมุติว่า $useLoginEmployee['authority'] มีค่าเป็น '1,2,3,4,5,6'
                $authorities = explode(',', $useLoginEmployee['authority']);

                // แปลงตัวเลขเป็นชื่อบทบาท
                $roles = array_map(function ($authority) use ($roleMap) {
                    return $roleMap[$authority];
                }, $authorities);

                // แสดงชื่อบทบาท
                echo implode('<br> ', $roles);
                ?>

            </p>
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="logout" onclick="confirmLogout(event)">
                        <i class="fa-solid fa-power-off text-danger"></i>
                    </a>
                </li>
            </ul>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">



            <ul id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="index">
                        <i class="fa-brands fa-microsoft"></i>
                        <span> หน้าหลัก</span>
                    </a>
                </li>
                <?php
                $authority = $useLoginEmployee['authority'];

                // Super Admin 
                if (in_array(1, str_split($authority))) {
                    echo '
                    <li class="menu-title mt-2">จัดการบุคล</li>

                    <li>
                        <a href="employees_aythority_type_show">
                            <i class="fa-solid fa-users-gear"></i>
                            <span> ประเภทสิทธิ์พนักงาน </span>
                        </a>
                    </li>

                    <li>
                        <a href="owner_show">
                            <i class="fa-solid fa-user-tie"></i>
                            <span> เจ้าของร้าน / ผู้บริหาร </span>
                        </a>
                    </li>

                    <li>
                        <a href="admin_show">
                            <i class="fa-solid fa-user-shield"></i>
                            <span> ผู้ดูแลระบบ </span>
                        </a>
                    </li>

                    <li>
                        <a href="employee_show">
                            <i class="fa-solid fa-users-gear"></i>
                            <span> พนักงาน</span>
                        </a>
                    </li>

                    <li>
                        <a href="member_show">
                            <i class="fa-solid fa-user"></i>
                            <span> สมาชิก</span>
                        </a>
                    </li>

                    <li class="menu-title mt-2">จัดการสินค้า</li>

                    <li>
                        <a href="product_type_show">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <span> ประเภทสินค้า</span>
                        </a>
                    </li>

                    <li>
                        <a href="publisher_show">
                            <i class="fa-solid fa-city"></i>
                            <span> สำนักพิมพ์</span>
                        </a>
                    </li>

                    <li>
                        <a href="author_show">
                            <i class="fa-solid fa-pen-clip"></i>
                            <span> ผู้แต่ง</span>
                        </a>
                    </li>

                    <li>
                        <a href="promotion_show">
                            <i class="fa-solid fa-tag"></i>
                            <span> โปรโมชั่น</span>
                        </a>
                    </li>

                    <li>
                        <a href="product_show">
                            <i class="fa-solid fa-book"></i>
                            <span> สินค้า</span>
                        </a>
                    </li>

                    <li class="menu-title mt-2">จัดการการชำระเงิน</li>

                    <li>
                        <a href="payment_show">
                            <i class="fa-brands fa-paypal"></i>
                            <span> ช่องทางชำระเงิน</span>
                        </a>
                    </li>

                    <li>
                        <a href="shipping_show">
                            <i class="fa-solid fa-truck"></i>
                            <span> ช่องทางจัดส่ง</span>
                        </a>
                    </li>

                    <li class="menu-title mt-2">รายงาน</li>

                    <li>
                        <a href="report_product_sales">
                            <i class="fa-solid fa-chart-simple"></i>
                            <span> รายงานยอดขาย</span>
                        </a>
                    </li>

                    <li>
                        <a href="report_product_views">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span> รายงานยอดเข้าชม</span>
                        </a>
                    </li>

                    <li class="menu-title mt-2">ตั้งค่า</li>

                    <li>
                        <a href="banner_show">
                            <i class="fa-solid fa-images"></i>
                            <span>แบนเนอร์ / โปรโมท</span>
                        </a>
                    </li>
                    <li>
                        <a href="contact_show">
                            <i class="fa-solid fa-globe"></i>
                            <span>ช่องทางติดต่อ</span>
                        </a>
                    </li>
                    <li>
                        <a href="setting_website_show">
                            <i class="fa-solid fa-gears"></i>
                            <span>ตั้งค่าเว็บไซต์</span>
                        </a>
                    </li>
                    ';
                }
                // Owner
                if (in_array(2, str_split($authority))) {
                    echo '
                    <li class="menu-title mt-2">จัดการบุคล</li>

                    <li>
                        <a href="owner_show">
                            <i class="fa-solid fa-user-tie"></i>
                            <span> เจ้าของร้าน / ผู้บริหาร </span>
                        </a>
                    </li>

                    <li>
                        <a href="admin_show">
                            <i class="fa-solid fa-user-shield"></i>
                            <span> ผู้ดูแลระบบ </span>
                        </a>
                    </li>

                    <li>
                        <a href="employee_show">
                            <i class="fa-solid fa-users-gear"></i>
                            <span> พนักงาน</span>
                        </a>
                    </li>

                    <li class="menu-title mt-2">รายงาน</li>

                    <li>
                        <a href="report_product_sales">
                            <i class="fa-solid fa-chart-simple"></i>
                            <span> รายงานยอดขาย</span>
                        </a>
                    </li>

                    <li>
                        <a href="report_product_views">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span> รายงานยอดเข้าชม</span>
                        </a>
                    </li>
                    ';
                }
                // Admin
                if (in_array(3, str_split($authority))) {
                    echo '
                    <li class="menu-title mt-2">จัดการบุคล</li>
                    <li>
                        <a href="admin_show">
                            <i class="fa-solid fa-user-shield"></i>
                            <span> ผู้ดูแลระบบ </span>
                        </a>
                    </li>

                    <li>
                        <a href="employee_show">
                            <i class="fa-solid fa-users-gear"></i>
                            <span> พนักงาน</span>
                        </a>
                    </li>

                    <li>
                        <a href="member_show">
                            <i class="fa-solid fa-user"></i>
                            <span> สมาชิก</span>
                        </a>
                    </li>

                    <li class="menu-title mt-2">จัดการสินค้า</li>

                    <li>
                        <a href="product_type_show">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <span> ประเภทสินค้า</span>
                        </a>
                    </li>

                    <li>
                        <a href="publisher_show">
                            <i class="fa-solid fa-city"></i>
                            <span> สำนักพิมพ์</span>
                        </a>
                    </li>

                    <li>
                        <a href="author_show">
                            <i class="fa-solid fa-pen-clip"></i>
                            <span> ผู้แต่ง</span>
                        </a>
                    </li>

                    <li>
                        <a href="promotion_show">
                            <i class="fa-solid fa-tag"></i>
                            <span> โปรโมชั่น</span>
                        </a>
                    </li>

                    <li>
                        <a href="product_show">
                            <i class="fa-solid fa-book"></i>
                            <span> สินค้า</span>
                        </a>
                    </li>

                    <li class="menu-title mt-2">จัดการการชำระเงิน</li>

                    <li>
                        <a href="payment_show">
                            <i class="fa-brands fa-paypal"></i>
                            <span> ช่องทางชำระเงิน</span>
                        </a>
                    </li>

                    <li>
                        <a href="shipping_show">
                            <i class="fa-solid fa-truck"></i>
                            <span> ช่องทางจัดส่ง</span>
                        </a>
                    </li>

                    <li class="menu-title mt-2">รายงาน</li>

                    <li>
                        <a href="report_product_sales">
                            <i class="fa-solid fa-chart-simple"></i>
                            <span> รายงานยอดขาย</span>
                        </a>
                    </li>

                    <li>
                        <a href="report_product_views">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span> รายงานยอดเข้าชม</span>
                        </a>
                    </li>

                    <li class="menu-title mt-2">ตั้งค่า</li>
                    <li>
                        <a href="banner_show">
                            <i class="fa-solid fa-images"></i>
                            <span>แบนเนอร์ / โปรโมท</span>
                        </a>
                    </li>
                    <li>
                        <a href="contact_show">
                            <i class="fa-solid fa-globe"></i>
                            <span>ช่องทางติดต่อ</span>
                        </a>
                    </li>
                    <li>
                        <a href="setting_website_show">
                            <i class="fa-solid fa-gears"></i>
                            <span>ตั้งค่าเว็บไซต์</span>
                        </a>
                    </li>
                    ';
                }
                // Accounting
                if (in_array(4, str_split($authority))) {
                    echo '
                    <li class="menu-title mt-2">จัดการการชำระเงิน</li>

                    <li>
                        <a href="payment_show">
                            <i class="fa-brands fa-paypal"></i>
                            <span> ช่องทางชำระเงิน</span>
                        </a>
                    </li>

                    <li>
                        <a href="shipping_show">
                            <i class="fa-solid fa-truck"></i>
                            <span> ช่องทางจัดส่ง</span>
                        </a>
                    </li>

                    <li class="menu-title mt-2">รายงาน</li>

                    <li>
                        <a href="report_product_sales">
                            <i class="fa-solid fa-chart-simple"></i>
                            <span> รายงานยอดขาย</span>
                        </a>
                    </li>

                    <li>
                        <a href="report_product_views">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span> รายงานยอดเข้าชม</span>
                        </a>
                    </li>

                    ';
                }
                // Sale
                if (in_array(5, str_split($authority))) {
                    echo '
                    <li class="menu-title mt-2">จัดการสินค้า</li>

                    <li>
                        <a href="product_type_show">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            <span> ประเภทสินค้า</span>
                        </a>
                    </li>

                    <li>
                        <a href="publisher_show">
                            <i class="fa-solid fa-city"></i>
                            <span> สำนักพิมพ์</span>
                        </a>
                    </li>

                    <li>
                        <a href="author_show">
                            <i class="fa-solid fa-pen-clip"></i>
                            <span> ผู้แต่ง</span>
                        </a>
                    </li>

                    <li>
                        <a href="promotion_show">
                            <i class="fa-solid fa-tag"></i>
                            <span> โปรโมชั่น</span>
                        </a>
                    </li>

                    <li>
                        <a href="product_show">
                            <i class="fa-solid fa-book"></i>
                            <span> สินค้า</span>
                        </a>
                    </li>
                    ';
                }
                // Employee
                if (in_array(6, str_split($authority))) {
                    echo '
                     <li class="menu-title mt-2">ตั้งค่า</li>

                     <li>
                        <a href="banner_show">
                            <i class="fa-solid fa-images"></i>
                            <span>แบนเนอร์ / โปรโมท</span>
                        </a>
                    </li>

                    <li>
                        <a href="contact_show">
                            <i class="fa-solid fa-globe"></i>
                            <span>ช่องทางติดต่อ</span>
                        </a>
                    </li>
                    <li>
                        <a href="setting_website_show">
                            <i class="fa-solid fa-gears"></i>
                            <span>ตั้งค่าเว็บไซต์</span>
                        </a>
                    </li>
                    ';
                }
                ?>
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->
</div>
</div>
<!-- ========== Left Sidebar End ========== -->