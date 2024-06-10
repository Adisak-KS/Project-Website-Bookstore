 <!--  ========== topbar Start ========== -->
 <div class="navbar-custom">
     <ul class="list-unstyled topnav-menu float-end mb-0">

         <li class="dropdown notification-list topbar-dropdown">
             <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                 <img src="../uploads/img_employees/default.png" alt="user-image" class="rounded-circle">
                 <span class="pro-user-name ms-1">
                     Nowak
                     <small><i class="fa-solid fa-chevron-down"></i></small>
                 </span>
             </a>
             <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                 <!-- item-->
                 <div class="dropdown-header noti-title">
                     <h6 class="text-overflow m-0">ยินดีต้อนรับ !</h6>
                 </div>

                 <!-- item-->
                 <a href="contacts-profile.html" class="dropdown-item notify-item">
                     <i class="fa-solid fa-user-gear"></i>
                     <span>บัญชีของฉัน</span>
                 </a>

                 <!-- item-->
                 <a href="auth-lock-screen.html" class="dropdown-item notify-item">
                     <i class="fa-solid fa-lock"></i>
                     <span>ล็อคหน้าจอ</span>
                 </a>

                 <div class="dropdown-divider"></div>

                 <!-- item-->
                 <a href="auth-logout.html" class="dropdown-item notify-item">
                     <i class="fa-solid fa-arrow-right-from-bracket"></i>
                     <span>ออกจากระบบ</span>
                 </a>

             </div>
         </li>

         <li class="dropdown notification-list">
             <a href="javascript:void(0);" class="nav-link right-bar-toggle waves-effect waves-light">
                 <i class="fa-solid fa-gear"></i>
             </a>
         </li>

     </ul>

     <!-- LOGO -->
     <div class="logo-box">
         <a href="index.html" class="logo logo-light text-center">
             <span class="logo-sm">
                 <img src="assets/images/logo-sm.png" alt="" height="22">
             </span>
             <span class="logo-lg">
                 <img src="assets/images/logo-light.png" alt="" height="16">
             </span>
         </a>
         <a href="index.html" class="logo logo-dark text-center">
             <span class="logo-sm">
                 <img src="assets/images/logo-sm.png" alt="" height="22">
             </span>
             <span class="logo-lg">
                 <img src="assets/images/logo-dark.png" alt="" height="16">
             </span>
         </a>
     </div>

     <ul class="list-unstyled topnav-menu topnav-menu-left mb-0">
         <li>
             <button class="button-menu-mobile disable-btn waves-effect">
                 <i class="fa-solid fa-bars"></i>
             </button>
         </li>

         <li>
             <h4 class="page-title-main"><?php echo $titlePage ?></h4>
         </li>

     </ul>

     <div class="clearfix"></div>

 </div>
 <!--  ========== topbar End ========== -->