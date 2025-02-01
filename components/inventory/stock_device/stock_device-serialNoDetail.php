<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

if (isset($id)) {
	$sql_ee				= "	SELECT a.*, b.category_name
							FROM products a
							LEFT JOIN product_categories b ON b.id = a.product_category
							WHERE 1=1 
							AND a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee			= $db->query($conn, $sql_ee);
	$row_ee				= $db->fetch($result_ee);
	$product_uniqueid	= $row_ee[0]['product_uniqueid'];
	$product_desc		= $row_ee[0]['product_desc'];
	$category_name		=  $row_ee[0]['category_name'];
	$detail_desc		= $row_ee[0]['detail_desc'];
}
$page_heading 	= "Serial# Detail";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>

		<div class="col s12 m12 l12">
			<div class="section section-data-tables">
				<div class="card custom_margin_card_table_top custom_margin_card_table_bottom">
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
									<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">
										List
									</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card custom_margin_card_table_top custom_margin_card_table_bottom">
				<div class="card-content custom_padding_card_content_table_top">
					<h4 class="card-title">Product Info</h4>
					<div class="row">
						<div class="col s12">
							<div class="card-content invoice-print-area">
								<!-- header section -->
								<div class="row invoice-date-number">
									<div class="col xl3 s12">
										<span class="invoice-number mr-1"><b>ProductID: </b></span>
										<span>
											<?php if (isset($product_uniqueid)) {
												echo $product_uniqueid;
											} ?>
										</span>
									</div>
									<div class="col xl6 s12">
										<div class="invoice-date display-flex align-items-center flex-wrap">
											<div class="mr-3">
												<small><b><?php //echo "Last Purchase Date:" 
															?></b></small>
												<span></span>
											</div>
											<div>
												<span><?= $row_ee[0]['update_date'] != "" && $row_ee[0]['update_date'] != NULL ?  "<b>Last Update Date:</b>" . $row_ee[0]['update_date'] : ""; ?></span>
											</div>
										</div>
									</div>
								</div>
								<!-- logo and title -->
								<div class="row invoice-logo-title">
									<div class="col m6 s12 display-flex invoice-logo mt-1 push-m6">
									</div>
									<div class="col m6 s12 pull-m6">
										<h5 class="indigo-text">
											<?php if (isset($product_desc)) {
												echo $product_desc;
											} ?>
											<?php if (isset($category_name)) {
												echo "(" . $category_name . ")";
											} ?>
										</h5>
										<span>
											<?php if (isset($detail_desc)) {
												echo $detail_desc;
											} ?>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card custom_margin_card_table_top custom_margin_card_table_bottom">
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
					<h4 class="card-title">Detail</h4>
					<?php
					$sql_cl		= "	SELECT a2.*, b.category_name, c.status_name, a.product_desc, a.product_uniqueid, d.sub_location_name  
									FROM products a
									INNER JOIN product_stock a2 ON a2.product_id = a.id
									LEFT JOIN product_categories b ON b.id = a.product_category
									LEFT JOIN inventory_status c ON c.id = a2.p_inventory_status
									LEFT JOIN warehouse_sub_locations d ON d.id = a2.sub_location
									WHERE 1=1 
									AND a.enabled = 1
									AND a2.p_total_stock > 0
									AND a2.is_final_pricing = 1  
									AND a2.id = '" . $detail_id . "'  ";
					$sql_cl		.= " ORDER BY a.product_uniqueid, b.category_name, c.status_name DESC, a2.stock_grade  ";
					// echo $sql_cl;
					$result_cl	= $db->query($conn, $sql_cl);
					$count_cl	= $db->counter($result_cl);
					?>
					<div class="row">
						<div class="col s12">
							<table class="responsive-table">
								<thead>
									<tr>
										<?php
										$headings = '<th class="sno_width_60">S.No</th>
													<th>Category</th>
													<th>Product ID</th>
													<th>Description</th>
													<th>Serial No</th>
													<th>Battery</th>
													<th>LCD</th>
													<th>Body</th>
													<th>Digitilizer</th>
													<th>Condition</th>
													<th>Storage</th>
													<th>Cost</th>
													<th>Defective Note 1</th>
													<th>Defective Note 2</th>
													<th>Status</th> ';
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
											$id2 		= $data['id'];
											$product_id2 = $data['product_id'];  ?>
											<tr>
												<td style="text-align: center;"><?php echo $i + 1; ?></td>
												<td><?php echo $data['category_name']; ?></td>
												<td><?php echo $data['product_uniqueid']; ?></td>
												<td><?php echo ucwords(strtolower($data['product_desc'])); ?></td>
												<td><?php echo $data['serial_no']; ?></td>
												<td><?php echo $data['battery_percentage']; ?></td>
												<td><?php echo $data['lcd_grade']; ?></td>
												<td><?php echo $data['body_grade']; ?></td>
												<td><?php echo $data['digitizer_grade']; ?></td>
												<td><?php echo $data['stock_grade']; ?></td>
												<td><?php echo $data['storage_size']; ?></td>
												<td><?php echo $data['price']; ?></td>
												<td><?php echo $data['defects_or_notes']; ?></td>
												<td></td>
												<td>
													<?php
													$status_name = $data['status_name'];
													if ($status_name == 'Defective') { ?>
														<span class="chip red lighten-5">
															<span class="red-text">
																<?php echo $status_name; ?></span>
														</span>
													<?php } else if ($status_name == 'Tested/Graded') { ?>
														<span class="chip green lighten-5">
															<span class="green-text">
																<?php echo $status_name; ?></span>
														</span>
													<?php
													} else { ?>
														<span class="chip blue lighten-5">
															<span class="blue-text">
																<?php echo $status_name; ?></span>
														</span>
													<?php
													} ?>
												</td>
											</tr>
									<?php $i++;
										}
									} ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- START RIGHT SIDEBAR NAV -->
		<?php include('sub_files/right_sidebar.php'); ?>
		<div class="content-overlay"></div>
	</div>
</div>