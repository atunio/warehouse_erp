<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$process_year		= "2021";
	$process_month		= "11";
}
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db = new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$school_admin_id 	= $_SESSION["school_admin_id"];
$user_id 	= $_SESSION["user_id"];
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
extract($_POST);
$count_cl = 0;
if (isset($is_Submit) && $is_Submit == "Y") {
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if ($process_year == "" && $process_month == "") {
		$error['msg'] = "Please select Salary Year & Month";
	}
	if (empty($error)) {
		$sql_cl = "	SELECT a.process_year, a.process_month, a.total_days_in_month, a1.e_full_name, a1.emp_code, c.department_name, d.designation, b.*
					FROM " . $selected_db_name . ".hr_payroll a
					INNER JOIN " . $selected_db_name . ".hr_payroll_detail b ON b.payroll_id = a.id
					INNER JOIN " . $selected_db_name . ".employee_profile a1 ON a1.id = b.emp_id
					INNER JOIN " . $selected_db_name . ".hr_emp_employment_history b2 ON a1.id = b2.emp_id  
								AND b2.id = ( SELECT MAX(id) FROM " . $selected_db_name . ".hr_emp_employment_history WHERE enabled = 1 AND emp_id = b2.emp_id)
					INNER JOIN " . $selected_db_name . ".departments c ON c.id = b2.dept_id
					INNER JOIN " . $selected_db_name . ".designations d ON d.id = b2.designation_id
					WHERE a.enabled = 1 AND a1.enabled = 1 
					AND a1.emp_status = 'Active'
					AND a.school_admin_id = '" . $school_admin_id . "' AND b.school_admin_id = '" . $school_admin_id . "' ";
		if ($process_year != "") {
			$sql_cl .= "AND a.process_year = '" . $process_year . "' ";
		}
		if ($process_month != "") {
			$sql_cl .=  "AND a.process_month = '" . $process_month . "' ";
		}
		$sql_cl .= " ORDER BY b.enabled DESC, a.process_year DESC, a.process_month, c.department_name, d.designation, a1.e_full_name ";
		//echo $sql_cl;
		$result_cl 		= $db->query($conn, $sql_cl);
		$count_cl 		= $db->counter($result_cl);
		if ($count_cl == 0) {
			$error['msg'] = "No record found";
		}
	}
}
$page_heading = "Monthly Deduction Process ";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="col s10 m6 l6">
						<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $page_heading; ?></span></h5>
						<ol class="breadcrumbs mb-0">
							<li class="breadcrumb-item"><a href="home">Home</a></li>
							</li>
							<li class="breadcrumb-item active">List</li>
						</ol>
					</div>
					<div class="col s2 m6 l6">
						<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&page=add&cmd=add") ?>" data-target="dropdown1">
							Add New
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12">
			<div class="container">
				<div class="section section-data-tables">
					<!-- Page Length Options -->
					<div class="row">
						<div class="col s12">
							<div class="card">
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
									<h4 class="card-title"><?php echo $page_heading; ?></h4>
									<form method="post" autocomplete="off" enctype="multipart/form-data">
										<input type="hidden" name="is_Submit" value="Y" />
										<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<div class="row">
											<div class="input-field col m3 s12">
												<select id="process_year" name="process_year" class="validate select2 browser-default select2-hidden-accessible <?php if (isset($process_year_valid)) {
																																									echo $process_year_valid;
																																								} ?>">
													<option value="">Select Salary Year</option>
													<?php
													for ($i = date('Y'); $i >= 2021; $i--) { ?>
														<option value="<?php echo $i; ?>" <?php if (isset($process_year) && $process_year == $i) { ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="input-field col m3 s12">
												<i class="material-icons prefix">date_range</i>
												<select id="process_month" name="process_month" class="validate <?php if (isset($process_month_valid)) {
																													echo $process_month_valid;
																												} ?>">
													<option value="">Select Salary Month</option>
													<?php
													for ($i = 1; $i < 13; $i++) {
														if ($i <= 9) {
															$i = "0" . $i;
														} ?>
														<option value="<?php echo $i; ?>" <?php if (isset($process_month) && $process_month == $i) { ?> selected="selected" <?php } ?>><?php echo convert_month_letter($i); ?></option>
													<?php } ?>
												</select>
												<label for="process_month">Salary Month</label>
											</div>
											<div class="input-field col m1 s12">
												<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button>
											</div>
										</div>
									</form>
									<div class="col m12 s12"></div>
									<div class="row">
										<div class="col s12">
											<?php
											if ($count_cl > 0) { ?>
												<table id="page-length-option" class="display">
													<thead>
														<tr>
															<th width="5%">S.No</th>
															<th>Year</th>
															<th>Month</th>
															<th>Total Days</th>
															<th>Department</th>
															<th>EmpName</th>
															<th>EmpID</th>
															<th>EmpCode</th>
															<th>Designation</th>
															<th>Actions</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$i = 0;
														$row_cl = $db->fetch($result_cl);
														foreach ($row_cl as $data) {
															$id	= $data['id']; ?>
															<tr data-id="<?php echo $id; ?>">
																<td><?php echo $i + 1; ?></td>
																<td><?php echo $data['process_year']; ?></td>
																<td><?php echo convert_month_letter($data['process_month']); ?></td>
																<td><?php echo $data['total_days_in_month']; ?></td>
																<td><?php echo $data['department_name']; ?></td>
																<td><?php echo $data['e_full_name']; ?></td>
																<td><?php echo $data['emp_id']; ?></td>
																<td><?php echo $data['emp_code']; ?></td>
																<td><?php echo $data['designation']; ?></td>
																<td class="text-align-center">
																	<a href="javascript:void(0)" class="<?php if ($data['enabled'] == '1') { ?>green-text<?php } else { ?>red-text<?php } ?>" onclick="change_status(this,'<?php echo $id ?>')"><?php echo ($data['enabled'] == '1') ? 'Enable' : 'Disable'; ?></a>
																	&nbsp;&nbsp;
																	<a class="waves-effect waves-light green darken-1  btn gradient-45deg-light-green-cyan box-shadow-none border-round mr-1 mb-1" title="Students" href="?string=<?php echo encrypt("module=" . $module . "&page=update&cmd=edit&id=" . $data['id']) ?>">
																		<i class="material-icons dp48">remove_red_eye</i>
																	</a> &nbsp;&nbsp;
																</td>
															</tr>
														<?php
															$i++;
														} ?>
													<tfoot>
														<tr>
															<th width="5%">S.No</th>
															<th>Year</th>
															<th>Month</th>
															<th>Total Days</th>
															<th>Department</th>
															<th>EmpName</th>
															<th>EmpID</th>
															<th>EmpCode</th>
															<th>Designation</th>
															<th>Actions</th>
														</tr>
													</tfoot>
												</table>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Multi Select -->
				</div><!-- START RIGHT SIDEBAR NAV -->

				<?php include('sub_files/right_sidebar.php'); ?>
			</div>

			<div class="content-overlay"></div>
		</div>
	</div>
</div>