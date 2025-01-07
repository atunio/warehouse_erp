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
		<div class="col s12 m6 16">
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
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="role_name" type="text" name="role_name" value="<?php if (isset($role_name)) {
																								echo $role_name;
																							} ?>" class="validate <?php if (isset($role_name_valid)) {
																														echo $role_name_valid;
																													} ?>">
								<label for="role_name">Role Name</label>
							</div>
						</div>
						<div class="row">
							<div class="row">
								<div class="input-field col m12 s12">
									<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
										<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?>
										</button>
									<?php } ?>
								</div>
							</div>
						</div>
					</form>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->