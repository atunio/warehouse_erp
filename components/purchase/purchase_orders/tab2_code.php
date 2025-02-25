<?php

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$tracking_no 			= date('YmdHis');
	$logistics_cost			= 20.5;
	$status_id 				= 10;
	// $courier_name 			= "courier_name " . date('YmdHis');
	// $shipment_date 			= date('d/m/Y');
	// $expected_arrival_date 	= date('d/m/Y');
}
if (isset($cmd2_1) && $cmd2_1 == 'edit' && isset($detail_id)) {
	$sql_ee1 = "SELECT b.*, d.logistics_cost
				FROM purchase_order_detail_logistics b 
				INNER JOIN purchase_orders d ON d.id = b.po_id
				WHERE b.id = '" . $detail_id . "'";
	// echo $sql_ee1;
	$result_ee1 	= $db->query($conn, $sql_ee1);
	$counter_ee1	= $db->counter($result_ee1);
	if ($counter_ee1 > 0) {
		$row_ee1 							= $db->fetch($result_ee1);
		$courier_name_update				= $row_ee1[0]['courier_name'];
		$tracking_no_update              	= $row_ee1[0]['tracking_no'];
		$shipment_date_update				= $row_ee1[0]['shipment_date'];
		$shipment_date_update				= str_replace("-", "/", convert_date_display($row_ee1[0]['shipment_date']));
		$expected_arrival_date_update		= str_replace("-", "/", convert_date_display($row_ee1[0]['expected_arrival_date']));
		$status_id_update					= $row_ee1[0]['logistics_status'];
		$logistics_cost_update				= $row_ee1[0]['logistics_cost'];
		$no_of_boxes_update					= $row_ee1[0]['no_of_boxes'];
	} else {
		$error4['msg'] = "No record found";
	}
}
if (isset($cmd2_1) && $cmd2_1 == 'delete' && isset($detail_id)) {
	if (po_permisions("Logistics") == 0) {
		$error2['msg'] = "You do not have add permissions.";
	} else {
		$sql_ee1 = " DELETE FROM purchase_order_detail_logistics WHERE id = '" . $detail_id . "'";
		$ok = $db->query($conn, $sql_ee1);
		if ($ok) {
			$sql_ee1 = " SELECT b.* FROM purchase_order_detail_logistics b WHERE b.po_id = '" . $id . "'";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql_c_up = "UPDATE  purchase_order_detail
												SET 	
													order_product_status	= '" . $before_logistic_status_dynamic . "',

													update_timezone			= '" . $timezone . "',
													update_date				= '" . $add_date . "',
													update_by				= '" . $_SESSION['username'] . "',
													update_ip				= '" . $add_ip . "'
						WHERE po_id = '" . $id . "' ";
				$db->query($conn, $sql_c_up);

				$sql_c_up = "UPDATE  purchase_orders
												SET 	
													order_status		= '" . $before_logistic_status_dynamic . "',

													update_timezone		= '" . $timezone . "',
													update_date			= '" . $add_date . "',
													update_by			= '" . $_SESSION['username'] . "',
													update_ip			= '" . $add_ip . "'
						WHERE id = '" . $id . "' ";
				$db->query($conn, $sql_c_up);

				$table		= "inventory_status";
				$columns	= array("status_name");
				$get_col_from_table = get_col_from_table($db, $conn, $selected_db_name, $table, $before_logistic_status_dynamic, $columns);
				foreach ($get_col_from_table as $array_key1 => $array_data1) {
					${$array_key1} = $array_data1;
				}
			}
			$msg2['msg_success'] = "Record has been added successfully.";
			unset($cmd2_1);
		}
	}
}

