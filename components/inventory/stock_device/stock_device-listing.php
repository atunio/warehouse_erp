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
$sql_cl		= "	SELECT * FROM (
					SELECT c.id, b.product_id, c.product_uniqueid, c.product_desc, '27' AS p_inventory_status, c.product_category, '' AS sub_location,  
						d.category_name, SUM(b.order_qty) AS total_qty, 'Untested/Not Graded' AS status_name, '0' AS is_final_pricing, 'Non Stock' AS r_type
					FROM `purchase_orders` a 
					INNER JOIN purchase_order_detail b ON b.`po_id` = a.id
					INNER JOIN products c ON c.id = b.`product_id`
					LEFT JOIN product_categories d ON  d.id = c.`product_category`
					WHERE order_status IN(1, 3, 4) AND a.`enabled` = 1
					GROUP BY b.product_id 

					UNION ALL

					SELECT a.id, a2.product_id, a.product_uniqueid, a.product_desc, a2.p_inventory_status, a.product_category, 
						GROUP_CONCAT(DISTINCT CONCAT('', a2.sub_location)) AS sub_location,
						b.category_name, '' AS total_qty, c.status_name, a2.is_final_pricing, 'Stock' AS r_type
					FROM products a
					LEFT JOIN product_stock a2 ON a2.product_id = a.id AND a2.enabled = 1 
					LEFT JOIN product_categories b ON b.id = a.product_category
					LEFT JOIN inventory_status c ON c.id = a.inventory_status
					WHERE 1=1 
					AND a.enabled 	= 1
					GROUP BY a2.product_id 
				) AS t1
				WHERE 1=1 ";

if (isset($flt_product_id) && $flt_product_id != "") {
	$sql_cl 	.= " AND product_uniqueid LIKE '%" . trim($flt_product_id) . "%' ";
}
if (isset($flt_product_desc) && $flt_product_desc != "") {
	$sql_cl 	.= " AND product_desc LIKE '%" . trim($flt_product_desc) . "%' ";
}
if (isset($flt_product_category) && $flt_product_category != "") {
	$sql_cl 	.= " AND product_category = '" . trim($flt_product_category) . "%' ";
}
if (isset($flt_stock_status) && $flt_stock_status > 0) {
	$sql_cl		.= " AND p_inventory_status = '" . $flt_stock_status . "' AND is_final_pricing = 1 ";
}
if (isset($flt_bin_id) && $flt_bin_id > 0) {
	$sql_cl		.= " AND FIND_IN_SET('" . $flt_bin_id . "', sub_location) AND is_final_pricing = 1 ";
}
$sql_cl		   .= " ORDER BY id DESC  ";
// echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);
$page_heading 	= "Stock Summary";
?>
<style>
	.detail_tr {
		display: none;
	}

	.minus_icon {
		display: none;
	}
