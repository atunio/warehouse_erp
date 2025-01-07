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
	$total_stock		= $row_ee[0]['total_stock'];
}
$page_heading 	= "Stock Hisotry";
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
						<?php
						if (access("add_perm") == 1) { ?>
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
								List
							</a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12">
			<div class="container">
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
												<div class="col xl3 s12">
													<span class="invoice-number mr-1"><b>Stock: </b></span>
													<span>
														<?php if (isset($total_stock)) {
															echo $total_stock;
														} ?>
													</span>
												</div>
												<div class="col xl6 s12">
													<div class="invoice-date display-flex align-items-center flex-wrap">
														<div class="mr-3">
															<small><b>Last Purchase Date:</b></small>
															<span>08/08/2024</span>
														</div>
														<div>
															<small><b>Last Update Date:</b></small>
															<span>08/08/2024</span>
														</div>
													</div>
												</div>
											</div>
											<!-- logo and title -->
											<div class="row mt-3 invoice-logo-title">
												<div class="col m6 s12 display-flex invoice-logo mt-1 push-m6">
												</div>
												<div class="col m6 s12 pull-m6">
													<h4 class="indigo-text">
														<?php if (isset($product_desc)) {
															echo $product_desc;
														} ?>

														<?php if (isset($category_name)) {
															echo "(" . $category_name . ")";
														} ?>
													</h4>
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
					<div class="col s12">
						<div class="card">
							<div class="card-content">
								<h4 class="card-title">Stock Summary</h4>
								<?php

								$sql_cl		= "	SELECT  a2.id, a2.product_id, a2.stock_product_uniqueid, a2.p_total_stock, a2.stock_grade, sum(a2.p_total_stock) as p_total_stock, 
														b.category_name, c.status_name, a.product_desc, a.product_uniqueid 
												FROM products a
												INNER JOIN product_stock a2 ON a2.product_id = a.id
												LEFT JOIN product_categories b ON b.id = a.product_category
												LEFT JOIN inventory_status c ON c.id = a2.p_inventory_status
												WHERE 1=1 
												AND a.enabled = 1
												AND a2.is_final_pricing = 1 ";
								if (isset($id) && $id > 0) {
									$sql_cl		.= " AND a2.product_id = '" . $id . "' ";
								}
								$sql_cl		.= " GROUP BY a.product_uniqueid, b.category_name, c.status_name, a2.stock_grade
												 ORDER BY a.product_uniqueid, b.category_name, c.status_name DESC, a2.stock_grade ";
								// echo $sql_cl;
								$result_cl	= $db->query($conn, $sql_cl);
								$count_cl	= $db->counter($result_cl);
								?>
								<div class="section section-data-tables">
									<div class="row">
										<div class="col s12">
											<table id="" class="display">
												<thead>
													<tr>
														<?php
														$headings = '<th class="sno_width_60">S.No</th>
																	<th>Product </th> 
																	<th>Product ID</th> 
																	<th>Status</th>
																	<th>Condition</th>
																	<th>Stock</th>';
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
																<td>
																	<?php
																	echo ucwords(strtolower($data['product_desc']));
																	if ($data['category_name'] != "") { ?>
																		(<?php echo $data['category_name']; ?>)
																	<?php } ?>
																</td>
																<td><?php echo $data['product_uniqueid']; ?></td>
																<td><?php echo $data['status_name']; ?></td>
																<td><?php echo $data['stock_grade']; ?></td>
																<td> <?php echo $data['p_total_stock']; ?> </td>
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
					</div>
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
								<h4 class="card-title">Detail Stock</h4>
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
												AND a2.is_final_pricing = 1  ";
								if (isset($id) && $id > 0) {
									$sql_cl		.= "AND a2.product_id = '" . $id . "' ";
								}
								$sql_cl		.= " ORDER BY a.product_uniqueid, b.category_name, c.status_name DESC, a2.stock_grade  ";
								// echo $sql_cl;
								$result_cl	= $db->query($conn, $sql_cl);
								$count_cl	= $db->counter($result_cl);
								?>
								<div class="section section-data-tables">
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="pagelength50">
												<thead>
													<tr>
														<?php
														$headings = '<th class="sno_width_60">S.No</th>
																	<th>Product ID <br> Product Detail</th>
																	<th>Status</th>
																	<th>Condition</th>
																	<th>Serial No</th>
																	<th>Model No</th>
																	<th>Specification</th>
																	<th>Defects or Notes</th>
 																	<th>Cosmetic Grade</th>';
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
																<td>
																	<?php echo $data['product_uniqueid']; ?>
																	<br>
																	<?php
																	echo ucwords(strtolower($data['product_desc']));
																	if ($data['category_name'] != "") { ?>
																		(<?php echo $data['category_name']; ?>)
																	<?php } ?>
																	<br>
																	Location: <?php echo $data['sub_location_name']; ?>
																</td>
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
																<td><?php echo $data['stock_grade']; ?></td>
																<td><?php echo $data['serial_no']; ?></td>
																<td><?php echo $data['model_no']; ?></td>
																<td>
																	<?php if ($data['battery_percentage'] > '0') {
																		echo "Battery: " . $data['battery_percentage'] . "%<br>";
																	} ?>
																	<?php if ($data['storage_size'] != '') {
																		echo "Storage: " . $data['storage_size'] . "<br>";
																	} ?>
																	<?php if ($data['ram_size'] != '') {
																		echo "RAM: " . $data['ram_size'] . "<br>";
																	} ?>
																	<?php if ($data['processor_size'] != '') {
																		echo "Processor: " . $data['processor_size'];
																	} ?>
																</td>
																<td><?php echo $data['defects_or_notes']; ?></td>
																<td>
																	<?php echo $data['cosmetic_grade']; ?>
																	<?php if ($data['body_grade'] != '') {
																		echo "Body: " . $data['body_grade'] . "<br>";
																	} ?>
																	<?php if ($data['lcd_grade'] != '') {
																		echo "LCD: " . $data['lcd_grade'] . "<br>";
																	} ?>
																	<?php if ($data['digitizer_grade'] != '') {
																		echo "Digitizer: " . $data['digitizer_grade'] . "<br>";
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
					</div>
					<!-- Puchase Listing -->
					<div class="col s12">
						<div class="card">
							<div class="card-content">
								<h4 class="card-title">Purchase History</h4>
								<?php
								$sql_cl		= "	SELECT * FROM (
													SELECT '' AS offer_no, aa.po_no, 
															aa2.*, 
															aa.id AS po_id_master, aa2.id AS po_detail_id,
															c.vender_name, aa.po_date, aa.enabled AS order_enabled, e.status_name
													FROM purchase_orders aa
													LEFT JOIN purchase_order_detail aa2 ON aa.id = aa2.po_id 
 													LEFT JOIN venders c ON c.id = aa.vender_id
													INNER JOIN inventory_status e ON e.id = aa.order_status
													WHERE 1=1 
													AND (aa.offer_id IS NULL || aa.offer_id = '0' )
													
													UNION ALL 
													
													SELECT 	a.offer_no AS offer_no, aa.po_no, 
															aa2.*, 
															aa.id AS po_id_master, aa2.id AS po_detail_id, 
															c.vender_name, aa.po_date, aa.enabled AS order_enabled, e.status_name 
													FROM purchase_orders aa
													INNER JOIN purchase_order_detail aa2 ON aa.id = aa2.po_id 
													INNER JOIN offers a ON a.id = aa.offer_id
													INNER JOIN offer_detail a1 ON a1.offer_id = a.id AND a1.id = aa2.offer_detail_id
 													INNER JOIN venders c ON c.id = a.vender_id
													INNER JOIN inventory_status e ON e.id = aa.order_status
													WHERE 1=1 
													AND aa.offer_id >0
												) AS t1
												WHERE 1=1 ";
								if (isset($id) && $id > 0) {
									$sql_cl		.= "AND t1.product_id = '" . $id . "' ";
								}
								$sql_cl		.= " ORDER BY  t1.po_id_master DESC, t1.po_detail_id DESC ";
								// echo $sql_cl;
								$result_cl	= $db->query($conn, $sql_cl);
								$count_cl	= $db->counter($result_cl);
								if ($count_cl > 0) { ?>
									<div class="section section-data-tables">
										<div class="row">
											<div class="col s12">
												<table id="" class="display">
													<thead>
														<tr>
															<?php
															$headings = '	<th class="sno_width_60">S.No</th>
																			<th>PO No</th>
																			<th>PO Date</th>
 																			<th>Vender</th> 
																			<th>Quantity</th>
																			<th>Price</th>';
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
																$id 		= $data['id'];
																$product_id = $data['product_id'];  ?>
																<tr>
																	<td style="text-align: center;"><?php echo $i + 1; ?></td>
																	<td><?php echo $data['po_no']; ?></td>
																	<td><?php echo dateformat2($data['po_date']); ?></td>
																	<td><?php echo $data['vender_name']; ?></td>
																	<td><?php echo $data['order_qty']; ?></td>
																	<td><?php echo $data['order_price']; ?></td>
																</tr>
														<?php $i++;
															}
														} ?>
												</table>
											</div>
										</div>
									</div>
								<?php } else { ?>
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card red lighten-5">
												<div class="card-content red-text">
													<p>No record found yet</p>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<!-- Sales Listing -->
					<div class="col s12">
						<div class="card">
							<div class="card-content">
								<h4 class="card-title">Sales History</h4>
								<?php
								$sql_cl		= "	SELECT * FROM (
													SELECT '' AS offer_no, aa.po_no, 
															aa2.*, 
															aa.id AS po_id_master, aa2.id AS po_detail_id,
															c.vender_name, aa.po_date, aa.enabled AS order_enabled, e.status_name
													FROM purchase_orders aa
													LEFT JOIN purchase_order_detail aa2 ON aa.id = aa2.po_id 
 													LEFT JOIN venders c ON c.id = aa.vender_id
													INNER JOIN inventory_status e ON e.id = aa.order_status
													WHERE 1=1 
													AND aa.offer_id IS NULL
													
													UNION ALL 
													
													SELECT 	a.offer_no AS offer_no, aa.po_no, 
															aa2.*, 
															aa.id AS po_id_master, aa2.id AS po_detail_id, 
															c.vender_name, aa.po_date, aa.enabled AS order_enabled, e.status_name 
													FROM purchase_orders aa
													INNER JOIN purchase_order_detail aa2 ON aa.id = aa2.po_id 
													INNER JOIN offers a ON a.id = aa.offer_id
													INNER JOIN offer_detail a1 ON a1.offer_id = a.id AND a1.id = aa2.offer_detail_id
 													INNER JOIN venders c ON c.id = a.vender_id
													INNER JOIN inventory_status e ON e.id = aa.order_status
													WHERE 1=1 
													AND aa.offer_id IS NOT NULL
												) AS t1
												WHERE 1=1 ";
								if (isset($id) && $id > 0) {
									$sql_cl		.= "AND t1.product_id = '" . $id . "1111111111111111111111111111111111111' ";
								}
								$sql_cl		.= " ORDER BY  t1.po_id_master DESC, t1.po_detail_id DESC ";
								//echo $sql_cl;
								$result_cl	= $db->query($conn, $sql_cl);
								$count_cl	= $db->counter($result_cl);
								if ($count_cl > 0) { ?>
									<div class="section section-data-tables">
										<div class="row">
											<div class="col s12">
												<table id="" class="display">
													<thead>
														<tr>
															<?php
															$headings = '	<th class="sno_width_60">S.No</th>
																			<th>PO No</th>
																			<th>PO Date</th>
 																			<th>Vender</th> 
																			<th>Quantity</th>
																			<th>Price</th>';
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
																$id 		= $data['id'];
																$product_id = $data['product_id'];  ?>
																<tr>
																	<td style="text-align: center;"><?php echo $i + 1; ?></td>
																	<td><?php echo $data['po_no']; ?></td>
																	<td><?php echo dateformat2($data['po_date']); ?></td>
																	<td><?php echo $data['vender_name']; ?></td>
																	<td><?php echo $data['order_qty']; ?></td>
																	<td><?php echo $data['order_price']; ?></td>
																</tr>
														<?php
																$i++;
															}
														} ?>
												</table>
											</div>
										</div>
									</div>
								<?php } else { ?>
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card red lighten-5">
												<div class="card-content red-text">
													<p>No record found yet</p>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>
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