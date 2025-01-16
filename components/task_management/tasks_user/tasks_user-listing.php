<?php
if (!isset($module)) {
	require_once('conf/functions.php');
	disallow_direct_school_directory_access();
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if (isset($cmd) && ($cmd == 'disabled' || $cmd == 'enabled') && access("delete_perm") == 0) {
	$error['msg'] = "You do not have edit permissions.";
} else {
	if (isset($cmd) && $cmd == 'disabled') {
	}
	if (isset($cmd) && $cmd == 'enabled') {
	}
}
if (isset($cmd) && $cmd == 'starred') {
	$sql_c_upd = "UPDATE tasks set  is_starred 			= 1,
									update_date 		= '" . $add_date . "' ,
									update_by 			= '" . $_SESSION['username'] . "' ,
									update_by_user_id 	= '" . $_SESSION['user_id'] . "' ,
									update_ip 			= '" . $add_ip . "',
									update_timezone		= '" . $timezone . "'
				WHERE id = '" . $id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg['msg_success'] = "Record has been removed from Favorite.";
	} else {
		$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
	}
}
if (isset($cmd) && $cmd == 'unstarred') {
	$sql_c_upd = "UPDATE tasks set 	is_starred 			= 0,
									update_date 		= '" . $add_date . "' ,
									update_by 			= '" . $_SESSION['username'] . "' ,
									update_by_user_id 	= '" . $_SESSION['user_id'] . "' ,
									update_ip 			= '" . $add_ip . "',
									update_timezone		= '" . $timezone . "'
				WHERE id = '" . $id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg['msg_success'] = "Record has been Favorite.";
	}
}
$sql_cl		= "	SELECT 	a.*, c.po_no, d.task_type as task_type_name, e.status_name, b.first_name, b.middle_name, b.last_name, 
						b.username, b.email, f.priority_name, g.sub_location_name, g.sub_location_type
				FROM tasks a
				INNER JOIN users b ON b.id = a.task_assign_to_user_id
				LEFT JOIN purchase_orders c ON c.id = a.po_id
				LEFT JOIN task_types d ON d.id = a.task_type 
				LEFT JOIN task_status e ON e.id = a.task_status 
				LEFT JOIN task_priorities f ON f.id = a.priority_id 
				LEFT JOIN warehouse_sub_locations g ON g.id = a.location_id 
				WHERE 1=1 
				AND a.task_assign_to_user_id = '" . $user_id . "'
				AND DATE_FORMAT(a.task_start_date, '%Y%m%d') <= '" . date('Ymd') . "' ";
