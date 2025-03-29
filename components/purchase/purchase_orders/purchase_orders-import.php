<?php

use Mpdf\Tag\Select;

if (!isset($module)) {
	require_once('../../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

$title_heading 			= "Import " . $main_menu_name;
$button_val 			= "Preview";
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}

$supported_column_titles	= array("po_no", "po_date", "vender", "vender_invoice_no",  "po_desc", "is_tested_po", "is_wiped_po", "is_imaged_po", "po_status", "po_stage", "product_id", "product_category", "order_qty", "order_price", "product_condition", "product_status");
$master_columns 			= array("po_no", "po_date", "vender", "vender_invoice_no",  "po_desc", "is_tested_po", "is_wiped_po", "is_imaged_po", "po_status", "po_stage");  // db fields
$duplication_columns 		= array("po_no");
$required_columns 			= array("po_no", "vender", "product_id", "order_qty", "order_price");

if (isset($is_Submit) && $is_Submit == 'Y') {
	if (isset($excel_data) && $excel_data == "") {
		$error['excel_data']	= "Required";
		$category_name_valid 	= "invalid";
	}
	if (empty($error)) {
		$excel_data = set_replace_string_char($excel_data);
		// Split the pasted data by new lines (each line is a row)
		$rows = explode(PHP_EOL, trim($excel_data));
		// Split each row by tabs or commas (each column in a row)
		$data = array();
		foreach ($rows as $row) {
			$data[] = preg_split('/[\t,]+/', trim($row)); // Split by tab or comma
		}
		// Separate headings (first row) from the data
		$headings = array_shift($data); // Get the first row as headings 

		////////////// validation on missing headings  ///////////////////
		//////////////////////////////////////////////////////////////////
		foreach ($data as $row_v1) {
			if (count($row_v1) > count($headings)) {
				$error['msg'] = "One or more column headings are missing.";
			}
		}
		if (!empty($error)) {
			$error['msg'] .= "<br>Please check Supported column titles.";
		}
		//////////////////////////////////////////////////////////////////

		/// All data cells should have values or add - if no 
		foreach ($data as $row11) {
			foreach ($row11 as $cell_array) {
				if (sizeof($headings) != sizeof($row11)) {
					if (!isset($error['msg'])) {
						$error['msg'] = "Please ensure that all cells contain values, or insert a dash (' - ') for any blank cells.";
					}
				}
			}
		}
	}
}

$master_table	= "purchase_orders";
$detail_table	= "purchase_order_detail";
$added 			= 0;

if (isset($is_Submit2) && $is_Submit2 == 'Y') {

	$import_colums_uniq 		= array_unique($import_colums);
	$total_import_column_set 	= count($import_colums_uniq);

	// if (sizeof($supported_column_titles) != $total_import_column_set) {
	// 	$error['msg'] = "One or more column headings are missing.";
	// }

	$required_columns_found = array();
	foreach ($import_colums_uniq as $import_colum) {
		if (in_array($import_colum, $required_columns)) {
			$required_columns_found[] = $import_colum;
		}
	}

	foreach ($required_columns as $required_column) {
		if (!in_array($required_column, $import_colums_uniq)) {
			if (isset($error['msg'])) {
				$error['msg'] .= "<br>" . $required_column . " column title is required.";
			} else {
				$error['msg'] = $required_column . " column title is required.";
			}
		}
	}

	// Initialize the new modified array
	$modified_array = array();
	$i 				= 0;
	foreach ($all_data as $value1) {
		$j = 0;
		foreach ($value1 as $key => $data) {
			$k = 0;
			foreach ($import_colums_uniq as $data2) {
				if ($k == $j) {
					$modified_array[$i][$data2] = $data;
				}
				$k++;
			}
			$j++;
		}
		$modified_array[$i]["is_insert"] = $data;
		$i++; // increment the index
	}
	if (empty($error)) {
		$ids_already = array();
		if (isset($all_data) && sizeof($all_data) > 0) {

			foreach ($all_data  as $data1) {
				$update_master = $update_product = $po_detail_table_id = $columns = $column_data = $update_detail_column = "";

				if (
					isset($data1['po_no']) && $data1['po_no'] != '' && $data1['po_no'] != NULL && $data1['po_no'] != 'blank'
					&& isset($data1['product_id']) && $data1['product_id'] != '' && $data1['product_id'] != NULL && $data1['product_id'] != 'blank'
					&& isset($data1['vender']) && $data1['vender'] != '' && $data1['vender'] != NULL && $data1['vender'] != 'blank'
				) {
					foreach ($data1 as $key => $data) {
						if (htmlspecialchars($data) == '-' || htmlspecialchars($data) == '' || htmlspecialchars($data) == 'blank') {
							$data = "";
						}
						if ($key != "" && $key != 'is_insert') {
							if ($data == '-' || $data == 'NA' || $data == 'N/A' || $data == 'blank') {
								$data = "";
							}
							if (in_array($key, $master_columns)) {

								if ($key == 'po_stage') {
									$key = "stage_status";
								}
								if ($key == 'po_status') {
									$key = "order_status";
								}
								if ($key == 'po_no' && $data != "") {
									$insert_db_field_id	= "po_id";
									$sql1 				= "SELECT * FROM " . $master_table . " WHERE " . $key . " = '" . $data . "' ";
									$result1 			= $db->query($conn, $sql1);
									$count1 			= $db->counter($result1);
									if ($count1 > 0) {
										$row1 					= $db->fetch($result1);
										${$insert_db_field_id}	= $row1[0]['id'];

										$columns 				.= ", " . $insert_db_field_id;
										$column_data 			.= ", '" . ${$insert_db_field_id} . "'";
									} else {
										$sql6 = "INSERT INTO " . $selected_db_name . "." . $master_table . "(subscriber_users_id, " . $key . ", add_date, add_by, add_ip, add_timezone)
												VALUES('" . $subscriber_users_id . "', '" . $data . "', '" . $add_date . "', '" . $_SESSION['username'] . " Imported', '" . $add_ip . "', '" . $timezone . "')";
										$ok = $db->query($conn, $sql6);
										if ($ok) {
											${$insert_db_field_id}	= mysqli_insert_id($conn);
											$columns 			.= ", " . $insert_db_field_id;
											$column_data 		.= ", '" . ${$insert_db_field_id} . "'";
										}
									}
								} else if ($key == 'vender') {
									if ($data != '' && $data != NULL && $data != '-' && $data != 'blank') {
										$insert_db_field_id_detail	= "vender_id";
										$insert_db_field_id_detai2	= "vender_name";
										$table1 					= "venders";

										$sql1 				= "SELECT * FROM " . $table1 . " WHERE " . $insert_db_field_id_detai2 . " = '" . $data . "' ";
										$result1 			= $db->query($conn, $sql1);
										$count1 			= $db->counter($result1);
										if ($count1 > 0) {
											$row1 							= $db->fetch($result1);
											${$insert_db_field_id_detail}	= $row1[0]['id'];

											$update_master .= $insert_db_field_id_detail . " = '" . ${$insert_db_field_id_detail} . "', ";
										} else {
											$sql6 = "INSERT INTO " . $selected_db_name . "." . $table1 . "(subscriber_users_id, " . $insert_db_field_id_detai2 . ", add_date, add_by, add_ip, add_timezone)
													VALUES('" . $subscriber_users_id . "', '" . $data . "', '" . $add_date . "', '" . $_SESSION['username'] . " Imported', '" . $add_ip . "', '" . $timezone . "')";
											$ok = $db->query($conn, $sql6);
											if ($ok) {
												${$insert_db_field_id_detail}	 = mysqli_insert_id($conn);

												$vender_no	= "V" . ${$insert_db_field_id_detail};
												$sql6		= "	UPDATE venders SET vender_no = '" . $vender_no . "' 
																WHERE id = '" . ${$insert_db_field_id_detail} . "' ";
												$db->query($conn, $sql6);

												$update_master .= $insert_db_field_id_detail . " = '" . ${$insert_db_field_id_detail} . "', ";
											}
										}
									}
								} else if ($key == 'order_status') {
									if ($data != '' && $data != NULL && $data != '-' && $data != 'blank') {
										$insert_db_field_id_detail	= "order_status";
										$insert_db_field_id_detai2	= "status_name";
										$table1 					= "inventory_status";

										$sql1 				= "SELECT * FROM " . $table1 . " WHERE " . $insert_db_field_id_detai2 . " = '" . $data . "' ";
										$result1 			= $db->query($conn, $sql1);
										$count1 			= $db->counter($result1);
										if ($count1 > 0) {
											$row1 							= $db->fetch($result1);
											${$insert_db_field_id_detail}	= $row1[0]['id'];

											$update_master .= $insert_db_field_id_detail . " = '" . ${$insert_db_field_id_detail} . "', ";
										} else {
											$sql6 = "INSERT INTO " . $selected_db_name . "." . $table1 . "(subscriber_users_id, " . $insert_db_field_id_detai2 . ", add_date, add_by, add_ip, add_timezone)
													VALUES('" . $subscriber_users_id . "', '" . $data . "', '" . $add_date . "', '" . $_SESSION['username'] . " Imported', '" . $add_ip . "', '" . $timezone . "')";
											$ok = $db->query($conn, $sql6);
											if ($ok) {
												${$insert_db_field_id_detail} = mysqli_insert_id($conn);
												$update_master .= $insert_db_field_id_detail . " = '" . ${$insert_db_field_id_detail} . "', ";
											}
										}
									}
								} else {
									if ($key == 'po_date') {
										$data = convert_date_mysql_slash($data);
									}
									$update_master .= $key . " = '" . $data . "', ";
								}
							} else {
								if (isset($po_id) && $po_id > 0) {

									if ($key == 'product_id') {
										$key = "product_uniqueid";
									}
									if ($key == 'product_uniqueid') {
										if ($data != '' && $data != NULL && $data != '-' && $data != 'blank') {
											$table1							= "products";
											$insert_db_field_id_detail_pd	= "product_id";

											$sql1		= "SELECT * FROM " . $table1 . " WHERE " . $key . " = '" . $data . "' ";
											$result1	= $db->query($conn, $sql1);
											$count1		= $db->counter($result1);
											if ($count1 > 0) {
												$row1								= $db->fetch($result1);
												${$insert_db_field_id_detail_pd}	= $row1[0]['id'];

												$columns		.= ", " . $insert_db_field_id_detail_pd;
												$column_data	.= ", '" . ${$insert_db_field_id_detail_pd} . "'";

												$update_detail_column 	.= ", " . $insert_db_field_id_detail_pd . " = '" . ${$insert_db_field_id_detail_pd} . "'";
											} else {

												$sql1		= "SELECT * FROM product_ids WHERE product_id = '" . $data . "' ";
												$result1	= $db->query($conn, $sql1);
												$count1		= $db->counter($result1);
												if ($count1 == 0) {
													$sql6 = "INSERT INTO " . $selected_db_name . ".product_ids(subscriber_users_id, product_id, add_date, add_by, add_ip, add_timezone)
																VALUES('" . $subscriber_users_id . "', '" . $data . "', '" . $add_date . "', '" . $_SESSION['username'] . " Imported', '" . $add_ip . "', '" . $timezone . "')";
													$db->query($conn, $sql6);
												} else {
													$sql6 = "UPDATE " . $selected_db_name . ".product_ids SET enabled = 1 WHERE product_id = '" . $data . "' ";
													$db->query($conn, $sql6);
												}

												$sql6 = "INSERT INTO " . $selected_db_name . "." . $table1 . "(subscriber_users_id, " . $key . ", add_date, add_by, add_ip, add_timezone)
														VALUES('" . $subscriber_users_id . "', '" . $data . "', '" . $add_date . "', '" . $_SESSION['username'] . " Imported', '" . $add_ip . "', '" . $timezone . "')";
												$ok = $db->query($conn, $sql6);
												if ($ok) {

													${$insert_db_field_id_detail_pd} = mysqli_insert_id($conn);

													$product_no	= "P" . ${$insert_db_field_id_detail_pd};
													$sql6		= "	UPDATE products SET product_no = '" . $product_no . "' 
																	WHERE id = '" . ${$insert_db_field_id_detail_pd} . "' ";
													$db->query($conn, $sql6);

													$columns				.= ", " . $insert_db_field_id_detail_pd;
													$column_data			.= ", '" . ${$insert_db_field_id_detail_pd} . "'";

													$update_detail_column 	.= ", " . $insert_db_field_id_detail_pd . " = '" . ${$insert_db_field_id_detail_pd} . "'";
												}
											}
										}
									} else if ($key == 'product_category') {
										if (isset($product_id)) {
											if ($key == 'product_category') {
												if ($data != '' && $data != NULL && $data != '-' && $data != 'blank') {
													$insert_db_field_id_detail	= "category_id";
													$insert_db_field_id_detail1	= "category_name";
													$table1 					= "product_categories";

													$sql1		= "SELECT * FROM " . $table1 . " WHERE " . $insert_db_field_id_detail1 . " = '" . $data . "' ";
													$result1	= $db->query($conn, $sql1);
													$count1		= $db->counter($result1);

													if ($count1 > 0) {
														$row1 = $db->fetch($result1);
														$update_product .= $key . " = '" . $row1[0]['id'] . "', ";
													} else {
														$sql6 = "INSERT INTO " . $selected_db_name . "." . $table1 . "(subscriber_users_id, " . $insert_db_field_id_detail1 . ", add_date, add_by, add_ip, add_timezone)
																VALUES('" . $subscriber_users_id . "', '" . $data . "', '" . $add_date . "', '" . $_SESSION['username'] . " Imported', '" . $add_ip . "', '" . $timezone . "')";
														$ok = $db->query($conn, $sql6);
														if ($ok) {
															$category_id = mysqli_insert_id($conn);
															$update_product .= $key . " = '" . $category_id . "', ";
														}
													}
												}
											} else {
												$update_product .= $key . " = '" . $data . "', ";
											}
										}
									} else if ($key == 'product_status') {
										if ($data != '' && $data != NULL && $data != '-' && $data != 'blank') {
											$insert_db_field_id_detail	= "expected_status";
											$insert_db_field_id_detai2	= "status_name";
											$table1 					= "inventory_status";

											$sql1 				= "SELECT * FROM " . $table1 . " WHERE " . $insert_db_field_id_detai2 . " = '" . $data . "' ";
											$result1 			= $db->query($conn, $sql1);
											$count1 			= $db->counter($result1);
											if ($count1 > 0) {
												$row1 							= $db->fetch($result1);
												${$insert_db_field_id_detail}	= $row1[0]['id'];

												$columns		.= ", " . $insert_db_field_id_detail_pd;
												$column_data	.= ", '" . ${$insert_db_field_id_detail_pd} . "'";

												$update_detail_column .= ", " . $insert_db_field_id_detail_pd . " = '" . ${$insert_db_field_id_detail_pd} . "'";
											} else {
												$sql6 = "INSERT INTO " . $selected_db_name . "." . $table1 . "(subscriber_users_id, " . $insert_db_field_id_detai2 . ", add_date, add_by, add_ip, add_timezone)
														VALUES('" . $subscriber_users_id . "', '" . $data . "', '" . $add_date . "', '" . $_SESSION['username'] . " Imported', '" . $add_ip . "', '" . $timezone . "')";
												$ok = $db->query($conn, $sql6);
												if ($ok) {
													${$insert_db_field_id_detail} = mysqli_insert_id($conn);

													$columns		.= ", " . $insert_db_field_id_detail_pd;
													$column_data	.= ", '" . ${$insert_db_field_id_detail_pd} . "'";

													$update_detail_column .= ", " . $insert_db_field_id_detail_pd . " = '" . ${$insert_db_field_id_detail_pd} . "'";
												}
											}
										}
									} else {
										$columns 		.= ", " . $key;
										$column_data 	.= ", '" . $data . "'";

										$update_detail_column .= ", " . $key . " = '" . $data . "'";
									}
								}
							}
						}
					}

					if (isset($po_id) && $po_id > 0) {
						if ($update_master != "") {
							$sql6 = " UPDATE " . $selected_db_name . "." . $master_table . " SET  " . $update_master . "
																									update_date 	= '" . $add_date . "', 
																									update_by 		= '" . $_SESSION['username'] . " Imported', 
																									update_ip 		= '" . $add_ip . "', 
																									update_timezone	= '" . $timezone . "'
								WHERE id = '" . $po_id . "' "; // echo "<br>" . $sql6;
							$db->query($conn, $sql6);
						}

						if ($update_product != "") {
							$sql6 = " UPDATE " . $selected_db_name . ".products SET  " . $update_product . "
																						update_date 	= '" . $add_date . "', 
																						update_by 		= '" . $_SESSION['username'] . " Imported', 
																						update_ip 		= '" . $add_ip . "',
																						update_timezone	= '" . $timezone . "'
									WHERE id = '" . ${$insert_db_field_id_detail_pd} . "' ";
							$db->query($conn, $sql6);
						}

						$sql1		= "		SELECT a.id 
											FROM purchase_order_detail a
											INNER JOIN products c ON c.id = a.product_id
											LEFT JOIN inventory_status d ON d.id = a.expected_status
											WHERE 1=1 
											AND a.enabled 			= 1 
											AND a.po_id				= '" . $po_id . "'
											AND c.product_uniqueid	= '" . $data1["product_id"] . "' ";
						if (isset($data1['product_condition'])) {
							$sql1		.= " 	AND a.product_condition	= '" . $data1["product_condition"] . "' ";
						}
						if (isset($data1['product_status'])) {
							$sql1		.= " 	AND a.status_name		= '" . $data1["product_status"] . "' ";
						}
						if (isset($data1['order_price'])) {
							$sql1		.= " 	AND a.order_price		= '" . $data1["order_price"] . "' ";
						}
						// echo "<br><br>".$sql1;
						$result1	= $db->query($conn, $sql1);
						$count1		= $db->counter($result1);
						if ($count1 > 0) {
							$row_dp1 = $db->fetch($result1);
							$po_detail_table_id = $row_dp1[0]['id'];
						}

						if (isset($po_detail_table_id) && $po_detail_table_id > 0 && $update_detail_column != "") {
							$sql6 = "UPDATE " . $selected_db_name . "." . $detail_table . " SET update_date 			= '" . $add_date . "', 
																								update_by 				= '" . $_SESSION['username'] . "', 
																								update_by_user_id 		= '" . $_SESSION['user_id'] . "', 
																								update_ip 				= '" . $add_ip . "', 
																								update_timezone 		= '" . $timezone . "', 
																								update_from_module_id 	= '" . $module_id . "'
																								" . $update_detail_column . " 
																								, enabled = '1'
									WHERE id 	= '" . $po_detail_table_id . "'  ";
							// echo "<br><br>".$sql6;
							$ok = $db->query($conn, $sql6);
							if ($ok) {
								$added++;
							}
						} else {
							$sql6 = "INSERT INTO " . $selected_db_name . "." . $detail_table . "(add_date " . $columns . ", add_by, add_ip, add_timezone)
									 VALUES('" . $add_date . "' " . $column_data . ", '" . $_SESSION['username'] . " Imported', '" . $add_ip . "', '" . $timezone . "')";
							// echo "<br>" . $sql6;
							$ok = $db->query($conn, $sql6);
							if ($ok) {
								$added++;
							}
						}
					}
				}
			}
		}
		if ($added > 0) {
			if ($added == 1) {
				$msg['msg_success'] = $added . " record has been imported successfully.";
			} else {
				$msg['msg_success'] = $added . " records have been imported successfully.";
			}
		} else {
			if (!isset($error['msg'])) {
				$error['msg'] = " No record has been imported.";
			} else {
				$error['msg'] = "No record has been imported.<br><br>" . $error['msg'];
			}
		}
	}
} ?>
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
									<?php echo $title_heading; ?>
								</h6>
							</div>
							<div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=import_file") ?>">
									Import File
								</a>
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=listing") ?>">
									PO List
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy custom_margin_card_table_top">
				<div class="card-content custom_padding_card_content_table_top">
					<h4 class="card-title">Import Excel Data</h4><br>
					<?php
					if (isset($msg['msg_success'])) { ?>
						<div class="card-alert card green lighten-5">
							<div class="card-content green-text">
								<p><?php echo $msg['msg_success']; ?></p>
							</div>
							<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
					<?php }
					if (isset($error['msg'])) { ?>
						<div class="card-alert card red lighten-5">
							<div class="card-content red-text">
								<p><?php echo $error['msg']; ?></p>
							</div>
							<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
					<?php }
					if ((!isset($excel_data) || (isset($excel_data) && $excel_data == "") || isset($error)) &&  !isset($is_Submit2)) { ?>

						<form method="post" autocomplete="off">
							<input type="hidden" name="is_Submit" value="Y" />
							<div class="row">
								<div class="input-field col m12 s12">
									<?php
									$field_name 	= "excel_data";
									$field_label 	= "Paste Here";
									?>
									<i class="material-icons prefix">description</i>
									<textarea type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea excel_data_textarea validate"><?php if (isset(${$field_name})) {
																																											echo ${$field_name};
																																										} ?></textarea>
									<label for="<?= $field_name; ?>">
										<?= $field_label; ?>
										<span class="color-red">* <?php
																	if (isset($error[$field_name])) {
																		echo $error[$field_name];
																	} ?>
										</span>
									</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col m4 s12">
									<?php
									if (access("add_perm") == 1) { ?>
										<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
											<i class="material-icons right">send</i>
										</button>
									<?php } ?>
								</div>
							</div>
						</form>

						<div class="row">
							<div class="col m12 s12">
								<h4 class="card-title">Supported column titles</h4>
								<table class="bordered striped" style="padding: 0px; width: 50%;">
									<tr>
										<td style='padding: 3px 15px  !important; text-align: center; '>Column Name</td>
										<td style='padding: 3px 15px !important; '>Column Heading</td>
										<td style='padding: 3px 15px !important; '>Format</td>
									</tr>
									<?php
									$i = 0;
									$char = 'a';

									foreach ($supported_column_titles as $s_heading) {
										$cell_format = "Text";
										if ($s_heading == 'product_sub_id') {
											$cell_format = "Text (Unique)";
										}
										if ($s_heading == 'total_stock') {
											$cell_format = " Number ";
										}
										echo " <tr>
													<td style='padding: 3px 15px !important; text-align: center; '>" . strtoupper($char) . "</td>
													<td style='padding: 3px 15px !important; '>" . $s_heading . "</td>
													<td style='padding: 3px 15px !important; '>" . $cell_format . "</td>
												</tr>";
										$i++;
										$char++;
									} ?>
								</table>
							</div>
						</div>
						<?php
					} else if (isset($headings) && sizeof($headings) > "0") {
						if ((isset($excel_data) && $excel_data != "" && !isset($error)) || isset($is_Submit2)) { ?>
							<div class="row">
								<div class="col m6 s12">
									Summary
									<table class="bordered striped">
										<tbody>
											<tr>
												<td style="padding: 3px 15px !important">S.No</td>
												<td style="padding: 3px 15px !important">Column Name</td>
												<td style="padding: 3px 15px !important">Column Value</td>
												<td style="padding: 3px 15px !important">Status</td>
											</tr>
											<?php
											foreach ($data as $row_c1) {
												$col_c1_no =  0;
												foreach ($row_c1 as $cell_c1) {
													if (!isset($headings[$col_c1_no])) {
														$headings[$col_c1_no] = "";
													}
													$column_name_c1 = $headings[$col_c1_no];
													$column_name_c1_org = $column_name_c1;

													if ($column_name_c1 == 'vender') {
														$db_name_c1 = 'vender_name';
														$sql_dup	= " SELECT * FROM venders WHERE " . $db_name_c1 . "	= '" . htmlspecialchars($cell_c1) . "' ";
														$result_dup	= $db->query($conn, $sql_dup);
														$count_dup	= $db->counter($result_dup);
														if ($count_dup == 0) {
															${$column_name_c1_org . "_array"}[] = $cell_c1;
														}
													}
													if ($column_name_c1 == 'product_category') {
														$db_name_c1 = 'category_name';
														$sql_dup	= " SELECT * FROM product_categories WHERE " . $db_name_c1 . "	= '" . htmlspecialchars($cell_c1) . "' ";
														$result_dup	= $db->query($conn, $sql_dup);
														$count_dup	= $db->counter($result_dup);
														if ($count_dup == 0) {
															${$column_name_c1_org . "_array"}[] = $cell_c1;
														}
													}
													if ($column_name_c1 == 'po_status') {
														$db_name_c1 = 'status_name';
														$sql_dup	= " SELECT * FROM inventory_status WHERE " . $db_name_c1 . "	= '" . htmlspecialchars($cell_c1) . "' ";
														$result_dup	= $db->query($conn, $sql_dup);
														$count_dup	= $db->counter($result_dup);
														if ($count_dup == 0) {
															${$column_name_c1_org . "_array"}[] = $cell_c1;
														}
													}
													if ($column_name_c1 == 'product_id') {
														$db_name_c1 = 'product_uniqueid';
														$sql_dup	= " SELECT * FROM products WHERE " . $db_name_c1 . "	= '" . htmlspecialchars($cell_c1) . "' ";
														$result_dup	= $db->query($conn, $sql_dup);
														$count_dup	= $db->counter($result_dup);
														if ($count_dup == 0) {
															${$column_name_c1_org . "_array"}[] = $cell_c1;
														}
													}
													$col_c1_no++;
												}
											}
											foreach ($headings as $heading_c1) {
												if (isset(${$heading_c1 . "_array"})) {
													$does_not_exist_unique = array_unique(${$heading_c1 . "_array"});
													sort($does_not_exist_unique); // Sort the array before looping
													$j = 0;
													foreach ($does_not_exist_unique as $data_1) {
														$j++; ?>
														<tr>
															<td style="padding: 3px 15px !important"><b><?php echo $j; ?></b></td>
															<td style="padding: 3px 15px !important"><b><?php echo $heading_c1; ?></b></td>
															<td style="padding: 3px 15px !important"><?php echo $data_1; ?></td>
															<td style="padding: 3px 15px !important" class="color-green">Creates</td>
														</tr>
											<?php }
												}
											} ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row">
								<div class="col m12 s12"><br></div>
							</div>
							<h4 class="card-title">Data to be imported</h4><br>
							<form method="post" autocomplete="off">
								<input type="hidden" name="is_Submit2" value="Y" />
								<input type="hidden" name="excel_data" value="<?php if (isset($excel_data)) {
																					echo $excel_data;
																				} ?>" />
								<div class="row">
									<div class="col m12 s12">
										<table class="bordered striped">
											<thead>
												<tr>
													<?php
													$m = 0;
													foreach ($headings as $heading) {
														$m++; ?>
														<th>
															<?php
															$field_name = "import_colums[]";
															?>
															<div class="width_heading_table_custom_col">
																<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="  validate">
																	<option value="">Unassigned</option>
																	<?php
																	foreach ($supported_column_titles as $heading_main) {  ?>
																		<option value="<?php echo $heading_main; ?>" <?php if (isset($heading) && $heading == $heading_main) { ?> selected="selected" <?php } ?>><?php echo $heading_main; ?></option>
																	<?php } ?>
																</select>
															</div>
														</th>
													<?php } ?>
													<th></th>
												</tr>
												<tr>
													<?php
													foreach ($headings as $heading) {
														$row_color 	= "";
														if (!in_array($heading, $supported_column_titles)) {
															$row_color 	= "color-red";
														}
														echo "<th class='" . $row_color . "'> " . htmlspecialchars($heading) . "</th>";
													} ?>
													<th>Import Status</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$row_no = 0;
												foreach ($data as $row) {
													echo "<tr>";
													$row_error_status = "";
													$col_no 	= $is_error = 0;
													$is_insert	= "Yes";
													$row_color	= "color-green";
													$sql1		= "		SELECT a.id 
																		FROM purchase_order_detail a
																		INNER JOIN purchase_orders b ON b.id = a.po_id
																		INNER JOIN products c ON c.id = a.product_id
																		LEFT JOIN inventory_status d ON d.id = a.expected_status
																		WHERE 1=1 
																		AND a.enabled = 1  ";
													foreach ($row as $cell) {

														$db_column 			= $headings[$col_no];
														$db_column_excel	= $db_column;


														if (!in_array($db_column_excel, $supported_column_titles)) {
															$row_color 	= "color-red";
															$is_error 	= 1;
															$is_insert 	= "No";
														}
														if ($db_column_excel == 'po_no') {
															$sql1 .= " AND b.po_no = '" . $cell . "' ";
														}
														if ($db_column_excel == 'product_condition') {
															$sql1 .= " AND a.product_condition	= '" . $cell . "' ";
														}
														if ($db_column_excel == 'order_price') {
															$sql1 .= " AND a.order_price	= '" . $cell . "' ";
														}
														if ($db_column_excel == 'product_id') {
															$sql1 .= " AND c.product_uniqueid	= '" . $cell . "' ";
														}
														if ($db_column_excel == 'product_status') {
															$sql1 .= " AND d.status_name	= '" . $cell . "' ";
														}

														$row_color = "color-green";  ?>
														<input type="hidden" name="all_data[<?= $row_no; ?>][<?= $db_column_excel; ?>]" value="<?= $cell; ?>">
													<?php
														echo "<td class='" . $row_color . "'>" . htmlspecialchars($cell) . "</td>";
														$col_no++;
													} ?>
													<input type="hidden" name="all_data[<?= $row_no; ?>][is_insert]" value="<?= $is_insert; ?>">
												<?php
													// echo "<br><br>".$sql1;
													$result_dup	= $db->query($conn, $sql1);
													$count_dup	= $db->counter($result_dup);
													if ($count_dup > 0) {
														$row_color 	= "color-red";
														$is_error 	= 1;
														if ($row_error_status != "") {
															$row_error_status .= ", Duplicate";
														} else {
															$row_error_status = " Duplicate";
														}
													} else {
														$row_color = "color-green";
													}
													if ($is_error == 1) {
														$row_color = "color-red";
													} else {
														$row_error_status = "Creates";
													}
													echo "<td class='" . $row_color . "'>" . $row_error_status . "</td>";
													echo "</tr>";
													$row_no++;
												} ?>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col m2 s12">&nbsp;</div>
								</div>
								<div class="row">
									<div class="col m2 s12">&nbsp;</div>
									<div class="col m2 s12">
										<button class="btn cyan waves-effect waves-light right" type="submit" name="action">Start Import
											<i class="material-icons right">send</i>
										</button>
									</div>
									<div class="col m2 s12">
										<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page) ?>" class="waves-effect waves-light btn modal-trigger mb-2 mr-1" type="submit" name="action">Copy New
											<i class="material-icons left">send</i>
										</a>
									</div>
									<div class="col m4 s12">&nbsp;</div>
								</div>
							</form>
						<?php
						} else { ?>
							<div class="row">
								<div class="col m2 s12">&nbsp;</div>
								<div class="col m2 s12">
									<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page) ?>" class="waves-effect waves-light btn modal-trigger mb-2 mr-1" type="submit" name="action">Copy New
										<i class="material-icons left">send</i>
									</a>
								</div>
								<div class="col m4 s12">&nbsp;</div>
							</div>
						<?php }
					} else { ?>
						<div class="row">
							<div class="col m2 s12">&nbsp;</div>
							<div class="col m2 s12">
								<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page) ?>" class="waves-effect waves-light btn modal-trigger mb-2 mr-1" type="submit" name="action">Copy New
									<i class="material-icons left">send</i>
								</a>
							</div>
							<div class="col m4 s12">&nbsp;</div>
						</div>
					<?php } ?>
				</div>
				<?php
				//include('sub_files/right_sidebar.php');
				?>
			</div>
		</div>
	</div>
</div><br><br><br><br>
<!-- END: Page Main-->