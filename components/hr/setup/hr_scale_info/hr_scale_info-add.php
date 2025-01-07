<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$scale_name			= "scale_name Test";
	$scale_level		= "1";
}
if (!isset($module)) {
	require_once('../../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 		= $_SESSION["subscriber_users_id"];
$user_id 		= $_SESSION["user_id"];
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
if ($cmd == 'edit') {
	$title_heading = "Edit Scale";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Scale";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee 			= "SELECT a.* FROM " . $selected_db_name . ".hr_scales a WHERE a.id = '" . $id . "' 
						AND a.subscriber_users_id = '" . $subscriber_users_id . "'   ";
	$result_ee 			= $db->query($conn, $sql_ee);
	$row_ee 			= $db->fetch($result_ee);
	$scale_name			=  $row_ee[0]['scale_name'];
	$scale_level 		=  $row_ee[0]['scale_level'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit_Scale) && $is_Submit_Scale == 'Y') {
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if (isset($scale_name) && $scale_name == "") {
		$error['msg'] = "Please Enter the Scale Name";
		$scale_name_valid = "invalid";
	}
	if (isset($scale_level) && $scale_level == "") {
		$error['msg'] = "Please Select the Scale Level";
		$scale_level_valid = "invalid";
	}

	if (empty($error)) {
		if ($cmd == 'add') {
			$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".hr_scales a 
									WHERE a.subscriber_users_id 	= '" . $subscriber_users_id . "' 
									AND a.scale_name 			= '" . $scale_name . "'   ";
			$result_ee 			= $db->query($conn, $sql_ee);
			$counter_ee			= $db->counter($result_ee);

			if ($counter_ee == 0) {
				$sql = "INSERT INTO " . $selected_db_name . ".hr_scales(subscriber_users_id, scale_name, scale_level, add_date, add_by, add_ip)
						VALUES('" . $subscriber_users_id . "', '" . $scale_name . "', '" . $scale_level . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				// echo $sql;
				$ok = $db->query($conn, $sql);
				if ($ok) {
					$scale_name = $scale_level = "";
					$msg['msg_success'] = "Scale has been added successfully.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This scale already exist.";
			}
		} else if ($cmd == 'edit') {
			$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".hr_scales a 
									WHERE a.subscriber_users_id 	= '" . $subscriber_users_id . "' 
									AND a.scale_name 			= '" . $scale_name . "'
									AND a.id 				   != '" . $id . "'";
			$result_ee 			= $db->query($conn, $sql_ee);
			$counter_ee			= $db->counter($result_ee);
			if ($counter_ee == 0) {
				$sql_c_up = "UPDATE " . $selected_db_name . ".hr_scales SET scale_name			= '" . $scale_name . "', 
																		scale_level			= '" . $scale_level . "',  
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
			} else {
				$error['msg'] = "This scale already exist.";
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
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">Scale List</a>
								</li>
							</ol>
						</div>
						<div class="col s2 m6 l6">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right"
								href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
								Scale List
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
						<input type="hidden" name="is_Submit_Scale" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																			echo encrypt($_SESSION['csrf_session']);
																		} ?>">
						<div class="row">
							<div class="col m12 s12">
								<div class="input-field">
									<i class="material-icons prefix pt-2">aspect_ratio</i>
									<input id="scale_name" type="text" name="scale_name" value="<?php if (isset($scale_name)) {
																									echo $scale_name;
																								} ?>" required>
									<label for="scale_name">Scale Name</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col m12 s12">
								<div class="input-field">
									<i class="material-icons prefix pt-2">aspect_ratio</i>
									<select id="scale_level" name="scale_level" class="validate <?php if (isset($scale_level_valid)) {
																									echo $scale_level_valid;
																								} ?>">
										<option value="">Select Scale Level</option>
										<?php
										$sql1 		= "	SELECT * FROM scale_levels WHERE enabled = 1  ORDER BY level_name ";
										$result1 	= $db->query($conn, $sql1);
										$count1 	= $db->counter($result1);
										if ($count1 > 0) {
											$row1	= $db->fetch($result1);
											foreach ($row1 as $data) { ?>
												<option value="<?php echo $data['id']; ?>" <?php if (isset($scale_level) && $scale_level == $data['id']) { ?> selected="selected" <?php } ?>><?php echo $data['level_name']; ?></option>
										<?php }
										} ?>
									</select>
								</div>
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