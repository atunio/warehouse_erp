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
$sql_cl			= " SELECT b.*, d.po_no, c.po_id, d.po_date, e.vender_name, d.vender_invoice_no, f.status_name, g.product_desc, g.product_uniqueid, h.category_name, b.overall_grade, b.price
					FROM purchase_order_detail_receive_rma a
					INNER JOIN purchase_order_detail_receive b ON b.id = a.receive_id
					INNER JOIN purchase_order_detail c ON c.id = b.po_detail_id
					INNER JOIN purchase_orders d ON d.id = c.po_id
					LEFT JOIN venders e ON e.id = d.vender_id
					INNER JOIN inventory_status f ON f.id = a.status_id
					LEFT JOIN products g ON g.id = c.product_id
					LEFT JOIN product_categories h ON h.id = g.product_category
					WHERE 1=1
					AND a.status_id != 19
					AND a.is_repaired = 0
					ORDER BY a.id DESC  ";
// echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);
$page_heading 	= "List PO For RMA ";
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
									<?php } ?><br>
									<div class="row">
										<div class="text_align_right">
											<?php 
											$table_columns	= array('SNo', 'PO No / PO Date', 'Vendor / InvoiceNo',  'Product ID',  'SeriaLNo', 'RMA Grade', 'RMA Status');
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
											<table id="page-length-option" class="display pagelength50_3">
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
															$id	= $data['po_id'];   ?>
															<tr>
																<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
																<td class="col-<?= set_table_headings($table_columns[1]);?>">
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab1") ?>">
																		<?php echo $data['po_no']; ?>
																	</a>
																	</br>
																	<?php echo dateformat2($data['po_date']); ?>
																	<br>
																</td>
																<td class="col-<?= set_table_headings($table_columns[2]);?>">
																	<?php echo $data['vender_name']; ?></br>
																	<?php echo $data['vender_invoice_no']; ?>
																</td>
																<td class="col-<?= set_table_headings($table_columns[3]);?>">
																	<?php echo $data['product_uniqueid']; ?></br>
																</td>
																<td class="col-<?= set_table_headings($table_columns[4]);?>">
																	<?php echo $data['serial_no_barcode']; ?></br>
																</td>
																<td class="col-<?= set_table_headings($table_columns[5]);?>">
																	Grade: <?php echo $data['overall_grade']; ?>
																</td>
																<td class="col-<?= set_table_headings($table_columns[6]);?>">
																	<span class="chip green lighten-5">
																		<span class="green-text">
																			<?php echo $data['status_name'];  ?>
																		</span>
																	</span>
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