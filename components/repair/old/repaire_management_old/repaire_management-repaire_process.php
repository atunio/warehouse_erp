<?php
if (!isset($module)) {
	require_once('../../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if ($cmd == 'edit') {
	$title_heading = "Update Repair Detail";
	$button_val = "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Repair Detail";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee					= "	SELECT b.*, d.po_no, d.po_date, e.vender_name, d.vender_invoice_no, 
										f.status_name, g.product_desc, g.product_uniqueid,  
										h.category_name, b.overall_grade, b.price, a.repaire_status_id, a.grade_after_repaire,
										a.repair_cost, a.parts_stock_ids
								FROM purchase_order_detail_receive_rma a
								INNER JOIN purchase_order_detail_receive b ON b.id = a.receive_id
								INNER JOIN purchase_order_detail c ON c.id = b.po_detail_id
								INNER JOIN purchase_orders d ON d.id = c.po_id
								LEFT JOIN venders e ON e.id = d.vender_id
								INNER JOIN inventory_status f ON f.id = a.status_id
								LEFT JOIN products g ON g.id = c.product_id
								LEFT JOIN product_categories h ON h.id = g.product_category
								WHERE  c.po_id = '" . $id . "' 
								AND a.id = '" . $detail_id . "'
								ORDER BY a.id DESC "; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$receive_id				= $row_ee[0]['id'];
	$base_product_id		= $row_ee[0]['base_product_id'];
	$sub_product_id			= $row_ee[0]['sub_product_id'];
	$serial_no_barcode		= $row_ee[0]['serial_no_barcode'];
	$grade_after_repaire	= $row_ee[0]['grade_after_repaire'];
	$status_name			= $row_ee[0]['status_name'];
	$po_no					= $row_ee[0]['po_no'];
	$po_date				= dateformat2($row_ee[0]['po_date']);
	$vender_name			= $row_ee[0]['vender_name'];
	$vender_invoice_no		= $row_ee[0]['vender_invoice_no'];
	$location_id			= $row_ee[0]['sub_location_id_after_diagnostic'];
	$repaire_status_id		= $row_ee[0]['repaire_status_id'];
	$repair_cost			= $row_ee[0]['repair_cost'];
	if ($row_ee[0]['parts_stock_ids'] != "" && $row_ee[0]['parts_stock_ids'] != null) {
		$part_id			= explode(",", $row_ee[0]['parts_stock_ids']);
	}
}
if ($_SERVER['HTTP_HOST'] == HTTP_HOST_IP && $test_on_local == 1) {
	if (($repaire_status_id == "" || $repaire_status_id == "0")) {
		$repaire_status_id	= 5;
	}
	if (($grade_after_repaire == "" || $grade_after_repaire == "0")) {
		$grade_after_repaire = 'B';
	}
	if (($repair_cost == "" || $repair_cost == "0")) {
		$repair_cost = '20';
	}
	if (!isset($part_id) || (isset($part_id) &&  sizeof($part_id) == '0')) {
		$part_id	= array(1, 2);
	}
}

extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}

