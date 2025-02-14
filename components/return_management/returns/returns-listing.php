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
} 

else {
	if (isset($cmd) && $cmd == 'disabled') {
		$sql_c_upd = "UPDATE returns set enabled = 0,
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
		$sql_c_upd = "UPDATE returns set 	enabled 	= 1,
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
$sql_cl			="	SELECT aa.return_no,aa.removal_order_id, aa.return_status, aa.id,c.store_name, aa.return_date, aa.enabled AS order_enabled, 
						aa.add_by_user_id AS add_by_user_id_order, f.status_name AS ro_status_name
					FROM  `returns` aa 
					LEFT JOIN stores c ON c.id = aa.store_id
					LEFT JOIN inventory_status f ON f.id = aa.return_status
					WHERE 1=1   "; 
if (isset($flt_return_no) && $flt_return_no != "") {
	$sql_cl 	.= " AND aa.return_no LIKE '%" . trim($flt_return_no) . "%' ";
}
if (isset($flt_store_id) && $flt_store_id != "") {
	$sql_cl 	.= " AND aa.`store_id` = '" . trim($flt_store_id) . "' ";
}
if (isset($flt_removal_order_id) && $flt_removal_order_id != "") {
	$sql_cl 	.= " AND aa.removal_order_id LIKE '%" . trim($flt_removal_order_id) . "%' ";
}
if (isset($flt_return_status) && $flt_return_status != "") {
	$sql_cl 	.= " AND aa.return_status = '" . trim($flt_return_status) . "' ";
}
$sql_cl	.= " 		  ORDER BY id DESC";
 //echo $sql_cl; die;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);

$page_heading 	= "List of Returns ";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<div class="container">
				<div class="section section-data-tables">
					<div class="row">
						<div class="col s12">
							<div class="card custom_margin_card_table_top">
								<div class="card-content custom_padding_card_content_table_top_bottom">
									<div class="row">
										<div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
											<h6 class="media-heading">
												<?php echo $page_heading; ?>
											</h6>
										</div>
										<div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
											<?php
											if (access("add_perm") == 1) { ?>
												<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=add&active_tab=tab1") ?>">
													New
												</a>
											<?php } ?>
											<?php
											if (access("add_perm") == 1) { ?>
												<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import") ?>">
													Import
												</a>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Page Length Options -->
					<div class="row">
						<div class="col s12">
							<div class="card custom_margin_card_table_top">
								<div class="card-content custom_padding_card_content_table_top">
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
									<form method="post" autocomplete="off">
										<input type="hidden" name="is_Submit" value="Y" />
										<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<div class="row">
											<br>
											<div class="input-field col m2 s12 custom_margin_bottom_col">
												<?php
												$field_name     = "flt_return_no";
												$field_label	= "ro#";
												$sql1			= "SELECT DISTINCT return_no FROM returns WHERE 1=1 ";
												$result1		= $db->query($conn, $sql1);
												$count1         = $db->counter($result1);
												?>
												<i class="material-icons prefix">question_answer</i>
												<div class="select2div">
													<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
														<option value="">All</option>
														<?php
														if ($count1 > 0) {
															$row1    = $db->fetch($result1);
															foreach ($row1 as $data2) { ?>
																<option value="<?php echo $data2['return_no']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['return_no']) { ?> selected="selected" <?php } ?>><?php echo $data2['return_no']; ?></option>
														<?php }
														} ?>
													</select>
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"><?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
														</span>
													</label>
												</div>
											</div>

											<div class="input-field col m3 s12 custom_margin_bottom_col">
												<?php
												$field_name     = "flt_store_id";
												$field_label	= "Store";
												$sql1			= " SELECT DISTINCT c.* FROM `returns` a 
																	JOIN `return_items_detail` b  ON a.`id`= b.return_id
																	JOIN stores c ON c.`id` = a.`store_id` ORDER BY a.`id`";
												$result1		= $db->query($conn, $sql1);
												$count1         = $db->counter($result1);
												?>
												<i class="material-icons prefix">question_answer</i>
												<div class="select2div">
													<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
														<option value="">All</option>
														<?php
														if ($count1 > 0) {
															$row1    = $db->fetch($result1);
															foreach ($row1 as $data2) { ?>
																<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['store_name']; ?> </option>
														<?php }
														} ?>
													</select>
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"><?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
														</span>
													</label>
												</div>
											</div>

											<div class="input-field col m3 s12 custom_margin_bottom_col">
												<?php
												$field_name = "flt_removal_order_id";
												$field_label = "Removal Order #";
												$sql1			= " SELECT DISTINCT a.removal_order_id FROM `returns` a 
																	JOIN `return_items_detail` b  ON a.`id`= b.return_id";
												$result1		= $db->query($conn, $sql1);
												$count1         = $db->counter($result1);
												?>
												<i class="material-icons prefix">question_answer</i>
												<div class="select2div">
													<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
														<option value="">All</option>
														<?php
														if ($count1 > 0) {
															$row1    = $db->fetch($result1);
															foreach ($row1 as $data2) { ?>
																<option value="<?php echo $data2['removal_order_id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['removal_order_id']) { ?> selected="selected" <?php } ?>><?php echo $data2['removal_order_id']; ?></option>
														<?php }
														} ?>
													</select>
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"><?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
														</span>
													</label>
												</div>
											</div>

											<div class="input-field col m2 s12 custom_margin_bottom_col">
												<?php
												$field_name     = "flt_return_status";
												$field_label	= "Status";
												$sql1			= "SELECT *  FROM inventory_status WHERE 1=1 AND id IN(1, 3, 4, 5)  ";
												$result1		= $db->query($conn, $sql1);
												$count1         = $db->counter($result1);
												?>
												<i class="material-icons prefix">question_answer</i>
												<div class="select2div">
													<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
														<option value="">All</option>
														<?php
														if ($count1 > 0) {
															$row1    = $db->fetch($result1);
															foreach ($row1 as $data2) { ?>
																<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['status_name']; ?></option>
														<?php }
														} ?>
													</select>
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"><?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
														</span>
													</label>
												</div>
											</div>

											<div class="input-field col m2 s12">
												<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button> &nbsp;&nbsp;
												<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">All</a>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display pagelength50">
												<thead>
													<tr>
														<?php
														$headings = '<th class="sno_width_60">S.No</th>
																	<th>RO#</th>
																	<th>RO Date</th>
 																	<th>Removal Order</th>
																	<th>Category Wise Qty</th>
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
															$id 				= $data['id'];
															$sql2				= "	SELECT a.*, b.status_name
																					FROM return_order_detail_logistics a
 																					LEFT JOIN inventory_status b ON b.id = a.logistics_status
																					WHERE a.return_id = '" . $id . "'";
															$result2			= $db->query($conn, $sql2);?>
															<tr>
																<td style="text-align: center;"><?php echo $i + 1; ?></td>
																<td>
																	<?php
																	if ($data['return_status'] == 1 && access("edit_perm") == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab1") ?>">
																			<?php echo $data['return_no']; ?>
																		</a>
																	<?php } 
																	else {
																		echo $data['return_no'];
																	} ?>
																	<span class="chip green lighten-5">
																		<span class="green-text">
																			<?php
																			echo $data['ro_status_name'];
																			///*
																			/////////////////////////////////////////////////////////
																			/////////////////////////////////////////////////////////
																			$sql2_2				= "SELECT a.*
																									FROM return_order_detail_logistics a
																									WHERE a.return_id = '" . $id . "'";
																			$result2_2			= $db->query($conn, $sql2_2);
																			$total_logistics	= $db->counter($result2_2);

																	
																			$j              = 0;
																			$sql3           = " SELECT a.*
																								FROM returns a
																								WHERE a.id = '" . $id . "'
																								AND return_date IS NOT NULL ";
																			$result3        = $db->query($conn, $sql3);
																			$total_arrived  = $db->counter($result3);
																			if ($data['return_status'] == $arrival_status_dynamic) {
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
																			$sql2       = " SELECT sum(a.return_qty) as return_qty
																							FROM return_items_detail a
																							WHERE a.return_id = '" . $id . "'
																							AND a.enabled = 1 ";
																			$result_r2    = $db->query($conn, $sql2);
																			$count2     = $db->counter($result_r2);
																			if ($count2 > 0) {
																				$row_lg2                = $db->fetch($result_r2);
																				$total_items_ordered    = $row_lg2[0]['return_qty'];
																			}
																			$sql3               = "SELECT a.id
																									FROM return_items_detail_receive a
																									INNER JOIN return_items_detail b ON b.id = a.ro_detail_id 
																									WHERE b.return_id = '" . $id . "'
																									AND a.enabled = 1 ";
																			$result3            = $db->query($conn, $sql3);
																			$total_received     = $db->counter($result3);
																			if ($data['return_status'] == $receive_status_dynamic) {
																				if ($total_items_ordered > 0 && $total_received > 0) {
																					$total_received_percentage = ($total_received / $total_items_ordered) * 100;
																					if ($total_received_percentage > 0) {
																						echo " (" . round(($total_received_percentage)) . "%)";
																					}
																				}
																			}
																			if ($data['return_status'] == $diagnost_status_dynamic) {
																				$sql3               = "SELECT a.id
																										FROM return_items_detail_receive a
																										INNER JOIN return_items_detail b ON b.id = a.ro_detail_id 
																										WHERE b.return_id = '" . $id . "'
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
																			if ($data['return_status'] == $inventory_status_dynamic) {
																				$sql3               = " SELECT a.id
																										FROM product_stock a
																										INNER JOIN return_items_detail_receive b ON b.id = a.receive_id
																										INNER JOIN return_items_detail c ON c.id = b.ro_detail_id 
																										WHERE c.return_id = '" . $id . "'
 																										AND a.enabled = 1 AND b.enabled = 1 ";
																				$result3            = $db->query($conn, $sql3);
																				$total_inventory    = $db->counter($result3);
																				if ($total_received > 0 && $total_inventory > 0) {
																					$total_inventory_percentage = ($total_inventory / $total_received) * 100;
																					if ($total_inventory_percentage > 0) {
																						echo " (" . round(($total_inventory_percentage)) . "%)";
																					}
																				}
																			}?>
																		</span>
																	</span>
																</td>
																<td> <?php echo dateformat2($data['return_date']); ?></td>
																<td>
																	<b>Removal_order_id#: </b><?php echo $data['removal_order_id']; ?></br>
																	<?php  //if ($data['offer_no'] != '') echo "<b>Offer#: </b>" . $data['offer_no']; ?>
																</td>
																<td>
																	<?php
																	$sql3		= " SELECT   d.category_name, sum(aa2.return_qty) as return_qty
																					FROM  return_items_detail aa2 
																					INNER JOIN products b ON b.id = aa2.product_id
																					INNER JOIN product_categories d ON d.id = b.product_category
																					WHERE 1=1 
																					AND aa2.return_id = '" . $id . "'  
																					GROUP BY b.product_category ";
																	$result3	= $db->query($conn, $sql3);
																	$count3		= $db->counter($result3);
																	if ($count3 > 0) {
																		$row3 = $db->fetch($result3);
																		$k = 0;
																		foreach ($row3 as $data3) { ?>
																			<div style="width: 100%;">
																				<div style="width: 80%; display: inline-block; border: 1px solid #eee;"><?php echo $data3['category_name']; ?>: </div>
																				<div style="width: 60px; display: inline-block; border: 1px solid #eee; text-align: center;"><?php echo "" . $data3['return_qty']; ?></div>
																			</div>
																	<?php
																			$k++;
																		}
																	} ?>
																</td>
																<td>
																	<?php
																	$j = 0;
																	if ($total_logistics > 0) {
																		$row2 = $db->fetch($result2);
																		foreach ($row2 as $data2) {
																			$tracking_no = $data2['tracking_no'];?>
																				<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&cmd3=add&active_tab=tab2&id=" . $id) ?>">
																					<?= $data2['tracking_no']; ?>
																					<span class="chip green lighten-5">
																						<span class="green-text">
																							<?php echo $data2['status_name']; ?>
																						</span>
																					</span>
																				</a><br>
																			<?php 
																			$j++;
																		}
																	}?>
																</td>
																<td class="text-align-center">
																	<?php
																	if ($data['order_enabled'] == 1 && access("print_perm") == 1) { ?>
																		<a href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/print_po.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
																			<i class="material-icons dp48">print</i>
																		</a>&nbsp;&nbsp;
																	<?php }
																	if (access("edit_perm") == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab1") ?>">
																			<i class="material-icons dp48">edit</i>
																		</a> &nbsp;&nbsp;
																		<?php }
																	if ($data['return_status'] == 1 || $data['return_status'] == '') {
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