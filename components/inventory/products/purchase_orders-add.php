<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$vender_id					= "1";
	$po_date 					= date('d/m/Y');
	$estimated_receive_date 	= date('d/m/Y');
	$po_desc					= "purchase order desc : " . date('YmdHis');
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
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

// if (!isset($is_Submit) && $cmd == 'edit' && isset($msg['msg_success']) && isset($id)) {
// 	echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id));
// }
if (isset($cmd3) && $cmd3 == 'disabled') {
	$sql_c_upd = "UPDATE purchase_order_detail set 	enabled = 0,
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
	$sql_c_upd = "UPDATE purchase_order_detail set 	enabled 	= 1,
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
	$title_heading 	= "Update Purchase Order";
	$button_val 	= "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Create Purchase Order";
	$button_val 	= "Create";
	$id 			= "";
}

if ($cmd2 == 'edit') {
	$title_heading2  = "Update Order Product";
	$button_val2 	= "Save";
}
if ($cmd2 == 'add') {
	$title_heading2	= "Add Order Product";
	$button_val2 	= "Add";
	$detail_id		= "";
}

if ($cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee					= "SELECT a.* FROM purchase_orders a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$vender_id				=  $row_ee[0]['vender_id'];
	$po_desc				= $row_ee[0]['po_desc'];
	$vender_invoice_no		= $row_ee[0]['vender_invoice_no'];
	$po_date				= str_replace("-", "/", convert_date_display($row_ee[0]['po_date']));
	$estimated_receive_date	= str_replace("-", "/", convert_date_display($row_ee[0]['estimated_receive_date']));
}
if ($cmd2 == 'edit' && isset($detail_id) && $detail_id > 0) {
	$sql_ee						= "SELECT a.* FROM purchase_order_detail a WHERE a.id = '" . $detail_id . "' "; // echo $sql_ee;
	$result_ee					= $db->query($conn, $sql_ee);
	$row_ee						= $db->fetch($result_ee);
	$product_id					= $row_ee[0]['product_id'];
	$order_qty					= $row_ee[0]['order_qty'];
	$order_price				= $row_ee[0]['order_price'];
	$product_po_desc			= $row_ee[0]['product_po_desc'];
	$is_tested					= $row_ee[0]['is_tested'];
	$is_wiped					= $row_ee[0]['is_wiped'];
	$is_imaged					= $row_ee[0]['is_imaged'];
	$product_condition			= $row_ee[0]['product_condition'];
	$warranty_period_in_days	= $row_ee[0]['warranty_period_in_days'];
	$package_id			= $row_ee[0]['package_id'];
	$package_material_qty		= $row_ee[0]['package_material_qty'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {

	$field_name = "po_desc";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "vender_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "po_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "estimated_receive_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
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
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM purchase_orders a 
								WHERE a.vender_id	= '" . $vender_id . "'
								AND a.po_date		= '" . $po_date1 . "'
								AND a.po_desc		= '" . $po_desc . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_orders(subscriber_users_id, vender_id, vender_invoice_no, po_date, estimated_receive_date, po_desc, add_date, add_by, add_by_user_id, add_ip, add_timezone)
							 VALUES('" . $subscriber_users_id . "', '" . $vender_id . "', '" . $vender_invoice_no . "', '" . $po_date1  . "', '" . $estimated_receive_date1  . "', '" . $po_desc  . "',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id			= mysqli_insert_id($conn);
						$po_no		= "PO" . $id;

						$sql6		= " UPDATE purchase_orders SET po_no = '" . $po_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						$msg['msg_success'] = "Purchase Order has been created successfully.";
						echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id . "&msg_success=" . $msg['msg_success']));
						$vender_id = $po_desc =  $po_date = $vender_invoice_no = $estimated_receive_date = "";
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
				$sql_dup	= " SELECT a.* FROM purchase_orders a 
								WHERE a.vender_id	= '" . $vender_id . "'
								AND a.po_date	= '" . $po_date1 . "'
								AND a.po_desc	= '" . $po_desc . "' 
								AND a.id		   != '" . $id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE purchase_orders SET	vender_id				= '" . $vender_id . "',
															po_date					= '" . $po_date1 . "',
															estimated_receive_date 	= '" . $estimated_receive_date1 . "', 
															po_desc					= '" . $po_desc . "', 
															vender_invoice_no		= '" . $vender_invoice_no . "', 
															
															update_date				= '" . $add_date . "',
															update_by				= '" . $_SESSION['username'] . "',
															update_by_user_id		= '" . $_SESSION['user_id'] . "',
															update_ip				= '" . $add_ip . "',
															update_timezone			= '" . $timezone . "'
								WHERE id = '" . $id . "' ";
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
}
if (isset($is_Submit2) && $is_Submit2 == 'Y') {
	$field_name = "package_id";
	if ((isset(${$field_name}) && ${$field_name} > 0)) {
		$field_name = "package_material_qty";
		if (!isset(${$field_name}) || (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0"))) {
			$error[$field_name] 		= "Required";
			${$field_name . "_valid"} 	= "invalid";
		}
	} else {
		$package_material_qty = "0";
	}
	$field_name = "is_tested";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "is_wiped";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "is_imaged";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	$field_name = "order_price";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "order_qty";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "product_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		if ($cmd2 == 'add') {
			if (access("add_perm") == 0) {
				$error2['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM purchase_order_detail a 
								WHERE a.po_id			= '" . $id . "'
								AND a.product_id		= '" . $product_id . "'
								AND a.product_condition	= '" . $product_condition . "'
								AND a.is_tested			= '" . $is_tested . "'
								AND a.is_wiped			= '" . $is_wiped . "' 
								AND a.is_imaged			= '" . $is_imaged . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_order_detail(po_id, product_id, order_qty, order_price, package_id, package_material_qty, is_tested,  is_wiped, is_imaged, product_condition, warranty_period_in_days, product_po_desc, add_date, add_by, add_by_user_id, add_ip, add_timezone)
							 VALUES('" . $id . "', '" . $product_id . "', '" . $order_qty  . "', '" . $order_price  . "', '" . $package_id  . "', '" . $package_material_qty  . "', '" . $is_tested  . "', '" . $is_wiped  . "', '" . $is_imaged  . "', '" . $product_condition  . "', '" . $warranty_period_in_days  . "', '" . $product_po_desc  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						if (isset($error2['msg'])) unset($error2['msg']);
						$msg2['msg_success'] = "Record has been added successfully.";
						$product_id = $order_price = $order_qty = $product_po_desc  = $is_tested = $is_wiped = $is_imaged = $product_condition = $warranty_period_in_days = "";
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
				$sql_dup	= " SELECT a.* FROM purchase_order_detail a 
								WHERE a.po_id		= '" . $id . "'
								AND a.product_id		= '" . $product_id . "'
								AND a.product_condition	= '" . $product_condition . "'
								AND a.is_tested			= '" . $is_tested . "'
								AND a.is_wiped			= '" . $is_wiped . "' 
								AND a.is_imaged			= '" . $is_imaged . "'
 								AND a.id			   != '" . $detail_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE purchase_order_detail SET 	product_id				= '" . $product_id . "', 
																	order_qty				= '" . $order_qty . "', 
																	order_price				= '" . $order_price . "', 
																	is_tested				= '" . $is_tested . "', 
																	is_wiped				= '" . $is_wiped . "', 
																	is_imaged				= '" . $is_imaged . "', 
																	product_condition		= '" . $product_condition . "', 
																	warranty_period_in_days	= '" . $warranty_period_in_days . "', 
																	product_po_desc			= '" . $product_po_desc . "', 
																	package_id		= '" . $package_id . "', 
																	package_material_qty	= '" . $package_material_qty . "', 

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
	</div>
	<div class="row">
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
					<h4 class="card-title">PO Info</h4><br>
					<form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id); ?>">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" id="cmd" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<div class="row">
							<?php
							$field_name 	= "po_date";
							$field_label 	= "Order Date (d/m/Y)";
							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">date_range</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>" class=" datepicker validate <?php if (isset(${$field_name . "_valid"})) {
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
							$field_name 	= "estimated_receive_date";
							$field_label 	= "Expected Arrival Date (d/m/Y)";
							?>
							<div class="input-field col m3 s12">
								<i class="material-icons prefix">date_range</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>" class=" datepicker validate <?php if (isset(${$field_name . "_valid"})) {
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
							$field_name 	= "vender_invoice_no";
							$field_label 	= "Vendor Invoice #";
							?>
							<div class="input-field col m2 s12">
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

							<?php /*?>
							<div class="row">
								<div class="input-field col m6 s12">
									<?php
									$field_name 	= "product_id";
									$field_label	= "Product";
									$sql1 			= " SELECT a.*, b.category_name
														FROM products a
														INNER JOIN product_categories b ON b.id = a.product_category
														WHERE a.enabled = 1 
														ORDER BY a.product_desc ";
									$result1		= $db->query($conn, $sql1);
									$count1			= $db->counter($result1);
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
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>) - <?php echo $data2['product_uniqueid']; ?></option>
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
								<div class="input-field col m2 s12">
									<a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#vender_add_modal">Add New Product</a>
								</div>
							</div>
							<?php */ ?>
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "vender_id";
								$field_label 	= "Vender";
								$sql1 			= "SELECT * FROM venders WHERE enabled = 1 ORDER BY vender_name ";
								$result1 		= $db->query($conn, $sql1);
								$count1 		= $db->counter($result1);
								?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1	= $db->fetch($result1);
											foreach ($row1 as $data2) { ?>
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['vender_name']; ?> - Phone: <?php echo $data2['phone_no']; ?></option>
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
							<div class="input-field col m2 s12">
								<a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#vender_add_modal">Add New Vender</a>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m12 s12">
								<?php
								$field_name 	= "po_desc";
								$field_label 	= "Description";
								?>
								<i class="material-icons prefix">description</i>
								<textarea id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
																																			echo ${$field_name};
																																		} ?></textarea>
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
									$field_name 	= "product_id";
									$field_label	= "Product";

									$sql1 			= " SELECT a.*, b.category_name
														FROM products a
														LEFT JOIN product_categories b ON b.id = a.product_category
														WHERE a.enabled = 1 
														ORDER BY a.product_desc";
									$result1 		= $db->query($conn, $sql1);
									$count1 		= $db->counter($result1);
									?>
									<i class="material-icons prefix">add_shopping_cart</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1	= $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>) - <?php echo $data2['product_uniqueid']; ?></option>
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
								<div class="input-field col m2 s12">
									<a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#product_add_modal">Add New Product</a>
								</div>
								<div class="input-field col m3 s12">
									<?php
									$field_name 	= "order_qty";
									$field_label 	= "Quantity";
									?>
									<i class="material-icons prefix">description</i>
									<input id="<?= $field_name; ?>" type="number" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
								<div class="input-field col m3 s12">
									<?php
									$field_name 	= "order_price";
									$field_label 	= "Unit Price";
									?>
									<i class="material-icons prefix">attach_money</i>
									<input id="<?= $field_name; ?>" type="text" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
								<div class="input-field col m3 s12">
									<?php
									$field_name 	= "warranty_period_in_days";
									$field_label 	= "Warranty Days from Date of Arrival";
									?>
									<i class="material-icons prefix">description</i>
									<input id="<?= $field_name; ?>" type="number" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
								<div class="input-field col m3 s12">
									<?php
									$field_name 	= "product_condition";
									$field_label 	= "Product Condition";
									?>
									<i class="material-icons prefix">description</i>
									<input id="<?= $field_name; ?>" type="text" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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


								<div class="input-field col m2 s12">
									<?php
									$field_name 	= "is_tested";
									$field_label 	= "To Be Tested";
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
											<span>Yes</span>
										</label> &nbsp;&nbsp;
										<label>
											<input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php
																																if (isset(${$field_name}) && ${$field_name} == 'No') {
																																	echo 'checked=""';
																																} ?>>
											<span>No</span>
										</label>
									</p>
								</div>
								<div class="input-field col m2 s12">
									<?php
									$field_name 	= "is_wiped";
									$field_label 	= "To Be Wiped";
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
											<span>Yes</span>
										</label> &nbsp;&nbsp;
										<label>
											<input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php
																																if (isset(${$field_name}) && ${$field_name} == 'No') {
																																	echo 'checked=""';
																																} ?>>
											<span>No</span>
										</label>
									</p>
								</div>
								<div class="input-field col m2 s12">
									<?php
									$field_name 	= "is_imaged";
									$field_label 	= "To Be Imaged";
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
											<span>Yes</span>
										</label> &nbsp;&nbsp;
										<label>
											<input name="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php
																																if (isset(${$field_name}) && ${$field_name} == 'No') {
																																	echo 'checked=""';
																																} ?>>
											<span>No</span>
										</label>
									</p>
								</div>
							</div>
							<div class="row">
								<br>
								<div class="input-field col m12 s12">
									<?php
									$field_name 	= "product_po_desc";
									$field_label 	= "Description";
									?>
									<i class="material-icons prefix">description</i>
									<textarea id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
																																				echo ${$field_name};
																																			} ?></textarea>
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
							<div class="row">
								<div class="input-field col m4 s12"></div>
							</div>
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
											if (isset($product_id) && $product_id > 0) {
												$sql1 			= " SELECT  a.id, b.category_name, a.package_name
																	FROM packages a
																	INNER JOIN product_categories b ON b.id = a.product_category
 																	WHERE 1=1
																	AND a.enabled = 1
																	AND FIND_IN_SET(  " . $product_id . " , product_ids) > 0
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
												<?php }
											} else { ?>
												<option value="">Select</option>
											<?php } ?>
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
								<div class="input-field col m3 s12 package_material_qty" style="<?php if (!isset($package_id) || (isset($package_id) && ($package_id == "" || $package_id == "0"))) {
																									echo "display:none;";
																								} ?>">
									<?php
									$field_name 	= "package_material_qty";
									$field_label 	= "Package Material Qty";
									?>
									<i class="material-icons prefix">description</i>
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
							</div>
							<div class="row">
								<div class="input-field col m6 s12">
									<?php if (($cmd2 == 'add' && access("add_perm") == 1)  || ($cmd2 == 'edit' && access("edit_perm") == 1)) { ?>
										<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val2; ?>
											<i class="material-icons right">send</i>
										</button>
									<?php } ?>
								</div>
								<div class="input-field col m2 s12">
									<?php if ($cmd2 == 'edit' && access("add_perm") == 1) { ?>
										<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&id=" . $id) ?>">Add New Product</a>
									<?php } ?>
								</div>
							</div>
						</form>
					</div>
					<?php //include('sub_files/right_sidebar.php'); 
					?>
				</div>
			</div>

			<div class="col s12">
				<div class="container">
					<div class="section section-data-tables">
						<!-- Page Length Options -->
						<h4 class="card-title">Purchase Order Details</h4>
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
										<?php
										$sql_cl		= "	SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid, f.product_sku, g.category_name as package_material_category_name
														FROM purchase_order_detail a 
														INNER JOIN purchase_orders b ON b.id = a.po_id
														INNER JOIN products c ON c.id = a.product_id
														LEFT JOIN product_categories d ON d.id = c.product_category
 														LEFT JOIN packages f ON f.id = a.package_id
														LEFT JOIN product_categories g ON g.id = f.product_category
														WHERE 1=1 
														AND a.po_id = '" . $id . "' 
														ORDER BY c.product_uniqueid, a.product_condition ";
										//echo $sql_cl;
										$result_cl	= $db->query($conn, $sql_cl);
										$count_cl	= $db->counter($result_cl);
										if ($count_cl > 0) { ?>
											<div class="row">
												<div class="col s12">
													<table id="page-length-option" class="display">
														<thead>
															<tr>
																<?php
																$headings = '<th class="sno_width_60">S.No</th>
																			<th>Base Product ID / Details</th>
																			<th>Package Material</th>
																			<th>Condition</th>
																			<th>Order Qty</th>
																			<th>Unit Price</th>
																			<th>To Be Tested</th>
																			<th>To Be Wiped</th>
																			<th>To Be Imaged</th>
																			<th>Warranty Days</th>
																			<th>Action</th>';
																echo $headings; ?>
															</tr>
														</thead>
														<tbody>
															<?php
															$i = 0;
															$row_cl = $db->fetch($result_cl);
															foreach ($row_cl as $data) {
																$detail_id 		= $data['id'];
																$order_qty 		= $data['order_qty'];
																$order_price	= $data['order_price']; ?>
																<tr>
																	<td style="text-align: center;">
																		<?php echo $i + 1; ?>
																	</td>
																	<td>
																		<?php echo $data['product_uniqueid']; ?><br>
																		<?php echo ucwords(strtolower($data['product_desc'])); ?>
																		<?php
																		if ($data['category_name'] != "") {
																			echo  " (" . $data['category_name'] . ")";
																		} ?>
																		<br>
																		<?php
																		$detail_desc = $data['product_po_desc'];
																		if ($detail_desc != '') {
																			echo substr($detail_desc, 0, 50) . "";
																			if (strlen($detail_desc) > 50) {
																				echo "...";
																			}
																		} ?>
																	</td>
																	<td>
																		<?php
																		echo  $data['product_sku'];
																		if ($data['package_material_category_name'] != "") {
																			echo  " (" . $data['package_material_category_name'] . ")";
																		} ?>
																		<br>
																		<?php
																		if ($data['package_material_qty'] > "0") {
																			echo  "<b>Quantity: </b>" . $data['package_material_qty'] . "";
																		} ?>
																	</td>
																	<td><?php echo $data['product_condition']; ?></td>
																	<td><?php echo $order_qty; ?></td>
																	<td><?php echo $order_price; ?></td>
																	<td><?php echo $data['is_tested']; ?></td>
																	<td><?php echo $data['is_wiped']; ?></td>
																	<td><?php echo $data['is_imaged']; ?></td>
																	<td><?php echo $data['warranty_period_in_days']; ?></td>

																	<td class="text-align-center">
																		<?php
																		if ($data['enabled'] == 1 && access("view_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=edit&id=" . $id . "&detail_id=" . $detail_id) ?>">
																				<i class="material-icons dp48">edit</i>
																			</a> &nbsp;&nbsp;
																		<?php }
																		if ($data['enabled'] == 0 && access("edit_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&cmd3=enabled&id=" . $id . "&detail_id=" . $detail_id) ?>">
																				<i class="material-icons dp48">add</i>
																			</a> &nbsp;&nbsp;
																		<?php } else if ($data['enabled'] == 1 && access("delete_perm") == 1) { ?>
																			<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&cmd3=disabled&id=" . $id . "&detail_id=" . $detail_id) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																				<i class="material-icons dp48">delete</i>
																			</a> &nbsp;&nbsp;
																		<?php } ?>
																	</td>
																</tr>
															<?php $i++;
															}  ?>
														<tfoot>
															<tr><?php echo $headings; ?></tr>
														</tfoot>
													</table>
												</div>
											</div>
										<?php } ?>
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

		<?php } ?>
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