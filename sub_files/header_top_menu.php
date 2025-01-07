<header class="page-topbar" id="header">
    <div class="navbar navbar-fixed">
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow">
            <div class="nav-wrapper">
            <ul class="navbar-list right">
					<?php /*?>
						<li class="dropdown-language"><a class="waves-effect waves-block waves-light translation-button" href="#" data-target="translation-dropdown"><span class="flag-icon flag-icon-gb"></span></a></li>
					<?php */ ?>
					<li class="hide-on-med-and-down"><a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);"><i class="material-icons">settings_overscan</i></a></li>
					<li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
					<!-- 
					<li><a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">5</small></i></a></li> 
					-->
					<li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="<?php echo $directory_path; ?>app-assets/images/logo/<?php echo $user_profile_pic; ?>" title="<?php echo $user_full_name; ?>" alt="<?php echo $user_full_name; ?>"><i></i></span></a></li>
					<?php /*?>
					<li><a class="waves-effect waves-block waves-light sidenav-trigger" href="#" data-target="slide-out-right"><i class="material-icons">format_indent_increase</i></a></li>
					<?php */ ?>
				</ul>
				<!-- translation-button-->
				<ul class="dropdown-content" id="translation-dropdown">
					<li class="dropdown-item"><a class="grey-text text-darken-1" href="#!" data-language="en"><i class="flag-icon flag-icon-gb"></i> English</a></li>
					<li class="dropdown-item"><a class="grey-text text-darken-1" href="#!" data-language="fr"><i class="flag-icon flag-icon-fr"></i> French</a></li>
					<li class="dropdown-item"><a class="grey-text text-darken-1" href="#!" data-language="pt"><i class="flag-icon flag-icon-pt"></i> Portuguese</a></li>
					<li class="dropdown-item"><a class="grey-text text-darken-1" href="#!" data-language="de"><i class="flag-icon flag-icon-de"></i> German</a></li>
				</ul>
				<!-- notifications-dropdown-->
				<ul class="dropdown-content" id="notifications-dropdown">
					<li>
						<h6>NOTIFICATIONS<span class="new badge">5</span></h6>
					</li>
					<li class="divider"></li>
					<li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle cyan small">add_shopping_cart</span> A new order has been placed!</a>
						<time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">2 hours ago</time>
					</li>
					<li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle red small">stars</span> Completed the task</a>
						<time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">3 days ago</time>
					</li>
					<li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle teal small">settings</span> Settings updated</a>
						<time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">4 days ago</time>
					</li>
					<li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle deep-orange small">today</span> Director meeting started</a>
						<time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">6 days ago</time>
					</li>
					<li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle amber small">trending_up</span> Generate monthly report</a>
						<time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">1 week ago</time>
					</li>
				</ul>
				<!-- profile-dropdown-->
				<ul class="dropdown-content" id="profile-dropdown">
					<?php
					$sql_d_c 			= "	SELECT a.id FROM sub_users_user_roles a
											INNER JOIN sub_users_role_permissions b ON b.role_id = a.role_id
											WHERE a.user_id = '" . $_SESSION['user_id'] . "'
											AND b.menu_id = 2 ";
					$result_d_c 		= $db->query($conn, $sql_d_c);
					$count_d_c			= $db->counter($result_d_c);
					if ($count_d_c > 0) { ?>
						<li>
							<a class="grey-text text-darken-1" href="?string=<?php echo encrypt("module_id=2&module=user_profile&page=listing") ?>">
								<i class="material-icons">person_outline</i>
								Profile
							</a>
						</li>
						<li class="divider"></li>
					<?php } else if ($_SESSION['user_type'] == 'Admin') { ?>
						<li>
							<a class="grey-text text-darken-1" href="?string=<?php echo encrypt("module_id=2&module=user_profile&page=listing") ?>">
								<i class="material-icons">person_outline</i>
								Profile
							</a>
						</li>
						<li class="divider"></li>
					<?php }
					$sql_d_c 			= "	SELECT a.id 
											FROM sub_users_user_roles a
											INNER JOIN sub_users_role_permissions b ON b.role_id = a.role_id
											WHERE a.user_id = '" . $_SESSION['user_id'] . "'
											AND b.menu_id = 1 ";
					$result_d_c 		= $db->query($conn, $sql_d_c); //echo $sql_d_c;
					$count_d_c			= $db->counter($result_d_c);
					if ($count_d_c > 0) { ?>
						<li>
							<a class="grey-text text-darken-1" href="?string=<?php echo encrypt("module_id=1&module=change_password&page=listing") ?>">
								<i class="material-icons">lock_outline</i> Password
							</a>
						</li>
						<li class="divider"></li>
					<?php } else if ($_SESSION['user_type'] == 'Admin') { ?>
						<li>
							<a class="grey-text text-darken-1" href="?string=<?php echo encrypt("module_id=1&module=change_password&page=listing") ?>">
								<i class="material-icons">lock_outline</i> Password
							</a>
						</li>
						<li class="divider"></li>
					<?php } ?>
					<li><a class="grey-text text-darken-1" href="signout"><i class="material-icons">keyboard_tab</i> Logout</a></li>
				</ul>
            </div>
        </nav> 
        <!-- BEGIN: Horizontal nav start-->
        <nav class="white hide-on-med-and-down" id="horizontal-nav">
            <div class="nav-wrapper">
                <ul class="left hide-on-med-and-down" id="ul-horizontal-nav" data-menu="menu-navigation">
                    <li data-menu="">
                        <a href="home">
                            <i class="material-icons">dashboard</i>
                            <span data-i18n="Home">Home</span>
                        </a>
                    </li>
                    <?php
                    $sitebar_parm2 = $sitebar_parm3 = $sitebar_parm4 = "";
                    $sql2_top_menu  = " SELECT * FROM menus
                                        WHERE m_level 			= 1
                                        AND enabled 			= 1 
                                        AND display_side_bar 	= 1 ";
                    if ($_SESSION["user_type"] == 'Admin') {
                        $sql2_top_menu  .= " AND display_side_bar_admin = 1 ";
                    }
                    $sql2_top_menu		.= " ORDER BY sort_order  "; //echo $sql2_menu;die;
                    $result_2_top_menu 	= $db->query($conn, $sql2_top_menu);
                    $row_2_top_menu		= $db->fetch($result_2_top_menu);
                    foreach ($row_2_top_menu as $data_top_menu) {
                        $parent_id_level_1_top_menu = $data_top_menu['id'];
                        $module_id_sitebar	        = $data_top_menu['id'];
                        $folder_name 		        = $data_top_menu['folder_name'];
                        $default_page 		        = $data_top_menu['default_page'];
                        $icon_name 			        = $data_top_menu['icon_name'];
                        $menu_name 			        = $data_top_menu['menu_name'];
                        $check_menu_permissions = check_menu_permissions($db, $conn, $_SESSION['user_id'], $_SESSION['subscriber_users_id'], $_SESSION['user_type'], $parent_id_level_1_top_menu, $selected_db_name, $sitebar_parm2, $sitebar_parm3);
                        if ($check_menu_permissions > 0) {
                            $check_menu_child = check_menu_child($db, $conn, $parent_id_level_1_top_menu, 2);
                            if ($check_menu_child == 0) {?> 
                                <li data-menu="">
                                    <a href="?string=<?php echo encrypt("module=" . $folder_name . "&module_id=" . $module_id_sitebar . "&page=" . $default_page . "") ?>">
                                        <i class="material-icons"><?php echo $icon_name; ?></i>
                                        <span data-i18n="<?php echo $menu_name; ?>"><?php echo $menu_name; ?></span>
                                    </a>
                                </li>  
                            <?php }
                            else{?> 
                                 <li>
                                    <a class="dropdown-menu" href="Javascript:void(0)" data-target="<?php echo $module_id_sitebar; ?>">
                                        <i class="material-icons"><?php echo $icon_name; ?></i>
                                        <span>
                                            <span class="dropdown-title" data-i18n="<?php echo $menu_name; ?>"><?php echo $menu_name; ?></span>
                                            <i class="material-icons right">keyboard_arrow_down</i>
                                        </span>
                                    </a>
                                    <ul class="dropdown-content dropdown-horizontal-list" id="<?php echo $module_id_sitebar; ?>">
                                        <?php
                                        $sql3_top_menu		= " SELECT * FROM menus
                                                            WHERE parent_id = '" . $parent_id_level_1_top_menu . "'
                                                            AND m_level = 2 AND enabled = 1
                                                            AND display_side_bar = 1";
                                        if ($_SESSION["user_type"] == 'Admin') {
                                            $sql3_top_menu		.= " AND display_side_bar_admin = 1 ";
                                        }
                                        $sql3_top_menu		.= " ORDER BY sort_order  "; //echo $sql3_menu;die;
                                        $result_3_top_menu 	= $db->query($conn, $sql3_top_menu);
                                        $count_3_top_menu 	= $db->counter($result_3_top_menu);
                                        if ($count_3_top_menu > 0) {
                                            $row_3_top_menu = $db->fetch($result_3_top_menu);
                                            foreach ($row_3_top_menu as $data2_top_menu) {
                                                $parent_id_level_2_top_menu 	= $data2_top_menu['id'];
                                                $module_id_sitebar			    = $data2_top_menu['id'];
                                                $folder_name 				    = $data2_top_menu['folder_name'];
                                                $default_page 				    = $data2_top_menu['default_page'];
                                                $icon_name 					    = $data2_top_menu['icon_name'];
                                                $menu_name 					    = $data2_top_menu['menu_name'];

                                                $check_menu_permissions = check_menu_permissions($db, $conn, $_SESSION['user_id'], $_SESSION['subscriber_users_id'], $_SESSION['user_type'], $parent_id_level_2_top_menu, $selected_db_name, $sitebar_parm2, $sitebar_parm3);
                                                if ($check_menu_permissions > 0) {
                                                    $check_menu_child = check_menu_child($db, $conn, $parent_id_level_2_top_menu, 3);
                                                    if ($check_menu_child == 0) { ?>
                                                        <li data-menu="">
                                                            <a href="?string=<?php echo encrypt("module=" . $folder_name . "&module_id=" . $module_id_sitebar . "&page=" . $default_page . "") ?>"><span data-i18n="<?php echo $menu_name; ?>"><?php echo $menu_name; ?></span></a>
                                                        </li>
                                                    <?php 
                                                    } 
                                                    else{?> 
                                                        <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                                                            <a class="dropdownSub-menu" href="Javascript:void(0)" data-target="<?php echo $module_id_sitebar; ?>">
                                                                 <span>
                                                                    <span class="dropdown-title" data-i18n="<?php echo $menu_name; ?>"><?php echo $menu_name; ?></span>
                                                                    <i class="material-icons right">chevron_right</i>
                                                                </span>
                                                            </a>
                                                            <ul class="dropdown-content dropdown-horizontal-list" id="<?php echo $module_id_sitebar; ?>">
                                                                <?php
                                                                $sql4_top_menu		= " SELECT * FROM menus
                                                                                        WHERE parent_id = '" . $parent_id_level_2_top_menu . "'
                                                                                        AND m_level = 3 AND enabled = 1
                                                                                        AND display_side_bar = 1";
                                                                if ($_SESSION["user_type"] == 'Admin') {
                                                                    $sql4_top_menu		.= " AND display_side_bar_admin = 1 ";
                                                                }
                                                                $sql4_top_menu		.= " ORDER BY sort_order  "; //echo $sql3_menu;die;
                                                                $result_4_top_menu 	= $db->query($conn, $sql4_top_menu);
                                                                $count_4_top_menu 	= $db->counter($result_4_top_menu);
                                                                if ($count_4_top_menu > 0) {
                                                                    $row_4_top_menu = $db->fetch($result_4_top_menu);
                                                                    foreach ($row_4_top_menu as $data3_top_menu) {
                                                                        $parent_id_level_3_top_menu = $data3_top_menu['id'];
                                                                        $module_id_sitebar			= $data3_top_menu['id'];
                                                                        $folder_name 				= $data3_top_menu['folder_name'];
                                                                        $default_page 				= $data3_top_menu['default_page'];
                                                                        $icon_name 					= $data3_top_menu['icon_name'];
                                                                        $menu_name 					= $data3_top_menu['menu_name'];

                                                                        $check_menu_permissions = check_menu_permissions($db, $conn, $_SESSION['user_id'], $_SESSION['subscriber_users_id'], $_SESSION['user_type'], $parent_id_level_3_top_menu, $selected_db_name, $sitebar_parm2, $sitebar_parm3);
                                                                        if ($check_menu_permissions > 0) {
                                                                            $check_menu_child = check_menu_child($db, $conn, $parent_id_level_3_top_menu, 4);
                                                                            if ($check_menu_child == 0) { ?> 
                                                                             </li>
                                                                                <li data-menu="">
                                                                                    <a href="?string=<?php echo encrypt("module=" . $folder_name . "&module_id=" . $module_id_sitebar . "&page=" . $default_page . "") ?>"><span data-i18n="<?php echo $menu_name; ?>"><?php echo $menu_name; ?></span></a>
                                                                                </li>
                                                                            <?php 
                                                                            }
                                                                            /*
                                                                            else{?> 
                                                                                <li class="dropdown dropdown-submenu" data-menu="dropdown-submenu">
                                                                                    <a class="dropdownSub-menu" href="Javascript:void(0)" data-target="<?php echo $module_id_sitebar; ?>">
                                                                                        <span>
                                                                                            <span class="dropdown-title" data-i18n="<?php echo $menu_name; ?>"><?php echo $menu_name; ?></span>
                                                                                            <i class="material-icons right">chevron_right</i>
                                                                                        </span>
                                                                                    </a>
                                                                                    <ul class="dropdown-content dropdown-horizontal-list" id="<?php echo $module_id_sitebar; ?>">
                                                                                        <?php
                                                                                        $sql5_top_menu		= " SELECT * FROM menus
                                                                                                                WHERE parent_id = '" . $parent_id_level_3_top_menu . "'
                                                                                                                AND m_level = 4 AND enabled = 1
                                                                                                                AND display_side_bar = 1";
                                                                                        if ($_SESSION["user_type"] == 'Admin') {
                                                                                            $sql5_top_menu		.= " AND display_side_bar_admin = 1 ";
                                                                                        }
                                                                                        $sql5_top_menu		.= " ORDER BY sort_order  "; //echo $sql3_menu;die;
                                                                                        $result_5_top_menu 	= $db->query($conn, $sql5_top_menu);
                                                                                        $count_5_top_menu 	= $db->counter($result_5_top_menu);
                                                                                        if ($count_5_top_menu > 0) {
                                                                                            $row_5_top_menu = $db->fetch($result_5_top_menu);
                                                                                            foreach ($row_5_top_menu as $data4_top_menu) {
                                                                                                $parent_id_level_4_top_menu = $data4_top_menu['id'];
                                                                                                $module_id_sitebar			= $data4_top_menu['id'];
                                                                                                $folder_name 				= $data4_top_menu['folder_name'];
                                                                                                $default_page 				= $data4_top_menu['default_page'];
                                                                                                $icon_name 					= $data4_top_menu['icon_name'];
                                                                                                $menu_name 					= $data4_top_menu['menu_name'];

                                                                                                $check_menu_permissions = check_menu_permissions($db, $conn, $_SESSION['user_id'], $_SESSION['subscriber_users_id'], $_SESSION['user_type'], $parent_id_level_4_top_menu, $selected_db_name, $sitebar_parm2, $sitebar_parm3);
                                                                                                if ($check_menu_permissions > 0) {
                                                                                                    $check_menu_child = check_menu_child($db, $conn, $parent_id_level_4_top_menu, 5);
                                                                                                    //if ($check_menu_child == 0) { ?>
                                                                                                    </li>
                                                                                                        <li data-menu="">
                                                                                                            <a href="?string=<?php echo encrypt("module=" . $folder_name . "&module_id=" . $module_id_sitebar . "&page=" . $default_page . "") ?>"><span data-i18n="<?php echo $menu_name; ?>"><?php echo $menu_name; ?></span></a>
                                                                                                        </li>
                                                                                                    <?php 
                                                                                                    //}
                                                                                                }
                                                                                            }
                                                                                        }?>  
                                                                                    </ul>
                                                                                </li>
                                                                            <?php
                                                                            } 
                                                                            */
                                                                        }
                                                                    }
                                                                }?>  
                                                            </ul>
                                                        </li>
                                                    <?php
                                                    } 
                                                }
                                            }
                                        }?>  
                                    </ul>
                                </li>
                            <?php
                            }
                        }
                    }?> 
                </ul>
            </div>
            <!-- END: Horizontal nav start-->
        </nav>
    </div>
</header>