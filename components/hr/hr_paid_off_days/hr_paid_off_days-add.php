<?php
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$school_admin_id 		= $_SESSION["school_admin_id"];
$user_id 		= $_SESSION["user_id"];
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
if ($cmd == 'edit') {
	$title_heading = "Edit Paid Off Day";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Paid Off Day";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee 			= "SELECT a.* FROM " . $selected_db_name . ".off_days_school a WHERE a.id = '" . $id . "' AND a.school_admin_id = '" . $school_admin_id . "'   ";
	$result_ee 			= $db->query($conn, $sql_ee);
	$row_ee 			= $db->fetch($result_ee);
	$off_day_name 		=  $row_ee[0]['off_day_name'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if (isset($off_day_name) && $off_day_name == "") {
		$error['msg'] = "Enter Paid Off Day";
		$off_day_name_valid = "invalid";
	}
	if (empty($error)) {
		if ($cmd == 'add') {
			$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".off_days_school a 
									WHERE a.school_admin_id = '" . $school_admin_id . "' 
									AND a.off_day_name 		= '" . $off_day_name . "'   ";
			$result_ee 			= $db->query($conn, $sql_ee);
			$counter_ee			= $db->counter($result_ee);
			if ($counter_ee == 0) {
				$sql = "INSERT INTO " . $selected_db_name . ".off_days_school(school_admin_id, off_day_name, add_date, add_by, add_ip)
						VALUES('" . $school_admin_id . "', '" . $off_day_name . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				// echo $sql;
				$ok = $db->query($conn, $sql);
				if ($ok) {
					$off_day_name = "";
					$msg['msg_success'] = "Paid Off Day has been added successfully.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This Paid Off Day is already exist.";
			}
		} else if ($cmd == 'edit') {
			$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".off_days_school a 
									WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
									AND a.off_day_name 			= '" . $off_day_name . "'
									AND a.id 				   != '" . $id . "'";
			$result_ee 			= $db->query($conn, $sql_ee);
			$counter_ee			= $db->counter($result_ee);
			if ($counter_ee == 0) {
				$sql_c_up = "UPDATE " . $selected_db_name . ".off_days_school SET	off_day_name		= '" . $off_day_name . "', 
																				update_date 		= '" . $add_date . "',
																				update_by 	 		= '" . $_SESSION['username'] . "',
																				update_ip 	 		= '" . $add_ip . "'
							WHERE id = '" . $id . "' AND school_admin_id = '" . $school_admin_id . "' ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This Paid Off Day is already exist.";
			}
		}
	}
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
					<form method="post" autocomplete="off" enctype="multipart/form-data">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																			echo encrypt($_SESSION['csrf_session']);
																		} ?>">
						<div class="row">
							<label for="off_day_name">Paid Off Day</label>
							<div class="input-field col m12 s12">
								<i class="material-icons prefix">book</i>
								<select id="off_day_name" name="off_day_name" class="validate <?php if (isset($off_day_name_valid)) {
																									echo $off_day_name_valid;
																								} ?>">
									<option value="">Select Paid Off Day</option>
									<option value="Sunday" <?php if (isset($off_day_name) && $off_day_name == 'Sunday') { ?> selected="selected" <?php } ?>>Sunday</option>
									<option value="Saturday" <?php if (isset($off_day_name) && $off_day_name == 'Saturday') { ?> selected="selected" <?php } ?>>Saturday</option>
									<option value="Friday" <?php if (isset($off_day_name) && $off_day_name == 'Friday') { ?> selected="selected" <?php } ?>>Friday</option>
									<option value="Thursday" <?php if (isset($off_day_name) && $off_day_name == 'Thursday') { ?> selected="selected" <?php } ?>>Thursday</option>
									<option value="Wednesday" <?php if (isset($off_day_name) && $off_day_name == 'Wednesday') { ?> selected="selected" <?php } ?>>Wednesday</option>
									<option value="Tuesday" <?php if (isset($off_day_name) && $off_day_name == 'Tuesday') { ?> selected="selected" <?php } ?>>Tuesday</option>
									<option value="Monday" <?php if (isset($off_day_name) && $off_day_name == 'Monday') { ?> selected="selected" <?php } ?>>Monday</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="row">&nbsp;&nbsp;</div>
							<div class="row">
								<div class="input-field col m4 s12"></div>
								<div class="input-field col m4 s12">
									<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?></button>
								</div>
								<div class="input-field col m4 s12"></div>
							</div>
						</div>
					</form>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->