<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

if (isset($cmd) && ($cmd == 'disabled' || $cmd == 'enabled') && access("delete_perm") == 0) {
	$error['msg'] = "You do not have edit permissions.";
} else {
	if (isset($cmd) && $cmd == 'disabled') {
		$sql_c_upd = "UPDATE timesheets set enabled = 0,
												update_date = '" . $add_date . "' ,
												update_by 	= '" . $_SESSION['username'] . "' ,
												update_ip 	= '" . $add_ip . "'
					WHERE id = '" . $id . "' ";
		$enabe_ok = $db->query($conn, $sql_c_upd);
		if ($enabe_ok) {
			$msg['msg_success'] = "Record has been disabled.";
		} else {
			$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
		}
	}
	if (isset($cmd) && $cmd == 'enabled') {
		$sql_c_upd = "UPDATE timesheets set enabled 	= 1,
											update_date = '" . $add_date . "' ,
											update_by 	= '" . $_SESSION['username'] . "' ,
											update_ip 	= '" . $add_ip . "'
					WHERE id = '" . $id . "' ";
		$enabe_ok = $db->query($conn, $sql_c_upd);
		if ($enabe_ok) {
			$msg['msg_success'] = "Record has been enabled.";
		}
	}
}
$sql_cl		= "SELECT  a1.*, a.e_full_name, b.department_name
				FROM timesheets a1
				JOIN `employee_profile` a ON a.id =a1.`employee_id`
				JOIN `departments` b  ON b.id =  a1.`department_id` "; 
if(isset($flt_emp_id) && $flt_emp_id != ""){
	$sql_cl		.= " AND a.id = '".$flt_emp_id."'";
}
if(isset($flt_emp_dept_id) && $flt_emp_dept_id != ""){
	$sql_cl		.= " AND b.id = '".$flt_emp_dept_id."'";
}
if (isset($flt_clock_date) && $flt_clock_date != "") {
    $dateParts = explode('/', $flt_clock_date);
    if (count($dateParts) === 3) {
        $flt_clock_date1 = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
        $flt_clock_date1 = strtotime($flt_clock_date1);
        $flt_clock_date1 = date('Y-m-d', $flt_clock_date1);
        $sql_cl .= " AND a1.clock_date = '" . $flt_clock_date1 . "'";
    }
}else{
	$flt_clock_date1 = date('Y-m-d');
    $sql_cl .= " AND a1.clock_date = '" . $flt_clock_date1 . "'";
}


