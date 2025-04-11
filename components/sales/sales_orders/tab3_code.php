<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if ($_SERVER['HTTP_HOST'] == HTTP_HOST_IP && (!isset($cmd2) || (isset($cmd2) && $cmd2 != 'edit'))) {
	$shipment_courier_id	= 1;
	$shipment_tracking_no	= date('YmdHis');
	$shipment_sent_date		= date('d/m/Y');
	$expected_delivery_date	= date('d/m/Y');
}
if (isset($cmd2) && $cmd2 == 'edit' && isset($detail_id)) {
	$sql_ee1 = "SELECT b.* 
				FROM sales_order_shipments b 
 				WHERE b.id = '" . $detail_id . "'";
	// echo $sql_ee1;
	$result_ee1 	= $db->query($conn, $sql_ee1);
	$counter_ee1	= $db->counter($result_ee1);
	if ($counter_ee1 > 0) {
		$row_ee1						= $db->fetch($result_ee1);
		$shipment_courier_id			= $row_ee1[0]['shipment_courier_id'];
		$shipment_tracking_no			= $row_ee1[0]['shipment_tracking_no'];
		$location_id					= $row_ee1[0]['location_id'];
		$shipment_sent_date				= str_replace("-", "/", convert_date_display($row_ee1[0]['shipment_sent_date']));
		$expected_delivery_date			= str_replace("-", "/", convert_date_display($row_ee1[0]['expected_delivery_date']));
	} else {
		$error3['msg'] = "No record found";
	}
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($cmd2) && $cmd2 == 'delete' && isset($detail_id)) {
	$sql_ee1 = " DELETE FROM sales_order_detail_packing WHERE id = '" . $detail_id . "'";
	$ok = $db->query($conn, $sql_ee1);
	if ($ok) {
		$msg2['msg_success'] = "Record has been added successfully.";
	}
}

