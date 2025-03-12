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
$sql_cl		= "	SELECT distinct id, product_id, product_uniqueid, product_desc, product_category, category_name 
				FROM (
				SELECT * 
				FROM (
					SELECT * FROM (
						SELECT 
							id, product_id, product_uniqueid, product_desc, product_category, category_name, '' as p_inventory_status, 
							sub_location_id as sub_location, serial_no_barcode as serial_no, overall_grade as stock_grade
						FROM (
 							SELECT  
								c.product_uniqueid, c.id, c1.product_id,
								c.product_desc,
								d.category_name, c.product_category,
								a.po_no,
								e.status_name AS po_status_name,
								SUM(1) AS po_order_qty,
								SUM(b.price) AS total_price, b.sub_location_id, b.serial_no_barcode, b.overall_grade 
							FROM `purchase_orders` a 
							INNER JOIN purchase_order_detail_receive b ON b.`po_id` = a.id
							INNER JOIN purchase_order_detail c1 ON c1.id = b.`po_detail_id`
							INNER JOIN products c ON c.id = c1.`product_id`
							LEFT JOIN product_categories d ON d.id = c.`product_category`
							INNER JOIN inventory_status e ON e.id = a.order_status
							WHERE a.enabled = 1
							AND a.order_status IN (3, 5, 6)
							AND a.is_pricing_done = 0
							AND b.is_rma_processed = 0
							GROUP BY c1.product_id, b.po_id

							UNION ALL

							SELECT   
								c.product_uniqueid, c.id, b.product_id,
								c.product_desc,
								d.category_name, c.product_category,
								a.po_no,
								e.status_name AS po_status_name,
								SUM(1) AS po_order_qty,
								SUM(b.price) AS total_price, b.sub_location_id, b.serial_no_barcode, b.overall_grade 

							FROM `purchase_orders` a 
							INNER JOIN purchase_order_detail_receive b ON b.`po_id` = a.id
							INNER JOIN products c ON c.id = b.`product_id`
							LEFT JOIN product_categories d ON  d.id = c.`product_category`
							INNER JOIN inventory_status e ON e.id = a.order_status
							WHERE a.enabled = 1
							AND order_status IN(3, 5, 6)
							AND a.is_pricing_done = 0
							GROUP BY b.product_id, b.po_id
							
							UNION ALL
							
							SELECT  c.product_uniqueid, c.id, b.product_id,
								c.product_desc,
								d.category_name, c.product_category,
								a.po_no,
								e.status_name AS po_status_name,
								SUM(b.order_qty) AS po_order_qty,
								SUM(b.order_qty*order_price) AS total_price, '' as sub_location_id, '' as serial_no_barcode, '' as overall_grade
							FROM `purchase_orders` a 
							INNER JOIN purchase_order_detail b ON b.`po_id` = a.id
							INNER JOIN products c ON c.id = b.`product_id`
							LEFT JOIN product_categories d ON  d.id = c.`product_category`
							INNER JOIN inventory_status e ON e.id = a.order_status
							WHERE a.enabled = 1 
							AND b.order_qty > 0 
							AND order_status IN(1, 3, 4, 11, 12)
							AND a.is_pricing_done = 0
							GROUP BY b.product_id, b.po_id

						) AS combined_data
						GROUP BY product_uniqueid, product_desc, category_name
					) as t1_2

					UNION ALL

					SELECT a.id, a2.product_id, a.product_uniqueid, a.product_desc, a.product_category, b.category_name, a2.p_inventory_status, a2.sub_location, a2.serial_no, a2.stock_grade
					FROM products a
					LEFT JOIN product_stock a2 ON a2.product_id = a.id AND a2.enabled = 1 
					LEFT JOIN product_categories b ON b.id = a.product_category
					LEFT JOIN inventory_status c ON c.id = a.inventory_status
					WHERE 1=1 
					AND a.enabled 	= 1 
					AND is_final_pricing = 1 
					GROUP BY a.id 
				) AS t1
			WHERE 1=1 ";
if (isset($flt_product_id) && $flt_product_id != "") {
	$sql_cl 	.= " AND product_uniqueid LIKE '" . trim($flt_product_id) . "' ";
}
if (isset($flt_product_desc) && $flt_product_desc != "") {
	$sql_cl 	.= " AND product_desc LIKE '%" . trim($flt_product_desc) . "%' ";
}
if (isset($flt_product_category) && $flt_product_category != "") {
	$sql_cl 	.= " AND product_category = '" . trim($flt_product_category) . "' ";
}
if (isset($flt_stock_status) && $flt_stock_status > 0) {
	$sql_cl		.= " AND FIND_IN_SET('" . $flt_stock_status . "', p_inventory_status) ";
}
if (isset($flt_bin_id) && $flt_bin_id > 0) {
	$sql_cl		.= " AND FIND_IN_SET('" . $flt_bin_id . "', sub_location) ";
}
if (isset($flt_serial_no) && $flt_serial_no != '') {
	$sql_cl		.= " AND FIND_IN_SET('" . $flt_serial_no . "', serial_no) ";
}
if (isset($flt_stock_grade) && $flt_stock_grade != '') {
	$sql_cl		.= " AND FIND_IN_SET('" . $flt_stock_grade . "', stock_grade) ";
}
$sql_cl		   .= " ORDER BY product_uniqueid
				) AS t2";
