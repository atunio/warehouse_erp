<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$vender_id					= "1";
	$po_date 					= date('d/m/Y');
	$po_desc					= "purchase order desc : " . date('YmdHis');
	$is_tested_po				= "Yes";
	$is_wiped_po				= "Yes";
	$is_imaged_po				= "Yes";
	$vender_invoice_no			= date('YmdHis');
	$order_status 				= 1;
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

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
$disp_status_name  = "In Process";
if ($cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee					= " SELECT a.*, b.status_name
								FROM purchase_orders a
								LEFT JOIN inventory_status b ON b.id = a.order_status
								WHERE a.id = '" . $id . "'"; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$vender_id				=  $row_ee[0]['vender_id'];
	$disp_status_name		=  $row_ee[0]['status_name'];
	$po_desc				= $row_ee[0]['po_desc'];
	$po_no					= $row_ee[0]['po_no'];
	$po_desc_public			= $row_ee[0]['po_desc_public'];
	$vender_invoice_no		= $row_ee[0]['vender_invoice_no'];
	$is_tested_po			= $row_ee[0]['is_tested_po'];
	$is_wiped_po			= $row_ee[0]['is_wiped_po'];
	$is_imaged_po			= $row_ee[0]['is_imaged_po'];
	$order_status           = $row_ee[0]['order_status'];
	$po_date				= str_replace("-", "/", convert_date_display($row_ee[0]['po_date']));

	$product_condition 		= [];
	$order_price 			= [];
	$order_qty 				= [];
	$expected_status		= [];
	$product_ids			= [];

	$sql_ee1	= "SELECT a.* FROM purchase_order_detail a WHERE a.po_id = '" . $id . "' ";
	$result_ee1	= $db->query($conn, $sql_ee1);
	$count_ee1  = $db->counter($result_ee1);
	if ($count_ee1 > 0) {
		$row_ee1	= $db->fetch($result_ee1);

		foreach ($row_ee1 as $data2) {
			$product_condition[]	= $data2['product_condition'];
			$order_price[]			= $data2['order_price'];
			$order_qty[]			= $data2['order_qty'];
			$product_ids[]			= $data2['product_id'];
			$expected_status[]		= $data2['expected_status'];
		}
	}

	$package_id 				= [];
	$order_part_qty 			= [];
	$order_part_price			= [];
	$case_pack					= [];
	$sql_ee1		= " SELECT a.*,b.case_pack 
						FROM package_materials_order_detail a
						INNER JOIN packages b ON b.id = a.package_id WHERE a.po_id = '" . $id . "' ";  //echo $sql_ee1;
	$result_ee1		= $db->query($conn, $sql_ee1);
	$count_ee1  	= $db->counter($result_ee1);
	if ($count_ee1 > 0) {
		$row_ee1	= $db->fetch($result_ee1);
		foreach ($row_ee1 as $data2) {
			$package_id[]				= $data2['package_id'];
			$order_part_qty[]			= $data2['order_qty'];
			$order_part_price[]			= $data2['order_price'];
			$case_pack[]				= $data2['case_pack'];
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
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM purchase_orders a 
								WHERE a.vender_id		= '" . $vender_id . "'
								AND a.vender_invoice_no	= '" . $vender_invoice_no . "' 
								AND a.po_date			= '" . $po_date1 . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_orders(subscriber_users_id, vender_id, vender_invoice_no, po_date, is_tested_po,  is_wiped_po, is_imaged_po, add_date, add_by, add_by_user_id, add_ip, add_timezone)
							 VALUES('" . $subscriber_users_id . "', '" . $vender_id . "', '" . $vender_invoice_no . "', '" . $po_date1  . "', '" . $is_tested_po  . "', '" . $is_wiped_po  . "', '" . $is_imaged_po  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id				= mysqli_insert_id($conn);
						$po_no			= "PO" . $id;
						$order_status	= 1;

						$sql6 = " UPDATE purchase_orders SET po_no = '" . $po_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						$msg['msg_success'] = "Purchase Order has been created successfully.";
						$cmd = 'edit';
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
		$po_date1 = NULL;
		if (isset($po_date) && $po_date != "") {
			$po_date1 = convert_date_mysql_slash($po_date);
		}
		$sql_c_up = "UPDATE purchase_orders SET	vender_id				= '" . $vender_id . "',
												po_date					= '" . $po_date1 . "',
 												po_desc					= '" . $po_desc . "', 
												po_desc_public			= '" . $po_desc_public . "', 
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
		$k = 0;
		if (isset($order_status) && ($order_status == 1 || $order_status == 4 || $order_status == 10 || $order_status == 12)) {
			$sql_dup = " DELETE FROM purchase_order_detail WHERE po_id	= '" . $id . "'";
			$db->query($conn, $sql_dup);

			$filtered_product_ids = array_values(array_filter($product_ids));
			$i = 0; // Initialize the counter before the loop
			$r = 1;
			foreach ($filtered_product_ids as $data_p) {

				$sql_dup 	= "SELECT a.* FROM purchase_order_detail a WHERE a.po_id = '" . $id . "' AND a.product_id = '" . $data_p . "'";
				$result_dup = $db->query($conn, $sql_dup);
				$count_dup 	= $db->counter($result_dup);

				if ($count_dup == 0) {
					// Check if all required array elements exist
					$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_order_detail (po_id, product_id, order_qty, order_price, product_condition, expected_status , add_date, add_by, add_by_user_id, add_ip, add_timezone) 
							 VALUES ('" . $id . "', '" . $data_p . "', '" . $order_qty[$i] . "', '" . $order_price[$i] . "', '" . $product_condition[$i] . "', '" . $expected_status[$i] . "','" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$k++; // Increment the counter only if the insertion is successful
					}
					$i++;
				} else {
					$product_ids[$i] 			= "";
					$product_condition[$i] 		= "";
					$order_price[$i] 			= "";
					$order_qty[$i] 				= "";
					$expected_status[$i] 		= "";
					$i++;
				}
			}
			if (isset($package_ids)) {
				$sql_dup = " DELETE FROM purchase_order_packages_detail WHERE po_id	= '" . $id . "'";
				$db->query($conn, $sql_dup);

				$filtered_id = (array_values(array_filter($package_ids)));

				$ii = 0; // Initialize the counter before the loop
				$rr = 1;
				foreach ($filtered_id as $package_id) {
					$sql_dup	= " SELECT a.* 
									FROM purchase_order_packages_detail a 
									WHERE a.po_id	= '" . $id . "'
									AND a.package_id	= '" . $package_id . "' ";
					$result_dup	= $db->query($conn, $sql_dup);
					$count_dup	= $db->counter($result_dup);
					if ($count_dup == 0) {
						$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_order_packages_detail(po_id, package_id,  order_qty, order_price, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
								VALUES('" . $id . "', '" . $package_id . "',  '" . $order_part_qty[$ii]  . "', '" . $order_part_price[$ii] . "' ,'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
						$ok = $db->query($conn, $sql6);
						if ($ok) {
							$k++; // Increment the counter only if the insertion is successful
						}
						$ii++;
					} else {
						$package_ids[$ii] 		= "";
						$order_part_qty[$ii] 	= "";
						$order_part_price[$ii] 	= "";
						$ii++;
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
		if ($logistics_status != "") {
			$order_status =  $logistics_status;
		}
	}
}
if (isset($_POST['is_Submit_tab5']) && $_POST['is_Submit_tab5'] == 'Y') {
	if (empty($error5)) {
		$order_status =  $receive_status_dynamic;
	}
}
if (isset($_POST['is_Submit_tab5_2']) && $_POST['is_Submit_tab5_2'] == 'Y') {
	if (empty($error5)) {
		$order_status =  $receive_status_dynamic;
	}
}
