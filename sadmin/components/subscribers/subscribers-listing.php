<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_sadmin_directory_access();
}
$db = new mySqlDB;
if (isset($cmd) && $cmd == 'disabled') {
	$sql_c_upd = "UPDATE subscribers_users set enabled = 0,
											update_date = '" . $add_date . "' ,
											update_by 	= '" . $_SESSION['username_super_admin'] . "' ,
											update_ip 	= '" . $add_ip . "'
				WHERE id = '" . $id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg['msg_success'] = "User has been deactived.";
	} else {
		$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
	}
}
if (isset($cmd) && $cmd == 'enabled') {
	$sql_c_upd = "UPDATE subscribers_users set enabled = 1,
										update_date = '" . $add_date . "' ,
										update_by 	= '" . $_SESSION['username_super_admin'] . "' ,
										update_ip 	= '" . $add_ip . "'
				WHERE id = '" . $id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg['msg_success'] = "User has been actived successfully.";
	}
}

$sql_cl = "	SELECT a.*, b.reg_status  AS subscriber_stauts, a2.username, a2.a_password, a2.first_name, a2.middle_name, a2.last_name, a2.email, a2.phone_no
			FROM subscribers_users a
			INNER JOIN users a2 ON a2.subscriber_users_id = a.id AND a2.user_type = 'Admin'
			INNER JOIN user_reg_status b ON b.id = a.reg_status
			WHERE  a.reg_status != '6'
			ORDER BY a.enabled DESC, a.reg_status, a.id DESC "; // echo $sql_cl;
$result_cl 	= $db->query($conn, $sql_cl);
$count_cl 	= $db->counter($result_cl);
$page_heading = "List of Subscribers";
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
							<li class="breadcrumb-item active">List</li>
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
														<th>S.No</th>
														<th>Username</th>
														<th>Company Name</th>
														<th>Admin Full Name</th>
														<th>Status</th>
														<th>Email<br>Phone</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i = 0;
													if ($count_cl > 0) {
														$row_cl = $db->fetch($result_cl);
														foreach ($row_cl as $data) { ?>
															<tr>
																<td class="<?php if ($data['enabled'] == 0) { ?> color-red <?php } ?>"><?php echo $i + 1; ?></td>
																<td class="<?php if ($data['enabled'] == 0) { ?> color-red <?php } ?>">
																	<br><?php echo $data['username']; ?>
																</td>
																<td class="<?php if ($data['enabled'] == 0) { ?> color-red <?php } ?>">
																	<?php echo $data['company_name']; ?>
																</td>
																<td class="<?php if ($data['enabled'] == 0) { ?> color-red <?php } ?>">
																	<?php echo $data['first_name']; ?> <?php echo $data['middle_name']; ?> <?php echo $data['last_name']; ?>
																</td>
																<td class="<?php if ($data['enabled'] == 0) {
																				echo "color-red";
																			} else if ($data['subscriber_stauts'] == 'Approved') {
																				echo "color-green";
																			} else {
																				echo "color-blue";
																			} ?>">
																	<?php
																	if ($data['enabled'] == 0) { ?> Deactivated <?php } else {
																												echo $data['subscriber_stauts'];
																											} ?>
																</td>
																<td class="<?php if ($data['enabled'] == 0) { ?> color-red <?php } ?>">
																	<?php echo $data['email'];
																	if ($data['email_verification_status'] == 'Verified') { ?>
																		<i class="material-icons">check</i>
																	<?php } ?>
																	<br><?php echo $data['phone_no']; ?>
																</td>
																<td class="text-align-center">
																	<?php
																	if ($data['enabled'] == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add&cmd=edit&id=" . $data['id']) ?>">
																			<i class="material-icons dp48">edit</i>
																		</a> &nbsp;&nbsp;
																	<?php }
																	if ($data['enabled'] == 0) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=listing&cmd=enabled&id=" . $data['id']) ?>">
																			<i class="material-icons dp48">add</i>
																		</a> &nbsp;&nbsp;
																	<?php } else if ($data['enabled'] == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=listing&cmd=disabled&id=" . $data['id']) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
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
														<th>S.No</th>
														<th>Username</th>
														<th>Company Name</th>
														<th>Admin Full Name</th>
														<th>Status</th>
														<th>Email<br>Phone</th>
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