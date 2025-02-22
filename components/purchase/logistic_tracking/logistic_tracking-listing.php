<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];


$sql_cl			= " SELECT * FROM (
						SELECT 'Devices' AS logistic_for, b.id as po_id, b.po_no, b.po_date, c.status_name as po_status_name, b.vender_invoice_no, e.vender_name, d.status_name as logistic_status_name, a.id, a.courier_name, a.tracking_no, a.shipment_date, a.expected_arrival_date, a.logistics_desc, a.add_date, a.logistics_status, b.vender_id
						FROM purchase_order_detail_logistics a
						INNER JOIN purchase_orders b ON b.id = a.po_id
						LEFT JOIN inventory_status c ON c.id = b.order_status
						LEFT JOIN inventory_status d ON d.id = a.logistics_status
						LEFT JOIN venders e ON e.id = b.vender_id
						UNION ALL 
						SELECT 'Packages' AS logistic_for, b.id as po_id, b.po_no, b.po_date, c.status_name as po_status_name, b.vender_invoice_no, e.vender_name, d.status_name as logistic_status_name, a.id, a.courier_name, a.tracking_no, a.shipment_date, a.expected_arrival_date, a.logistics_desc, a.add_date, a.logistics_status, b.vender_id
						FROM package_materials_order_detail_logistics a
						INNER JOIN package_materials_orders b ON b.id = a.po_id
						LEFT JOIN inventory_status c ON c.id = b.order_status
						LEFT JOIN inventory_status d ON d.id = a.logistics_status
						LEFT JOIN venders e ON e.id = b.vender_id 
 					) AS t1 
					WHERE 1=1 ";
if (isset($flt_po_no) && $flt_po_no != "") {
	$sql_cl 	.= " AND t1.po_no LIKE '%" . trim($flt_po_no) . "%' ";
}
if (isset($flt_vender_id) && $flt_vender_id != "") {
	$sql_cl 	.= " AND t1.vender_id = '" . trim($flt_vender_id) . "' ";
}
if (isset($flt_vender_invoice_no) && $flt_vender_invoice_no != "") {
	$sql_cl 	.= " AND t1.vender_invoice_no LIKE '%" . trim($flt_vender_invoice_no) . "%' ";
}
if (isset($flt_logistic_status) && $flt_logistic_status != "") {
	$sql_cl 	.= " AND t1.logistics_status = '" . trim($flt_logistic_status) . "' ";
}
$sql_cl	.= " ORDER BY DATE_FORMAT(add_date, '%Y%m%d%H%i%s') DESC";
// echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);
$page_heading 	= "Logistics ";
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
											<h6 class="media-heading"><?php echo $page_heading; ?></h6>
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
												$field_name     = "flt_po_no";
												$field_label	= "PO#";
												$sql1			= "SELECT DISTINCT po_no FROM purchase_orders WHERE 1=1 ";
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
																<option value="<?php echo $data2['po_no']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['po_no']) { ?> selected="selected" <?php } ?>><?php echo $data2['po_no']; ?></option>
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
												$field_name     = "flt_vender_id";
												$field_label	= "Vendor";
												$sql1			= " SELECT DISTINCT b.id, b.vender_name, b.vender_no, c.type_name
																	FROM purchase_orders a
																	INNER JOIN venders b ON b.id = a.vender_id
																	INNER JOIN vender_types c ON c.id = b.vender_type
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
																<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['vender_name']; ?> (<?php echo $data2['type_name']; ?>) </option>
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
												$field_name = "flt_vender_invoice_no";
												$field_label = "Vendor Invoice#";
												$sql1			= "SELECT DISTINCT vender_invoice_no FROM purchase_orders WHERE 1=1 ";
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
																<option value="<?php echo $data2['vender_invoice_no']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['vender_invoice_no']) { ?> selected="selected" <?php } ?>><?php echo $data2['vender_invoice_no']; ?></option>
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
												$field_name     = "flt_logistic_status";
												$field_label	= "Status";
												$sql1			= "SELECT *  FROM inventory_status WHERE 1=1 AND id IN(1, 4, 10, 11, 12) ORDER BY status_name  ";
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
										<div class="text_align_right">
											<?php 
											$table_columns	= array('SNo', 'PO Type','PO No','PO Date','Vendor Name','Vendor Invoice No','Tracking No');
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
												</thead>
												<tbody>
													<?php
													$i = 0;
													if ($count_cl > 0) {
														$row_cl = $db->fetch($result_cl);
														foreach ($row_cl as $data) {
															$id 				= $data['po_id'];
															$logistic_id		= $data['id'];
															$tracking_no 		= $data['tracking_no'];
															$logistic_for 		= $data['logistic_for'];
															$logistic_module_id = 0;
															$detail_id2		 	= "";
															if ($logistic_for == 'Devices') {
																$logistic_module_id = 10;
																$detail_id2 = "&detail_id=" . $tracking_no;
															}
															if ($logistic_for == 'Packages') {
																$logistic_module_id = 34;
																$detail_id2 = "&logistic_id=" . $logistic_id;
															}
															$active_tab = "tab3"; ?>
															<tr>
																<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
																<td class="col-<?= set_table_headings($table_columns[1]);?>"> <?php echo $data['logistic_for']; ?></td>
																<td class="col-<?= set_table_headings($table_columns[2]);?>"><?php echo $data['po_no']; ?></td>
																<td class="col-<?= set_table_headings($table_columns[3]);?>"> <?php echo dateformat2($data['po_date']); ?></td>
																<td class="col-<?= set_table_headings($table_columns[4]);?>"><?php echo $data['vender_name']; ?></td>
																<td class="col-<?= set_table_headings($table_columns[5]);?>"><?php echo $data['vender_invoice_no']; ?></td>
																<td class="col-<?= set_table_headings($table_columns[6]);?>">
																	<?php
																	if ($logistic_module_id > 0) { ?>
																		<a class="" href="?string=<?php echo encrypt("module_id=" . $logistic_module_id . "&page=profile&cmd=edit&cmd3=add&active_tab=" . $active_tab . "&id=" . $id . $detail_id2) ?>">
																			<?= $data['tracking_no']; ?>
																			<span class="chip green lighten-5">
																				<span class="green-text">
																					<?php echo $data['logistic_status_name']; ?>
																				</span>
																			</span>
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