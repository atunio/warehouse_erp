<?php
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
	if ($date_from == "" && $date_to == "") {
		$error['msg'] = "Enter Date From or Date To";
		$date_from_valid = "invalid";
		$date_to_valid = "invalid";
	}
	if ($emp_id == "" && $emp_code == "") {
		$error['msg'] = "Please enter Emp ID or Emp Code";
	}
	if (empty($error)) {
		$sql_cl 		= "	SELECT a.*, b.e_full_name, b.id AS e_emp_id, b.emp_code
							FROM " . $selected_db_name . ".emp_leave a
							INNER JOIN " . $selected_db_name . ".employee_profile b ON b.id = a.emp_id
							WHERE a.school_admin_id = '" . $school_admin_id . "' "; //echo $sql_cl;
		if ($emp_id != "") {
			$sql_cl .= "AND b.id = '" . $emp_id . "' ";
		}
		if ($emp_code != "") {
			$sql_cl .= "AND b.emp_code = '" . $emp_code . "' ";
		}
		if ($date_from == "" && $date_to != "") {
			$date_to1 = str_replace("-", "", convert_date_mysql_slash($date_to));
			$sql_cl .= "AND date_format(a.leave_to, '%Y%m%d') <= '" . $date_to1 . "' ";
		} else if ($date_to == "" && $date_from != "") {
			$date_from1 = str_replace("-", "", convert_date_mysql_slash($date_from));
			$sql_cl .= "AND date_format(a.leave_from, '%Y%m%d') >= '" . $date_from1 . "' ";
		} else if ($date_from != "" && $date_to != "") {
			$date_to1 	= str_replace("-", "", convert_date_mysql_slash($date_to));
			$date_from1 = str_replace("-", "", convert_date_mysql_slash($date_from));
			$sql_cl .= "AND date_format(a.leave_from, '%Y%m%d') >= '" . $date_from1 . "' ";
			$sql_cl .= "AND date_format(a.leave_to, '%Y%m%d') <= '" . $date_to1 . "' ";
		}
		$sql_cl 	   .= " ORDER BY date_format(a.leave_from, '%Y%m%d') DESC, date_format(a.leave_to, '%Y%m%d') DESC "; //echo $sql_cl;
		$result_cl 		= $db->query($conn, $sql_cl);
		$count_cl 		= $db->counter($result_cl);
		if ($count_cl == 0) {
			$error['msg'] = "No record found";
		}
	}
}
$page_heading = "Employee's Leaves";
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
							<li class="breadcrumb-item"><a href="home">Home</a>
							</li>
							</li>
							<li class="breadcrumb-item active">Leave List</li>
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
												<i class="material-icons prefix">person_outline</i>
												<input id="emp_id" type="text" name="emp_id" value="<?php if (isset($emp_id)) {
																										echo $emp_id;
																									} ?>" class="validate <?php if (isset($emp_id_valid)) {
																																										echo $emp_id_valid;
																																									} ?>">
												<label for="emp_id">Emp ID</label>
											</div>
											<div class="input-field col m3 s12">
												<i class="material-icons prefix">person_outline</i>
												<input id="emp_code" type="text" name="emp_code" value="<?php if (isset($emp_code)) {
																											echo $emp_code;
																										} ?>" class="validate <?php if (isset($emp_code_valid)) {
																																												echo $emp_code_valid;
																																											} ?>">
												<label for="emp_code">Emp Code</label>
											</div>
											<div class="col m2 s12">
												<div class="input-field">
													<i class="material-icons prefix">date_range</i>
													<input id="date_from" type="text" name="date_from" class="datepicker" value="<?php if (isset($date_from)) {
																																		echo $date_from;
																																	} else {
																																		echo date('d/m/Y');
																																	} ?>" class="validate datepicker <?php if (isset($date_from_valid)) {
																																																												echo $date_from_valid;
																																																											} ?>">
													<label for="date_from">Date From (d/m/Y)</label>
												</div>
											</div>
											<div class="col m2 s12">
												<div class="input-field">
													<i class="material-icons prefix">date_range</i>
													<input id="date_to" type="text" name="date_to" class="datepicker" value="<?php if (isset($date_to)) {
																																	echo $date_to;
																																} else {
																																	echo date('d/m/Y');
																																} ?>" class="validate datepicker <?php if (isset($date_to_valid)) {
																																																										echo $date_to_valid;
																																																									} ?>">
													<label for="date_to">Date To (d/m/Y)</label>
												</div>
											</div>
											<div class="input-field col m1 s12">
												<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col s12">
											<?php
											if ($count_cl > 0) { ?>
												<table id="page-length-option" class="display">
													<thead>
														<tr>
															<th width="5%">S.No</th>
															<th>Emp ID</th>
															<th>Emp Code</th>
															<th>Emp Name</th>
															<th> Leave Type</th>
															<th>Leave Duration<br> Status</th>
															<th> Leave Category</th>
															<th>Days</th>
															<th>Actions</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$i = 0;
														if ($count_cl > 0) {
															$row_cl = $db->fetch($result_cl);
															foreach ($row_cl as $data) {
																$id	= $data['id']; ?>
																<tr data-id="<?php echo $id; ?>">
																	<td><?php echo $i + 1; ?></td>
																	<td><?php echo $data['e_emp_id']; ?></td>
																	<td><?php echo $data['emp_code']; ?></td>
																	<td><?php echo $data['e_full_name']; ?></td>
																	<td><?php echo $data['leave_type']; ?></td>
																	<td>
																		<span class="<?php if ($data['leave_status'] == 'Approved') { ?>green-text<?php } else { ?>red-text<?php } ?>""><?php echo $data['leave_status']; ?></span><br>
														<?php echo dateformat2($data['leave_from']); ?> - <?php echo dateformat2($data['leave_to']); ?>
													</td> 
													<td><?php echo $data['leave_category']; ?></td>   
													<td><?php echo $data['days_deduction']; ?></td>   
													<td class=" text-align-center">
																			<a href="javascript:void(0)" class="<?php if ($data['enabled'] == '1') { ?>green-text<?php } else { ?>red-text<?php } ?>" onclick="change_status(this,'<?php echo $id ?>')"><?php echo ($data['enabled'] == '1') ? 'Enable' : 'Disable'; ?></a>
																			&nbsp;&nbsp;
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add&cmd=edit&id=" . $data['id']) ?>">
																				<i class="material-icons dp48">edit</i>
																			</a> &nbsp;&nbsp;
																	</td>
																</tr>
														<?php
																$i++;
															}
														} ?>
													<tfoot>
														<tr>
															<th width="5%">S.No</th>
															<th>Emp ID</th>
															<th>Emp Code</th>
															<th>Emp Name</th>
															<th> Leave Type</th>
															<th>Leave Duration<br> Status</th>
															<th> Leave Category</th>
															<th>Days</th>
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