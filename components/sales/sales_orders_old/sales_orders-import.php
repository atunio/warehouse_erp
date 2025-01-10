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

$supported_column_titles	= array("so_no", "order_date", "customer", "customer_po_no",  "internal_note",  "estimated_ship_date", "order_status", "product_id", "product_desc", "product_category", "order_qty", "order_price", "product_condition", "warranty_period_in_days");
$master_columns 			= array("so_no", "order_date", "customer", "customer_po_no", "internal_note", "estimated_ship_date", "order_status");  // db fields
$duplication_columns 		= array("so_no");
$required_columns 			= array("so_no", "customer", "product_id", "product_category", "order_qty", "order_price");

if (isset($is_Submit) && $is_Submit == 'Y') {
	if (isset($excel_data) && $excel_data == "") {
		$error['excel_data']	= "Required";
		$category_name_valid 	= "invalid";
	}
	if (empty($error)) {
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

$master_table	= "sales_orders";
$detail_table	= "sales_order_detail";
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

	$all_data = $modified_array;

	if (empty($error)) {
		$duplicate_data_array = array();
		if (isset($all_data) && sizeof($all_data) > 0) {
			foreach ($duplication_columns  as $dup_data) {
				$duplicate_colum_values = array_unique(array_column($all_data, $dup_data));
				foreach ($duplicate_colum_values  as $duplicate_colum_values1) {

					$db_column = $dup_data;

					if ($dup_data == 'product_id') {
						$db_column = "product_uniqueid";
					} else if ($dup_data == 'order_status') {
						$db_column = "order_status";
					}

					$sql1		= "SELECT * FROM " . $master_table . " WHERE " . $db_column . " = '" . $duplicate_colum_values1 . "' ";
					$result1	= $db->query($conn, $sql1);
					$count1		= $db->counter($result1);
					if ($count1 > 0) {
						$duplicate_data_array[] = $duplicate_colum_values1;
						if (!isset($error['msg'])) {
							$error['msg'] = "This " . $dup_data . ": <span class='color-blue'>" . $duplicate_colum_values1 . "</span> is already exist.";
						} else {
							$error['msg'] .= "<br>This " . $dup_data . ": <span class='color-blue'>" . $duplicate_colum_values1 . "</span> is already exist.";
						}
					}
				}
			}
			foreach ($all_data  as $data1) {
				$duplicate_data_array = array_unique($duplicate_data_array);
				if (!in_array($data1[$dup_data], $duplicate_data_array)) {
					$update_master = $update_product = $columns = $column_data = "";
					foreach ($data1 as $key => $data) {
						if (htmlspecialchars($data) == '-' || htmlspecialchars($data) == '' || htmlspecialchars($data) == 'blank') {
							$data = "";
						}
						if ($key != "" && $key != 'is_insert') {
							if ($data != "") {
								if (in_array($key, $master_columns)) {

									if ($key == 'order_status') {
										$key = "order_status";
									}

									if ($key == 'so_no' && $data != "") {
										$insert_db_field_id	= "sales_order_id";
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
									} else if ($key == 'customer') {
										if ($data != '' && $data != NULL && $data != '-' && $data != 'blank') {
											$insert_db_field_id_detail	= "customer_id";
											$insert_db_field_id_detai2	= "customer_name";
											$table1 					= "customers";

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

													$customer_no	= "C" . ${$insert_db_field_id_detail};
													$sql6		= "	UPDATE customers SET customer_no = '" . $customer_no . "' 
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
										if ($key == 'order_date' || $key == 'estimated_ship_date') {
											$data = convert_date_mysql_slash($data);
										}
										$update_master .= $key . " = '" . $data . "', ";
									}
								} else {
									if (isset(${$insert_db_field_id}) && ${$insert_db_field_id} > 0) {

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
												} else {
													$sql6 = "INSERT INTO " . $selected_db_name . "." . $table1 . "(subscriber_users_id, " . $key . ", add_date, add_by, add_ip, add_timezone)
															VALUES('" . $subscriber_users_id . "', '" . $data . "', '" . $add_date . "', '" . $_SESSION['username'] . " Imported', '" . $add_ip . "', '" . $timezone . "')";
													$ok = $db->query($conn, $sql6);
													if ($ok) {

														${$insert_db_field_id_detail_pd} = mysqli_insert_id($conn);

														$product_no	= "P" . ${$insert_db_field_id_detail_pd};
														$sql6		= "	UPDATE products SET product_no = '" . $product_no . "' 
																		WHERE id = '" . ${$insert_db_field_id_detail_pd} . "' ";
														$db->query($conn, $sql6);

														$columns 						.= ", " . $insert_db_field_id_detail_pd;
														$column_data 					.= ", '" . ${$insert_db_field_id_detail_pd} . "'";
													}
												}
											}
										} else if ($key == 'product_desc' || $key == 'product_category') {

											if (isset(${$insert_db_field_id_detail_pd})) {
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
										} else {
											$columns 		.= ", " . $key;
											$column_data 	.= ", '" . $data . "'";
										}
									}
								}
							}
						}
					}

					if (isset(${$insert_db_field_id}) && ${$insert_db_field_id} > 0) {
						if ($update_master != "") {
							$sql6 = " UPDATE " . $selected_db_name . "." . $master_table . " SET  " . $update_master . "
																									update_date 	= '" . $add_date . "', 
																									update_by 		= '" . $_SESSION['username'] . " Imported', 
																									update_ip 		= '" . $add_ip . "', 
																									update_timezone	= '" . $timezone . "'
								WHERE id = '" . ${$insert_db_field_id} . "' "; // echo "<br>" . $sql6;
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
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="col s10 m6 l6">
						<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $title_heading; ?></span></h5>
						<ol class="breadcrumbs mb-0">
							<li class="breadcrumb-item"><?php echo $title_heading; ?>
							</li>
							<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">List</a>
							</li>
						</ol>
					</div>
					<div class="col s2 m6 l6">
						<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
							List
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy">
				<div class="card-content">
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



													if ($column_name_c1 == 'customer') {
														$db_name_c1 = 'customer_name';
														$sql_dup	= " SELECT * FROM customers WHERE " . $db_name_c1 . "	= '" . htmlspecialchars($cell_c1) . "' ";
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
													if ($column_name_c1 == 'order_status') {
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
													foreach ($row as $cell) {

														$db_column 			= $headings[$col_no];
														$db_column_excel	= $db_column;


														if (!in_array($db_column_excel, $supported_column_titles)) {
															$row_color 	= "color-red";
															$is_error 	= 1;
															$is_insert 	= "No";
														}
														if (in_array($db_column_excel, $duplication_columns)) {


															if ($db_column == 'product_grade') {
																$db_column = "stock_grade";
															}
															if ($db_column == 'product_sub_id') {
																$db_column = "stock_product_uniqueid";
															}

															if ($db_column == 'status') {
																$db_column = "p_inventory_status";
															}

															if ($db_column == 'total_stock') {
																$db_column = "p_total_stock";
															}

															if ($db_column == 'product_base_id') {
																$db_column = "product_id";
															}

															$sql_dup 	= " SELECT * FROM " . $master_table . " WHERE " . $db_column . "	= '" . htmlspecialchars($cell) . "' "; // echo "<br>" . $sql_dup;
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