if (isset($is_Submit) && $is_Submit == 'Y') {
	$field_name = "location_id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error['location_id'] = "Required";
	}

	$field_name = "grade_after_repaire";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error[${$field_name}] = "Required";
	}
	$field_name = "repaire_status_id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error[${$field_name}] = "Required";
	}
	if (empty($error)) {
		if (po_permisions("RMA Repair") == 0) {
			$error['msg'] = "You do not have add permissions.";
		} else {

			$k 					= 0;
			$m 					= 0;
			$parts_cost 		= 0;
			$parts_stock_ids 	= "";
			if (isset($part_id) && sizeof($part_id) > 0) {
				$part_id = array_filter($part_id, function ($value) {
					return $value !== "";
				});
				$parts_stock_ids = implode(",", $part_id);
				foreach ($part_id as $part_id1) {
					if (isset($part_id1) && $part_id1 > 0) {
						$sql_pd1	= "	SELECT a.price FROM package_stock a  WHERE a.enabled = 1  AND a.total_stock > '" . $m . "' AND a.id = '" . $part_id1 . "' ";
						$result_pd1	= $db->query($conn, $sql_pd1);
						$count_pd1	= $db->counter($result_pd1);
						if ($count_pd1 > 0) {
							$row_pd1 = $db->fetch($result_pd1);
							$parts_cost += $row_pd1[0]['price'];
						}
					}
					$m++;
				}
			}
			if (!isset($repair_cost) && $repair_cost == "") {
				$repair_cost = 0;
			}
			$total_repair_cost = $parts_cost + $repair_cost;
			$sql_c_up = "UPDATE  purchase_order_detail_receive_rma 
										SET 
											repair_cost				= '" . $repair_cost . "',
											parts_cost				= '" . $parts_cost . "',
											total_repair_cost		= '" . $total_repair_cost . "',
											repaire_status_id		= '" . $repaire_status_id . "',
											grade_after_repaire		= '" . $grade_after_repaire . "',
											parts_stock_ids			= '" . $parts_stock_ids . "',
 											is_repaired 			= '1', 

											update_timezone			= '" . $timezone . "',
											update_date				= '" . $add_date . "',
											update_by				= '" . $_SESSION['username'] . "',
											update_by_user_id		= '" . $_SESSION['user_id'] . "',
											update_ip				= '" . $add_ip . "',
											update_from_module_id	= '" . $module_id . "'
						WHERE id = '" . $detail_id . "' 
						AND edit_lock = 0 ";
			$ok = $db->query($conn, $sql_c_up);
			if ($ok) {
				$sql_c_up = "UPDATE  purchase_order_detail_receive 
											SET
												sub_location_id_after_diagnostic	= '" . $location_id . "',

												update_timezone						= '" . $timezone . "',
												update_date							= '" . $add_date . "',
												update_by							= '" . $_SESSION['username'] . "',
												update_by_user_id					= '" . $_SESSION['user_id'] . "',
												update_ip							= '" . $add_ip . "',
												update_from_module_id				= '" . $module_id . "'
							WHERE id = '" . $receive_id . "' ";
				$ok = $db->query($conn, $sql_c_up);
				$msg['msg_success'] = "Record has been processed successfully.";
			}
		}
	} else {
		$error['msg'] = "Please check the error in form.";
	}
}
?>
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
							<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab2") ?>">List</a></li>
						</ol>
					</div>
					<div class="col s2 m6 l6">
						<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=profile&cmd=edit&id=" . $id . "&active_tab=tab2") ?>" data-target="dropdown1">
							Back to Repairs
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy">
				<div class="card-content">
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
							<div class="input-field col m4 s12"></div>
							<div class="input-field col m5 s12">
								<?php $entry_type = "repaire";  ?>
								<a class="btn gradient-45deg-light-blue-cyan timer_<?= $entry_type; ?>" title="Timer" href="javascript:void(0)" id="timer_<?= $entry_type; ?>_<?= $detail_id ?>"
									<?php
									if (
										!isset($_SESSION['is_start']) ||
										!isset($_SESSION[$entry_type]) ||
										(isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] != $entry_type)
									) { ?> style="display: none;" <?php } ?>>00:00:00</a>
								<a class="btn gradient-45deg-green-teal startButton_<?= $entry_type; ?>" title="Start <?= $entry_type; ?>" href="javascript:void(0)" id="startButton_<?= $entry_type; ?>_<?= $detail_id ?>" onclick="startTimer(<?= $detail_id ?>, '<?= $entry_type ?>')" style="<?php
																																																																									if ((
																																																																										isset($_SESSION['is_start']) && $_SESSION['is_start'] == 1) && (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] == $entry_type)) {
																																																																										echo "display: none;";
																																																																									} ?>">
									Start Repair
								</a> &nbsp;
								<a class="btn gradient-45deg-red-pink stopButton_<?= $entry_type; ?>" title="Stop <?= $entry_type; ?>" href="javascript:void(0)" id="stopButton_<?= $entry_type; ?>_<?= $detail_id ?>" onclick="stopTimer(<?= $detail_id ?>, '<?= $entry_type ?>' )" style="<?php
																																																																							if (!isset($_SESSION['is_start']) || !isset($_SESSION[$entry_type])) {
																																																																								echo "display: none; ";
																																																																							} else if (isset($_SESSION['is_start']) && $_SESSION['is_start'] != 1 && isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] !=  $entry_type) {
																																																																								echo "display: none;";
																																																																							} ?> ">
									Finish Repair
								</a>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m3 s12">
								<?php
								$field_name     = "po_no";
								$field_label    = "PO No";
								?>
								<i class="material-icons prefix">description</i>
								<input id="<?= $field_name; ?>" type="text" disabled value="<?php if (isset(${$field_name})) {
																								echo ${$field_name};
																							} ?>" class="validate">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
								</label>
							</div>
							<div class="input-field col m3 s12">
								<?php
								$field_name     = "po_date";
								$field_label    = "PO Date";
								?>
								<i class="material-icons prefix">description</i>
								<input id="<?= $field_name; ?>" type="text" disabled value="<?php if (isset(${$field_name})) {
																								echo ${$field_name};
																							} ?>" class="validate">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
								</label>
							</div>
							<div class="input-field col m3 s12">
								<?php
								$field_name     = "vender_name";
								$field_label    = "Vender";
								?>
								<i class="material-icons prefix">description</i>
								<input id="<?= $field_name; ?>" type="text" disabled value="<?php if (isset(${$field_name})) {
																								echo ${$field_name};
																							} ?>" class="validate">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
								</label>
							</div>
							<div class="input-field col m3 s12">
								<?php
								$field_name     = "vender_invoice_no";
								$field_label    = "Vendor Invoice#";
								?>
								<i class="material-icons prefix">description</i>
								<input id="<?= $field_name; ?>" type="text" disabled value="<?php if (isset(${$field_name})) {
																								echo ${$field_name};
																							} ?>" class="validate">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m3 s12">
								<?php
								$field_name     = "base_product_id";
								$field_label    = "Base Product ID";
								?>
								<i class="material-icons prefix">description</i>
								<input id="<?= $field_name; ?>" type="text" disabled value="<?php if (isset(${$field_name})) {
																								echo ${$field_name};
																							} ?>" class="validate">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
								</label>
							</div>

							<div class="input-field col m3 s12">
								<?php
								$field_name     = "sub_product_id";
								$field_label    = "Sub Product ID";
								?>
								<i class="material-icons prefix">description</i>
								<input id="<?= $field_name; ?>" type="text" disabled value="<?php if (isset(${$field_name})) {
																								echo ${$field_name};
																							} ?>" class="validate">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
								</label>
							</div>

							<div class="input-field col m3 s12">
								<?php
								$field_name     = "serial_no_barcode";
								$field_label    = "Serial No";
								?>
								<i class="material-icons prefix">description</i>
								<input id="<?= $field_name; ?>" type="text" disabled value="<?php if (isset(${$field_name})) {
																								echo ${$field_name};
																							} ?>" class="validate">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m3 s12">
								<?php
								$field_name         = "repaire_status_id";
								$field_label        = "Status after Repair";
								$sql1               = " SELECT * FROM inventory_status 
														WHERE enabled = 1 AND id IN(" . $repaire_status_ids . ") 
														ORDER BY status_name DESC ";
								$result1            = $db->query($conn, $sql1);
								$count1             = $db->counter($result1);
								?>
								<i class="material-icons prefix">question_answer</i>
								<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																												echo ${$field_name . "_valid"};
																											} ?>">
									<option value="">Select</option>
									<?php
									if ($count1 > 0) {
										$row1    = $db->fetch($result1);
										foreach ($row1 as $data2) { ?>
											<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['status_name']; ?> </option>
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
							<div class="input-field col m3 s12">
								<?php
								$field_name     = "repair_cost";
								$field_label    = "Repair Cost";
								?>
								<i class="material-icons prefix">attach_money</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>" class="twoDecimalNumber  validate <?php if (isset(${$field_name . "_valid"})) {
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
							<div class="input-field col m3 s12">
								<?php
								$field_name     = "grade_after_repaire";
								$field_label    = "Grade After Repair";
								?>
								<i class="material-icons prefix">description</i>
								<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																												echo ${$field_name . "_valid"};
																											} ?>">
									<option value="">Select</option>
									<option value="A" <?php if (isset(${$field_name}) && ${$field_name} == 'A') { ?> selected="selected" <?php } ?>> Grade: A</option>
									<option value="B" <?php if (isset(${$field_name}) && ${$field_name} == 'B') { ?> selected="selected" <?php } ?>> Grade: B</option>
									<option value="C" <?php if (isset(${$field_name}) && ${$field_name} == 'C') { ?> selected="selected" <?php } ?>> Grade: C</option>
									<option value="D" <?php if (isset(${$field_name}) && ${$field_name} == 'D') { ?> selected="selected" <?php } ?>> Grade: D</option>
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

							<div class="input-field col m3 s12">
								<?php
								$field_name         = "location_id";
								$field_label        = "Location";
								$sql1               = " SELECT * 
														FROM warehouse_sub_locations 
														WHERE enabled = 1  
														ORDER BY sub_location_name, sub_location_type DESC ";
								$result1            = $db->query($conn, $sql1);
								$count1             = $db->counter($result1);
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
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>>
													<?php
													echo $data2['sub_location_name'];
													if ($data2['sub_location_type'] != "") {
														echo " (" . $data2['sub_location_type'] . ")";
													} ?>
												</option>
										<?php }
										} ?>
									</select>
									<label for="<?= $field_name; ?>">
										<?= $field_label; ?>
										<span class="color-red"> * <?php
																	if (isset($error[$field_name])) {
																		echo $error[$field_name];
																	} ?>
										</span>
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m2 s12"> </div>
						</div>
						<div class="row">
							<?php
							for ($i = 0; $i < 8; $i++) { ?>
								<div class="input-field col m3 s12">
									<?php
									$i2 = $i + 1;
									$field_name     = "part_id";
									$field_id       = "part_id" . $i2;
									$field_label    = "Part " . $i2;

									$sql1           = " SELECT b.id, a.product_sku, c.category_name, b.price, b.total_stock
														FROM packages a 
														INNER JOIN package_stock b ON b.package_id = a.id
														INNER JOIN product_categories c ON c.id = a.product_category
														WHERE b.enabled = 1 
														AND b.total_stock > 0
														AND a.enabled = 1 ";
									$result1        = $db->query($conn, $sql1);
									$count1         = $db->counter($result1);
									?>
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<select id="<?= $field_id; ?>" name="<?= $field_name; ?>[]" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1    = $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}[$i]) && ${$field_name}[$i] == $data2['id']) { ?> selected="selected" <?php } ?>> <?php echo $data2['product_sku']; ?> (<?php echo $data2['category_name']; ?>), Qty:<?php echo $data2['total_stock']; ?> </option>
											<?php }
											} ?>
										</select>
										<label for="<?= $field_id; ?>">
											<?= $field_label; ?>
											<span class="color-red"> <?php
																		if (isset($error[$field_name])) {
																			echo $error[$field_name];
																		} ?>
											</span>
										</label>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<?php if (isset($id) && $id > 0 && access("add_perm") == 1 && access("edit_perm") == 1) { ?>
									<button class="btn cyan waves-effect waves-light right" type="submit" name="action">Process
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