if (isset($_POST['is_Submit_tab3']) && $_POST['is_Submit_tab3'] == 'Y') {
	$field_name = "packing_type";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3[${$field_name}] = "Required";
	}
	$field_name = "box_no";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == ""))) {
		$error3[${$field_name}] = "Required";
	}
	$field_name = "product_stock_id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3[${$field_name}] = "Required";
	}
	if (empty($error3)) {
		$sql_pd01 		= "	SELECT a.* 
							FROM sales_order_detail_packing a 
							WHERE a.enabled = 1  
							AND a.sale_order_id	= '" . $id . "'
							AND a.product_stock_id	= '" . $product_stock_id . "'  ";
		$result_pd01	= $db->query($conn, $sql_pd01);
		$count_pd01		= $db->counter($result_pd01);
		if ($count_pd01 == 0) {
			$sql_in = "INSERT INTO sales_order_detail_packing (sale_order_id, product_stock_id, packing_type,box_no,pallet_no, 
														add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id) 
						VALUES ('" . $id . "','" . $product_stock_id . "','" . $packing_type . "', '" . $box_no . "' , '" . $pallet_no . "',
							'" . $add_date . "','" . $_SESSION['username'] . "','" . $_SESSION['user_id'] . "','" . $add_ip . "', '" . $timezone . "' ,'" . $module_id . "') ";
			$ok = $db->query($conn, $sql_in);
			if ($ok) {
				$sql_c_up = "UPDATE  product_stock SET 	is_packed						= '1',
															
														update_by						= '" . $_SESSION['username'] . "',
														update_by_user_id				= '" . $_SESSION['user_id'] . "',
														update_timezone					= '" . $timezone . "',
														update_date						= '" . $add_date . "',
														update_ip						= '" . $add_ip . "',
														update_from_module_id			= '" . $module_id . "'
					WHERE id = '" . $product_stock_id . "' ";
				$db->query($conn, $sql_c_up);
				$product_stock_id = "";
				$msg3['msg_success'] = "Product has been added in packing successfully.";
				update_so_detail_status($db, $conn, $id, $module_id, $packing_status_dynamic);
				update_so_status($db, $conn, $id, $module_id, $packing_status_dynamic);
			} else {
				$error3['msg'] = "There is error, Please check it.";
			}
		} else {
			$error3['msg'] = "The product is already added in packing";
		}
	} else {
		$error3['msg'] = "Please check Error in form.";
	}
}
if (isset($_POST['is_Submit_tab3_1']) && $_POST['is_Submit_tab3_1'] == 'Y') {
	extract($_POST);
	if (!isset($bulkpacked) || (isset($bulkpacked) && sizeof($bulkpacked) == 0)) {
		$error4['msg'] = "Select atleast one record to unpack / remove from packing";
	}
	if (!isset($packing_type_bulk) || (isset($packing_type_bulk)  && ($packing_type_bulk == "0" || $packing_type_bulk == ""))) {
		$error4['packing_type_bulk'] = "Required";
	}
	if (isset($box_no_bulk) && $box_no_bulk == "") {
		$error4['box_no_bulk'] = "Required";
	}

	if (empty($error4)) {
		$k = 0;
		foreach ($bulkpacked as $bulkpack) {

			$sql_pd01 		= "	SELECT a.* 
							FROM sales_order_detail_packing a 
							WHERE a.enabled = 1  
							AND a.sale_order_id	= '" . $id . "'
							AND a.serial_no_barcode	= '" . $bulkpack . "'  ";
			$result_pd01	= $db->query($conn, $sql_pd01);
			$count_pd01		= $db->counter($result_pd01);
			if ($count_pd01 == 0) {
				$sql_in = "INSERT INTO sales_order_detail_packing (sale_order_id, product_stock_id, packing_type,box_no,pallet_no, 
															add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id) 
							VALUES ('" . $id . "','" . $bulkpack . "','" . $packing_type_bulk . "', '" . $box_no_bulk . "' , '" . $pallet_no_bulk . "',
								'" . $add_date . "','" . $_SESSION['username'] . "','" . $_SESSION['user_id'] . "','" . $add_ip . "', '" . $timezone . "' ,'" . $module_id . "') ";
				$ok = $db->query($conn, $sql_in);
				if ($ok) {
					$sql_c_up = "UPDATE  product_stock SET 	is_packed						= '1',
 																
															update_by						= '" . $_SESSION['username'] . "',
															update_by_user_id				= '" . $_SESSION['user_id'] . "',
															update_timezone					= '" . $timezone . "',
															update_date						= '" . $add_date . "',
															update_ip						= '" . $add_ip . "',
															update_from_module_id			= '" . $module_id . "'
						WHERE id = '" . $bulkpack . "' ";
					$db->query($conn, $sql_c_up);
					$msg4['msg_success'] = "Bulk Products has been added in packing successfully.";
					update_so_detail_status($db, $conn, $id, $module_id, $packing_status_dynamic);
					update_so_status($db, $conn, $id, $module_id, $packing_status_dynamic);
				} else {
					$error4['msg'] = "There is error, Please check it.";
				}
			} else {
				$error4['msg'] = "The product is already added in packing";
			}
		}
		$box_no_bulk = "";
	} else {
		$error4['msg'] = "Please check Error in form.";
	}
}
if (isset($_POST['is_Submit_tab3_2']) && $_POST['is_Submit_tab3_2'] == 'Y') {
	extract($_POST);
	if (!isset($sub_location_pack) || (isset($sub_location_pack)  && ($sub_location_pack == "0" || $sub_location_pack == ""))) {
		$error3['sub_location_pack'] = "Required";
	}
	if (!isset($packedItems) || (isset($packedItems) && sizeof($packedItems) == 0)) {
		$error3['msg'] = "Select atleast one record to unpack / remove from packing";
	}
	if (empty($error3)) {
		$k = 0;
		foreach ($packedItems as $packedItem) {
			$packedItem_array 	= explode("^", $packedItem);
			$packing_id 		= $packedItem_array[0];
			$stock_id 			= $packedItem_array[1];

			$sql_c_up = "DELETE FROM  sales_order_detail_packing  WHERE id = '" . $packing_id . "' ";
			$ok = $db->query($conn, $sql_c_up);
			if ($ok) {

				$sql_c_up = "UPDATE   product_stock SET is_packed =0, sub_location = '" . $sub_location_pack . "' WHERE id = '" . $stock_id . "' ";
				$db->query($conn, $sql_c_up);

				update_so_detail_status($db, $conn, $id, $module_id, $stock_id, 1);

				$k++;
			}
		}
		if ($k > 0) {
			update_so_status($db, $conn, $id, $module_id, 1);
			if ($k == 1) {
				$msg3['msg_success'] = $k . " record has been packed successfully.";
			} else {
				$msg3['msg_success'] = $k . " records have been packed successfully.";
			}
		}
	}
}
if (isset($_POST['is_Submit_tab3_3']) && $_POST['is_Submit_tab3_3'] == 'Y') {
	extract($_POST);
	if (empty($error3)) {
		$k = 0;
		foreach ($box_no_array as $box_no1) {

			$box_weight_val = $box_weight[$box_no1];
			$box_height_val = $box_height[$box_no1];
			$box_width_val 	= $box_width[$box_no1];

			$sql_pd01 		= "	SELECT a.* 
								FROM sale_order_box_dimensions a 
								WHERE a.enabled = 1  
								AND a.sale_order_id	= '" . $id . "'
								AND a.box_no		= '" . $box_no1 . "'  ";
			$result_pd01	= $db->query($conn, $sql_pd01);
			$count_pd01		= $db->counter($result_pd01);
			if ($count_pd01 == 0) {
				$sql_in = "INSERT INTO sale_order_box_dimensions(sale_order_id, box_no, box_weight, box_height, box_width, 
															add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id) 
							VALUES ('" . $id . "','" . $box_no1 . "','" . $box_weight_val . "', '" . $box_height_val . "' , '" . $box_width_val . "',
								'" . $add_date . "','" . $_SESSION['username'] . "','" . $_SESSION['user_id'] . "','" . $add_ip . "', '" . $timezone . "' ,'" . $module_id . "') ";
				$ok = $db->query($conn, $sql_in);
				if ($ok) {
					$k++;
				}
			} else {
				$sql_c_up = "UPDATE  sale_order_box_dimensions SET 	box_weight				= '" . $box_weight_val . "',
																	box_height				= '" . $box_height_val . "',
																	box_width				= '" . $box_width_val . "',

																	update_by				= '" . $_SESSION['username'] . "',
																	update_by_user_id		= '" . $_SESSION['user_id'] . "',
																	update_timezone			= '" . $timezone . "',
																	update_date				= '" . $add_date . "',
																	update_ip				= '" . $add_ip . "',
																	update_from_module_id	= '" . $module_id . "'
					WHERE 1 = 1
					AND sale_order_id 	= '" . $id . "' 
					AND box_no 			= '" . $box_no1 . "' ";
				$db->query($conn, $sql_c_up);
				$k++;
			}
		}
		if ($k > 0) {
			if ($k == 1) {
				$msg3['msg_success'] = $k . " record has been updated successfully.";
			} else {
				$msg3['msg_success'] = $k . " records have been updated successfully.";
			}
		}
	}
}
