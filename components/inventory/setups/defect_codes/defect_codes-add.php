<?php
if (!isset($module)) {
	require_once('../../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$defect_code_name	= "" . date('Ymd-His');
	$defect_code			= array('Screen Issue', 'Keypad Issue');
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if ($cmd == 'edit') {
	$title_heading = "Update Defect Code";
	$button_val = "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Defect Code";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {

	$sql_ee				= "SELECT a.* FROM defect_codes a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee			= $db->query($conn, $sql_ee);
	$row_ee				= $db->fetch($result_ee);
	$defect_code_name	= $row_ee[0]['defect_code_name'];
	$defect_code		= explode(",", $row_ee[0]['defect_code']);
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	if (isset($defect_code_name) && $defect_code_name == "") {
		$error['defect_code_name']	= "Required";
		$vender_name_valid 		= "invalid";
	}
	if (empty($error)) {

		$all_defect_codes = "";
		if (isset($defect_code) && is_array($defect_code)) {
			$filtered_defect_code = array_filter($defect_code, function ($value) {
				return !empty($value); // Remove empty values
			});
			if (!empty($filtered_defect_code)) {
				$all_defect_codes = implode(",", $filtered_defect_code);
			}
		}
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM defect_codes a  
								WHERE a.defect_code_name	= '" . $defect_code_name . "' 
								AND a.defect_code			= '" . $all_defect_codes . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".defect_codes(subscriber_users_id, defect_code_name, defect_code, add_date, add_by, add_ip)
							VALUES('" . $subscriber_users_id . "', '" . $defect_code_name . "', '" . $all_defect_codes . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
						$defect_code_name = "";
						unset($defect_code);
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$sql_c_up = "UPDATE defect_codes 	SET enabled		= '1', 
														update_date		= '" . $add_date . "',
														update_by		= '" . $_SESSION['username'] . "',
														update_ip		= '" . $add_ip . "'
								WHERE defect_code_name	= '" . $defect_code_name . "'
								 AND a.defect_code 		= '" . $all_defect_codes . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
						$category_name = $category_type = "";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM defect_codes a  
								WHERE a.defect_code_name	= '" . $defect_code_name . "' 
								AND a.defect_code			= '" . $all_defect_codes . "'
								AND a.id 				   != '" . $id . "'";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE defect_codes SET 	defect_code_name	= '" . $defect_code_name . "', 
															defect_code			= '" . $all_defect_codes . "', 
															update_date			= '" . $add_date . "',
															update_by			= '" . $_SESSION['username'] . "',
															update_ip			= '" . $add_ip . "'
								WHERE id = '" . $id . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
					} else {
						$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$sql_c_up = "UPDATE defect_codes SET enabled		= '1', 
														update_date		= '" . $add_date . "',
														update_by		= '" . $_SESSION['username'] . "',
														update_ip		= '" . $add_ip . "'
								WHERE defect_code_name	= '" . $defect_code_name . "'
								 AND a.defect_code 		= '" . $all_defect_codes . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$sql_c_up = "UPDATE defect_codes SET enabled		= '0', 
															update_date		= '" . $add_date . "',
															update_by		= '" . $_SESSION['username'] . "',
															update_ip		= '" . $add_ip . "'
									WHERE id = '" . $id . "' ";
						$db->query($conn, $sql_c_up);
						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
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
					<h4 class="card-title">Detail Form</h4><br>
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<div class="row">
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "defect_code_name";
								$field_label 	= "Defect Code Name";
								$field_id 		= "defect_code_name";
								?>
								<i class="material-icons prefix">description</i>
								<input type="text" id="<?= $field_id; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																												echo ${$field_name};
																											} ?>">
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

						<div class="row">
							<?php
							$max = 0;
							if (isset($defect_code)) {
								// Filter out empty values from the array
								$filtered = array_filter($defect_code, function ($value) {
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
								<div class="input-field col m2 s12 defect_code_input_<?= $i2; ?>" style="<?= $style; ?>">
									<?php
									$field_name     = "defect_code";
									$field_id       = $field_name . "_" . $i2;
									$field_label    = "Defect Code " . $i2;
									?>
									<i class="material-icons prefix">description</i>
									<input id="<?= $field_id; ?>" type="text" name="<?= $field_name; ?>[]" value="<?php if (isset($defect_code[$i])) {
																														echo trim($defect_code[$i]);
																													} ?>" class="validate ">
									<label for="<?= $field_id; ?>">
										<?= $field_label; ?>
										<span class="color-red">* <?php
																	if (isset($error[$field_name . "_" . $i2])) {
																		echo $error[$field_name . "_" . $i2];
																	} ?>
										</span>
									</label>
								</div>
								<div style="<?= $style; ?>" class=" input-field col m1 s12 button_div_defect_code" id="button_div_defect_code_<?= $i2; ?>">
									<a href="javascript:void(0)" style="<?= $style2; ?> font-size: 30px;" class="add_<?= $field_name; ?> add_<?= $field_name; ?>_<?= $i2; ?>" id="add_<?= $field_name; ?>^<?= $i2; ?>">+</a>
									&nbsp;
									<a href="javascript:void(0)" style="<?= $style; ?> font-size: 30px;" class="minus_<?= $field_name; ?> minus_<?= $field_name; ?>_<?= $i2; ?>" id="minus_<?= $field_name; ?>^<?= $i2; ?>">-</a>
								</div>
							<?php } ?>
						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
				<?php //include('sub_files/right_sidebar.php');
				?>
			</div>
		</div>


	</div><br><br><br><br>
	<!-- END: Page Main-->