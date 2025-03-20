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

if (isset($cmd) && ($cmd == 'disabled' || $cmd == 'enabled') && access("delete_perm") == 0) {
	$error['msg'] = "You do not have edit permissions.";
} else {
	if (isset($cmd) && $cmd == 'disabled') {
		$sql_c_upd = "DELETE FROM " . $selected_db_name . ".users_bin_for_diagnostic WHERE id = '$id ' AND is_processing_done = 0 ";
		$ok = $db->query($conn, $sql_c_upd);
		$enabe_ok = $db->query($conn, $sql_c_upd);
		if ($enabe_ok) {
			$msg['msg_success'] = "Record has been removed.";
		} else {
			$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
		}
	}
}
$module_status = 5;
$sql_cl = " SELECT DISTINCT IFNULL(e.id, 0) AS bin_id, a.sub_location_id AS sub_location, d.sub_location_name, d.sub_location_type, 
					GROUP_CONCAT(DISTINCT CONCAT('<br>PO#: ', COALESCE(c.po_no, 'N/A'), ', Vendor Name: ', COALESCE(a2.vender_name, 'N/A')) ORDER BY c.po_no SEPARATOR '') AS po_detail,
					GROUP_CONCAT(DISTINCT date_format(a.add_date, '%Y-%m-%d') ORDER BY a.add_date SEPARATOR '<br>') AS received_dates
			FROM purchase_order_detail_receive a
			INNER JOIN purchase_orders c ON c.id = a.po_id
			INNER JOIN venders a2 ON a2.id = c.vender_id
			INNER JOIN warehouse_sub_locations d ON d.id = a.sub_location_id
 			LEFT JOIN users_bin_for_diagnostic e ON e.location_id = a.sub_location_id AND e.is_processing_done = 0
			WHERE 1= 1 
			AND is_diagnost = 0 
			AND IFNULL(e.id, 0) = 0 ";
if (isset($flt_bin_id) && $flt_bin_id != "") {
	$sql_cl .= " AND a.sub_location_id = '" . $flt_bin_id . "' ";
}
if (isset($flt_product_category) && $flt_product_category != "") {
	$sql_cl .= "  AND FIND_IN_SET(  '" . $flt_product_category . "' , a.recevied_product_category) > 0 ";
}

$sql_cl .= " GROUP BY a.sub_location_id
			 HAVING COUNT(*)> 0
			 ORDER BY a.sub_location_id ";
// echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);

$sql_u 			= " SELECT id,CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) AS user_full_name 
					FROM users 
					WHERE FIND_IN_SET(  'Diagnostic' , user_sections) > 0 
					AND enabled = 1"; //echo $sql_u;
$result_u		= $db->query($conn, $sql_u);
$count_u		= $db->counter($result_u);

$sql_cl2			= " SELECT  DISTINCT IFNULL(f.id, 0) AS bin_id, a3.id, a3.category_name, 
								COUNT(a.id) AS qty, IFNULL(e.devices_per_user_per_day, 0) AS devices_per_user_per_day,
								IFNULL((COUNT(a.id) / (e.devices_per_user_per_day*" . $count_u . ")), 0) AS estimated_time_hours 
						FROM purchase_order_detail_receive a
 						INNER JOIN purchase_orders c ON c.id = a.po_id
 						INNER JOIN product_categories a3 ON a3.id = a.recevied_product_category
						INNER JOIN warehouse_sub_locations d ON d.id = a.sub_location_id
						LEFT JOIN formula_category e ON e.product_category = a.recevied_product_category AND e.formula_type = 'Diagnostic' AND e.enabled = 1
 						LEFT JOIN users_bin_for_diagnostic f ON f.location_id = a.sub_location_id AND f.is_processing_done = 0
						WHERE 1=1
						AND a.is_diagnost = 0 
						AND IFNULL(f.id, 0) = 0
						GROUP BY a.recevied_product_category "; //echo $sql_cl2;
