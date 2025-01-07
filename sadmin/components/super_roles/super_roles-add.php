<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_sadmin_directory_access();
}
$db = new mySqlDB;
if ($cmd == 'edit') {
	$title_heading = "Edit Super Admin Role";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Super Admin Role";
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
		$error['role_name'] 			= "Required";
	}

	if (empty($error)) {
		if ($cmd == 'add') {
			$sql1 		= " SELECT * FROM super_admin_roles WHERE enabled = 1  AND role_name = '" . $role_name . "'";
			$result1 	= $db->query($conn, $sql1);
			$count1 	= $db->counter($result1);
			if ($count1 == 0) {
				$sql_c_u = "INSERT INTO super_admin_roles (role_name, add_date, add_by, add_ip)
							VALUES('" . $role_name . "', '" . $add_date . "', '" . $_SESSION['username_super_admin'] . "', '" . $add_ip . "')";
				$ok = $db->query($conn, $sql_c_u);
				if ($ok) {
					$msg['msg_success'] = "Record has been added Successfully.";
					$role_name 					= "";
				} else {
					$error['msg'] = "<span class='color-red'>There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "<span class='color-red'>This record is already exist.";
			}
		} else if ($cmd == 'edit') {
			$sql1 		= "	SELECT * FROM super_admin_roles WHERE enabled = 1 AND role_name = '" . $role_name . "' ";
			$sql1 			.= " AND id != '" . $id . "' "; //echo $sql1;
			$result1 	= $db->query($conn, $sql1);
			$count1 	= $db->counter($result1);
			if ($count1 == 0) {
				$sql_c_up = "UPDATE super_admin_roles SET role_name = '" . $role_name . "',
												update_date 		= '" . $add_date . "',
												update_by 			= '" . $_SESSION['username_super_admin'] . "',
												update_ip 			= '" . $add_ip . "'
							WHERE id = '" . $id . "'";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "<span class='color-red'>There is Error, record does not update, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "<span class='color-red'>This record is already exist.";
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
								<input id="role_name" type="text" name="role_name" value="<?php if (isset($role_name)) {
																								echo $role_name;
																							} ?>">
								<label for="role_name">Role Name</label>
							</div>
						</div>
						<div class="row">
							<div class="row">
								<div class="input-field col m6 s12">
									<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
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