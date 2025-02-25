<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$po_date = date('d/m/Y');
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$vender_id					= "1";
	$vender_invoice_no			= date('YmdHis');
	$po_date 					= date('d/m/Y');
	$po_desc					= "purchase order desc : " . date('YmdHis');
	$order_status    			= "1";
	$stage_status    			= "Draft";
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

if (isset($cmd3) && $cmd3 == 'disabled') {
	$sql_c_upd = "UPDATE package_materials_order_detail set	enabled = 0,

															update_date				= '" . $add_date . "',
															update_by				= '" . $_SESSION['username'] . "',
															update_by_user_id		= '" . $_SESSION['user_id'] . "',
															update_ip				= '" . $add_ip . "',
															update_timezone			= '" . $timezone . "',
															update_from_module_id	= '" . $module_id . "'
				WHERE id = '" . $detail_id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg2['msg_success'] = "Record has been disabled.";
	} else {
		$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
	}
}
if (isset($cmd3) && $cmd3 == 'enabled') {
	$sql_c_upd = "UPDATE package_materials_order_detail set	enabled 				= 1,

															update_date				= '" . $add_date . "',
															update_by				= '" . $_SESSION['username'] . "',
															update_by_user_id		= '" . $_SESSION['user_id'] . "',
															update_ip				= '" . $add_ip . "',
															update_timezone			= '" . $timezone . "',
															update_from_module_id	= '" . $module_id . "'
				WHERE id = '" . $detail_id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg2['msg_success'] = "Record has been enabled.";
	}
}

if ($cmd == 'edit') {
	$title_heading 	= "Update Sale Order";
	$button_val 	= "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Create Sale Order";
	$button_val 	= "Create";
	$id 			= "";
}

$title_heading2	= "Add Order Product";
$button_val2 	= "Add";
if (isset($cmd2) && $cmd2 == 'edit') {
	$title_heading2  = "Update Order Product";
	$button_val2 	= "Save";
}

