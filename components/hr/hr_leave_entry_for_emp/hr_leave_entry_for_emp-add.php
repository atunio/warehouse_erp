<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$emp_id					= "1";
	$leave_from				= date('d/m/Y');
	$leave_to				= date('d/m/Y');
	$leave_type				= "Full Day";
	$leave_category			= "Sick Leave";
	$leave_description		= "Sick Leave";
	$leave_status			= "Approved";
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
	$title_heading = "Edit Leave";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Leave";
	$button_val 	= "Add";
	$id 			= "";
}
$days_deduction_old = 0;
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
$emp_details = "";
$sql1 = "SELECT  a.*, c.department_name, d.designation
		FROM " . $selected_db_name . ".employee_profile a
		INNER JOIN " . $selected_db_name . ".hr_emp_employment_history b ON a.id = b.emp_id  
					AND b.id = ( SELECT MAX(id) FROM " . $selected_db_name . ".hr_emp_employment_history WHERE enabled = 1 AND emp_id = b.emp_id)
		INNER JOIN " . $selected_db_name . ".departments c ON c.id = b.dept_id
		INNER JOIN " . $selected_db_name . ".designations d ON d.id = b.designation_id
		WHERE a.enabled = 1 
		AND a.school_admin_id 	= '" . $school_admin_id . "' 
		AND a.user_id 	= '" . $user_id . "' 
		ORDER BY b.id DESC LIMIT 1";
