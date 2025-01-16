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
	if (empty($error)) {
		if (!is_array($all_id)) {
			$all_id = array($all_id);
		}
		$sql_del = "DELETE FROM  sub_users_role_permissions  
					WHERE role_id = '" . $id . "'  ";
		$db->query($conn, $sql_del);
		foreach ($all_id as $key => $value) {
			if ($key > 0) {
				$sql_c1 	= " SELECT * FROM sub_users_role_permissions WHERE role_id = " . $id . " AND menu_id = " . $key . "  ";
				$rs_c1 		= $db->query($conn, $sql_c1);
				$count_c1 	= $db->counter($rs_c1);
				if ($count_c1 == 0) {

					$sql_parent = " SELECT * FROM menus WHERE parent_id = " . $key . " ";
					//echo "<br>" . $sql_c1 . "<br><br>";
					$rs_parent 		= $db->query($conn, $sql_parent);
					$count_parent 	= $db->counter($rs_parent);
					if ($count_parent > 0) {
						$add_perm = $add2_perm = $edit_perm = $edit2_perm = $delete_perm = $view_perm = $print_perm = 1;
					} else {
						$add_perm = $add2_perm = $edit_perm = $edit2_perm = $delete_perm = $view_perm = $print_perm = 0;
						if (isset(${"perm_" . $key})) {
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
						}
					}
					$special_module_permisions2 = "";
					if ($key == '10' && isset($special_module_permisions_10) && sizeof($special_module_permisions_10)) {
						$special_module_permisions2 = implode(",", $special_module_permisions_10);
					}
					if ($key == '34' && isset($special_module_permisions_34) && sizeof($special_module_permisions_34)) {
						$special_module_permisions2 = implode(",", $special_module_permisions_34);
					}
					if ($key == '31' && isset($special_module_permisions_31) && sizeof($special_module_permisions_31)) {
						$special_module_permisions2 = implode(",", $special_module_permisions_31);
					}
					if ($key == '35' && isset($special_module_permisions_35) && sizeof($special_module_permisions_35)) {
						$special_module_permisions2 = implode(",", $special_module_permisions_35);
					}
					$sql_c_u = "INSERT INTO sub_users_role_permissions (role_id, menu_id, add_perm, add2_perm, edit_perm, edit2_perm, 
																		delete_perm, view_perm, print_perm, special_module_permisions, add_date, add_by, add_ip)
									VALUES('" . $id . "', '" . $key . "', '" . $add_perm . "', '" . $add2_perm . "', '" . $edit_perm . "', '" . $edit2_perm . "', 
														'" . $delete_perm . "', '" . $view_perm . "', '" . $print_perm . "', '" . $special_module_permisions2 . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					$db->query($conn, $sql_c_u);
					$msg['msg_success'] = "Permissions Updated Successfully.";
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
		<div class="col s12 m12 l12">
			<div class="section section-data-tables">   
				<div class="card custom_margin_card_table_top custom_margin_card_table_bottom">
					<div class="card-content custom_padding_card_content_table_top_bottom"> 
						<div class="row">
							<div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
								<h6 class="media-heading">
									<?php echo $title_heading; ?>
								</h6>
							</div>
							<div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">
									List
								</a> 
							</div>
						</div>
					</div>
				</div> 
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy custom_margin_card_table_top custom_margin_card_table_bottom">
				<div class="card-content custom_padding_card_content_table_top">
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
							<div class="input-field col m12 s12">
								<label>
									<input type="checkbox" id="all_checked" class="filled-in" />
									<span>All menus</span>
								</label>
							</div>
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
							foreach ($row_2 as $data) {
								$cheked_add1 = $cheked_add21 = $cheked_edit1 = $cheked_edit21 = $cheked_delete1 = $cheked_view1 = $cheked_print1 = "";
								$parent_id_level_1 = $data['id'];
								$sql_level1 	= " SELECT * FROM sub_users_role_permissions  WHERE role_id = '" . $id . "'  AND menu_id = '" . $parent_id_level_1 . "' ";
								$rs_level1 		= $db->query($conn, $sql_level1);
								$count_level1 	= $db->counter($rs_level1);
								if ($count_level1 > 0) {
									$checked1 	= "checked=''";
									$row_l1		= $db->fetch($rs_level1);
									foreach ($row_l1   as $rowl1_data) {

										if ($parent_id_level_1 == '31') {
											$special_module_permisions_31 = explode(",", $rowl1_data['special_module_permisions']);
										}
										if ($parent_id_level_1 == '35') {
											$special_module_permisions_35 = explode(",", $rowl1_data['special_module_permisions']);
										}
										if ($rowl1_data['add_perm'] > 0) {
											$cheked_add1 = "checked=''";
										}
										if ($rowl1_data['add2_perm'] > 0) {
											$cheked_add21 = "checked=''";
										}
										if ($rowl1_data['edit_perm'] > 0) {
											$cheked_edit1 = "checked=''";
										}
										if ($rowl1_data['edit2_perm'] > 0) {
											$cheked_edit21 = "checked=''";
										}
										if ($rowl1_data['delete_perm'] > 0) {
											$cheked_delete1 = "checked=''";
										}
										if ($rowl1_data['view_perm'] > 0) {
											$cheked_view1 = "checked=''";
										}
										if ($rowl1_data['print_perm'] > 0) {
											$cheked_print1 = "checked=''";
										}
									}
								} else {
									$checked1 = "";
								} ?>
								<div class="" style="height: 40px;">&nbsp;</div>
								<div class="input-field col m5 s12">
									<label>
										<input type="checkbox" <?php echo $checked1; ?> name="<?php echo "all_id[" . $parent_id_level_1 . "]"; ?>" id="<?php echo $parent_id_level_1; ?>" class="checkbox filled-in" />
										<span><?php echo $data['menu_name']; ?></span>
									</label>
								</div>
								<?php
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
								if ($count_3 > 0) {
									$row_3 = $db->fetch($result_3);
									foreach ($row_3 as $data2) {
										$parent_id_level_2 	= $data2['id'];
										$sql_level2 		= " SELECT * FROM sub_users_role_permissions WHERE role_id = " . $id . " AND menu_id = " . $parent_id_level_2 . " ";
										$rs_level2 			= $db->query($conn, $sql_level2);
										$count_level2 		= $db->counter($rs_level2);
										$cheked_add2 		= $cheked_add22 = $cheked_edit2 = $cheked_edit22 = $cheked_delete2 = $cheked_view2 = $cheked_print2 = "";
										if ($count_level2 > 0) {
											$checked2 = "checked=''";
											$row_l2		= $db->fetch($rs_level2);
											foreach ($row_l2   as $rowl2_data) {
												if ($rowl2_data['add_perm'] > 0) {
													$cheked_add2 = "checked=''";
												}
												if ($rowl2_data['add2_perm'] > 0) {
													$cheked_add22 = "checked=''";
												}
												if ($rowl2_data['edit_perm'] > 0) {
													$cheked_edit2 = "checked=''";
												}
												if ($rowl2_data['edit2_perm'] > 0) {
													$cheked_edit22 = "checked=''";
												}
												if ($rowl2_data['delete_perm'] > 0) {
													$cheked_delete2 = "checked=''";
												}
												if ($rowl2_data['view_perm'] > 0) {
													$cheked_view2 = "checked=''";
												}
												if ($rowl2_data['print_perm'] > 0) {
													$cheked_print2 = "checked=''";
												}
											}
										} else {
											$checked2 = "";
										} ?>
										<div class="" style="height: 40px;">&nbsp;</div>
										<div class="input-field col m5 s12">
											<label style="margin-left: 25px;">
												<input type="checkbox" <?php echo $checked2; ?> name="<?php echo "all_id[" . $parent_id_level_2 . "]"; ?>" id="<?php echo $parent_id_level_2; ?>" class="checkbox <?php echo $parent_id_level_1; ?> filled-in" />
												<span><?php echo $data2['menu_name']; ?></span>
											</label>
										</div>
										<?php
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
										if ($count4 > 0) {
											$row4 = $db->fetch($result4);
											foreach ($row4 as $data3) {
												$parent_id_level_3 = $data3['id'];
												$sql_level3 		= " SELECT * FROM sub_users_role_permissions WHERE role_id = " . $id . "  AND menu_id = " . $parent_id_level_3 . " ";
												$rs_level3 			= $db->query($conn, $sql_level3);
												$count_level3 		= $db->counter($rs_level3);
												$cheked_add3 = $cheked_add23 = $cheked_edit3 = $cheked_edit23 = $cheked_delete3 = $cheked_view3 = $cheked_print3 = "";
												if ($count_level3 > 0) {
													$checked3 = "checked=''";
													$row_l3		= $db->fetch($rs_level3);
													foreach ($row_l3   as $rowl3_data) {

														if ($parent_id_level_3 == '10') {
															$special_module_permisions_10 = explode(",", $rowl3_data['special_module_permisions']);
														}
														if ($parent_id_level_3 == '34') {
															$special_module_permisions_34 = explode(",", $rowl3_data['special_module_permisions']);
														}

														if ($rowl3_data['add_perm'] > 0) {
															$cheked_add3 = "checked=''";
														}
														if ($rowl3_data['add2_perm'] > 0) {
															$cheked_add23 = "checked=''";
														}
														if ($rowl3_data['edit_perm'] > 0) {
															$cheked_edit3 = "checked=''";
														}
														if ($rowl3_data['edit2_perm'] > 0) {
															$cheked_edit23 = "checked=''";
														}
														if ($rowl3_data['delete_perm'] > 0) {
															$cheked_delete3 = "checked=''";
														}
														if ($rowl3_data['view_perm'] > 0) {
															$cheked_view3 = "checked=''";
														}
														if ($rowl3_data['print_perm'] > 0) {
															$cheked_print3 = "checked=''";
														}
													}
												} else {
													$checked3 = "";
												} ?>
												<div class="" style="height: 40px;">&nbsp;</div>
												<div class="input-field col m5 s12">
													<label style="margin-left: 50px;">
														<input type="checkbox" <?php echo $checked3; ?> name="<?php echo "all_id[" . $parent_id_level_3 . "]"; ?>" id="<?php echo $parent_id_level_3; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> filled-in" />
														<span><?php echo $data3['menu_name']; ?></span>
													</label>
												</div>
												<?php
												$sql5		= " SELECT DISTINCT e.* 
																FROM subscribers_users a
																INNER JOIN user_roles b ON a.id = b.subscriber_users_id
																INNER JOIN roles c ON c.id = b.role_id
																INNER JOIN role_permissions d ON d.role_id = c.id
																INNER JOIN menus e ON e.id = d.menu_id
																WHERE e.m_level = 4 AND e.enabled = 1 AND a.id = '" . $subscriber_users_id . "'
																AND e.parent_id = '" . $parent_id_level_3 . "'
																ORDER BY e.sort_order ";
												$result5 	= $db->query($conn, $sql5);
												$count5 	= $db->counter($result5);
												///*
												if ($count5 > 0) {
													$row5 = $db->fetch($result5);
													foreach ($row5 as $data4) {
														$parent_id_level_4 	= $data4['id'];
														$sql_level4 		= " SELECT * FROM sub_users_role_permissions
																				WHERE role_id 		= " . $id . "
																				AND menu_id 		= " . $parent_id_level_4 . "  ";
														$rs_level4 			= $db->query($conn, $sql_level4);
														$count_level4 		= $db->counter($rs_level4);
														$cheked_add4 		= $cheked_add24 = $cheked_edit4 = $cheked_edit24 = $cheked_delete4 = $cheked_view4 = $cheked_print4 = "";
														if ($count_level4 > 0) {
															$checked4 	= "checked=''";
															$row_l4 	= $db->fetch($rs_level4);
															foreach ($row_l4  as $rowl4_data) {
																if ($rowl4_data['add_perm'] > 0) {
																	$cheked_add4 = "checked=''";
																}
																if ($rowl4_data['add2_perm'] > 0) {
																	$cheked_add24 = "checked=''";
																}
																if ($rowl4_data['edit_perm'] > 0) {
																	$cheked_edit4 = "checked=''";
																}
																if ($rowl4_data['edit2_perm'] > 0) {
																	$cheked_edit24 = "checked=''";
																}
																if ($rowl4_data['delete_perm'] > 0) {
																	$cheked_delete4 = "checked=''";
																}
																if ($rowl4_data['view_perm'] > 0) {
																	$cheked_view4 = "checked=''";
																}
																if ($rowl4_data['print_perm'] > 0) {
																	$cheked_print4 = "checked=''";
																}
															}
														} else {
															$checked4 = "";
														} ?>
														<div class="" style="height: 40px;">&nbsp;</div>
														<div class="input-field col m6 s12">
															<label style="margin-left: 60px;">
																<input type="checkbox" <?php echo $checked4; ?> name="<?php echo "all_id[" . $parent_id_level_4 . "]"; ?>" id="<?php echo $parent_id_level_4; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?> filled-in" />
																<span><?php echo $data4['menu_name']; ?></span>
															</label>
														</div>

														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_add4; ?> value="Add" name="perm_<?php echo $parent_id_level_4 ?>[]" id="add_<?php echo $parent_id_level_4; ?>" class="checkbox <?php echo $parent_id_level_4; ?>" />
																<span>Add</span>
															</label>
														</div>

														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_add24; ?> value="Add2" name="perm_<?php echo $parent_id_level_4 ?>[]" id="add_<?php echo $parent_id_level_4; ?>" class="checkbox <?php echo $parent_id_level_4; ?>" />
																<span>Add2</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_edit4; ?> value="Edit" name="perm_<?php echo $parent_id_level_4 ?>[]" id="add_<?php echo $parent_id_level_4; ?>" class="checkbox <?php echo $parent_id_level_4; ?>" />
																<span>Edit</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_edit24; ?> value="Edit2" name="perm_<?php echo $parent_id_level_4 ?>[]" id="add_<?php echo $parent_id_level_4; ?>" class="checkbox <?php echo $parent_id_level_4; ?>" />
																<span>Edit2</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_delete4; ?> value="Delete" name="perm_<?php echo $parent_id_level_4 ?>[]" id="add_<?php echo $parent_id_level_4; ?>" class="checkbox <?php echo $parent_id_level_4; ?>" />
																<span>Delete</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_view4; ?> value="View" name="perm_<?php echo $parent_id_level_4 ?>[]" id="add_<?php echo $parent_id_level_4; ?>" class="checkbox <?php echo $parent_id_level_4; ?>" />
																<span>View</span>
															</label>
														</div>
														<div class="input-field col m1 s12">
															<label>
																<input type="checkbox" <?php echo $cheked_print4; ?> value="Print" name="perm_<?php echo $parent_id_level_4 ?>[]" id="add_<?php echo $parent_id_level_4; ?>" class="checkbox <?php echo $parent_id_level_4; ?>" />
																<span>Print</span>
															</label>
														</div>
													<?php }
												} else { ?>
													<div class="input-field col m1 s12">
														<label>
															<input type="checkbox" <?php echo $cheked_add3; ?> value="Add" name="perm_<?php echo $parent_id_level_3 ?>[]" id="add_<?php echo $parent_id_level_3; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
															<span>Add</span>
														</label>
													</div>

													<div class="input-field col m1 s12">
														<label>
															<input type="checkbox" <?php echo $cheked_add23; ?> value="Add2" name="perm_<?php echo $parent_id_level_3 ?>[]" id="add_<?php echo $parent_id_level_3; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
															<span>Add2</span>
														</label>
													</div>
													<div class="input-field col m1 s12">
														<label>
															<input type="checkbox" <?php echo $cheked_edit3; ?> value="Edit" name="perm_<?php echo $parent_id_level_3 ?>[]" id="add_<?php echo $parent_id_level_3; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
															<span>Edit</span>
														</label>
													</div>
													<div class="input-field col m1 s12">
														<label>
															<input type="checkbox" <?php echo $cheked_edit23; ?> value="Edit2" name="perm_<?php echo $parent_id_level_3 ?>[]" id="add_<?php echo $parent_id_level_3; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
															<span>Edit2</span>
														</label>
													</div>
													<div class="input-field col m1 s12">
														<label>
															<input type="checkbox" <?php echo $cheked_delete3; ?> value="Delete" name="perm_<?php echo $parent_id_level_3 ?>[]" id="add_<?php echo $parent_id_level_3; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
															<span>Delete</span>
														</label>
													</div>
													<div class="input-field col m1 s12">
														<label>
															<input type="checkbox" <?php echo $cheked_view3; ?> value="View" name="perm_<?php echo $parent_id_level_3 ?>[]" id="add_<?php echo $parent_id_level_3; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
															<span>View</span>
														</label>
													</div>
													<div class="input-field col m1 s12">
														<label>
															<input type="checkbox" <?php echo $cheked_print3; ?> value="Print" name="perm_<?php echo $parent_id_level_3 ?>[]" id="add_<?php echo $parent_id_level_3; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
															<span>Print2</span>
														</label>
													</div>
													<?php
													if ($parent_id_level_3 == '10') {  ?>
						</div>
						<div class="row">
							<div class="input-field col m5 s12">
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) &&  in_array("PO Detail", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="PO Detail" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>PO Detail</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) &&  in_array("Vendor Data", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="Vendor Data" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>Vendor Data</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) &&  in_array("Logistics", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="Logistics" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>Logistics</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) &&  in_array("Arrival", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="Arrival" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>Arrival</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) &&  in_array("Receive", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="Receive" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>Receive</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) && in_array("Diagnostic", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="Diagnostic" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>Diagnostic</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) && in_array("Move as Inventory", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="Move as Inventory" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>Move as Inventory</span>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m5 s12">
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) && in_array("RMA", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="RMA" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>RMA</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) && in_array("RMA Process", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="RMA Process" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>RMA Process</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) && in_array("PriceSetup", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="PriceSetup" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>FinalPricing</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_10) && in_array("ALL PO in List", $special_module_permisions_10)) {
																echo "checked=''";
															} ?> value="ALL PO in List" name="special_module_permisions_10[]" id="special_module_permisions_10" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>ALL PO in List</span>
								</label>
							</div>
						<?php
													}

													if ($parent_id_level_3 == '34') {  ?>
						</div>
						<div class="row">
							<div class="input-field col m5 s12">
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_34) &&  in_array("Pkg_Logistics", $special_module_permisions_34)) {
																echo "checked=''";
															} ?> value="Pkg_Logistics" name="special_module_permisions_34[]" id="special_module_permisions_34" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>Logistics</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_34) &&  in_array("Pkg_PO_Detail", $special_module_permisions_34)) {
																echo "checked=''";
															} ?> value="Pkg_PO_Detail" name="special_module_permisions_34[]" id="special_module_permisions_34" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>PO Detail</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_34) &&  in_array("Pkg_Receive", $special_module_permisions_34)) {
																echo "checked=''";
															} ?> value="Pkg_Receive" name="special_module_permisions_34[]" id="special_module_permisions_34" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?> <?php echo $parent_id_level_3; ?>" />
									<span>Receive</span>
								</label>
							</div>
				<?php
													}
												}
												//*/
											}
										} else { ?>
				<div class="input-field col m1 s12">
					<label>
						<input type="checkbox" <?php echo $cheked_add2; ?> value="Add" name="perm_<?php echo $parent_id_level_2 ?>[]" id="add_<?php echo $parent_id_level_2; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?>" />
						<span>Add</span>
					</label>
				</div>

				<div class="input-field col m1 s12">
					<label>
						<input type="checkbox" <?php echo $cheked_add22; ?> value="Add2" name="perm_<?php echo $parent_id_level_2 ?>[]" id="add_<?php echo $parent_id_level_2; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?>" />
						<span>Add2</span>
					</label>
				</div>
				<div class="input-field col m1 s12">
					<label>
						<input type="checkbox" <?php echo $cheked_edit2; ?> value="Edit" name="perm_<?php echo $parent_id_level_2 ?>[]" id="add_<?php echo $parent_id_level_2; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?>" />
						<span>Edit</span>
					</label>
				</div>
				<div class="input-field col m1 s12">
					<label>
						<input type="checkbox" <?php echo $cheked_edit22; ?> value="Edit2" name="perm_<?php echo $parent_id_level_2 ?>[]" id="add_<?php echo $parent_id_level_2; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?>" />
						<span>Edit2</span>
					</label>
				</div>
				<div class="input-field col m1 s12">
					<label>
						<input type="checkbox" <?php echo $cheked_delete2; ?> value="Delete" name="perm_<?php echo $parent_id_level_2 ?>[]" id="add_<?php echo $parent_id_level_2; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?>" />
						<span>Delete</span>
					</label>
				</div>
				<div class="input-field col m1 s12">
					<label>
						<input type="checkbox" <?php echo $cheked_view2; ?> value="View" name="perm_<?php echo $parent_id_level_2 ?>[]" id="add_<?php echo $parent_id_level_2; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?>" />
						<span>View</span>
					</label>
				</div>
				<div class="input-field col m1 s12">
					<label>
						<input type="checkbox" <?php echo $cheked_print2; ?> value="Print" name="perm_<?php echo $parent_id_level_2 ?>[]" id="add_<?php echo $parent_id_level_2; ?>" class="checkbox <?php echo $parent_id_level_1; ?> <?php echo $parent_id_level_2; ?>" />
						<span>Print</span>
					</label>
				</div>
		<?php
										}
									}
								} else { ?>
		<div class="input-field col m1 s12">
			<label>
				<input type="checkbox" <?php echo $cheked_add1; ?> value="Add" name="perm_<?php echo $parent_id_level_1 ?>[]" id="add_<?php echo $parent_id_level_1; ?>" class="checkbox <?php echo $parent_id_level_1; ?>" />
				<span>Add</span>
			</label>
		</div>

		<div class="input-field col m1 s12">
			<label>
				<input type="checkbox" <?php echo $cheked_add21; ?> value="Add2" name="perm_<?php echo $parent_id_level_1 ?>[]" id="add_<?php echo $parent_id_level_1; ?>" class="checkbox <?php echo $parent_id_level_1; ?>" />
				<span>Add2</span>
			</label>
		</div>
		<div class="input-field col m1 s12">
			<label>
				<input type="checkbox" <?php echo $cheked_edit1; ?> value="Edit" name="perm_<?php echo $parent_id_level_1 ?>[]" id="add_<?php echo $parent_id_level_1; ?>" class="checkbox <?php echo $parent_id_level_1; ?>" />
				<span>Edit</span>
			</label>
		</div>
		<div class="input-field col m1 s12">
			<label>
				<input type="checkbox" <?php echo $cheked_edit21; ?> value="Edit2" name="perm_<?php echo $parent_id_level_1 ?>[]" id="add_<?php echo $parent_id_level_1; ?>" class="checkbox <?php echo $parent_id_level_1; ?>" />
				<span>Edit2</span>
			</label>
		</div>
		<div class="input-field col m1 s12">
			<label>
				<input type="checkbox" <?php echo $cheked_delete1; ?> value="Delete" name="perm_<?php echo $parent_id_level_1 ?>[]" id="add_<?php echo $parent_id_level_1; ?>" class="checkbox <?php echo $parent_id_level_1; ?>" />
				<span>Delete</span>
			</label>
		</div>
		<div class="input-field col m1 s12">
			<label>
				<input type="checkbox" <?php echo $cheked_view1; ?> value="View" name="perm_<?php echo $parent_id_level_1 ?>[]" id="add_<?php echo $parent_id_level_1; ?>" class="checkbox <?php echo $parent_id_level_1; ?>" />
				<span>View</span>
			</label>
		</div>
		<div class="input-field col m1 s12">
			<label>
				<input type="checkbox" <?php echo $cheked_print1; ?> value="Print" name="perm_<?php echo $parent_id_level_1 ?>[]" id="add_<?php echo $parent_id_level_1; ?>" class="checkbox <?php echo $parent_id_level_1; ?>" />
				<span>Print</span>
			</label>
		</div>
		<?php


									if ($parent_id_level_1 == '31') {  ?>
						</div>
						<div class="row">
							<div class="input-field col m5 s12">
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_31) &&  in_array("RMA Repair", $special_module_permisions_31)) {
																echo "checked=''";
															} ?> value="RMA Repair" name="special_module_permisions_31[]" id="special_module_permisions_31" class="checkbox <?php echo $parent_id_level_1; ?> " />
									<span>Repair</span>
								</label>
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_31) &&  in_array("Repair Process", $special_module_permisions_31)) {
																echo "checked=''";
															} ?> value="Repair Process" name="special_module_permisions_31[]" id="special_module_permisions_31" class="checkbox <?php echo $parent_id_level_1; ?>" />
									<span>Repair Process</span>
								</label>
							</div>
						<?php
									}
									if ($parent_id_level_1 == '35') {  ?>
						</div>
						<div class="row">
							<div class="input-field col m5 s12">
							</div>
							<div class="input-field col m1 s12">
								<label>
									<input type="checkbox" <?php if (isset($special_module_permisions_35) &&  in_array("Move to Finale", $special_module_permisions_35)) {
																echo "checked=''";
															} ?> value="Move to Finale" name="special_module_permisions_35[]" id="special_module_permisions_35" class="checkbox <?php echo $parent_id_level_1; ?>" />
									<span>Move to Finale</span>
								</label>
							</div>
						<?php
									} ?>

				<?php }
							} ?>
						</div> <br> <br>
						<div class="row">
							<div class="input-field col m2 s12">
								<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?>
								</button>
							</div>
						</div><br>
					</form>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br>
	<!-- END: Page Main-->