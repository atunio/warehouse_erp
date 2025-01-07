<?php
$attendance_date	= date('d/m/Y');
if (isset($test_on_local) && $test_on_local == 1) {;
}
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 				= new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$school_admin_id 	= $_SESSION["school_admin_id"];
$teacher_user_id 	= $_SESSION["user_id"];
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
$title_heading	 	= "Employees Attendance";
$button_val = "Add Attendance";

$result_ee2 	= list_of_employees($db, $conn, $school_admin_id, $selected_db_name);
$count_ee2 		= $db->counter($result_ee2);
if ($count_ee2 > 0) {
	$row_ee2 = $db->fetch($result_ee2);
	foreach ($row_ee2 as $data1) {
		$this_emp_id = $data1['id'];
		$sql_prev1 		= "	SELECT * 
							FROM " . $selected_db_name . ".emp_attendance a
							WHERE a.school_admin_id = '" . $school_admin_id . "'
							AND a.emp_id 			=  '" . $this_emp_id . "'
							AND a.att_date 			=  '" . date('Y-m-d') . "' ";  //echo $sql_prev;
		$result_prev1 	= $db->query($conn, $sql_prev1);
		$count_prev1 	= $db->counter($result_prev1);
		if ($count_prev1 > 0) {
			$row_prev1 = $db->fetch($result_prev1);
			${"attendance_" . $this_emp_id} 	= $row_prev1[0]['is_absent'];
			${"att_time_in-" . $this_emp_id} 	= $row_prev1[0]['att_time_in'];
			${"att_time_out-" . $this_emp_id} = $row_prev1[0]['att_time_out'];
		} else {
			${"attendance_" . $this_emp_id} = "Present";
			${"att_time_in-" . $this_emp_id} = $emp_duty_time_from_1;
			${"att_time_out-" . $this_emp_id} = $emp_duty_time_to_1;
		}
	}
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') { // 
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if (isset($attendance_date) && $attendance_date == "") {
		$error['msg'] = "Enter Attendance Date";
		$attendance_date_valid = "invalid";
	} else {
		$attendance_date1 	= "0000-00-00";
		$attendance_date1 	= convert_date_mysql_slash($attendance_date);
	}
	if ($count_ee2 > 0) {
		foreach ($row_ee2 as $data2) {
			if (isset(${"attendance_" . $data2['id']}) && ${"attendance_" . $data2['id']} == "") {
				$error['msg'] = "Please Select attendance for all Employees.";
			} else if (!isset(${"attendance_" . $data2['id']})) {
				$error['msg'] = "Please Select attendance for all Employees.";
			}
			if (isset(${"att_time_in-" . $data2['id'] . ""}) && ${"att_time_in-" . $data2['id']} == "") {
				$error['msg'] = "Please enter Time In for all Employees.";
			}
			if (isset(${"att_time_out-" . $data2['id'] . ""}) && ${"att_time_out-" . $data2['id']} == "") {
				$error['msg'] = "Please enter Time Out for all Employees.";
			}
		}
	}
	if (empty($error)) {
		$k = 0;
		if ($count_ee2 > 0) {
			foreach ($row_ee2 as $data3) {
				$this_emp_id = $data3['id'];
				$attendance = ${"attendance_" . $this_emp_id};
				$att_time_in = ${"att_time_in-" . $this_emp_id};
				$att_time_out = ${"att_time_out-" . $this_emp_id};

				$att_time_in_seconds = strtotime($att_time_in);
				$att_time_out_seconds = strtotime($att_time_out);

				$sql_prev 		= "	SELECT * 
								FROM " . $selected_db_name . ".emp_attendance a
								WHERE a.school_admin_id = '" . $school_admin_id . "'
								AND a.emp_id 			=  '" . $this_emp_id . "'
								AND a.att_date 			=  '" . $attendance_date1 . "' ";  //echo $sql_prev;
				$result_prev 	= $db->query($conn, $sql_prev);
				$count_prev 	= $db->counter($result_prev);
				if ($count_prev > 0) {
					$sql = " DELETE FROM " . $selected_db_name . ".emp_attendance 
						WHERE school_admin_id 	= '" . $school_admin_id . "' 
						AND emp_id 				= '" . $this_emp_id . "' 
						AND att_date			= '" . $attendance_date1 . "' ";
					$db->query($conn, $sql);
				}
				$sql = "INSERT INTO " . $selected_db_name . ".emp_attendance(school_admin_id, emp_id, att_date, att_time_in, att_time_in_seconds, att_time_out, att_time_out_seconds, 
																		is_absent, add_date, add_by, add_ip)
							VALUES('" . $school_admin_id . "', '" . $this_emp_id . "', '" . $attendance_date1 . "', '" . $att_time_in . "', '" . $att_time_in_seconds . "', '" . $att_time_out . "', '" . $att_time_out_seconds . "', 
											 '" . $attendance . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				$ok = $db->query($conn, $sql);
				$k++;
			}
			if ($k > 0) {
				$msg['msg_success'] = $k . " Employees Attendance have been added Successfully.";
			}
		}
	}
} ?>
<!-- BEGIN: Page Main-->
<style>
	.dropdown-trigger {
		font-weight: bold;
	}
