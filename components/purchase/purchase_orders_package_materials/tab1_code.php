<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$po_date = date('d/m/Y');
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$vender_id					= "1";
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
				$sql_dup	= " SELECT a.* FROM package_materials_orders a WHERE a.duplication_check_token	= '" . $duplication_check_token . "'  ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".package_materials_orders(subscriber_users_id, vender_id, po_date, duplication_check_token, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
							 VALUES('" . $subscriber_users_id . "', '" . $vender_id . "', '" . $po_date1  . "', '" . $duplication_check_token  . "',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id					= mysqli_insert_id($conn);
						$po_no				= "PPO" . $id;
						$po_date_disp		= dateformat2($po_date1);
						$cmd 				= 'edit';
						$order_status 		= 1;
						$stage_status 		= 'Draft';
						$disp_status_name 	= get_status_name($db, $conn, $order_status);
						$order_date_disp	= dateformat2($po_date1);

						$sql6 = " UPDATE package_materials_orders SET po_no = '" . $po_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						$msg['msg_success'] = "Purchase Order has been created successfully.";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
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
						AND a.id		   	   != '" . $id . "' ";
		$result_dup	= $db->query($conn, $sql_dup);
		$count_dup	= $db->counter($result_dup);
		if ($count_dup == 0) {
			$sql_c_up = "UPDATE package_materials_orders SET	vender_id				= '" . $vender_id . "',
																po_date					= '" . $po_date1 . "',
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
		/*
		$k = 0;
		if (isset($stage_status) && $stage_status != "Committed") {

			$filtered_id = array_values(array_filter($package_ids));
			$current_ids = implode(',', $filtered_id);
			if ($current_ids != "") {
				$sql_dup1 = "UPDATE package_materials_order_detail SET enabled = 0 
							WHERE po_id	= '" . $id . "' 
							AND package_id NOT IN(" . $current_ids . ") ";
				$db->query($conn, $sql_dup1);
			}
			echo "<br><br><br><br><br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: ";
			print_r($filtered_id);
			die;

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
		*/

		$k = 0;
		if (isset($stage_status) && $stage_status != "Committed") {

			$filtered_id 			= array_values(array_filter($package_ids));
			$current_ids 			= implode(',', $filtered_id);
			$order_qty 				= array_values(array_filter($order_qty));
			$order_price 			= array_values(array_filter($order_price));
			$case_pack 			= array_values(array_filter($case_pack));

			$matches_po_detail_ids = array();
			foreach ($filtered_id as $index => $product) {
				$price = isset($order_price[$index]) ? $order_price[$index] : "";
				if (isset($product_detail)) {
					foreach ($product_detail as $key => $entry) {
						if (isset($entry[0]) && $entry[1] && $entry[0] == $product && $entry[1] == $price) {
							$sql_old = "	SELECT id FROM package_materials_order_detail
											WHERE 1=1 
											AND enabled 			= 1 
											AND po_id				= '" . $id . "'
											AND package_id 			= '" . $product . "'
  											AND order_price			= '" . $price . "' ";
							$result_p_old 	= $db->query($conn, $sql_old);
							$counter_p_old	= $db->counter($result_p_old);
							if ($counter_p_old > 0) {
								$row_p_old = $db->fetch($result_p_old);
								foreach ($row_p_old as $data_p_old) {
									$matches_po_detail_ids[] = $data_p_old['id'];
								}
							}
							break;
						}
					}
				}
			}
			$all_matches_po_detail_ids = "''";
			if (!empty($matches_po_detail_ids)) {
				$all_matches_po_detail_ids = implode(",", $matches_po_detail_ids);
			}

			$sql_dup1 = "UPDATE package_materials_order_detail a
						 LEFT JOIN package_materials_order_detail_receive b ON b.po_detail_id = a.id
							SET a.enabled = 0 
						WHERE a.po_id	= '" . $id . "' 
						AND IFNULL(b.id, 0) = 0
						AND a.id NOT IN(" . $all_matches_po_detail_ids . ") ";
			$db->query($conn, $sql_dup1);

			$i = 0; // Initialize the counter before the loop
			$r = 1;

			// echo "<br><br><br><br><br><br><br>aaaaaaaaaaaaaaaaaaaa <pre>";
			// print_r($filtered_id);
			// print_r($order_qty);
			// print_r($order_price);
			// print_r($product_condition);

			foreach ($filtered_id as $data_p) {
				if ($data_p != "") {

					$order_price[$i] 		= isset($order_price[$i]) ? $order_price[$i] : "";
					$order_qty[$i] 			= isset($order_qty[$i]) ? $order_qty[$i] : "";
					$case_pack[$i] 			= isset($case_pack[$i]) ? $case_pack[$i] : "";

					$sql_dup 	= " SELECT a.* FROM package_materials_order_detail a 
									WHERE a.enabled 			= 1
									AND a.po_id 			= '" . $id . "' 
									AND a.package_id 		= '" . $data_p . "'
 									AND a.order_price 		= '" . $order_price[$i] . "' ";
					//echo "<br><br>".$sql_dup;
					$result_dup = $db->query($conn, $sql_dup);
					$count_dup 	= $db->counter($result_dup);
					if ($count_dup > 0) {
						$row_dup = $db->fetch($result_dup);
						foreach ($row_dup as $data_dup) {
							$po_detail_id1 = $data_dup['id'];
							if ($po_detail_id1 > 0) {
								$sql_c_up = "UPDATE  package_materials_order_detail SET 	order_qty 			= '" . $order_qty[$i] . "',
																							order_price			= '" . $order_price[$i] . "',
																							order_case_pack		= '" . $case_pack[$i] . "',
																							enabled				= '1',
																							
																							update_timezone	= '" . $timezone . "',
																							update_date		= '" . $add_date . "',
																							update_by		= '" . $_SESSION['username'] . "',
																							update_ip		= '" . $add_ip . "'
											WHERE id = '" . $po_detail_id1 . "' ";
								$db->query($conn, $sql_c_up);
							}
						}
						$package_ids[$i] 			= "";
						$order_price[$i] 			= "";
						$order_qty[$i] 				= "";
						$case_pack[$i] 				= "";
						$i++;
					} else {
						// Check if all required array elements exist
						$sql6 = "INSERT INTO " . $selected_db_name . ".package_materials_order_detail (po_id, package_id, order_qty, order_price, order_case_pack, add_date, add_by, add_by_user_id, add_ip, add_timezone) 
								 VALUES ('" . $id . "', '" . $data_p . "', '" . $order_qty[$i] . "', '" . $order_price[$i] . "', '" . $case_pack[$i] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
						$ok = $db->query($conn, $sql6);
						if ($ok) {
							$k++; // Increment the counter only if the insertion is successful
						}
						$i++;
					}
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
		if (isset($stage_status) && $stage_status != "Committed") {
			$sql_msg 	= " SELECT DISTINCT c.sku_code, a.order_price
							FROM package_materials_order_detail a
							INNER JOIN package_materials_order_detail_receive b ON b.po_detail_id = a.id
							INNER JOIN packages c ON c.id = a.package_id
 							WHERE a.po_id	= '" . $id . "'
							AND IFNULL(b.id, 0) > 0
							AND a.enabled = 1 AND b.enabled = 1
							AND a.id NOT IN(" . $all_matches_po_detail_ids . ") ";
			// echo "<br><br><br><br>" . $sql_msg;
			$result_msg	= $db->query($conn, $sql_msg);
			$count_msg 	= $db->counter($result_msg);
			if ($count_msg > 0) {
				if (isset($error2['msg'])) {
					$error2['msg'] .= "<br>The SKU Code/s with following detail already has/have been recieved, Please remove receiving before removing from PO:<br>";
				} else {
					$error2['msg'] = "<br>The SKU Code/s with following detail already has/have been recieved, Please remove receiving before removing from PO:<br>";
				}
				$row_msg = $db->fetch($result_msg);
				foreach ($row_msg as $data_msg) {
					$sku_code				= $data_msg['sku_code'];
					$order_price_msg		= $data_msg['order_price'];
					$error2['msg'] .= "<br>SKU: " . $sku_code . ", Price: " . $order_price_msg;
				}
			}
		}
	}
}
