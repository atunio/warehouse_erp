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
		$sql_c_upd = "UPDATE products set enabled = 0,
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
		$sql_c_upd = "UPDATE products set 	enabled 	= 1,
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
$sql_cl		= "	SELECT a.*, b.category_name, c.status_name
				FROM products a
				LEFT JOIN product_categories b ON b.id = a.product_category
				LEFT JOIN inventory_status c ON c.id = a.inventory_status
				WHERE 1=1  ";
if (isset($flt_product_id) && $flt_product_id != "") {
	$sql_cl 	.= " AND a.product_uniqueid LIKE '%" . trim($flt_product_id) . "%' ";
}
if (isset($flt_product_desc) && $flt_product_desc != "") {
	$sql_cl 	.= " AND a.product_desc LIKE '%" . trim($flt_product_desc) . "%' ";
}
if (isset($flt_product_category) && $flt_product_category != "") {
	$sql_cl 	.= " AND a.product_category = '" . trim($flt_product_category) . "%' ";
}
$sql_cl	.= " ORDER BY a.enabled DESC, a.id DESC "; // echo $sql_cl;
$result_cl	= $db->query($conn, $sql_cl);
$count_cl	= $db->counter($result_cl);
$page_heading 	= "List of Products";
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
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=add&cmd2=add") ?>">
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
									<form method="post" autocomplete="off" enctype="multipart/form-data">
										<input type="hidden" name="is_Submit" value="Y" />
										<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<div class="row">
											<?php
											$field_name = "flt_product_id";
											$field_label = "ProductID";
											?>
											<div class="input-field col m2 s12">
												<i class="material-icons prefix">description</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>">
												<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
											</div>
											<?php
											$field_name = "flt_product_desc";
											$field_label = "Product Description";
											?>
											<div class="input-field col m2 s12">
												<i class="material-icons prefix">description</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>">
												<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
											</div>
											<div class="input-field col m2 s12">
												<?php
												$field_name 	= "flt_product_category";
												$field_label 	= "Category";
												$sql1 			= "SELECT * FROM product_categories WHERE enabled = 1 AND category_type = 'Device' ORDER BY category_name ";
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
																<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['category_name']; ?></option>
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

											<div class="input-field col m1 s12">
												<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button>
											</div>
											<div class="input-field col m1 s12">
												<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">All</a>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display pagelength50_3">
												<thead>
													<tr>
														<?php
														$headings = '<th class="sno_width_60">S.No</th>
																	<th>Product ID / Description / Detail</th>
																	<th>Category</th>
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
																<td>
																	<?php echo $data['product_uniqueid']; ?></br>
																	<?php echo ucwords(strtolower(substr($data['product_desc'], 0, 50) . "")); ?></br>
																	<?php
																	$detail_desc = $data['detail_desc'];
																	if ($detail_desc != '') {
																		echo substr($detail_desc, 0, 50) . "";
																		if (strlen($detail_desc) > 50) {
																			echo "...";
																		}
																	} ?>
																</td>
																<td><?php echo $data['category_name']; ?></td>
																<td class="text-align-center">
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

			<div class="content-overlay"></div>
		</div>
	</div>
</div>