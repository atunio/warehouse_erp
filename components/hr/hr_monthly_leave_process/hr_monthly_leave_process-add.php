<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$leave_process_year			= "2021";
	$leave_process_month		= "10";
	$casual_leaves				= "2.5";
	$sick_leaves				= "1.5";
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
if ($cmd == 'edit') {
	$title_heading = "Edit Proccess Entry";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Leave Proccess Entry";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee 			= "SELECT a.* FROM " . $selected_db_name . ".hr_leaves_process_entries a 
							WHERE a.id = '" . $id . "'  AND a.school_admin_id = '" . $school_admin_id . "'   ";
	$result_ee 			= $db->query($conn, $sql_ee);
	$row_ee 			= $db->fetch($result_ee);
	$leave_process_year			=  $row_ee[0]['leave_process_year'];
	$leave_process_month 		=  $row_ee[0]['leave_process_month'];
	$casual_leaves 				=  $row_ee[0]['casual_leaves'];
	$sick_leaves 				=  $row_ee[0]['sick_leaves'];
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
	if (isset($leave_process_year) && $leave_process_year == "") {
		$error['msg'] = "Please Select the Leaves Process Year";
		$leave_process_year_valid = "invalid";
	}
	if (isset($leave_process_month) && $leave_process_month == "") {
		$error['msg'] = "Please Select the Leaves Process Month";
		$leave_process_month_valid = "invalid";
	}
	if (isset($casual_leaves) && $casual_leaves == "") {
		$error['msg'] = "Pleae Enter the Month Casual Leaves";
		$casual_leaves_valid = "invalid";
	}
	if (isset($sick_leaves) && $sick_leaves == "") {
		$error['msg'] = "Pleae Enter the Month Sick Leaves";
		$sick_leaves_valid = "invalid";
	}

	if (empty($error)) {
		if ($cmd == 'add') {
			$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".hr_leaves_process_entries a 
									WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
									AND a.leave_process_year 	= '" . $leave_process_year . "'  
									AND a.leave_process_month	= '" . $leave_process_month . "' ";
			$result_ee 			= $db->query($conn, $sql_ee);
			$counter_ee			= $db->counter($result_ee);
			if ($counter_ee == 0) {
				$sql_ee2 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
										WHERE a.school_admin_id 	= '" . $school_admin_id . "'  
										AND a.emp_status = 'Active' AND a.enabled = 1";
				$result_ee2 			= $db->query($conn, $sql_ee2);
				$counter_ee2			= $db->counter($result_ee2);
				if ($counter_ee2 > 0) {
					$row_cl2 = $db->fetch($result_ee2);
					foreach ($row_cl2 as $data2) {
						$emp_id_current	= $data2['id'];

						$sql = "UPDATE " . $selected_db_name . ".employee_profile SET 	e_casual_leave 		= e_casual_leave+" . $casual_leaves . ",
																					e_sick_leave 		= e_sick_leave+" . $sick_leaves . ",
																					e_earn_leave 		= e_earn_leave+(" . $casual_leaves . "+" . $sick_leaves . "), 

																					update_date 		= '" . $add_date . "',
																					update_by 	 		= '" . $_SESSION['username'] . "',
																					update_ip 	 		= '" . $add_ip . "' 
						WHERE id = '" . $emp_id_current . "' ";
						$db->query($conn, $sql);
					}
				}
				$sql = "INSERT INTO " . $selected_db_name . ".hr_leaves_process_entries(school_admin_id, leave_process_year, leave_process_month, casual_leaves, sick_leaves, add_date, add_by, add_ip)
						VALUES('" . $school_admin_id . "', '" . $leave_process_year . "', '" . $leave_process_month . "', '" . $casual_leaves . "',  '" . $sick_leaves . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				// echo $sql;
				$ok = $db->query($conn, $sql);
				if ($ok) {
					$scale_name = $scale_level = "";
					$msg['msg_success'] = "Process has been added successfully.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This Process already exist.";
			}
		} else if ($cmd == 'edit') {
			$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".hr_leaves_process_entries a 
									WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
									AND a.leave_process_year 	= '" . $leave_process_year . "'  
									AND a.leave_process_month	= '" . $leave_process_month . "' 
									AND a.id 				   != '" . $id . "'";
			$result_ee 			= $db->query($conn, $sql_ee);
			$counter_ee			= $db->counter($result_ee);
			if ($counter_ee == 0) {
				$sql_ee2 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
										WHERE a.school_admin_id 	= '" . $school_admin_id . "'  
										AND a.emp_status = 'Active' AND a.enabled = 1";
				$result_ee2 			= $db->query($conn, $sql_ee2);
				$counter_ee2			= $db->counter($result_ee2);
				if ($counter_ee2 > 0) {
					$row_cl2 = $db->fetch($result_ee2);
					foreach ($row_cl2 as $data2) {
						$emp_id_current	= $data2['id'];

						$sql = "UPDATE " . $selected_db_name . ".employee_profile SET 	e_casual_leave 	= (e_casual_leave-" . $casual_leaves_old . ")+" . $casual_leaves . ",
																					e_sick_leave	= (e_sick_leave-" . $sick_leaves_old . ")+" . $sick_leaves . ",
																					e_earn_leave 	= (e_earn_leave-(" . $casual_leaves_old . "+" . $sick_leaves_old . "))+(" . $casual_leaves . "+" . $sick_leaves . "),
																					
																					update_date 		= '" . $add_date . "',
																					update_by 	 		= '" . $_SESSION['username'] . "',
																					update_ip 	 		= '" . $add_ip . "' 
						WHERE id = '" . $emp_id_current . "' ";
						$db->query($conn, $sql);
					}
				}

				$sql_c_up = "UPDATE " . $selected_db_name . ".hr_leaves_process_entries SET leave_process_year 			= '" . $leave_process_year . "', 
																						leave_process_month			= '" . $leave_process_month . "',  
																						casual_leaves				= '" . $casual_leaves . "',  
																						sick_leaves					= '" . $sick_leaves . "',  
																						update_date 				= '" . $add_date . "',
																						update_by 	 				= '" . $_SESSION['username'] . "',
																						update_ip 	 				= '" . $add_ip . "'
													WHERE id = '" . $id . "'  ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This process already exist.";
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
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">List</a>
								</li>
							</ol>
						</div>
						<div class="col s2 m6 l6">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right"
								href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
								Leave Process List
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
						<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																			echo encrypt($_SESSION['csrf_session']);
																		} ?>">
						<div class="row">
							<div class="col m3 s12">
								<div class="input-field">
									<i class="material-icons prefix">date_range</i>
									<select id="leave_process_year" name="leave_process_year" class="validate <?php if (isset($leave_process_year_valid)) {
																													echo $leave_process_year_valid;
																												} ?>">
										<option value="">Select Leave Process Year</option>
										<?php
										for ($i = date('Y') - 1; $i <= date('Y'); $i++) { ?>
											<option value="<?php echo $i; ?>" <?php if (isset($leave_process_year) && $leave_process_year == $i) { ?> selected="selected" <?php } ?>><?php echo $i; ?></option>
										<?php } ?>
									</select>
									<label for="leave_process_year">Leave Process Year</label>
								</div>
							</div>
							<div class="col m3 s12">
								<div class="input-field">
									<i class="material-icons prefix">date_range</i>
									<select id="leave_process_month" name="leave_process_month" class="validate <?php if (isset($leave_process_month_valid)) {
																													echo $leave_process_month_valid;
																												} ?>">
										<option value="">Select Fee Month</option>
										<?php
										for ($i = 1; $i < 13; $i++) {
											if ($i <= 9) {
												$i = "0" . $i;
											} ?>
											<option value="<?php echo $i; ?>" <?php if (isset($leave_process_month) && $leave_process_month == $i) { ?> selected="selected" <?php } ?>><?php echo convert_month_letter($i); ?></option>
										<?php } ?>
									</select>
									<label for="leave_process_month">Fee Month</label>
								</div>
							</div>
							<div class="col m3 s12">
								<div class="input-field">
									<i class="material-icons prefix">date_range</i>
									<input id="casual_leaves" type="text" name="casual_leaves" value="<?php if (isset($casual_leaves)) {
																											echo $casual_leaves;
																										} ?>" required="" class="validate <?php if (isset($casual_leaves_valid)) {
																																																	echo $casual_leaves_valid;
																																																} ?>">
									<input id="casual_leaves_old" type="hidden" name="casual_leaves_old" value="<?php if (isset($casual_leaves)) {
																													echo $casual_leaves;
																												} ?>">
									<label for="att_date">Casual Leaves</label>
								</div>
							</div>
							<div class="col m3 s12">
								<div class="input-field">
									<i class="material-icons prefix">date_range</i>
									<input id="sick_leaves" type="text" name="sick_leaves" value="<?php if (isset($sick_leaves)) {
																										echo $sick_leaves;
																									} ?>" required="" class="validate <?php if (isset($sick_leaves_valid)) {
																																															echo $sick_leaves_valid;
																																														} ?>">
									<input id="sick_leaves_old" type="hidden" name="sick_leaves_old" value="<?php if (isset($sick_leaves)) {
																												echo $sick_leaves;
																											} ?>">
									<label for="att_date">Sick Leaves</label>
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