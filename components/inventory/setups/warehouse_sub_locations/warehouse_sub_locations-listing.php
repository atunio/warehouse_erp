<?php
if (!isset($module)) {
	require_once('../../../../conf/functions.php');
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
		$sql_c_upd = "UPDATE warehouse_sub_locations set 	enabled 	= 0,
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
		$sql_c_upd = "UPDATE warehouse_sub_locations set enabled = 1,
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

$sql_cl 		= "	SELECT  a.*, b.warehouse_name
					FROM warehouse_sub_locations a 
					LEFT JOIN warehouses b ON b.id = a.warehouse_id
					ORDER BY a.enabled DESC, a.id DESC  "; // echo $sql_cl;
$result_cl 		= $db->query($conn, $sql_cl);
$count_cl 		= $db->counter($result_cl);
$page_heading 	= "List of " . $main_menu_name;
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
					<div class="col m2 l2">
						<?php if (access("add_perm") == 1) { ?>
							<a class="btn waves-effect waves-light blue darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import") ?>">
								Import
							</a>
						<?php } ?>
					</div>
					<div class="col m2 l2">
						<?php if (access("add_perm") == 1) { ?>
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=add") ?>">
								Add New
							</a>
						<?php } ?>
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
																	<th>Warehouse</th>
																	<th>Purpose</th>
																	<th>Sub Location</th>
																	<th>Type</th>
																	<th>Mobile</th>
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
															$id = $data['id'];  ?>
															<tr>
																<td style="text-align: center;"><?php echo $i + 1; ?></td>
																<td><?php echo ucwords(strtolower($data['warehouse_name'])); ?></td>
																<td><?php echo ucwords(strtolower($data['purpose'])); ?></td>
																<td><?php echo ucwords(strtolower($data['sub_location_name'])); ?></td>
																<td><?php echo ucwords(strtolower($data['sub_location_type'])); ?></td>
																<td><?php echo ucwords(strtolower($data['is_mobile'])); ?></td>
																<td class="text-align-center">
																	<?php
																	if ($data['enabled'] == 1 && access("view_perm") == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&id=" . $id) ?>">
																			<i class="material-icons dp48">edit</i>
																		</a> &nbsp;&nbsp;
																	<?php }
																	if ($data['enabled'] == 0 && access("edit_perm") == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=enabled&id=" . $id) ?>">
																			<i class="material-icons dp48">add</i>
																		</a> &nbsp;&nbsp;
																	<?php } else if ($data['enabled'] == 1 && access("delete_perm") == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=disabled&id=" . $id) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
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

			<div class="content-overlay"></div>
		</div>
	</div>
</div>