//  echo "<br><br><br><br><br>" . $sql_cl;
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

	table.bordered td {
		padding-top: 10px !important;
		padding-bottom: 10px !important;
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
											$sql1			= "SELECT DISTINCT product_uniqueid FROM products WHERE 1=1 AND product_uniqueid != '' ";
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
											$sql1			= "SELECT DISTINCT product_desc FROM products WHERE 1=1 AND product_desc != '' ";
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
											$sql1 			= "SELECT id, category_name FROM product_categories WHERE enabled = 1 AND category_type = 'Device' ORDER BY category_name ";
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
										<div class="input-field col m3 s12">
											<?php
											$field_name 	= "flt_serial_no";
											$field_label 	= "Serial No";
											$sql1 			= "SELECT DISTINCT serial_no  FROM product_stock WHERE enabled = 1 AND serial_no IS NOT NULL AND serial_no != '' ";
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
															<option value="<?php echo $data2['serial_no']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['serial_no']) { ?> selected="selected" <?php } ?>><?php echo "" . $data2['serial_no']; ?></option>
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

												$sql1		= " SELECT DISTINCT b.id,b.sub_location_name, b.sub_location_type
																FROM product_stock a 
																INNER JOIN  products a2 ON a2.id = a.product_id
																INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location 
																WHERE a.p_total_stock > 0
																AND a.sub_location > 0
																GROUP BY b.id ";
												$result1	= $db->query($conn, $sql1);
												$count1		= $db->counter($result1);
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

										<div class="input-field col m3 s12">
											<?php
											$field_name		= "flt_stock_grade";
											$field_label	= "Condition";
											?>
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">ALL</option>
													<option value="A" <?php if (isset(${$field_name}) && ${$field_name} == 'A') { ?> selected="selected" <?php } ?>>A</option>
													<option value="B" <?php if (isset(${$field_name}) && ${$field_name} == 'B') { ?> selected="selected" <?php } ?>>B</option>
													<option value="C" <?php if (isset(${$field_name}) && ${$field_name} == 'C') { ?> selected="selected" <?php } ?>>C</option>
													<option value="D" <?php if (isset(${$field_name}) && ${$field_name} == 'D') { ?> selected="selected" <?php } ?>>D</option>
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
											<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button>
											&nbsp;&nbsp;
											<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">Reset</a>
											&nbsp;&nbsp;
											<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=detailStock") ?>">Detail Stocks</a>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="col s2">
										<a href="javascript:void(0)" class="plus_icon expand_all"><i class="material-icons dp48">arrow_drop_down</i>Expand All</a>
										<a href="javascript:void(0)" class="minus_icon collapse_all"><i class="material-icons dp48">arrow_drop_up</i>Collapse All</a>
									</div>
									<div class="col s10">
										<div class="text_align_right">
											<?php
											$table_columns	= array('Detail', 'ProductID', 'Description', 'Category', 'Status', 'Condition', 'Location', 'Stock', 'SerialNo', 'AveragePrice');
											$k 				= 0;
											foreach ($table_columns as $data_c1) { ?>
												<label>
													<input type="checkbox" value="<?= $k ?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1) ?>" checked="checked">
													<span><?= $data_c1 ?></span>
												</label>&nbsp;&nbsp;
											<?php
												$k++;
											} ?>
										</div>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col s12">
										<table id="page-length-option" class="display simpledatatable_pagelength1000_1 custom_font_size_table">
											<thead>
												<tr>
													<?php
													$headings = "";
													foreach ($table_columns as $data_c) {
														if ($data_c == 'Detail') {
															$headings .= '<th class="sno_width_30 col-' . set_table_headings($data_c) . '">' . $data_c . '</th>';
														} else {
															$headings .= '<th class="col-' . set_table_headings($data_c) . '">' . $data_c . '</th> ';
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
														$id 				= $data['id'];
														$product_uniqueid	= $data['product_uniqueid'];
														$category_name		= $data['category_name'];
														$product_desc 		= ucwords(strtolower(substr($data['product_desc'], 0, 50) . ""));
														$total_stock_p 		= 0;
														$avg_price 			= 0;

														$sql_cl1	= "	SELECT *
																		FROM (
																			SELECT  a2.id, a2.product_id, a2.stock_grade, SUM(a2.p_total_stock) AS p_total_stock, SUM(a2.price)/SUM(a2.p_total_stock)  AS avg_price,
																				c.status_name, a2.p_inventory_status, b.category_name, a.product_desc, a.product_uniqueid,
																				GROUP_CONCAT(DISTINCT CONCAT(' ', COALESCE(b1.sub_location_name))) AS sub_location_names,
																				GROUP_CONCAT( (a2.sub_location)) AS sub_location_ids,
																				GROUP_CONCAT( (a2.serial_no)) AS serial_nos,
																				'' as po_details, 
																				'' as po_ids
																			FROM products a 
																			INNER JOIN product_stock a2 ON a2.product_id = a.id
																			LEFT JOIN product_categories b ON b.id = a.product_category
																			LEFT JOIN inventory_status c ON c.id = a2.p_inventory_status
																			LEFT JOIN warehouse_sub_locations b1 ON b1.id = a2.sub_location
																			WHERE 1=1 
																			AND a2.p_total_stock > 0 
																			AND a2.is_final_pricing = 1 
																			AND a.enabled = 1 
																			AND a2.product_id = '" . $id . "'
																			HAVING SUM(a2.p_total_stock)>0
 
																			UNION ALL 

																			SELECT * FROM (
																				SELECT 	 
																					'0' AS id, product_id, '' AS stock_grade, SUM(po_order_qty) AS p_total_stock,
																					ROUND(SUM(total_price) / SUM(po_order_qty), 4) AS avg_price, 
																					'Untested/Not Graded' AS status_name, '' AS p_inventory_status,  category_name, product_desc, product_uniqueid,
																					GROUP_CONCAT(DISTINCT (sub_location_name)) AS sub_location_names,
																					GROUP_CONCAT( (sub_location_id)) AS sub_location_ids, 
																					serial_no_barcode AS serial_nos,
																					GROUP_CONCAT(
																						CONCAT(COALESCE(po_no), ' ', COALESCE(po_status_name), ' (', COALESCE(po_order_qty), ')')
																						ORDER BY po_no
																					) AS po_details,  
																					GROUP_CONCAT((po_id) ORDER BY po_id ) AS po_ids 
																				FROM (
																					SELECT  
																						a.po_no, b.po_id, c1.product_id, SUM(1) AS po_order_qty, SUM(b.price) AS total_price, 
																						d.category_name, c.product_desc, c.product_uniqueid,
																						f.sub_location_name, b.sub_location_id,
																						e.status_name AS po_status_name, b.serial_no_barcode, b.overall_grade
																					FROM purchase_orders a 
																					INNER JOIN purchase_order_detail_receive b ON b.po_id = a.id
																					INNER JOIN purchase_order_detail c1 ON c1.id = b.po_detail_id
																					INNER JOIN products c ON c.id = c1.product_id
																					INNER JOIN product_categories d ON d.id = c.product_category
																					INNER JOIN warehouse_sub_locations f ON f.id = b.sub_location_id
																					INNER JOIN inventory_status e ON e.id = a.order_status
																					WHERE a.enabled = 1
																					AND a.order_status IN (3, 5, 6)
																					AND a.is_pricing_done = 0
																					AND b.is_rma_processed = 0
																					GROUP BY c1.product_id, b.po_id

																					UNION ALL

																					SELECT a.po_no, b.po_id, b.product_id, SUM(1) AS po_order_qty, SUM(b.price) AS total_price, 
																						d.category_name, c.product_desc, c.product_uniqueid,
																						f.sub_location_name, b.sub_location_id,
																						e.status_name AS po_status_name, b.serial_no_barcode, b.overall_grade
																					FROM purchase_orders a 
																					INNER JOIN purchase_order_detail_receive b ON b.po_id = a.id
																					INNER JOIN products c ON c.id = b.product_id
																					INNER JOIN warehouse_sub_locations f ON f.id = b.sub_location_id
																					INNER JOIN product_categories d ON d.id = c.product_category
																					INNER JOIN inventory_status e ON e.id = a.order_status
																					WHERE a.enabled = 1
																					AND order_status IN(3, 5, 6)
																					AND a.is_pricing_done = 0
																					GROUP BY b.product_id, b.po_id
																					
																					UNION ALL
																					
																					SELECT a.po_no, b.po_id, b.product_id, SUM(b.order_qty) AS po_order_qty, SUM(b.order_qty*order_price) AS total_price, 
																						d.category_name, c.product_desc, c.product_uniqueid,
																						'' AS sub_location_name, '' AS sub_location_id,
																						e.status_name AS po_status_name, '' as serial_no_barcode, '' as overall_grade
																					FROM purchase_orders a 
																					INNER JOIN purchase_order_detail b ON b.po_id = a.id
																					INNER JOIN products c ON c.id = b.product_id
																					INNER JOIN product_categories d ON d.id = c.product_category
																					INNER JOIN inventory_status e ON e.id = a.order_status
																					WHERE a.enabled = 1
																					AND b.order_qty > 0 
																					AND order_status IN(1, 3, 4, 11, 12) 
																					AND a.is_pricing_done = 0
																					GROUP BY b.product_id, b.po_id

																				) AS combined_data
																				WHERE product_id = '" . $id . "' 
																				GROUP BY product_id 
																			) AS t3
																		) AS t1 
																		WHERE 1=1 ";
														if (isset($flt_bin_id) && $flt_bin_id > 0) {
															$sql_cl1		.= " AND FIND_IN_SET('" . $flt_bin_id . "', sub_location_ids) ";
														}
														if (isset($flt_stock_status) && $flt_stock_status > 0) {
															$sql_cl1		.= " AND FIND_IN_SET('" . $flt_stock_status . "', p_inventory_status) ";
														}
														if (isset($flt_stock_grade) && $flt_stock_grade > 0) {
															$sql_cl1		.= " AND FIND_IN_SET('" . $flt_stock_grade . "', stock_grade) ";
														}
														$sql_cl1	.= " GROUP BY status_name, stock_grade  
																			ORDER BY status_name DESC, stock_grade "; //a2.p_inventory_status 
														$result_cl1	= $db->query($conn, $sql_cl1);
														$count_cl1	= $db->counter($result_cl1);
														if ($count_cl1 > 0) {
															$row_cl1 			= $db->fetch($result_cl1);
															$total_stock_p 		= $row_cl1[0]['p_total_stock'];
															$avg_price  		= $row_cl1[0]['avg_price'];
															$sub_location_names = $row_cl1[0]['sub_location_names'];
														}
														$sql_cl2	= "	SELECT *
																		FROM (
																			SELECT  a2.id, a2.product_id, a2.stock_grade, SUM(a2.p_total_stock) AS p_total_stock, SUM(a2.price)/SUM(a2.p_total_stock)  AS avg_price,
																				c.status_name, a2.p_inventory_status, b.category_name, a.product_desc, a.product_uniqueid,
																				GROUP_CONCAT(DISTINCT CONCAT(' ', COALESCE(b1.sub_location_name))) AS sub_location_names,
																				GROUP_CONCAT( (a2.sub_location)) AS sub_location_ids,
																				GROUP_CONCAT( (a2.serial_no)) AS serial_nos,
																				'' as po_details, 
																				'' as po_ids
																			FROM products a 
																			INNER JOIN product_stock a2 ON a2.product_id = a.id
																			LEFT JOIN product_categories b ON b.id = a.product_category
																			LEFT JOIN inventory_status c ON c.id = a2.p_inventory_status
																			LEFT JOIN warehouse_sub_locations b1 ON b1.id = a2.sub_location
																			WHERE 1=1 
																			AND a2.p_total_stock > 0 
																			AND a2.is_final_pricing = 1 
																			AND a.enabled = 1 
																			AND a2.product_id = '" . $id . "'
																			GROUP BY status_name, stock_grade

																			UNION ALL 

																			SELECT * FROM (
																				SELECT 	 
																					'0' AS id, product_id, '' AS stock_grade, SUM(po_order_qty) AS p_total_stock,
																					ROUND(SUM(total_price) / SUM(po_order_qty), 4) AS avg_price, 
																					'Untested/Not Graded' AS status_name, '' AS p_inventory_status,  category_name, product_desc, product_uniqueid,
																					GROUP_CONCAT(DISTINCT (sub_location_name)) AS sub_location_names,
																					GROUP_CONCAT( (sub_location_id)) AS sub_location_ids, 
																					serial_no_barcode AS serial_nos,
																					GROUP_CONCAT(
																						CONCAT(COALESCE(po_no), ' ', COALESCE(po_status_name), ' (', COALESCE(po_order_qty), ')')
																						ORDER BY po_no
																					) AS po_details,  
																					GROUP_CONCAT((po_id) ORDER BY po_id ) AS po_ids 
																				FROM (
																					SELECT  
																						a.po_no, b.po_id, c1.product_id, SUM(1) AS po_order_qty, SUM(b.price) AS total_price, 
																						d.category_name, c.product_desc, c.product_uniqueid,
																						f.sub_location_name, b.sub_location_id,
																						e.status_name AS po_status_name, b.serial_no_barcode, b.overall_grade
																					FROM purchase_orders a 
																					INNER JOIN purchase_order_detail_receive b ON b.po_id = a.id
																					INNER JOIN purchase_order_detail c1 ON c1.id = b.po_detail_id
																					INNER JOIN products c ON c.id = c1.product_id
																					INNER JOIN product_categories d ON d.id = c.product_category
																					INNER JOIN warehouse_sub_locations f ON f.id = b.sub_location_id
																					INNER JOIN inventory_status e ON e.id = a.order_status
																					WHERE a.enabled = 1
																					AND a.order_status IN (3, 5, 6)
																					AND a.is_pricing_done = 0
																					AND b.is_rma_processed = 0
																					GROUP BY c1.product_id, b.po_id

																					UNION ALL

																					SELECT a.po_no, b.po_id, b.product_id, SUM(1) AS po_order_qty, SUM(b.price) AS total_price, 
																						d.category_name, c.product_desc, c.product_uniqueid,
																						f.sub_location_name, b.sub_location_id,
																						e.status_name AS po_status_name, b.serial_no_barcode, b.overall_grade
																					FROM purchase_orders a 
																					INNER JOIN purchase_order_detail_receive b ON b.po_id = a.id
																					INNER JOIN products c ON c.id = b.product_id
																					INNER JOIN warehouse_sub_locations f ON f.id = b.sub_location_id
																					INNER JOIN product_categories d ON d.id = c.product_category
																					INNER JOIN inventory_status e ON e.id = a.order_status
																					WHERE a.enabled = 1
																					AND order_status IN(3, 5, 6)
																					AND a.is_pricing_done = 0
																					GROUP BY b.product_id, b.po_id
																					
																					UNION ALL
																					
																					SELECT a.po_no, b.po_id, b.product_id, SUM(b.order_qty) AS po_order_qty, SUM(b.order_qty*order_price) AS total_price, 
																						d.category_name, c.product_desc, c.product_uniqueid,
																						'' AS sub_location_name, '' AS sub_location_id,
																						e.status_name AS po_status_name, '' as serial_no_barcode, '' as overall_grade
																					FROM purchase_orders a 
																					INNER JOIN purchase_order_detail b ON b.po_id = a.id
																					INNER JOIN products c ON c.id = b.product_id
																					INNER JOIN product_categories d ON d.id = c.product_category
																					INNER JOIN inventory_status e ON e.id = a.order_status
																					WHERE a.enabled = 1
																					AND b.order_qty > 0 
																					AND order_status IN(1, 3, 4, 11, 12) 
																					AND a.is_pricing_done = 0
																					GROUP BY b.product_id, b.po_id

																				) AS combined_data
																				WHERE product_id = '" . $id . "' 
																				GROUP BY product_id 
																			) AS t3
																		) AS t1 
																		WHERE 1=1 ";
														if (isset($flt_serial_no) && $flt_serial_no != '') {
															$sql_cl2		.= " AND FIND_IN_SET('" . $flt_serial_no . "', serial_nos) ";
														}
														if (isset($flt_bin_id) && $flt_bin_id > 0) {
															$sql_cl2		.= " AND FIND_IN_SET('" . $flt_bin_id . "', sub_location_ids) ";
														}
														if (isset($flt_stock_status) && $flt_stock_status > 0) {
															$sql_cl2		.= " AND FIND_IN_SET('" . $flt_stock_status . "', p_inventory_status) ";
														}
														if (isset($flt_stock_grade) && $flt_stock_grade > 0) {
															$sql_cl2		.= " AND FIND_IN_SET('" . $flt_stock_grade . "', stock_grade) ";
														}
														$sql_cl2	.= " GROUP BY status_name, stock_grade  
																			ORDER BY status_name DESC, stock_grade "; //a2.p_inventory_status
														// echo "<br><br><br><br>" . $sql_cl2;
														$result_cl2	= $db->query($conn, $sql_cl2);
														$count_cl2	= $db->counter($result_cl2); ?>
														<tr>
															<td style="text-align: center;" class="col-<?= strtolower($table_columns[0]); ?>">
																<?php
																if ($count_cl2 > 0) { ?>
																	<a href="javascript:void(0)" class="plus_icon <?= "plus_" . $id; ?>" id="<?= $id; ?>"><i class="material-icons dp48" style="font-size: 20px;">add_circle_outline</i></a>
																	<a href="javascript:void(0)" class="minus_icon <?= "minus_" . $id; ?>" id="<?= $id; ?>"><i class="material-icons dp48" style="font-size: 20px;">remove_circle_outline</i></a>
																<?php } ?>
															</td>
															<td class="col-<?= strtolower($table_columns[1]); ?>">
																<?php
																if (access("edit_perm") == 1) { ?>
																	<a target="_blank" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=history&id=" . $id) ?>" title="Detail View">
																		<?php echo $product_uniqueid; ?>
																	</a> &nbsp;&nbsp;
																<?php } else {
																	echo  $product_uniqueid;
																} ?>
															</td>
															<td class="col-<?= strtolower($table_columns[2]); ?>"><?php echo $product_desc; ?></td>
															<td class="col-<?= strtolower($table_columns[3]); ?>"><?php echo $category_name; ?></td>
															<td class="col-<?= strtolower($table_columns[4]); ?>"></td>
															<td class="col-<?= strtolower($table_columns[5]); ?>"></td>
															<td class="col-<?= strtolower($table_columns[6]); ?>"></td>
															<td class="col-<?= strtolower($table_columns[7]); ?> text_align_right">
																<?php
																/*
																if (access("edit_perm") == 1) { ?>
																	<a target="_blank" class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=detailStock&id=" . $id . "&detail_id=" . $product_uniqueid . "&is_Submit=Y") ?>" title="Detail Stock View" style="padding: 20px;">
																		<?php echo $total_stock_p; ?>
																	</a> &nbsp;&nbsp;
																<?php } else {
																*/
																echo $total_stock_p;
																//} 
																?>
															</td>
															<td class="col-<?= strtolower($table_columns[8]); ?>"></td>
															<td class="col-<?= strtolower($table_columns[9]); ?>">
																<?php
																/*
																if (access("edit_perm") == 1) { ?>
																	<a target="_blank" class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=detailStock&id=" . $id . "&detail_id=" . $product_uniqueid . "&is_Submit=Y") ?>" title="Detail Stock View" style="padding: 20px;">
																		<?php echo number_format($avg_price, 2); ?>
																	</a> &nbsp;&nbsp;
																<?php } else {
																*/
																echo number_format($avg_price, 2);
																//} 
																?>
															</td>
														</tr>
														<?php
														if ($count_cl2 > 0) {
															$row_cl2 = $db->fetch($result_cl2);
															foreach ($row_cl2 as $data2) {
																$id2 					= $data2['id'];
																$product_id 			= $data2['product_id'];
																$po_details				= $data2['po_details'];
																$po_ids					= $data2['po_ids'];
																$filter_1 				= $data2['p_inventory_status'];
																$filter_2 				= $data2['stock_grade']; ?>
																<tr class="detail_tr <?= $id; ?>">
																	<td style="text-align: center;" class="col-<?= strtolower($table_columns[0]); ?>">
																		<?php
																		if ($id2 > 0) { ?>
																			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																			<a href="javascript:void(0)" class="plus_icon_sub <?= "sub_plus_" . $id2; ?>" id="<?= $id2; ?>"><i class="material-icons dp48" style="font-size: 20px;">add_circle_outline</i></a>
																			<a href="javascript:void(0)" class="minus_icon_sub <?= "sub_minus_" . $id2; ?>" id="<?= $id2; ?>"><i class="material-icons dp48" style="font-size: 20px;">remove_circle_outline</i></a>
																		<?php } ?>
																	</td>
																	<td class="col-<?= strtolower($table_columns[1]); ?>">
																		<?php echo  $product_uniqueid;  ?>
																	</td>
																	<td class="col-<?= strtolower($table_columns[2]); ?>">
																		<?php echo $product_desc; ?>
																	</td>
																	<td class="col-<?= strtolower($table_columns[3]); ?>"> <?php echo $category_name; ?></td>
																	<td class="col-<?= strtolower($table_columns[4]); ?>">
																		<?php echo $data2['status_name']; ?>
																	</td>
																	<td class="col-<?= strtolower($table_columns[5]); ?>"><?php echo $data2['stock_grade']; ?></td>
																	<td class="col-<?= strtolower($table_columns[6]); ?>">
																		<?php
																		if ($id2 == '0') { ?>
																			In transit
																			<?php
																			$po_detail_array		= explode(",", $po_details);
																			$po_ids_array			= explode(",", $po_ids);
																			$po_module_permision 	=  check_module_permission($db, $conn, 10, $_SESSION["user_id"], $_SESSION["user_type"]);
																			if ($po_module_permision != "") {
																				$m = 0;
																				foreach ($po_detail_array as $po_detail_data) {
																					$data_po_id = '';
																					if (isset($po_ids_array[$m])) $data_po_id = $po_ids_array[$m]; ?>
																					<br>
																					<a target="_blank" href="?string=<?php echo encrypt("module_id=10&page=profile&cmd=edit&id=" . $data_po_id . "&active_tab=tab1") ?>">
																						<?php echo $po_detail_data; ?>
																					</a>
																		<?php
																					$m++;
																				}
																			} else {
																				foreach ($po_detail_array as $po_detail_data) {
																					echo "<br>";
																					echo $po_detail_data;
																				}
																			}
																		} else if ($data2['sub_location_names'] != '') {
																			echo $data2['sub_location_names'];
																		} ?>
																	</td>
																	<td class="col-<?= strtolower($table_columns[7]); ?> text_align_right">
																		<?php
																		if (access("edit_perm") == 1 && $id2 > 0) { ?>
																			<a target="_blank" class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=detailStock&id=" . $product_id . "&detail_id=" . $product_uniqueid . "&filter_1=" . $filter_1 . "&filter_2=" . $filter_2 . "&is_Submit=Y") ?>" title="Detail Stock View" >
																				<?php echo $data2['p_total_stock']; ?>
																			</a>
																			<?php } else {
																			echo '' . $data2['p_total_stock'];
																		} ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																	</td>
																	<td class="col-<?= strtolower($table_columns[8]); ?>"></td>
																	<td class="col-<?= strtolower($table_columns[9]); ?>">
																		<?php
																		if (access("edit_perm") == 1 && $id2 > 0) { ?>
																			<a target="_blank" class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=detailStock&id=" . $product_id . "&detail_id=" . $product_uniqueid . "&filter_1=" . $filter_1 . "&filter_2=" . $filter_2 . "&is_Submit=Y") ?>" title="Detail Stock View" >
																				<?php echo number_format($data2['avg_price'], 2); ?>
																			</a> &nbsp;&nbsp;
																		<?php } else {
																			echo number_format($data2['avg_price'], 2);
																		} ?>
																	</td>
																</tr>
																<?php
																$sql_cl3 	= "	SELECT a2.*, d.sub_location_name
																				FROM product_stock a2 
																				LEFT JOIN warehouse_sub_locations d ON d.id = a2.sub_location
																				WHERE 1 = 1
																				AND a2.p_total_stock > 0
																				AND a2.is_final_pricing = 1
																				AND a2.enabled = 1 ";
																$sql_cl3	.= " AND a2.product_id = '" . $product_id . "' ";
																$sql_cl3	.= " AND a2.p_inventory_status = '" . $filter_1 . "' ";
																$sql_cl3	.= " AND a2.stock_grade = '" . $filter_2 . "' ";
																if (isset($flt_bin_id) && $flt_bin_id > 0) {
																	$sql_cl3 .= " AND a2.sub_location = '" . $flt_bin_id . "'";
																}
																if (isset($flt_serial_no) && $flt_serial_no != "") {
																	$sql_cl3 .= " AND a2.serial_no = '" . $flt_serial_no . "'";
																}
																$sql_cl3	.= " ORDER BY a2.serial_no, d.sub_location_name";
																//echo "<br>".$sql_cl3;
																$result_cl3	= $db->query($conn, $sql_cl3);
																$count_cl3	= $db->counter($result_cl3);
																if ($count_cl3 > 0) {
																	$row_cl3 = $db->fetch($result_cl3);
																	foreach ($row_cl3 as $data3) {
																		$id3 = $data3['id']; ?>
																		<tr class="detail_tr <?= $id2; ?> datatr_<?= $id; ?>">
																			<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[0]); ?>"></td>
																			<td class="col-<?= set_table_headings($table_columns[1]); ?>"></td>
																			<td class="col-<?= set_table_headings($table_columns[2]); ?>"></td>
																			<td class="col-<?= set_table_headings($table_columns[3]); ?>"></td>
																			<td class="col-<?= set_table_headings($table_columns[4]); ?>"><?php echo $data2['status_name']; ?></td>
																			<td class="col-<?= set_table_headings($table_columns[5]); ?>"><?php echo $data2['stock_grade']; ?></td>
																			<td class="col-<?= set_table_headings($table_columns[6]); ?>"><?php echo $data3['sub_location_name']; ?></td>
																			<td class="col-<?= set_table_headings($table_columns[7]); ?> text_align_right">
																				<?php
																				$filter_3 = $data3['serial_no'];
																				/*
																				if (access("edit_perm") == 1) { ?>
																					<a target="_blank" class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=detailStock&id=" . $product_id . "&detail_id=" . $product_uniqueid . "&filter_1=" . $filter_1 . "&filter_2=" . $filter_2 . "&filter_3=" . $filter_3 . "&is_Submit=Y") ?>" title="Detail Stock View" style="padding: 20px;">
																					<?php echo $data3['p_total_stock']; ?>
																					</a> &nbsp;&nbsp;
																				<?php }
																				*/ ?>
																			</td>
																			<td class="col-<?= set_table_headings($table_columns[8]); ?>">
																				<a target="_blank" class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=serialNoDetail&id=" . $id . "&detail_id=" . $id3) ?>" title="Serial# Detail">
																					<?php echo $data3['serial_no']; ?>
																				</a> &nbsp;&nbsp;
																			</td>
																			<td class="col-<?= set_table_headings($table_columns[9]); ?>">
																				<?php
																				if (access("edit_perm") == 1) { ?>
																					<a target="_blank" class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=detailStock&id=" . $product_id . "&detail_id=" . $product_uniqueid . "&filter_1=" . $filter_1 . "&filter_2=" . $filter_2 . "&filter_3=" . $filter_3 . "&is_Submit=Y") ?>" title="Detail Stock View" >
																						<?php echo number_format($data3['price'], 2); ?>
																					</a> &nbsp;&nbsp;
																				<?php } else {
																					echo number_format($data3['price'], 2);
																				} ?>
																			</td>
																		</tr>
												<?php
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