if (isset($_POST['is_Submit_tab2']) && $_POST['is_Submit_tab2'] == 'Y') {
	extract($_POST);
	if (!isset($status_id) || (isset($status_id)  && ($status_id == "0" || $status_id == ""))) {
		$error2['status_id'] = "Required";
	}
	if (!isset($no_of_boxes) || (isset($no_of_boxes)  && ($no_of_boxes == "0" || $no_of_boxes == ""))) {
		$error2['no_of_boxes'] = "Required";
	}
	if (!isset($logistics_cost) || (isset($logistics_cost)  && ($logistics_cost == ""))) {
		$error2['logistics_cost'] = "Required";
	}
	if (isset($tracking_no) && $tracking_no == "") {
		$error2['tracking_no'] = "Required";
	} else {
		$sql_dup	= " SELECT a.* FROM purchase_order_detail_logistics a 
						WHERE  a.tracking_no = '" . $tracking_no . "' "; //echo $sql_dup;
		$result_dup	= $db->query($conn, $sql_dup);
		$count_dup	= $db->counter($result_dup);
		if ($count_dup > 0) {
			$error2['tracking_no'] = "The tracking no is already exist.";
		}
	}
	if (isset($shipment_date) && $shipment_date == "") {
		$shipment_date1 = NULL;
	} else {
		$shipment_date1 = convert_date_mysql_slash($shipment_date);
	}
	if (isset($expected_arrival_date) && $expected_arrival_date == "") {
		$expected_arrival_date1	= NULL;
	} else {
		$expected_arrival_date1 = convert_date_mysql_slash($expected_arrival_date);
	}
	if (!isset($id) || (isset($id)  && ($id == "0" || $id == ""))) {
		$error2['msg'] = "Please add master record first";
	}
	if (empty($error2)) {
		if (po_permisions("Logistics") == 0) {
			$error2['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			if ($cmd2 == 'add') {

				if (access("add_perm") == 0) {
					$error2['msg'] = "You do not have add permissions.";
				} else {
					$sql6 = "INSERT INTO purchase_order_detail_logistics(po_id, courier_name, tracking_no, shipment_date, expected_arrival_date, logistics_status, no_of_boxes, add_date, add_by, add_ip, add_timezone)
							 VALUES('" . $id . "', '" . $courier_name . "', '" . $tracking_no . "', '"  . $shipment_date1  . "', '" . $expected_arrival_date1  . "', '" . $status_id  . "', '" . $no_of_boxes  . "',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$tracking_no	= "";
						$no_of_boxes 	= 1;
						$sql_c_up = "UPDATE  purchase_order_detail SET order_product_status	= '" . $status_id . "',
																		update_timezone		= '" . $timezone . "',
																		update_date			= '" . $add_date . "',
																		update_by			= '" . $_SESSION['username'] . "',
																		update_ip			= '" . $add_ip . "'
									WHERE po_id = '" . $id . "' ";
						$db->query($conn, $sql_c_up);
						$k++;
						if (isset($error2['msg'])) unset($error2['msg']);
					} else {
						$error2['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				}
			}

			if ($k > 0) {
				$sql_c_up = "UPDATE  purchase_orders SET 	order_status	= '" . $logistic_status_dynamic . "',
															logistics_cost	= '" . $logistics_cost . "',
															update_timezone	= '" . $timezone . "',
															update_date		= '" . $add_date . "',
															update_by		= '" . $_SESSION['username'] . "',
															update_ip		= '" . $add_ip . "'
						WHERE id = '" . $id . "' ";
				$db->query($conn, $sql_c_up);

				$disp_status_name = get_status_name($db, $conn, $logistic_status_dynamic);

				if (isset($msg2['msg_success'])) {
					$msg2['msg_success'] .= "<br>Logistics info has been added successfully.";
				} else {
					$msg2['msg_success'] = "Logistics info has been added successfully.";
				}
				if ($_SERVER['HTTP_HOST'] != 'localhost') {
					$tracking_no = "";
				}
				if ($_SERVER['HTTP_HOST'] == 'localhost') {
					$tracking_no = date('YmdHis');
				}
			}
		}
	} else {
		$error2['msg'] = "Please check Error in form.";
	}
}
if (isset($_POST['is_Submit_tab2_1']) && $_POST['is_Submit_tab2_1'] == 'Y') {
	extract($_POST);
	if (!isset($no_of_boxes_update) || (isset($no_of_boxes_update)  && ($no_of_boxes_update == "0" || $no_of_boxes_update == ""))) {
		$error2['no_of_boxes_update'] = "Required";
	}
	$shipment_date_update1 = $expected_arrival_date_update1	= NULL;
	if (isset($logistics_cost_update) && $logistics_cost_update == "") {
		$error2['logistics_cost_update'] = "Required";
	}
	if (!isset($status_id_update) || (isset($status_id_update)  && ($status_id_update == "0" || $status_id_update == ""))) {
		$error2['status_id_update'] = "Required";
	}
	if (isset($tracking_no_update) && $tracking_no_update == "") {
		$error2['tracking_no_update'] = "Required";
	} else {
		$sql_dup	= " SELECT a.* FROM purchase_order_detail_logistics a 
						WHERE  a.tracking_no = '" . $tracking_no_update . "'
						AND id != '" . $detail_id . "' ";
		//echo $sql_dup;
		$result_dup	= $db->query($conn, $sql_dup);
		$count_dup	= $db->counter($result_dup);
		if ($count_dup > 0) {
			$error2['tracking_no_update'] = "The tracking no is already exist.";
		}
	}
	if (!empty($shipment_date_update)) {
		$shipment_date_update1 = convert_date_mysql_slash($shipment_date_update);
	}
	if (!empty($expected_arrival_date_update)) {
		$expected_arrival_date_update1 = convert_date_mysql_slash($expected_arrival_date_update);
	}
	if (!isset($id) || (isset($id)  && ($id == "0" || $id == ""))) {
		$error2['msg'] = "Please add master record first";
	}
	if (!isset($detail_id) || (isset($detail_id)  && ($detail_id == "0" || $detail_id == ""))) {
		$error2['msg'] = "Please click to edit anyone record";
	}
	if (empty($error2)) {
		if (po_permisions("Logistics") == 0) {
			$error2['msg'] = "You do not have add permissions.";
		} else {
			$sql_c_up = "UPDATE  purchase_order_detail_logistics 
										SET 
											courier_name 			= '" . $courier_name_update . "',
											tracking_no 			= '" . $tracking_no_update . "',
											shipment_date 			= '" . $shipment_date_update1 . "',
											expected_arrival_date 	= '" . $expected_arrival_date_update1 . "',
											logistics_status		= '" . $status_id_update . "',
											logistics_cost			= '" . $logistics_cost_update . "',
											no_of_boxes				= '" . $no_of_boxes_update . "',
											
											update_timezone			= '" . $timezone . "',
											update_date				= '" . $add_date . "',
											update_by				= '" . $_SESSION['username'] . "',
											update_ip				= '" . $add_ip . "'
						WHERE id = '" . $detail_id . "' ";
			$ok = $db->query($conn, $sql_c_up);
			if ($ok) {

				$sql_c_up = "UPDATE  purchase_orders
											SET 	
												logistics_cost		= '" . $logistics_cost_update . "',

												update_timezone		= '" . $timezone . "',
												update_date			= '" . $add_date . "',
												update_by			= '" . $_SESSION['username'] . "',
												update_ip			= '" . $add_ip . "'
							WHERE id = '" . $id . "' ";
				$db->query($conn, $sql_c_up);

				$sql_ee1 = " SELECT b.* FROM purchase_order_detail_logistics b WHERE b.po_id = '" . $id . "'";
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

					$table		= "inventory_status";
					$columns	= array("status_name");
					$get_col_from_table = get_col_from_table($db, $conn, $selected_db_name, $table, $before_logistic_status_dynamic, $columns);
					foreach ($get_col_from_table as $array_key1 => $array_data1) {
						${$array_key1} = $array_data1;
					}
				}
				$msg2['msg_success'] = "Record has been updated successfully.";
			} else {
				$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
			}
		}
	} else {
		$error2['msg'] = "Please check the error in form.";
	}
}
if (isset($_POST['is_Submit_tab2_3']) && $_POST['is_Submit_tab2_3'] == 'Y') {
	extract($_POST);

	if (!isset($logistics_status) || (isset($logistics_status)  && ($logistics_status == "0" || $logistics_status == ""))) {
		$error2['logistics_status'] = "Required";
	}
	if (empty($error2)) {
		if (po_permisions("Logistics") == 0) {
			$error2['msg'] = "You do not have add permissions.";
		} else {
			if (isset($logistics_ids) && sizeof($logistics_ids) > 0) {
				$k = 0;
				foreach ($logistics_ids as $logistics_id) {
					$sql_c_up = "UPDATE  purchase_order_detail_logistics SET 	logistics_status	= '" . $logistics_status . "',
																				update_timezone		= '" . $timezone . "',
																				update_date			= '" . $add_date . "',
																				update_by			= '" . $_SESSION['username'] . "',
																				update_ip			= '" . $add_ip . "'
										WHERE id = '" . $logistics_id . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {

						$sql_ee1 = " SELECT b.* FROM purchase_order_detail_logistics b WHERE b.po_id = '" . $id . "'";
						// echo $sql_ee1;
						$result_ee1 	= $db->query($conn, $sql_ee1);
						$counter_ee1	= $db->counter($result_ee1);
						if ($counter_ee1 == 0) {
							$sql_c_up = "UPDATE  purchase_order_detail
													SET 	
														order_product_status	= '" . $logistics_status . "',

														update_timezone			= '" . $timezone . "',
														update_date				= '" . $add_date . "',
														update_by				= '" . $_SESSION['username'] . "',
														update_ip				= '" . $add_ip . "'
							WHERE po_id = '" . $id . "' ";
							$db->query($conn, $sql_c_up);

							$sql_c_up = "UPDATE  purchase_orders
													SET 	
														order_status		= '" . $logistics_status . "',

														update_timezone		= '" . $timezone . "',
														update_date			= '" . $add_date . "',
														update_by			= '" . $_SESSION['username'] . "',
														update_ip			= '" . $add_ip . "'
							WHERE id = '" . $id . "' ";
							$db->query($conn, $sql_c_up);

							$table		= "inventory_status";
							$columns	= array("status_name");
							$get_col_from_table = get_col_from_table($db, $conn, $selected_db_name, $table, $before_logistic_status_dynamic, $columns);
							foreach ($get_col_from_table as $array_key1 => $array_data1) {
								${$array_key1} = $array_data1;
							}
						}

						$k++;
						if (isset($error2['msg'])) unset($error2['msg']);
					} else {
						$error2['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				}
				if ($k > 0) {
					if (isset($msg2['msg_success'])) {
						$msg2['msg_success'] .= "<br>Logistics status  has been updated successfully.";
					} else {
						$msg2['msg_success'] = "Logistics status has been added successfully.";
					}
					$logistics_status = "";
				}
			} else {
				$error2['msg'] = "Please select atleast one record.";
			}
		}
	} else {
		$error2['msg'] = "Please check required fields in the form.";
	}
}
