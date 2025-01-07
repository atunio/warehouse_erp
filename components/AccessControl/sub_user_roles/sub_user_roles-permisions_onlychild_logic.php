<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db = new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 		= $_SESSION["user_id"];
$title_heading 			= "Set Sub User Permissions";
$button_val 			= "Submit";
$all_id = "";
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	if (access("add_perm") == 0) {
		$error['msg'] = "You do not have add permissions.";
	} else {
		if (empty($error)) {
			if (!is_array($all_id)) {
				$all_id = array($all_id);
			}
			$sql_del = "DELETE FROM  sub_users_role_permissions  WHERE role_id = '" . $id . "'  ";
			$db->query($conn, $sql_del);
			foreach ($all_id as $key => $value) {
				if ($key > 0) {
					$add_perm = $add2_perm = $edit_perm = $edit2_perm = $delete_perm = $view_perm = $print_perm = 0;
					$innerArray = ${"perm_" . $key};
					foreach ($innerArray as $data_perm) {

						if ($data_perm == 'Add') {
							$add_perm = 1;
						}
						if ($data_perm == 'Add2') {
							$add2_perm = 1;
						}
						if ($data_perm == 'Delete') {
							$delete_perm = 1;
						}
						if ($data_perm == 'Edit') {
							$edit_perm = 1;
						}
						if ($data_perm == 'Edit2') {
							$edit2_perm = 1;
						}
						if ($data_perm == 'View') {
							$view_perm = 1;
						}
						if ($data_perm == 'Print') {
							$print_perm = 1;
						}
					}
					$sql_c1 	= " SELECT * FROM sub_users_role_permissions WHERE role_id = " . $id . "  AND menu_id = " . $key . "  ";
					$rs_c1 		= $db->query($conn, $sql_c1);
					$count_c1 	= $db->counter($rs_c1);
					if ($count_c1 == 0) {

						$sql_c2 		= " SELECT * FROM menus WHERE id = " . $key . " ";
						$rs_c2 			= $db->query($conn, $sql_c2);
						$row_c2 		= $db->fetch($rs_c2);
						$parent_id_1 	= $row_c2[0]['parent_id'];
						if ($parent_id_1 > 0) {
							$sql_c12 		= " SELECT * FROM sub_users_role_permissions WHERE role_id = " . $id . " AND menu_id = " . $parent_id_1 . "  ";
							$rs_c13 		= $db->query($conn, $sql_c12);
							$count_c3 		= $db->counter($rs_c13);
							if ($count_c3 == 0) {
								$sql_c_u2 = "INSERT INTO sub_users_role_permissions (role_id, menu_id, add_date, add_by, add_ip)
										VALUES('" . $id . "', '" . $parent_id_1 . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
								$db->query($conn, $sql_c_u2);
							}
						}
						$sql_c_u = "INSERT INTO sub_users_role_permissions (role_id, menu_id,add_perm, add2_perm,
																				edit_perm, edit2_perm, delete_perm, view_perm, print_perm, add_date, add_by, add_ip)
										VALUES('" . $id . "', '" . $key . "', '" . $add_perm . "', '" . $add2_perm . "', '" . $edit_perm . "', '" . $edit2_perm . "', '" . $delete_perm . "'
																			, '" . $view_perm . "', '" . $print_perm . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
						$db->query($conn, $sql_c_u);
						$msg['msg_success'] = "Permissions Updated Successfully.";
					}
				}
			}
		}
	}
} else if ($cmd == 'edit' && isset($id)) {
	$sql_ee 	= " SELECT * FROM sub_users_roles 
					WHERE enabled = 1 
					AND id = '" . $id . "' 
					AND subscriber_users_id='" . $subscriber_users_id . "' "; //echo $sql_ee;
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
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">List</a>
								</li>
							</ol>
						</div>
						<div class="col s2 m6 l6">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
								List
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 16">
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

						<div class="row">
							<div class="input-field col m12 s12">
								<i class="material-icons prefix">lock_outline</i>
								<input readonly id="role_name" type="text" name="role_name" value="<?php if (isset($role_name)) {
																										echo $role_name;
																									} ?>">
								<label for="role_name">Role Name</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m2 s12">

							</div>
							<div class="input-field col m10 s12">
								<label>
									<input type="checkbox" id="all_checked" class="filled-in" />
									<span>All menus</span>
								</label>
							</div>
							<br>
							<br>
							<?php
							$sql2		= " SELECT DISTINCT e.* 
											FROM subscribers_users a
 											INNER JOIN user_roles b ON a.id = b.subscriber_users_id
											INNER JOIN roles c ON c.id = b.role_id
											INNER JOIN role_permissions d ON d.role_id = c.id
											INNER JOIN menus e ON e.id = d.menu_id
											WHERE e.m_level = 1 AND e.enabled = 1 AND a.id = '" . $subscriber_users_id . "' ";

							$sql2		.= " ORDER BY e.sort_order  ";
							//echo $sql2; die;
							$result_2 	= $db->query($conn, $sql2);
							$row_2 		= $db->fetch($result_2);
							foreach ($row_2 as $data2) {
								$parent_id_level_1 = $data2['id'];
								$sql3		= " SELECT DISTINCT e.* 
												FROM subscribers_users a
												INNER JOIN user_roles b ON a.id = b.subscriber_users_id
												INNER JOIN roles c ON c.id = b.role_id
												INNER JOIN role_permissions d ON d.role_id = c.id
												INNER JOIN menus e ON e.id = d.menu_id
												WHERE e.m_level = 2 AND e.enabled = 1 AND a.id = '" . $subscriber_users_id . "'
												AND e.parent_id = '" . $parent_id_level_1 . "' 	"; //echo $sql3;
								$sql3		.= " ORDER BY e.sort_order ";
								$result_3 	= $db->query($conn, $sql3);
								$count_3 	= $db->counter($result_3);
								if ($count_3 == 0) {
									$sql_level1 	= " SELECT * FROM sub_users_role_permissions  WHERE role_id = '" . $id . "'  AND menu_id = '" . $parent_id_level_1 . "' ";
									$rs_level1 		= $db->query($conn, $sql_level1);
									$count_level1 	= $db->counter($rs_level1);
									$cheked_add1 = $cheked_add2_1 = $cheked_edit1 = $cheked_edit2_1 = $cheked_delete1 = $cheked_view1 = $cheked_print1 = "";
									if ($count_level1 > 0) {
										$row_l1 		= $db->fetch($rs_level1);
										$checked1 = "checked";
										foreach ($row_l1   as $rowl1_data) {
											if ($rowl1_data['add_perm'] > 0) {
												$cheked_add1 = "checked";
											}
											if ($rowl1_data['add2_perm'] > 0) {
												$cheked_add2_1 = "checked";
											}
											if ($rowl1_data['edit_perm'] > 0) {
												$cheked_edit1 = "checked";
											}
											if ($rowl1_data['edit2_perm'] > 0) {
												$cheked_edit2_1 = "checked";
											}
											if ($rowl1_data['delete_perm'] > 0) {
												$cheked_delete1 = "checked";
											}
											if ($rowl1_data['view_perm'] > 0) {
												$cheked_view1 = "checked";
											}
											if ($rowl1_data['print_perm'] > 0) {
												$cheked_print1 = "checked";
											}
										}
									} else {
										$checked1 = "";
									} ?>
									<div class="row">
										<div class="input-field col m2 s12">
											<?php echo $data2['menu_name']; ?>

										</div>
										<div class="input-field col m1 s12">
											<label>
												<input type="checkbox" <?php echo $checked1; ?> name="<?php echo "all_id[" . $parent_id_level_1 . "]"; ?>" id="<?php echo $parent_id_level_1; ?>" class="checkbox filled-in" />
												<span></span>
											</label>
										</div>
										<div class="input-field col m1 s12">
											<label>
												<input type="checkbox" <?php echo $cheked_add1; ?> value="Add" name="perm_<?php echo $parent_id_level_1 ?>[]" id="add_<?php echo $parent_id_level_1 ?>" class="checkbox <?php echo $parent_id_level_1 ?> filled-in" />
												<span>Add</span>
											</label>
										</div>
										<div class="input-field col m1 s12">
											<label>
												<input type="checkbox" <?php echo $cheked_add2_1; ?> value="Add2" name="perm_<?php echo $parent_id_level_1 ?>[]" id="add2_<?php echo $parent_id_level_1 ?>" class="checkbox <?php echo $parent_id_level_1 ?> filled-in" />
												<span>Add2</span>
											</label>
										</div>
										<div class="input-field col m1 s12">
											<label>
												<input type="checkbox" <?php echo $cheked_edit1; ?> value="Edit" name="perm_<?php echo $parent_id_level_1 ?>[]" id="edit_<?php echo $parent_id_level_1 ?>" class="checkbox <?php echo $parent_id_level_1 ?> filled-in" />
												<span>Edit</span>
											</label>
										</div>
										<div class="input-field col m1 s12">
											<label>
												<input type="checkbox" <?php echo $cheked_edit2_1; ?> value="Edit2" name="perm_<?php echo $parent_id_level_1 ?>[]" id="edit2_<?php echo $parent_id_level_1 ?>" class="checkbox <?php echo $parent_id_level_1 ?> filled-in" />
												<span>Edit2</span>
											</label>
										</div>
										<div class="input-field col m1 s12">
											<label>
												<input type="checkbox" <?php echo $cheked_delete1; ?> value="Delete" name="perm_<?php echo $parent_id_level_1 ?>[]" id="delete_<?php echo $parent_id_level_1 ?>" class="checkbox <?php echo $parent_id_level_1 ?> filled-in" />
												<span>Delete</span>
											</label>
										</div>
										<div class="input-field col m1 s12">
											<label>
												<input type="checkbox" <?php echo $cheked_view1; ?> value="View" name="perm_<?php echo $parent_id_level_1 ?>[]" id="view_<?php echo $parent_id_level_1 ?>" class="checkbox <?php echo $parent_id_level_1 ?> filled-in" />
												<span>View</span>
											</label>
										</div>
										<div class="input-field col m1 s12">
											<label>
												<input type="checkbox" <?php echo $cheked_print1; ?> value="Print" name="perm_<?php echo $parent_id_level_1 ?>[]" id="print_<?php echo $parent_id_level_1 ?>" class="checkbox <?php echo $parent_id_level_1 ?> filled-in" />
												<span>Print</span>
											</label>
										</div>
									</div>
									<?php
								} else {
									$row_3 = $db->fetch($result_3);
									foreach ($row_3 as $data3) {
										$parent_id_level_2 	= $data3['id'];

										$sql4		= " SELECT DISTINCT e.* 
														FROM subscribers_users a
														INNER JOIN user_roles b ON a.id = b.subscriber_users_id
														INNER JOIN roles c ON c.id = b.role_id
														INNER JOIN role_permissions d ON d.role_id = c.id
														INNER JOIN menus e ON e.id = d.menu_id
														WHERE e.m_level = 3 AND e.enabled = 1 AND a.id = '" . $subscriber_users_id . "'
														AND e.parent_id = '" . $parent_id_level_2 . "' 	";
										$result4 	= $db->query($conn, $sql4);
										$count4 	= $db->counter($result4);
										if ($count4 == 0) {
											$sql_level2 		= " SELECT * FROM sub_users_role_permissions WHERE role_id = " . $id . " AND menu_id = " . $parent_id_level_2 . " ";
											$rs_level2 			= $db->query($conn, $sql_level2);
											$count_level2 		= $db->counter($rs_level2);
											$cheked_add2 = $cheked_add2_2 = $cheked_edit2 = $cheked_edit2_2 = $cheked_delete2 = $cheked_view2 = $cheked_print2 = "";
											if ($count_level2 > 0) {
												$row_l2 		= $db->fetch($rs_level2);
												$checked2 = "checked";
												foreach ($row_l2   as $rowl2_data) {
													if ($rowl2_data['add_perm'] > 0) {
														$cheked_add2 = "checked";
													}
													if ($rowl2_data['add2_perm'] > 0) {
														$cheked_add2_2 = "checked";
													}
													if ($rowl2_data['edit_perm'] > 0) {
														$cheked_edit2 = "checked";
													}
													if ($rowl2_data['edit2_perm'] > 0) {
														$cheked_edit2_2 = "checked";
													}
													if ($rowl2_data['delete_perm'] > 0) {
														$cheked_delete2 = "checked";
													}
													if ($rowl2_data['view_perm'] > 0) {
														$cheked_view2 = "checked";
													}
													if ($rowl2_data['print_perm'] > 0) {
														$cheked_print2 = "checked";
													}
												}
											} else {
												$checked2 = "";
											} ?>
											<div class="row">
												<div class="input-field col m2 s12">
													<?php echo $data3['menu_name']; ?>
												</div>
												<div class="input-field col m1 s12">
													<label>
														<input type="checkbox" <?php echo $checked2; ?> name="<?php echo "all_id[" . $parent_id_level_2 . "]"; ?>" id="<?php echo $parent_id_level_2; ?>" class="checkbox <?php echo $parent_id_level_1; ?> filled-in" />
														<span></span>
													</label>
												</div>
												<div class="input-field col m1 s12">
													<label>
														<input type="checkbox" <?php echo $cheked_add2; ?> value="Add" name="perm_<?php echo $parent_id_level_2 ?>[]" id="add_<?php echo $parent_id_level_2 ?>" class="checkbox <?php echo $parent_id_level_2 ?> filled-in" />
														<span>Add</span>
													</label>
												</div>
												<div class="input-field col m1 s12">
													<label>
														<input type="checkbox" <?php echo $cheked_add2_2; ?> value="Add2" name="perm_<?php echo $parent_id_level_2 ?>[]" id="add2_<?php echo $parent_id_level_2 ?>" class="checkbox <?php echo $parent_id_level_2 ?> filled-in" />
														<span>Add2</span>
													</label>
												</div>
												<div class="input-field col m1 s12">
													<label>
														<input type="checkbox" <?php echo $cheked_edit2; ?> value="Edit" name="perm_<?php echo $parent_id_level_2 ?>[]" id="edit_<?php echo $parent_id_level_2 ?>" class="checkbox <?php echo $parent_id_level_2 ?> filled-in" />
														<span>Edit</span>
													</label>
												</div>
												<div class="input-field col m1 s12">
													<label>
														<input type="checkbox" <?php echo $cheked_edit2_2; ?> value="Edit2" name="perm_<?php echo $parent_id_level_2 ?>[]" id="edit2_<?php echo $parent_id_level_2 ?>" class="checkbox <?php echo $parent_id_level_2 ?> filled-in" />
														<span>Edit2</span>
													</label>
												</div>
												<div class="input-field col m1 s12">
													<label>
														<input type="checkbox" <?php echo $cheked_delete2; ?> value="Delete" name="perm_<?php echo $parent_id_level_2 ?>[]" id="delete_<?php echo $parent_id_level_2 ?>" class="checkbox <?php echo $parent_id_level_2 ?> filled-in" />
														<span>Delete</span>
													</label>
												</div>
												<div class="input-field col m1 s12">
													<label>
														<input type="checkbox" <?php echo $cheked_view2; ?> value="View" name="perm_<?php echo $parent_id_level_2 ?>[]" id="view_<?php echo $parent_id_level_2 ?>" class="checkbox <?php echo $parent_id_level_2 ?> filled-in" />
														<span>View</span>
													</label>
												</div>
												<div class="input-field col m1 s12">
													<label>
														<input type="checkbox" <?php echo $cheked_print2; ?> value="Print" name="perm_<?php echo $parent_id_level_2 ?>[]" id="print_<?php echo $parent_id_level_2 ?>" class="checkbox <?php echo $parent_id_level_2 ?> filled-in" />
														<span>Print</span>
													</label>
												</div>
											</div>

											<?php } else {
											$row4 = $db->fetch($result4);
											foreach ($row4 as $data4) {
												$parent_id_level_3 = $data4['id'];

												$sql5		= " SELECT DISTINCT e.* 
																FROM subscribers_users a
																INNER JOIN user_roles b ON a.id = b.subscriber_users_id
																INNER JOIN roles c ON c.id = b.role_id
																INNER JOIN role_permissions d ON d.role_id = c.id
																INNER JOIN menus e ON e.id = d.menu_id
																WHERE e.m_level = 3 AND e.enabled = 1 AND a.id = '" . $subscriber_users_id . "'
																AND e.parent_id = '" . $parent_id_level_3 . "'
																ORDER BY e.sort_order ";
												$result5 	= $db->query($conn, $sql5);
												$count5 	= $db->counter($result5);
												if ($count5 == 0) {
													$sql_level3 		= " SELECT * FROM sub_users_role_permissions WHERE role_id = " . $id . "  AND menu_id = " . $parent_id_level_3 . " ";
													$rs_level3 			= $db->query($conn, $sql_level3);
													$count_level3 		= $db->counter($rs_level3);
													$cheked_add3 = $cheked_add2_3 = $cheked_edit3 = $cheked_edit2_3 = $cheked_delete3 = $cheked_view3 = $cheked_print3 = "";
													if ($count_level3 > 0) {
														$row_l3 		= $db->fetch($rs_level3);
														$checked3 = "checked";
														foreach ($row_l3  as $rowl3_data) {
															if ($rowl3_data['add_perm'] > 0) {
																$cheked_add3 = "checked";
															}
															if ($rowl3_data['edit_perm'] > 0) {
																$cheked_edit3 = "checked";
															}
															if ($rowl3_data['delete_perm'] > 0) {
																$cheked_delete3 = "checked";
															}
															if ($rowl3_data['view_perm'] > 0) {
																$cheked_view3 = "checked";
															}
															if ($rowl3_data['print_perm'] > 0) {
																$cheked_print3 = "checked";
															}
														}
													} else {
														$checked3 = "";
													} ?>
													<div class="row">
														<div class="input-field col m2 s12">
															<?php echo $data4['menu_name']; ?>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $checked3; ?> name="<?php echo "all_id[" . $parent_id_level_3 . "]"; ?>" id="<?php echo $parent_id_level_3; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> filled-in" />
																<span></span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_add3; ?> value="Add" name="perm_<?php echo $parent_id_level_3 ?>[]" id="add_<?php echo $parent_id_level_3 ?>" class="checkbox <?php echo $parent_id_level_3 ?> filled-in" />
																<span>Add</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_add2_3; ?> value="Add2" name="perm_<?php echo $parent_id_level_3 ?>[]" id="add2_<?php echo $parent_id_level_3 ?>" class="checkbox <?php echo $parent_id_level_3 ?> filled-in" />
																<span>Add2</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_edit3; ?> value="Edit" name="perm_<?php echo $parent_id_level_3 ?>[]" id="edit_<?php echo $parent_id_level_3 ?>" class="checkbox <?php echo $parent_id_level_3 ?> filled-in" />
																<span>Edit</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_edit2_3; ?> value="Edit2" name="perm_<?php echo $parent_id_level_3 ?>[]" id="edit2_<?php echo $parent_id_level_3 ?>" class="checkbox <?php echo $parent_id_level_3 ?> filled-in" />
																<span>Edit2</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_delete3; ?> value="Delete" name="perm_<?php echo $parent_id_level_2 ?>[]" id="delete_<?php echo $parent_id_level_3 ?>" class="checkbox <?php echo $parent_id_level_3 ?> filled-in" />
																<span>Delete</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_view3; ?> value="View" name="perm_<?php echo $parent_id_level_2 ?>[]" id="view_<?php echo $parent_id_level_3 ?>" class="checkbox <?php echo $parent_id_level_3 ?> filled-in" />
																<span>View</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_print3; ?> value="Print" name="perm_<?php echo $parent_id_level_2 ?>[]" id="print_<?php echo $parent_id_level_3 ?>" class="checkbox <?php echo $parent_id_level_3 ?> filled-in" />
																<span>Print</span>
															</label>
														</div>
													</div>

													<?php } else {
													$row5 = $db->fetch($result5);
													foreach ($row5 as $data5) {
														$parent_id_level_4 	= $data5['id'];
														$sql_level4 		= " SELECT * FROM sub_users_role_permissions
																		WHERE role_id 		= " . $id . "
																		AND menu_id 		= " . $parent_id_level_4 . "  ";
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
																<span><?php echo $data5['menu_name']; ?></span>
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
						<!-- <div class="row">
							<div class="row">
								<div class="input-field col m6 s12">
									<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?>
									</button>
								</div>
							</div>
						</div><br> -->
						<div class="row">
							<div class="row">&nbsp;&nbsp;</div>
							<div class="row">
								<div class="input-field col m4 s12"></div>
								<div class="input-field col m3 s12">
									<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
										<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?>
										</button>
									<?php } ?>
								</div>
								<div class="input-field col m3 s12"></div>
							</div>
						</div>
					</form>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br>
	<!-- END: Page Main-->