<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	/* $clock_date			= date('d/m/Y');
	$employee_profile_id	= "1";
	$time_of_category_id			= "1"; */
	/* 	$start_date				= "09:05";
	$end_date			= "10:10"; */
	/* $cash_tips				= "10";
	$credit_tips			= "20"; */
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

	$sql_ee						= "SELECT *, a.e_full_name, b.category_name,
											FLOOR(SUM((SUBSTRING_INDEX(worked_hours, ' hr', 1) * 60) + (SUBSTRING_INDEX(SUBSTRING_INDEX(worked_hours, ' min', 1), ' ', -1))) / 60) AS total_hours,
											SUM((SUBSTRING_INDEX(worked_hours, ' hr', 1) * 60) + (SUBSTRING_INDEX(SUBSTRING_INDEX(worked_hours, ' min', 1), ' ', -1))) % 60 AS total_minutes
									FROM timesheets
									JOIN employee_profile a ON a.id =timesheets.employee_id
									JOIN departments b  ON b.id =  timesheets.time_of_category_id
									WHERE  a.enabled=1 and timesheetFs.id = '" . $id . "' "; //echo $sql_ee;
	$result_ee					= $db->query($conn, $sql_ee);
	$row_ee						= $db->fetch($result_ee);
	$clock_date					= str_replace("-", "/", convert_date_display($row_ee[0]['clock_date']));
	$employee_profile_id		= $row_ee[0]['employee_id'];
	$time_of_category_id				= $row_ee[0]['time_of_category_id'];
	$start_date 				= date("H:i", strtotime($row_ee[0]['start_date']));
	$end_date 				= date("H:i", strtotime($row_ee[0]['end_date']));
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

	/* $field_name = "clock_date";
	if (isset(${$field_name}) && trim(${$field_name}) == "") {
		$error[$field_name] = "Required";
		${$field_name . "_valid"} = "invalid";
	} else {
		$clock_date1 	= NULL;
		$clock_date1 	= convert_date_mysql_slash($clock_date);
	}
 */


	$field_name = "employee_profile_id";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}
	$field_name = "time_of_category_id";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}

	/* $start_date = DateTime::createFromFormat('d/m/Y', $start_date);
	$end_date = DateTime::createFromFormat('d/m/Y', $end_date); */


	$field_name = "start_date";
	if (isset(${$field_name}) && trim(${$field_name}) == "") {
		$error[$field_name] = "Required";
		${$field_name . "_valid"} = "invalid";
	} else {
		$start_date1 = convert_date_mysql_slash($start_date);
	}

	$field_name = "end_date";
	if (isset(${$field_name}) && trim(${$field_name}) == "") {
		$error[$field_name] = "Required";
		${$field_name . "_valid"} = "invalid";
	} else {
		$end_date1 = convert_date_mysql_slash($end_date);
	}

	if (!empty($start_date1) && !empty($end_date1)) {
		$start_date_obj = DateTime::createFromFormat('Y-m-d', $start_date1);
		$end_date_obj = DateTime::createFromFormat('Y-m-d', $end_date1);

		if ($start_date_obj && $end_date_obj) {
			if ($end_date_obj >= $start_date_obj) {
				$diff_days = $start_date_obj->diff($end_date_obj)->days + 1; // +1 to include start date

				// If both dates are the same, set hours to 8
				$total_hours = ($diff_days == 1) ? 8 : $diff_days * 8;
			}
		}
	}

	echo "--------------" . $end_date; //12/02/2025
	echo "<br>--------------" . $start_date; //11/02/2025



	if (empty($error)) {
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup = "SELECT * FROM timesheets 
							WHERE employee_id = '" . $employee_profile_id . "' 
							AND start_date = '" . $start_date . "'
							AND end_date = '" . $end_date . "' ";
				$result_dup = $db->query($conn, $sql_dup);
				$count_dup = $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO timesheets  (employee_id, time_of_category_id, `clock_date` , start_date, end_date, worked_hours, cash_tips, credit_tips,  add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
							VALUES('" . $employee_profile_id . "', '" . $time_of_category_id . "', '" . $clock_date1 . "', '" . $start_date . "', '" . $end_date . "', '" . $worked_hours . "', '" . $cash_tips . "', '" . $credit_tips . "',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";

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
								AND start_date = '" . $start_date . "'
								AND end_date = '" . $end_date . "'
								AND id		!= '" . $id . "'";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE timesheets SET 	`clock_date`			= '" . $clock_date1 . "', 
														employee_id				= '" . $employee_profile_id . "',
														time_of_category_id			= '" . $time_of_category_id . "', 
														start_date				= '" . $start_date . "', 
														end_date				= '" . $end_date . "', 
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
							<?php /*
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
							*/ ?>
							<div class="input-field col m4 s12">
								<?php
								$field_name  = "employee_profile_id";
								$field_label = "Employee Name";
								$sql1        = "SELECT * FROM `employee_profile` WHERE enabled=1 ORDER BY e_full_name";
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
								$field_name  = "time_of_category_id";
								$field_label = "Category";
								$sql1        = "SELECT * FROM time_of_category WHERE enabled=1 ORDER BY category_name";
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
												echo "<option value='{$data2['id']}' $selected>{$data2['category_name']}</option>";
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
								$field_name  = "time_of_reason_id";
								$field_label = "Reason";
								$sql1        = "SELECT * FROM time_of_reason WHERE enabled=1 ORDER BY reason_name";
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
												echo "<option value='{$data2['id']}' $selected>{$data2['reason_name']}</option>";
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

							<div class="input-field col m2 s12">
								<i class="material-icons prefix">speaker_notes_outline</i>
								<input id="e_exit_reason" type="text" name="e_exit_reason" value="" class="validate">
								<label for="e_exit_reason" class="">Reason Note: <span class="color-red"> Reason *</span>
								</label>
							</div>


							<?php
							$field_name  = "start_date";
							$field_label = "Start Date";

							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">access_time</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>"
								value="<?= isset(${$field_name}) ? ${$field_name} : ""; ?>" class="datepicker validate">
								<label for="<?= $field_name; ?>"><?= $field_label; ?>
									<span class="color-red">* <?= isset($error[$field_name]) ? $error[$field_name] : ''; ?></span>
								</label>
							</div>
							<?php
							$field_name  = "end_date";
							$field_label = "End Date";
							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">access_time</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>"
								value="<?= isset(${$field_name}) ? ${$field_name} : date("d/m/Y"); ?>" class="datepicker validate">
								<label for="<?= $field_name; ?>"><?= $field_label; ?>
									<span class="color-red">* <?= isset($error[$field_name]) ? $error[$field_name] : ''; ?></span>
								</label>
							</div>

							<?php
							$field_name  = "total_hours";
							$field_label = "Worked Hours";
							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">timer</i>
								<input id="total_hours" type="text" name="<?= $field_name; ?>" value="<?= isset(${$field_name}) ? ${$field_name} : "" ?>" readonly>
								<label for="<?= $field_name; ?>"><?= $field_label; ?>
									<span class="color-red">* <?= isset($error[$field_name]) ? $error[$field_name] : ''; ?></span>
								</label>
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
<!-- <script>
	$(document).ready(function () {
		alert('sss')

    // Initialize Datepicker
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoClose: true,
        onSelect: function () {
            calculateHours();
        }
    });

    function calculateHours() {
        let startDateStr = $("#start_date").val();
        let endDateStr = $("#end_date").val();

        if (startDateStr && endDateStr) {
            let startParts = startDateStr.split('/');
            let endParts = endDateStr.split('/');

            let startDate = new Date(startParts[2], startParts[1] - 1, startParts[0]);
            let endDate = new Date(endParts[2], endParts[1] - 1, endParts[0]);

            if (endDate >= startDate) {
                let diffDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1; // +1 to include start date
                let totalHours = diffDays * 8;
                $("#total_hours").val(totalHours);
            } else {
                $("#total_hours").val(0);
            }
        }
    }

    // Bind event to recalculate hours when date changes
    $("#start_date, #end_date").change(calculateHours);
});
</script> -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function() {

		// Get current date
		let today = new Date();
		let formattedDate = today.getDate().toString().padStart(2, '0') + '/' +
			(today.getMonth() + 1).toString().padStart(2, '0') + '/' +
			today.getFullYear();

		// Set default values in inputs
		$("#start_date").val(formattedDate);
		$("#end_date").val(formattedDate);

		// Initialize Datepicker
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true
		}).on('changeDate', function() {
			calculateHours();
		});

		function calculateHours() {
			let startDateStr = $("#start_date").val();
			let endDateStr = $("#end_date").val();

			if (startDateStr && endDateStr) {
				let startParts = startDateStr.split('/');
				let endParts = endDateStr.split('/');

				// Create Date objects (months are 0-based in JS)
				let startDate = new Date(startParts[2], startParts[1] - 1, startParts[0]);
				let endDate = new Date(endParts[2], endParts[1] - 1, endParts[0]);

				if (endDate >= startDate) {
					// Ensure both dates are full days (no partial time issues)
					startDate.setHours(0, 0, 0, 0);
					endDate.setHours(23, 59, 59, 999);

					// Correct Days Calculation
					let diffTime = endDate - startDate;
					let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // Ensure rounding up
					let totalHours = diffDays * 8; // 8 hours per day

					$("#total_hours").val(totalHours);

					// Debugging Logs
					console.log("Start Date:", startDate);
					console.log("End Date:", endDate);
					console.log("Difference in Days:", diffDays);
					console.log("Total Hours:", totalHours);
				} else {
					$("#total_hours").val(0);
					console.log("End date is before start date. Total hours set to 0.");
				}
			} else {
				console.log("Please select both start and end dates.");
			}
		}

		// Trigger calculation on load
		calculateHours();

		// Bind event to recalculate hours when date changes
		$("#start_date, #end_date").on('change', calculateHours);
	});
</script>