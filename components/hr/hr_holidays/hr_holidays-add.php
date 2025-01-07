<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$holiday_name			= "Approved";
	$holiday_from			= "28/10/2021";
	$holiday_to				= "28/10/2021";
	$hol_description		= "hol_description";
}
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$school_admin_id 		= $_SESSION["school_admin_id"];
$user_id 		= $_SESSION["user_id"];
if ($cmd == 'edit') {
	$title_heading = "Edit Holiday";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Holiday";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee 			= "SELECT a.* FROM " . $selected_db_name . ".emp_holiday a WHERE a.id = '" . $id . "' AND a.school_admin_id = '" . $school_admin_id . "'   ";
	$result_ee 			= $db->query($conn, $sql_ee);
	$row_ee 			= $db->fetch($result_ee);
	$holiday_name 		=  $row_ee[0]['holiday_name'];
	$holiday_from		=  str_replace("-", "/", convert_date_display($row_ee[0]['holiday_from']));
	$holiday_to 		=  str_replace("-", "/", convert_date_display($row_ee[0]['holiday_to']));
	$hol_description	=  $row_ee[0]['hol_description'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit_Holiday) && $is_Submit_Holiday == 'Y') {
	if (isset($holiday_name) && $holiday_name == "") {
		$error['msg'] = "Enter Holiday Name";
		$holiday_name_valid = "invalid";
	}
	if (isset($holiday_from) && $holiday_from == "") {
		$error['msg'] = "Enter Beginning Date of Holiday";
		$holiday_from_valid = "invalid";
	} else {
		$holiday_from1 	= "0000-00-00";
		$holiday_from1 = convert_date_mysql_slash($holiday_from);
	}
	if (isset($holiday_to) && $holiday_to == "") {
		$error['msg'] = "Enter End Date of Holiday";
		$holiday_to_valid = "invalid";
	} else {
		$holiday_to1 	= "0000-00-00";
		$holiday_to1 = convert_date_mysql_slash($holiday_to);
	}
	if (isset($hol_description) && $hol_description == "") {
		$error['msg'] = "Enter Holiday Description";
		$hol_description_valid = "invalid";
	}

	if (empty($error)) {
		if ($cmd == 'add') {
			$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".emp_holiday a 
									WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
									AND a.holiday_name 			= '" . $holiday_name . "'   ";
			$result_ee 			= $db->query($conn, $sql_ee);
			$counter_ee			= $db->counter($result_ee);
			if ($counter_ee == 0) {
				$sql = "INSERT INTO " . $selected_db_name . ".emp_holiday(school_admin_id, holiday_name, holiday_from, holiday_to, hol_description,
								 add_date, add_by, add_ip)
								VALUES('" . $school_admin_id . "', '" . $holiday_name . "', '" . $holiday_from1 . "', '" . $holiday_to1 . "', '" . $hol_description . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";

				$ok = $db->query($conn, $sql);
				if ($ok) {
					$holiday_name = $holiday_from = $holiday_from = $holiday_from = "";
					$msg['msg_success'] = "Holiday has been added successfully.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This Holiday already exist.";
			}
		} else if ($cmd == 'edit') {
			$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".emp_holiday a 
									WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
									AND a.holiday_name 			= '" . $holiday_name . "'
									AND a.id 				   != '" . $id . "'";
			$result_ee 			= $db->query($conn, $sql_ee);
			$counter_ee			= $db->counter($result_ee);
			if ($counter_ee == 0) {
				$sql_c_up = "UPDATE " . $selected_db_name . ".emp_holiday SET 	holiday_name		= '" . $holiday_name . "', 
																			holiday_from		= '" . $holiday_from1 . "', 
																			holiday_to			= '" . $holiday_to1 . "', 
																			hol_description		= '" . $hol_description . "', 
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
				$error['msg'] = "This Holiday already exist.";
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
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">Holiday List</a>
								</li>
							</ol>
						</div>
						<div class="col s2 m6 l6">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right"
								href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
								Holiday List
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
					<form method="post" autocomplete="off" enctype="multipart/form-data">
						<input type="hidden" name="is_Submit_Holiday" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<div class="row">
							<div class="row">
								<div class="input-field col m6 s6">
									<i class="material-icons prefix">flight_takeoff</i>
									<input id="holiday_name" type="text" name="holiday_name" value="<?php if (isset($holiday_name)) {
																										echo $holiday_name;
																									} ?>" required="" class="validate <?php if (isset($holiday_name_valid)) {
																																																echo $holiday_name_valid;
																																															} ?>">
									<label for="holiday_name">Holiday Name</label>
								</div>
								<div class="col m3 s12">
									<div class="input-field">
										<i class="material-icons prefix">date_range</i>
										<input id="holiday_from" type="text" name="holiday_from" class="datepicker" value="<?php if (isset($holiday_from)) {
																																echo $holiday_from;
																															} ?>" required>
										<label for="holiday_from">Duration From (d/m/Y)</label>
									</div>
								</div>
								<div class="col m3 s12">
									<div class="input-field">
										<i class="material-icons prefix">date_range</i>
										<input id="holiday_to" type="text" name="holiday_to" class="datepicker" value="<?php if (isset($holiday_to)) {
																															echo $holiday_to;
																														} ?>" required>
										<label for="holiday_to">Duration To (d/m/Y)</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="input-field col m12 s6">
									<i class="material-icons prefix">description</i>
									<input id="hol_description" type="text" name="hol_description" value="<?php if (isset($hol_description)) {
																												echo $hol_description;
																											} ?>" required="" class="validate <?php if (isset($hol_description_valid)) {
																																																			echo $hol_description_valid;
																																																		} ?>">
									<label for="hol_description">Holiday Description</label>
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