if ($cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee					= "SELECT a.*, b.status_name
								FROM package_materials_orders a
								LEFT JOIN inventory_status b ON b.id = a.order_status
								WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);

	$po_no					= $row_ee[0]['po_no'];
	$vender_id				= $row_ee[0]['vender_id'];
	$po_desc				= $row_ee[0]['po_desc'];
	$public_note			= $row_ee[0]['public_note'];
	$vender_invoice_no		= $row_ee[0]['vender_invoice_no'];
	$po_date				= str_replace("-", "/", convert_date_display($row_ee[0]['po_date']));
	$order_date_disp		= dateformat2($row_ee[0]['po_date']);
	$order_status    		= $row_ee[0]['order_status'];
	$disp_status_name		=  $row_ee[0]['status_name'];
	$stage_status    		= $row_ee[0]['stage_status'];
	$package_id 				= [];
	$order_qty 					= [];
	$order_price 				= [];
	$product_po_desc 			= [];
	$case_pack					= [];
	$sql_ee1		= " SELECT a.* 
						FROM package_materials_order_detail a
 						WHERE a.po_id = '" . $id . "' ";  //echo $sql_ee1;
	$result_ee1		= $db->query($conn, $sql_ee1);
	$count_ee1  	= $db->counter($result_ee1);
	if ($count_ee1 > 0) {
		$row_ee1	= $db->fetch($result_ee1);
		foreach ($row_ee1 as $data2) {
			$package_id[]				= $data2['package_id'];
			$order_qty[]				= $data2['order_qty'];
			$order_price[]				= $data2['order_price'];
			$product_po_desc[]			= $data2['product_po_desc'];
			$case_pack[]				= $data2['order_case_pack'];
		}
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

	$field_name = "vender_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "vender_invoice_no";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "po_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		$po_date1 = NULL;
		if (isset($po_date) && $po_date != "") {
			$po_date1 = convert_date_mysql_slash($po_date);
		}
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM package_materials_orders a 
								WHERE a.vender_id	= '" . $vender_id . "'
								AND a.po_date		= '" . $po_date1 . "'
								AND a.vender_invoice_no	= '" . $vender_invoice_no . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".package_materials_orders(subscriber_users_id, vender_id, vender_invoice_no, po_date, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
							 VALUES('" . $subscriber_users_id . "', '" . $vender_id . "', '" . $vender_invoice_no . "', '" . $po_date1  . "',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id					= mysqli_insert_id($conn);
						$po_no				= "PPO" . $id;
						$po_date_disp		= dateformat2($po_date1);
						$cmd 				= 'edit';
						$order_status 		= 1;
						$disp_status_name 	= get_status_name($db, $conn, $order_status);
						$order_date_disp	= dateformat2($po_date1);

						$sql6 = " UPDATE package_materials_orders SET po_no = '" . $po_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);
						$msg['msg_success'] = "Purchase Order has been created successfully.";
						// $vender_id = $vender_invoice_no = $po_date = $vender_invoice_no = "";
						$po_date 	= date("d/m/Y");
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
				$sql_dup	= " SELECT a.* FROM package_materials_orders a 
								WHERE a.vender_id		= '" . $vender_id . "'
								AND a.po_date			= '" . $po_date1 . "' 
								AND a.vender_invoice_no	= '" . $vender_invoice_no . "' 
								AND a.id		   	   != '" . $id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE package_materials_orders SET 	vender_id				= '" . $vender_id . "',
																		po_date					= '" . $po_date1 . "',
 																		vender_invoice_no		= '" . $vender_invoice_no . "', 

																		update_date				= '" . $add_date . "',
																		update_by				= '" . $_SESSION['username'] . "',
																		update_by_user_id		= '" . $_SESSION['user_id'] . "',
																		update_ip				= '" . $add_ip . "',
																		update_timezone			= '" . $timezone . "',
																		update_from_module_id	= '" . $module_id . "'
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
	$field_name = "vender_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "vender_invoice_no";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "po_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		$po_date1 = NULL;
		if (isset($po_date) && $po_date != "") {
			$po_date1 = convert_date_mysql_slash($po_date);
		}
		$sql_dup	= " SELECT a.* FROM package_materials_orders a 
						WHERE a.vender_id		= '" . $vender_id . "'
						AND a.po_date			= '" . $po_date1 . "' 
						AND a.vender_invoice_no	= '" . $vender_invoice_no . "' 
						AND a.id		   	   != '" . $id . "' ";
		$result_dup	= $db->query($conn, $sql_dup);
		$count_dup	= $db->counter($result_dup);
		if ($count_dup == 0) {
			$sql_c_up = "UPDATE package_materials_orders SET	vender_id				= '" . $vender_id . "',
																po_date					= '" . $po_date1 . "',
																vender_invoice_no		= '" . $vender_invoice_no . "', 
																po_desc					= '" . $po_desc . "', 
																public_note				= '" . $public_note . "',

																update_date				= '" . $add_date . "',
																update_by				= '" . $_SESSION['username'] . "',
																update_by_user_id		= '" . $_SESSION['user_id'] . "',
																update_ip				= '" . $add_ip . "',
																update_timezone			= '" . $timezone . "',
																update_from_module_id	= '" . $module_id . "'
						WHERE id = '" . $id . "' ";
			$ok = $db->query($conn, $sql_c_up);
		}
		$k = 0;
		if (isset($stage_status) && $stage_status != "Committed") {
			
			$filtered_id = array_values(array_filter($package_ids));
			$current_ids = implode(',', $filtered_id);
			if($current_ids !=""){
				$sql_dup1 = "UPDATE package_materials_order_detail SET enabled = 0 
							WHERE po_id	= '" . $id . "' 
							AND package_id NOT IN(" . $current_ids . ") ";
				$db->query($conn, $sql_dup1);
			}

			$i = 0; // Initialize the counter before the loop
			$r = 1;
			foreach ($filtered_id as $package_id) {
				$sql_dup	= " SELECT a.* 
								FROM package_materials_order_detail a 
								WHERE a.po_id	= '" . $id . "'
								AND a.package_id	= '" . $package_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".package_materials_order_detail(po_id, package_id, product_po_desc, order_qty, order_price, order_case_pack,  add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
							VALUES('" . $id . "', '" . $package_id . "', '" . $product_po_desc[$i]  . "', '" . $order_qty[$i]  . "', '" . $order_price[$i]  . "', '" . $case_pack[$i]  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$k++; // Increment the counter only if the insertion is successful
					}
					$i++;
				} else {
					$sql_c_up = "UPDATE  package_materials_order_detail SET 
																			product_po_desc     = '" . $product_po_desc[$i] . "',
																			order_qty 			= '" . $order_qty[$i] . "',
																			order_price			= '" . $order_price[$i] . "',
																			order_case_pack		= '" . $case_pack[$i] . "',
																			enabled 			= 1,
																			
																			update_timezone	= '" . $timezone . "',
																			update_date		= '" . $add_date . "',
																			update_by		= '" . $_SESSION['username'] . "',
																			update_ip		= '" . $add_ip . "'
								WHERE po_id = '" . $id . "'  AND package_id = '" . $package_id . "' ";
					$db->query($conn, $sql_c_up);
					$package_ids[$i] 		= "";
					$order_qty[$i] 			= "";
					$order_price[$i]		= "";
					$product_po_desc[$i]	= "";
					$case_pack[$i]			= "";
					$i++;
				}
			}
		}
		if ($k == 1) {
			if (isset($error2['msg'])) unset($error2['msg']);
			$msg2['msg_success'] = "Record has been added successfully.";
		} else {
			if (isset($error2['msg'])) unset($error2['msg']);
			$msg2['msg_success'] = "Record has been added successfully.";
		}
	}
}
