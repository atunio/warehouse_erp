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
if (!isset($_SESSION['session_id_temp_2_' . $module_id])) {
	$_SESSION['session_id_temp_2_' . $module_id] = session_id() . "2" . $module_id;
}
$title_heading 				= "Import Vendor Data File ";
$button_val 				= "Preview";
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

$supported_column_titles	= array("product_id",  "serial_no", "overall_grade", "defects_or_notes", "status", "price");
$duplication_columns 		= array("serial_no");
$required_columns 			= array("product_id", "serial_no");

$error = []; // Initialize error array
// Function to read CSV
function readCSV($file_tmp)
{
	return file_get_contents($file_tmp);
}
// Function to read Excel
function readExcel($file_tmp)
{
	$spreadsheet = IOFactory::load($file_tmp);
	$worksheet = $spreadsheet->getActiveSheet();
	return implode(PHP_EOL, array_map(fn($row) => implode(',', $row), $worksheet->toArray()));
}
// /*
if (isset($_POST['is_Submit']) && $_POST['is_Submit'] == 'Y') {
	if (isset($_FILES["upload_excel_file"]) && $_FILES["upload_excel_file"]["error"] == 0) {
		$file_name = $_FILES["upload_excel_file"]["name"];
		$file_tmp = $_FILES["upload_excel_file"]["tmp_name"];
		$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

		// Allowed file types
		$allowed_ext = ["csv", "xls", "xlsx"];

		if (!in_array($file_ext, $allowed_ext)) {
			$error['msg'][] = "Invalid file type! Only CSV and Excel files are allowed.";
		}

		if (empty($error)) {
			// Read file data
			if ($file_ext == "csv") {
				$excel_data = readCSV($file_tmp);
			} else {
				$excel_data = readExcel($file_tmp);
			}

			// Process data
			$rows = explode(PHP_EOL, trim($excel_data));
			$data = [];
			foreach ($rows as $row) {
				$data[] = preg_split('/[\t,]+/', trim($row)); // Split by tab or comma
			}

			// Extract headings
			$headings = array_shift($data);

			// Validation: Check for missing headings
			foreach ($data as $row_v1) {
				if (count($row_v1) != count($headings)) {
					$error['msg'] = "One or more column headings are missing or extra columns exist.";
					break;
				}
			}
			// Validation: Ensure all cells have values
			foreach ($data as $row11) {
				foreach ($row11 as $cell_array) {
					if (empty($cell_array) || trim($cell_array) == '') {
						$error['msg'] = "All cells must contain values. Use '-' for empty cells.";
						break 2; // Break out of both loops
					}
				}
			}

			$sql_del = "DELETE FROM export_temp_data 
						WHERE (session_id = '" . $_SESSION['session_id_temp_2_' . $module_id] . "' AND module_id = '" . $module_id . "') 
						OR DATE_FORMAT(add_date, '%Y%m%d') != '" . date('Ymd') . "' ";
			$db->query($conn, $sql_del);

			if (empty($error)) {
				$upload_excel_file = $_FILES["upload_excel_file"]["tmp_name"];
				foreach ($data as $row11) {
					$field_names 	= "";
					$field_values 	= "";
					$colno 			= 1;
					foreach ($row11 as $cell_val) {
						$field_names .= "column" . $colno . ", ";
						$field_values .= "'" . trim($cell_val) . "', ";
						$colno++;
					}
					$total_columns_in_data = $colno - 1;

					$field_names .= "session_id, ";
					$field_values .= "'" . $_SESSION['session_id_temp_2_' . $module_id] . "', ";

					$field_names .= "module_id, ";
					$field_values .= "'" . $module_id . "', ";

					$field_names .= "add_date";
					$field_values .= "'" . $add_date . "'";

					$sql = "INSERT INTO export_temp_data (" . $field_names . ") VALUES(" . $field_values . ")"; //echo "<br>".$sql;
					$db->query($conn, $sql);
				}
			}
		}
	} else {
		$error['msg'][] = "File upload error!";
	}
}

