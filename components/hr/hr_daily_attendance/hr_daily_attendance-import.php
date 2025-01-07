<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$email_send = 0;
} else {
	$email_send = 1;
}
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 				= new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$school_admin_id 	= $_SESSION["school_admin_id"];
$user_id 	= $_SESSION["user_id"];
$title_heading 		= "Import Employees Attendance";
$button_val = "Import";
$id 				= "";
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
$rec_added = 0;
if (isset($is_Submit) && $is_Submit == 'Y') {
	$tmp_name = $_FILES['file']['tmp_name'];
	if (isset($tmp_name) && $tmp_name == "") {
		$error['msg'] = "Please Browse Excel File.";
		$tmp_name_valid = "invalid";
	}
	if (empty($error)) {
		if ($tmp_name != "") {
			$file_mimes = array('application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			$mime		= mime_content_type($_FILES['file']['tmp_name']); //echo "-------------------------".$mime;die;
			if (in_array($mime, $file_mimes)) {
				require "phpspreadsheet/vendor/autoload.php";
				$reader 		= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				$spreadsheet 	= $reader->load($_FILES['file']['tmp_name']);
				$worksheet 		= $spreadsheet->getActiveSheet();
				$highestRow 	= $worksheet->getHighestRow(); // total number of rows
				//echo "-------------------------Total Rows: ".$highestRow." <br>"; 
				$stop_running 	= 0;
				$already 		= 0;
				$EmpCodeAlready 	= "";
				for ($i = 0; $i < $highestRow; $i++) {
					$EmpCode 			= trim($worksheet->getCellByColumnAndRow(1, $i + 1)->getValue());
					$AttendanceDate 	= trim($worksheet->getCellByColumnAndRow(2, $i + 1)->getValue());
					$TimeIn 			= trim($worksheet->getCellByColumnAndRow(3, $i + 1)->getValue());
					$TimeOut 			= trim($worksheet->getCellByColumnAndRow(4, $i + 1)->getValue());
					$Attendance 		= trim($worksheet->getCellByColumnAndRow(5, $i + 1)->getValue());
					if ($i == 0) {
						//echo "-------------------------  EmpCode: ".$AttendanceDate." <br>"; 
						if ($EmpCode != 'EmpCode') {
							$error["msg"] = "Column A is not valid column name (" . $EmpCode . "), Please check format.";
							$i = $highestRow;
							$stop_running = 1;
						} else if ($AttendanceDate != 'AttendanceDate') {
							$error["msg"] = "Column B is not valid column name (" . $AttendanceDate . "), Please check format.";
							$i = $highestRow;
							$stop_running = 1;
						} else if ($TimeIn != 'TimeIn') {
							$error["msg"] = "Column C is not valid column name (" . $TimeIn . "), Please check format.";
							$i = $highestRow;
							$stop_running = 1;
						} else if ($TimeOut != 'TimeOut') {
							$error["msg"] = "Column D is not valid column name (" . $TimeOut . "), Please check format.";
							$i = $highestRow;
							$stop_running = 1;
						} else if ($Attendance != 'Attendance') {
							$error["msg"] = "Column E is not valid column name (" . $Attendance . "), Please check format.";
							$i = $highestRow;
							$stop_running = 1;
						}
						$rec_added++;
					} else {
						// echo "-------------------------  TimeIn: ".timeValidation($TimeIn)." <br>"; die;
						if (mysqlDateValidation($AttendanceDate) == 1 && timeValidation($TimeIn) == 1 && timeValidation($TimeOut) == 1) {
							if (empty($error) && $EmpCode != "" && $AttendanceDate != "") {
								// echo "Record No ".$i.": ";  echo "AttendanceDate: ".$AttendanceDate; echo ", TimeIn: ".$TimeIn; 
								// echo ", TimeOut: ".$TimeOut; echo ", Attendance: ".$Attendance; echo "<br>"; die;
								$att_time_in_seconds 	= strtotime($TimeIn);
								$att_time_out_seconds 	= strtotime($TimeOut);
								$sql1 		= "	SELECT b.*
												FROM " . $selected_db_name . ".employee_profile a 
												INNER JOIN " . $selected_db_name . ".emp_attendance b ON b.emp_id = a.id
												WHERE a.school_admin_id = '" . $school_admin_id . "' 
												AND a.emp_status 	= 'Active'
												AND b.att_date 		= '" . $AttendanceDate . "'
												AND a.emp_code 		= '" . $EmpCode . "' "; //echo $sql1;die;
								$result1 	= $db->query($conn, $sql1);
								$count1 	= $db->counter($result1);
								if ($count1 == 0) {
									$sql12 		= "	SELECT a.*
													FROM " . $selected_db_name . ".employee_profile a 
													WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
													AND a.emp_status 		= 'Active' 
													AND a.emp_code 			= '" . $EmpCode . "' ";
									$result12 	= $db->query($conn, $sql12);
									$count12 	= $db->counter($result12);
									if ($count12 > 0) {
										$row12		= $db->fetch($result12);
										$emp_id	 	= $row12[0]['id'];
										$sql = "INSERT INTO " . $selected_db_name . ".emp_attendance(school_admin_id, emp_id, att_date, att_time_in, att_time_in_seconds,
																								att_time_out, att_time_out_seconds, is_absent, add_ip, add_date, add_by)
												VALUES('" . $school_admin_id . "', '" . $emp_id . "', '" . $AttendanceDate . "', '" . $TimeIn . "', '" . $att_time_in_seconds . "',
													'" . $TimeOut . "', '" . $att_time_out_seconds . "', '" . $Attendance . "', '" . $add_ip . "', '" . $add_date . "', '" . $_SESSION['username'] . "') "; //echo $sql;die;
										$db->query($conn, $sql);
										$rec_added++;
									} else {
										$already++;
										$EmpCodeAlready .= $EmpCode . ", ";
									}
								} else {
									$row1		= $db->fetch($result1);
									$att_id	 	= $row1[0]['id'];
									$sql_c_up 			= "	UPDATE " . $selected_db_name . ".emp_attendance 
																					SET att_time_in 			= '" . $TimeIn . "',
																						att_time_in_seconds 	= '" . $att_time_in_seconds . "',
																						att_time_out 			= '" . $TimeOut . "',
																						att_time_out_seconds 	= '" . $att_time_out_seconds . "',
																						is_absent 				= '" . $Attendance . "',

																						update_date 			= '" . $add_date . "',
																						update_by 	 			= '" . $_SESSION['username'] . "',
																						update_ip 	 			= '" . $add_ip . "' 
															WHERE school_admin_id 	= '" . $school_admin_id . "' 
															AND id 					= '" . $att_id . "' "; //echo $sql_c_up;

									$db->query($conn, $sql_c_up);
									$rec_added++;
								}
							}
						} else {
							$already++;
							$EmpCodeAlready .= $EmpCode . ", ";
						}
					}
				}
				if ($rec_added == $highestRow) {
					$msg["msg_success"] = ($rec_added - 1) . " Records have been Imported Successfully";
				} elseif ($stop_running == 1) {
					$error["msg"] .= " Records have not imported";
				} else {
					if ($already > 0) {
						$error["msg"] = ($rec_added - 1) . " Records have been Imported Successfully. Email/s: already exist in system: " . $EmpCodeAlready;
					} else {
						$error["msg"] = ($rec_added - 1) . " Records have been Imported Successfully";
					}
				}
			} else {
				$error["msg"] = "Invalid File Format, Please import only Excel File";
			}
		} else {
			$error["msg"] = "Please Browse File";
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
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">Student Profile List</a>
								</li>
							</ol>
						</div>
						<div class="col s2 m6 l6">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
								List
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
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
					<form method="post" autocomplete="off" enctype="multipart/form-data">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<div id="file-upload" class="section">
							<!--Default version-->
							<div class="row section">
								<div class="col s12 m12 12">
									<h4>Excel Format, Column Names should be accordingly and Cell Format must be text</h4>
									<table>
										<tr>
											<td style="border: 1px solid #000;">A</td>
											<td style="border: 1px solid #000;">B</td>
											<td style="border: 1px solid #000;">C</td>
											<td style="border: 1px solid #000;">D</td>
											<td style="border: 1px solid #000;">E</td>
										</tr>
										<tr>
											<td style="border: 1px solid #000;">EmpCode</td>
											<td style="border: 1px solid #000;">AttendanceDate</td>
											<td style="border: 1px solid #000;">TimeIn</td>
											<td style="border: 1px solid #000;">TimeOut</td>
											<td style="border: 1px solid #000;">Attendance</td>
										</tr>
										<tr>
											<td style="border: 1px solid #000;">E12350</td>
											<td style="border: 1px solid #000;"><?php echo date("Y"); ?>-12-25</td>
											<td style="border: 1px solid #000;">09:00 AM</td>
											<td style="border: 1px solid #000;">05:00 PM</td>
											<td style="border: 1px solid #000;">Present</td>
										</tr>
									</table>
								</div>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row section">
								<div class="col s2 m2 2">
									<br><br>
									<b>Browse Excel File</b>
								</div>
								<div class="col s5 m5 5">
									<input type="file" name="file" id="input-file-now" class="dropify" data-default-file="" />
								</div>
							</div>
							<div class="divider mb-1 mt-1"></div>
						</div>
						<div class="row">
							<div class="row">
								<div class="input-field col m1 s12"></div>
								<div class="input-field col m3 s12">
									<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?></button>
								</div>
								<div class="input-field col m8 s12"></div>
							</div>
						</div>
					</form>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->