</style>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
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
								<br>
								<form method="post" autocomplete="off" enctype="multipart/form-data">
									<input type="hidden" name="is_Submit" value="Y" />
									<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
									<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																						echo encrypt($_SESSION['csrf_session']);
																					} ?>">
									<div class="row">
										<div class="input-field col m3 s12 custom_margin_bottom_col">
											<?php
											$field_name     = "flt_product_id";
											$field_label	= "ProductID";
											$sql1			= "SELECT DISTINCT product_uniqueid FROM products WHERE 1=1 ";
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
															<option value="<?php echo $data2['product_uniqueid']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['product_uniqueid']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_uniqueid']; ?></option>
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
											$field_name 	= "flt_product_desc";
											$field_label 	= "Product Description";
											$sql1			= "SELECT DISTINCT product_desc FROM products WHERE 1=1 ";
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
															<option value="<?php echo $data2['product_desc']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['product_desc']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_desc']; ?></option>
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
										<div class="input-field col m3 s12">
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
									</div>
									<div class="row">
										<div class="input-field col m3 s12">
											<?php
											$field_name		= "flt_stock_status";
											$field_label	= "Status";
											$sql1			= " SELECT * FROM inventory_status WHERE enabled = 1 AND id IN(" . $status_for_search . ")";
											$result1		= $db->query($conn, $sql1);
											$count1			= $db->counter($result1);
											?>
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">ALL</option>
													<?php
													if ($count1 > 0) {
														$row1    = $db->fetch($result1);
														foreach ($row1 as $data2) { ?>
															<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['status_name']; ?> </option>
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
										<div class="input-field col m3 s12">
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<?php
												$field_name     = "flt_bin_id";
												$field_label    = "Bin/Location";

												$sql1           = " SELECT b.id,b.sub_location_name, b.sub_location_type
																	FROM product_stock a 
																	INNER JOIN  products a2 ON a2.id = a.product_id
																	INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location 
																	WHERE a.p_total_stock > 0
																	
																	GROUP BY b.id ";
												$result1        = $db->query($conn, $sql1);
												$count1         = $db->counter($result1);
												?>
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">ALL</option>
													<?php
													if ($count1 > 0) {
														$row1    = $db->fetch($result1);
														foreach ($row1 as $data2) { ?>
															<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php
																																																				echo $data2['sub_location_name'];
																																																				if ($data2['sub_location_type'] != "") {
																																																					echo "(" . ucwords(strtolower($data2['sub_location_type'])) . ")";
																																																				} ?></option>
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

										<div class="input-field col m4 s12">
											<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button>
											&nbsp;&nbsp;
											<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">Reset</a>
											&nbsp;&nbsp;
											<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=detailStock") ?>">Detail Stocks</a>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="col s4">&nbsp;</div>
									<div class="col s2">
										<?php
										if(isset($flt_bin_id) && $flt_bin_id > 0){
										?>
											<a href="javascript:void(0)" class="plus_icon expand_all"><i class="material-icons dp48">arrow_drop_down</i>Expand All</a>
										<?php
										}else{
											?>
											<a href="javascript:void(0)" class="plus_icon expand_all"><i class="material-icons dp48">arrow_drop_down</i>Expand All</a>
											<a href="javascript:void(0)" class="minus_icon collapse_all"><i class="material-icons dp48">arrow_drop_up</i>Collapse All</a>
										<?php
										}
										?>
										
										
									</div>

								</div>

								<div class="row">
									<div class="col s12">
										<table id="data-table-simple" class="display dataTable dtr-inline simpledatatable_pagelength100">
											<thead>
												<tr>
													<?php
													$headings = '<th class="sno_width_60">P#</th>
																<th class="sno_width_30"></th>
																<th>Product ID / Description</th>
																<th>Status</th>
																<th>Condition</th>
																<th>Serial No</th>
																<th>Model No</th>
																<th>Battery</th>
																<th>RAM</th>
																<th>Storage</th>
																<th>Location</th>
																<th>Average Stock</th>
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
														$id = $data['id'];
														$total_stock_p = 0;
														$avg_price = 0;
														$sql_cl1	= "	SELECT  sum(a2.p_total_stock) as p_total_stock, sum(a2.price)/sum(a2.p_total_stock)  as avg_price,
																				GROUP_CONCAT(DISTINCT CONCAT(' ', a2.stock_grade)) AS stock_grades,
																				GROUP_CONCAT(DISTINCT CONCAT(' ', c.status_name)) AS status_names,
																				GROUP_CONCAT(DISTINCT CONCAT(' ', b1.sub_location_name)) AS sub_location_names
																		FROM products a 
																		INNER JOIN product_stock a2 ON a2.product_id = a.id
																		LEFT JOIN inventory_status c ON c.id = a2.p_inventory_status
																		LEFT JOIN warehouse_sub_locations b1 ON b1.id = a2.sub_location
																		WHERE 1=1 
																		AND a.enabled = 1 
																		AND a2.enabled = 1 
																		AND a2.is_final_pricing = 1
																		AND a2.product_id = '" . $id . "' ";
														// echo "<br><br>" . $sql_cl;
														$result_cl1	= $db->query($conn, $sql_cl1);
														$count_cl1	= $db->counter($result_cl1);
														if ($count_cl1 > 0) {
															$row_cl1 			= $db->fetch($result_cl1);
															$total_stock_p 		= $row_cl1[0]['p_total_stock'];
															$avg_price  		= $row_cl1[0]['avg_price'];
															$stock_grades  		= $row_cl1[0]['stock_grades'];
															$status_names  		= $row_cl1[0]['status_names'];
															$sub_location_names = $row_cl1[0]['sub_location_names'];
														}
														if ($data['r_type'] == 'Non Stock') {
															//for($c = 0;$c<1000; $c++){?>
															<tr>
																<td style="text-align: center;">
																	<?php echo $i + 1; ?>
																</td>
																<td style="text-align: center;"></td>
																<td>
																	<?php
																	echo  $data['product_uniqueid'];
																	?></br>
																	<?php
																	echo ucwords(strtolower(substr($data['product_desc'], 0, 50) . ""));
																	if ($data['category_name'] != "") { ?>
																		(<?php echo $data['category_name']; ?>)
																	<?php } ?>
																</td>
																<td><?php echo $data['status_name']; ?></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td>In transit</td>
																<td><span style="padding-left: 20px;"></td>
																<td><span style="padding-left: 20px;"><?php echo $data['total_qty']; ?></span></td>
															</tr>
														<?php //}
													} else { 
															$sql_cl2	= "	SELECT  a2.id, a2.product_id, a2.stock_product_uniqueid, a2.stock_grade, sum(a2.p_total_stock) as p_total_stock, sum(a2.price)/sum(a2.p_total_stock)  as avg_price,
																					b.category_name, c.status_name, a.product_desc, a.product_uniqueid, a2.p_inventory_status,
																					GROUP_CONCAT(DISTINCT CONCAT(' ', b1.sub_location_name)) AS sub_location_names
																			FROM products a 
																			INNER JOIN product_stock a2 ON a2.product_id = a.id
																			LEFT JOIN product_categories b ON b.id = a.product_category
																			LEFT JOIN inventory_status c ON c.id = a2.p_inventory_status
																			LEFT JOIN warehouse_sub_locations b1 ON b1.id = a2.sub_location
																			WHERE 1=1 
																			AND a2.p_total_stock > 0 
																			AND a2.is_final_pricing = 1 
																			AND a.enabled = 1 ";
															$sql_cl2	.= "AND a2.product_id = '" . $id . "' ";
															$sql_cl2	.= " GROUP BY c.status_name, a2.stock_grade  
																			ORDER BY c.status_name DESC, a2.stock_grade "; //a2.p_inventory_status
															// echo "<br><br>" . $sql_cl2;
															$result_cl2	= $db->query($conn, $sql_cl2);
															$count_cl2	= $db->counter($result_cl2);
															?>
															<tr>
																<td style="text-align: center;">
																	<?php echo $i + 1; ?>
																</td>
																<td style="text-align: center;">
																	<?php
																	if ($count_cl2 > 0) { ?>
																		<a href="javascript:void(0)" class="plus_icon <?= "plus_" . $id; ?>" id="<?= $id; ?>"><i class="material-icons dp48">add_circle_outline</i></a>
																		<a href="javascript:void(0)" class="minus_icon <?= "minus_" . $id; ?>" id="<?= $id; ?>"><i class="material-icons dp48">remove_circle_outline</i></a>
																	<?php } ?>
																</td>
																<td>
																	<?php
																	if (access("edit_perm") == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=history&id=" . $id) ?>" title="Detail View">
																			<?php echo $data['product_uniqueid']; ?>
																		</a> &nbsp;&nbsp;
																	<?php } else {
																		echo  $data['product_uniqueid'];
																	} ?></br>
																	<?php
																	echo ucwords(strtolower(substr($data['product_desc'], 0, 50) . ""));
																	if ($data['category_name'] != "") { ?>
																		(<?php echo $data['category_name']; ?>)
																	<?php } ?>
																</td>
																<td><?php echo $status_names; ?></td>
																<td><?php echo $stock_grades; ?></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td><?php echo $sub_location_names; ?></td>
																<td><span style="padding-left: 20px;"><?php if (round($avg_price, 2) > 0) echo round($avg_price, 2); ?></span></td>
																<td><span style="padding-left: 20px;"><?php echo $total_stock_p; ?></span></td>
															</tr>
															<?php
															if ($count_cl2 > 0) {
																$row_cl2 = $db->fetch($result_cl2);
																foreach ($row_cl2 as $data2) {
																	$id2 					= $data2['id'];
																	$product_id 			= $data2['product_id'];
																	$product_uniqueid 		= $data2['product_uniqueid'];
																	$filter_1 				= $data2['p_inventory_status'];
																	$filter_2 				= $data2['stock_grade']; ?>
																	<tr class="detail_tr <?= $id; ?>">
																		<td style="text-align: center;"><?php echo $i + 1; ?></td>
																		<td style="text-align: center;">
																			<a href="javascript:void(0)" class="plus_icon_sub <?= "sub_plus_" . $id2; ?>" id="<?= $id2; ?>"><i class="material-icons dp48">add_circle_outline</i></a>
																			<a href="javascript:void(0)" class="minus_icon_sub <?= "sub_minus_" . $id2; ?>" id="<?= $id2; ?>"><i class="material-icons dp48">remove_circle_outline</i></a>
																		</td>
																		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																			
																			<!-- <i class="material-icons prefix">subdirectory_arrow_right</i> -->
																		</td>
																		<td><?php echo $data2['status_name']; ?></td>
																		<td><?php echo $data2['stock_grade']; ?></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td></td>
																		<td><?php echo $data2['sub_location_names']; ?></td>
																		<td>
																			<?php
																			if (access("edit_perm") == 1) { ?>
																				<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=detailStock&id=" . $product_id . "&detail_id=" . $product_uniqueid . "&filter_1=" . $filter_1 . "&filter_2=" . $filter_2. "&is_Submit=Y") ?>" title="Detail Stock View" style="padding: 20px;">
																					<?php echo round($data2['avg_price'], 2); ?>
																				</a> &nbsp;&nbsp;
																			<?php } else {
																				echo round($data2['avg_price'], 2);
																			} ?>
																		</td>
																		<td>
																			<?php
																			if (access("edit_perm") == 1) { ?>
																				<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=detailStock&id=" . $product_id . "&detail_id=" . $product_uniqueid . "&filter_1=" . $filter_1 . "&filter_2=" . $filter_2. "&is_Submit=Y") ?>" title="Detail Stock View" style="padding: 20px;">
																					<?php echo $data2['p_total_stock']; ?>
																				</a> &nbsp;&nbsp;
																			<?php } else {
																				echo $data2['p_total_stock'];
																			} ?>
																		</td>
																	</tr>
																	
																	<?php
																	$sql_cl3 = "SELECT a2.id, a2.product_id, a2.serial_no, a2.model_no, a2.battery_percentage, a2.ram_size, 
																					   a2.storage_size, a2.price, a2.cosmetic_grade, b.category_name, c.status_name, a.product_desc, 
																					   a.product_uniqueid, d.sub_location_name, a2.p_inventory_status, a2.stock_grade, d6.type_name, a2.is_packed,
																					   a2.p_total_stock 
																				FROM products a 
																				INNER JOIN product_stock a2 ON a2.product_id = a.id 
																				LEFT JOIN product_categories b ON b.id = a.product_category 
																				LEFT JOIN inventory_status c ON c.id = a2.p_inventory_status 
																				LEFT JOIN warehouse_sub_locations d ON d.id = a2.sub_location 
																				LEFT JOIN purchase_order_detail d3 ON d3.product_id = a2.product_id
																				LEFT JOIN purchase_orders d4 ON d4.id = d3.po_id 
																				LEFT JOIN venders d5 ON d5.id = d4.vender_id 
																				LEFT JOIN vender_types d6 ON d6.id = d5.vender_type 
																				WHERE 1=1 
																				AND a2.p_total_stock > 0 
																				AND a2.is_final_pricing = 1 
																				AND a.enabled = 1";
																	$sql_cl3	.= " AND a2.product_id = '" . $product_id . "' ";
																	$sql_cl3	.= " ORDER BY a2.serial_no,d.sub_location_name";
																	
																	$result_cl3	= $db->query($conn, $sql_cl3);
																	$count_cl3	= $db->counter($result_cl3);
																	if ($count_cl3 > 0) {
																		$row_cl3 = $db->fetch($result_cl3);
																		foreach ($row_cl3 as $data3) {
																			$id3 					= $data3['id'];?>
																			<tr class="detail_tr <?= $id2; ?>">
																				<td style="text-align: center;"><?php echo $i + 1; ?></td>
																				<td style="text-align: center;"></td>
																				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																					
																					<!-- <i class="material-icons prefix">subdirectory_arrow_right</i> -->
																				</td>
																				<td><?php echo $data3['status_name']; ?></td>
																				<td><?php echo $data3['stock_grade']; ?></td>
																				<td><?php echo $data3['serial_no']; ?></td>
																				<td><?php echo $data3['model_no']; ?></td>
																				<td><?php echo $data3['battery_percentage']; ?></td>
																				<td><?php echo $data3['ram_size']; ?></td>
																				<td><?php echo $data3['storage_size']; ?></td>
																				<td><?php echo $data3['sub_location_name']; ?></td>
																				<td><?php echo round($data3['price'], 2);?></td>
																				<td><?php echo $data3['p_total_stock']; ?></td>
																			</tr>
																		<?php
																		}
																	}
																}
															}
														}
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
			<div class="content-overlay"></div>
		</div>
	</div>
</div>