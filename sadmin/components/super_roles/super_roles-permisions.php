<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_sadmin_directory_access();
}
$db = new mySqlDB;
$title_heading 	= "Set Permissions";
$button_val 	= "Submit";
$all_id 		= "";
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	if (empty($error)) {
		if (!is_array($all_id)) {
			$all_id = array($all_id);
		}
		$sql_del = "DELETE FROM  super_admin_role_permissions  WHERE role_id 	= '" . $id . "' ";
		$db->query($conn, $sql_del);
		foreach ($all_id as $key => $value) {
			$sql_c1 	= " SELECT * FROM super_admin_role_permissions	WHERE role_id = " . $id . " AND menu_id = " . $key . " ";
			$rs_c1 		= $db->query($conn, $sql_c1);
			$count_c1 	= $db->counter($rs_c1);
			if ($count_c1 == 0) {
				$sql_c_u = "INSERT INTO super_admin_role_permissions (role_id, menu_id, add_date, add_by, add_ip)
							VALUES('" . $id . "', '" . $key . "', '" . $add_date . "', '" . $_SESSION['username_super_admin'] . "', '" . $add_ip . "')";
				$db->query($conn, $sql_c_u);
				$msg['msg_success'] = "Permissions Updated Successfully.";
			}
		}
	}
} else if ($cmd == 'edit' && isset($id)) {
	$sql_ee 	= " SELECT * FROM super_admin_roles WHERE enabled = 1 AND id = '" . $id . "' ";
	$result_ee 	= $db->query($conn, $sql_ee);
	$row_ee 	= $db->fetch($result_ee);
	$role_name	= $row_ee[0]['role_name'];
} ?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="row">
						<div class="col s10 m6 l6">
							<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $title_heading; ?></span></h5>
							<ol class="breadcrumbs mb-0">
								<li class="breadcrumb-item"><?php echo $title_heading; ?>
								</li>
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">List</a>
								</li>
							</ol>
						</div>
						<div class="col s2 m6 l6">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right"
								href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
								List
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy">
				<div class="card-content">
					<?php
					if (isset($error['msg'])) { ?>
						<div class="card-alert card red lighten-5">
							<div class="card-content red-text">
								<p><?php echo $error['msg']; ?></p>
							</div>
							<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
					<?php } else if (isset($msg['msg_success'])) { ?>
						<div class="card-alert card green lighten-5">
							<div class="card-content green-text">
								<p><?php echo $msg['msg_success']; ?></p>
							</div>
							<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
					<?php } ?>
					<h4 class="card-title">Detail Form</h4>
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
						<div class="row">
							<div class="input-field col m6 s12">
								<input readonly id="role_name" type="text" name="role_name" value="<?php if (isset($role_name)) {
																										echo $role_name;
																									} ?>">
								<label for="role_name">Role Name</label>
							</div>
						</div>
						<div class="row">

							<div class="input-field col m12 s12">
								<label>
									<input type="checkbox" id="all_checked" class="filled-in" />
									<span>All super_admin_menus</span>
								</label>
							</div>
							<?php
							$sql2		= " SELECT * FROM super_admin_menus WHERE m_level = 1 AND enabled = 1 ORDER BY sort_order ";
							$result_2 	= $db->query($conn, $sql2);
							$row_2 		= $db->fetch($result_2);
							foreach ($row_2 as $data) {
								$parent_id_level_1 = $data['id'];
								$sql_level1 	= " SELECT * FROM super_admin_role_permissions 
											WHERE role_id = " . $id . " AND menu_id = " . $parent_id_level_1 . " ";
								$rs_level1 		= $db->query($conn, $sql_level1);
								$count_level1 	= $db->counter($rs_level1);
								if ($count_level1 > 0) {
									$checked1 = "checked=''";
								} else {
									$checked1 = "";
								} ?>
								<div class="input-field col m12 s12">
									<label>
										<input type="checkbox" <?php echo $checked1; ?> name="<?php echo "all_id[" . $parent_id_level_1 . "]"; ?>" id="<?php echo $parent_id_level_1; ?>" class="checkbox filled-in" />
										<span><?php echo $data['menu_name']; ?></span>
									</label>
								</div>
								<?php
								$sql3		= " SELECT * FROM super_admin_menus
										WHERE parent_id = '" . $parent_id_level_1 . "'
										AND m_level = 2 AND enabled = 1 ORDER BY sort_order ";
								$result_3 	= $db->query($conn, $sql3);
								$count_3 	= $db->counter($result_3);
								if ($count_3 > 0) {
									$row_3 = $db->fetch($result_3);
									foreach ($row_3 as $data2) {
										$parent_id_level_2 	= $data2['id'];
										$sql_level2 		= " SELECT * FROM super_admin_role_permissions
														WHERE role_id = " . $id . " AND menu_id = " . $parent_id_level_2 . " ";
										$rs_level2 			= $db->query($conn, $sql_level2);
										$count_level2 		= $db->counter($rs_level2);
										if ($count_level2 > 0) {
											$checked2 = "checked=''";
										} else {
											$checked2 = "";
										} ?>
										<div class="input-field col m12 s12" style="margin-left: 20px;">
											<label>
												<input type="checkbox" <?php echo $checked2; ?> name="<?php echo "all_id[" . $parent_id_level_2 . "]"; ?>" id="<?php echo $parent_id_level_2; ?>" class="checkbox <?php echo $parent_id_level_1; ?> filled-in" />
												<span><?php echo $data2['menu_name']; ?></span>
											</label>
										</div>
										<?php
										$sql4		= " SELECT * FROM super_admin_menus
												WHERE parent_id = '" . $parent_id_level_2 . "'
												AND m_level = 3 AND enabled = 1 ORDER BY sort_order ";
										$result4 	= $db->query($conn, $sql4);
										$count4 	= $db->counter($result4);
										if ($count4 > 0) {
											$row4 = $db->fetch($result4);
											foreach ($row4 as $data3) {
												$parent_id_level_3 = $data3['id'];
												$sql_level3 		= " SELECT * FROM super_admin_role_permissions
																WHERE role_id = " . $id . " AND menu_id = " . $parent_id_level_3 . "";
												$rs_level3 			= $db->query($conn, $sql_level3);
												$count_level3 		= $db->counter($rs_level3);
												if ($count_level3 > 0) {
													$checked3 = "checked=''";
												} else {
													$checked3 = "";
												} ?>
												<div class="input-field col m12 s12" style="margin-left: 40px;">
													<label>
														<input type="checkbox" <?php echo $checked3; ?> name="<?php echo "all_id[" . $parent_id_level_3 . "]"; ?>" id="<?php echo $parent_id_level_3; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> filled-in" />
														<span><?php echo $data3['menu_name']; ?></span>
													</label>
												</div>
												<?php
												$sql5		= " SELECT * FROM super_admin_menus
														WHERE parent_id = '" . $parent_id_level_3 . "'
														AND m_level = 4 AND enabled = 1 ORDER BY sort_order ";
												$result5 	= $db->query($conn, $sql5);
												$count5 	= $db->counter($result5);
												if ($count5 > 0) {
													$row5 = $db->fetch($result5);
													foreach ($row5 as $data4) {
														$parent_id_level_4 	= $data4['id'];
														$sql_level4 		= " SELECT * FROM super_admin_role_permissions
																		WHERE role_id = " . $id . " AND menu_id = " . $parent_id_level_4 . " ";
														$rs_level4 			= $db->query($conn, $sql_level4);
														$count_level4 		= $db->counter($rs_level4);
														if ($count_level4 > 0) {
															$checked4 = "checked=''";
														} else {
															$checked4 = "";
														} ?>
														<div class="input-field col m12 s12" style="margin-left: 60px;">
															<label>
																<input type="checkbox" <?php echo $checked4; ?> name="<?php echo "all_id[" . $parent_id_level_4 . "]"; ?>" id="<?php echo $parent_id_level_4; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?> filled-in" />
																<span><?php echo $data4['menu_name']; ?></span>
															</label>
														</div>
							<?php }
												}
											}
										}
									}
								}
							} ?>
						</div> <br>
						<div class="row">
							<div class="row">
								<div class="input-field col m6 s12">
									<button class="btn cyan waves-effect waves-light left" type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
								</div>
							</div>
						</div><br>
					</form>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br>
	<!-- END: Page Main-->