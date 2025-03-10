<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
$module_status = 5;
$sql_cl = " SELECT DISTINCT a.sub_location, b.sub_location_name, b.sub_location_type 
            FROM product_stock a 
			INNER JOIN products a2 ON a2.id = a.product_id
			INNER JOIN product_categories a3 ON a3.id = a2.product_category
			INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location
			INNER JOIN purchase_order_detail_receive e ON e.id = a.receive_id
			INNER JOIN purchase_order_detail d3 ON d3.id = e.po_detail_id
			INNER JOIN purchase_orders d4 ON d4.id = d3.po_id
			INNER JOIN venders d5 ON d5.id = d4.vender_id
            WHERE a.p_total_stock > 0
			AND a.is_final_pricing = 1
            AND a.p_inventory_status =  '$module_status' ";
$flt_field_name = "flt_bin_id";
if (isset(${$flt_field_name}) && ${$flt_field_name} != "") {
	$sql_cl .= " AND a.sub_location = '" . ${$flt_field_name} . "' ";
}
$flt_field_name = "flt_product_category";
if (isset(${$flt_field_name}) && ${$flt_field_name} != "") {
	$sql_cl .= " AND a2.product_category = '" . ${$flt_field_name} . "' ";
}
$flt_field_name = "flt_product_id";
if (isset(${$flt_field_name}) && ${$flt_field_name} != "") {
	$sql_cl .= " AND a2.product_uniqueid = '" . ${$flt_field_name} . "' ";
}
$flt_field_name = "flt_product_desc";
if (isset(${$flt_field_name}) && ${$flt_field_name} != "") {
	$sql_cl .= " AND a2.product_desc = '" . ${$flt_field_name} . "' ";
}
$flt_field_name = "flt_condition";
if (isset(${$flt_field_name}) && ${$flt_field_name} != "") {
	$sql_cl .= " AND a.stock_grade = '" . ${$flt_field_name} . "' ";
}
$flt_field_name = "flt_vendor_type";
if (isset(${$flt_field_name}) && ${$flt_field_name} != "") {
	$sql_cl .= " AND d5.vender_type = '" . ${$flt_field_name} . "' ";
}
$sql_cl .= "GROUP BY a.sub_location
			ORDER BY a.sub_location ";
// echo "<br><br><br><br><br><br>" . $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);

$sql_u 			= " SELECT id,CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) AS user_full_name FROM users WHERE  FIND_IN_SET(  'Processing' , user_sections) > 0 "; //echo $sql_u;
$result_u		= $db->query($conn, $sql_u);
$count_u		= $db->counter($result_u);

$sql_cl2		= " SELECT DISTINCT a3.id, a3.category_name, 
						COUNT(a.id) AS qty, IFNULL(devices_per_user_per_day, 0) AS devices_per_user_per_day,
						IFNULL((COUNT(a.id) / (devices_per_user_per_day*" . $count_u . ")), 0) AS estimated_time_hours
					FROM product_stock a 
					INNER JOIN  products a2 ON a2.id = a.product_id
					INNER JOIN product_categories a3 ON a3.id = a2.product_category
					INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location
					LEFT JOIN formula_category c ON c.product_category = a2.product_category AND c.formula_type = 'Processing' AND c.enabled = 1
 					WHERE a.p_total_stock > 0 
					AND a.is_final_pricing = 1
					AND a.p_inventory_status =  '$module_status'  ";
