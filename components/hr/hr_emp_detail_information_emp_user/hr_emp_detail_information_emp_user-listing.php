<?php
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db = new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$school_admin_id 	= $_SESSION["school_admin_id"];
$user_id 	= $_SESSION["user_id"];
$sql_cl 		= "	SELECT a.*
					FROM " . $selected_db_name . ".employee_profile a
					WHERE a.school_admin_id = '" . $school_admin_id . "'
					AND user_id = '" . $user_id . "'
					ORDER BY a.enabled DESC, a.id DESC "; //echo $sql_cl;
$result_cl 		= $db->query($conn, $sql_cl);
$count_cl 		= $db->counter($result_cl);
$page_heading = "Employment Information"; ?>
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
									<h4 class="card-title"><?php echo $page_heading; ?></h4>
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display">
												<thead>
													<tr>
														<th width="5%">S.No</th>
														<th>Emp ID</th>
														<th>Emp Code</th>
														<th>Full Name<br>Parent Name</th>
														<th>Gender<br>Date Of Birth</th>
														<th>Phone Number<br>Email</th>
														<th>Status</th>
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
																<td><?php echo $id; ?></td>
																<td><?php echo $data['emp_code']; ?></td>
																<td><?php echo $data['e_full_name']; ?><br><?php echo $data['parent_name']; ?></td>
																<td><?php echo $data['e_gender']; ?><br><?php echo dateformat2($data['e_birth_date']); ?></td>
																<td><?php echo $data['e_phone']; ?><br><?php echo $data['e_email']; ?></td>
																<td>
																	<?php
																	if ($data['emp_status'] == 'Active') { ?>
																		<span class="chip green lighten-5">
																			<span class="green-text"><?php echo $data['emp_status']; ?></span>
																		</span>
																	<?php } else { ?>
																		<span class="chip red lighten-5"><span class="red-text"><?php echo $data['emp_status']; ?></span></span>
																	<?php } ?>
																</td>
																<td class="text-align-center">
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit&cmd=edit&active_tab=tab1&id=" . $data['id']) ?>">
																		<i class="material-icons dp48">edit</i>
																	</a>
																</td>
															</tr>
													<?php
															$i++;
														}
													}
													if (isset($cmd) && $cmd == 'delete' && isset($id)) {
														$sql_del 			= "	DELETE FROM " . $selected_db_name . ".employee_profile WHERE id = '" . $id . "' ";
														$ok = $db->query($conn, $sql_del);
														if ($ok) {
															$error['msg'] = "Record Deleted Successfully";
														} else {
															$error['msg'] = "There is Error, record did not delete, Please check it again OR contact Support Team.";
														}
													} ?>
												<tfoot>
													<tr>
														<th width="5%">S.No</th>
														<th>ID</th>
														<th>Employment Code</th>
														<th>Full Name<br>Parent Name<br>Gender</th>
														<th>Date Of Birth</th>
														<th>Phone Number<br>Email</th>
														<th>Status</th>
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