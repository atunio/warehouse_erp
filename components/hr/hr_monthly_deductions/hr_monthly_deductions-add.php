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
$title_heading 			= "Monthly Deduction Process";
$button_val 			= "Run Monthly Deduction Process";
$id 					= "";
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
$total_days_in_month 	= date("t");
$off_days 				= array();
$sql_ee3_1				= "	SELECT * FROM " . $selected_db_name . ".off_days_school a 
							WHERE a.enabled = 1 AND a.school_admin_id = '" . $school_admin_id . "'";
$result_ee3_1			= $db->query($conn, $sql_ee3_1);
$counter_ee3_1			= $db->counter($result_ee3_1);
if ($counter_ee3_1 > 0) {
	$row_cl3_1 = $db->fetch($result_ee3_1);
	foreach ($row_cl3_1 as $data3_1) {
		$off_days[] = $data3_1['off_day_name'];
	}
}
if (isset($is_Submit_Scale) && $is_Submit_Scale == 'Y') {
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if (isset($process_year) && $process_year == "") {
		$error['msg'] = "Please Select the Process Year";
		$process_year_valid = "invalid";
	}
	if (isset($process_month) && $process_month == "") {
		$error['msg'] = "Please Select the Process Month";
		$process_month_valid = "invalid";
	}
	if (empty($error)) {
		if ($cmd == 'add') {
			$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".hr_payroll a 
									WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
									AND a.process_year 	= '" . $process_year . "'  
									AND a.process_month	= '" . $process_month . "' ";
			$result_ee 			= $db->query($conn, $sql_ee);
			$counter_ee			= $db->counter($result_ee);
			if ($counter_ee == 0) {
				$sql = "INSERT INTO " . $selected_db_name . ".hr_payroll(school_admin_id, process_year, process_month, total_days_in_month, add_date, add_by, add_ip)
						VALUES('" . $school_admin_id . "', '" . $process_year . "', '" . $process_month . "', '" . $total_days_in_month . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				$ok = $db->query($conn, $sql);
				if ($ok) {
					$payroll_id = mysqli_insert_id($conn);
					$countEmp 	= 0;
					$sql_ee2		= "	SELECT a.*
										FROM " . $selected_db_name . ".employee_profile a 
										INNER JOIN " . $selected_db_name . ".hr_emp_employment_history b2 ON a.id = b2.emp_id  
										AND b2.id = ( SELECT MAX(id) FROM " . $selected_db_name . ".hr_emp_employment_history WHERE enabled = 1 AND emp_id = b2.emp_id)
										WHERE a.school_admin_id	= '" . $school_admin_id . "'  
										AND a.emp_status		= 'Active'
										AND a.enabled 			= 1
										AND a.gross_salary > 0";
					$result_ee2		= $db->query($conn, $sql_ee2);
					$counter_ee2	= $db->counter($result_ee2);
					if ($counter_ee2 > 0) {
						$row_cl2 = $db->fetch($result_ee2);
						foreach ($row_cl2 as $data2) {
							$countEmp++;
							$emp_id_current						= $data2['id'];
							$no_of_month_salary_payment			= $data2['no_of_month_salary_payment'];
							$children_scholarship_percentage	= $data2['children_scholarship_percentage'];
							$e_joining_date						= $data2['e_joining_date'];
							$e_joining_date_without_dash		= str_replace("-", "", $e_joining_date);
							$e_exit_date						= $data2['e_exit_date'];
							$e_exit_date_without_dash			= str_replace("-", "", $e_exit_date);

							$dependant_fees = $ward_payable = $net_salary = $total_presents = $total_absents = $total_off_days = $no_attendance_days = $total_attendance_days = $duduction_amount = $income_tax = $cash_advance  = $dependant_fees  = $ward_payable  = $other_allowance_and_benefits = 0;

							$gross_salary				= $data2['gross_salary'];
							$sql_ee3					= "	SELECT COUNT(*) AS total_absents
															FROM " . $selected_db_name . ".emp_attendance a 
															WHERE a.enabled = 1
															AND a.school_admin_id	= '" . $school_admin_id . "' 
															AND a.emp_id = '" . $emp_id_current . "'
															AND YEAR(att_date) = '" . $process_year . "'
															AND MONTH(att_date) = '" . $process_month . "'
															AND is_absent = 'Absent'";
							$result_ee3					= $db->query($conn, $sql_ee3);
							$counter_ee3				= $db->counter($result_ee3);
							if ($counter_ee3 > 0) {
								$row_cl3 			= $db->fetch($result_ee3);
								$total_absents 		= $row_cl3[0]['total_absents'];
							}
							for ($i = 1; $i <= $total_days_in_month; $i++) {
								if ($i < 10) $i = "0" . $i;
								$dummy_date_create_compare 	= $process_year . $process_month . $i;
								$dummy_date_create 			= $process_year . "-" . $process_month . "-" . $i;
								$off_day_attendance 		= 0;
								$sql_ee11 					= "	SELECT *
																FROM " . $selected_db_name . ".emp_attendance
																WHERE enabled = 1
																AND school_admin_id	= '" . $school_admin_id . "'  
																AND emp_id 			= '" . $emp_id_current . "'
																AND att_date 		= '" . $dummy_date_create . "' ";
								$result_ee11 				= $db->query($conn, $sql_ee11);
								$off_day_attendance			= $db->counter($result_ee11);
								if ($off_day_attendance == 0) {
									$dummy_date_create 	= date_create($dummy_date_create);
									$current_off_day 	= date_format($dummy_date_create, "l");

									$sql_ee12 					= "	SELECT * FROM " . $selected_db_name . ".emp_holiday  
																	WHERE '" . $dummy_date_create_compare . "' BETWEEN DATE_FORMAT(holiday_from, '%Y%m%d') AND DATE_FORMAT(holiday_to, '%Y%m%d') ";
									$result_ee12 				= $db->query($conn, $sql_ee12);
									$count_holiday_t			= $db->counter($result_ee12);
									if ($count_holiday_t > 0) {
										$total_off_days++;
									} else if ($dummy_date_create_compare >= $e_joining_date_without_dash && ($e_exit_date_without_dash == '00000000' || $dummy_date_create_compare <= $e_exit_date_without_dash)) {
										if (in_array($current_off_day, $off_days)) {
											$total_off_days++;
										}
									}
								}
							}
							$sql_ee1 					= "	SELECT DISTINCT att_date
															FROM " . $selected_db_name . ".emp_attendance
															WHERE enabled = 1
															AND school_admin_id	= '" . $school_admin_id . "'  
															AND emp_id 			= '" . $emp_id_current . "'
															AND YEAR(att_date) 	= '" . $process_year . "'
															AND MONTH(att_date) = '" . $process_month . "' ";
							$result_ee1 				= $db->query($conn, $sql_ee1);
							$total_attendance_days		= $db->counter($result_ee1);
							$no_attendance_days 		= $total_days_in_month - ($total_attendance_days + $total_off_days);

							$sql_ee3					= "	SELECT COUNT(*) AS total_presents
															FROM " . $selected_db_name . ".emp_attendance a 
															WHERE a.enabled = 1
															AND a.school_admin_id	= '" . $school_admin_id . "' 
															AND a.emp_id = '" . $emp_id_current . "'
															AND YEAR(att_date) = '" . $process_year . "'
															AND MONTH(att_date) = '" . $process_month . "'
															AND is_absent != 'Absent'";
							$result_ee3					= $db->query($conn, $sql_ee3);
							$counter_ee3				= $db->counter($result_ee3);
							if ($counter_ee3 > 0) {
								$row_cl3 			= $db->fetch($result_ee3);
								$total_presents 	= $row_cl3[0]['total_presents'];
								$total_paid_days 	= $total_presents + $total_off_days;
							}
							/////////////////////////   Employee Children Student Fees START //////////////////////////////
							$sql_ee3_1			= "	SELECT a.student_id, (sum(b.total_final_amount)-(sum(b.arrears_deferred)+sum(b.total_discount))) as total_amount, sum(b.ward_payable) AS ward_payable
													FROM " . $selected_db_name . ".hr_emp_children_as_students a 
													INNER JOIN " . $selected_db_name . ".fee_voucher_master b ON b.student_profile_id = a.student_id
													WHERE a.enabled = 1 and b.enabled = 1
													AND b.payment_status 	= 'Not Paid'
													AND a.school_admin_id 	= '" . $school_admin_id . "' 
													AND a.emp_id 			= '" . $emp_id_current . "'
													AND b.fee_year 			= '" . $process_year . "' 
													AND b.fee_month 		= '" . $process_month . "'
													GROUP BY a.student_id "; //echo $sql_ee3_1."<br>";
							$result_ee3_1	= $db->query($conn, $sql_ee3_1);
							$counter_ee3_1	= $db->counter($result_ee3_1);
							if ($counter_ee3_1 > 0) {
								$row_cl3_1 = $db->fetch($result_ee3_1);
								foreach ($row_cl3_1 as $data3_1) {
									$student_id_emp 	 = $data3_1['student_id'];
									$dependant_fees 	+= $data3_1['total_amount'];
									$ward_payable 		+= $data3_1['ward_payable'];
									$sql				= " UPDATE " . $selected_db_name . ".student_profile 
																SET out_standing_dues = out_standing_dues-" . $data3_1['total_amount'] . "
															WHERE id 				= '" . $student_id_emp . "'
															AND school_admin_id 	= '" . $school_admin_id . "' "; //echo $sql."<br>";
									$db->query($conn, $sql);
								}
								$sql				= " UPDATE " . $selected_db_name . ".fee_voucher_master b
														INNER JOIN " . $selected_db_name . ".hr_emp_children_as_students a ON b.student_profile_id = a.student_id
															SET b.payment_status = 'Paid'
														WHERE a.enabled = 1 AND b.enabled = 1
														AND a.school_admin_id 	= '" . $school_admin_id . "' 
														AND a.emp_id 			= '" . $emp_id_current . "'
														AND b.fee_year 			= '" . $process_year . "' 
														AND b.fee_month 		= '" . $process_month . "'  
														AND b.payment_status 	= 'Not Paid' "; //echo $sql."<br>";
								$db->query($conn, $sql);
							}
							/////////////////////////   Employee Children Student Fees END //////////////////////////////
							$sql_ee4			= "	SELECT SUM(total_amount) AS total_amount
													FROM " . $selected_db_name . ".hr_other_allowances_or_benefits 
													WHERE school_admin_id 	= '" . $school_admin_id . "' 
													AND emp_id 				= '" . $emp_id_current . "' 
													AND enabled 			= 1 ";
							$result_ee4			= $db->query($conn, $sql_ee4);
							$counter_ee4		= $db->counter($result_ee4);
							if ($counter_ee4 > 0) {
								$row_cl4						= $db->fetch($result_ee4);
								$other_allowance_and_benefits 	= $row_cl4[0]['total_amount'];
							}

							$duduction_amount1	= $gross_salary - (($gross_salary / 30) * $total_paid_days);
							$duduction_amount 	= round($duduction_amount1, 2);
							$net_salary 		= ($gross_salary - $duduction_amount1) + $other_allowance_and_benefits;

							$condition_1 		= "70000";
							$condition_2 		= "200001";
							$condition_2_var2 	= "6500";
							$condition_3 		= "800001";
							$condition_3_var2 	= "96500";

							if ($net_salary > ($condition_3 / $no_of_month_salary_payment)) {
								$tax1 = (($net_salary - ($condition_1 / $no_of_month_salary_payment)) - ($condition_3_var2 / $no_of_month_salary_payment)) * 0.25;
								$income_tax = ($condition_3_var2 / $no_of_month_salary_payment) + $tax1;
							} else if ($net_salary >= ($condition_2 / $no_of_month_salary_payment)) {
								$tax1 = (($net_salary - ($condition_1 / $no_of_month_salary_payment)) - ($condition_2_var2 / $no_of_month_salary_payment)) * 0.15;
								$income_tax = ($condition_2_var2 / $no_of_month_salary_payment) + $tax1;
							} else if ($net_salary > ($condition_1 / $no_of_month_salary_payment)) {
								$income_tax = ($net_salary - ($condition_1 / $no_of_month_salary_payment)) * 0.05;
							} else {
								$income_tax = 0;
							}
							$net_salary_after_tax 	= $net_salary - $income_tax;
							$net_salary_after_tax 	= round($net_salary_after_tax, 2);
							$income_tax 			= round($income_tax, 2);
							$net_salary 			= round($net_salary, 2);
							$final_net_salary 		= $net_salary_after_tax - ($cash_advance + $dependant_fees);
							$sql = "INSERT INTO " . $selected_db_name . ".hr_payroll_detail(school_admin_id, payroll_id, emp_id, total_presents, gross_salary, duduction_amount, 
																		income_tax, cash_advance, dependant_fees, other_allowance_and_benefits, final_net_salary, total_month_days, 
																		no_attendance_days, total_absents, total_off_days, net_salary_before_tax, net_salary_after_tax, add_date, add_by, add_ip)
									VALUES('" . $school_admin_id . "', '" . $payroll_id . "', '" . $emp_id_current . "', '" . $total_presents . "',  '" . $gross_salary . "', '" . $duduction_amount . "',  
									'" . $income_tax . "',  '" . $cash_advance . "',  '" . $dependant_fees . "', '" . $other_allowance_and_benefits . "', '" . $final_net_salary . "',  
									'" . $total_days_in_month . "',  '" . $no_attendance_days . "', '" . $total_absents . "', '" . $total_off_days . "', '" . $net_salary . "', '" . $net_salary_after_tax . "', 
																																	'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
							$db->query($conn, $sql);
							$payroll_detail_id = mysqli_insert_id($conn);
							/// History //// 
							$sql = "INSERT INTO " . $selected_db_name . ".hr_payroll_detail_history(school_admin_id, payroll_detail_id, total_presents, gross_salary, duduction_amount, 
																		income_tax, cash_advance, dependant_fees, other_allowance_and_benefits, final_net_salary, total_month_days, 
																		no_attendance_days, total_absents, total_off_days, net_salary_before_tax, net_salary_after_tax, add_date, add_by, add_ip)
									VALUES('" . $school_admin_id . "', '" . $payroll_detail_id . "', '" . $total_presents . "', '" . $gross_salary . "', '" . $duduction_amount . "',  
									'" . $income_tax . "',  '" . $cash_advance . "',  '" . $dependant_fees . "', '" . $other_allowance_and_benefits . "', '" . $final_net_salary . "',  
									'" . $total_days_in_month . "',  '" . $no_attendance_days . "', '" . $total_absents . "', '" . $total_off_days . "', '" . $net_salary . "', '" . $net_salary_after_tax . "', 
																																	'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
							$db->query($conn, $sql);
						}
					}
					$process_year = $process_month = "";
					$msg['msg_success'] = "Monthly deduction process run successfully for Total: " . $countEmp . " employees.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This Process already exist.";
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
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">Deduction List</a>
								</li>
							</ol>
						</div>
						<div class="col s2 m6 l6">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right"
								href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
								Deduction Process List
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
						<input type="hidden" name="is_Submit_Scale" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
						<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																			echo encrypt($_SESSION['csrf_session']);
																		} ?>">
						<div class="row">
							<div class="col m3 s12">
								<div class="input-field">
									<i class="material-icons prefix">date_range</i>
									<select id="process_year" name="process_year" class="validate <?php if (isset($process_year_valid)) {
																										echo $process_year_valid;
																									} ?>">
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
							</div>
						</div>
						<div class="row">
							<div class="row">&nbsp;&nbsp;</div>
							<div class="row">
								<div class="input-field col m2 s12"></div>
								<div class="input-field col m3 s12">
									<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?></button>
								</div>
								<div class="input-field col m7 s12"></div>
							</div>
						</div>
					</form>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->