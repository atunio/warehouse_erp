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
	$detail_desc		= "detail_desc " . date('Ymd');
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

if ($cmd == 'edit') {
	$title_heading 	= "Update " . $main_menu_name;
	$button_val 	= "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add " . $main_menu_name;
	$button_val 	= "Add";
	$id 			= "";
}

if ($cmd == 'edit' && isset($id) && $id > 0) {

	$sql_ee						= "SELECT a.* FROM formula_category a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee					= $db->query($conn, $sql_ee);
	$row_ee						= $db->fetch($result_ee);

	$formula_type				= $row_ee[0]['formula_type'];
	$product_category			=  $row_ee[0]['product_category'];
	$devices_per_user_per_day	= $row_ee[0]['devices_per_user_per_day'];
	$no_of_employees			= $row_ee[0]['no_of_employees'];
	$repair_type				= $row_ee[0]['repair_type'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	$field_name = "devices_per_user_per_day";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "product_category";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	}
	$field_name = "formula_type";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name] 	= "Required";
		${$field_name . "_valid"} = "invalid";
	} else if (${$field_name} == 'Repair') {
		$field_name = "repair_type";
		if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
			$error[$field_name] 	= "Required";
			${$field_name . "_valid"} = "invalid";
		}
	} else {
		$repair_type = 0;
	}
	if (empty($error)) {
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup 	= " SELECT a.* 
								FROM formula_category a 
								WHERE  a.formula_type		= '" . $formula_type . "'
								AND  a.product_category		= '" . $product_category . "' ";
				if ($formula_type == 'Repair') {
					$sql_dup .= " AND a.repair_type = '" . $repair_type . "' ";
				}
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".formula_category(subscriber_users_id, formula_type, repair_type, product_category, devices_per_user_per_day,  add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
							VALUES('" . $subscriber_users_id . "', '" . $formula_type . "', '" . $repair_type . "', '" . $product_category . "',  '" . $devices_per_user_per_day  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$msg['msg_success'] = "Record has been added successfully.";
						$formula_type = $product_category = $devices_per_user_per_day = $no_of_employees = $repair_type = "";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM formula_category a 
								WHERE  a.formula_type		= '" . $formula_type . "'
								AND  a.product_category		= '" . $product_category . "'
								AND a.id		  		   != '" . $id . "'";
				if ($formula_type == 'Repair') {
					$sql_dup .= " AND a.repair_type = '" . $repair_type . "' ";
				}
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE formula_category SET 	formula_type				= '" . $formula_type . "', 
																product_category			= '" . $product_category . "',
																devices_per_user_per_day	= '" . $devices_per_user_per_day . "', 
																repair_type					= '" . $repair_type . "', 
																
																update_date					= '" . $add_date . "',
																update_by					= '" . $_SESSION['username'] . "',
																update_by_user_id			= '" . $_SESSION['user_id'] . "',
																update_ip					= '" . $add_ip . "',
																update_timezone				= '" . $timezone . "',
																update_from_module_id		= '" . $module_id . "' 			
								WHERE id = '" . $id . "'   ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
					} else {
						$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
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
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
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
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<div class="row">
							<div class="input-field col m2 s12">
								<?php
								$field_name 	= "formula_type";
								$field_label 	= "Formula Type";
								?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
										<option value="">Select</option>
										<option value="Processing" <?php if (isset(${$field_name}) && ${$field_name} == 'Processing') { ?> selected="selected" <?php } ?>>Processing</option>
										<option value="Repair" <?php if (isset(${$field_name}) && ${$field_name} == 'Repair') { ?> selected="selected" <?php } ?>>Repair</option>
										<option value="Diagnostic" <?php if (isset(${$field_name}) && ${$field_name} == 'Diagnostic') { ?> selected="selected" <?php } ?>>Diagnostic</option>
										<option value="Receive" <?php if (isset(${$field_name}) && ${$field_name} == 'Receive') { ?> selected="selected" <?php } ?>>Receive</option>
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
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "product_category";
								$field_id 		= "product_category2";
								$field_label 	= "Category";
								$sql1 			= "SELECT * FROM product_categories ORDER BY category_name ";
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
							<div class="input-field col m2 s12">
								<?php
								$field_name 	= "devices_per_user_per_day";
								$field_label 	= "No of Devices Per User Per Day";
								?>
								<i class="material-icons prefix">description</i>
								<input id="<?= $field_name; ?>" type="number" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																echo ${$field_name};
																															} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																						echo ${$field_name . "_valid"};
																																					} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> * <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m3 s12 formula_type" style="<?php if (!isset($formula_type) || (isset($formula_type) && $formula_type != 'Repair') || $formula_type == '') {
																						echo "display: none;";
																					} ?>">
								<?php
								$field_name     = "repair_type";
								$field_label    = "Repair Type";
								$sql1           = "SELECT * FROM repair_types WHERE enabled = 1  ORDER BY repair_type_name ";
								$result1        = $db->query($conn, $sql1);
								$count1         = $db->counter($result1);
								?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1    = $db->fetch($result1);
											foreach ($row1 as $data2) { ?>
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['repair_type_name']; ?> </option>
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
							<div class="input-field col m2 s12 formula_type" style="<?php if (!isset($formula_type) || (isset($formula_type) && $formula_type != 'Repair') || $formula_type == '') {
																						echo "display: none;";
																					} ?>">
								<a class="btn waves-effect waves-light gradient-45deg-amber-amber modal-trigger" href="#repair_type_add_modal">New Repair Type</a>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m6 s12">
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn cyan waves-effect waves-light right custom_btn_size" type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include("sub_files/add_repair_type_modal.php") ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<?php include("sub_files/add_repair_type_js_code.php") ?>
<!-- END: Page Main-->