$master_table	= "vender_po_data";
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

	$modified_array = excel_export_temp_data_with_columns($db, $conn, $_SESSION['session_id_temp_2_' . $module_id], $module_id,  $total_columns_in_data, $import_colums);
	// Initialize the new modified array
	$all_data = $modified_array;

	if (isset($all_data)) {
		/*
		$sql_ee1 = " SELECT a.* FROM " . $master_table . " a  WHERE a.duplication_check_token = '" . $duplication_check_token . "' ";
		// echo $sql_ee1;
		$result_ee1 	= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 > 0) {
			$error['msg'] = "Already imported.";
		}
		*/
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

					$sql_del = " DELETE FROM vender_po_data WHERE po_id =  '" . $id . "' ";
					$db->query($conn, $sql_del);

					$sql_dup1 = "UPDATE purchase_order_detail SET enabled = 0 
								 WHERE po_id	= '" . $id . "' ";
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
									// echo "<br><br><br>" . $sql_po_d;
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
											if ($key == 'product_id' || $key == 'overall_grade'  || $key == 'status'  || $key == 'price') {

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
													if ($data != '' && $data != NULL && $data != '-' && $data != 'blank') {
														$sql1		= "SELECT * FROM inventory_status WHERE status_name = '" . $data . "' ";
														$result1	= $db->query($conn, $sql1);
														$count1		= $db->counter($result1);
														if ($count1 > 0) {
															$row1 = $db->fetch($result1);

															$po_detail_column	.= ", expected_status";
															$po_detail_data 	.= ", '" . $row1[0]['id'] . "'";
															$update_po_detail	.= ", expected_status = '" . $row1[0]['id'] . "'";
														}
													}
												} else if ($key == 'overall_grade') {
													$insert_db_field_id = "product_condition";
													${$insert_db_field_id} = $data;
													if (${$insert_db_field_id} == 'A' || ${$insert_db_field_id} == 'B' || ${$insert_db_field_id} == 'C' || ${$insert_db_field_id} == 'D') {
														$po_detail_column	.= ", " . $insert_db_field_id;
														$po_detail_data		.= ", '" . ${$insert_db_field_id} . "'";
														$update_po_detail	.= ", " . $insert_db_field_id . " = '" . ${$insert_db_field_id} . "'";
													}
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
												WHERE id 	= '" . $vender_data_id . "'
												AND po_id 			= '" . $id . "' ";
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
		}
	}
}
//*/
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
									<?php echo $title_heading; ?>
								</h6>
							</div>
							<div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module_id=" . $module_id . "&page=importvender_data&id=" . $id) ?>">
									Import Data
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
				<div class="card-content custom_padding_card_content_table_top">
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
					if (isset($error['msg'])) {  ?>
						<div class="card-alert card red lighten-5">
							<div class="card-content red-text">
								<p><?php echo $error['msg']; ?></p>
							</div>
							<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
					<?php }

					if (!isset($upload_excel_file) || (isset($upload_excel_file) && $upload_excel_file == "")) { ?>

						<form method="post" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="is_Submit" value="Y" />
							<div class="row">
								<div class="input-field col m3 s12">
									<?php
									$field_name 	= "upload_excel_file";
									$field_label 	= "File";
									?>
									<div class="file-field input-field">
										<div class="btn">
											<span>Browse</span>
											<input type="hidden" name="old_upload_excel_file" value="">
											<input type="file" name="<?= $field_name; ?>">
										</div>
										<div class="file-path-wrapper">
											<input class="file-path validate" type="text" placeholder="upload excel or csv file here ...">
										</div>
									</div>
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
						if ((isset($upload_excel_file) && $upload_excel_file != "" && !isset($error) || isset($error)) && sizeof($error) == 0) {
							$data = excel_export_temp_data($db, $conn, $_SESSION['session_id_temp_2_' . $module_id], $module_id, $total_columns_in_data); ?>
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

													if ($column_name_c1 == 'po_no') {
														$db_name_c1 = 'po_no';
														$sql_dup	= " SELECT * FROM purchase_orders WHERE " . $db_name_c1 . "	= '" . htmlspecialchars($cell_c1) . "' ";
														$result_dup	= $db->query($conn, $sql_dup);
														$count_dup	= $db->counter($result_dup);
														if ($count_dup == 0) {
															${$column_name_c1_org . "_array"}[] = $cell_c1;
														}
													}
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
								<input type="hidden" name="upload_excel_file" value="<?php echo $upload_excel_file; ?>" />
								<input type="hidden" name="total_columns_in_data" value="<?php echo $total_columns_in_data; ?>" />
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
															<?php
															} else {
																$row_color = "color-green"; ?>
															<?php
															}
														} else {
															$row_color = "color-green";  ?>
													<?php
														}
														echo "<td class='" . $row_color . "'>" . htmlspecialchars($cell) . "</td>";
														$col_no++;
													} ?>
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
										<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&id=" . $id) ?>" class="waves-effect waves-light btn modal-trigger mb-2 mr-1" type="submit" name="action">Browse New
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
									<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&id=" . $id) ?>" class="waves-effect waves-light btn modal-trigger mb-2 mr-1" type="submit" name="action">Browse New
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
								<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&id=" . $id) ?>" class="waves-effect waves-light btn modal-trigger mb-2 mr-1" type="submit" name="action">Browse New
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