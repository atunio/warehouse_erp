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
	if (isset($leave_status) && $leave_status == "") {
		$error['msg'] = "Select Leave Status";
		$leave_status_valid = "invalid";
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
		if ($leave_status != 'Approved') {
			$days_deduction = 0;
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
			// echo "---------------------------------------------:   ".$emp_casual_leave_balance;
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
								'" . $leave_to1 . "', '" . $leave_description . "', '" . $leave_status . "', '" . $days_deduction . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					// echo $sql;
					$ok = $db->query($conn, $sql);
					if ($ok) {
						$sql_emp2 			= "	UPDATE " . $selected_db_name . ".employee_profile SET " . $deduction_field . " 	= " . $deduction_field . "-" . $days_deduction . ",
																									e_earn_leave 		= e_earn_leave-" . $days_deduction . "
												WHERE school_admin_id 	= '" . $school_admin_id . "' 
												AND id					= '" . $emp_id . "' "; //echo $sql_emp2; 
						$db->query($conn, $sql_emp2);
						$emp_id = $leave_type = $leave_category = $leave_from = $leave_to = $leave_description = $leave_status = "";
						$msg['msg_success'] = "Employee Leave has been added successfully.";
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
																			leave_status		= '" . $leave_status . "',  
																			days_deduction		= '" . $days_deduction . "',  
																			update_date 		= '" . $add_date . "',
																			update_by 	 		= '" . $_SESSION['username'] . "',
																			update_ip 	 		= '" . $add_ip . "'
								WHERE id = '" . $id . "'  ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						if ($leave_status_old == 'Approved' && $leave_status == 'Approved') {
							if ($leave_category_old == $leave_category) {
								$sql_emp2	= "	UPDATE " . $selected_db_name . ".employee_profile SET " . $deduction_field . "	= (" . $deduction_field . "+" . $days_deduction_old . ")-" . $days_deduction . ",
																									e_earn_leave 		= (e_earn_leave+" . $days_deduction_old . ")-" . $days_deduction . "
												WHERE school_admin_id 	= '" . $school_admin_id . "' 
												AND id					= '" . $emp_id . "' "; //echo $sql_emp2;
							} else {
								$sql_emp2 			= "	UPDATE " . $selected_db_name . ".employee_profile SET " . $deduction_field . " 	= " . $deduction_field . "-" . $days_deduction . ",
																											e_earn_leave 		= (e_earn_leave+" . $days_deduction_old . ")-" . $days_deduction;
								if ($deduction_field_old != "") {
									$sql_emp2 .= ", " . $deduction_field_old . " = " . $deduction_field_old . "+" . $days_deduction_old;
								}
								$sql_emp2 .= " WHERE school_admin_id = '" . $school_admin_id . "'  AND id = '" . $emp_id . "' "; //echo $sql_emp2;
							}
							$db->query($conn, $sql_emp2);
						} else if ($leave_status_old == 'Approved' && $leave_status == 'Not Approved') {
							if ($leave_category_old == $leave_category) {
								$sql_emp2	= "	UPDATE " . $selected_db_name . ".employee_profile SET " . $deduction_field . "	= (" . $deduction_field . "+" . $days_deduction_old . "),
																									e_earn_leave 		= (e_earn_leave+" . $days_deduction_old . ")
												WHERE school_admin_id 	= '" . $school_admin_id . "' 
												AND id					= '" . $emp_id . "' "; //echo $sql_emp2;
							} else {
								$sql_emp2 			= "	UPDATE " . $selected_db_name . ".employee_profile SET " . $deduction_field . " 	= " . $deduction_field . ",
																											e_earn_leave 		= (e_earn_leave+" . $days_deduction_old . ")";
								if ($deduction_field_old != "") {
									$sql_emp2 .= ", " . $deduction_field_old . " = " . $deduction_field_old . "+" . $days_deduction_old;
								}
								$sql_emp2 .= " WHERE school_admin_id = '" . $school_admin_id . "'  AND id = '" . $emp_id . "' "; //echo $sql_emp2;
							}
							$db->query($conn, $sql_emp2);
						} else if ($leave_status_old == 'Not Approved' && $leave_status == 'Approved') {
							if ($leave_category_old == $leave_category) {
								$sql_emp2	= "	UPDATE " . $selected_db_name . ".employee_profile SET " . $deduction_field . "	= (" . $deduction_field . ")-" . $days_deduction . ",
																									e_earn_leave 		= (e_earn_leave)-" . $days_deduction . "
												WHERE school_admin_id 	= '" . $school_admin_id . "' 
												AND id					= '" . $emp_id . "' "; //echo $sql_emp2;
							} else {
								$sql_emp2 			= "	UPDATE " . $selected_db_name . ".employee_profile SET " . $deduction_field . " 	= " . $deduction_field . "-" . $days_deduction . ",
																											e_earn_leave 		= (e_earn_leave)-" . $days_deduction;
								$sql_emp2 .= " WHERE school_admin_id = '" . $school_admin_id . "'  AND id = '" . $emp_id . "' "; //echo $sql_emp2;
							}
							$db->query($conn, $sql_emp2);
						} else if ($leave_status_old == 'Not Approved' && $leave_status == 'Not Approved') {;
						}
						$msg_main = "Record Updated Successfully.";
						header("location: home?string=" . encrypt("&module=" . $module . "&page=add&cmd=edit&id=" . $id . "&msg_main=" . $msg_main));
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
		$leave_status		=  $row_ee[0]['leave_status'];
		$leave_status_old	=  $leave_status;
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
						<input type="hidden" name="days_deduction_old" value="<?php if (isset($days_deduction_old)) echo $days_deduction_old; ?>" />
						<input type="hidden" name="leave_category_old" value="<?php if (isset($leave_category_old)) echo $leave_category_old; ?>" />
						<input type="hidden" name="leave_status_old" value="<?php if (isset($leave_status_old)) echo $leave_status_old; ?>" />

						<div class="row">
							<label for="leave_category">Employee Details</label>
							<div class="input-field col m12 s12">
								<i class="material-icons prefix">person_outline</i>
								<?php $result1 	= list_of_employees($db, $conn, $school_admin_id, $selected_db_name); ?>
								<select id="emp_id" name="emp_id" class="validate select2 browser-default select2-hidden-accessible <?php if (isset($emp_id_valid)) {
																																		echo $emp_id_valid;
																																	} ?>">
									<option value="">Select Employee Name</option>
									<?php
									$count1 	= $db->counter($result1);
									if ($count1 > 0) {
										$row1	= $db->fetch($result1);
										foreach ($row1 as $data) { ?>
											<option value="<?php echo $data['id']; ?>" <?php if (isset($emp_id) && $emp_id == $data['id']) { ?> selected="selected" <?php } ?>><?php echo ucwords(strtolower($data['e_full_name'])); ?> => <?php echo $data['department_name']; ?> => Emp ID: <?php echo $data['id']; ?> => Leave Balance: <?php echo $data['e_earn_leave']; ?> => Sick Leavs: <?php echo $data['e_sick_leave']; ?> => Casual Leavs: <?php echo $data['e_casual_leave']; ?></option>
									<?php }
									} ?>
								</select>
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
							<div class="col m4 s12">
								<label for="leave_category">Status</label>
								<div class="input-field">
									<i class="material-icons prefix pt-2">done</i>
									<select id="leave_status" name="leave_status" class="validate <?php if (isset($leave_status_valid)) {
																										echo $leave_status_valid;
																									} ?>">
										<option value="">Select Status</option>
										<option value="Approved" <?php if (isset($leave_status) && $leave_status == "Approved") { ?> selected="selected" <?php } ?>>Approved</option>
										<option value="Not Approved" <?php if (isset($leave_status) && $leave_status == "Not Approved") { ?> selected="selected" <?php } ?>>Not Approved</option>
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
							<div class="col m8 s12">
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