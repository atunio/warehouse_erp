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

$title_heading 			= "Import Vendor Data";
$button_val 			= "Preview";
$sql_ee						= " SELECT a.*, b.status_name
								FROM purchase_orders a
								LEFT JOIN inventory_status b ON b.id = a.order_status
								WHERE a.id = '" . $id . "'"; // echo $sql_ee;
$result_ee					= $db->query($conn, $sql_ee);
$row_ee						= $db->fetch($result_ee);
$disp_po_no					=  $row_ee[0]['po_no'];
$disp_vender_invoice_no		= $row_ee[0]['vender_invoice_no'];
$disp_stage_status			= $row_ee[0]['stage_status'];

extract($_POST);

foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
$supported_column_titles	= array("invoice_no", "product_id",  "serial_no", "overall_grade", "defects_or_notes", "status", "price");
$duplication_columns 		= array("serial_no");
$required_columns 			= array("invoice_no", "product_id", "serial_no");

if (isset($is_Submit) && $is_Submit == 'Y') {
	if (isset($excel_data) && $excel_data == "") {
		$error['excel_data']	= "Required";
		$category_name_valid 	= "invalid";
	}
	if (empty($error)) {
		if (po_permisions("Vendor Data") == 0) {
			$error2['msg'] = "You do not have add permissions.";
		} else {
			//$excel_data = str_replace("'", "", str_replace(",", "", $excel_data));
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
}
$added = 0;
$master_table = "vender_po_data";
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

	if (isset($all_data)) {
		foreach ($all_data as $value1) {
			$j = 0;
			foreach ($value1 as $key => $data) {
				$k = 0;
				foreach ($import_colums_uniq as $data2) {
					if ($k == $j) {
						$modified_array[$i][$data2] = trim($data);
					}
					$k++;
				}
				$j++;
			}
			$modified_array[$i]["is_insert"] = $data;
			$i++; // increment the index
		}

		$all_data = $modified_array;
		$sql_ee1 = " SELECT a.* FROM " . $master_table . " a  WHERE a.duplication_check_token = '" . $duplication_check_token . "' ";
		// echo $sql_ee1;
		// $result_ee1 	= $db->query($conn, $sql_ee1);
		// $counter_ee1	= $db->counter($result_ee1);
		// if ($counter_ee1 > 0) {
		// 	$error['msg'] = "Already imported.";
		// }
		if (empty($error)) {
			if (po_permisions("Vendor Data") == 0) {
				$error2['msg'] = "You do not have add permissions.";
			} else {
				$ids_already = array();
				if (isset($all_data) && sizeof($all_data) > 0) {
					foreach ($duplication_columns  as $dup_data) {
						$duplicate_colum_values = array_unique(array_column($all_data, $dup_data));
						foreach ($duplicate_colum_values  as $duplicate_colum_values1) {
							$db_column = $dup_data;
							// if ($dup_data == 'product_id') {
							// 	$db_column = "product_uniqueid";
							// }
							if ($duplicate_colum_values1  != "") {
								$sql1		= "	SELECT * FROM " . $master_table . " 
												WHERE " . $db_column . " = '" . $duplicate_colum_values1 . "' 
												AND po_id = '" . $id . "' ";
								$result1	= $db->query($conn, $sql1);
								$count1		= $db->counter($result1);
								if ($count1 > 0) {
									$row_dp1 = $db->fetch($result1);
									foreach ($row_dp1 as $data_dp11) {
										$ids_already[] = $duplicate_colum_values1;
									}
									// if (!isset($error['msg'])) {
									// 	$error['msg'] = "This " . $dup_data . ": <span class='color-blue'>" . $duplicate_colum_values1 . "</span> is already exist.";
									// } else {
									// 	$error['msg'] .= "<br>This " . $dup_data . ": <span class='color-blue'>" . $duplicate_colum_values1 . "</span> is already exist.";
									// }
								}
							}
						}
					}

					$sql_del = "DELETE FROM vender_po_data WHERE po_id =  '" . $id . "' ";
					$db->query($conn, $sql_del);

					$sql_dup1 = "	UPDATE purchase_order_detail a
									LEFT JOIN purchase_order_detail_receive b ON b.po_detail_id = a.id
										SET a.enabled = 0 
									WHERE a.po_id	= '" . $id . "' 
									AND IFNULL(b.id, 0) = 0 ";
					$db->query($conn, $sql_dup1);


					$product_id_not_available = "";
					foreach ($all_data  as $data1) {
						$update_master = $columns = $column_data = $update_column = $po_detail_data =  $po_detail_column = $update_po_detail = "";
						if (
							isset($data1['product_id']) && $data1['product_id'] != '' && $data1['product_id'] != NULL && $data1['product_id'] != 'blank'
							&& isset($data1['serial_no']) && $data1['serial_no'] != '' && $data1['serial_no'] != NULL && $data1['serial_no'] != 'blank'
						) {
							$table1 	= "products";
							$sql1		= "SELECT * FROM " . $table1 . " WHERE product_uniqueid = '" . $data1['product_id'] . "' ";
							$result1	= $db->query($conn, $sql1);
							$count1		= $db->counter($result1);
							if ($count1 > 0) {

								$row1			= $db->fetch($result1);
								$product_id2 	= $row1[0]['id'];

								$do_not_import_row = 0;
								if (
									$data1['overall_grade'] == ''
									|| $data1['overall_grade'] == NULL
									|| $data1['overall_grade'] == '-'
									|| $data1['overall_grade'] == 'blank'
									|| $data1['overall_grade'] == 'N/A'
									|| $data1['overall_grade'] == 'NA'
									|| $data1['overall_grade'] == 'A'
									|| $data1['overall_grade'] == 'B'
									|| $data1['overall_grade'] == 'C'
									|| $data1['overall_grade'] == 'D'
								) {;
								} else {
									$do_not_import_row = 1;
									if (!isset($error['msg'])) {
										$error['msg'] = "The overall_grade of serial# <span class='color-blue'> " . $data1['serial_no'] . "</span> is invalid.";
									} else {
										$error['msg'] .= "<br>The overall_grade of serial# <span class='color-blue'> " . $data1['serial_no'] . "</span> is invalid.";
									}
								}
								$count_defects_or_note = 0;
								$field_name = "defects_or_notes";
								if ($data1[$field_name] != '' && $data1[$field_name] != NULL  && $data1[$field_name] != '-'  && $data1[$field_name] != 'blank'  && $data1[$field_name] != 'N/A'  && $data1[$field_name] != 'NA') {
									$sql_defects_or_note	= "SELECT * FROM defect_codes WHERE defect_code = '" . $data1[$field_name] . "' ";
									$result_defects_or_note	= $db->query($conn, $sql_defects_or_note);
									$count_defects_or_note	= $db->counter($result_defects_or_note);
									if ($count_defects_or_note == 0) {
										$do_not_import_row = 1;
										if (!isset($error['msg'])) {
											$error['msg'] = "The defective_code of serial# <span class='color-blue'> " . $data1['serial_no'] . "</span> does not exist in the system.";
										} else {
											$error['msg'] .= "<br>The defective_code of serial# <span class='color-blue'> " . $data1['serial_no'] . "</span> does not exist in the system.";
										}
									}
								}
								$count_status = 0;
								$field_name = "status";
								if ($data1[$field_name] != '' && $data1[$field_name] != NULL  && $data1[$field_name] != '-'  && $data1[$field_name] != 'blank'  && $data1[$field_name] != 'N/A'  && $data1[$field_name] != 'NA') {
									$sql_status		= "SELECT * FROM inventory_status WHERE status_name = '" . $data1[$field_name] . "' ";
									$result_status	= $db->query($conn, $sql_status);
									$count_status	= $db->counter($result_status);
									if ($count_status == 0) {
										$do_not_import_row = 1;
										if (!isset($error['msg'])) {
											$error['msg'] = "The status of serial# <span class='color-blue'> " . $data1['serial_no'] . "</span> does not exist in the system.";
										} else {
											$error['msg'] .= "<br>The status of serial# <span class='color-blue'> " . $data1['serial_no'] . "</span> does not exist in the system.";
										}
									}
								}
								if ($do_not_import_row == 0) {

									$overall_grade  = isset($data1['overall_grade']) ? $data1['overall_grade'] : '';
									$status 		= isset($data1['status'])        ? $data1['status'] 	   : '';
									$price 			= isset($data1['price'])		 ? $data1['price']		   : '';

									$product_po_detail_id = $po_order_qty = 0;
									$sql_po_d			= " SELECT a.* 
															FROM purchase_order_detail a
															LEFT JOIN inventory_status b ON b.id = a.expected_status 
															WHERE 1=1
															AND a.enabled = 1
															AND a.product_id		= '" . $product_id2 . "'  ";
									if ($overall_grade == '-' || $overall_grade == 'NA' || $overall_grade == 'N/A' || $overall_grade == 'blank') {
										$sql_po_d			.= " AND (a.product_condition = '' OR a.product_condition IS NULL)";
									} else {
										$sql_po_d			.= " AND a.product_condition = '" . $overall_grade . "'";
									}
									if ($status == '-' || $status == 'NA' || $status == 'N/A' || $status == 'blank') {
										$sql_po_d			.= " AND (a.expected_status = '' OR a.expected_status IS NULL)";
									} else {
										$sql_po_d			.= " AND b.status_name 		= '" . $status . "'";
									}
									if ($price == '-' || $price == 'NA' || $price == 'N/A' || $price == 'blank') {
										$sql_po_d			.= " AND (a.order_price = '' OR a.order_price IS NULL)";
									} else {
										$sql_po_d			.= " AND a.order_price 		= '" . $price . "'";
									}
									$sql_po_d			.= " AND a.po_id 			=  '" . $id . "' ";
									$result_po_d		= $db->query($conn, $sql_po_d);
									$count_po_d			= $db->counter($result_po_d);
									if ($count_po_d > 0) {
										$row_po_d				= $db->fetch($result_po_d);
										$product_po_detail_id 	= $row_po_d[0]['id'];
										$po_order_qty 			= $row_po_d[0]['order_qty'];
									}

									$vender_data_id = 0;
									$sql1			= " SELECT * FROM " . $master_table . " 
														WHERE  serial_no = '" . $data1['serial_no'] . "'
														AND po_id =  '" . $id . "' ";
									$result2		= $db->query($conn, $sql1);
									$count2			= $db->counter($result2);
									if ($count2 > 0) {
										$row2			= $db->fetch($result2);
										$vender_data_id = $row2[0]['id'];
									}
									foreach ($data1 as $key => $data) {
										if ($key != "" && $key != 'is_insert') {
											if ($data == '-' || $data == 'NA' || $data == 'N/A' || $data == 'blank') {
												$data = "";
											}
											if ($key == 'product_id') {
												$key2 = "product_uniqueid";
											} else {
												$key2 = $key;
											}
											$columns 		.= ", " . $key2;
											$column_data 	.= ", '" . $data . "'";

											$update_column	.= ", " . $key2 . " = '" . $data . "'";
											if ($key == 'product_id' || $key == 'overall_grade'  || $key == 'status'  || $key == 'price'  || $key == 'invoice_no') {

												if ($key == 'price') {
													$key = "order_price";

													$po_detail_column	.= ", " . $key;
													$po_detail_data		.= ", '" . $data . "'";
													$update_po_detail	.= ", " . $key . " = '" . $data . "'";
												} else if ($key == 'product_id') {
													$data = $product_id2;
													$po_detail_column	.= ", " . $key;
													$po_detail_data		.= ", '" . $data . "'";
													$update_po_detail	.= ", " . $key . " = '" . $data . "'";
												} else if ($key == 'status') {
													if ($count_status > 0) {
														$result_status 		 = $db->fetch($result_status);
														$po_detail_column	.= ", expected_status";
														$po_detail_data 	.= ", '" . $result_status[0]['id'] . "'";
														$update_po_detail	.= ", expected_status = '" . $result_status[0]['id'] . "'";
													}
												} else if ($key == 'overall_grade') {
													$insert_db_field_id = "product_condition";
													${$insert_db_field_id} = $data;
													if (${$insert_db_field_id} == 'A' || ${$insert_db_field_id} == 'B' || ${$insert_db_field_id} == 'C' || ${$insert_db_field_id} == 'D') {
														$po_detail_column	.= ", " . $insert_db_field_id;
														$po_detail_data		.= ", '" . ${$insert_db_field_id} . "'";
														$update_po_detail	.= ", " . $insert_db_field_id . " = '" . ${$insert_db_field_id} . "'";
													}
												} else if ($key == 'invoice_no') {
													$po_detail_column	.= ", " . $key;
													$po_detail_data		.= ", '" . $data . "'";
													$update_po_detail	.= ", " . $key . " = '" . $data . "'";
												}
											}
										}
									}
									if (isset($ids_already) && isset($vender_data_id) && $vender_data_id > 0 && in_array($data1['serial_no'], $ids_already)) {
										$sql6 = "UPDATE " . $selected_db_name . "." . $master_table . " SET update_date 			= '" . $add_date . "', 
																											update_by 				= '" . $_SESSION['username'] . "', 
																											update_by_user_id 		= '" . $_SESSION['user_id'] . "', 
																											update_ip 				= '" . $add_ip . "', 
																											update_timezone 		= '" . $timezone . "', 
																											update_from_module_id 	= '" . $module_id . "'
																											" . $update_column . " 
																											, enabled = '1' 
												WHERE id	= '" . $vender_data_id . "'
												AND po_id 	= '" . $id . "' ";
										// echo "<br><br>" . $sql6;
										$ok = $db->query($conn, $sql6);
										if ($ok) {
											$added++;
										}
									} else {
										$sql6 = "INSERT INTO " . $selected_db_name . "." . $master_table . "(subscriber_users_id, po_id " . $columns . ", duplication_check_token, add_date, add_by, add_ip)
												VALUES('" . $subscriber_users_id . "', '" . $id . "' " . $column_data . ", '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
										// echo "<br><br>" . $sql6;
										$ok = $db->query($conn, $sql6);
										if ($ok) {
											$added++;
										}
									}
									if ($count_po_d > 0) {
										$sql_sr1 	= " SELECT a.id
														FROM purchase_order_detail_receive a
														WHERE a.po_id	= '" . $id . "'
														AND  a.serial_no_barcode	= '" . $data1['serial_no'] . "'
														AND a.enabled = 1 "; //echo "<br><br><br><br>" . $sql_sr1;
										$result_sr1	= $db->query($conn, $sql_sr1);
										$count_sr1 	= $db->counter($result_sr1);
										if ($count_sr1 == 0) {
											$sql6 = "UPDATE " . $selected_db_name . ".purchase_order_detail SET update_date 			= '" . $add_date . "', 
																												update_by 				= '" . $_SESSION['username'] . "', 
																												update_by_user_id 		= '" . $_SESSION['user_id'] . "', 
																												update_ip 				= '" . $add_ip . "', 
																												update_timezone 		= '" . $timezone . "', 
																												update_from_module_id 	= '" . $module_id . "',
																												order_qty 				= '" . ($po_order_qty + 1) . "'
																												" . $update_po_detail . " 
																												, enabled = '1' 
													WHERE id 	= '" . $product_po_detail_id . "' "; // echo "<br><br>" . $sql6;
											$db->query($conn, $sql6);
										}
									} else {
										$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_order_detail(po_id " . $po_detail_column . ", order_qty, add_date, add_by, add_ip)
												 VALUES('" . $id . "' " . $po_detail_data . ", '1', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')"; // echo "<br><br>" . $sql6;
										$db->query($conn, $sql6);
									}
								}
							} else {
								$product_id_not_available .= "<br>" . $data1['product_id'];
							}
						}
					}
					if (isset($product_id_not_available) && $product_id_not_available != "") {
						if (!isset($error['msg'])) {
							$error['msg'] = "<br>Following product id/ids is/are not in system <br><span class='color-blue'>" . $product_id_not_available . "</span>";
						} else {
							$error['msg'] .= "<br><br>Following product id/ids is/are not in system <span class='color-blue'>" . $product_id_not_available . "</span>";
						}
					}

					$sql_msg 	= " SELECT DISTINCT c.product_uniqueid, a.product_condition, a.order_price, d.status_name
									FROM purchase_order_detail a
									INNER JOIN purchase_order_detail_receive b ON b.po_detail_id = a.id
									INNER JOIN products c ON c.id = a.product_id
									LEFT JOIN inventory_status d ON d.id = a.expected_status
									WHERE a.po_id	= '" . $id . "'
									AND IFNULL(b.id, 0) > 0
									AND a.enabled = 1 AND b.enabled = 1  ";
					//echo "<br><br><br><br>" . $sql_msg;
					$result_msg	= $db->query($conn, $sql_msg);
					$count_msg 	= $db->counter($result_msg);
					if ($count_msg > 0) {
						if (isset($error['msg'])) {
							$error['msg'] .= "<br>The product/s with following detail already has/have been recieved, Please remove receiving before import in order to remove those from PO:<br>";
						} else {
							$error['msg'] = "<br>The product/s with following detail already has/have been recieved, Please remove receiving before import in order to remove those from PO:<br>";
						}
						$row_msg = $db->fetch($result_msg);
						foreach ($row_msg as $data_msg) {
							$product_uniqueid_msg	= $data_msg['product_uniqueid'];
							$status_name_msg		= $data_msg['status_name'];
							$product_condition_msg	= $data_msg['product_condition'];
							$order_price_msg		= $data_msg['order_price'];
							$error['msg'] .= "<br>Product ID: " . $product_uniqueid_msg . ", Price: " . $order_price_msg . ", Condition: " . $product_condition_msg . ", Status: " . $status_name_msg;
						}
					}
				}
				if ($added > 0) {
					if ($added == 1) {
						$msg['msg_success'] = $added . " record has been processed for imported successfully.";
					} else {
						$msg['msg_success'] = $added . " records have been processed for imported successfully.";
					}
				} else {
					if (!isset($error['msg'])) {
						$error['msg'] = " No record has been imported.";
					} else {
						$error['msg'] = "No record has been imported.<br><br>" . $error['msg'];
					}
				}
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
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=importvender_data_file&id=" . $id) ?>">
									Import File
								</a>
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=listing") ?>">
									PO List
								</a>
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab4") ?>">
									PO Profile
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy custom_margin_card_table_top">
				<div class="card-panel custom_padding_card_content_table_top">
					<?php
					if (isset($id) && isset($disp_po_no)) {  ?>
						<div class="row">
							<div class="input-field col m4 s12">
								<h6 class="media-heading"><span class=""><?php echo "<b>PO#:</b>" . $disp_po_no; ?></span></h6>
							</div>
							<div class="input-field col m4 s12">
								<h6 class="media-heading"><span class=""><?php echo "<b>Vendor Invoice#: </b>" . $disp_vender_invoice_no; ?></span></h6>
							</div>
							<div class="input-field col m4 s12">
								<span class="chip green lighten-5">
									<span class="green-text">
										<?php echo $disp_stage_status; ?>
									</span>
								</span>
							</div>
						</div>
					<?php } ?>
					<h4 class="card-title">Import Excel Data</h4><br>
					<?php
					if ($disp_stage_status == 'Draft') {
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
											if ($s_heading == 'price') {
												$cell_format = "Number";
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
								<h4 class="card-title">Data to be imported</h4><br>
								<form method="post" autocomplete="off">
									<input type="hidden" name="is_Submit2" value="Y" />
									<input type="hidden" name="excel_data" value="<?php if (isset($excel_data)) {
																						echo $excel_data;
																					} ?>" />
									<input type="hidden" name="duplication_check_token" value="<?php echo (time() . session_id()); ?>">
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
																<div class="select2div">
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
														foreach ($row as $cell) {

															$db_column 			= $headings[$col_no];
															$db_column_excel	= $db_column;


															if (!in_array($db_column_excel, $supported_column_titles)) {
																$row_color 	= "color-red";
																$is_error 	= 1;
																$is_insert 	= "No";
															}
															if (in_array($db_column_excel, $duplication_columns)) {

																if ($db_column == 'product_id') {
																	$db_column = "product_uniqueid";
																}

																$sql_dup 	= " SELECT * FROM " . $master_table . " WHERE " . $db_column . "	= '" . htmlspecialchars($cell) . "' AND po_id = '" . $id . "' ";
																// echo "<br>" . $sql_dup;
																$result_dup	= $db->query($conn, $sql_dup);
																$count_dup	= $db->counter($result_dup);

																if ($count_dup > 0) {
																	$row_color 	= "color-red";
																	$is_error 	= 1;
																	$is_insert 	= "No";
																	if ($row_error_status != "") {
																		$row_error_status .= ", Duplicate " . $db_column_excel;
																	} else {
																		$row_error_status = "Duplicate " . $db_column_excel;
																	} ?>
																	<input type="hidden" name="all_data[<?= $row_no; ?>][<?= $db_column_excel; ?>]" value="<?= $cell; ?>">
																<?php
																} else {
																	$row_color = "color-green"; ?>
																	<input type="hidden" name="all_data[<?= $row_no; ?>][<?= $db_column_excel; ?>]" value="<?= $cell; ?>">
																<?php
																}
															} else {
																$row_color = "color-green";  ?>
																<input type="hidden" name="all_data[<?= $row_no; ?>][<?= $db_column_excel; ?>]" value="<?= $cell; ?>">
														<?php
															}
															echo "<td class='" . $row_color . "'>" . htmlspecialchars($cell) . "</td>";
															$col_no++;
														} ?>
														<input type="hidden" name="all_data[<?= $row_no; ?>][is_insert]" value="<?= $is_insert; ?>">
													<?php
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
											<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&id=" . $id) ?>" class="waves-effect waves-light btn modal-trigger mb-2 mr-1" type="submit" name="action">Copy New
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
										<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&id=" . $id) ?>" class="waves-effect waves-light btn modal-trigger mb-2 mr-1" type="submit" name="action">Copy New
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
									<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&id=" . $id) ?>" class="waves-effect waves-light btn modal-trigger mb-2 mr-1" type="submit" name="action">Copy New
										<i class="material-icons left">send</i>
									</a>
								</div>
								<div class="col m4 s12">&nbsp;</div>
							</div>
						<?php }
					} else { ?>
						<div class="card-alert card red lighten-5">
							<div class="card-content red-text">
								<p>Data only can be import if PO is in Draft</p>
							</div>
						</div>
					<?php
					} ?>
				</div>
				<?php
				//include('sub_files/right_sidebar.php');
				?>
			</div>
		</div>
	</div>
</div><br><br><br><br>
<!-- END: Page Main-->