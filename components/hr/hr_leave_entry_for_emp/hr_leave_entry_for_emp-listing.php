<?php
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db = new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$school_admin_id 	= $_SESSION["school_admin_id"];
$user_id 	= $_SESSION["user_id"];
$sql_cl 		= "	SELECT a.*, b.e_full_name, b.id AS e_emp_id, b.emp_code
					FROM " . $selected_db_name . ".emp_leave a
					INNER JOIN " . $selected_db_name . ".employee_profile b ON b.id = a.emp_id
					WHERE a.school_admin_id = '" . $school_admin_id . "'
					ORDER BY a.enabled DESC, a.id DESC "; //echo $sql_cl;
$result_cl 		= $db->query($conn, $sql_cl);
$count_cl 		= $db->counter($result_cl);
$page_heading = "Employee's Leaves";
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
									<h4 class="card-title"><?php echo $page_heading; ?></h4>
									<div class="row">
										<div class="col s12">
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
														<th> Status</th>
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
																<td><?php echo dateformat2($data['leave_from']); ?> - <?php echo dateformat2($data['leave_to']); ?></td>
																<td><?php echo $data['leave_category']; ?></td>
																<td><?php echo $data['days_deduction']; ?></td>
																<td>
																	<span class="<?php if ($data['leave_status'] == 'Approved') { ?>green-text<?php } else { ?>red-text<?php } ?>""><?php echo $data['leave_status']; ?></span>
											</td>   
											<td class=" text-align-center">
																		<?php
																		if ($data['leave_status'] != 'Approved') { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add&cmd=edit&id=" . $data['id']) ?>">
																				<i class="material-icons dp48">edit</i>
																			</a>
																		<?php } ?>
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
														<th> Status</th>
														<th>Actions</th>
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

			<div class="content-overlay"></div>
		</div>
	</div>
</div>