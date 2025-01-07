<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$process_year		= "2021";
	$process_month		= "11";
}
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
$title_heading 			= "Employee Payroll Details";
$button_val = "Edit";
if ($cmd == 'edit' && isset($id)) {
	$sql_ee 			= "	SELECT a.process_year, a.process_month, a.total_days_in_month, a1.e_full_name, a1.emp_code, c.department_name, d.designation, b.*
							FROM " . $selected_db_name . ".hr_payroll a
							INNER JOIN " . $selected_db_name . ".hr_payroll_detail b ON b.payroll_id = a.id
							INNER JOIN " . $selected_db_name . ".employee_profile a1 ON a1.id = b.emp_id
							INNER JOIN " . $selected_db_name . ".hr_emp_employment_history b2 ON a1.id = b2.emp_id  
										AND b2.id = ( SELECT MAX(id) FROM " . $selected_db_name . ".hr_emp_employment_history WHERE enabled = 1 AND emp_id = b2.emp_id)
							INNER JOIN " . $selected_db_name . ".departments c ON c.id = b2.dept_id
							INNER JOIN " . $selected_db_name . ".designations d ON d.id = b2.designation_id
							WHERE a.school_admin_id = '" . $school_admin_id . "' 
							AND b.id = '" . $id . "' ";
	$result_ee 			= $db->query($conn, $sql_ee);
	$row_ee 			= $db->fetch($result_ee);
	$process_year					=  $row_ee[0]['process_year'];
	$process_month 					=  $row_ee[0]['process_month'];
	$e_full_name 					=  $row_ee[0]['e_full_name'];
	$emp_code	 					=  $row_ee[0]['emp_code'];
	$department_name				=  $row_ee[0]['department_name'];
	$designation 					=  $row_ee[0]['designation'];
	$total_presents					=  $row_ee[0]['total_presents'];
	$total_off_days					=  $row_ee[0]['total_off_days'];
	$total_paid_days				=  $total_presents + $total_off_days;
	$gross_salary 					=  number_format($row_ee[0]['gross_salary'], 2);
	$duduction_amount 				=  $row_ee[0]['duduction_amount'];
	$other_allowance_and_benefits	=  number_format($row_ee[0]['other_allowance_and_benefits'], 2);
	$net_salary_before_tax			=  number_format($row_ee[0]['net_salary_before_tax'], 2);
	$income_tax		 				=  number_format($row_ee[0]['income_tax'], 2);
	$net_salary_after_tax			=  number_format($row_ee[0]['net_salary_after_tax'], 2);
	$cash_advance 					=  number_format($row_ee[0]['cash_advance'], 2);
	$dependant_fees 				=  number_format($row_ee[0]['dependant_fees'], 2);
	$final_net_salary 				=  number_format(($row_ee[0]['net_salary_after_tax'] - ($row_ee[0]['cash_advance'] + $row_ee[0]['dependant_fees'])), 2);
}
extract($_POST); ?>
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
					<div class="row">
						<div class="col m3 s12">
							<div class="input-field">
								<i class="material-icons prefix">date_range</i>
								<select class="validate <?php if (isset($process_year_valid)) {
															echo $process_year_valid;
														} ?>" disabled="disabled">
									<option value="">Select Salary Year</option>
									<?php
									for ($i = date('Y') - 1; $i <= date('Y'); $i++) { ?>
										<option value="<?php echo $i; ?>" <?php if (isset($process_year) && $process_year == $i) { ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
									<?php } ?>
								</select>
								<label for="process_year">Salary Year</label>
							</div>
						</div>
						<div class="col m3 s12">
							<div class="input-field">
								<i class="material-icons prefix">date_range</i>
								<select class="validate <?php if (isset($process_month_valid)) {
															echo $process_month_valid;
														} ?>" disabled="disabled">
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
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" value="<?php if (isset($e_full_name)) {
															echo $e_full_name;
														} ?>" disabled="disabled" class="validate <?php if (isset($e_full_name_valid)) {
																																						echo $e_full_name_valid;
																																					} ?>">
							<label for="e_full_name">Employee Name</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" value="<?php if (isset($emp_code)) {
															echo $emp_code;
														} ?>" disabled="disabled" class="validate <?php if (isset($emp_code_valid)) {
																																				echo $emp_code_valid;
																																			} ?>">
							<label for="emp_code">Employee Code</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" value="<?php if (isset($department_name)) {
															echo $department_name;
														} ?>" disabled="disabled" class="validate <?php if (isset($department_name_valid)) {
																																								echo $department_name_valid;
																																							} ?>">
							<label for="department_name">Department Name</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" value="<?php if (isset($designation)) {
															echo $designation;
														} ?>" disabled="disabled" class="validate <?php if (isset($designation_valid)) {
																																						echo $designation_valid;
																																					} ?>">
							<label for="designation">Designation</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" value="<?php if (isset($gross_salary)) {
															echo $gross_salary;
														} ?>" disabled="disabled" class="validate <?php if (isset($gross_salary_valid)) {
																																						echo $gross_salary_valid;
																																					} ?>">
							<label for="gross_salary">Gross Salary</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="total_presents" id="total_presents" disabled="disabled" value="<?php if (isset($total_presents)) {
																														echo $total_presents;
																													} ?>" class="validate <?php if (isset($total_presents_valid)) {
																																																		echo $total_presents_valid;
																																																	} ?>">
							<label for="total_presents">Present Days</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="total_off_days" id="total_off_days" disabled="disabled" value="<?php if (isset($total_off_days)) {
																														echo $total_off_days;
																													} ?>" class="validate <?php if (isset($total_off_days_valid)) {
																																																		echo $total_off_days_valid;
																																																	} ?>">
							<label for="total_off_days">Paid Off Days</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="total_paid_days" id="total_paid_days" disabled="disabled" value="<?php if (isset($total_paid_days)) {
																															echo $total_paid_days;
																														} ?>" disabled="disabled" class="validate <?php if (isset($total_paid_days_valid)) {
																																																								echo $total_paid_days_valid;
																																																							} ?>">
							<label for="total_paid_days">Total Paid Days</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="duduction_amount" id="duduction_amount" disabled="disabled" value="<?php if (isset($duduction_amount)) {
																															echo $duduction_amount;
																														} ?>" class="validate <?php if (isset($duduction_amount_valid)) {
																																																				echo $duduction_amount_valid;
																																																			} ?>">
							<label for="duduction_amount">Deductions</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="other_allowance_and_benefits" disabled="disabled" id="other_allowance_and_benefits" value="<?php if (isset($other_allowance_and_benefits)) {
																																					echo $other_allowance_and_benefits;
																																				} ?>" class="validate <?php if (isset($other_allowance_and_benefits_valid)) {
																																																																echo $other_allowance_and_benefits_valid;
																																																															} ?>">
							<label for="other_allowance_and_benefits">Allowances & Benefits</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="net_salary_before_tax" disabled="disabled" id="net_salary_before_tax" value="<?php if (isset($net_salary_before_tax)) {
																																		echo $net_salary_before_tax;
																																	} ?>" class="validate <?php if (isset($net_salary_before_tax_valid)) {
																																																									echo $net_salary_before_tax_valid;
																																																								} ?>">
							<label for="net_salary_before_tax">Net Salary Before Tax</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="income_tax" id="income_tax" disabled="disabled" value="<?php if (isset($income_tax)) {
																												echo $income_tax;
																											} ?>" class="validate <?php if (isset($income_tax_valid)) {
																																														echo $income_tax_valid;
																																													} ?>">
							<label for="income_tax">Income Tax</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="net_salary_after_tax" disabled="disabled" id="net_salary_after_tax" value="<?php if (isset($net_salary_after_tax)) {
																																	echo $net_salary_after_tax;
																																} ?>" disabled="" class="validate <?php if (isset($net_salary_after_tax_valid)) {
																																																											echo $net_salary_after_tax_valid;
																																																										} ?>">
							<label for="net_salary_after_tax">Net Salary After Tax</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="cash_advance" id="cash_advance" disabled="disabled" value="<?php if (isset($cash_advance)) {
																													echo $cash_advance;
																												} ?>" class="validate <?php if (isset($cash_advance_valid)) {
																																																echo $cash_advance_valid;
																																															} ?>">
							<label for="cash_advance">Cash in Advance</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="dependant_fees" id="dependant_fees" disabled="disabled" value="<?php if (isset($dependant_fees)) {
																														echo $dependant_fees;
																													} ?>" class="validate <?php if (isset($dependant_fees_valid)) {
																																																		echo $dependant_fees_valid;
																																																	} ?>">
							<label for="dependant_fees">Dependants Fees</label>
						</div>
						<div class="input-field col m3 s12">
							<i class="material-icons prefix">person_outline</i>
							<input type="text" name="final_net_salary" id="final_net_salary" disabled="disabled" value="<?php if (isset($final_net_salary)) {
																															echo $final_net_salary;
																														} ?>" class="validate <?php if (isset($final_net_salary_valid)) {
																																																				echo $final_net_salary_valid;
																																																			} ?>">
							<label for="final_net_salary">Final Net Salary</label>
						</div>
					</div>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->