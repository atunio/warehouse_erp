<?php

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$courier_name				= 'courier_name';
	$tracking_no				= 'tracking_no';
	$shipment_date				= date('d/m/Y');
	$expected_receiving_date	= date('d/m/Y');
	$status_id					= '11';
	$logistics_cost				= '20';
}

if (isset($cmd3) && $cmd3 == 'delete' && isset($detail_id)) {
	if (po_permisions("Arrival") == 0) {
		$error2['msg'] = "You do not have add permissions.";
	} else {
		$sql_c_up = "UPDATE  purchase_order_detail_receive_rma_logistics 
												SET 	
													bill_of_landing		= '',
													sub_location_id		= '0',
													arrival_no			= '0',
													arrived_date		= NULL,
													logistics_status	= '" . $logistic_status_dynamic . "',

													update_timezone		= '" . $timezone . "',
													update_date			= '" . $add_date . "',
													update_by			= '" . $_SESSION['username'] . "',
													update_ip			= '" . $add_ip . "'
					WHERE id = '" . $detail_id . "' ";
		$ok = $db->query($conn, $sql_c_up);
		if ($ok) {

			$sql_ee1 = " SELECT b.* FROM purchase_order_detail_receive_rma_logistics b WHERE b.po_id = '" . $id . "' AND b.arrived_date IS NOT NULL";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql_c_up = "UPDATE  purchase_order_detail
							SET 	
								order_product_status	= '" . $logistic_status_dynamic . "',

								update_timezone			= '" . $timezone . "',
								update_date				= '" . $add_date . "',
								update_by				= '" . $_SESSION['username'] . "',
								update_ip				= '" . $add_ip . "'
						WHERE po_id = '" . $id . "' ";
				$db->query($conn, $sql_c_up);

				$sql_c_up = "UPDATE  purchase_orders
							SET 	
								order_status		= '" . $logistic_status_dynamic . "',

								update_timezone		= '" . $timezone . "',
								update_date			= '" . $add_date . "',
								update_by			= '" . $_SESSION['username'] . "',
								update_ip			= '" . $add_ip . "'
						WHERE id = '" . $id . "' ";
				$db->query($conn, $sql_c_up);
			}
			$msg2['msg_success'] = "Arrival record has been deleted successfully.";
		}
	}
}

if (isset($_POST['is_Submit_tab2']) && $_POST['is_Submit_tab2'] == 'Y') {
	extract($_POST);
	$shipment_date1			= "0000-00-00";
	$expected_receiving_date1	= "0000-00-00";

	if (!isset($status_id) || (isset($status_id)  && ($status_id == "0" || $status_id == ""))) {
		$error2['status_id'] = "Required";
	}
	if (isset($tracking_no) && $tracking_no == "") {
		$error2['tracking_no'] = "Required";
	}
	if (isset($shipment_date) && $shipment_date == "") {
		$error2['shipment_date'] = "Required";
	} else {
		$shipment_date1 = convert_date_mysql_slash($shipment_date);
	}
	if (isset($expected_receiving_date) && $expected_receiving_date == "") {
		$error2['expected_receiving_date'] = "Required";
	} else {
		$expected_receiving_date1 = convert_date_mysql_slash($expected_receiving_date);
	}
	if (!isset($logistic_rma_ids) || (isset($logistic_rma_ids) && sizeof($logistic_rma_ids) == 0)) {
		$error2['msg'] = "Select atleast one record to delete";
	}
	if (isset($courier_name) && $courier_name == "") {
		$error2['courier_name'] = "Required";
	}
	if (empty($error2)) {
		if (po_permisions("Retrun Logistics") == 0) {
			$error2['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			$per_logistics_cost = 0;
			if ($logistics_cost > 0 && sizeof($logistic_rma_ids) > 0) {
				$per_logistics_cost = ($logistics_cost / sizeof($logistic_rma_ids));
			}
			foreach ($logistic_rma_ids as $logistic_rma_id) {
				$sql_c_up = "UPDATE  purchase_order_detail_receive_rma 
											SET 
												courier_name			= '" . $courier_name . "',
												tracking_no				= '" . $tracking_no . "',
												shipment_date			= '" . $shipment_date1 . "',
												expected_receiving_date	= '" . $expected_receiving_date1 . "',
												logistics_status		= '" . $status_id . "', 
												logistics_cost			= '" . $per_logistics_cost . "', 
												
												update_timezone			= '" . $timezone . "',
												update_date				= '" . $add_date . "',
												update_by				= '" . $_SESSION['username'] . "',
												update_ip				= '" . $add_ip . "',
												update_from_module_id	= '" . $module_id . "'
							WHERE id = '" . $logistic_rma_id . "' ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$tracking_no = "";
					$k++;
					if (isset($error2['msg'])) unset($error2['msg']);
				}
			}
			if ($k > 0) {
				if ($k == 1) {
					$msg2['msg_success'] = $k . " record for logistic has been updated successfully.";
				} else {
					$msg2['msg_success'] = $k . " records for logistic have been updated successfully.";
				}
			}
		}
	} else {
		if (!isset($error2)) {
			$error2['msg'] = "Please check Error in form.";
		}
	}
}
