<?php
/*
$title 			= "Education Course	";
$heading 		= "WE PROVIDES";
$desc 			= "Offered chiefly farther of my no colonel shyness. Such on help ye some door if in. Laughter proposal laughing any son law consider.";
$btn1_text 		= "WE PROVIDES";
$btn2_text 		= "WE PROVIDES";
*/
if (!isset($module)) {
	require_once('../../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id	= $_SESSION["subscriber_users_id"];
$user_id 		= $_SESSION["user_id"];
$department_image_old	= "no_image.png";
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
if ($cmd == 'edit') {
	$title_heading = "Edit Department";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Department";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee 			= "SELECT a.* FROM " . $selected_db_name . ".departments a WHERE a.id = '" . $id . "' AND a.subscriber_users_id = '" . $subscriber_users_id . "'   ";
	$result_ee 			= $db->query($conn, $sql_ee);
	$row_ee 			= $db->fetch($result_ee);
	$department_name	=  $row_ee[0]['department_name'];
	$enabled			=  $row_ee[0]['enabled'];
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
	if (isset($department_name) && $department_name == "") {
		$error['msg'] = "Enter department name";
		$title_valid = "invalid";
	} else if (isset($department_name) && (strlen($department_name) > 45 || strlen($department_name) < 5)) {
		$error['msg'] = "Department name should be between 5 to 45 characters";
		$department_name_valid = "invalid";
	}
	if (empty($error)) {
		if ($cmd == 'add') {
			$sql6 = "INSERT INTO " . $selected_db_name . ".departments(subscriber_users_id,department_name, add_date, add_by, add_ip)
					VALUES('" . $subscriber_users_id . "', '" . $department_name . "',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
			// echo $sql6;
			$ok = $db->query($conn, $sql6);
			if ($ok) {
				$department_name = "";
				$msg['msg_success'] = "Department has been saved Successfully.";
			} else {
				$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
			}
		} else if ($cmd == 'edit') {
			$sql_c_up = "UPDATE " . $selected_db_name . ".departments SET 	department_name		= '" . $department_name . "',
																			update_date 		= '" . $add_date . "',
																			update_by 	 		= '" . $_SESSION['username'] . "',
																			update_ip 	 		= '" . $add_ip . "'
						WHERE id = '" . $id . "'  ";
			$ok = $db->query($conn, $sql_c_up);
			if ($ok) {
				$msg['msg_success'] = "Record Updated Successfully.";
			} else {
				$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
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
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">List</a>
								</li>
							</ol>
						</div>
						<div class="col s2 m6 l6">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right"
								href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
								List
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m6 l6">
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
							<div class="input-field col m12 s12">
								<i class="material-icons prefix">subtitles</i>
								<input id="department_name" type="text" name="department_name" value="<?php if (isset($department_name)) {
																											echo $department_name;
																										} ?>" required="" class="validate <?php if (isset($department_name_valid)) {
																																				echo $department_name_valid;
																																			} ?>">
								<label for="department_name">Department name</label>
							</div>
						</div>
						<div class="row">&nbsp;&nbsp;</div>
						<div class="row">
							<div class="input-field col m3 s12"></div>
							<div class="input-field col m6 s12">
								<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?></button>
							</div>
							<div class="input-field col m3 s12"></div>
						</div>
					</form>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->