if (isset($filter_1) && $filter_1 == 'starred') {
	$sql_cl		.= " AND a.is_starred = '1' ";
} else if (isset($filter_1) && $filter_1 == 'unstarred') {
	$sql_cl		.= " AND a.is_starred = '0' ";
} else if (isset($filter_1) && $filter_1 == 'today') {
	$sql_cl		.= " AND a.task_start_date = '" . date('Y-m-d') . "' ";
} else if (isset($filter_1) && ($filter_1 == 'High' || $filter_1 == 'Medium' || $filter_1 == 'Low')) {
	$sql_cl		.= " AND f.priority_name = '" . $filter_1 . "' ";
} else if (isset($filter_1)) {
	$sql_cl		.= " AND e.filter_field = '" . $filter_1 . "' ";
}
$sql_cl		.= " ORDER BY a.enabled DESC,  DATE_FORMAT(a.task_start_date, '%Y%m%d'), a.priority_id DESC "; // echo $sql_cl;
$result_cl	= $db->query($conn, $sql_cl);
$count_cl	= $db->counter($result_cl);
$page_heading 	= "List of Tasks";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<div class="container">
				<!-- Sidebar Area Starts -->
				<div class="todo-overlay"></div>
				<div class="sidebar-left sidebar-fixed">
					<div class="sidebar-left sidebar-fixed">
						<div class="sidebar">
							<div class="sidebar-content">
								<div class="sidebar-header">
									<div class="sidebar-details"></div>
								</div>
								<div id="sidebar-list" class="sidebar-menu list-group position-relative animate fadeLeft ps ps--active-y">
									<div class="sidebar-list-padding app-sidebar" id="todo-sidenav">
										<ul class="todo-list display-grid">
											<li class="active"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" class="text-sub"><i class="material-icons mr-2"> mail_outline </i> All</a>
											</li>
											<li class="sidebar-title">Filters</li>
											<li><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&filter_1=starred") ?>" class="text-sub"><i class="material-icons mr-2"> star_border </i> Starred</a></li>
											<li><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&filter_1=today") ?>" class="text-sub"><i class="material-icons mr-2"> date_range </i> Today</a></li>
											<li class="sidebar-title">Status</li>
											<?php
											$sql1		= "SELECT * FROM task_status WHERE enabled = 1 ORDER BY status_name DESC ";
											$result1	= $db->query($conn, $sql1);
											$count1		= $db->counter($result1);
											if ($count1 > 0) {
												$row1	= $db->fetch($result1);
												foreach ($row1 as $data2) {
													$color_class = status_color_class($data2['status_name']);
													$filter_field = $data2['filter_field']; ?>
													<li><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&filter_1=" . $filter_field) ?>" class="text-sub"><i class="<?php echo $color_class; ?> material-icons small-icons mr-2">fiber_manual_record </i><?php echo $data2['status_name']; ?></a></li>
											<?php
												}
											} ?>
											<li class="sidebar-title">Priority</li>
											<?php
											$sql1		= "SELECT * FROM task_priorities WHERE enabled = 1 ORDER BY priority_name DESC ";
											$result1	= $db->query($conn, $sql1);
											$count1		= $db->counter($result1);
											if ($count1 > 0) {
												$row1	= $db->fetch($result1);
												foreach ($row1 as $data2) {
													$color_class = priority_color_class($data2['priority_name']);
													$filter_field = $data2['priority_name']; ?>
													<li><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&filter_1=" . $filter_field) ?>" class="text-sub"><i class="<?php echo $color_class; ?> material-icons small-icons mr-2">fiber_manual_record </i><?php echo $data2['priority_name']; ?></a></li>
											<?php }
											} ?>
										</ul>
									</div>
									<div class="ps__rail-x" style="left: 0px; bottom: -250px;">
										<div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
									</div>
									<div class="ps__rail-y" style="top: 250px; right: 0px; height: 274px;">
										<div class="ps__thumb-y" tabindex="0" style="top: 123px; height: 134px;"></div>
									</div>
								</div>
								<a href="javascript:void(0)" data-target="todo-sidenav" class="sidenav-trigger hide-on-large-only"><i class="material-icons">menu</i></a>
							</div>
						</div>
					</div>
				</div>
				<!-- Sidebar Area Ends -->

				<!-- Content Area Starts -->
				<div class="app-todo">
					<div class="content-area content-right">
						<div class="app-wrapper">
							<div class="app-search">
								<i class="material-icons mr-2 search-icon">search</i>
								<input type="text" placeholder="Search Contact" class="app-filter" id="todo_filter">
							</div>
							<div class="card card card-default scrollspy border-radius-6 fixed-width">
								<div class="card-content p-0 pb-1">
									<div class="todo-header">
										<div class="header-checkbox"></div>
										<div class="list-content"></div>
										<div class="todo-action">
											<div class="select-action">
												<!-- Dropdown Trigger -->
												<a class='dropdown-trigger grey-text' href='#' data-target='dropdown1'>
													<i class="material-icons">more_vert</i>
												</a>
												<!-- Dropdown Structure -->
												<ul id='dropdown1' class='dropdown-content'>
													<li><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">All</a></li>
													<li><a href=" ?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&filter_1=starred") ?>">Starred</a></li>
													<li><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&filter_1=unstarred") ?>">Unstarred</a></li>
												</ul>
											</div>
										</div>
									</div>
									<ul class=" collection todo-collection">
										<?php
										$i = 0;
										if ($count_cl > 0) {
											$row_cl = $db->fetch($result_cl);
											foreach ($row_cl as $data) {
												$id = $data['id'];
												$po_id = $data['po_id']; ?>
												<li class="collection-item todo-items">

													<div class="list-left">
														<br>
														<?php
														if ($data['is_starred'] == 0) { ?>
															<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=starred&id=" . $id) ?>">
																<i class="material-icons favorite">star_border</i>
															</a>
														<?php } else if ($data['is_starred'] == 1) { ?>
															<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=unstarred&id=" . $id) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																<i class="material-icons favorite  amber-text">star_border</i>
															</a>
														<?php } ?>
													</div>
													<div class="list-content" style="cursor: context-menu;">
														<?php
														$task_type 		= $data['task_type'];
														$location_id 	= $data['location_id'];
														$active_tab		= $active_tabe_cmd = "";
														if ($task_type == '1') {
															$active_tab = "tab5";
															$active_tabe_cmd = "cmd5";
														}
														if ($task_type == '2') {
															$active_tab = "tab6";
															$active_tabe_cmd = "cmd6";
														}
														if ($task_type == '5') {
															$active_tab = "tab2";
															$active_tabe_cmd = "tab2";
														} ?>
														<div class="list-title-area">
															<div class="list-title">
																<?php
																if ($task_type == 1 || $task_type == 2) { ?>
																	<a href="?string=<?php echo encrypt("module=purchase_orders&module_id=10&page=profile&cmd=edit&" . $active_tabe_cmd . "=add&active_tab=" . $active_tab . "&id=" . $po_id) ?>">
																	<?php
																}
																if ($task_type == 5) { ?>
																		<a href="?string=<?php echo encrypt("module=process_inventory&module_id=35&page=add&cmd=edit&cmd2=add&id=" . $location_id) ?>">
																		<?php
																	}
																	echo $data['task_name'];
																	if ($task_type == 1 || $task_type == 2 || $task_type == 5) { ?>
																		</a>
																	<?php } ?>
																	<span class="badge grey lighten-2">
																		<?php
																		$color_class = priority_color_class($data['priority_name']); ?>
																		<i class="<?= $color_class; ?> material-icons small-icons mr-2">fiber_manual_record </i>
																		<?= $data['priority_name']; ?>
																	</span>
															</div>
															<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&id=" . $id) ?>">
																<span class="badge grey lighten-2">
																	<?php
																	$color_class = status_color_class($data['status_name']); ?>
																	<i class="<?= $color_class; ?> material-icons small-icons mr-2">fiber_manual_record </i>
																	<?= $data['status_name']; ?>
																</span>
															</a>
														</div>
														<div class="list-desc">
															<?php
															if ($task_type == 1 || $task_type == 2) { ?>
																<a href="?string=<?php echo encrypt("module=purchase_orders&module_id=10&page=profile&cmd=edit&" . $active_tabe_cmd . "=add&active_tab=" . $active_tab . "&id=" . $po_id) ?>">
																<?php
															}
															if ($task_type == 5) { ?>
																	<a href="?string=<?php echo encrypt("module=inventory_processing&module_id=10&page=profile&cmd=edit&" . $active_tabe_cmd . "=add&active_tab=" . $active_tab . "&id=" . $po_id) ?>">
																	<?php
																}
																echo $data['task_desc'];

																if ($task_type == 1 || $task_type == 2 || $task_type == 5) { ?>
																	</a>
																<?php } ?>
														</div>
														<div class="list-desc">
															<b>Assign To: </b><?= $data['first_name']; ?> <?= $data['middle_name']; ?> <?= $data['last_name']; ?>
															<?php
															echo ", Purpose: " . $data['task_type_name'];
															?>
														</div>
														<div>
															<?php
															if ($data['po_id'] > '0') {
																echo "PO #: " . $data['po_id'] . "";
															} ?>
														</div>
														<div>
															<?php
															if ($data['location_id'] > '0') {
																echo "Location / Bin: " . $data['sub_location_name'];
																if ($data['sub_location_type'] != "") {
																	echo " (" . $data['sub_location_type'] . ")";
																}
															} ?>
														</div>

													</div>
													<div class="list-right">
														<div class="list-date">Started: <?= dateformat2($data['task_start_date']); ?> </div>
													</div>
												</li>
											<?php }
										} else { ?>
											<li class="collection-item todo-items">
												<div class="list-left">
													<h6 class="center-align font-weight-500">No Results Found</h6>
												</div>
											</li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Content Area Ends -->
				<!-- Add new todo popup -->
				<div style="bottom: 54px; right: 19px;" class="fixed-action-btn direction-top">
					<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=add") ?>" class="btn-floating btn-large primary-text gradient-shadow todo-sidebar-trigger">
						<i class="material-icons">note_add</i>
					</a>
				</div>
				<!-- Add new todo popup Ends-->
			</div>
			<div class="content-overlay"></div>
		</div>
	</div>
</div>
<!-- END: Page Main-->