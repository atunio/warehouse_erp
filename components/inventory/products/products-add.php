<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$product_desc		= "xyz " . date('Ymd');
	$address			= "address " . date('Ymd');
	$product_category	= "1";
	$product_uniqueid	= uniqid();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd2 == 'add') {
	$product_id					= "2001";
	$order_qty					= "1";
	$order_price				= "500";
	$product_po_desc			= "product_po_desc: " . date('YmdHis');
	$is_tested					= "Yes";
	$is_wiped					= "Yes";
	$is_imaged					= "Yes";
	$product_condition			= "A Grade";
	$warranty_period_in_days	= "15";
	$vender_invoice_no			= date('YmdHis');
	$product_model_no			= array("DMQD7TMFMF3M1", "DMQD7TMFMF3M2");
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

// if (!isset($is_Submit) && $cmd == 'edit' && isset($msg['msg_success']) && isset($id)) {
// 	echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id));
// }
if (isset($cmd3) && $cmd3 == 'disabled') {
	$sql_c_upd = "UPDATE product_packages set 	enabled = 0,
													update_date = '" . $add_date . "' ,
													update_by 	= '" . $_SESSION['username'] . "' ,
													update_ip 	= '" . $add_ip . "'
				WHERE id = '" . $detail_id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg2['msg_success'] = "Record has been disabled.";
	} else {
		$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
	}
}
if (isset($cmd3) && $cmd3 == 'enabled') {
	$sql_c_upd = "UPDATE product_packages set 	enabled 	= 1,
													update_date = '" . $add_date . "' ,
													update_by 	= '" . $_SESSION['username'] . "' ,
													update_ip 	= '" . $add_ip . "'
				WHERE id = '" . $detail_id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg2['msg_success'] = "Record has been enabled.";
	}
}

if ($cmd == 'edit') {
	$title_heading 	= "Update Product";
	$button_val 	= "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Product";
	$button_val 	= "Add";
	$id 			= "";
}

if ($cmd2 == 'edit') {
	$title_heading2  = "Update Product Packaging Materials";
	$button_val2 	= "Save";
}
if ($cmd2 == 'add') {
	$title_heading2	= "Add Product Packaging Materials";
	$button_val2 	= "Add";
	$detail_id		= "";
}