//echo "-------------------------------------------".$sql1; die;
$result1 	= $db->query($conn, $sql1);
$count1 	= $db->counter($result1);
if ($count1 > 0) {
	$row1	= $db->fetch($result1);
	foreach ($row1 as $data) {
		$emp_id 		= $data['id'];
		$emp_details 	= ucwords(strtolower($data['e_full_name'])) . "=> " . $data['department_name'] . " => Emp ID: " . $data['id'] . " => Leave Balance: " . $data['e_earn_leave'] . " => Sick Leavs: " . $data['e_sick_leave'] . " => Casual Leavs: " . $data['e_casual_leave'];
	}
	if (isset($is_Submit_Leave) && $is_Submit_Leave == 'Y') {
		if (isset($emp_id) && $emp_id == "") {
			$error['msg'] = "Select Employee Name";
			$emp_id_valid 			= "invalid";
		}
		if (isset($leave_type) && $leave_type == "") {
			$error['msg'] = "Select Leave Type";
			$leave_type_valid	 	= "invalid";
		}
		if (isset($leave_category) && $leave_category == "") {
			$error['msg'] = "Select Leave Category";
			$leave_category_valid 	= "invalid";
		}
		if (isset($leave_from) && $leave_from == "") {
			$error['msg'] = "Select Leave Start Date";
			$leave_from_valid 		= "invalid";
		} else {
			$leave_from1 	= "0000-00-00";
			$leave_from1 = convert_date_mysql_slash($leave_from);
		}
		if (isset($leave_to) && $leave_to == "") {
			$error['msg'] = "Select Leave End Date";
			$leave_to_valid = "invalid";
		} else {
			$leave_to1 	= "0000-00-00";
			$leave_to1 = convert_date_mysql_slash($leave_to);
		}
		if (isset($leave_from) && $leave_from != "" && isset($leave_to) && $leave_to != "") {
			if (str_replace("-", "", $leave_from1) > str_replace("-", "", $leave_to1)) {
				$error['msg'] = "Start Date should not be greater than End Date";
				$leave_from_valid = "invalid";
			}
		}
		if (isset($leave_description) && $leave_description == "") {
			$error['msg'] = "Enter Leave Description";
			$leave_description_valid = "invalid";
		}
		if (empty($error)) {
			$days_deduction = 0;
			if ($leave_type == 'Full Day') {
				$days_deduction = 1;
			} else if ($leave_type == 'Half Day') {
				$days_deduction = 0.5;
			} else if ($leave_type == 'Long Leave') {
				$days_deduction = dateDifference($leave_from1, $leave_to1) + 1;
			}

			$emp_sick_leave_balance 	= 0;
			$emp_casual_leave_balance 	= 0;
			$emp_earn_leave_balance 	= 0;
			$deduction_field_old 		= "";
			$sql_emp 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
									WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
									AND a.id 					= '" . $emp_id . "' "; //echo $sql_emp;
			$result_emp 			= $db->query($conn, $sql_emp);
			$counter_emp			= $db->counter($result_emp);
			if ($counter_emp > 0) {
				$row_emp = $db->fetch($result_emp);
				if (isset($leave_category_old) && $leave_category_old == 'Casual Leave') {
					$deduction_field_old 		= "e_casual_leave";
					$emp_casual_leave_balance 	= $row_emp[0]['e_casual_leave'] + $days_deduction_old;
					$emp_sick_leave_balance 	= $row_emp[0]['e_sick_leave'];
				} else if (isset($leave_category_old) && $leave_category_old == 'Sick Leave') {
					$deduction_field_old 		= "e_sick_leave";
					$emp_sick_leave_balance 	= $row_emp[0]['e_sick_leave'] + $days_deduction_old;
					$emp_casual_leave_balance 	= $row_emp[0]['e_casual_leave'];
				} else {
					$emp_casual_leave_balance 	= $row_emp[0]['e_casual_leave'];
					$emp_sick_leave_balance 	= $row_emp[0]['e_sick_leave'];
				}
				$emp_earn_leave_balance 	= $row_emp[0]['e_earn_leave'] + $days_deduction_old;
			}
			if ($leave_category == 'Sick Leave') {
				$deduction_field = "e_sick_leave";
				if ($days_deduction > $emp_sick_leave_balance) {
					$error['msg'] = "Insufficient Sick Leave Balance ";
					$leave_status_valid = "invalid";
				}
			} else if ($leave_category == 'Casual Leave') {
				$deduction_field = "e_casual_leave";
				if ($days_deduction > $emp_casual_leave_balance) {
					$error['msg'] = "Insufficient Casual Leave Balance ";
					$leave_status_valid = "invalid";
				}
			}
			if (empty($error)) {
				if ($cmd == 'add') {
					$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".emp_leave a 
											WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
											AND a.emp_id 				= '" . $emp_id . "' 
											AND a.leave_from 			= '" . $leave_from1 . "' 
											AND a.leave_to 				= '" . $leave_to1 . "' ";
					$result_ee 			= $db->query($conn, $sql_ee);
					$counter_ee			= $db->counter($result_ee);
					if ($counter_ee == 0) {
						$sql = "INSERT INTO " . $selected_db_name . ".emp_leave(school_admin_id, emp_id, leave_type, leave_category, leave_from,
								leave_to, leave_description, leave_status, days_deduction, add_date, add_by, add_ip)
								VALUES('" . $school_admin_id . "', '" . $emp_id . "', '" . $leave_type . "',  '" . $leave_category . "',  '" . $leave_from1 . "', 
									'" . $leave_to1 . "', '" . $leave_description . "', 'Not Approved', '" . $days_deduction . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
						// echo $sql;
						$ok = $db->query($conn, $sql);
						if ($ok) {
							$leave_type = $leave_category = $leave_from = $leave_to = $leave_description = "";
							$msg['msg_success'] = "Leave has been added successfully.";
						} else {
							$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
						}
					} else {
						$error['msg'] = "This record is already exist.";
					}
				} else if ($cmd == 'edit') {
					$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".emp_leave a 
											WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
											AND a.emp_id 				= '" . $emp_id . "' 
											AND a.leave_from 			= '" . $leave_from1 . "' 
											AND a.leave_to 				= '" . $leave_to1 . "' 
											AND a.id 				   != '" . $id . "'";
					$result_ee 			= $db->query($conn, $sql_ee);
					$counter_ee			= $db->counter($result_ee);
					if ($counter_ee == 0) {
						$sql_c_up = "UPDATE " . $selected_db_name . ".emp_leave SET emp_id				= '" . $emp_id . "', 
																				leave_type			= '" . $leave_type . "',
																				leave_category		= '" . $leave_category . "',
																				leave_from			= '" . $leave_from1 . "', 
																				leave_to			= '" . $leave_to1 . "', 
																				leave_description	= '" . $leave_description . "', 
																				days_deduction		= '" . $days_deduction . "',  
																				update_date 		= '" . $add_date . "',
																				update_by 	 		= '" . $_SESSION['username'] . "',
																				update_ip 	 		= '" . $add_ip . "'
									WHERE id = '" . $id . "'  ";
						$ok = $db->query($conn, $sql_c_up);
						if ($ok) {
							$msg_main = "Record Updated Successfully.";
						} else {
							$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
						}
					} else {
						$error['msg'] = "This record is is already exist.";
					}
				}
			}
		}
	}
	if ($cmd == 'edit' && isset($id)) {
		$sql_ee 			= "SELECT a.* FROM " . $selected_db_name . ".emp_leave a WHERE a.id = '" . $id . "' AND a.school_admin_id = '" . $school_admin_id . "'   ";
		$result_ee 			= $db->query($conn, $sql_ee);
		$counter_ee			= $db->counter($result_ee);
		if ($counter_ee > 0) {
			$row_ee 			= $db->fetch($result_ee);
			$emp_id 			=  $row_ee[0]['emp_id'];
			$leave_type			=  $row_ee[0]['leave_type'];
			$leave_category		=  $row_ee[0]['leave_category'];
			$leave_from 		=  str_replace("-", "/", convert_date_display($row_ee[0]['leave_from']));
			$leave_to			=  str_replace("-", "/", convert_date_display($row_ee[0]['leave_to']));
			$leave_description 	=  $row_ee[0]['leave_description'];
			$leave_category_old	=  $row_ee[0]['leave_category'];
			$days_deduction_old	=  $row_ee[0]['days_deduction'];
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
									<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">Leave List</a>
									</li>
								</ol>
							</div>
							<div class="col s2 m6 l6">
								<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right"
									href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
									Leave List
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
						<?php } else if (isset($msg_main) && $msg_main != "") { ?>
							<div class="card-alert card green lighten-5">
								<div class="card-content green-text">
									<p><?php echo $msg_main; ?></p>
								</div>
								<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
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
							<input type="hidden" name="is_Submit_Leave" value="Y" />
							<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
							<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
							<input type="hidden" name="days_deduction_old" value="<?php if (isset($days_deduction_old)) echo $days_deduction_old; ?>" />
							<input type="hidden" name="leave_category_old" value="<?php if (isset($leave_category_old)) echo $leave_category_old; ?>" />
							<div class="row">
								<div class="input-field col m12 s12">
									<i class="material-icons prefix">person_outline</i>
									<input id="leave_description" type="text" readonly value="<?php echo $emp_details; ?>">
									<label for="leave_description">Employee Details</label>
								</div>
							</div>
							<div class="row">
								<div class="col m4 s12">
									<label for="leave_type">Leave Type</label>
									<div class="input-field">
										<i class="material-icons prefix">drive_eta</i>
										<select id="leave_type" name="leave_type" class="validate <?php if (isset($leave_type_valid)) {
																										echo $leave_type_valid;
																									} ?>">
											<option value="">Select Type of Leave</option>
											<option value="Full Day" <?php if (isset($leave_type) && $leave_type == "Full Day") { ?> selected="selected" <?php } ?>>Full Day</option>
											<option value="Half Day" <?php if (isset($leave_type) && $leave_type == "Half Day") { ?> selected="selected" <?php } ?>> Half Day </option>
											<option value="Long Leave" <?php if (isset($leave_type) && $leave_type == "Long Leave") { ?> selected="selected" <?php } ?>> Long Leave </option>
										</select>
									</div>
								</div>
								<div class="col m4 s12">
									<label for="leave_category">Leave Category</label>
									<div class="input-field">
										<i class="material-icons prefix">drive_eta</i>
										<select id="leave_category" name="leave_category" class="validate <?php if (isset($leave_category_valid)) {
																												echo $leave_category_valid;
																											} ?>">
											<option value="">Select Category</option>
											<option value="Sick Leave" <?php if (isset($leave_category) && $leave_category == "Sick Leave") { ?> selected="selected" <?php } ?>>Sick Leave</option>
											<option value="Casual Leave" <?php if (isset($leave_category) && $leave_category == "Casual Leave") { ?> selected="selected" <?php } ?>> Casual Leave </option>
										</select>
									</div>
								</div>
								<div class="col m2 s12">
									<div class="input-field">
										<i class="material-icons prefix">date_range</i>
										<input id="leave_from" type="text" name="leave_from" class="datepicker" value="<?php if (isset($leave_from)) {
																															echo $leave_from;
																														} ?>" required>
										<label for="leave_from">Duration From (d/m/Y)</label>
									</div>
								</div>
								<div class="col m2 s12">
									<div class="input-field">
										<i class="material-icons prefix">date_range</i>
										<input id="leave_to" type="text" name="leave_to" class="datepicker" value="<?php if (isset($leave_to)) {
																														echo $leave_to;
																													} ?>" required>
										<label for="leave_to">Duration To (d/m/Y)</label>
									</div>
								</div>
								<div class="col m12 s12">
									<div class="input-field">
										<i class="material-icons prefix">description</i>
										<input id="leave_description" type="text" name="leave_description" value="<?php if (isset($leave_description)) {
																														echo $leave_description;
																													} ?>" required="" class="validate <?php if (isset($leave_description_valid)) {
																																																						echo $leave_description_valid;
																																																					} ?>">
										<label for="leave_description">Leave Description</label>
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
	<?php } else { ?>
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
										<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">Leave List</a>
										</li>
									</ol>
								</div>
								<div class="col s2 m6 l6">
									<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right"
										href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
										Leave List
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col s12 m12 16">
					<div id="Form-advance" class="card card card-default scrollspy">
						<div class="card-content">
							<div class="card-alert card red lighten-5">
								<div class="card-content red-text">
									<p>Please contact support team, Your profile is incomplete</p>
								</div>
								<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>