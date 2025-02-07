<?php


if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$sub_location_id_barcode	= 1737;
	$product_id_barcode 		= 1;
	$logistic_id_barcode 		= 1;
	$sub_location_id_manual		= 1737;
	$logistic_id 				= 1;
	// $receiving_qties[5] 		= 15;
	// $receiving_location[5]	= 1737;
	$product_id_manual 			= 1;
	$logistic_id_manual			= 1;
	$serial_no_manual			= array("DMQD7TMFMF3M1", "DMQD7TMFMF3M2", "DMQD7TMFMF3M3", "DMQD7TMFMF3M4", "DMQD7TMFMF3M5", "DMQD7TMFMF3M6", "DMQD7TMFMF3M7", "DMQD7TMFMF3M8", "R72F1QJ62X", "F9FRFN0HGHKH", "DLXN2FKQFK10");
}

if (isset($cmd5) && $cmd5 == 'delete' && isset($detail_id)) {
	$sql_c_up = "DELETE FROM  return_items_detail_receive  WHERE id = '" . $detail_id . "' ";
	$ok = $db->query($conn, $sql_c_up);
	if ($ok) {
		$msg5['msg_success'] = "Record has been deleted successfully.";
	}
}

if (isset($_POST['is_Submit_tab5_4_2']) && $_POST['is_Submit_tab5_4_2'] == 'Y') {
	extract($_POST);
	if (!isset($receviedProductIds) || (isset($receviedProductIds) && sizeof($receviedProductIds) == 0)) {
		$error5['msg'] = "Select atleast one record to delete";
	}
	if (empty($error5)) {
		if (po_permisions("Receive") == 0) {
			$error5['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			foreach ($receviedProductIds as $receviedProductId) {
				$receviedProductId_array = explode("-", $receviedProductId);
				if ($receviedProductId_array[0] == 'CateogryReceived') {
					$delete_id = " recevied_product_category = '" . $receviedProductId_array[1] . "' AND return_id = '" . $id . "' ";
				} else {
					$delete_id = " id= '" . $receviedProductId_array[1] . "' ";
				}
				$sql_c_up = "DELETE FROM  return_items_detail_receive  
							WHERE " . $delete_id . " AND (is_diagnost = 0 OR is_diagnostic_bypass = 1) ";
				//echo "<br><br>" . $sql_c_up;
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$sql_c_up = "DELETE FROM  product_stock WHERE receive_id = '" . $receviedProductId . "' ";
					$db->query($conn, $sql_c_up);
					$k++;
				}
			}
			if ($k > 0) {
				if ($k == 1) {
					$msg5['msg_success'] = $k . " record has been deleted successfully.";
				} else {
					$msg5['msg_success'] = $k . " records have been deleted successfully.";
				}
			}
		}
	}
}