$result_cl2		= $db->query($conn, $sql_cl2);
$count_cl2		= $db->counter($result_cl2);
$page_heading 	= "List of Bins For Diagnostic ( Manager View)";
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
								<form method="post" autocomplete="off" action="?string=<?php echo encrypt("module_id=" . $module_id . "&page=" . $page) ?>" enctype="multipart/form-data">
									<input type="hidden" name="is_Submit2" value="Y" />
									<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																						echo encrypt($_SESSION['csrf_session']);
																					} ?>">
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
								</form>
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
								<form method="post" autocomplete="off" enctype="multipart/form-data" action="?string=<?php echo encrypt("module_id=" . $module_id . "&page=" . $page) ?>">

									<input type="hidden" id="module_url" value="<?= PROJECT_URL; ?>/home?string=<?php echo encrypt("module_id=" . $module_id . "&page=" . $page) ?>" />
									<input type="hidden" name="is_Submit" value="Y" />
									<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																						echo encrypt($_SESSION['csrf_session']);
																					} ?>">
									<div class="row">

										<div class="input-field col m3 s12">
											<i class="material-icons prefix">question_answer</i>
											<div class="select2div">
												<?php
												$field_name     = "flt_bin_id";
												$field_label	= "Bin/Location";

												$sql1		= " SELECT b.id,b.sub_location_name, b.sub_location_type FROM warehouse_sub_locations b WHERE  b.enabled = 1 ";
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
												$field_name     = "flt_product_category";
												$field_label    = "Product Category";
												$sql11          = " SELECT b.id, b.category_name FROM  product_categories b WHERE  b.enabled = 1 ";
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
											<a href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=listing") ?>">All</a>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="text_align_right">
										<?php
										$table_columns	= array('SNo', 'Location / Bin', 'PO Detail', 'Received Date', 'Warranty Remaining', 'Details', 'Total Qty', 'Assign User');
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
										<table id="page-length-option" class="display pagelength100">
											<thead>
												<tr>
													<?php
													$headings = "";
													foreach ($table_columns as $data_c) {
														if ($data_c == 'SNo') {
															$headings .= '<th class="sno_width_60 col-' . set_table_headings($data_c) . '">' . $data_c . '</th>';
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
														$column_no = 0;
														$id = $data['sub_location']; ?>
														<tr>
															<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[$column_no]); ?>">
																<?php
																echo $i + 1;
																$column_no++;
																?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[$column_no]); ?>">
																<?php
																$column_no++;
																echo $data['sub_location_name'];
																if ($data['sub_location_type'] != "") {
																	echo "(" . ucwords(strtolower($data['sub_location_type'])) . ")";
																} ?>
															</td>
															<td class="text_align_center col-<?= set_table_headings($table_columns[$column_no]); ?>">
																<?php
																echo $data['po_detail'];
																$column_no++; ?>
															</td>
															<td class="text_align_center col-<?= set_table_headings($table_columns[$column_no]); ?>">
																<?php
																echo ($data['received_dates']);
																$column_no++; ?>
															</td>
															<td class="text_align_center col-<?= set_table_headings($table_columns[$column_no]); ?>">
																<?php

																$column_no++; ?>
															</td>

															<td class="col-<?= set_table_headings($table_columns[$column_no]); ?>">
																<?php
																$column_no++;
																$total_qty = 0;
																$sql_cl3 = "SELECT COUNT(*) AS qty, a3.category_name  
																				FROM purchase_order_detail_receive a
																				INNER JOIN purchase_orders c ON c.id = a.po_id
																				INNER JOIN product_categories a3 ON a3.id = a.recevied_product_category 
																				WHERE 1=1
																				AND a.is_diagnost = 0 
																				AND a.sub_location_id = '" . $id . "' 
																				GROUP BY a3.category_name
																				ORDER BY a3.category_name";
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
															<td class="text_align_center col-<?= set_table_headings($table_columns[$column_no]); ?>">
																<?php
																echo $total_qty;
																$column_no++; ?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[$column_no]); ?>">
																<div class="input-field col m12 s12">
																	<div class="select2div">
																		<?php
																		$column_no++;
																		$sql_u13			= " SELECT * FROM users_bin_for_diagnostic WHERE location_id = '$id' AND is_processing_done = 0 "; //echo $sql_u;
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

																		$sql_u1			= " SELECT id, CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) AS user_full_name
																		 					FROM users 
																							WHERE  FIND_IN_SET('Diagnostic' , user_sections) > 0 "; //echo $sql_u;
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
											</tbody>
										</table>
									</div>
								</div>  
							</div>
						</div>
					</div>
					<?php 
					$sql3 			="	SELECT b.id, CONCAT(COALESCE(a.first_name), ' ', COALESCE(a.last_name)) AS user_full_name, a.profile_pic,
											b.location_id, b.bin_user_id, b2.sub_location_name, b2.sub_location_type ,b.assignment_no
										FROM users a
										INNER JOIN users_bin_for_diagnostic b ON a.id = b.bin_user_id AND b.is_processing_done = '0'
										INNER JOIN warehouse_sub_locations b2 ON b2.id = b.location_id
										WHERE 1=1
										GROUP BY bin_user_id, location_id ";
					$result_cl3		= $db->query($conn, $sql3); 
					$count_3		= $db->counter($result_cl3);
					if($count_3 >0){?>
						<div class="col s12">
							<div class="card custom_margin_card_table_top">
								<div class="card-content custom_padding_card_content_table_top">
									<div class="row">
										<div class="text_align_right">
											<?php
											$table_columns	= array('SNo', 'Picture', 'Tester Name', 'Bin', 'Assignment#', 'Days', 'Actions');
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
											<table id="page-length-option" class="display pagelength50_10">
												<thead>
													<tr>
														<?php 
														$headings = "";
														foreach ($table_columns as $data_c) {
															if ($data_c == 'SNo') {
																$headings .= '<th class="sno_width_60 col-' . set_table_headings($data_c) . '">' . $data_c . '</th>';
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
													$row_cl3 = $db->fetch($result_cl3);
													foreach ($row_cl3 as $data3) {
														$column_no = 0;  
														
														$detail_id2             = $data3['id'];
														$bin_user_id             = $data3['bin_user_id'];
														$location_id             = $data3['location_id'];
														$sub_location_name        = $data3['sub_location_name'];
														$sub_location_type        = $data3['sub_location_type'];
														$assignment_no            = $data3['assignment_no'];
														$total_estimated_time     = 0;
															
														$sql_time ="SELECT IFNULL((COUNT(a.id) / e.devices_per_user_per_day), 0) AS estimated_time
																	FROM purchase_order_detail_receive a
																	LEFT JOIN formula_category e ON e.product_category = a.recevied_product_category AND e.formula_type = 'Diagnostic' AND e.enabled = 1
																	INNER JOIN users_bin_for_diagnostic d1 ON d1.location_id = a.sub_location_id AND d1.`is_processing_done` = 0 
																	WHERE 1=1
																	AND is_diagnost = 0
																	AND d1.bin_user_id = '$bin_user_id' 
																	AND d1.location_id = '$location_id' 
																	GROUP BY a.sub_location_id, e.product_category";
														$result_time    = $db->query($conn, $sql_time);
														$count_time    = $db->counter($result_time);
														if ($count_time > 0) {
															$row_time = $db->fetch($result_time);
															foreach ($row_time as $data_time) {
																$total_estimated_time += $data_time['estimated_time'];  ?>
														<?php }
														}  ?>
														<tr>
															<td class="col-<?= set_table_headings($table_columns[$column_no]); ?> text_align_center">
																<?php
																echo $i + 1;
																$column_no++;
																?>
															</td>
															<td class="text_align_center col-<?= set_table_headings($table_columns[$column_no]); ?>">
																<?php
																$column_no++;
																?>
																<span class="avatar-contact avatar-online">
																	<img src="app-assets/images/logo/<?php echo $data3['profile_pic']; ?>" style="height:70px !important;" alt="<?php echo $data3['user_full_name']; ?>">
																</span>
															</td> 
															<td class="text_align_center col-<?= set_table_headings($table_columns[$column_no]); ?>">
																<?php
																$column_no++;
																?>
																<?php echo $data3['user_full_name']; ?>
															</td> 
															<td class="col-<?= set_table_headings($table_columns[$column_no]); ?> text_align_center">
																<?php
																$column_no++;
																?>
																<?php echo $sub_location_name;?>
															</td> 
															<td class="col-<?= set_table_headings($table_columns[$column_no]); ?> text_align_center">
																<?php
																$column_no++;
																?>
																<?php echo $assignment_no; ?>
															</td> 
															<td class="col-<?= set_table_headings($table_columns[$column_no]); ?> text_align_center">
																<?php
																$column_no++;
																?>
																<?php echo round($total_estimated_time, 2); ?>
															</td> 
															<td class="col-<?= set_table_headings($table_columns[$column_no]); ?> text_align_center">
																<?php
																$column_no++;
																?>
																<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&bin_user_id=" . $bin_user_id . "&location_id=" . $location_id . "&page=editAssignment&cmd=edit&id=" . $detail_id2) ?>" title="Edit">
																	<i class="material-icons dp48">edit</i>
																</a>&nbsp;&nbsp;
																<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=disabled&id=" . $detail_id2) ?>" title="Disable" onclick="return confirm('Are you sure, You want to delete this record?')">
																	<i class="material-icons dp48">delete</i>
																</a>
																
															</td> 
														</tr>
												<?php $i++;
													}?>
												</tbody>
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
					<?php }?>
				</div>
				
				<?PHP /*?>
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
				<?PHP */?>
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
				let dataString = module_id = $ {
					module_id
				} & type = update_order_diagnostic & user_ids = $ {
					reorderedIds
				};
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