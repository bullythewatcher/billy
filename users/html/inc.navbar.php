<div class="iq-top-navbar">
    <div class="iq-navbar-custom">
        <nav class="navbar navbar-expand-lg navbar-light p-0">
            <div class="iq-navbar-logo d-flex align-items-center justify-content-between">
                <i class="ri-menu-line wrapper-menu"></i>
                <a href="<?php echo get_server()."/users/dashboard";?>" class="header-logo">
                    <img src="<?php echo get_server()."/assets/images/users/".md5($user_data['user_email']).".png";?>" class="img-fluid" alt="logo">
                </a>
            </div>
            <div class="iq-search-bar-header device-search">

            </div>
            <div class="d-flex align-items-center">
                <ul class="navbar-nav ml-auto navbar-list align-items-center">
                    <li class="nav-item nav-icon search-content">
                        <a href="#" class="search-toggle rounded" id="dropdownSearch" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            <i class="las la-search bg-light font-size-20 iq-card-icon-small"></i>
                        </a>
                        <div class="iq-search-bar iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownSearch">
                            <form action="#" class="searchbox device">
                                <div class="form-group mb-0 position-relative">
                                    <input type="text" class="text search-input font-size-12 bg-light"
                                           placeholder="type here to search...">
                                    <a href="#" class="search-link"><i class="las la-search"></i></a>
                                </div>
                            </form>
                        </div>
                    </li>
                    <!--<li class="nav-item nav-icon dropdown">
                        <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="las la-envelope bg-light font-size-20 iq-card-icon-small"></i>
                        </a>
                        <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton2">
                            <div class="card shadow-none m-0">
                                <div class="card-body p-0 ">
                                    <div class="cust-title p-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0">All Messages</h5>
                                            <a class="badge badge-primary badge-card" href="#">3</a>
                                        </div>
                                    </div>
                                    <div class="px-3 pt-0 pb-0 sub-card">
                                        <a href="#" class="iq-sub-card">
                                            <div class="media align-items-center cust-card py-3 border-bottom">
                                                <div class="">
                                                    <img class="avatar-50 rounded-small"
                                                        src="assets/images/user/01.jpg" alt="01">
                                                </div>
                                                <div class="media-body ml-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h6 class="mb-0">Emma Watson</h6>
                                                        <small class="text-dark"><b>12 : 47 pm</b></small>
                                                    </div>
                                                    <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="iq-sub-card">
                                            <div class="media align-items-center cust-card py-3 border-bottom">
                                                <div class="">
                                                    <img class="avatar-50 rounded-small"
                                                        src="assets/images/user/02.jpg" alt="02">
                                                </div>
                                                <div class="media-body ml-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h6 class="mb-0">Ashlynn Franci</h6>
                                                        <small class="text-dark"><b>11 : 30 pm</b></small>
                                                    </div>
                                                    <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="iq-sub-card">
                                            <div class="media align-items-center cust-card py-3">
                                                <div class="">
                                                    <img class="avatar-50 rounded-small"
                                                        src="assets/images/user/03.jpg" alt="03">
                                                </div>
                                                <div class="media-body ml-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h6 class="mb-0">Kianna Carder</h6>
                                                        <small class="text-dark"><b>11 : 21 pm</b></small>
                                                    </div>
                                                    <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a class="right-ic btn btn-primary btn-block position-relative p-2" href="#"
                                        role="button">
                                        View All
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>-->
                    <div class="flex align-items-center list-user-action">
                        <a class="btn btn-sm bg-success" data-toggle="tooltip" data-placement="top" title="" data-original-title="Configurar WhatsApp" href="<?php echo get_server()."/users/whatsapp-numbers";?>">
                            <i class="lab la-whatsapp font-size-20"></i>
                        </a>
                        <a class="btn btn-sm bg-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="Configurar Twitch" href="<?php echo get_server()."/users/twitch";?>">
                            <i class="lab la-twitch font-size-20"></i>
                        </a>
                        <a class="btn btn-sm bg-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Ver Reportes" href="<?php echo get_server()."/users/reports";?>">
                            <i class="las la-bell font-size-20"></i>
                        </a>
                        <a class="btn btn-sm bg-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Cerrar Sesión" href="<?php echo get_server()."/users/logout";?>">
                            <i class="las la-sign-out-alt font-size-20"></i>
                        </a>
                    </div>

                    <li class="nav-item nav-icon dropdown">
                        <!--<a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="las la-bell bg-light font-size-20 iq-card-icon-small"></i>
                        </a>-->
                        <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div class="card shadow-none m-0">
                                <div class="card-body p-0 ">
                                    <div class="cust-title p-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5 class="mb-0">Notifications</h5>
                                            <a class="badge badge-primary badge-card" href="#">3</a>
                                        </div>
                                    </div>
                                    <div class="px-3 pt-0 pb-0 sub-card">
                                        <a href="#" class="iq-sub-card">
                                            <div class="media align-items-center cust-card py-3 border-bottom">
                                                <div class="">
                                                    <img class="avatar-50 rounded-small"
                                                         src="<?php echo get_server()."/assets/images/users/".md5($user_data['user_email']).".png";?>" alt="01">
                                                </div>
                                                <div class="media-body ml-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h6 class="mb-0">Emma Watson</h6>
                                                        <small class="text-dark"><b>12 : 47 pm</b></small>
                                                    </div>
                                                    <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="iq-sub-card">
                                            <div class="media align-items-center cust-card py-3 border-bottom">
                                                <div class="">
                                                    <img class="avatar-50 rounded-small"
                                                         src="assets/images/user/02.jpg" alt="02">
                                                </div>
                                                <div class="media-body ml-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h6 class="mb-0">Ashlynn Franci</h6>
                                                        <small class="text-dark"><b>11 : 30 pm</b></small>
                                                    </div>
                                                    <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="iq-sub-card">
                                            <div class="media align-items-center cust-card py-3">
                                                <div class="">
                                                    <img class="avatar-50 rounded-small"
                                                         src="assets/images/user/03.jpg" alt="03">
                                                </div>
                                                <div class="media-body ml-3">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h6 class="mb-0">Kianna Carder</h6>
                                                        <small class="text-dark"><b>11 : 21 pm</b></small>
                                                    </div>
                                                    <small class="mb-0">Lorem ipsum dolor sit amet</small>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a class="right-ic btn btn-primary btn-block position-relative p-2" href="notification.html"
                                       role="button">
                                        View All
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item nav-icon dropdown caption-content">
                        <a href="#" class="search-toggle dropdown-toggle" id="dropdownMenuButton4"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?php echo get_server()."/assets/images/users/".md5($user_data['user_email']).".png";?>" class="img-fluid rounded" alt="user">
                        </a>
                        <!--
                        <div class="iq-sub-dropdown dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div class="card shadow-none m-0">
                                <div class="card-body p-0 text-center">
                                    <div class="media-body profile-detail text-center">
                                        <img src="assets/images/logo_web.png" alt="profile-bg"
                                             class="profile-bg img-fluid mb-4">
                                        <img src="<?php echo get_server()."/assets/images/users/".md5($user_data['user_email']).".png";?>" alt="profile-img"
                                             class="rounded profile-img img-fluid avatar-70">
                                    </div>
                                    <div class="p-3">
                                        <h5 class="mb-1"><?php echo $user_data['user_email'];?></h5>
                                        <div class="d-flex align-items-center justify-content-center mt-3">
                                            <a href="<?php echo get_server()."/users/logout";?>" class="btn border">Cerrar Sesión</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>

