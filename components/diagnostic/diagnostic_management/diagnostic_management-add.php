<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1) {
	$stock_id 			= 1369;
	$finale_condition 	= "54";
	$custom_product_id 	= "IPAD6-32C-" . date("YmdHis");
	$package_id1 		= "6";
	$package_id2 		= "1";
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
$title_heading 			= "Diagnostic";
$button_val 			= "Save";
$title_heading2			= "Product Diagnostic";
$button_val2 			= "Add";
$total_parts 			= 3;

if ($cmd == 'edit' && isset($detail_id) && $detail_id > 0) {
	$sql_ee				= " SELECT  b.id, b.sub_location_name, b.sub_location_type, 
									GROUP_CONCAT(DISTINCT CONCAT( '', COALESCE(d.first_name, ''), ' ', COALESCE(d.middle_name, ''), ' ', COALESCE(d.last_name, ''), ' (', COALESCE(d.username, ''), ')') ) AS task_user_details,
									c.bin_user_id
							FROM warehouse_sub_locations b 
							INNER JOIN users_bin_for_diagnostic c ON b.id = c.location_id
							INNER JOIN users d ON d.id = c.bin_user_id
							WHERE c.id = '" . $detail_id . "' "; //echo $sql_ee;
	$result_ee			= $db->query($conn, $sql_ee);
	$row_ee				= $db->fetch($result_ee);
	$sub_location_name	= $row_ee[0]['sub_location_name'];
	$sub_location_type	= $row_ee[0]['sub_location_type'];
	$bin_user_id		= $row_ee[0]['bin_user_id'];
	$id					= $row_ee[0]['id'];
	if ($sub_location_type != "") {
		$sub_location_name .= "(" . ucwords(strtolower($sub_location_type)) . ")";
	}
	$task_user_details		= $row_ee[0]['task_user_details'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
// For Testing
$is_test = 0;
if ($is_test == 1) {
	echo "<br><br><br><br><br><br>";
}
if (isset($is_Submit2) && $is_Submit2 == 'Y') {

	$field_name = "phone_check_username";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "diagnostic_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		
		if (access("add_perm") == 0) {
			$error['msg'] = "You do not have add permissions.";
		} else {

			$diagnostic_date1 = null;
			if(isset($diagnostic_date) && $diagnostic_date != ''){
				$diagnostic_date1 	= convert_date_mysql_slash($diagnostic_date);
			}
			$diagnostic_date1 	= convert_date_mysql_slash($diagnostic_date);

			$sql_pd02 			= "	SELECT DISTINCT f.po_no, e.po_id
									FROM purchase_order_detail_receive a
									INNER JOIN purchase_order_detail e ON e.id = a.po_detail_id
									INNER JOIN purchase_orders f ON f.id = e.po_id
									WHERE a.sub_location_id = '". $id ."' ";
 			$result_pd02		= $db->query($conn, $sql_pd02);
			$count_pd02			= $db->counter($result_pd02);
			if($count_pd02 > 0){
				$row_pd02 = $db->fetch($result_pd02);
				foreach($row_pd02 as $data_pd02){
					$invoiceNo 	= $data_pd02['po_no'];
					$po_id 		= $data_pd02['po_id'];
					$limit		= 500;  // Optional, max 500 records
					$offset		= 1;  // Optional
					
					if ($_SERVER['HTTP_HOST'] == 'localhost' && $test_on_local == 1) {
						$invoiceNo 			= "19200";  // Optional
						$diagnostic_date1	= "2024-10-04";  // Filter by Date (optional)
					}

					$data = [
						'Apikey' 		=> $phoneCheck_apiKey,
						'Username' 		=> $phone_check_username,
						'Invoiceno' 	=> $invoiceNo,
						'Date' 			=> $diagnostic_date1,
						'limit' 		=> $limit,
						'offset' 		=> $offset
					];
					$imei_already = $phone_check_sku_codes = "";
					$k = $n = 0;
					$all_devices_info = v2_devices_call_phonecheck($data);
					// echo "<br><br><pre>";   print_r($all_devices_info['imei']); die;
					if (isset($all_devices_info['imei']) && sizeof($all_devices_info['imei']) > 0) {
						$m = 1;
						foreach ($all_devices_info['imei'] as $data) {
							// $data = "DMPDQNJ0Q1GC";
							if ($data != "" && $data != null) {
								$sql_pd01_4		= "	SELECT  a.*
													FROM phone_check_api_data a 
													WHERE a.enabled = 1 
													AND a.imei_no = '" . $data . "'
													ORDER BY a.id DESC LIMIT 1";
								$result_pd01_4	= $db->query($conn, $sql_pd01_4);
								$count_pd01_4	= $db->counter($result_pd01_4);
								if ($count_pd01_4 == 0) {
									$model_name = $model_no = $make_name = $carrier_name = $color_name = $battery = $body_grade = $lcd_grade = $digitizer_grade = $ram = $memory = $defectsCode = $lcd_grade = $lcd_grade = $lcd_grade = $overall_grade = $sku_code = "";
									$device_detail_array 	= getinfo_phonecheck_imie($data);
									// echo "<br><br><pre>";   print_r($device_detail_array);
									$jsonData2				= json_encode($device_detail_array);
									if ($jsonData2 != '[]' && $jsonData2 != 'null' && $jsonData2 != null) {
										$insert_bin_and_po_id_fields 	= "bin_id, po_id, ";
										$insert_bin_and_po_id_values 	= "'".$id."', '".$po_id."', ";
										$serial_no_barcode_diagnostic 	= $data;
										include("components/purchase/purchase_orders/process_phonecheck_response.php");
									} 
								}
							}
						}
					}
					if (!isset($all_devices_info['imei']) || (isset($all_devices_info['imei']) && sizeof($all_devices_info['imei']) == 0)) {
						$error['msg'] = "No Serial# is avaible again this invoice# in the date.";
					}
					if ($k > 0) {
						$msg['msg_success'] = "Total " . $k . " Serial# have been updated successfully.";
						// $serial_no_manual	= $sub_location_id_barcode = "";
					} 
				}
			}
		}
	}
}

if(isset($is_Submit2_preview) && $is_Submit2_preview == 'Y'){
	if (empty($error)) {
		if (access("add_perm") == 0) {
			$error['msg'] = "You do not have add permissions.";
		} else {
			$i = 0;
			foreach($bulkserialNo as $data){

				$sql_pd01_4 		= "	SELECT  a.*
										FROM phone_check_api_data a 
										WHERE a.enabled = 1 
										AND a.imei_no = '" . $data . "'
										ORDER BY a.id DESC LIMIT 1";
				$result_pd01_4	= $db->query($conn, $sql_pd01_4);
				$count_pd01_4	= $db->counter($result_pd01_4);
				if ($count_pd01_4 > 0) {
					$row_pd01_4					= $db->fetch($result_pd01_4);
 					$phone_check_product_id		= $product_ids[$i]; 
 					$po_id						= $row_pd01_4[0]['po_id']; 
 					$phone_check_api_data_id	= $row_pd01_4[0]['id'];     

					if($phone_check_product_id != ""){
						$sql_pd01 		= "	SELECT a.*, c.product_desc, c.product_uniqueid
											FROM purchase_order_detail a 
											INNER JOIN purchase_orders b ON b.id = a.po_id
											INNER JOIN products c ON c.id = a.product_id
											WHERE 1=1 
											AND a.po_id = '" . $po_id . "' 
											AND c.product_uniqueid = '" . $phone_check_product_id . "'  ";
						$result_pd01	= $db->query($conn, $sql_pd01);
						$count_pd01		= $db->counter($result_pd01);
						if ($count_pd01 > 0) {
							$row_pd01						= $db->fetch($result_pd01);
							$diagnostic_fetch_product_id 	= $row_pd01[0]['id'];
							
							$sql_pd01 		= "	SELECT a.* 
												FROM purchase_order_detail_receive a 
												WHERE a.enabled = 1  
												AND a.serial_no_barcode	= '" . $data . "' ";
							$result_pd01	= $db->query($conn, $sql_pd01);
							$count_pd01		= $db->counter($result_pd01);
							if ($count_pd01 == 0) {
								$sql_pd01 		= "	SELECT a.* 
													FROM purchase_order_detail_receive a 
													WHERE a.enabled = 1 
													AND a.po_detail_id = '" . $diagnostic_fetch_product_id . "' 
													AND (a.serial_no_barcode IS NULL OR a.serial_no_barcode = '')
													LIMIT 1";
								$result_pd01	= $db->query($conn, $sql_pd01);
								$count_pd01		= $db->counter($result_pd01);
								if ($count_pd01 > 0) {
									$row_pd01		= $db->fetch($result_pd01);
									$receive_id_2 	= $row_pd01[0]['id'];
									$sql_c_up = "UPDATE  purchase_order_detail_receive SET 		serial_no_barcode					= '" . $data . "', 
																								is_diagnost							= '1',
																								is_import_diagnostic_data			= '1',
																								diagnose_by_user					= '" . $_SESSION['username'] . "',
																								diagnose_by_user_id					= '" . $_SESSION['user_id'] . "',
																								diagnose_timezone					= '" . $timezone . "',
																								diagnose_date						= '" . $add_date . "',
																								diagnose_ip							= '" . $add_ip . "'
											WHERE id = '" . $receive_id_2 . "' ";
									$ok = $db->query($conn, $sql_c_up);
									if ($ok) {
										update_po_detail_status($db, $conn, $diagnostic_fetch_product_id, $diagnost_status_dynamic);
										update_po_status($db, $conn, $id, $diagnost_status_dynamic);
									}
								}
								$m++;
							} 
						}
					} 
				}
				$i++;
			} 
		}
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
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy custom_margin_card_table_top custom_margin_card_table_bottom">
				<div class="card-content custom_padding_card_content_table_top">
					<h4 class="card-title">Detail</h4><br>
					<div class="row">
						<?php
						$field_name     = "sub_location_name";
						$field_label     = "Location / Bin";
						?>
						<div class="input-field col m2 s12">
							<i class="material-icons prefix">description</i>
							<input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
																										echo ${$field_name};
																									} ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
																																echo ${$field_name . "_valid"};
																															} ?>">
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
						$field_name     = "task_user_details";
						$field_label     = "Task User Detail";
						?>
						<div class="input-field col m5 s12">
							<i class="material-icons prefix">description</i>
							<input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
																										echo ${$field_name};
																									} ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
																																echo ${$field_name . "_valid"};
																															} ?>">
							<label for="<?= $field_name; ?>">
								<?= $field_label; ?>
								<span class="color-red"><?php
														if (isset($error[$field_name])) {
															echo $error[$field_name];
														} ?>
								</span>
							</label>
						</div>

						<div class="input-field col m5 s12">
							<?php
							$entry_type = "process";  ?>
							<a class="btn gradient-45deg-light-blue-cyan timer_<?= $entry_type; ?>" title="Timer" href="javascript:void(0)" id="timer_<?= $entry_type; ?>_<?= $id ?>"
								<?php
								if (
									!isset($_SESSION['is_start']) ||
									!isset($_SESSION[$entry_type]) ||
									(isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] != $entry_type)
								) { ?> style="display: none;" <?php } ?>>00:00:00 </a>
							<a class="btn gradient-45deg-green-teal startButton_<?= $entry_type; ?>" title="Start <?= $entry_type; ?>" href="javascript:void(0)" id="startButton_<?= $entry_type; ?>_<?= $id ?>" onclick="startTimer(<?= $id ?>, '<?= $entry_type ?>')" style="<?php
																																																																				if ((
																																																																					isset($_SESSION['is_start']) && $_SESSION['is_start'] == 1) && (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] == $entry_type)) {
																																																																					echo "display: none;";
																																																																				} ?>">
								Start
							</a> &nbsp;
							<a class="btn gradient-45deg-red-pink stopButton_<?= $entry_type; ?>" title="Stop <?= $entry_type; ?>" href="javascript:void(0)" id="stopButton_<?= $entry_type; ?>_<?= $id ?>" onclick="stopTimer(<?= $id ?>, '<?= $entry_type ?>' )" style=" <?php
																																																																			if (!isset($_SESSION['is_start']) || !isset($_SESSION[$entry_type])) {
																																																																				echo "display: none; ";
																																																																			} else if (isset($_SESSION['is_start']) && $_SESSION['is_start'] != 1 && isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] !=  $entry_type || (isset($_SESSION['p_is_paused']) && $_SESSION['p_is_paused'] == '1')) {
																																																																				echo "display: none;";
																																																																			} ?> ">
								Stop
							</a>&nbsp;
							<a class="btn gradient-45deg-amber-amber pauseButton_<?= $entry_type; ?>" title="Pause Timer" href="javascript:void(0)" id="pauseButton_<?= $entry_type; ?>_<?= $id ?>" onclick="pauseTimer(<?= $id ?>, '<?= $entry_type ?>')" style="<?php
																																																																	if (!isset($_SESSION['is_start']) || !isset($_SESSION[$entry_type])) {
																																																																		echo "display: none; ";
																																																																	} else if (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] ==  $entry_type && (isset($_SESSION['p_is_paused']) && $_SESSION['p_is_paused'] == '1')) {
																																																																		echo "display: none;";
																																																																	} ?> ">
								Pause
							</a>&nbsp;
							<a class="btn gradient-45deg-green-teal resumeButton_<?= $entry_type; ?>" title="Resume <?= $entry_type; ?>" href="javascript:void(0)" id="resumeButton_<?= $entry_type; ?>_<?= $id ?>" onclick="resumeTimer(<?= $id ?>, '<?= $entry_type ?>')" style="<?php
																																																																					if (!isset($_SESSION['p_is_paused']) || (isset($_SESSION['p_is_paused']) && $_SESSION['p_is_paused'] == '0') && (!isset($_SESSION[$entry_type]) || (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] == $entry_type))) {
																																																																						echo "display: none;";
																																																																					} ?> ">Resume <?php //echo $_SESSION[$entry_type]; 
																																																																									?>
							</a>&nbsp;
							<input type="hidden" name="p_total_pause_duration" id="p_total_pause_duration" value="0">
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		if (isset($cmd) && $cmd == 'edit') { ?>
			<div class="col s12 m12 l12">
				<div id="Form-advance2" class="card card card-default scrollspy custom_margin_card_table_top custom_margin_card_table_bottom">
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
						<h4 class="card-title">Fetch Data From PhoneCheck</h4><br>
						<form method="post" autocomplete="off" action="">
							<input type="hidden" name="is_Submit2" value="Y" />
							<div class="row">
								<div class="input-field col m4 s12">
									<?php
									$field_name     = "phone_check_username";
									$field_label    = "PhoneCheck User";
									$sql            = " SELECT a.*
														FROM phone_check_users a 
														WHERE 1=1 
														AND a.enabled = '1' 
														ORDER BY a.username"; // echo $sql; 
									$result_log2    = $db->query($conn, $sql);
									$count_r2       = $db->counter($result_log2); ?>
									<i class="material-icons prefix pt-1">description</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible  validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
											<?php
											if ($count_r2 > 1) { ?>
												<option value="">Select</option>
												<?php
											}
											if ($count_r2 > 0) {
												$row_r2    = $db->fetch($result_log2);
												foreach ($row_r2 as $data_r2) { ?>
													<option value="<?php echo $data_r2['username']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data_r2['username']) { ?> selected="selected" <?php } ?>>
														<?php echo $data_r2['username'];  ?><?php  if($data_r2['full_name'] !=""){ echo " (".$data_r2['full_name'].")"; }  ?>
													</option>
											<?php 
												}
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
									$field_name     = "diagnostic_date";
									$field_id       = $field_name;
									$field_label    = "PhoneCheck Diagnostic Date ";
									?>
									<i class="material-icons prefix">date_range</i>
									<input id="<?= $field_id; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} else {
																													echo date('m/d/Y');
																												} ?>" class="datepicker validate ">
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
								<div class="input-field col m4 s12"></div>
							</div>
							<div class="row">
								<div class="input-field col m3 s12"></div>
								<div class="input-field col m4 s12">
									<?php if (($cmd2 == 'add' && access("add_perm") == 1)  || ($cmd2 == 'edit' && access("edit_perm") == 1)) { ?>
										<button class="btn cyan waves-effect waves-light right" type="submit" name="action" value="update_info">Update Info
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
			<?php   
			$sql_preview = "SELECT a.*, f.po_no
							FROM phone_check_api_data a
							INNER JOIN purchase_orders f ON f.id = a.po_id
 							WHERE a.bin_id = '".$id."' ";
			$result_preview	= $db->query($conn, $sql_preview);
			$count_preview		= $db->counter($result_preview);
			if($count_preview > 0){
				$row_preview = $db->fetch($result_preview); ?>
				<div class="col s12 m12 l12">
					<div id="Form-advance2" class="card card card-default scrollspy custom_margin_card_table_top custom_margin_card_table_bottom">
						<div class="card-content custom_padding_card_content_table_top">
						<h4 class="card-title">Preview Fetched Data</h4><br>
							<form method="post" autocomplete="off" action="">
								<input type="hidden" name="is_Submit2_preview" value="Y" />
								<div class="row">
									<table id="page-length-option1" class="display bordered striped addproducttable">
										<thead>
											<tr>
												<th style="text-align: center;">
                                                    <label>
                                                        <input type="checkbox" id="all_checked" class="filled-in" name="all_checked" value="1" <?php if (isset($all_checked) && $all_checked == '1') {
                                                                                                                                                    echo "checked";
                                                                                                                                                } ?> />
                                                        <span>Serial#</span>
                                                    </label>
                                                </th>
												<th>Product ID</th>
												<th>PO#</th>
											</tr>
										</thead>
										<tbody>
										<?php 
										foreach ($row_preview as $data) { 
											$phone_check_product_id = $data['sku_code'];
											$po_id 					= $data['po_id'];
											$bulkserialNo[] 		= $data['imei_no'];?>
											
											<tr> 
												<td style="width:150px;">
													<?php
													if (access("delete_perm") == 1) { ?>
														<label style="margin-left: 25px;">
															<input type="checkbox" name="bulkserialNo[]" id="bulkserialNo[]" value="<?= $data['imei_no']; ?>" <?php if (isset($data['imei_no']) && in_array($data['imei_no'], $bulkserialNo)) {
																																						echo "checked";
																																					} ?> class="checkbox filled-in" />
															<span><?php echo $data['imei_no'];?></span>
														</label>
													<?php } ?>
												</td>
												<td>
													<?php 
													$sql_pd01 		= "	SELECT a.id 
																		FROM purchase_order_detail a 
																		INNER JOIN purchase_orders b ON b.id = a.po_id
																		INNER JOIN products c ON c.id = a.product_id
																		WHERE 1=1 
																		AND a.po_id = '" . $po_id . "' 
																		AND c.product_uniqueid = '" . $phone_check_product_id . "'  ";
													$result_pd01	= $db->query($conn, $sql_pd01);
													$count_pd01		= $db->counter($result_pd01);
													if ($count_pd01 > 0) {?>
															<input type="text" readonly class="green-text" name="product_ids[]" id="product_ids" value="<?php echo $phone_check_product_id;?>">
												<?php }
													else{
														?>
														<input type="text" class="red-text" name="product_ids[]" id="product_ids" value="<?php echo $phone_check_product_id;?>">
														<?php
													}
													?>
												</td>
												<td><?php echo $data['po_no'];?></td>
											</tr>
										<?php } ?>
										</tbody>
									</table>
								</div><br><br>
								<div class="row">
									<div class="input-field col m3 s12">
										<i class="material-icons prefix">question_answer</i>
										<div class="select2div">
											<?php
											$field_name     = "process_bin_id";
											$field_label    = "Bin/Location";

											$sql1           = " SELECT b.id,b.sub_location_name, b.sub_location_type
																FROM  warehouse_sub_locations b";
											$result1        = $db->query($conn, $sql1);
											$count1         = $db->counter($result1);
											?>
											<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																												echo ${$field_name . "_valid"};
																																											} ?>">
												<option value="">Select</option>
												<?php
												if ($count1 > 0) {
													$row1    = $db->fetch($result1);
													foreach ($row1 as $data2) { ?>
														<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php
																																																			echo $data2['sub_location_name'];
																																																			if ($data2['sub_location_type'] != "") {
																																																				echo "(" . ucwords(strtolower($data2['sub_location_type'])) . ")";
																																																			} ?></option>
												<?php }
												} ?>
											</select>
											<label for="<?= $field_name; ?>">
												<?= $field_label; ?>
												<span class="color-red">*<?php
																		if (isset($error[$field_name])) {
																			echo $error[$field_name];
																		} ?>
												</span>
											</label>
										</div>
									</div>
									<div class="input-field col m2 s12">
										<?php if (($cmd2 == 'add' && access("add_perm") == 1)  || ($cmd2 == 'edit' && access("edit_perm") == 1)) { ?>
											<button class="btn cyan waves-effect waves-light right" type="submit" name="action" value="update_info">Process Diagnostic
												<i class="material-icons right">send</i>
											</button>
										<?php } ?>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			<?php } 
		}?>
	</div>
	<?php include("sub_files/add_product_modal.php") ?>
	<?php include("sub_files/add_vender_modal.php") ?>
</div>
<br><br><br><br>
<!-- END: Page Main-->
<!-- END: Page Main-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<?php include("sub_files/add_product_js_code.php") ?>
<?php include("sub_files/add_vender_js_code.php") ?>