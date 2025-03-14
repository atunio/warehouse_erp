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
		$sql_c_upd = "UPDATE sales_orders set enabled = 0,
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
		$sql_c_upd = "UPDATE sales_orders set 	enabled 	= 1,
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
						SELECT  aa.so_no, aa.estimated_ship_date, aa.order_status, 
								aa.id AS sale_order_id_master, aa.customer_invoice_no, aa.order_date, aa.enabled AS order_enabled, aa.add_by_user_id AS add_by_user_id_order,
								c.id as customer_id, c.customer_name, f.status_name AS po_status_name,aa.stage_status
						FROM sales_orders aa
						LEFT JOIN customers c ON c.id = aa.customer_id
						LEFT JOIN inventory_status f ON f.id = aa.order_status
						WHERE 1=1  
					) AS t1
					WHERE 1=1 ";
if (po_permisions("ALL SO in List") != '1') {
	$sql_cl	.= " AND (t1.sub_user_id = '" . $_SESSION['user_id'] . "' || t1.add_by_user_id_order = '" . $_SESSION['user_id'] . "') ";
}
if (isset($flt_so_no) && $flt_so_no != "") {
	$sql_cl 	.= " AND t1.so_no LIKE '" . trim($flt_so_no) . "' ";
}
if (isset($flt_customer_id) && $flt_customer_id != "") {
	$sql_cl 	.= " AND t1.customer_id = '" . trim($flt_customer_id) . "' ";
}
if (isset($flt_customer_invoice_no) && $flt_customer_invoice_no != "") {
	$sql_cl 	.= " AND t1.customer_invoice_no = '" . trim($flt_customer_invoice_no) . "' ";
}
if (isset($flt_so_status) && $flt_so_status != "") {
	$sql_cl 	.= " AND t1.order_status = '" . trim($flt_so_status) . "' ";
}
if (isset($flt_stage_status) && $flt_stage_status != "") {
	$sql_cl 	.= " AND t1.stage_status = '" . $flt_stage_status . "' ";
}
$sql_cl	.= " 	GROUP BY t1.sale_order_id_master	
				ORDER BY  t1.sale_order_id_master DESC";
// echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);
$page_heading 	= "List Sales Orders ";
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
												<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=profile&cmd=add&active_tab=tab1") ?>">
													New
												</a>
											<?php }
											if (access("add_perm") == 1) { ?>
												<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=listing") ?>">
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

									<form method="post" autocomplete="off"  action="<?php echo "?string=" . encrypt('module_id=' . $module_id . '&page=' . $page); ?>">
										<input type="hidden" name="is_Submit" value="Y" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<div class="row">
											<br>
											<div class="input-field col m1 s12 custom_margin_bottom_col">
												<?php
												$field_name     = "flt_so_no";
												$field_label	= "SO#";
												$sql1			= "SELECT DISTINCT so_no FROM sales_orders WHERE 1=1 ";
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
																<option value="<?php echo $data2['so_no']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['so_no']) { ?> selected="selected" <?php } ?>><?php echo $data2['so_no']; ?></option>
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
												$field_name     = "flt_customer_id";
												$field_label	= "Vendor";
												$sql1			= " SELECT DISTINCT b.id, b.customer_name
																	FROM sales_orders a
																	INNER JOIN customers b ON b.id = a.customer_id
 																	WHERE 1=1";
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
																<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['customer_name']; ?> </option>
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
												$field_name = "flt_customer_invoice_no";
												$field_label = "Vendor Invoice#";
												$sql1			= "SELECT DISTINCT customer_invoice_no FROM sales_orders WHERE 1=1 ";
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
																<option value="<?php echo $data2['customer_invoice_no']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['customer_invoice_no']) { ?> selected="selected" <?php } ?>><?php echo $data2['customer_invoice_no']; ?></option>
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

											<div class="input-field col m1 s12 custom_margin_bottom_col">
												<?php
												$field_name     = "flt_so_status";
												$field_label	= "Status";
												$sql1			= "SELECT *  FROM inventory_status WHERE 1=1 AND id IN(11, 28)  ";
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
											<div class="input-field col m1 s12 custom_margin_bottom_col">
												<?php
												$field_name     = "flt_stage_status";
												$field_label	= "Stage";
												$sql1			= "SELECT *  FROM stages_status WHERE 1=1 AND enabled = 1  ";
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
																<option value="<?php echo $data2['status_name']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['status_name']) { ?> selected="selected" <?php } ?>><?php echo $data2['status_name']; ?></option>
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
												<a href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=listing") ?>">All</a>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="text_align_right">
											<?php 
											$table_columns	= array('SNo', 'SO No', 'Order Date', 'Customer', 'Invoice No', 'Category Wise Qty','Actions');
											$k 				= 0;
											foreach($table_columns as $data_c1){?>
												<label>
													<input type="checkbox" value="<?= $k?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1)?>" checked="checked">
													<span><?= $data_c1?></span>
												</label>&nbsp;&nbsp;
											<?php 
												$k++;
											}?> 
										</div>
									</div>
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display pagelength50">
												<thead>
													<tr>
														<?php
														$headings = "";
														foreach($table_columns as $data_c){
															if($data_c == 'SNo'){
																$headings .= '<th class="sno_width_60 col-'.set_table_headings($data_c).'">'.$data_c.'</th>';
															}
															else{
																$headings .= '<th class="col-'.set_table_headings($data_c).'">'.$data_c.'</th> ';
															}
														} 
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
															$id	= $data['sale_order_id_master'];
													?>
															<tr>
																<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
																<td class="col-<?= set_table_headings($table_columns[1]);?>">
																	<?php
																	if ($data['order_enabled'] == 1 && access("edit_perm") == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab1") ?>">
																			<?php echo $data['so_no']; ?>
																		</a>
																	<?php } else {
																		echo $data['so_no'];
																	} ?>
																	<span class="chip green lighten-5">
																		<span class="green-text">
																			<?php
																			echo $data['po_status_name'];
																			///*
																			/////////////////////// Total //////////////////////////////////
																			$total_items_ordered = 0;
																			$sql2       = " SELECT count(a.id) as order_qty
																							FROM sales_order_detail a
																							WHERE a.sales_order_id = '" . $id . "'
																							AND a.enabled = 1 ";
																			$result_r2	= $db->query($conn, $sql2);
																			$count2		= $db->counter($result_r2);
																			if ($count2 > 0) {
																				$row_lg2                = $db->fetch($result_r2);
																				$total_items_ordered    = $row_lg2[0]['order_qty'];
																			}
																			/////////////////////// Total //////////////////////////////////

																			/////////////////////// Packing //////////////////////////////////
																			$sql3               = "SELECT a.id
																									FROM sales_order_detail_packing a
 																									WHERE a.sale_order_id = '" . $id . "'
																									AND a.enabled = 1 ";
																			$result3            = $db->query($conn, $sql3);
																			$total_packed     = $db->counter($result3);
																			
																			if ($data['order_status'] == $packing_status_dynamic) {
																				if ($total_items_ordered > 0 && $total_packed > 0) {
																					$total_packed_percentage = ($total_packed / $total_items_ordered) * 100;
																					if ($total_packed_percentage > 0) {
																						echo " (" . round(($total_packed_percentage)) . "%)";
																					}
																				}
																			}
																			/////////////////////// Packing //////////////////////////////////

																			/////////////////////// Shipment ///////////////////////////////// 

																			$sql3               = " SELECT a.id
																									FROM sales_order_detail_packing a
																									WHERE a.sale_order_id = '" . $id . "'
																									AND a.enabled = 1 AND a.is_shipped =1 ";
																			$result3            = $db->query($conn, $sql3);
																			$total_shipped     = $db->counter($result3);
																			if ($data['order_status'] == $shipped_status_dynamic) {
																				if ($total_items_ordered > 0 && $total_shipped > 0) {
																					$total_shipped_percentage = ($total_shipped / $total_items_ordered) * 100;
																					if ($total_shipped_percentage > 0) {
																						echo " (" . round(($total_shipped_percentage)) . "%)";
																					}
																				}
																			}
																			/////////////////////// Shipment /////////////////////////////////
																			?>
																		</span>
																	</span>
																	<span class="chip blue lighten-5">
																		<span class="blue-text">
																			<?php echo "".$data['stage_status']; ?>
																		</span>
																	</span>
																</td>
																<td class="col-<?= set_table_headings($table_columns[2]);?>"><?php echo dateformat2($data['order_date']); ?></td>
 																<td class="col-<?= set_table_headings($table_columns[3]);?>"><?php echo $data['customer_name']; ?></td>
																<td class="col-<?= set_table_headings($table_columns[4]);?>"><?php echo $data['customer_invoice_no']; ?></td>
																<td class="col-<?= set_table_headings($table_columns[5]);?>">
																	<?php
																	$sql3		= " SELECT d.category_name, sum(aa2.order_qty) as order_qty
																					FROM  sales_order_detail aa2 
																					INNER JOIN product_stock c ON c.id = aa2.product_stock_id
                                													INNER JOIN products c1 ON c1.id = c.product_id
																					INNER JOIN product_categories d ON d.id = c1.product_category
																					WHERE 1=1 
																					AND aa2.sales_order_id = '" . $id . "'  
																					GROUP BY c1.product_category ";
																	$result3	= $db->query($conn, $sql3);
																	$count3		= $db->counter($result3);
																	if ($count3 > 0) {
																		$row3 = $db->fetch($result3);
																		$k = 0;
																		foreach ($row3 as $data3) { ?>
																			<div class="row">
																				<div class="col m6 s12" style="text-align: right;"><?php echo $data3['category_name']; ?>:</div>
																				<div class="col m6 s12"><?php echo "" . $data3['order_qty']; ?></div>
																			</div>
																	<?php
																			$k++;
																		}
																	} ?>
																</td>
																<td class="text-align-center col-<?= set_table_headings($table_columns[6]);?>">
																	<?php
																	if ($data['order_enabled'] == 1 && access("print_perm") == 1) { ?>
																		<a href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/print_invoice.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
																			<i class="material-icons dp48">print</i>
																		</a>&nbsp;&nbsp;
																	<?php }
																	if ($data['stage_status'] != 'Committed') {
																		if ($data['order_enabled'] == 1 && access("edit_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab1") ?>">
																				<i class="material-icons dp48">edit</i>
																			</a> &nbsp;&nbsp;
																		<?php }
																		if ($data['order_enabled'] == 0 && access("delete_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=listing&cmd=enabled&id=" . $id) ?>">
																				<i class="material-icons dp48">add</i>
																			</a> &nbsp;&nbsp;
																		<?php } else if ($data['order_enabled'] == 1 && access("delete_perm") == 1 ) { ?>
																			<a class="" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=listing&cmd=disabled&id=" . $id) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																				<i class="material-icons dp48">delete</i>
																			</a> &nbsp;&nbsp;
																	<?php }
																	}  ?>
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