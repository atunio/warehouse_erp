<?php
if (!isset($module)) {
	require_once('../../../../conf/functions.php');
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
		$sql_c_upd = "UPDATE phone_check_users set enabled = 0,
												update_date = '" . $add_date . "' ,
												update_by 	= '" . $_SESSION['username'] . "' ,
												update_ip 	= '" . $add_ip . "'
					WHERE id = '" . $id . "' ";
		$enabe_ok = $db->query($conn, $sql_c_upd);
		if ($enabe_ok) {
			$msg['msg_success'] = "Record has been disabled.";
		} else {
			$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
		}
	}
	if (isset($cmd) && $cmd == 'enabled') {
		$sql_c_upd = "UPDATE phone_check_users set enabled = 1,
												update_date = '" . $add_date . "' ,
												update_by 	= '" . $_SESSION['username'] . "' ,
												update_ip 	= '" . $add_ip . "'
					WHERE id = '" . $id . "' ";
		$enabe_ok = $db->query($conn, $sql_c_upd);
		if ($enabe_ok) {
			$msg['msg_success'] = "Record has been enabled.";
		}
	}
}

$sql_cl 		= "	SELECT  a.*, b.first_name, b.middle_name, b.last_name, b.username
					FROM phone_check_users a
					LEFT JOIN users b ON b.id = a.erp_user_id
					WHERE 1=1 ";
if (isset($flt_phone_check_users) && $flt_phone_check_users != "") {
	$sql_cl 	.= " AND a.id = '" . trim($flt_phone_check_users) . "' ";
}

