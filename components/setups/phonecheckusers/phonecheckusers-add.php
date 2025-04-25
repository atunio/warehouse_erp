<?php
if (!isset($module)) {
	require_once('../../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$full_name	= "User  " . date('Ymd-His');
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if ($cmd == 'edit') {
	$title_heading = "Update PhoneCheck User";
	$button_val = "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add PhoneCheck User";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee			= "	SELECT a.* FROM phone_check_users a 
						WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee		= $db->query($conn, $sql_ee);
	$row_ee			= $db->fetch($result_ee);
	$username		= $row_ee[0]['username'];
	$full_name		= $row_ee[0]['full_name'];
	$erp_user_id	= $row_ee[0]['erp_user_id'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	$field_name = "full_name";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name]			= "Required";
		${$field_name . "_valid"}	= "invalid";
	}
	$field_name = "username";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name]			= "Required";
		${$field_name . "_valid"}	= "invalid";
	}
	$field_name = "erp_user_id";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name]			= "Required";
		${$field_name . "_valid"}	= "invalid";
	}
	if (empty($error)) {
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM phone_check_users a  WHERE a.username	= '" . $username . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".phone_check_users(subscriber_users_id, username, full_name, erp_user_id, add_date, add_by, add_ip)
							 VALUES('" . $subscriber_users_id . "', '" . $username . "','" . $full_name . "', '" . $erp_user_id . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
						$full_name = $username = $erp_user_id = "";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$sql_c_up = "UPDATE phone_check_users 	SET enabled		= '1', 
															update_date		= '" . $add_date . "',
															update_by		= '" . $_SESSION['username'] . "',
															update_ip		= '" . $add_ip . "'
								WHERE username	= '" . $username . "'  ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM phone_check_users a WHERE a.username = '" . $username . "' AND a.id != '" . $id . "'";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE phone_check_users SET 	full_name		= '" . $full_name . "', 
																username		= '" . $username . "', 
																erp_user_id		= '" . $erp_user_id . "', 
																update_date		= '" . $add_date . "',
																update_by		= '" . $_SESSION['username'] . "',
																update_ip		= '" . $add_ip . "'
								WHERE id = '" . $id . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
					} else {
						$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$sql_c_up = "UPDATE phone_check_users SET enabled			= '1', 
														update_date		= '" . $add_date . "',
														update_by		= '" . $_SESSION['username'] . "',
														update_ip		= '" . $add_ip . "'
								WHERE username = '" . $username . "'  ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$sql_c_up = "UPDATE phone_check_users 	SET enabled			= '0', 
																update_date		= '" . $add_date . "',
																update_by		= '" . $_SESSION['username'] . "',
																update_ip		= '" . $add_ip . "'
									WHERE id = '" . $id . "' ";
						$db->query($conn, $sql_c_up);
						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				}
			}
		}
	}
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
					<h4 class="card-title">Detail Form</h4><br>
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<div class="row">
							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "username";
								$field_label 	= "PhoneCheck User";
								?>
								<i class="material-icons prefix">description</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "full_name";
								$field_label 	= "User Full Name";
								?>
								<i class="material-icons prefix">description</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m4 s12">
								<?php

								$field_name     = "erp_user_id";
								$field_label    = "ERP User";

								$sql            = " SELECT a.* FROM users a  WHERE 1=1 AND a.enabled = 1 AND a.user_type != 'Admin' "; // echo $sql; 
								$result    		= $db->query($conn, $sql);
								$count       	= $db->counter($result); ?>
								<i class="material-icons prefix pt-1">add_shopping_cart</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
										<option value="">Select</option>
										<?php
										if ($count > 0) {
											$row_r2 = $db->fetch($result);
											foreach ($row_r2 as $data_r2) { ?>
												<option value="<?php echo $data_r2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['id']) { ?> selected="selected" <?php } ?>>
													<?php
													echo $data_r2['first_name'];
													echo $data_r2['middle_name'];
													echo $data_r2['last_name'];
													echo " (" . $data_r2['username'] . ")"; ?>
												</option>
										<?php }
										} ?>
									</select>
									<label for="<?= $field_name; ?>">
										<?= $field_label; ?>
										<span class="color-red">*<?php
																	if (isset($error6[$field_name])) {
																		echo $error6[$field_name];
																	} ?>
										</span>
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
				<?php //include('sub_files/right_sidebar.php');
				?>
			</div>
		</div>


	</div><br><br><br><br>
	<!-- END: Page Main-->