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
		$sql_c_upd = "UPDATE purchase_orders set enabled = 0,
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
		$sql_c_upd = "UPDATE purchase_orders set 	enabled 	= 1,
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
					SELECT * FROM (
						SELECT '' AS offer_no, aa.po_no, aa.estimated_receive_date, aa.vender_invoice_no, aa.order_status, aa.sub_user_id,
								aa2.*, 
								aa.id AS po_id_master, aa2.id AS po_detail_id,  b.product_uniqueid, b.product_desc,  
								d.category_name, c.vender_name, aa.po_date, aa.enabled AS order_enabled, e.status_name, aa.add_by_user_id as add_by_user_id_order,
								f.status_name as po_status_name
						FROM purchase_orders aa
						LEFT JOIN purchase_order_detail aa2 ON aa.id = aa2.po_id 
						LEFT JOIN products b ON b.id = aa2.product_id
						LEFT JOIN product_categories d ON d.id = b.product_category
						LEFT JOIN venders c ON c.id = aa.vender_id
						LEFT JOIN inventory_status e ON e.id = aa2.order_product_status
						LEFT JOIN inventory_status f ON f.id = aa.order_status
						WHERE 1=1 
						AND aa.offer_id = 0
						
						UNION ALL 
						
						SELECT 	a.offer_no AS offer_no, aa.po_no, aa.estimated_receive_date, aa.vender_invoice_no, aa.order_status, aa.sub_user_id, 
								aa2.*, 
								aa.id AS po_id_master, aa2.id AS po_detail_id, b.product_uniqueid, b.product_desc,  
								d.category_name, c.vender_name, aa.po_date, aa.enabled AS order_enabled, e.status_name, aa.add_by_user_id as add_by_user_id_order, 
								f.status_name as po_status_name
						FROM purchase_orders aa
						INNER JOIN purchase_order_detail aa2 ON aa.id = aa2.po_id 
						INNER JOIN offers a ON a.id = aa.offer_id
						INNER JOIN offer_detail a1 ON a1.offer_id = a.id AND a1.id = aa2.offer_detail_id
						INNER JOIN products b ON b.id = aa2.product_id
						INNER JOIN product_categories d ON d.id = b.product_category
						INNER JOIN venders c ON c.id = a.vender_id
						INNER JOIN inventory_status e ON e.id = aa2.order_product_status
						INNER JOIN inventory_status f ON f.id = aa.order_status
						WHERE 1=1 
						AND aa.offer_id != 0

					) AS t1
					WHERE 1=1 ";
