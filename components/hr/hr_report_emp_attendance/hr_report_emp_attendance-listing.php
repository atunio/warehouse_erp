<?php
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$date_from 			= date('d/m/Y');
$date_to 			= date('d/m/Y');
$db 				= new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$school_admin_id 	= $_SESSION["school_admin_id"];
$user_id 	= $_SESSION["user_id"];
$page_heading 		= "Attendace Summary Report";
function custom_echo($x, $length)
{
	$x = strip_tags($x);
	if (strlen($x) <= $length) {
		echo $x;
	} else {
		$y = substr($x, 0, $length) . '...';
		echo $y;
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
						<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $page_heading; ?></span></h5>
						<ol class="breadcrumbs mb-0">
							<li class="breadcrumb-item"><a href="home">Home</a>
							</li>
							</li>
							<li class="breadcrumb-item active">List</li>
						</ol>
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
									<form method="post" action="components/<?php echo $module; ?>/hr_emp_attendance_pdf_files.php" style="display: inline" target="_blank">
										<input type="hidden" name="v_id" value="<?php echo $data['id']; ?>">
										<input type="hidden" name="module" value="<?php echo $module; ?>">
										<input type="hidden" name="is_Submit" value="Y" />
										<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<div class="row">
											<div class="col m2 s12">
												<div class="input-field">
													<i class="material-icons prefix">date_range</i>
													<input id="date_from" type="text" name="date_from" class="datepicker" value="<?php if (isset($date_from)) {
																																		echo $date_from;
																																	} ?>" required>
													<label for="date_from">Date From (d/m/Y)</label>
												</div>
											</div>
											<div class="col m2 s12">
												<div class="input-field">
													<i class="material-icons prefix">date_range</i>
													<input id="date_to" type="text" name="date_to" class="datepicker" value="<?php if (isset($date_to)) {
																																	echo $date_to;
																																} ?>" required>
													<label for="date_to">Date To (d/m/Y)</label>
												</div>
											</div>
											<div class="col m8 s12">
												<div class="input-field">
													<i class="material-icons prefix">person_outline</i>
													<?php $result1 	= list_of_employees($db, $conn, $school_admin_id, $selected_db_name); ?>
													<select id="emp_id" name="emp_id" class="validate select2 browser-default select2-hidden-accessible <?php if (isset($emp_id_valid)) {
																																							echo $emp_id_valid;
																																						} ?>">
														<option value="">All Employee</option>
														<?php
														$count1 	= $db->counter($result1);
														if ($count1 > 0) {
															$row1	= $db->fetch($result1);
															foreach ($row1 as $data) { ?>
																<option value="<?php echo $data['id']; ?>" <?php if (isset($emp_id) && $emp_id == $data['id']) { ?> selected="selected" <?php } ?>><?php echo ucwords(strtolower($data['e_full_name'])); ?> => <?php echo $data['department_name']; ?> => Emp ID: <?php echo $data['id']; ?> => Leave Balance: <?php echo $data['e_earn_leave']; ?> => Sick Leavs: <?php echo $data['e_sick_leave']; ?> => Casual Leavs: <?php echo $data['e_casual_leave']; ?></option>
														<?php }
														} ?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="input-field col m4 s12"></div>
											<div class="input-field col m3 s12">
												<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action">Attendance Summary Report</button>
											</div>
											<div class="input-field col m4 s12"></div>
										</div>
									</form>
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