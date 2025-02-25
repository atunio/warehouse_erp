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
	$sql_ee				= "SELECT a.* FROM stock_transfer a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee			= $db->query($conn, $sql_ee);
	$row_ee				= $db->fetch($result_ee);
	$stock_id			= $row_ee[0]['stock_id'];
	$tranfer_loction_id	= $row_ee[0]['transfer_to_location_id']; 
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {

	$field_name = "stock_id";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "tranfer_loction_id";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	if (empty($error)) {
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else { 
				$sub_location = 0;
				$sql_dup1		= " SELECT a.* FROM product_stock a WHERE a.id = '" . $stock_id . "' ";
				$result_dup1	= $db->query($conn, $sql_dup1);
				$count_dup1		= $db->counter($result_dup1);
				if($count_dup1 > 0){
					$row_dup1    = $db->fetch($result_dup1);
					$sub_location = $row_dup1[0]['sub_location'];
				}
				$sql_dup	= " SELECT a.* FROM product_stock a  
								WHERE a.id	= '" . $stock_id . "' 
								AND a.sub_location	= '" . $tranfer_loction_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".stock_transfer(stock_id, transfer_from_location_id, transfer_to_location_id, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
							 VALUES('" . $stock_id . "', '" . $sub_location . "', '" . $tranfer_loction_id . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id 			= mysqli_insert_id($conn);
						$transfer_no	= "TR" . $id;

						$sql6 = "UPDATE stock_transfer SET transfer_no = '" . $transfer_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						$sql_c_up = "UPDATE product_stock	SET sub_location				= '" . $tranfer_loction_id . "', 
																	
																update_date					= '" . $add_date . "',
																update_by					= '" . $_SESSION['username'] . "',
																update_by_user_id			= '" . $_SESSION['user_id'] . "',
																update_ip					= '" . $add_ip . "',
																update_timezone				= '" . $timezone . "',
																update_from_module_id		= '" . $module_id . "'
									WHERE id = '" . $stock_id . "' ";
 						$db->query($conn, $sql_c_up);

						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
						$tranfer_loction_id = $stock_id = "";
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
				$sql_dup	= " SELECT a.* FROM product_stock a  
								WHERE a.id	= '" . $stock_id . "' 
								AND a.sub_location	= '" . $tranfer_loction_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_dup	= " SELECT a.* FROM stock_transfer a  
									WHERE a.stock_id	= '" . $stock_id . "' 
									AND a.transfer_from_location_id	= '" . $tranfer_loction_id . "'
									AND a.id	= '" . $id . "'  "; //echo $sql_dup;
					$result_dup	= $db->query($conn, $sql_dup);
					$count_dup	= $db->counter($result_dup);
					if ($count_dup == 0) {
						$sql_c_up = "UPDATE stock_transfer SET transfer_to_location_id	= '" . $tranfer_loction_id . "', 
																			
																update_date					= '" . $add_date . "',
																update_by					= '" . $_SESSION['username'] . "',
																update_by_user_id			= '" . $_SESSION['user_id'] . "',
																update_ip					= '" . $add_ip . "',
																update_timezone				= '" . $timezone . "',
																update_from_module_id		= '" . $module_id . "'
									WHERE id = '" . $id . "' ";
						$ok = $db->query($conn, $sql_c_up);
						if ($ok) {

							$sql_c_up = "UPDATE product_stock	SET sub_location				= '" . $tranfer_loction_id . "', 
																		
																	update_date					= '" . $add_date . "',
																	update_by					= '" . $_SESSION['username'] . "',
																	update_by_user_id			= '" . $_SESSION['user_id'] . "',
																	update_ip					= '" . $add_ip . "',
																	update_timezone				= '" . $timezone . "',
																	update_from_module_id		= '" . $module_id . "'
										WHERE id = '" . $stock_id . "' ";
							$db->query($conn, $sql_c_up);
							$msg['msg_success'] = "Record Updated Successfully.";
						} else {
							$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
						}
					} else {
						$error['msg'] = "This record is already exist.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
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
							<div class="input-field col m8 s12">
								<?php
								$field_name 	= "stock_id";
								$field_label 	= "Stock";
								$sql1 			= " SELECT DISTINCT a.id AS stock_id, a.serial_no, b.product_uniqueid, b.product_desc, c.category_name, c.category_type, d.sub_location_name,d.sub_location_type
													FROM product_stock a
													INNER JOIN products b ON b.id = a.product_id
													INNER JOIN product_categories c ON c.id = b.product_category
													INNER JOIN warehouse_sub_locations d ON d.id = a.sub_location";
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
												<option value="<?php echo $data2['stock_id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['stock_id']) { ?> selected="selected" <?php } ?>>
													<?php echo $data2['product_uniqueid']; ?> 
													<?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>), 
													Serial#: <?php echo $data2['serial_no']; ?>, 
													Location: <?php echo $data2['sub_location_name']; if(isset($data2['sub_location_type'])) echo " (".$data2['sub_location_type'].") "; ?> 
												</option>
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
								$field_name 	= "tranfer_loction_id";
								$field_label 	= "Tranfer Location";
								$sql1 			= "SELECT * FROM warehouse_sub_locations WHERE enabled = 1 ORDER BY sub_location_name ";
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
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['sub_location_name']; if(isset($data2['sub_location_type']) && $data2['sub_location_type'] != '') echo " ( ".$data2['sub_location_type']." ) "; ?></option>
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
							<div class="input-field col m5 s12 "></div>
							<div class="input-field col m2 s12 text-center">
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
								<?php } ?>
							</div>
							<div class="input-field col m5 s12 "></div>
						</div>
					</form>
				</div>
				<?php //include('sub_files/right_sidebar.php');
				?>
			</div>
		</div>


	</div><br><br><br><br>
	<!-- END: Page Main-->