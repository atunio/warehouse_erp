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
		$sql_c_upd = "UPDATE package_materials_orders set enabled = 0,
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
		$sql_c_upd = "UPDATE package_materials_orders set 	enabled 	= 1,
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
$sql_cl			= "	  
					SELECT aa.po_no, aa.vender_invoice_no, aa.order_status, aa.sub_user_id,
							aa2.*, 
							aa.id AS po_id_master, aa.estimated_receive_date, aa.add_by_user_id as add_by_user_id_order, 
							aa2.id AS po_detail_id,  
							b.product_sku, b.package_name,
							d.category_name, c.vender_name, aa.po_date, aa.enabled AS order_enabled, e.status_name,
							f.status_name as po_status_name
					FROM package_materials_orders aa
					LEFT JOIN package_materials_order_detail aa2 ON aa.id = aa2.po_id 
					LEFT JOIN packages b ON b.id = aa2.package_id
					LEFT JOIN product_categories d ON d.id = b.product_category
					LEFT JOIN venders c ON c.id = aa.vender_id
					LEFT JOIN inventory_status e ON e.id = aa2.order_product_status
					LEFT JOIN inventory_status f ON f.id = aa.order_status
					WHERE 1=1 ";
if (po_permisions("ALL PO in List") != '1') {
	$sql_cl	.= " AND (t1.sub_user_id = '" . $_SESSION['user_id'] . "' || t1.add_by_user_id_order = '" . $_SESSION['user_id'] . "') ";
}
$sql_cl	.= " ORDER BY aa.enabled DESC, aa.id DESC, aa2.enabled DESC, aa2.id";
// echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);
$page_heading 	= "List Purchase Orders (Package / Parts)";
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
						<?php
						/*
						if (access("add_perm") == 1) { ?>
							<a class="btn waves-effect waves-light blue darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import") ?>">
								Import
							</a>
						<?php }
						*/ ?>
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
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display pagelength50_3">
												<thead>
													<tr>
														<?php
														$headings = '<th class="sno_width_60">S.No</th>
																	<th>PO No</br>PO Date</th>
  																	<th>Vender / Invoice#</th>
																	<th>Product SKU / Category</th>
																	<th>Quantity / Price</th> 
																	<th>Expected Arrival Date</th>
 																	<th>Tracking / Pro #</th>
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
															$id 				= $data['po_id_master'];
															$po_detail_id		= $data['po_detail_id'];


															$sql2				= "	SELECT a.*, status_name
																					FROM package_materials_order_detail_logistics a
 																					LEFT JOIN inventory_status b ON b.id = a.logistics_status
																					WHERE a.po_id = '" . $id . "'";
															$result2			= $db->query($conn, $sql2);
															$total_logistics	= $db->counter($result2);  ?>
															<tr>
																<td style="text-align: center;"><?php echo $i + 1; ?></td>
																<td>
																	<?php
																	if ($data['order_enabled'] == 1 && access("view_perm") == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab1") ?>">
																			<?php echo $data['po_no']; ?>
																		</a>
																	<?php } else {
																		echo $data['po_no'];
																	} ?>
																	</br>
																	<?php echo dateformat2($data['po_date']); ?>
																	<br>
																	<span class="chip green lighten-5">
																		<span class="green-text">
																			<?php
																			echo $data['po_status_name'];

																			/////////////////////////////////////////////////////////
																			$total_items_ordered = 0;
																			$sql2       = " SELECT sum(a.order_qty) as order_qty
																							FROM package_materials_order_detail a
																							WHERE a.po_id = '" . $id . "'
																							AND a.enabled = 1 ";
																			$result_r2    = $db->query($conn, $sql2);
																			$count2     = $db->counter($result_r2);
																			if ($count2 > 0) {
																				$row_lg2                = $db->fetch($result_r2);
																				$total_items_ordered    = $row_lg2[0]['order_qty'];
																			}
																			$sql3               = "SELECT a.id
																									FROM package_materials_order_detail_receive a
																									INNER JOIN package_materials_order_detail b ON b.id = a.po_detail_id 
																									WHERE b.po_id = '" . $id . "'
																									AND a.enabled = 1 ";
																			$result3            = $db->query($conn, $sql3);
																			$total_received     = $db->counter($result3);
																			if ($data['order_status'] == $receive_status_dynamic) {
																				if ($total_items_ordered > 0 && $total_received > 0) {
																					$total_received_percentage = ($total_received / $total_items_ordered) * 100;
																					if ($total_received_percentage > 0) {
																						echo " (" . round(($total_received_percentage)) . "%)";
																					}
																				}
																			} ?>
																		</span>
																	</span>
																</td>
																<td>
																	<?php echo $data['vender_name']; ?></br>
																	<?php echo $data['vender_invoice_no']; ?>
																</td>
																<td>
																	<?php echo $data['package_name']; ?>
																	<?php
																	if ($data['category_name'] != "") {
																		echo  " (" . $data['category_name'] . ")";
																	} ?>
																</td>
																<td>
																	<?php
																	if ($data['order_qty'] > 0) {
																		echo  "<b>Qty:</b>: " . $data['order_qty'] . "</br>";
																	}
																	if ($data['order_qty'] > 0) {
																		echo  "<b>Price:</b>: " . number_format($data['order_price'], 2) . "</br>";
																	}
																	if ($data['order_qty'] > 0) {
																		echo  "<b>Cost:</b>: " . number_format($data['order_qty'], 2) . "</br>";
																	} ?>
																</td>
																<td>
																	<?php echo dateformat2($data['estimated_receive_date']); ?>
																</td>
																<td>
																	<?php
																	$j = 0;
																	if ($total_logistics > 0) {
																		$row2 = $db->fetch($result2);
																		foreach ($row2 as $data2) {
																			$tracking_no = $data2['tracking_no'];
																			if (po_permisions("Arrival") == 1) { ?>
																				<?= $data2['tracking_no']; ?>
																				<span class="chip green lighten-5">
																					<span class="green-text">
																						<?php echo $data2['status_name']; ?>
																					</span>
																				</span> <br>
																			<?php
																			} else { ?>
																				<?= $data2['tracking_no']; ?>
																				<span class="chip green lighten-5">
																					<span class="green-text">
																						<?php echo $data2['status_name']; ?>
																					</span>
																				</span>
																				<br>
																	<?php
																			}
																			$j++;
																		}
																	} ?>
																</td>
																<td class="text-align-center">
																	<?php
																	if ($data['order_product_status'] == 1 || $data['order_product_status'] == '') {
																		if ($data['order_enabled'] == 1 && access("edit_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&id=" . $id) ?>">
																				<i class="material-icons dp48">edit</i>
																			</a> &nbsp;&nbsp;
																		<?php }
																		if ($data['order_enabled'] == 0 && access("delete_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=enabled&id=" . $id) ?>">
																				<i class="material-icons dp48">add</i>
																			</a> &nbsp;&nbsp;
																		<?php } else if ($data['order_enabled'] == 1 && ($data['po_detail_id'] == "" || $data['po_detail_id'] == null) && access("delete_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=disabled&id=" . $id) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																				<i class="material-icons dp48">delete</i>
																			</a> &nbsp;&nbsp;
																	<?php }
																	} ?>
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