$sql_cl		.= " ORDER BY a.enabled DESC, DATE_FORMAT(a1.clock_date, '%Y%m%d'), DATE_FORMAT(a1.clocked_in, '%H%i'), DATE_FORMAT(a1.clocked_out, '%H%i') ";  //echo $sql_cl;
$result_cl	= $db->query($conn, $sql_cl);
$count_cl	= $db->counter($result_cl);
$page_heading 	= "Timesheet";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<div class="section section-data-tables">
				<div class="row">
					<div class="col s12">
						<div class="card custom_margin_card_table_top">
							<div class="card-content custom_padding_card_content_table_top_bottom">
								<div class="row">
									<div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
										<h6 class="media-heading">
											<?php echo $page_heading; ?>
										</h6>
									</div>
									<div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
										<?php
										if (access("add_perm") == 1) { ?>
											<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=add&cmd2=add") ?>">
												New
											</a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Page Length Options -->
				<div class="row">
					<div class="col s12">
						<div class="card custom_margin_card_table_top">
							<div class="card-content custom_padding_card_content_table_top">
								<?php
								if (isset($error['msg'])) { ?>
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card red lighten-5">
												<div class="card-content red-text">
													<p><?php echo $error['msg']; ?></p>
												</div>
												<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										</div>
									</div>
								<?php } else if (isset($msg['msg_success'])) { ?>
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card green lighten-5">
												<div class="card-content green-text">
													<p><?php echo $msg['msg_success']; ?></p>
												</div>
												<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										</div>
									</div>
								<?php } ?>
								<br>
								<form method="post" autocomplete="off" enctype="multipart/form-data">
									<input type="hidden" name="is_Submit" value="Y" />
									<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
									<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																						echo encrypt($_SESSION['csrf_session']);
																					} ?>">
									<div class="row">
										<div class="input-field col m2 s12">
											<?php
											$field_name 	= "flt_emp_id";
											$field_label 	= "Employee";
											$sql1 			= "SELECT * FROM `employee_profile` WHERE enabled=1 AND emp_status = 'Active' ORDER BY e_full_name ";
											$result1 		= $db->query($conn, $sql1);
											$count1 		= $db->counter($result1);
											?>
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">ALL</option>
													<?php
													if ($count1 > 0) {
														$row1	= $db->fetch($result1);
														foreach ($row1 as $data2) { ?>
															<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['e_full_name']; ?></option>
													<?php }
													} ?>
												</select>
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
										</div>
										<div class="input-field col m2 s12">
											<?php
											$field_name 	= "flt_emp_dept_id";
											$field_label 	= "Department";
											$sql1 			= "SELECT * FROM `departments` WHERE enabled=1 ORDER BY department_name ";
											$result1 		= $db->query($conn, $sql1);
											$count1 		= $db->counter($result1);
											?>
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">ALL</option>
													<?php
													if ($count1 > 0) {
														$row1	= $db->fetch($result1);
														foreach ($row1 as $data2) { ?>
															<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['department_name']; ?></option>
													<?php }
													} ?>
												</select>
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
										</div>
										<div class="input-field col m2 s12">
											<?php
											$field_name  = "flt_clock_date";
											$field_label = "Current Date (d/m/Y)";
											?>
											<i class="material-icons prefix">event</i>
											<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>"
												value="<?= isset(${$field_name}) ? ${$field_name} : date("d/m/Y"); ?>"
												class="datepicker validate <?= isset(${$field_name . "_valid"}) ? ${$field_name . "_valid"} : ''; ?>">
											<label for="<?= $field_name; ?>">
												<?= $field_label; ?>
												<span class="color-red"> <?= isset($error[$field_name]) ? $error[$field_name] : ''; ?></span>
											</label>
										</div>
										<div class="input-field col m3 s12">
											<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button>
											&nbsp;
											<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">All</a>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="text_align_right">
										<?php 
										$table_columns	= array('SNo', 'Employee Name','Department Name','Clock Date','Clocked In','Clocked Out','Worked Hours','Actions');
										$k 				= 0;
										foreach($table_columns as $data_c1){?>
											<label>
												<input type="checkbox" value="<?= $k?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1)?>" checked="checked">
												<span><?= $data_c1?></span>
											</label>&nbsp;&nbsp;
										<?php 
											$k++;
										}?> 
									</div>
								</div>
								<div class="row">
									<div class="col s12">
										<table id="page-length-option" class="display pagelength50_3">
											<thead>
												<tr>
													<?php
													$headings = "";
													foreach($table_columns as $data_c){
														if($data_c == 'SNo'){
															$headings .= '<th class="sno_width_60 col-'.set_table_headings($data_c).'">'.$data_c.'</th>';
														}
														else{
															$headings .= '<th class="col-'.set_table_headings($data_c).'">'.$data_c.'</th> ';
														}
													} 
													echo $headings;
													?>
													?>
												</tr>
											</thead>
											<tbody>
												<?php
												$i = 0;
												if ($count_cl > 0) {
													$row_cl = $db->fetch($result_cl);
													foreach ($row_cl as $data) {
															$id = $data['id'];    
															if (!empty($data['clocked_in']) && !empty($data['clocked_out'])) {
																$in_time = strtotime($data['clocked_in']);
																$out_time = strtotime($data['clocked_out']);
														
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
														 ?>
														<tr>
															<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
															<td class="col-<?= set_table_headings($table_columns[1]);?>"><?php echo $data['e_full_name']; ?></td>
															<td class="col-<?= set_table_headings($table_columns[2]);?>"><?php echo $data['department_name']; ?></td>
															<td class="col-<?= set_table_headings($table_columns[3]);?>"><?php echo $data['clock_date']; ?></td>
															<td class="col-<?= set_table_headings($table_columns[4]);?>"><?php echo $data['clocked_in']; ?></td>
															<td class="col-<?= set_table_headings($table_columns[5]);?>"><?php echo $data['clocked_out']; ?></td>
															<td class="col-<?= set_table_headings($table_columns[6]);?>"><?php echo $worked_hours; ?></td>
													

															<?php //*/ 
															?>
															<td class="text-align-center col-<?= set_table_headings($table_columns[7]);?>">
																<?php
																if ($data['enabled'] == 1 && access("view_perm") == 1) { ?>
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&id=" . $id) ?>" title="Edit">
																		<i class="material-icons dp48">edit</i>
																	</a> &nbsp;&nbsp;
																<?php }
																if ($data['enabled'] == 0 && access("edit_perm") == 1) { ?>
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=enabled&id=" . $id) ?>" title="Enable">
																		<i class="material-icons dp48">add</i>
																	</a> &nbsp;&nbsp;
																<?php } else if ($data['enabled'] == 1 && access("delete_perm") == 1) { ?>
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=disabled&id=" . $id) ?>" title="Disable" onclick="return confirm('Are you sure, You want to delete this record?')">
																		<i class="material-icons dp48">delete</i>
																	</a>&nbsp;&nbsp;
																<?php } ?>
															</td>
														</tr>
												<?php $i++;
													}
												} ?>
											<tfoot>
												<tr>
													<?php echo $headings; ?>
												</tr>
											</tfoot>
										</table>
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
	</div>
</div>

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
</script>