</style>
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
							<li class="breadcrumb-item"><?php echo $title_heading; ?></li>
							<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">Back To Attendance</a>
							</li>
						</ol>
					</div>
					<div class="col s2 m6 l6">
						<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
							Back To Attendance
						</a>
					</div>
				</div>
			</div>
		</div>
		<form method="post" autocomplete="off">
			<input type="hidden" name="is_Submit" value="Y" />
			<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
			<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																echo encrypt($_SESSION['csrf_session']);
															} ?>">
			<div class="col s12">
				<div class="container">
					<!-- users view start -->
					<div class="section users-view">
						<!-- users view card data start -->
						<div class="card">
							<div class="card-content">
								<div class="row">
									<div class="col s12 m12"><?php
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
										<table class="striped">
											<tbody>
												<tr>
													<td>Attendance Date</td>
													<td colspan="3">
														<div class="row">
															<div class="input-field col m4 s12">
																<input id="attendance_date" readonly type="text" name="attendance_date" value="<?php if (isset($attendance_date)) {
																																					echo $attendance_date;
																																				} ?>" required="" class="validate datepicker <?php if (isset($attendance_date_valid)) {
																																																	echo $attendance_date_valid;
																																																} ?>">
																<label for="test_exam_date">Attendance Date (d/m/Y)</label>
															</div>
														</div>
													</td>
												</tr>
												<?php
												$k = 1;
												// $sql_ee2='';
												if ($count_ee2 > 0) {
													foreach ($row_ee2 as $data) {
														$employee_full_name = $data['e_full_name'];
												?>
														<tr>
															<td style="width: 50%; padding: 0px 5px; color: #000;">(<?php echo $k . ").&nbsp;&nbsp;" . ucwords(strtolower($employee_full_name)); ?> => <b>Emp ID:</b> <?php echo $data['id']; ?> => <b>Emp Code:</b> <?php echo $data['emp_code']; ?> => <?php echo $data['department_name']; ?> </td>
															<td style="width: 25%; padding: 0px 5px;">
																<table>
																	<tr>
																		<td>
																			<p>
																				<label>
																					<input class="with-gap" name="attendance_<?php echo $data['id'] ?>" value="Present" type="radio" <?php if (isset(${"attendance_" . $data['id']}) && ${"attendance_" . $data['id']} == 'Present') {
																																															echo "checked";
																																														} ?> /><span>Present</span>
																				</label>
																			</p>
																		</td>
																		<td>
																			<p>
																				<label>
																					<input class="with-gap" name="attendance_<?php echo $data['id'] ?>" value="Absent" type="radio" <?php if (isset(${"attendance_" . $data['id']}) && ${"attendance_" . $data['id']} == 'Absent') {
																																														echo "checked";
																																													} ?> /><span>Absent</span>
																				</label>
																			</p>
																		</td>
																		<td>
																			<p>
																				<label>
																					<input class="with-gap" name="attendance_<?php echo $data['id'] ?>" value="Leave" type="radio" <?php if (isset(${"attendance_" . $data['id']}) && ${"attendance_" . $data['id']} == 'Leave') {
																																														echo "checked";
																																													} ?> /><span>Leave</span>
																				</label>
																			</p>
																		</td>
																	</tr>
																</table>
															</td>
															<td style="width: 12.5%; padding: 0px 5px;">
																<table>
																	<tr>
																		<td>
																			<label for="att_time_in-<?php echo $data['id'] ?>">Arrival Time</label>
																			<input class="timepicker" id="att_time_in-<?php echo $data['id'] ?>" type="text" name="att_time_in-<?php echo $data['id'] ?>" value="<?php if (isset(${"att_time_in-" . $data['id']})) {
																																																						echo ${"att_time_in-" . $data['id']};
																																																					} ?>" required>
																		</td>
																	</tr>
																</table>
															</td>
															<td style="width: 12.5%; padding: 0px 5px;">
																<table>
																	<tr>
																		<td>
																			<label for="att_time_out-<?php echo $data['id'] ?>">Departure Time</label>
																			<input class="timepicker" id="att_time_out-<?php echo $data['id'] ?>" type="text" name="att_time_out-<?php echo $data['id'] ?>" value="<?php if (isset(${"att_time_out-" . $data['id']})) {
																																																						echo ${"att_time_out-" . $data['id']};
																																																					} ?>" required>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
														<input type="hidden" name="all_emp_ids[]" value="<?php echo $data['id']; ?>">
												<?php
														$k++;
													}
												} ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="input-field col m6 s12">
										<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
											<i class="material-icons right">send</i>
										</button>
									</div>
								</div>
							</div>
						</div>
						<!-- users view card data ends -->
					</div>
					<!-- users view ends -->
					<!-- START RIGHT SIDEBAR NAV -->
				</div>
			</div>
		</form>
		<div class="content-overlay"></div>
		<?php include('sub_files/right_sidebar.php'); ?>
	</div>
</div><br><br>
<!-- END: Page Main-->