if ($cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee					= "SELECT a.* FROM products a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$product_desc			= $row_ee[0]['product_desc'];
	$product_category		=  $row_ee[0]['product_category'];
	$inventory_status		= $row_ee[0]['inventory_status'];
	$total_stock			= $row_ee[0]['total_stock'];
	$product_uniqueid		= $row_ee[0]['product_uniqueid'];
	$product_model_no 		= explode(",", $row_ee[0]['product_model_no']);
}
if ($cmd2 == 'edit' && isset($detail_id) && $detail_id > 0) {
	$sql_ee						= "SELECT a.* FROM product_packages a WHERE a.id = '" . $detail_id . "' "; // echo $sql_ee;
	$result_ee					= $db->query($conn, $sql_ee);
	$row_ee						= $db->fetch($result_ee);
	$package_id					= $row_ee[0]['package_id'];
	$is_mandatory				= $row_ee[0]['is_mandatory'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	$field_name = "product_category";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}
	$field_name = "product_uniqueid";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}
	if (empty($error)) {
		$po_date1 = "0000-00-00";
		if (isset($po_date) && $po_date != "") {
			$po_date1 = convert_date_mysql_slash($po_date);
		}
		$estimated_receive_date1 = "0000-00-00";
		if (isset($estimated_receive_date) && $estimated_receive_date != "") {
			$estimated_receive_date1 = convert_date_mysql_slash($estimated_receive_date);
		}
		$all_product_model_nos = "";
		if (isset($product_model_no) && is_array($product_model_no)) {
			$filtered_product_model_no = array_filter($product_model_no, function ($value) {
				return !empty($value); // Remove empty values
			});
			if (!empty($filtered_product_model_no)) {
				$all_product_model_nos = implode(",", $filtered_product_model_no);
			}
		}

		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM products a 
								WHERE a.product_uniqueid	= '" . $product_uniqueid . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".products(subscriber_users_id, product_desc,  product_uniqueid, product_category, product_model_no, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
							VALUES('" . $subscriber_users_id . "', '" . $product_desc . "',  '" . $product_uniqueid  . "', '" . $product_category . "',  '" . $all_product_model_nos . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id 			= mysqli_insert_id($conn);
						$product_no		= "P" . $id;
						$sql6 			= "UPDATE products SET product_no = '" . $product_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
						echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id . "&msg_success=" . $msg['msg_success']));
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "The model# or product id is already exist.";
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM products a 
								WHERE  a.product_uniqueid 	= '" . $product_uniqueid . "'
								AND a.id != '" . $id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {

					$sql_c_up = "UPDATE products SET 	product_desc			= '" . $product_desc . "', 
														product_category		= '" . $product_category . "',
														product_uniqueid		= '" . $product_uniqueid . "', 
														product_model_no		= '" . $all_product_model_nos . "', 
														
														update_date				= '" . $add_date . "',
														update_by				= '" . $_SESSION['username'] . "',
														update_by_user_id		= '" . $_SESSION['user_id'] . "',
														update_ip				= '" . $add_ip . "',
														update_timezone			= '" . $timezone . "',
														update_from_module_id	= '" . $module_id . "' 			
								WHERE id = '" . $id . "'   ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
					} else {
						$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "The model# or product id is already exist.";
				}
			}
		}
	}
}
if (isset($is_Submit2) && $is_Submit2 == 'Y') {
	$field_name = "is_mandatory";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "package_id";
	if ((isset(${$field_name}) && (${$field_name} == '' || ${$field_name} == '0'))) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	// is_mandatory package_id
	if (empty($error)) {
		if ($cmd2 == 'add') {
			if (access("add_perm") == 0) {
				$error2['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM product_packages a 
								WHERE a.product_id		= '" . $id . "'
								AND a.package_id		= '" . $package_id . "'  ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".product_packages(subscriber_users_id, product_id, package_id, is_mandatory, add_date, add_by, add_by_user_id, add_ip, add_timezone)
							 VALUES('" . $subscriber_users_id . "', '" . $id . "', '" . $package_id . "', '" . $is_mandatory  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						if (isset($error2['msg'])) unset($error2['msg']);
						$msg2['msg_success'] = "Record has been added successfully.";
						$package_id = $is_mandatory = "";
					} else {
						$error2['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error2['msg'] = "This record is already exist.";
				}
			}
		} else if ($cmd2 == 'edit') {
			if (access("edit_perm") == 0) {
				$error2['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM product_packages a 
								WHERE a.product_id		= '" . $id . "'
								AND a.package_id		= '" . $package_id . "' 
 								AND a.id			   != '" . $detail_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE product_packages SET 	package_id				= '" . $package_id . "', 
																is_mandatory			= '" . $is_mandatory . "',  

																update_date				= '" . $add_date . "',
																update_by				= '" . $_SESSION['username'] . "',
																update_by_user_id		= '" . $_SESSION['user_id'] . "',
																update_ip				= '" . $add_ip . "',
																update_timezone			= '" . $timezone . "'
								WHERE id = '" . $detail_id . "'   ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg2['msg_success'] = "Record Updated Successfully.";
					} else {
						$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error2['msg'] = "This record is already exist.";
				}
			}
		}
	}
}  ?>
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
	</div>
	<div class="row">
		<div class="col s12 m12 l12 ">
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
					<h4 class="card-title">Product Detail</h4><br>
					<form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id); ?>">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" id="cmd" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<div class="row">
							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "product_uniqueid";
								$field_id 		= "product_uniqueid2";
								$field_label 	= "Product ID";
								$sql1 			= "SELECT * FROM product_ids WHERE 1=1  ";
								if (isset($cmd) && $cmd != "edit") {
									$sql1 .= " AND enabled = 1";
								}
								$result1 		= $db->query($conn, $sql1);
								$count1 		= $db->counter($result1);
								?>
								<i class="material-icons prefix">description</i>
								<div class="select2div">
									<select id="<?= $field_id; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1	= $db->fetch($result1);
											foreach ($row1 as $data2) { ?>
												<option value="<?php echo $data2['product_id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['product_id']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_id']; ?></option>
										<?php }
										} ?>
									</select>
									<label for="<?= $field_id; ?>">
										<?= $field_label; ?>
										<span class="color-red">* <?php
																	if (isset($error[$field_name])) {
																		echo $error[$field_name];
																	} ?>
										</span>
									</label>
								</div>
							</div>
							<div class="input-field col m2 s12 custom_margin_bottom_col"><br>
								<a class="waves-effect waves-light btn modal-trigger mb-2 mr-1 custom_btn_size" href="#productid_add_modal">Add New Product ID</a>
							</div>
							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "product_category";
								$field_id 		= "product_category2";
								$field_label 	= "Category";
								$sql1 			= "SELECT * FROM product_categories WHERE category_type = 'Device' ";
								if (isset($cmd) && $cmd != "edit") {
									$sql1 .= " AND enabled = 1";
								}
								$sql1 .= "  ORDER BY category_name ";
								$result1 		= $db->query($conn, $sql1);
								$count1 		= $db->counter($result1);
								?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_id; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
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
									<label for="<?= $field_id; ?>">
										<?= $field_label; ?>
										<span class="color-red">* <?php
																	if (isset($error[$field_name])) {
																		echo $error[$field_name];
																	} ?>
										</span>
									</label>
								</div>
							</div>
							<div class="input-field col m2 s12 custom_margin_bottom_col"><br>
								<a class="waves-effect waves-light btn modal-trigger mb-2 mr-1 custom_btn_size" href="#category_add_modal">Add New Category</a>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m6 s12">
								<?php
								$field_name 	= "product_desc";
								$field_label 	= "Product Descripton";
								?>
								<i class="material-icons prefix">description</i>
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
						</div>
						<div class="row">
							<?php
							$max = 1;
							if (isset($product_model_no)) {
								// Filter out empty values from the array
								$filtered = array_filter($product_model_no, function ($value) {
									return !empty($value); // Keep only non-empty values
								});
								// Check if there are any non-empty values
								if (!empty($filtered)) {
									$max = sizeof($filtered) - 1;
								}
							}
							for ($i = 0; $i < 100; $i++) {
								$style = $style2 = "";
								if ($i > $max) {
									$style = "display: none;";
								}
								if ($i > $max || $i < $max) {
									$style2 = "display: none;";
								}
								$i2 = $i + 1; ?>
								<div class="input-field col m2 s12 product_model_no_input_<?= $i2; ?>" style="<?= $style; ?>">
									<?php
									$field_name     = "product_model_no";
									$field_id       = $field_name . "_" . $i2;
									$field_label    = "Model# " . $i2;
									?>
									<i class="material-icons prefix">description</i>
									<input id="<?= $field_id; ?>" type="text" name="<?= $field_name; ?>[]" value="<?php if (isset($product_model_no[$i])) {
																														echo trim($product_model_no[$i]);
																													} ?>" class="validate ">
									<label for="<?= $field_id; ?>">
										<?= $field_label; ?>
										<span class="color-red">* <?php
																	if (isset($error5["field_name_" . $i2])) {
																		echo $error5["field_name_" . $i2];
																	} ?>
										</span>
									</label>
								</div>
								<div style="<?= $style; ?>" class=" input-field col m1 s12 button_div_product_model_no" id="button_div_product_model_no_<?= $i2; ?>">
									<a href="javascript:void(0)" style="<?= $style2; ?> font-size: 30px;" class="add_<?= $field_name; ?> add_<?= $field_name; ?>_<?= $i2; ?>" id="add_<?= $field_name; ?>^<?= $i2; ?>">+</a>
									&nbsp;
									<a href="javascript:void(0)" style="<?= $style; ?> font-size: 30px;" class="minus_<?= $field_name; ?> minus_<?= $field_name; ?>_<?= $i2; ?>" id="minus_<?= $field_name; ?>^<?= $i2; ?>">-</a>
								</div>
							<?php } ?>
						</div>
						<div class="row">
							<div class="input-field col m6 s12">
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
		if (isset($cmd) && $cmd == 'edit') { ?>
			<div class="col s12 m12 l12">
				<div id="Form-advance2" class="card card card-default scrollspy">
					<div class="card-content">
						<?php
						if (isset($error2['msg'])) { ?>
							<div class="card-alert card red lighten-5">
								<div class="card-content red-text">
									<p><?php echo $error2['msg']; ?></p>
								</div>
								<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
						<?php } else if (isset($msg2['msg_success'])) { ?>
							<div class="card-alert card green lighten-5">
								<div class="card-content green-text">
									<p><?php echo $msg2['msg_success']; ?></p>
								</div>
								<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
						<?php } ?>
						<h4 class="card-title"><?php echo $title_heading2; ?></h4><br>
						<form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=' . $cmd . '&cmd2=' . $cmd2 . '&id=' . $id . '&detail_id=' . $detail_id); ?>">
							<input type="hidden" name="is_Submit2" value="Y" />
							<div class="row">
								<div class="input-field col m4 s12">
									<?php
									$field_name 	= "package_id";
									$field_label	= "Packaging Material / Part";
									?>
									<i class="material-icons prefix">subtitles</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {  //a.product_sku, a.case_pack,a.pack_desc, b.category_name, c.total_stock
																																											echo ${$field_name . "_valid"};
																																										} ?>">
											<?php

											$sql1 			= " SELECT  a.id, b.category_name, a.package_name
																	FROM packages a
																	INNER JOIN product_categories b ON b.id = a.product_category
 																	WHERE 1=1
																	AND a.enabled = 1 
																	ORDER BY b.category_name, a.package_name ";
											$result1 		= $db->query($conn, $sql1);
											$count1 		= $db->counter($result1);
											if ($count1 > 0) {
												$row1	= $db->fetch($result1); ?>
												<option value="">Select</option>
												<?php
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>>
														<?php echo $data2['package_name']; ?> (<?php echo $data2['category_name']; ?>)
													</option>
												<?php }
											} else { ?>
												<option value="">No <?= $field_label; ?> Available</option>
											<?php }  ?>
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
									<?php
									$field_name = "product_id_for_package_material"; ?>
									<input type="hidden" name="<?= $field_name ?>" id="<?= $field_name ?>" value="" />
								</div>
								<?php /*?>
								<div class="input-field col m2 s12">
									<a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#product_add_modal">Add New Package Material</a>
								</div>
								<?php */ ?>

								<div class="input-field col m4 s12">
									<?php
									$field_name 	= "is_mandatory";
									$field_label 	= "Type";
									?>
									<div style="margin-top: -10px; margin-bottom: 10px;">
										<?= $field_label; ?>
										<span class="color-red">* <?php
																	if (isset($error[$field_name])) {
																		echo $error[$field_name];
																	} ?>
										</span>
									</div>
									<p class="mb-1 custom_radio">
										<label>
											<input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="Yes" <?php
																																if (isset(${$field_name}) && ${$field_name} == 'Yes') {
																																	echo 'checked=""';
																																} ?>>
											<span>Mandatory</span>
										</label> &nbsp;&nbsp;
										<label>
											<input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php
																																if (isset(${$field_name}) && ${$field_name} == 'No') {
																																	echo 'checked=""';
																																} ?>>
											<span>Optional</span>
										</label>
									</p>
								</div>
							</div>
							<div class="row">
								<div class="input-field col m3 s12">
									<?php if (($cmd2 == 'add' && access("add_perm") == 1)  || ($cmd2 == 'edit' && access("edit_perm") == 1)) { ?>
										<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val2; ?>
											<i class="material-icons right">send</i>
										</button>
									<?php } ?>
								</div>
								<div class="input-field col m2 s12">
									<?php if ($cmd2 == 'edit' && access("add_perm") == 1) { ?>
										<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&id=" . $id) ?>">Add New Package Material</a>
									<?php } ?>
								</div>
							</div>
						</form>
					</div>
					<?php //include('sub_files/right_sidebar.php'); 
					?>
				</div>
			</div>
			<?php
			$sql_cl		= "	SELECT  a1.*, b.category_name, a.package_name
							FROM product_packages a1 
							INNER JOIN packages a ON a.id = a1.package_id
							INNER JOIN product_categories b ON b.id = a.product_category
							WHERE 1=1
							AND a.enabled = 1 
							AND a1.product_id = '" . $id . "' 
							ORDER BY a1.enabled DESC, b.category_name, a.package_name";
			// echo $sql_cl;
			$result_cl	= $db->query($conn, $sql_cl);
			$count_cl	= $db->counter($result_cl);
			if ($count_cl > 0) { ?>
				<div class="col s12">
					<div class="container">
						<div class="section section-data-tables">
							<!-- Page Length Options -->
							<h4 class="card-title">Package Materials</h4>
							<div class="row">
								<div class="col s12">
									<div class="card">
										<div class="card-content">
											<?php
											if (isset($error3['msg'])) { ?>
												<div class="card-alert card red lighten-5">
													<div class="card-content red-text">
														<p><?php echo $error3['msg']; ?></p>
													</div>
													<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
											<?php } else if (isset($msg3['msg_success'])) { ?>
												<div class="card-alert card green lighten-5">
													<div class="card-content green-text">
														<p><?php echo $msg3['msg_success']; ?></p>
													</div>
													<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
											<?php } ?>
											<div class="row">
												<div class="col s12">
													<table id="page-length-option" class="display">
														<thead>
															<tr>
																<?php
																$headings = '<th class="sno_width_60">S.No</th>
																			<th>Package Material</th>
																			<th>Is Mandatory</th>
																			<th>Action</th>';
																echo $headings; ?>
															</tr>
														</thead>
														<tbody>
															<?php
															$i = 0;
															$row_cl = $db->fetch($result_cl);
															foreach ($row_cl as $data) {
																$detail_id2 		= $data['id'];  ?>
																<tr>
																	<td style="text-align: center;">
																		<?php echo $i + 1; ?>
																	</td>
																	<td>
																		<?php echo ucwords(strtolower($data['package_name'])); ?>
																		<?php
																		if ($data['category_name'] != "") {
																			echo  " (" . $data['category_name'] . ")";
																		} ?>
																	</td>
																	<td><?php echo $data['is_mandatory']; ?></td>
																	<td class="text-align-center">
																		<?php
																		if ($data['enabled'] == 1 && access("view_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=edit&id=" . $id . "&detail_id=" . $detail_id2) ?>">
																				<i class="material-icons dp48">edit</i>
																			</a> &nbsp;&nbsp;
																		<?php }
																		if ($data['enabled'] == 0 && access("edit_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&cmd3=enabled&id=" . $id . "&detail_id=" . $detail_id2) ?>">
																				<i class="material-icons dp48">add</i>
																			</a> &nbsp;&nbsp;
																		<?php } else if ($data['enabled'] == 1 && access("delete_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&cmd3=disabled&id=" . $id . "&detail_id=" . $detail_id2) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																				<i class="material-icons dp48">delete</i>
																			</a> &nbsp;&nbsp;
																		<?php } ?>
																	</td>
																</tr>
															<?php
																$i++;
															}  ?>
														<tfoot>
															<tr><?php echo $headings; ?></tr>
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
					</div>
					<div class="content-overlay"></div>
				</div>
		<?php }
		} ?>
	</div>
	<?php include("sub_files/add_category_modal.php") ?>
	<?php include("sub_files/add_productid_modal.php") ?>
</div>
<br><br><br><br>
<!-- END: Page Main-->
<!-- END: Page Main-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> -->
<?php include("sub_files/add_category_js_code.php") ?>
<?php include("sub_files/add_productid_js_code.php") ?>