if (isset($_POST['is_Submit_tab5_2']) && $_POST['is_Submit_tab5_2'] == 'Y') {


	extract($_POST);

	if (!isset($serial_no_barcode) || (isset($serial_no_barcode)  && ($serial_no_barcode == "0" || $serial_no_barcode == ""))) {
		$error5['serial_no_barcode'] = "Required";
	}
	if (!isset($sub_location_id_barcode) || (isset($sub_location_id_barcode)  && ($sub_location_id_barcode == "0" || $sub_location_id_barcode == ""))) {
		$error5['sub_location_id_barcode'] = "Required";
	}
	if (!isset($product_id_barcode) || (isset($product_id_barcode)  && ($product_id_barcode == "0" || $product_id_barcode == ""))) {
		$error5['product_id_barcode'] = "Required";
	}
	if (empty($error5)) {
		if (po_permisions("Receive") == 0) {
			$error5['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			$sql_ee1 = "SELECT a.* FROM return_items_detail_receive a 
						INNER JOIN return_items_detail b ON b.id = a.ro_detail_id
						WHERE a.enabled = 1 
						AND ( 
								b.return_id = '" . $id . "'
								AND a.serial_no_barcode = '" . $serial_no_barcode . "'
							) ";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {

				$product_uniqueid_main1 = "";
				$package_id1 = $package_material_qty1 = $package_material_qty_received1 = 0;

				$sql_pd3		= "	SELECT a.product_id, a.product_condition, c.product_uniqueid, a.order_price,a.expected_status
									FROM return_items_detail a 
									INNER JOIN products c ON c.id = a.product_id
									INNER JOIN returns a2 ON a2.id = a.return_id
									WHERE 1 = 1
									AND a.id 	= '" . $product_id_barcode . "'";
				$result_pd3		= $db->query($conn, $sql_pd3);
				$count_pd3		= $db->counter($result_pd3);
				if ($count_pd3 > 0) {
					$row_pd3 						= $db->fetch($result_pd3);
					$order_price					= $row_pd3[0]['order_price'];
					$product_uniqueid_main1			= $row_pd3[0]['product_uniqueid'];
					$c_product_id2 					= $row_pd3[0]['product_id'];
					$c_product_condition2 			= $row_pd3[0]['product_condition'];
					$c_expected_status2     		= $row_pd3[0]['expected_status'];
 					$sql6 = "INSERT INTO return_items_detail_receive(base_product_id, ro_detail_id, serial_no_barcode, price, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone)
							VALUES('" . $product_uniqueid_main1 . "', '" . $product_id_barcode . "', '" . $serial_no_barcode . "',  '" . $order_price . "', '" . $_SESSION['user_id'] . "', '" . $sub_location_id_barcode . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {

						$receive_id = mysqli_insert_id($conn);
						/////////////////////////// Create Stock  START /////////////////////////////

							$sql6 = "INSERT INTO product_stock(subscriber_users_id, receive_id, product_id, p_total_stock, stock_grade, p_inventory_status, sub_location,  add_by_user_id, add_date, add_by, add_ip, add_timezone)
									 VALUES('" . $subscriber_users_id . "', '" . $receive_id . "', '" . $c_product_id2 . "', 1, '" . $c_product_condition2 . "', '" . $c_expected_status2 . "', '" . $sub_location_id_barcode . "', '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
							$db->query($conn, $sql6);

							if (isset($serial_no_barcode) && $serial_no_barcode == '') {
								$serial_no_barcode = "GEN" . $receive_id;
							}

							$sql_c_up = "UPDATE return_items_detail_receive SET 	serial_no_barcode			= '" . $serial_no_barcode . "',
																					edit_lock 					= '1',
																					is_import_diagnostic_data	= '1',
																					is_diagnost					= '1',
																					overall_grade				= '" . $c_product_condition2 . "',
																					inventory_status			= '" . $c_expected_status2 . "',
																					is_diagnostic_bypass 		= 1,

																					update_by				= '" . $_SESSION['username'] . "',
																					update_by_user_id		= '" . $_SESSION['user_id'] . "',
																					update_timezone			= '" . $timezone . "',
																					update_date				= '" . $add_date . "',
																					update_ip				= '" . $add_ip . "',
																					update_from_module_id	= '" . $module_id . "'
										WHERE id = '" . $receive_id . "' ";
							$db->query($conn, $sql_c_up);
						

						update_po_detail_status($db, $conn, $product_id_barcode, $receive_status_dynamic);
						update_po_status($db, $conn, $id, $receive_status_dynamic);

						/////////////////////////// Create Stock  END /////////////////////////////
						$msg5['msg_success']	= "Return Product with barcode has been received successfully.";
						$serial_no_barcode		=  "";
						// $serial_no_barcode	= $sub_location_id_barcode = "";
					}
				}
			} else {
				$error5['msg'] = "The record is already exist";
			}
		}
	} else {
		$error5['msg'] = "Please check Error in form.";
	}
}
if (isset($_POST['is_Submit_tab5']) && $_POST['is_Submit_tab5'] == 'Y') {

	extract($_POST);
	if (!isset($receiving_location) || (isset($receiving_location)  && sizeof($receiving_location) == "0")) {
		$error5['receiving_location'] = "Required";
	} else {
		$receiving_location_error = 1;
		foreach ($receiving_location as $data_r1) {
			if ($data_r1 > 0) {
				$receiving_location_error = 0;
			}
		}
		if ($receiving_location_error == 1) {
			$error5['receiving_location'] = "Required";
		}
	}
	if (!isset($receiving_qties) || (isset($receiving_qties)  && sizeof($receiving_qties) == "0")) {
		$error5['receiving_qties'] = "Required";
	} else {
		$receiving_qty_error = 1;
		foreach ($receiving_qties as $data_r1) {
			if ($data_r1 > 0) {
				$receiving_qty_error = 0;
			}
		}
		if ($receiving_qty_error == 1) {
			$error5['receiving_qties'] = "Required";
		}
	}

	if (!isset($id) || (isset($id)  && ($id == "0" || $id == ""))) {
		$error5['msg'] = "Please add master record first";
	}

	if (empty($error5)) {
		if (po_permisions("Receive") == 0) {
			$error5['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			$sql_ee1 = " SELECT a.* FROM return_items_detail_receive a WHERE a.duplication_check_token = '" . $duplication_check_token . "' ";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				foreach ($receiving_qties as $key => $receiving_qty) {
					if ($receiving_qty > 0) {

						$total_receiving_qty 	= $receiving_qty; // Total receiving quantity available
						$product_uniqueid_main1 = "";
						$rn 					= 1;

						$recevied_product_category = $key;

						$package_id1 	= $package_material_qty1 = $package_material_qty_received1 = 0;
						$sql_pd3 		= "	SELECT  a.id, a.product_id, a.product_condition, b.product_uniqueid, a.order_price, a.return_qty, a.expected_status
												FROM return_items_detail a
												INNER JOIN products b ON b.id = a.product_id
												INNER JOIN returns a2 ON a2.id = a.return_id
												WHERE a.id				= '" . $recevied_product_category . "' 
												AND a.return_id			= '" . $id . "' ";
						$result_pd3		= $db->query($conn, $sql_pd3);
						$count_pd3		= $db->counter($result_pd3);
						if ($count_pd3 > 0) {
							$row_pd3 = $db->fetch($result_pd3);
							$stopLoops = false; // Flag to control breaking multiple loops
							foreach ($row_pd3 as $data3_rv) { 

								$ro_detail_id			= $data3_rv['id'];
								$order_price			= $data3_rv['order_price'];
								$return_qty				= $data3_rv['return_qty'];
								$product_uniqueid_main1	= $data3_rv['product_uniqueid'];
								$c_product_id2			= $data3_rv['product_id'];
								$c_product_condition2	= $data3_rv['product_condition'];
								$c_expected_status2		= $data3_rv['expected_status'];

								if ($rn == $count_pd3) {
									$allocated_qty = $total_receiving_qty;
								} else {
									$allocated_qty 			= min($total_receiving_qty, $return_qty);
									$total_receiving_qty   -= $allocated_qty; // Deduct allocated quantity
								}

								for ($m = 0; $m < $allocated_qty; $m++) {
									$receiving_location_add = $receiving_location[$key];
									$sql6 = "INSERT INTO return_items_detail_receive(return_id, base_product_id, ro_detail_id, receive_type, price, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone)
											 VALUES('" . $id . "', '" . $product_uniqueid_main1 . "',  '" . $ro_detail_id . "', 'ProductReceived', '" . $order_price . "', '" . $_SESSION['user_id'] . "', '" . $receiving_location_add . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
									$ok = $db->query($conn, $sql6);
									if ($ok) {
										$receive_id = mysqli_insert_id($conn);
										$k++;
										update_po_detail_status($db, $conn, $key, $receive_status_dynamic);
									}
								}
								$rn++;
							}
						}
					}
				}
				if ($k > 0) {
					update_po_status($db, $conn, $id, $receive_status_dynamic);

					$msg5['msg_success'] = "Receiving has been processed successfully.";
					unset($receiving_qties);
					unset($receiving_location);
					$logistic_id = "";
				}
			} else {
				$error5['msg'] = "The record is already exist";
			}
		}
	} else {
		$error5['msg'] = "Please check Error in form.";
	}
}
