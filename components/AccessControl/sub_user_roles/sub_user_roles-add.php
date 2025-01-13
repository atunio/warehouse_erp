<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if ($cmd == 'edit') {
	$title_heading = "Edit Sub User Role";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Sub User Role";
	$button_val 	= "Add";
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	if (isset($role_name) && $role_name == "") {
		$error['role_name'] = "Required";
		$role_name_valid = "invalid";
	}
	if (empty($error)) {
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql1 		= " SELECT * FROM sub_users_roles
								WHERE enabled = 1 
								AND role_name = '" . $role_name . "' 
								AND subscriber_users_id = '" . $subscriber_users_id . "' ";
				$result1 	= $db->query($conn, $sql1);
				$count1 	= $db->counter($result1);
				if ($count1 == 0) {
					$sql_c_u = "INSERT INTO sub_users_roles (role_name, subscriber_users_id, add_date, add_by, add_ip)
								VALUES('" . $role_name . "', '" . $subscriber_users_id . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					$ok = $db->query($conn, $sql_c_u);
					if ($ok) {
						$msg['msg_success'] = "Record has been added Successfully.";
						$role_name 					= "";
					} else {
						$error['msg'] = "<span class='color-red'>There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "<span class='color-red'>This record is already exist.";
					$role_name_valid = "invalid";
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				check_id($db, $conn, $id, "sub_users_roles");
				$sql1 		= "	SELECT * FROM sub_users_roles
								WHERE enabled = 1 AND role_name = '" . $role_name . "' 
								AND subscriber_users_id = '" . $subscriber_users_id . "' ";
				$sql1 			.= " AND id != '" . $id . "' "; //echo $sql1;
				$result1 	= $db->query($conn, $sql1);
				$count1 	= $db->counter($result1);
				if ($count1 == 0) {
					$sql_c_up = "UPDATE sub_users_roles SET role_name 			= '" . $role_name . "',
																	update_date 		= '" . $add_date . "',
																	update_by 			= '" . $_SESSION['username'] . "',
																	update_ip 			= '" . $add_ip . "'
								WHERE id = '" . $id . "' AND subscriber_users_id = '" . $subscriber_users_id . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
					} else {
						$error['msg'] = "<span class='color-red'>There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "<span class='color-red'>This record is already exist.";
					$role_name_valid = "invalid";
				}
			}
		}
	}
} else if ($cmd == 'edit' && isset($id)) {
	$sql_ee 	= " SELECT * FROM sub_users_roles 
					WHERE enabled = 1 
					AND id = '" . $id . "' 
					AND subscriber_users_id = '" . $subscriber_users_id . "'  ";
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
					<br>
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />

						<div class="row">
							<div class="input-field col m6 s12">
								<?php
								$field_name		= "role_name";
								$field_label 	= "Role Name";
								?>
								<i class="material-icons prefix pt-1">person_outline</i>
								<input id="<?= $field_name; ?>" name="<?= $field_name; ?>" required="" type="text" value="<?php if (isset(${$field_name})) {
																																echo ${$field_name};
																															} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																						echo ${$field_name . "_valid"};
																																					} ?> ">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m2 s12"><br>
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 custom_btn_size" type="submit" name="action"><?php echo $button_val; ?>
									</button>
								<?php } ?>
							</div>
							<div class="input-field col m5 s12"></div>
						</div>
					</form>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->