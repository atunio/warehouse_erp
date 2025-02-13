<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$clock_date			= date('d/m/Y');
	$employee_profile_id	= "1";
	$department_id			= "1";
	$clocked_in				= "09:05";
	$clocked_out			= "10:10";
	$cash_tips				= "10";
	$credit_tips			= "20";
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

if ($cmd == 'edit') {
	$title_heading 	= "Update " . $main_menu_name;
	$button_val 	= "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add " . $main_menu_name;
	$button_val 	= "Add";
	$id 			= "";
}
 
if ($cmd == 'edit' && isset($id) && $id > 0) {

	$sql_ee						= "SELECT *, a.e_full_name, b.department_name,
											FLOOR(SUM((SUBSTRING_INDEX(worked_hours, ' hr', 1) * 60) + (SUBSTRING_INDEX(SUBSTRING_INDEX(worked_hours, ' min', 1), ' ', -1))) / 60) AS total_hours,
											SUM((SUBSTRING_INDEX(worked_hours, ' hr', 1) * 60) + (SUBSTRING_INDEX(SUBSTRING_INDEX(worked_hours, ' min', 1), ' ', -1))) % 60 AS total_minutes
									FROM timesheets
									JOIN employee_profile a ON a.id =timesheets.employee_id
									JOIN departments b  ON b.id =  timesheets.department_id
									WHERE  a.enabled=1 and timesheets.id = '" . $id . "' "; //echo $sql_ee;
	$result_ee					= $db->query($conn, $sql_ee);
	$row_ee						= $db->fetch($result_ee);
 	$clock_date					= str_replace("-", "/", convert_date_display($row_ee[0]['clock_date']));
	$employee_profile_id		= $row_ee[0]['employee_id'];
	$department_id				= $row_ee[0]['department_id'];
	$clocked_in 				= date("H:i", strtotime($row_ee[0]['clocked_in']));
	$clocked_out 				= date("H:i", strtotime($row_ee[0]['clocked_out']));
	$worked_hours				= $row_ee[0]['worked_hours'];
	$cash_tips					= $row_ee[0]['cash_tips'];
	$credit_tips				= $row_ee[0]['credit_tips'];
}

extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
 
	$field_name = "clock_date"; 
	if (isset(${$field_name}) && trim(${$field_name}) == "") {
		$error[$field_name] = "Required";
		${$field_name . "_valid"} = "invalid";
	} else {
		$clock_date1 	= NULL;
		$clock_date1 	= convert_date_mysql_slash($clock_date);
	}

	$field_name = "employee_profile_id";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}
	$field_name = "department_id";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}

	$field_name = "clocked_in";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}

	if (!empty($clocked_in) && !empty($clocked_out)) {
		$in_time = strtotime($clocked_in);
		$out_time = strtotime($clocked_out);

		if ($out_time < $in_time) {
			$out_time += 86400;
		}

		$diff = $out_time - $in_time;
		$worked_minutes = floor($diff / 60);
		$hours = floor($worked_minutes / 60);
		$minutes = $worked_minutes % 60;

		$worked_hours = "$hours hr $minutes min";
	} else {
		$worked_hours = "0 hr 0 min";
		$worked_minutes = 0;
	}

	if (empty($error)) {
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup = "SELECT * FROM timesheets 
							WHERE employee_id = '" . $employee_profile_id . "' 
							AND clock_date = CURDATE()
							AND clocked_in = '".$clocked_in."'
							AND clocked_out = '".$clocked_out."' ";
				$result_dup = $db->query($conn, $sql_dup);
				$count_dup = $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO timesheets  (employee_id, department_id, `clock_date` , clocked_in, clocked_out, worked_hours, cash_tips, credit_tips,  add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
							VALUES('" . $employee_profile_id . "', '" . $department_id . "', '" . $clock_date1 . "', '" . $clocked_in . "', '" . $clocked_out . "', '" . $worked_hours . "', '" . $cash_tips . "', '" . $credit_tips . "',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";

					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$msg['msg_success'] = "Record has been added successfully.";
						$product_category = $devices_per_user_per_day = $no_of_employees = "";
					} else {
						$error['msg'] = "There is an error, please check it again OR contact the support team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT * FROM timesheets 
								WHERE employee_id = '" . $employee_profile_id . "' 
								AND clock_date = CURDATE()
								AND clocked_in = '".$clocked_in."'
								AND clocked_out = '".$clocked_out."'
								AND id		!= '" . $id . "'";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE timesheets SET 	`clock_date`			= '" . $clock_date1 . "', 
														employee_id				= '" . $employee_profile_id . "',
														department_id			= '" . $department_id . "', 
														clocked_in				= '" . $clocked_in . "', 
														clocked_out				= '" . $clocked_out . "', 
														worked_hours			= '" . $worked_hours . "', 
														credit_tips				= '" . $credit_tips . "', 
														
														update_date				= '" . $add_date . "',
														update_by				= '" . $_SESSION['username'] . "',
														update_by_user_id		= '" . $_SESSION['user_id'] . "',
														update_ip				= '" . $add_ip . "',
														update_timezone			= '" . $timezone . "',
														update_from_module_id	= '" . $module_id . "' 

								WHERE id = '" . $id . "'   ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
					} else {
						$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
				}
			}
		}
	}
}
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12 m12 l12">
			<div class="section section-data-tables">
				<div class="card custom_margin_card_table_top custom_margin_card_table_bottom">
					<div class="card-content custom_padding_card_content_table_top_bottom">
						<div class="row">
							<div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
								<h6 class="media-heading">
									<?php echo $title_heading; ?>
								</h6>
							</div>
							<div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">
									List
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy custom_margin_card_table_top custom_margin_card_table_bottom">
				<div class="card-content custom_padding_card_content_table_top">
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
					<br>
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />

						<div class="row">
							<div class="input-field col m4 s12">
								<?php
								$field_name  = "clock_date";
								$field_label = "Current Date (d/m/Y)";
								?>
								<i class="material-icons prefix">event</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>"
									value="<?= isset(${$field_name}) ? ${$field_name} : date("d/m/Y"); ?>"
									class="datepicker validate <?= isset(${$field_name . "_valid"}) ? ${$field_name . "_valid"} : ''; ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?= isset($error[$field_name]) ? $error[$field_name] : ''; ?></span>
								</label>
							</div>

							<div class="input-field col m4 s12">
								<?php
								$field_name  = "employee_profile_id";
								$field_label = "Employee Name";
								$sql1        = "SELECT * FROM `employee_profile` WHERE enabled=1 AND emp_status = 'Active' ORDER BY e_full_name";
								$result1     = $db->query($conn, $sql1);
								$count1      = $db->counter($result1);
								?>
								<i class="material-icons prefix">person</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>"
										class="select2 browser-default validate <?= isset(${$field_name . "_valid"}) ? ${$field_name . "_valid"} : ''; ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1 = $db->fetch($result1);
											foreach ($row1 as $data2) {
												$selected = (isset(${$field_name}) && ${$field_name} == $data2['id']) ? 'selected' : '';
												echo "<option value='{$data2['id']}' $selected>{$data2['e_full_name']}</option>";
											}
										}
										?>
									</select>
									<label for="<?= $field_name; ?>">
										<?= $field_label; ?>
										<span class="color-red">* <?= isset($error[$field_name]) ? $error[$field_name] : ''; ?></span>
									</label>
								</div>
							</div>

							<div class="input-field col m4 s12">
								<?php
								$field_name  = "department_id";
								$field_label = "Departments";
								$sql1        = "SELECT * FROM departments WHERE enabled=1 ORDER BY department_name";
								$result1     = $db->query($conn, $sql1);
								$count1      = $db->counter($result1);
								?>
								<i class="material-icons prefix">apartment</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>"
										class="select2 browser-default validate <?= isset(${$field_name . "_valid"}) ? ${$field_name . "_valid"} : ''; ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1 = $db->fetch($result1);
											foreach ($row1 as $data2) {
												$selected = (isset(${$field_name}) && ${$field_name} == $data2['id']) ? 'selected' : '';
												echo "<option value='{$data2['id']}' $selected>{$data2['department_name']}</option>";
											}
										}
										?>
									</select>
									<label for="<?= $field_name; ?>">
										<?= $field_label; ?>
										<span class="color-red">* <?= isset($error[$field_name]) ? $error[$field_name] : ''; ?></span>
									</label>
								</div>
							</div>

						</div>
						<div class="row">
							<!-- Clocked In -->
							<?php
							$field_name  = "clocked_in";
							$field_label = "Clocked In";
							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">access_time</i>
								<input id="clocked_in" type="time" name="<?= $field_name; ?>" value="<?= isset(${$field_name}) ? ${$field_name} : ""; ?>" onchange="calculateWorkedHours()">
								<label for="clocked_in" class="active">Clocked In *</label>
							</div>

							<?php
							$field_name  = "clocked_out";
							$field_label = "Clocked Out";
							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">access_time</i>
								<input id="clocked_out" type="time" name="<?= $field_name; ?>" value="<?= isset(${$field_name}) ? ${$field_name} : ""; ?>" onchange="calculateWorkedHours()">
								<label for="clocked_out" class="active">Clocked Out *</label>
							</div>

							<?php
							$field_name  = "worked_hours";
							$field_label = "Worked ($)";
							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">timer</i>
								<input id="worked_hours" type="text" name="<?= $field_name; ?>" value="<?= isset(${$field_name}) ? ${$field_name} : "0 hr 0 min"; ?>" readonly>
								<label for="worked_hours" class="active">Worked</label>
							</div>

							<?php
							$field_name  = "cash_tips";
							$field_label = "Cash Tips ($)";

							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">attach_money</i>
								<input id="cash_tips" type="number" name="<?= $field_name; ?>" step="0.01" min="0" value="<?= isset(${$field_name}) ? ${$field_name} : "0.00"; ?>" onchange="calculateTotalTips()">
								<label for="cash_tips" class="active">Cash Tips ($)</label>
							</div>

							<?php
							$field_name  = "credit_tips";
							$field_label = "Credit Tips ($)";
							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">credit_card</i>
								<input id="credit_tips" type="number" name="<?= $field_name; ?>" step="0.01" min="0" value="<?= isset(${$field_name}) ? ${$field_name} : "0.00"; ?>" onchange="calculateTotalTips()">
								<label for="credit_tips" class="active">Credit Tips ($)</label>
							</div>

						</div>
						<div class="row">
							<div class="input-field col m6 s12">
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn cyan waves-effect waves-light right custom_btn_size" type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<br><br><br><br>
<!-- END: Page Main-->

<script>
	calculateWorkedHours();
	function calculateWorkedHours() {
		var clockedIn = document.getElementById("clocked_in").value;
		var clockedOut = document.getElementById("clocked_out").value;

		if (clockedIn && clockedOut) {
			var inTime = new Date("1970-01-01T" + clockedIn);
			var outTime = new Date("1970-01-01T" + clockedOut);

			var diff = (outTime - inTime) / 1000; // Difference in seconds
			if (diff < 0) diff += 86400; // Handle next-day cases

			var hours = Math.floor(diff / 3600);
			var minutes = Math.floor((diff % 3600) / 60);

			document.getElementById("worked_hours").value = hours + " hr " + minutes + " min";
		}
	}

	function calculateTotalTips() {
		var cashTips = parseFloat(document.getElementById("cash_tips").value) || 0;
		var creditTips = parseFloat(document.getElementById("credit_tips").value) || 0;
		document.getElementById("total_tips").value = (cashTips + creditTips).toFixed(2);
	}
</script>