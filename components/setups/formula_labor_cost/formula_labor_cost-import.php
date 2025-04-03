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

$supported_column_titles 	= array("formula_type", "category", "labor_cost");
$duplication_columns 		= array("formula_type", "category");
$required_columns 			= array("formula_type", "category", "labor_cost");

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
$added = 0;
$master_table = "formula_labor_cost";
if (isset($is_Submit2) && $is_Submit2 == 'Y') {

	// $filter_import_colums = array_filter($import_colums, function ($value) {
	// 	return !empty($value);
	// });

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

	if (empty($error)) {
		$result = array();
		if (isset($all_data) && sizeof($all_data) > 0) {
			$dup = 0;
			$dup_msg2 = "";
			foreach ($all_data as $index => $record) {
				$dup_msg 	= "";
				$dup_where 	= " 1 = 1 ";
				$q 			= 0;
				$filtered 	= [];
				foreach ($duplication_columns  as $dup_data) {
					if (isset($record[$dup_data])) {
						$cell_data				= $record[$dup_data];
						$filtered[$dup_data]	= $cell_data;
						$db_name = $dup_data;
						if ($dup_data == 'category') {
							$db_name = " product_category ";
							if ($cell_data != '' && $cell_data != NULL && $cell_data != '-' && $cell_data != 'blank') {
								$sql1		= "SELECT * FROM product_categories WHERE category_name = '" . $cell_data . "' ";
								$result1	= $db->query($conn, $sql1);
								$count1		= $db->counter($result1);
								if ($count1 > 0) {
									$row1		 = $db->fetch($result1);
									$dup_where	.= " AND " . $db_name . " = '" . $row1[0]['id'] . "'";
									$dup_msg	.= " " . $cell_data;
								} else {
									$sql6 = "INSERT INTO " . $selected_db_name . ".product_categories(subscriber_users_id, category_name, category_type, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
											 VALUES('" . $subscriber_users_id . "', '" . $data . "', 'Device', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
									$ok = $db->query($conn, $sql6);
									if ($ok) {
										$category_id 	= mysqli_insert_id($conn);
										$dup_where		.= " AND " . $db_name . " = '" . $category_id . "'";
										$dup_msg		.= " " . $cell_data;
									}
								}
							}
						} else {
							$dup_where	.= " AND " . $dup_data . " = '" . $cell_data . "'";
							$dup_msg	.= " " . $cell_data;
						}
					}
				}
				$sql1		= "SELECT * FROM " . $master_table . " WHERE " . $dup_where . " ";
				$result1	= $db->query($conn, $sql1);
				$count1		= $db->counter($result1);
				if ($count1 > 0) {
					$result[$index] = $filtered;
					$dup++;
					$dup_msg2 .= "<br>" . $dup_msg;
				}
			}
			if ($dup > 0) {
				if (!isset($error['msg'])) {
					$error['msg'] = "Already exist: <span class='color-blue'>" . $dup_msg2 . "</span>.";
				} else {
					$error['msg'] .= "Already exist: <span class='color-blue'>" . $dup_msg2 . "</span>.";
				}
			}
			foreach ($all_data as $index1 => $data1) {
				if (array_key_exists($index1, $result)) {
					// echo "<br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: find " . $index1;
				} else {
					// echo "<br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: does not find" . $index1;
					$columns = $column_data = "";
					foreach ($data1 as $key => $data) {
						if ($key != "" && $key != 'is_insert') {
							if ($data != '' && $data != NULL && $data != '-' && $data != 'blank') {
								if ($key == 'category') {
									$db_name 	= " product_category ";
									$sql1		= "SELECT * FROM product_categories WHERE category_name = '" . $data . "' ";
									$result1	= $db->query($conn, $sql1);
									$count1		= $db->counter($result1);
									if ($count1 > 0) {
										$row1		 	= $db->fetch($result1);
										$columns 		.= ", " . $db_name;
										$column_data 	.= ", '" . $row1[0]['id'] . "'";
									} else {
										$sql6 = "INSERT INTO " . $selected_db_name . ".product_categories(subscriber_users_id, category_name, category_type, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
												VALUES('" . $subscriber_users_id . "', '" . $data . "', 'Device', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
										$ok = $db->query($conn, $sql6);
										if ($ok) {
											$category_id 	= mysqli_insert_id($conn);
											$columns 		.= ", " . $db_name;
											$column_data 	.= ", '" . $category_id . "'";
										}
									}
								} else {
									$columns 		.= ", " . $key;
									$column_data 	.= ", '" . $data . "'";
								}
							}
						}
					}
					if ($columns != "" && $column_data != "") { // ONLY IF one field 
						$sql6 = "INSERT INTO " . $selected_db_name . "." . $master_table . "(subscriber_users_id " . $columns . ", add_date, add_by, add_ip)
								VALUES('" . $subscriber_users_id . "' " . $column_data . ", '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
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
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=add&cmd2=add") ?>">
									New
								</a>
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">
									List
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
										if ($s_heading == 'defect_code') {
											$cell_format = "Text (Unique)";
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
															if ($db_column == 'category') {
																$db_column = "product_category";
															}


															$row_color = "color-green"; ?>
															<input type="hidden" name="all_data[<?= $row_no; ?>][<?= $db_column_excel; ?>]" value="<?= $cell; ?>">
														<?php
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
	</div><br><br><br><br>
	<!-- END: Page Main-->