if (!isset($is_enabled_disabled)) {
	$is_enabled_disabled	 = 1;
}
if (isset($is_enabled_disabled) && $is_enabled_disabled != "") {
	$sql_cl			.= " AND a.enabled = '" . $is_enabled_disabled . "' ";
}
$sql_cl 		.= " ORDER BY a.enabled DESC, a.id DESC";  //echo $sql_cl; die;
$result_cl 		= $db->query($conn, $sql_cl);
$count_cl 		= $db->counter($result_cl);
$page_heading 	= "List of PhoneCheck Users";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<!-- <div class="container"> -->
			<div class="section section-data-tables">
				<div class="row">
					<div class="col s12">
						<div class="card custom_margin_card_table_top">
							<div class="card-content custom_padding_card_content_table_top_bottom">
								<div class="row">
									<div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
										<h6 class="media-heading">
											<?php echo $page_heading; ?>
										</h6>
									</div>
									<div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
										<?php
										if (access("add_perm") == 1) { ?>
											<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=add&cmd2=add") ?>">
												New
											</a>
										<?php } ?>
										<?php
										if (access("add_perm") == 1) { ?>
											<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import") ?>">
												Import
											</a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Page Length Options -->
				<div class="row">
					<div class="col s12">
						<div class="card custom_margin_card_table_top">
							<div class="card-content custom_padding_card_content_table_top">
								<?php
								if (isset($error['msg'])) { ?>
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card red lighten-5">
												<div class="card-content red-text">
													<p><?php echo $error['msg']; ?></p>
												</div>
												<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										</div>
									</div>
								<?php } else if (isset($msg['msg_success'])) { ?>
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card green lighten-5">
												<div class="card-content green-text">
													<p><?php echo $msg['msg_success']; ?></p>
												</div>
												<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										</div>
									</div>
								<?php } ?><br>
								<form method="post" autocomplete="off" enctype="multipart/form-data" action="<?php echo "?string=" . encrypt('module_id=' . $module_id . '&page=' . $page); ?>">
									<input type="hidden" name="is_Submit" value="Y" />
									<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																						echo encrypt($_SESSION['csrf_session']);
																					} ?>">
									<div class="row">
										<div class="input-field col m3 s12">
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<?php
												$field_name     = "flt_phone_check_users";
												$field_label    = "User Name";

												$sql1           = " SELECT  a.* FROM phone_check_users a ORDER BY username";
												$result1        = $db->query($conn, $sql1);
												$count1         = $db->counter($result1);
												?>
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">ALL</option>
													<?php
													if ($count1 > 0) {
														$row1    = $db->fetch($result1);
														foreach ($row1 as $data2) { ?>
															<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['username']; ?></option>
													<?php }
													} ?>
												</select>
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
										</div>
										<div class="input-field col m2 s12">
											<?php
											$field_name 	= "is_enabled_disabled";
											$field_label 	= "Active";
											?>
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">All</option>
													<option value="1" <?php if (isset(${$field_name}) && ${$field_name} == "1") { ?> selected="selected" <?php } ?>>Yes</option>
													<option value="0" <?php if (isset(${$field_name}) && ${$field_name} == "0") { ?> selected="selected" <?php } ?>>No </option>
												</select>
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
										</div>
										<div class="input-field col m2 s12">
											<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button> &nbsp;&nbsp;
											<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">All</a>
										</div>
									</div>
								</form>
								<br>
								<div class="row">
									<div class="text_align_right">
										<?php
										$table_columns	= array('SNo', 'Username', 'Full Name', 'ERP User', 'Actions');
										$k 				= 0;
										foreach ($table_columns as $data_c1) { ?>
											<label>
												<input type="checkbox" value="<?= $k ?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1) ?>" checked="checked">
												<span><?= $data_c1 ?></span>
											</label>&nbsp;&nbsp;
										<?php
											$k++;
										} ?>
									</div>
								</div>
								<div class="row">
									<div class="col s12">
										<table id="page-length-option" class="display pagelength50_3">
											<thead>
												<tr>
													<?php
													$headings = "";
													foreach ($table_columns as $data_c) {
														if ($data_c == 'SNo') {
															$headings .= '<th class="sno_width_60 col-' . set_table_headings($data_c) . '">' . $data_c . '</th>';
														} else {
															$headings .= '<th class="col-' . set_table_headings($data_c) . '">' . $data_c . '</th> ';
														}
													}
													echo $headings;
													?>
												</tr>
											</thead>
											<tbody>
												<?php
												$i = 0;
												if ($count_cl > 0) {
													$row_cl = $db->fetch($result_cl);
													foreach ($row_cl as $data) {
														$id = $data['id'];
														$columnno = 0; ?>
														<tr>
															<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[$columnno]); ?>">
																<?php echo $i + 1;
																$columnno++; ?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[$columnno]); ?>">
																<?php echo (($data['username']));
																$columnno++; ?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[$columnno]); ?>">
																<?php echo ucwords(strtolower($data['full_name']));
																$columnno++; ?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[$columnno]); ?>">
																<?php
																if ($data['first_name'] != "") {
																	echo ucwords(strtolower($data['first_name']));
																}
																if ($data['middle_name'] != "") {
																	echo " " . ucwords(strtolower($data['middle_name']));
																}
																if ($data['last_name'] != "") {
																	echo " " . ucwords(strtolower($data['last_name']));
																}
																if ($data['username'] != "") {
																	echo " (" . $data['username'] . ")";
																}
																$columnno++; ?>
															</td>
															<td class="text-align-center col-<?= set_table_headings($table_columns[$columnno]); ?>">
																<?php
																if ($data['enabled'] == 0 && access("edit_perm") == 1) { ?>
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=enabled&id=" . $id) ?>">
																		<i class="material-icons dp48">add</i>
																	</a> &nbsp;&nbsp;
																<?php } else if ($data['enabled'] == 1 && access("delete_perm") == 1) { ?>
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=disabled&id=" . $id) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																		<i class="material-icons dp48">delete</i>
																	</a>&nbsp;&nbsp;
																<?php }
																if ($data['enabled'] == 1 && access("view_perm") == 1) { ?>
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&id=" . $id) ?>">
																		<i class="material-icons dp48">edit</i>
																	</a> &nbsp;&nbsp;
																<?php } ?>
															</td>
														</tr>
												<?php $i++;
													}
												} ?>
											<tfoot>
												<tr>
													<?php echo $headings; ?>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Multi Select -->
			</div><!-- START RIGHT SIDEBAR NAV -->

			<?php include('sub_files/right_sidebar.php'); ?>
			<!-- </div> -->

			<!-- <div class="content-overlay"></div> -->
		</div>
	</div>
</div>