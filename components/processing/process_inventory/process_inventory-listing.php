<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

$sql_cl			= " SELECT a.id, a.location_id, b.sub_location_name, b.sub_location_type, 
							GROUP_CONCAT(DISTINCT CONCAT( '', COALESCE(d.first_name, ''), ' ', COALESCE(d.middle_name, ''), ' ', COALESCE(d.last_name, ''), ' (', COALESCE(d.username, ''), ')') ) AS task_user_details,
							a.add_date
					FROM users_bin_for_processing a
					INNER JOIN warehouse_sub_locations b ON b.id = a.location_id
					INNER JOIN users d ON d.id = a.bin_user_id 
					WHERE 1 = 1 ";
if (po_permisions("ALL Bins") != '1') {
	$sql_cl	.= " AND a.bin_user_id = '" . $_SESSION['user_id'] . "' ";
}
$sql_cl	.= "GROUP BY a.id
			ORDER BY a.id DESC";
// echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);
$page_heading 	= "Bins For Processing";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="col m8 l8">
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
									<h4 class="card-title"><?php echo $page_heading; ?></h4>
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display pagelength50_3">
												<thead>
													<tr>
														<?php
														$headings = '<th class="sno_width_60">S.No</th>
																	<th>Location / Bin</th> 
																	<th>Task Users</th> 
																	<th>Assign Date</th> 
 																	<th>Action</th>';
														echo $headings;
														?>
													</tr>
												</thead>
												<tbody>
													<?php
													$i = 0;
													if ($count_cl > 0) {
														$row_cl = $db->fetch($result_cl);
														foreach ($row_cl as $data) {
															$id = $data['id']; ?>
															<tr>
																<td style="text-align: center;"><?php echo $i + 1; ?></td>
																<td>
																	<?php
																	echo $data['sub_location_name'];
																	if ($data['sub_location_type'] != "") {
																		echo "(" . ucwords(strtolower($data['sub_location_type'])) . ")";
																	} ?>
																</td>
																<td>
																	<?php
																	if ($data['task_user_details'] != "   ()") {
																		echo "" . $data['task_user_details'] . "";
																	} ?>
																</td>
																<td><?php echo "" . dateformat2($data['add_date']) . ""; ?></td>
																<td class="text-align-center">
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&detail_id=" . $id . "&cmd2=add") ?>">
																		<i class="material-icons dp48">list</i>
																	</a>
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

			<div class="content-overlay"></div>
		</div>
	</div>
</div>