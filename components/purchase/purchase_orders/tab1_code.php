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
	$is_tested_po				= "Yes";
	$is_wiped_po				= "Yes";
	$is_imaged_po				= "Yes";
}
if (isset($test_on_local) && $test_on_local == 1 && isset($cmd2) &&  $cmd2 == 'add') {
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

$title_heading2	= "Add Order Product";
$button_val2 	= "Add"; 
if (isset($cmd2) &&  $cmd2 == 'edit') {
	$title_heading2  = "Update Order Product";
	$button_val2 	= "Save";
} 

if ($cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee					= "SELECT a.* FROM purchase_orders a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$vender_id				=  $row_ee[0]['vender_id'];
	$po_desc				= $row_ee[0]['po_desc'];
	$vender_invoice_no		= $row_ee[0]['vender_invoice_no'];
	$is_tested_po			= $row_ee[0]['is_tested_po'];
	$is_wiped_po			= $row_ee[0]['is_wiped_po'];
	$is_imaged_po			= $row_ee[0]['is_imaged_po'];
	$po_date				= str_replace("-", "/", convert_date_display($row_ee[0]['po_date']));
	$estimated_receive_date	= str_replace("-", "/", convert_date_display($row_ee[0]['estimated_receive_date']));
}

if (isset($cmd2) &&  $cmd2 == 'edit' && isset($detail_id) && $detail_id > 0) {
	$sql_ee						= "SELECT a.* FROM purchase_order_detail a WHERE a.id = '" . $detail_id . "' ";
	// echo $sql_ee;
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
	$field_name = "is_tested_po";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "is_wiped_po";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "is_imaged_po";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
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
					$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_orders(subscriber_users_id, vender_id, vender_invoice_no, po_date, estimated_receive_date, po_desc,is_tested_po,  is_wiped_po, is_imaged_po, add_date, add_by, add_by_user_id, add_ip, add_timezone)
							 VALUES('" . $subscriber_users_id . "', '" . $vender_id . "', '" . $vender_invoice_no . "', '" . $po_date1  . "', '" . $estimated_receive_date1  . "', '" . $po_desc  . "', '" . $is_tested_po  . "', '" . $is_wiped_po  . "', '" . $is_imaged_po  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id			= mysqli_insert_id($conn);
						$po_no		= "PO" . $id;

						$sql6		= " UPDATE purchase_orders SET po_no = '" . $po_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						$msg['msg_success'] = "Purchase Order has been created successfully.";
						$cmd = 'edit';
						//echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id . "&msg_success=" . $msg['msg_success']));
						//$vender_id = $po_desc =  $po_date = $vender_invoice_no = $estimated_receive_date = $is_tested_po = $is_wiped_po = $is_imaged_po = "";
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
															is_tested_po			= '" . $is_tested_po . "', 
															is_wiped_po				= '" . $is_wiped_po . "', 
															is_imaged_po			= '" . $is_imaged_po . "', 

															update_date				= '" . $add_date . "',
															update_by				= '" . $_SESSION['username'] . "',
															update_by_user_id		= '" . $_SESSION['user_id'] . "',
															update_ip				= '" . $add_ip . "',
															update_timezone			= '" . $timezone . "'
								WHERE id = '" . $id . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
						$cmd = "edit";
						$cmd2 = "add";
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
		if (isset($cmd2) &&  $cmd2 == 'add') {
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
						$cmd = "edit";
						$cmd2 = "add";
						$product_id = $order_price = $order_qty = $product_po_desc  = $is_tested = $is_wiped = $is_imaged = $product_condition = $warranty_period_in_days = "";
					} else {
						$error2['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error2['msg'] = "This record is already exist.";
				}
			}
		} else if (isset($cmd2) &&  $cmd2 == 'edit') {
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
}

if (isset($_POST['is_Submit_tab2']) && $_POST['is_Submit_tab2'] == 'Y') {
	if (empty($error2)) {
		$order_status =  $logistic_status_dynamic;
	}
}
if (isset($cmd2_1) && $cmd2_1 == 'delete' && isset($detail_id)) {
	$order_status =  $before_logistic_status_dynamic;
}

if (isset($_POST['is_Submit_tab2_1']) && $_POST['is_Submit_tab2_1'] == 'Y') {
	if (empty($error2)) {
		$order_status =  $logistic_status_dynamic;
	}
}
if (isset($_POST['is_Submit_tab2_3']) && $_POST['is_Submit_tab2_3'] == 'Y') {
	if (empty($error2)) {
		$order_status =  $logistics_status;
	}
}
