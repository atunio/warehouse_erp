<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$product_sku 		= uniqid();
	$product_category 	= 7;
	$case_pack 			= 10;
	$product_id			= "2001";
	$pack_desc			= "pack_desc: " . date('Ymd');
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];


if ($cmd == 'edit') {
	$title_heading = "Update " . $main_menu_name;
	$button_val = "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add " . $main_menu_name;
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee					= "SELECT a.* FROM packages a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$package_name			= $row_ee[0]['package_name'];
	$product_category		= $row_ee[0]['product_category'];
	$product_ids_comma_sepr = $row_ee[0]['product_ids'];
	$product_ids			= explode(",", $product_ids_comma_sepr);
	$stock_in_hand			= $row_ee[0]['stock_in_hand'];
	$case_pack				= $row_ee[0]['case_pack'];
	$package_product_id		= $row_ee[0]['package_product_id'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {

	$field_name = "product_ids";
	if (isset(${$field_name}) && sizeof(${$field_name}) == "0") {
		$error[$field_name] 		= "Select atleat one Compatible Product";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "stock_in_hand";
	if (isset(${$field_name}) && ${$field_name} == ""  && $cmd != 'edit') {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}
	$field_name = "case_pack";
	if (isset(${$field_name}) && ${$field_name} == ""  && $cmd != 'edit') {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}

	$field_name = "product_category";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}
	$field_name = "package_product_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}
	$field_name = "package_name";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}
	if (empty($error)) {
		$product_ids_str = isset($product_ids) ? implode(",", $product_ids) : "";
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM packages a 
								WHERE  a.package_product_id = '" . $package_product_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) { //product_sku, case_pack,
					$sql6 = "INSERT INTO " . $selected_db_name . ".packages(subscriber_users_id, product_ids, package_product_id, package_name, product_category, stock_in_hand, case_pack , add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
							VALUES('" . $subscriber_users_id . "', '" . $product_ids_str . "', '" . $package_product_id . "',  '" . $package_name . "',  '" . $product_category . "', '" . $stock_in_hand  . "', '" . $case_pack . "' ,'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id 			= mysqli_insert_id($conn);
						$package_no		= "PP" . $id;
						$sql6 			= "UPDATE packages SET package_no = '" . $package_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
						$product_sku = $package_name = $product_category = $case_pack = $stock_in_hand = "";
						unset($product_ids);
						unset($all_checked);
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error['package_product_id'] = "Already exist.";
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM packages a 
								WHERE a.package_product_id = '" . $package_product_id . "' 
								AND a.id			   != '" . $id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE packages SET 	package_product_id	= '" . $package_product_id . "', 
														package_name		= '" . $package_name . "',
														product_category	= '" . $product_category . "',  
 														product_ids			= '" . $product_ids_str . "', 
 														case_pack			= '" . $case_pack . "', 
														update_date			= '" . $add_date . "',
														update_by			= '" . $_SESSION['username'] . "',
														update_ip			= '" . $add_ip . "'
								WHERE id = '" . $id . "'   ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
					} else {
						$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error['package_product_id'] = "Already exist.";
				}
			}
		}
	} else {
		$error['msg'] = "Please check required fields in form";
	}
}
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
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">
									List
								</a>
								<?php if (access("add_perm") == 1) { ?>
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
	</div>
	<div class="row">
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy custom_margin_card_table_top custom_margin_card_table_bottom">
				<div class="card-content custom_padding_card_content_table_top">
					<?php
					if (isset($error['msg'])) { ?>
						<div class="card-alert card red lighten-5">
							<div class="card-content red-text">
								<p><?php echo $error['msg']; ?></p>
							</div>
							<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
					<?php } else if (isset($msg['msg_success'])) { ?>
						<div class="card-alert card green lighten-5">
							<div class="card-content green-text">
								<p><?php echo $msg['msg_success']; ?></p>
							</div>
							<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
					<?php } ?>
					<br>
					<form method="post" autocomplete="off" action="">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" id="cmd" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" id="id" name="id" value="<?php if (isset($id)) echo $id; ?>" />
						<div class="row">
							<?php
							$field_name 	= "package_name";
							$field_label 	= "Package / Part Name";
							?>
							<div class="input-field col m4 s12">
								<i class="material-icons prefix">question_answer</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																			echo ${$field_name . "_valid"};
																																		} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<?php
							$field_name 	= "package_product_id";
							$field_label 	= "Package Product ID";
							?>
							<div class="input-field col m4 s12">
								<i class="material-icons prefix">question_answer</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																			echo ${$field_name . "_valid"};
																																		} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "product_category";
								$field_label 	= "Category";
								$sql1 			= "SELECT * FROM product_categories WHERE category_type != 'Device'  ";
								if (isset($cmd) && $cmd != "edit") {
									$sql1 .= " AND enabled = 1";
								}
								$sql1 			.= "  ORDER BY category_name ";
								$result1 		 = $db->query($conn, $sql1);
								$count1 		 = $db->counter($result1);
								?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="product_category_main" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
										<option value="">Select</option>
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
										<span class="color-red">* <?php
																	if (isset($error[$field_name])) {
																		echo $error[$field_name];
																	} ?>
										</span>
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<?php
							$field_name 	= "case_pack";
							$field_label 	= "No of Case Pack";
							?>
							<div class="input-field col m4 s12">
								<i class="material-icons prefix">question_answer</i>
								<input id="<?= $field_name; ?>" type="number" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																			echo ${$field_name . "_valid"};
																																		} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<?php
							$field_name 	= "stock_in_hand";
							$field_label 	= "Stock In Hand";
							?>
							<div class="input-field col m4 s12">
								<i class="material-icons prefix">question_answer</i>
								<input id="<?= $field_name; ?>" <?php if (isset($cmd) && $cmd == 'edit') {
																	echo "disabled";
																} ?> type="number" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																															echo ${$field_name};
																														} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																					echo ${$field_name . "_valid"};
																																				} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m4 s12">
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn cyan waves-effect waves-light " type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
								<?php } ?>
							</div>
						</div>

						<div class="row">
							<?php
							$field_name 	= "product_ids";
							$field_label 	= "Compatible Product";
							?>
							<label>
								<b>
									<?= $field_label; ?>
									<span class="color-red"> * <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</b>
							</label>
							<label>
								<input type="checkbox" id="all_checked" class="filled-in" name="all_checked" value="1" <?php if (isset($all_checked) && $all_checked == '1') {
																															echo "checked";
																														} ?> />
								<span></span>
							</label>
							<br>
							<?php
							$count12 = 0;
							if (isset($product_ids_comma_sepr) && $product_ids_comma_sepr != "") {
								$sql12		= " SELECT a.*, b.category_name
												FROM products a
												INNER JOIN product_categories b ON b.id = a.product_category
												WHERE a.enabled = 1 
												AND FIND_IN_SET(a.id, '" . $product_ids_comma_sepr . "')
												ORDER BY a.product_uniqueid ";
								$result_cl2	= $db->query($conn, $sql12);
								$count12	= $db->counter($result_cl2);

								$sql12		= " SELECT a.*, b.category_name
												FROM products a
												INNER JOIN product_categories b ON b.id = a.product_category
												WHERE a.enabled = 1 
												AND NOT FIND_IN_SET(a.id, '" . $product_ids_comma_sepr . "')
												ORDER BY a.product_uniqueid ";
								$result_cl2_2	= $db->query($conn, $sql12);
								$count12_2	= $db->counter($result_cl2_2);
							} else {
								$sql12		= " SELECT a.*, b.category_name
												FROM products a
												INNER JOIN product_categories b ON b.id = a.product_category
												WHERE a.enabled = 1 
												ORDER BY a.product_uniqueid ";
								$result_cl2_2	= $db->query($conn, $sql12);
								$count12_2	= $db->counter($result_cl2_2);
							} ?>
							<div class="section section-data-tables">
								<div class="row">
									<div class="col s12">
										<table id="page-length-option" class="display pagelength100">
											<thead>
												<tr>
													<?php
													$headings = '<th class="sno_width_60">Table Row#</th>
																 <th>Product</th>
																 <th>Product</th>
																 <th>Product</th>';
													echo $headings;
													?>
												</tr>
											</thead>
											<tbody>
												<?php
												$i = $j = $k = 0;
												if ($count12 > 0) {
													$row12 = $db->fetch($result_cl2);
													foreach ($row12 as $data2) {
														if ($i == 0) echo "<tr><td>" . ($k + 1) . "</td>"; ?>
														<td>
															<label>
																<input type="checkbox" value="<?php echo $data2['id']; ?>" name="<?= $field_name; ?>[]" id="<?= $field_name; ?>" class="checkbox" <?php if (isset(${$field_name}) && in_array($data2['id'], ${$field_name})) { ?> checked <?php } ?>>
																<span></span>
															</label>
															<?php echo $data2['product_uniqueid']; ?>
														</td>
														<?php
														$i++;
														$j++;

														if ($j == $count12) {
															if ($i == 1) {
																echo "<td></td>";
																echo "<td></td></tr>";
																$k++;
															} else if ($i == 2) {
																echo "<td></td></tr>";
																$k++;
															}
														} else {
														}
														if ($i == 3) {
															echo "</tr>";
															$k++;
															$i = 0;
														}
													}
												}
												$i = $j = 0;
												if ($count12_2 > 0) {
													$row12_2 = $db->fetch($result_cl2_2);
													foreach ($row12_2 as $data2_2) {
														if ($i == 0) echo "<tr><td>" . $k + 1 . "</td>"; ?>
														<td>
															<label>
																<input type="checkbox" value="<?php echo $data2_2['id']; ?>" name="<?= $field_name; ?>[]" id="<?= $field_name; ?>" class="checkbox" <?php if (isset(${$field_name}) && in_array($data2_2['id'], ${$field_name})) { ?> checked <?php } ?>>
																<span></span>
															</label>
															<?php echo $data2_2['product_uniqueid']; ?>
														</td>
												<?php
														$i++;
														$j++;

														if ($j == $count12_2) {
															if ($i == 1) {
																echo "<td></td>";
																echo "<td></td></tr>";
																$k++;
															} else if ($i == 2) {
																echo "<td></td></tr>";
																$k++;
															}
														}
														if ($i == 3) {
															echo "</tr>";
															$k++;
															$i = 0;
														}
													}
												} ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m12 s12"><br></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<br><br><br><br>
<!-- END: Page Main-->
<!-- END: Page Main-->