if (po_permisions("ALL PO in List") != '1') {
	$sql_cl	.= " AND (t1.sub_user_id = '" . $_SESSION['user_id'] . "' || t1.add_by_user_id_order = '" . $_SESSION['user_id'] . "') ";
}
$sql_cl	.= " 		ORDER BY  t1.po_id_master DESC, t1.product_uniqueid, t1.product_condition  ";
// echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);
$page_heading 	= "List Purchase Orders ";
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
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="pagelength50_3">
												<thead>
													<tr>
														<?php
														$headings = '<th class="sno_width_60">S.No</th>
																	<th>
																		PO No</br>
																		PO Date
																	</th>
																	<th>Expected Arrival Date<br>Warranty in Days after Arrival </th>
 																	<th>Vendor / Invoice# / OfferID</th>
																	<th>Product ID / Condition</th>
																	<th>Quantity / Price</th>
 																	<th>To Be</th>
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
																					FROM purchase_order_detail_logistics a
 																					LEFT JOIN inventory_status b ON b.id = a.logistics_status
																					WHERE a.po_id = '" . $id . "'";
															$result2			= $db->query($conn, $sql2);  ?>
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
																			///*

																			/////////////////////////////////////////////////////////
																			/////////////////////////////////////////////////////////
																			$sql2_2				= "SELECT a.*
																									FROM purchase_order_detail_logistics a
																									WHERE a.po_id = '" . $id . "'";
																			$result2_2			= $db->query($conn, $sql2_2);
																			$total_logistics	= $db->counter($result2_2);

																			$j              = 0;
																			$sql3           = " SELECT a.*
																								FROM purchase_order_detail_logistics a
																								WHERE a.po_id = '" . $id . "'
																								AND arrived_date IS NOT NULL ";
																			$result3        = $db->query($conn, $sql3);
																			$total_arrived  = $db->counter($result3);
																			if ($data['order_status'] == $arrival_status_dynamic) {
																				if ($total_logistics > 0 && $total_arrived > 0) {
																					$total_arrival_percentage = ($total_arrived / $total_logistics) * 100;
																					if ($total_arrival_percentage > 0) {
																						echo " (" . round(($total_arrival_percentage)) . "%)";
																					}
																				}
																			}

																			/////////////////////////////////////////////////////////
																			/////////////////////////////////////////////////////////
																			$total_items_ordered = 0;
																			$sql2       = " SELECT sum(a.order_qty) as order_qty
																							FROM purchase_order_detail a
																							WHERE a.po_id = '" . $id . "'
																							AND a.enabled = 1 ";
																			$result_r2    = $db->query($conn, $sql2);
																			$count2     = $db->counter($result_r2);
																			if ($count2 > 0) {
																				$row_lg2                = $db->fetch($result_r2);
																				$total_items_ordered    = $row_lg2[0]['order_qty'];
																			}
																			$sql3               = "SELECT a.id
																									FROM purchase_order_detail_receive a
																									INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id 
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
																			}
																			if ($data['order_status'] == $diagnost_status_dynamic) {
																				$sql3               = "SELECT a.id
																										FROM purchase_order_detail_receive a
																										INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id 
																										WHERE b.po_id = '" . $id . "'
																										AND a.serial_no_barcode IS NOT NULL
																										AND a.serial_no_barcode !=''
																										AND a.is_diagnost = 1
																										AND a.enabled = 1 ";
																				$result3            = $db->query($conn, $sql3);
																				$total_diagnosed    = $db->counter($result3);
																				if ($total_received > 0) {
																					if ($total_diagnosed > 0) {
																						$total_diagnosed_percentage = ($total_diagnosed / $total_received) * 100;
																						if ($total_received > 0) {
																							echo " (" . round(($total_diagnosed_percentage)) . "%)";
																						}
																					}
																				}
																			}


																			if ($data['order_status'] == $inventory_status_dynamic) {
																				$sql3               = " SELECT a.id
																										FROM product_stock a
																										INNER JOIN purchase_order_detail_receive b ON b.id = a.receive_id
																										INNER JOIN purchase_order_detail c ON c.id = b.po_detail_id 
																										WHERE c.po_id = '" . $id . "'
 																										AND a.enabled = 1 AND b.enabled = 1 ";
																				$result3            = $db->query($conn, $sql3);
																				$total_inventory    = $db->counter($result3);
																				if ($total_received > 0 && $total_inventory > 0) {
																					$total_inventory_percentage = ($total_inventory / $total_received) * 100;
																					if ($total_inventory_percentage > 0) {
																						echo " (" . round(($total_inventory_percentage)) . "%)";
																					}
																				}
																			}
																			//*/ 
																			?>
																		</span>
																	</span>
																</td>
																<td>
																	<?php echo dateformat2($data['estimated_receive_date']); ?>
																	<?php if ($data['warranty_period_in_days'] > 0) {
																		echo "</br>" . $data['warranty_period_in_days'] . " Days";
																	} ?>
																</td>
																<td>
																	<?php echo $data['vender_name']; ?></br>
																	<?php echo $data['vender_invoice_no']; ?></br>
																	<?php echo $data['offer_no']; ?>
																</td>
																<td>
																	<?php echo $data['product_uniqueid']; ?></br>
																	<?php echo $data['product_condition']; ?>
																	<br>
																	<span class="chip green lighten-5">
																		<span class="green-text">
																			<?php
																			echo $data['status_name'];
																			if ($data['order_product_status'] == $arrival_status_dynamic) {
																				$sql3			= " SELECT a.*
																									FROM purchase_order_detail_logistics a
																									WHERE a.po_id = '" . $id . "'
																									AND arrived_date IS NOT NULL ";
																				$result3		= $db->query($conn, $sql3);
																				$total_arrived	= $db->counter($result3);
																				if ($total_logistics > 0 && $total_arrived > 0) {
																					echo " (" . round((($total_arrived / $total_logistics) * 100)) . "%)";
																				}
																			}

																			$total_items_ordered    = $data['order_qty'];
																			$sql3               	= "SELECT a.id
																										FROM purchase_order_detail_receive a
																										INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id 
																										WHERE b.po_id = '" . $id . "'
																										AND a.po_detail_id = '" . $po_detail_id . "'
																										AND a.enabled = 1 ";
																			$result3            	= $db->query($conn, $sql3);
																			$total_received     	= $db->counter($result3);
																			if ($data['order_product_status'] == $receive_status_dynamic) {
																				if ($total_items_ordered > 0 && $total_received > 0) {
																					$total_received_percentage = ($total_received / $total_items_ordered) * 100;
																					if ($total_received_percentage > 0) {
																						echo " (" . round(($total_received_percentage)) . "%)";
																					}
																				}
																			}

																			$sql3               = "SELECT a.id
																									FROM purchase_order_detail_receive a
																									INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id 
																									WHERE b.po_id = '" . $id . "'
																									AND a.po_detail_id = '" . $po_detail_id . "'
																									AND a.serial_no_barcode IS NOT NULL
																									AND a.serial_no_barcode !=''
																									AND a.is_diagnost = 1
																									AND a.enabled = 1 ";
																			$result3            = $db->query($conn, $sql3);
																			$total_diagnosed    = $db->counter($result3);
																			if ($data['order_product_status'] == $diagnost_status_dynamic) {
																				if ($total_received > 0) {
																					if ($total_diagnosed > 0) {
																						$total_diagnosed_percentage = ($total_diagnosed / $total_received) * 100;
																						if ($total_diagnosed_percentage > 0) {
																							echo " (" . round(($total_diagnosed_percentage)) . "%)";
																						}
																					}
																				}
																			}

																			if ($data['order_product_status'] == $inventory_status_dynamic) {
																				$sql3               = " SELECT * FROM product_stock a
																										INNER JOIN purchase_order_detail_receive b ON b.id = a.receive_id
																										INNER JOIN purchase_order_detail c ON c.id = b.po_detail_id 
																										WHERE c.po_id = '" . $id . "'
																										AND b.po_detail_id = '" . $po_detail_id . "'
																										AND a.enabled = 1 AND b.enabled = 1 ";
																				$result3            = $db->query($conn, $sql3);
																				$total_inventory    = $db->counter($result3);
																				if ($total_received > 0 && $total_inventory > 0) {
																					$total_inventory_percentage = ($total_inventory / $total_received) * 100;
																					if ($total_inventory_percentage > 0) {
																						echo " (" . round(($total_inventory_percentage)) . "%)";
																					}
																				}
																			}  ?>
																		</span>
																	</span>
																</td>
																<td>
																	<b>Qty:</b> <?php echo $data['order_qty']; ?></br>
																	<b>Price:</b> <?php echo number_format($data['order_price'], 2); ?></br>
																	<b>Cost:</b> <?php echo number_format($data['order_qty'] * $data['order_price'], 2); ?>
																</td>
																<td>
																	<b>Tested:</b> <?php echo $data['is_tested']; ?></br>
																	<b>Wiped:</b> <?php echo $data['is_wiped']; ?></br>
																	<b>Imaged:</b> <?php echo $data['is_imaged']; ?>
																</td>
																<td>
																	<?php
																	$j = 0;
																	if ($total_logistics > 0) {
																		$row2 = $db->fetch($result2);
																		foreach ($row2 as $data2) {
																			$tracking_no = $data2['tracking_no'];
																			if (po_permisions("Arrival") == 1) { ?>
																				<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&cmd3=add&active_tab=tab3&id=" . $id . "&detail_id=" . $tracking_no) ?>">
																					<?= $data2['tracking_no']; ?>
																					<span class="chip green lighten-5">
																						<span class="green-text">
																							<?php echo $data2['status_name']; ?>
																						</span>
																					</span>
																				</a><br>
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
																		if ($data['order_enabled'] == 0 && access("edit_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=enabled&id=" . $id) ?>">
																				<i class="material-icons dp48">add</i>
																			</a> &nbsp;&nbsp;
																		<?php } else if ($data['order_enabled'] == 1 && access("delete_perm") == 1) { ?>
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