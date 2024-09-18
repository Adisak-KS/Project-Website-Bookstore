 <!-- footer-area-start -->
 <footer>
     <!-- footer-top-start -->
     <div class="footer-top">
         <div class="container">
             <div class="row">
                 <div class="col-lg-12">
                     <div class="footer-top-menu bb-2">
                         <nav>
                             <ul>
                                 <li><a href="index">หน้าแรก</a></li>
                                 <li><a href="contact">ติดต่อเรา</a></li>
                               
                             </ul>
                         </nav>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- footer-top-start -->
     <!-- footer-mid-start -->
     <div class="footer-mid ptb-50">
         <div class="container">
             <div class="row">
                 <div class="col-lg-8 col-12">
                     <div class="row">
                         <div class="col-lg-4 col-md-4 col-12">
                             <div class="single-footer br-2 xs-mb">
                                 <div class="footer-title mb-20">
                                     <h3>บริการ</h3>
                                 </div>
                                 <div class="footer-mid-menu">
                                     <ul>
                                         <li><a href="products_show">สินค้าทั้งหมด</a></li>
                                         <li><a href="products_preorder_show">สินค้าพรีออเดอร์ </a></li>
                                         <li><a href="product_request_form">ตามหาหนังสือ</a></li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-4 col-12">
                             <div class="single-footer br-2 xs-mb">
                                 <div class="footer-title mb-20">
                                     <h3>ทางลัด</h3>
                                 </div>
                                 <div class="footer-mid-menu">
                                     <ul>
                                         <li><a href="login_form">เข้าสู่ระบบ</a></li>
                                         <li><a href="register_form">สมัครสมาชิก</a></li>
                                         <li><a href="cart">รถเข็น</a></li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                         <div class="col-lg-4 col-md-4 col-12">
                             <div class="single-footer br-2 xs-mb">
                                 <div class="footer-title mb-20">
                                     <h3>บัญชีของคุณ</h3>
                                 </div>
                                 <div class="footer-mid-menu">
                                     <ul>
                                         <li><a href="account_show">ข้อมูลส่วนตัว</a></li>
                                         <li><a href="account_address">ที่อยู่</a></li>
                                         <li><a href="account_order_history">รายการสั่งซื้อ</a></li>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-4 col-12">

                     <?php
                        $ct_ids_to_check = [4, 5, 6];
                        $found_contacts = array_filter($contacts, function ($contact) use ($ct_ids_to_check) {
                            return in_array($contact['ct_id'], $ct_ids_to_check);
                        });
                        ?>
                     <?php if (!empty($found_contacts)) { ?>
                         <div class="single-footer mrg-sm">
                             <div class="footer-title mb-20">
                                 <h3>ข้อมูลร้าน</h3>
                             </div>
                             <div class="footer-contact">

                                 <?php
                                    $details = [
                                        'address' => '',
                                        'phone' => '',
                                        'email' => ''
                                    ];

                                    foreach ($contacts as $contact) {
                                        switch ($contact['ct_id']) {
                                            case 6:
                                                $details['address'] = $contact['ct_detail'];
                                                break;
                                            case 5:
                                                $details['phone'] = $contact['ct_detail'];
                                                break;
                                            case 4:
                                                $details['email'] = $contact['ct_detail'];
                                                break;
                                        }
                                    }

                                    if ($details['address']) {
                                        echo '<p class="address">ที่อยู่ : ' . $details['address'] . '</p>';
                                    }
                                    if ($details['phone']) {
                                        echo '<p class="address">ติดต่อ : ' . $details['phone'] . '</p>';
                                    }
                                    if ($details['email']) {
                                        echo '<p class="address">อีเมล : ' . $details['email'] . '</p>';
                                    }
                                    ?>

                             </div>
                         </div>
                     <?php } ?>
                 </div>
             </div>
         </div>
     </div>
     <!-- footer-mid-end -->
     <!-- footer-bottom-start -->
     <div class="footer-bottom">
         <div class="container">
             <div class="row bt-2">
                 <div class="col-lg-6 col-md-6 col-12">
                     <div class="copy-right-area">
                         <p>&copy; 2022 <strong> Koparion </strong> Mede with ❤️ by <a href="https://hasthemes.com/" target="_blank"><strong>HasThemes</strong></a></p>
                     </div>
                 </div>
                 <div class="col-lg-6 col-md-6 col-12">
                     <div class="payment-img text-end">
                         <a href="#"><img src="img/1.png" alt="payment" /></a>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- footer-bottom-end -->
 </footer>
 <!-- footer-area-end -->