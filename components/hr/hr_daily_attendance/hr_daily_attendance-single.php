<?php
$att_time_in		= $emp_duty_time_from_1;
$att_time_out		= $emp_duty_time_to_1;
$att_date			= date('d/m/Y');
$is_absent 			= "Present";
if (isset($test_on_local) && $test_on_local == 1) {
	$emp_id 						= "1";
}
if (!isset($module)) {
	require_once '../../conf/functions.php';
	disallow_direct_school_directory_access();
}
$db = new mySqlDB;
$selected_db_name = $_SESSION["db_name"];
$school_admin_id = $_SESSION["school_admin_id"];
$user_id = $_SESSION["user_id"];
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
if ($cmd == 'edit') {
	$title_heading = "Edit Attendance";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading = "Add Attendance";
	$button_val = "Add";
	$id = "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee = "SELECT a.* FROM " . $selected_db_name . ".emp_attendance a WHERE a.id = '" . $id . "' AND a.school_admin_id = '" . $school_admin_id . "'   ";
	$result_ee = $db->query($conn, $sql_ee);
	$row_ee = $db->fetch($result_ee);
	$emp_id 					= $row_ee[0]['emp_id'];
	$att_date 					= str_replace("-", "/", convert_date_display($row_ee[0]['att_date']));
	$att_time_in 				= $row_ee[0]['att_time_in'];
	$att_time_out 				= $row_ee[0]['att_time_out'];
	$is_absent 				= $row_ee[0]['is_absent'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit_Attendance) && $is_Submit_Attendance == 'Y') {
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if (isset($emp_id) && $emp_id == "") {
		$error['msg'] = "Select Employee Name";
		$emp_id_valid = "invalid";
	}
	if (isset($att_date) && $att_date == "") {
		$error['msg'] = "Enter Attendance Date";
		$att_date_valid = "invalid";
	} else {
		$att_date1 	= "0000-00-00";
		$att_date1 = convert_date_mysql_slash($att_date);
	}
	if (isset($att_time_in) && $att_time_in == "") {
		$error['msg'] = "Enter Arrival Time";
		$att_time_in_valid 			= "invalid";
	} else {
		$att_time_in_seconds = strtotime($att_time_in);
	}
	if (isset($att_time_out) && $att_time_out == "") {
		$error['msg'] = "Enter Departure Time";
		$att_time_out_valid 		= "invalid";
	} else {
		$att_time_out_seconds = strtotime($att_time_out);
	}
	if (isset($is_absent) && $is_absent == "") {
		$error['msg'] = "Select Attendance Status";
		$is_absent_valid 			= "invalid";
	}

	if (empty($error)) {
		if ($cmd == 'add') {
			$sql_ee 	= "	SELECT a.* FROM " . $selected_db_name . ".emp_attendance a
							WHERE a.school_admin_id 	= '" . $school_admin_id . "'
							AND a.emp_id 				= '" . $emp_id . "'
							AND a.att_date 				= '" . $att_date1 . "' ";
			$result_ee 	= $db->query($conn, $sql_ee);
			$counter_ee = $db->counter($result_ee);
			if ($counter_ee == 0) {
				$sql = "INSERT INTO " . $selected_db_name . ".emp_attendance(school_admin_id, emp_id, att_date, att_time_in, att_time_in_seconds, att_time_out, att_time_out_seconds, 
																				is_absent, add_date, add_by, add_ip)
									VALUES('" . $school_admin_id . "', '" . $emp_id . "', '" . $att_date1 . "', '" . $att_time_in . "', '" . $att_time_in_seconds . "', '" . $att_time_out . "', '" . $att_time_out_seconds . "', 
													 '" . $is_absent . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				$ok = $db->query($conn, $sql);
				if ($ok) {
					$emp_id = $emp_id = $att_date = $att_time_in = $att_time_out = $is_absent = "";
					$msg['msg_success'] = "Attendance has been added successfully.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This Attendance already exist.";
			}
		} else if ($cmd == 'edit') {
			check_id($db, $conn, $id, "emp_attendance", $school_admin_id, $selected_db_name);
			$sql_ee = "	SELECT a.* FROM " . $selected_db_name . ".emp_attendance a
									WHERE a.school_admin_id 	= '" . $school_admin_id . "'
									AND a.emp_id 				= '" . $emp_id . "'
									AND a.att_date 				= '" . $att_date1 . "'
									AND a.id 				   != '" . $id . "'";
			$result_ee = $db->query($conn, $sql_ee);
			$counter_ee = $db->counter($result_ee);
			if ($counter_ee == 0) {
				$sql_c_up = "UPDATE " . $selected_db_name . ".emp_attendance SET 	
																				emp_id					= '" . $emp_id . "',
																				att_date				= '" . $att_date1 . "',
																				att_time_in				= '" . $att_time_in . "',
																				att_time_in_seconds		= '" . $att_time_in_seconds . "',
																				att_time_out			= '" . $att_time_out . "',
																				att_time_out_seconds	= '" . $att_time_out_seconds . "',
																				is_absent				= '" . $is_absent . "',

																				update_date 			= '" . $add_date . "',
																				update_by 	 			= '" . $_SESSION['username'] . "',
																				update_ip 	 			= '" . $add_ip . "'
							WHERE id = '" . $id . "'  ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This Attendance already exist.";
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
						<input type="hidden" name="is_Submit_Attendance" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) {
																	echo $cmd;
																} ?>" />
						<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																			echo encrypt($_SESSION['csrf_session']);
																		} ?>">
						<div class="row">
							<div class="col m12 s12">
								<label for="emp_id">Employee Details</label>
								<div class="input-field">
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
												<option value="<?php echo $data['id']; ?>" <?php if (isset($emp_id) && $emp_id == $data['id']) { ?> selected="selected" <?php } ?>><?php echo ucwords(strtolower($data['e_full_name'])); ?> => <?php echo $data['designation']; ?> => <?php echo $data['department_name']; ?> => Emp ID: <?php echo $data['id']; ?></option>
										<?php }
										} ?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col m2 s12">
								<div class="input-field">
									<i class="material-icons prefix">date_range</i>
									<input id="att_date" type="text" name="att_date" class="datepicker" value="<?php if (isset($att_date)) {
																													echo $att_date;
																												} ?>" class="validate datepicker <?php if (isset($att_date_valid)) {
																																																echo $att_date_valid;
																																															} ?>" required>
									<label for="att_date">Attendance Date (d/m/Y)</label>
								</div>
							</div>
							<div class="col m2 s12">
								<div class="input-field">
									<i class="material-icons prefix">query_builder</i>
									<input id="att_time_in" type="text" name="att_time_in" value="<?php if (isset($att_time_in)) {
																										echo $att_time_in;
																									} ?>" required>
									<label for="att_time_in">Arrival Time</label>
								</div>
							</div>
							<div class="col m2 s12">
								<div class="input-field">
									<i class="material-icons prefix">query_builder</i>
									<input id="att_time_out" type="text" name="att_time_out" value="<?php if (isset($att_time_out)) {
																										echo $att_time_out;
																									} ?>" required>
									<label for="att_time_out">Departure Time</label>
								</div>
							</div>
							<div class="col m6 s12">
								<div class="input-field">
									<table>
										<tr>
											<td>
												<p>
													<label>
														<input class="with-gap" name="is_absent" value="Present" type="radio" <?php if (isset($is_absent) && $is_absent == "Present") { ?> checked <?php } ?> /><span>Present</span>
													</label>
												</p>
											</td>
											<td>
												<p>
													<label>
														<input class="with-gap" name="is_absent" value="Absent" type="radio" <?php if (isset($is_absent) && $is_absent == "Absent") { ?> checked <?php } ?> /><span>Absent</span>

													</label>
												</p>
											</td>
											<td>
												<p>
													<label>
														<input class="with-gap" name="is_absent" value="Leave" type="radio" <?php if (isset($is_absent) && $is_absent == "Leave") { ?> checked <?php } ?> /><span>Leave</span>
													</label>
												</p>
											</td>
										</tr>
									</table>
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
					</form>
				</div>
				<?php include 'sub_files/right_sidebar.php'; ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->