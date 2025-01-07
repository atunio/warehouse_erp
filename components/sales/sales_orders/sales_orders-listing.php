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
								aa.id AS sale_order_id_master, aa.customer_po_no, aa.order_date, aa.enabled AS order_enabled, aa.add_by_user_id AS add_by_user_id_order,
								c.customer_name, f.status_name AS po_status_name
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
	$sql_cl 	.= " AND t1.so_no LIKE '%" . trim($flt_so_no) . "%' ";
}
if (isset($flt_customer_name) && $flt_customer_name != "") {
	$sql_cl 	.= " AND t1.customer_name LIKE '%" . trim($flt_customer_name) . "%' ";
}
if (isset($flt_customer_invoice_no) && $flt_customer_invoice_no != "") {
	$sql_cl 	.= " AND t1.customer_po_no LIKE '%" . trim($flt_customer_invoice_no) . "%' ";
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
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=add&active_tab=tab1") ?>">Add New</a>
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
											$field_name = "flt_so_no";
											$field_label = "SO No";
											?>
											<div class="input-field col m2 s12">
												<i class="material-icons prefix">description</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>">
												<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
											</div>
											<?php
											$field_name = "flt_customer_name";
											$field_label = "Customer Name";
											?>
											<div class="input-field col m2 s12">
												<i class="material-icons prefix">description</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>">
												<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
											</div>
											<?php
											$field_name = "flt_customer_invoice_no";
											$field_label = "Customer Invoice#";
											?>
											<div class="input-field col m2 s12">
												<i class="material-icons prefix">description</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>">
												<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
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
											<table id="page-length-option" class="display pagelength50">
												<thead>
													<tr>
														<?php
														$headings = '<th class="sno_width_60">S.No</th>
																	<th>SO No</br>Order Date</th>
																	<th>Expected Ship Date</th>
 																	<th>Customer / Invoice#</th>
																	<th>Category Wise Qty</th>
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
															$id	= $data['sale_order_id_master'];
															?>
															<tr>
																<td style="text-align: center;"><?php echo $i + 1; ?></td>
																<td>
																	<?php
																	if ($data['order_enabled'] == 1 && access("edit_perm") == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab1") ?>">
																			<?php echo $data['so_no']; ?>
																		</a>
																	<?php } else {
																		echo $data['so_no'];
																	} ?>
																	</br>
																	<?php echo dateformat2($data['order_date']); ?>
																	<br>
																	<span class="chip green lighten-5">
																		<span class="green-text">
																			<?php
																			echo $data['po_status_name'];
																			///*
																			/////////////////////// Total //////////////////////////////////
																			$total_items_ordered = 0;
																			$sql2       = " SELECT sum(a.order_qty) as order_qty
																							FROM sales_order_detail a
																							WHERE a.sales_order_id = '" . $id . "'
																							AND a.enabled = 1 ";
																			$result_r2    = $db->query($conn, $sql2);
																			$count2     = $db->counter($result_r2);
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
																			
																			$sql3               = "SELECT a.id
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
																</td>
																<td>
																	<?php echo dateformat2($data['estimated_ship_date']); ?>
																</td>
																<td>
																	<b>Customer: </b><?php echo $data['customer_name']; ?></br>
																	<?php if ($data['customer_po_no'] != '') echo "<b>Invoice#: </b>" . $data['customer_po_no']; ?>

																</td>
																<td>
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
																<td class="text-align-center">
																	<?php
																	if ($data['order_status'] == 1 || $data['order_status'] == '') {
																		if ($data['order_enabled'] == 1 && access("edit_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab1") ?>">
																				<i class="material-icons dp48">edit</i>
																			</a> &nbsp;&nbsp;
																		<?php }
																		if ($data['order_enabled'] == 0 && access("delete_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=enabled&id=" . $id) ?>">
																				<i class="material-icons dp48">add</i>
																			</a> &nbsp;&nbsp;
																		<?php } else if ($data['order_enabled'] == 1 && access("delete_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=disabled&id=" . $id) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																				<i class="material-icons dp48">delete</i>
																			</a> &nbsp;&nbsp;
																	<?php }
																	} 
																	if ($data['order_enabled'] == 1 && access("print_perm") == 1) { ?>
																		<a href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/print_invoice.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
																			<i class="material-icons dp48">print</i>
																		</a>
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