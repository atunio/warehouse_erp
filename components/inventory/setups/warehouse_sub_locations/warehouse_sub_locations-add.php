<?php
if (!isset($module)) {
	require_once('../../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
 	$sub_location_name	= "A".date('His');
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
if ($cmd == 'edit' && isset($id)) {

	$sql_ee				= "SELECT a.* FROM warehouse_sub_locations a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee			= $db->query($conn, $sql_ee);
	$row_ee				= $db->fetch($result_ee);
	$sub_location_name	= $row_ee[0]['sub_location_name'];
	$warehouse_id		= $row_ee[0]['warehouse_id'];
	$purpose			= $row_ee[0]['purpose'];
	$sub_location_type	= $row_ee[0]['sub_location_type'];
	$is_mobile			= $row_ee[0]['is_mobile'];
	$total_capacity		= $row_ee[0]['total_capacity'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {

	$field_name = "warehouse_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "sub_location_name";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "total_capacity";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	if (empty($error)) {
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM warehouse_sub_locations a  
								WHERE a.warehouse_id	= '" . $warehouse_id . "' 
								AND a.sub_location_name	= '" . $sub_location_name . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".warehouse_sub_locations(warehouse_id, sub_location_name, total_capacity, purpose, sub_location_type, is_mobile, add_date, add_by, add_ip)
							VALUES('" . $warehouse_id . "', '" . $sub_location_name . "', '" . $total_capacity . "', '" . $purpose . "', '" . $sub_location_type . "', '" . $is_mobile . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
						$sub_location_name = $warehouse_id = $total_capacity = $purpose =  $sub_location_type =  $is_mobile = "";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$sql_c_up = "UPDATE warehouse_sub_locations SET enabled			= '1', 
																	update_date		= '" . $add_date . "',
																	update_by		= '" . $_SESSION['username'] . "',
																	update_ip		= '" . $add_ip . "'
								WHERE warehouse_id	= '" . $warehouse_id . "' 
								AND sub_location_name	= '" . $sub_location_name . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
						//$sub_location_name = "";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM warehouse_sub_locations a  
								WHERE a.warehouse_id	= '" . $warehouse_id . "' 
								AND a.sub_location_name	= '" . $sub_location_name . "' 
								AND a.id != '" . $id . "'";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE warehouse_sub_locations SET warehouse_id		= '" . $warehouse_id . "',
																	sub_location_name	= '" . $sub_location_name . "', 
																	total_capacity		= '" . $total_capacity . "', 
																	purpose				= '" . $purpose . "', 
																	sub_location_type	= '" . $sub_location_type . "', 
																	is_mobile			= '" . $is_mobile . "', 
																	
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
					$sql_c_up = "UPDATE warehouse_sub_locations SET enabled		= '1', 
																update_date		= '" . $add_date . "',
																update_by		= '" . $_SESSION['username'] . "',
																update_ip		= '" . $add_ip . "'
								WHERE warehouse_id	= '" . $warehouse_id . "' 
								AND sub_location_name	= '" . $sub_location_name . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$sql_c_up = "UPDATE warehouse_sub_locations SET enabled		= '0', 
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
								<?php }?>
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
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<div class="row">

							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "warehouse_id";
								$field_label 	= "Warehouse";
								$sql1 			= "SELECT * FROM warehouses WHERE enabled = 1 ORDER BY warehouse_name ";
								$result1 		= $db->query($conn, $sql1);
								$count1 		= $db->counter($result1);
								?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1	= $db->fetch($result1);
											foreach ($row1 as $data2) { ?>
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['warehouse_name']; ?></option>
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

							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "sub_location_name";
								$field_label 	= "Sub Location";
								?>
								<i class="material-icons prefix">description</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
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
								$field_name 	= "total_capacity";
								$field_label 	= "Capacity";
								?>
								<i class="material-icons prefix">description</i>
								<input type="number" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
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
							<div class="input-field col m12 s12">
							</div>
						</div>
						<div class="row">

							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "purpose";
								$field_label 	= "Purpose";
								?>
								<i class="material-icons prefix">description</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "sub_location_type";
								$field_label 	= "Type";
								?>
								<i class="material-icons prefix">description</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "is_mobile";
								$field_label 	= "Mobile";
								?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
																														echo ${$field_name . "_valid"};
																													} ?>">
										<option value="">Select</option>
										<option value="Yes" <?php if (isset(${$field_name}) && (${$field_name} == 'Yes' || ${$field_name} == 'yes')) { ?> selected="selected" <?php } ?>>Yes</option>
										<option value="No" <?php if (isset(${$field_name}) && (${$field_name} == 'No'  || ${$field_name} == 'no')) { ?> selected="selected" <?php } ?>>No</option>
									</select>
									<label for="<?= $field_name; ?>">
										<?= $field_label; ?>
										<span class="color-red"> <?php
																	if (isset($error[$field_name])) {
																		echo $error[$field_name];
																	} ?>
										</span>
									</label>
								</div>
							</div>
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