$sql_cl2 .= " GROUP BY a3.id ";
// echo $sql_cl2;
$result_cl2		= $db->query($conn, $sql_cl2);
$count_cl2		= $db->counter($result_cl2);
$page_heading 	= "List of Bins For Processing ( Manager View)";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<!-- <div class="container"> -->
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
				<div class="row">
					<div class="col s12">
						<div class="card custom_margin_card_table_top">
							<div class="card-content custom_padding_card_content_table_top">
								<h4 class="card-title">Categories Wise Detail</h4>
								<?php
								if ($count_cl2 > 0) {
									$row_cl = $db->fetch($result_cl2);
									$i = 1;
									foreach ($row_cl as $data) {
										$id 					= $data['id'];
										$category_name			= $data['category_name'];
										$qty 					= $data['qty'];
										$estimated_time_hours	= round($data['estimated_time_hours'], 2);
										$field_name 			= "category[" . $id . "]";
										${$field_name} 			= $qty;
										$field_id 				= "category" . $i;
										$field_label 			= $category_name;
										$estimated_time[$id] 	= $estimated_time_hours; ?>
										<div class="row">
											<div class="input-field col m3 s12">
												<i class="material-icons prefix">apps</i>
												<input id="<?= $field_id; ?>" readonly type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																			echo ${$field_name};
																																		} ?>">
												<label for="<?= $field_id; ?>"><?= $field_label; ?></label>
											</div>
											<?php
											$field_name		= "estimated_time[" . $id . "]";
											$field_id 		= "estimated_time" . $i;
											$field_label 	= "Estimated Time";
											?>
											<div class="input-field col m3 s12">
												<i class="material-icons prefix">access_time</i>
												<input id="<?= $field_id; ?>" readonly type="text" name="<?= $field_name; ?>" value="<?php if (isset($estimated_time[$id])) {
																																			echo $estimated_time[$id];
																																		} ?>" class="twoDecimalNumber validate">
												<label for="<?= $field_id; ?>"><?= $field_label; ?></label>

											</div>
										</div>
								<?php
										$i++;
									}
								} ?>
							</div>
						</div>
					</div>
				</div>
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
								<h4 class="card-title">Bins / Locations</h4>
								<form method="post" autocomplete="off" enctype="multipart/form-data" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page) ?>">
									<input type="hidden" name="is_Submit" value="Y" />
									<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																						echo encrypt($_SESSION['csrf_session']);
																					} ?>">
									<br>
									<div class="row">
										<div class="input-field col m2 s12">
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<?php
												$field_name     = "flt_vendor_type";
												$field_label    = "Vendor Type";
												$sql1           = " SELECT DISTINCT d5.vender_type, d6.type_name
																	FROM venders d5 
																	INNER JOIN vender_types d6 ON d6.id = d5.vender_type 
																	WHERE 1=1 
																	ORDER BY d6.type_name ";
												$result1        = $db->query($conn, $sql1);
												$count1         = $db->counter($result1); ?>
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">All</option>
													<?php
													if ($count1 > 0) {
														$row1    = $db->fetch($result1);
														foreach ($row1 as $data2) { ?>
															<option value="<?php echo $data2['vender_type']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['vender_type']) { ?> selected="selected" <?php } ?>><?php echo $data2['type_name'];  ?></option>
													<?php }
													} ?>
												</select>
												<label for="<?= $field_id; ?>"><?= $field_label; ?> </label>
											</div>
										</div>
										<div class="input-field col m3 s12">
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<?php
												$field_name     = "flt_product_id";
												$field_label    = "Product ID";
												$sql11          = " SELECT DISTINCT a2.product_uniqueid 
																	FROM products a2 
																	INNER JOIN product_stock b ON b.product_id = a2.id
																	WHERE  1=1 AND a2.enabled = 1 
																	AND b.p_total_stock > 0
																	AND b.is_final_pricing = 1
																	AND b.p_inventory_status = '$module_status' 
																	ORDER BY a2.product_uniqueid ";
												$result11       = $db->query($conn, $sql11);
												$count11        = $db->counter($result11);
												?>
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">All</option>
													<?php
													if ($count11 > 0) {
														$row11    = $db->fetch($result11);
														foreach ($row11 as $data12) { ?>
															<option value="<?php echo $data12['product_uniqueid']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data12['product_uniqueid']) { ?> selected="selected" <?php } ?>><?php
																																																												echo $data12['product_uniqueid']; ?>
															</option>
													<?php }
													} ?>
												</select>
												<label for="<?= $field_id; ?>"><?= $field_label; ?> </label>
											</div>
										</div>
										<div class="input-field col m4 s12">
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<?php
												$field_name     = "flt_product_desc";
												$field_label    = "Product DESC";
												$sql11          = " SELECT DISTINCT a2.product_desc 
																	FROM products a2  
																	INNER JOIN product_stock b ON b.product_id = a2.id 
																	WHERE a2.product_desc != '' 
 																	AND b.p_total_stock > 0
																	AND b.is_final_pricing > 0
																	AND b.p_inventory_status = '$module_status' 
																	AND a2.enabled = 1 
																	ORDER BY a2.product_desc  ";
												$result11       = $db->query($conn, $sql11);
												$count11        = $db->counter($result11);
												?>
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">All</option>
													<?php
													if ($count11 > 0) {
														$row11    = $db->fetch($result11);
														foreach ($row11 as $data12) { ?>
															<option value="<?php echo $data12['product_desc']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data12['product_desc']) { ?> selected="selected" <?php } ?>><?php
																																																										echo $data12['product_desc']; ?>
															</option>
													<?php }
													} ?>
												</select>
												<label for="<?= $field_id; ?>"><?= $field_label; ?> </label>
											</div>
										</div>
										<div class="input-field col m3 s12">
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<?php
												$field_name     = "flt_product_category";
												$field_label    = "Product Category";
												$sql11          = "SELECT DISTINCT a3.id, a3.category_name
																	FROM product_stock a 
																	INNER JOIN  products a2 ON a2.id = a.product_id
																	INNER JOIN product_categories a3 ON a3.id = a2.product_category 
																	WHERE a.p_total_stock > 0
																	AND a.is_final_pricing > 0
																	AND a.p_inventory_status =  '$module_status' 
																	ORDER BY a3.category_name ";
												$result11       = $db->query($conn, $sql11);
												$count11        = $db->counter($result11);
												?>
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">All</option>
													<?php
													if ($count11 > 0) {
														$row11    = $db->fetch($result11);
														foreach ($row11 as $data12) { ?>
															<option value="<?php echo $data12['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data12['id']) { ?> selected="selected" <?php } ?>><?php
																																																					echo $data12['category_name']; ?>
															</option>
													<?php }
													} ?>
												</select>
												<label for="<?= $field_id; ?>"><?= $field_label; ?> </label>
											</div>
										</div>
									</div>
									<br>
									<div class="row">
										<div class="input-field col m2 s12">
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<?php
												$field_name     = "flt_condition";
												$field_label    = "Condition";
												?>
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">All</option>
													<option value="A" <?php if (isset(${$field_name}) && ${$field_name} == 'A') { ?> selected="selected" <?php } ?>>A</option>
													<option value="B" <?php if (isset(${$field_name}) && ${$field_name} == 'B') { ?> selected="selected" <?php } ?>>B</option>
													<option value="C" <?php if (isset(${$field_name}) && ${$field_name} == 'C') { ?> selected="selected" <?php } ?>>C</option>
													<option value="D" <?php if (isset(${$field_name}) && ${$field_name} == 'D') { ?> selected="selected" <?php } ?>>D</option>
												</select>
												<label for="<?= $field_id; ?>"><?= $field_label; ?> </label>
											</div>
										</div>
										<div class="input-field col m3 s12">
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<?php
												$field_name     = "flt_bin_id";
												$field_label    = "Bin/Location";

												$sql1		= " SELECT distinct b.id,b.sub_location_name, b.sub_location_type
																FROM product_stock a 
 																INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location 
																WHERE 1=1 
																AND a.p_total_stock > 0
																AND a.is_final_pricing > 0
																AND a.p_inventory_status =  '$module_status'
																ORDER BY b.sub_location_name, b.sub_location_type ";
												$result1	= $db->query($conn, $sql1);
												$count1		= $db->counter($result1);
												?>
												<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																													echo ${$field_name . "_valid"};
																																												} ?>">
													<option value="">All</option>
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
												<label for="<?= $field_id; ?>"><?= $field_label; ?> </label>
											</div>
										</div>
										<div class="input-field col m3 s12">
											<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button>
											&nbsp;&nbsp;
											<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">All</a>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="text_align_right">
										<?php
										$table_columns	= array('SNo', 'Location / Bin', 'Details', 'Total Qty', 'Assign User');
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
								<div class="row">
									<div class="col s12">
										<table id="page-length-option" class="display pagelength50_3">
											<thead>
												<tr>
													<?php
													$headings = "";
													foreach ($table_columns as $data_c) {
														if ($data_c == 'SNo') {
															$headings .= '<th class="text_align_center sno_width_60 col-' . set_table_headings($data_c) . '">' . $data_c . '</th>';
														} else {
															$headings .= '<th class="text_align_center col-' . set_table_headings($data_c) . '">' . $data_c . '</th> ';
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
														$id = $data['sub_location']; ?>
														<tr>
															<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[0]); ?>"><?php echo $i + 1; ?></td>
															<td class="text_align_center col-<?= set_table_headings($table_columns[1]); ?>">
																<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=bin_detail&cmd=edit&id=" . $id) ?>">
																	<?php
																	echo $data['sub_location_name'];
																	if ($data['sub_location_type'] != "") {
																		echo "(" . ucwords(strtolower($data['sub_location_type'])) . ")";
																	} ?>
																</a>
															</td>
															<td class="text_align_center col-<?= set_table_headings($table_columns[2]); ?>">
																<?php
																$total_qty = 0;
																$sql_cl3 = "SELECT COUNT(*) AS qty, a3.category_name  
																				FROM product_stock a 
																				INNER JOIN products a2 ON a2.id = a.product_id
																				INNER JOIN product_categories a3 ON a3.id = a2.product_category
																				WHERE a.p_total_stock > 0
																				AND a.is_final_pricing > 0
																				AND a.p_inventory_status =  '$module_status' 
																				AND a.sub_location = '" . $id . "' 
																				GROUP BY a3.category_name
																				ORDER BY a3.category_name ";
																$result_cl3		= $db->query($conn, $sql_cl3);
																$count_cl3		= $db->counter($result_cl3);
																if ($count_cl3 > 0) {
																	$row_cl3 = $db->fetch($result_cl3);
																	foreach ($row_cl3 as $data3) {
																		$total_qty += $data3['qty']; ?>
																		<div class="col m8 s12" style="text-align: right;"><b></b><?= $data3['category_name']; ?></div>
																		<div class="col m4 s12"><b>Qty: </b><?= $data3['qty']; ?></div>
																<?php
																	}
																} ?>
															</td>
															<td class="text_align_center col-<?= set_table_headings($table_columns[3]); ?>"><?php echo $total_qty; ?></td>
															<td class="text_align_center col-<?= set_table_headings($table_columns[4]); ?>">
																<div class="input-field col m12 s12">
																	<div class="select2div">
																		<?php
																		$sql_u13			= " SELECT * FROM users_bin_for_processing WHERE location_id = '$id' AND is_processing_done = 0 "; //echo $sql_u;
																		$result_u13		= $db->query($conn, $sql_u13);
																		$count_u13		= $db->counter($result_u13);
																		if ($count_u13 > 0) {
																			$row_u13 = $db->fetch($result_u13);
																			$bin_user_id = $row_u13[0]['bin_user_id'];
																			$location_id = $row_u13[0]['location_id'];
																		}
																		$field_name     = "bin_user_id";
																		$field_id     	= "bin_user_id-" . $id;
																		$field_label    = "Users";

																		$sql_u1			= " SELECT id,CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) AS user_full_name FROM users WHERE  FIND_IN_SET(  'Processing' , user_sections) > 0 "; //echo $sql_u;
																		$result_u1		= $db->query($conn, $sql_u1);
																		$count_u1		= $db->counter($result_u1);
																		?>
																		<select id="<?= $field_id; ?>" name="<?= $field_name; ?>" class="bin_user_id select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																																					echo ${$field_name . "_valid"};
																																																				} ?>">
																			<option value="">Assign User</option>
																			<?php

																			if ($count_u1 > 0) {
																				$row_u1 = $db->fetch($result_u1);
																				foreach ($row_u1 as $data_u1) { ?>
																					<option value="<?php echo $data_u1['id']; ?>" <?php if (isset($bin_user_id) && $bin_user_id == $data_u1['id'] && $location_id == $id) { ?> selected="selected" <?php } ?>><?php echo $data_u1['user_full_name']; ?></option>
																			<?php
																				}
																			} ?>
																		</select>
																	</div>
																</div>
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
				<div class="row">
					<div class="col s12">
						<div class="card custom_margin_card_table_top">
							<div class="card-content custom_padding_card_content_table_top">
								<input type="hidden" name="module_id" id="module_id" value="<?= $module_id; ?>">
								<?php
								if (isset($error2['msg'])) { ?>
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card red lighten-5">
												<div class="card-content red-text">
													<p><?php echo $error2['msg']; ?></p>
												</div>
												<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										</div>
									</div>
								<?php } else if (isset($msg2['msg_success'])) { ?>
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card green lighten-5">
												<div class="card-content green-text">
													<p><?php echo $msg2['msg_success']; ?></p>
												</div>
												<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										</div>
									</div>
								<?php } ?>
								<h4 class="card-title">Users</h4>
								<style>
									.user-list-container {
										display: flex;
										flex-direction: column;
										margin: 10px;
									}

									.user-list {
										display: flex;
										gap: 10px;
										border: 2px dashed #ccc;
										padding: 10px;
										flex-wrap: wrap;
									}

									.user {
										background-color: #f0f0f0;
										padding: 10px;
										cursor: grab;
										border: 1px solid #ddd;
										border-radius: 4px;
									}

									.user1 {
										background-color: #f0f0f0;
										padding: 10px;
										border: 1px solid #ddd;
										border-radius: 4px;
									}

									.drop-row {
										display: flex;
										justify-content: space-between;
										margin: 10px 0;
										align-items: center;
									}

									.location-column {
										width: 40%;
										padding: 10px;
										text-align: center;
										background-color: #f0f0f0;
										border: 1px solid #ddd;
										border-radius: 4px;
									}

									.drop-column {
										width: 55%;
									}

									.drop-box {
										border: 2px dashed #ccc;
										min-height: 100px;
										padding: 10px;
										background-color: #ffffff;
									}

									.drop-box.dragover {
										background-color: #d9f7be;
									}
								</style>
								<div class="row">
									<div class="col s12 bin_users">
										<?php include('display_users_bins.php'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- </div> -->
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
	$(document).ready(function() {
		let draggedElement = null; // Track the dragged user element

		// Drag start event for users
		$('.user').on('dragstart', function(e) {
			draggedElement = $(this); // Store the dragged element
			e.originalEvent.dataTransfer.setData('text/plain', $(this).data('id'));
		});

		// Drag over event for users
		$('.user').on('dragover', function(e) {
			e.preventDefault(); // Allow dropping
			$(this).addClass('dragover'); // Highlight the target user
		});

		// Drag leave event for users
		$('.user').on('dragleave', function() {
			$(this).removeClass('dragover'); // Remove the highlight
		});

		// Drop event for users
		$('.user').on('drop', function(e) {
			e.preventDefault();
			$(this).removeClass('dragover'); // Remove the highlight

			const targetElement = $(this); // The user element being dropped on

			// Swap positions of draggedElement and targetElement
			if (draggedElement && targetElement.length && draggedElement[0] !== targetElement[0]) {
				if (targetElement.index() > draggedElement.index()) {
					targetElement.after(draggedElement);
				} else {
					targetElement.before(draggedElement);
				}

				// Send the updated order to the backend
				const reorderedIds = [];
				$('#user-list .user').each(function() {
					reorderedIds.push($(this).data('id'));
				});
				var module_id = $("#module_id").val();
				let dataString = `module_id=${module_id}&type=update_order&user_ids=${reorderedIds}`;
				// AJAX to save the new order
				$.ajax({
					type: "POST",
					url: "ajax/ajax_add_entries.php",
					data: dataString,
					success: function(response) {
						console.log('Order updated successfully:', response);
					},
					error: function() {
						alert('Error updating order.');
					}
				});
			}

			draggedElement = null; // Reset the dragged element
		});
	});
</script>