<div class="small-saidbar">
    <div class="iq-sidebar-logo d-flex justify-content-between">
        <a href="index.html">
            <img src="<?php echo get_server()."/assets/images/users/".md5($user_data['user_email']).".png";?>" class="avatar rounded" alt="logo">
        </a>
        <div class="iq-menu-bt-sidebar">
            <div class="iq-menu-bt align-self-center">
                <div class="wrapper-menu open">
                    <div class="main-circle"><i class="ri-close-line"></i></div>
                </div>
            </div>
        </div>
    </div>
    <nav class="iq-sidebar-menu">
        <ul id="iq-sidebar-toggle" class="iq-menu">
            <li class="<?php if ($btw_module == 'dashboard') echo 'active'?>">
                <a href="<?php echo get_server()."/users/dashboard";?>" class="">
                    <i class="las la-home iq-arrow-left"></i><span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li class="<?php if ($btw_module == 'whatsapp-numbers') echo 'active'?>">
                <a href="<?php echo get_server()."/users/whatsapp-numbers";?>" class="" >
                    <i class="lab la-whatsapp-square"></i><span class="menu-text">WhatsApp</span>
                </a>
            </li>
            <!--
            <li class="<?php if ($btw_module == 'whatsapp') echo 'active'?>">
                <a href="<?php echo get_server()."/users/whatsapp";?>" class="" >
                    <i class="lab la-whatsapp"></i><span class="menu-text">WhatsApp</span>
                </a>
            </li>
            -->
            <li class="<?php if ($btw_module == 'twitch') echo 'active'?>">
                <a href="<?php echo get_server()."/users/twitch";?>" class="">
                    <i class="lab la-twitch"></i><span class="menu-text">Twitch</span>
                </a>
            </li>
            <li class="<?php if ($btw_module == 'reports') echo 'active'?>">
                <a href="<?php echo get_server()."/users/reports";?>" class="">
                    <i class="las la-bell"></i><span class="menu-text">Reportes</span>
                </a>
            </li>
            <li class="<?php if ($btw_module == 'logout') echo 'active'?>">
                <a href="<?php echo get_server()."/users/logout";?>" class="">
                    <i class="las la-sign-out-alt"></i><span class="menu-text">Cerrar Sesión</span>
                </a>
            </li>
            <!--
            <li class="">
                <a href="#user" class="collapsed svg-icon" data-toggle="collapse" aria-expanded="false">
                    <i class="las la-user-check iq-arrow-left"></i><span>User Details</span>
                </a>
                <ul id="user" class="iq-submenu collapse" data-parent="#user">
                    <li class="">
                        <a href="../app/user-profile.html">
                            <i class="las la-user"></i><span class="">User Profile</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="../app/user-add.html">
                            <i class="las la-user-plus"></i><span class="">User Add</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="../app/user-list.html">
                            <i class="las la-user-edit"></i><span class="">User List</span>
                        </a>
                    </li>
                </ul>
            </li>
            -->
        </ul>
    </nav>
</div>