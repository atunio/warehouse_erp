<?php
$active_super_admin_menus[] = "";
if (isset($module) && $module != "") {
	$sql_open_active		= " SELECT a.* FROM super_admin_menus a WHERE a.folder_name =  '" . $module . "'";
	$result_open_active 	= $db->query($conn, $sql_open_active);
	$count_open_active 		= $db->counter($result_open_active);
	if ($count_open_active > 0) {
		$row_open_active 	= $db->fetch($result_open_active);
		$parent_id 			= $row_open_active[0]['parent_id'];
		if ($parent_id > 0) {
			$sql_open_active2		= " SELECT a.* FROM super_admin_menus a WHERE a.id =  '" . $parent_id . "'";
			$result_open_active2 	= $db->query($conn, $sql_open_active2);
			$count_open_active2 	= $db->counter($result_open_active2);
			if ($count_open_active2 > 0) {
				$row_open_active2 		= $db->fetch($result_open_active2);
				$parent_id2 			= $row_open_active2[0]['parent_id'];
				$active_super_admin_menus[] 		= $row_open_active2[0]['id'];
				if ($parent_id2 > 0) {
					$sql_open_active3		= " SELECT a.* FROM super_admin_menus a WHERE a.id =  '" . $parent_id2 . "'";
					$result_open_active3 	= $db->query($conn, $sql_open_active3);
					$count_open_active3 	= $db->counter($result_open_active3);
					if ($count_open_active3 > 0) {
						$row_open_active3 		= $db->fetch($result_open_active3);
						$parent_id3 			= $row_open_active3[0]['parent_id'];
						$active_super_admin_menus[] 		= $row_open_active3[0]['id'];
						if ($parent_id3 > 0) {
							$sql_open_active4		= " SELECT a.* FROM super_admin_menus a WHERE a.id =  '" . $parent_id3 . "'";
							$result_open_active4 	= $db->query($conn, $sql_open_active4);
							$count_open_active4 	= $db->counter($result_open_active4);
							if ($count_open_active4 > 0) {
								$row_open_active4 		= $db->fetch($result_open_active4);
								$parent_id4				= $row_open_active4[0]['parent_id'];
								$active_super_admin_menus[] 		= $row_open_active4[0]['id'];
							}
						}
					}
				}
			}
		}
	}
	//var_dump($active_super_admin_menus);
} ?>
<aside class="<?php echo $nav_layout; ?>">
	<div class="brand-sidebar">
		<h1 class="logo-wrapper">
			<a class="brand-logo darken-1" href="home">
				<img class="hide-on-med-and-down" src="<?php echo $directory_path; ?>/app-assets/images/logo/materialize-logo-color.png" alt="materialize logo" />
				<img class="show-on-medium-and-down hide-on-med-and-up" src="<?php echo $directory_path; ?>/app-assets/images/logo/materialize-logo.png" alt="materialize logo" />
				<span class="logo-text hide-on-med-and-down">SuperAdmin</span>
			</a>
			<a class="navbar-toggler" href="#"><i class="material-icons"><?php echo $nav_check; ?></i></a>
		</h1>
	</div>
	<ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
		<li class="navigation-header"><a class="navigation-header-text">Options</a><i class="navigation-header-icon material-icons">more_horiz</i></li>
		<li class="bold">
			<a class="waves-effect waves-cyan" href="../home">
				<i class="material-icons">home</i>
				<span class="menu-title" data-i18n="Authentication">Back To System</span>
			</a>
		</li>
		<?php
		$sql2_menu		= " SELECT * FROM super_admin_menus WHERE m_level = 1
						AND enabled = 1 AND display_side_bar = 1 
						ORDER BY sort_order "; //echo $sql2_menu;die;
		$result_2_menu 	= $db->query($conn, $sql2_menu);
		$row_2_menu		= $db->fetch($result_2_menu);
		foreach ($row_2_menu as $data_menu) {
			$parent_id_level_1_menu = $data_menu['id'];

			$folder_name 	= $data_menu['folder_name'];
			$default_page 	= $data_menu['default_page'];
			$icon_name 		= $data_menu['icon_name'];
			$menu_name 		= $data_menu['menu_name'];
			$check_menu_permissions = check_menu_permissions_super_admin($db, $conn, $_SESSION['user_id_super_admin'], $parent_id_level_1_menu);
			if ($check_menu_permissions > 0) {
				$check_menu_child = check_menu_child_super_admin($db, $conn, $parent_id_level_1_menu, 2);
				if ($check_menu_child == 0) { ?>
					<li class="bold <?php if (isset($module) && $module == $folder_name) {
										echo "active";
									} ?> ">
						<a class="waves-effect waves-cyan <?php if (isset($module) && $module == $folder_name) {
																echo "active";
															} ?> " href="?string=<?php echo encrypt("module=" . $folder_name . "&page=" . $default_page . "") ?>">
							<i class="material-icons"><?php echo $icon_name; ?></i>
							<span class="menu-title" data-i18n="Authentication"><?php echo $menu_name; ?></span>
						</a>
					</li>
				<?php } else { ?>
					<li class="bold <?php if (in_array($parent_id_level_1_menu, $active_super_admin_menus)) {
										echo "active open";
									} ?>">
						<a class="collapsible-header waves-effect waves-cyan" href="JavaScript:void(0)">
							<i class="material-icons"><?php echo $icon_name; ?></i>
							<span class="menu-title" data-i18n="Authentication"><?php echo $menu_name; ?></span>
						</a>
						<div class="collapsible-body">
							<ul class="collapsible collapsible-sub" data-collapsible="accordion">
								<?php
								$sql3_menu		= " SELECT * FROM super_admin_menus
											WHERE parent_id = '" . $parent_id_level_1_menu . "'
											AND m_level = 2 AND enabled = 1
											AND display_side_bar = 1 ORDER BY sort_order ";
								$result_3_menu 	= $db->query($conn, $sql3_menu);
								$count_3_menu 	= $db->counter($result_3_menu);
								if ($count_3_menu > 0) {
									$row_3_menu = $db->fetch($result_3_menu);
									foreach ($row_3_menu as $data2_menu) {
										$parent_id_level_2_menu 	= $data2_menu['id'];
										$folder_name 				= $data2_menu['folder_name'];
										$default_page 				= $data2_menu['default_page'];
										$icon_name 					= $data2_menu['icon_name'];
										$menu_name 					= $data2_menu['menu_name'];

										$check_menu_permissions = check_menu_permissions_super_admin($db, $conn, $_SESSION['user_id_super_admin'], $parent_id_level_2_menu);
										if ($check_menu_permissions > 0) {
											$check_menu_child = check_menu_child_super_admin($db, $conn, $parent_id_level_2_menu, 3);
											if ($check_menu_child == 0) { ?>
												<li class="<?php if (isset($module) && $module == $folder_name) {
																echo "active";
															} ?>">
													<a href="?string=<?php echo encrypt("module=" . $folder_name . "&page=" . $default_page . "") ?>" class="<?php if (isset($module) && $module == $folder_name) {
																																									echo "active";
																																								} ?>">
														<i class="material-icons"><?php echo $icon_name; ?></i>
														<span data-i18n="Second level"><?php echo $menu_name; ?></span>
													</a>
												</li>
											<?php } else { ?>
												<li <?php if (in_array($parent_id_level_2_menu, $active_super_admin_menus)) {
														echo "active open";
													} ?>>
													<a class="collapsible-header waves-effect waves-cyan" href="JavaScript:void(0)">
														<i class="material-icons"><?php echo $icon_name; ?></i>
														<span data-i18n="Vertical"><?php echo $menu_name; ?></span>
													</a>
													<div class="collapsible-body">
														<ul class="collapsible" data-collapsible="accordion">
															<?php
															$sql4_menu		= " SELECT * FROM super_admin_menus
																	WHERE parent_id = '" . $parent_id_level_2_menu . "'
																	AND m_level = 3 AND enabled = 1
																	AND display_side_bar = 1
																	ORDER BY sort_order ";
															$result_4_menu 	= $db->query($conn, $sql4_menu);
															$count_4_menu 	= $db->counter($result_4_menu);
															if ($count_4_menu > 0) {
																$row_4_menu = $db->fetch($result_4_menu);
																foreach ($row_4_menu as $data3_menu) {
																	$parent_id_level_3_menu 	= $data3_menu['id'];

																	$folder_name 				= $data3_menu['folder_name'];
																	$default_page 				= $data3_menu['default_page'];
																	$icon_name 					= $data3_menu['icon_name'];
																	$menu_name 					= $data3_menu['menu_name'];
																	$check_menu_permissions = check_menu_permissions_super_admin($db, $conn, $_SESSION['user_id_super_admin'], $parent_id_level_3_menu);
																	if ($check_menu_permissions > 0) {
																		$check_menu_child = check_menu_child_super_admin($db, $conn, $parent_id_level_3_menu, 4);
																		//if($check_menu_child == 0){ 
															?>
																		<li class="<?php if (isset($module) && $module == $folder_name) {
																						echo "active";
																					} ?>">
																			<a href="?string=<?php echo encrypt("module=" . $folder_name . "&page=" . $default_page . "") ?>" class="<?php if (isset($module) && $module == $folder_name) {
																																															echo "active";
																																														} ?>">
																				<i class="material-icons"><?php echo $icon_name; ?></i>
																				<span data-i18n="Third level"><?php echo $menu_name; ?></span>
																			</a>
																		</li>
															<?php //}
																	}
																}
															} ?>
														</ul>
													</div>
												</li>
								<?php }
										}
									}
								} ?>
							</ul>
						</div>
					</li>
		<?php }
			}
		} ?>

	</ul>
	<div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>