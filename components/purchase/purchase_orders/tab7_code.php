<?php

if ($_SERVER['HTTP_HOST'] == 'localhost' && $test_on_local == 1) {
}

if (isset($cmd7) && $cmd7 == 'delete' && isset($detail_id)) {
	// if (po_permisions("Diagnostic") == 0) {
	// 	$error7['msg'] = "You do not have add permissions.";
	// } else {
	// 	$sql_c_up = "DELETE FROM  purchase_order_detail_receive  WHERE id = '" . $detail_id . "' ";
	// 	$ok = $db->query($conn, $sql_c_up);
	// 	if ($ok) {
	// 		$msg7['msg_success'] = "Record has been deleted successfully.";
	// 	}
	// }
}
if (isset($_POST['is_Submit_tab7_3']) && $_POST['is_Submit_tab7_3'] == 'Y') {
	extract($_POST);
	if (!isset($ids_for_rma) || (isset($ids_for_rma) && sizeof($ids_for_rma) == 0)) {
		$error7['msg'] = "Select atleast one record";
	}
	if (empty($error7)) {
		if (po_permisions("RMA Process") == 0) {
			$error7['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			foreach ($ids_for_rma as $rma_receive_id) {
				$sql_pd1	= "	SELECT a.*, b.po_detail_id
								FROM purchase_order_detail_receive_rma a
								INNER JOIN purchase_order_detail_receive b ON b.id = a.receive_id
  								WHERE a.receive_id = '" . $rma_receive_id . "'
								AND a.edit_lock = 0 ";
				// echo "<br><br><br><br><br><br><br>" . $sql_pd1;
				$result_pd1	= $db->query($conn, $sql_pd1);
				$count_pd1	= $db->counter($result_pd1);
				if ($count_pd1 > 0) {
					$row_pd1 				= $db->fetch($result_pd1);
					$receive_rma_id			= $row_pd1[0]['id'];
					$status_id_upd 			= $row_pd1[0]['status_id'];
					$reduced_price_upd		= $row_pd1[0]['reduced_price'];
					$new_value_upd			= $row_pd1[0]['new_value'];
					$sub_location_id_upd	= $row_pd1[0]['sub_location_id'];
					$credit_memo_upd		= $row_pd1[0]['credit_memo'];
					$inv_po_detail_id		= $row_pd1[0]['po_detail_id'];

					$sql_c_up 	= "	UPDATE product_stock SET p_inventory_status = '" . $status_id_upd . "', ";
					if ($status_id_upd == '19' || $status_id_upd == '18') {
						$sql_c_up 	.= "price 			= '" . $new_value_upd . "', 
										sub_location 	= '" . $sub_location_id_upd . "' ";
					} else {
						$sql_c_up 	.= "sub_location = '0' ";
					}
					$sql_c_up 	.= " 
									WHERE receive_id = '" . $rma_receive_id . "' ";

					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$sql_c_up = "UPDATE purchase_order_detail_receive_rma SET edit_lock = '1' WHERE receive_id = '" . $rma_receive_id . "' ";
						$db->query($conn, $sql_c_up);

						$sql_c_up = "UPDATE purchase_order_detail_receive SET is_rma_processed = '1' WHERE id = '" . $rma_receive_id . "' ";
						$db->query($conn, $sql_c_up);

						$sql_itm1	= "	SELECT c.id 
										FROM purchase_order_detail a
										INNER JOIN purchase_order_detail_receive b ON b.po_detail_id = a.id
										INNER JOIN product_stock c ON c.receive_id = b.id
										WHERE a.po_id = '" . $id . "'
										AND c.p_inventory_status = 5";
						// echo "<br><br><br><br><br><br><br>" . $sql_itm1;
						$result_itm1	= $db->query($conn, $sql_itm1);
						$count_itm1		= $db->counter($result_itm1);
						if ($reduced_price_upd > 0 && $count_itm1 > 0) {
							$row_itm1	= $db->fetch($result_itm1);
							foreach ($row_itm1 as $data_itm1) {
								$stk_id = $data_itm1['id'];
								$reduced_amount = round(($reduced_price_upd / $count_itm1), 2);

								$sql_c_up = "UPDATE product_stock SET price = round((price+" . $reduced_amount . "), 2), distributed_amount = round((distributed_amount+" . $reduced_amount . "), 2) WHERE id = '" . $stk_id . "' ";
								$db->query($conn, $sql_c_up);

								$sql6 = "INSERT INTO purchase_order_detail_receive_rma_reduced_prices(subscriber_users_id, receive_rma_id, stock_id, reduced_amount, add_by_user_id, add_date,  add_by, add_ip, add_timezone, added_from_module_id)
										VALUES('" . $subscriber_users_id . "', '" . $receive_rma_id . "', '" . $stk_id . "', '" . $reduced_amount . "', '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
								$db->query($conn, $sql6);
							}
						}

						if ($credit_memo_upd > 0) {
							$sql6 = "INSERT INTO vender_credit_memo(subscriber_users_id, receive_rma_id, credit_memo, add_by_user_id, add_date,  add_by, add_ip, add_timezone, added_from_module_id)
	 								 VALUES('" . $subscriber_users_id . "', '" . $receive_rma_id . "', '" . $credit_memo_upd . "', '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
							$db->query($conn, $sql6);

							$sql_c_up = "UPDATE venders SET credit_balance = round((credit_balance+" . $credit_memo_upd . "), 2) WHERE id = '" . $vender_id . "' ";
							$db->query($conn, $sql_c_up);
						}

						$k++;
					}
				}
			}
			if ($k > 0) {
				if ($k == 1) {
					$msg7['msg_success'] = $k . " record has been mvoe to RMA successfully.";
				} else {
					$msg7['msg_success'] = $k . " records have been mvoe to RMA successfully.";
				}
			}
		}
	} else {
		if (!isset($error7['msg'])) {
			$error7['msg'] = "Please select all required fields";
		}
	}
}

if (isset($_POST['is_Submit_tab7_2']) && $_POST['is_Submit_tab7_2'] == 'Y') {
	extract($_POST);

	$field_name = "status_id_rma";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error7[$field_name] = "Required";
	} else {
		$field_name = "status_id_rma";
		if (${$field_name} == '19' || ${$field_name} == '18' || ${$field_name} == '22' || ${$field_name} == '23' || ${$field_name} == '24') {
			if (${$field_name} == '18') {
				$field_name2 = "partial_refund_status";
				if (!isset(${$field_name2}) || (isset(${$field_name2})  && (${$field_name2} == "0" || ${$field_name2} == ""))) {
					$error7[$field_name2] = "Required";
				}
			}
			if (${$field_name} == '19') {
				$field_name = "repair_type";
				if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
					$error7[$field_name] = "Required";
				}
			} else {
				$repair_type = "0";
			}
			$field_name = "new_value";
			if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
				$error7[$field_name] = "Required";
			}
			$field_name = "sub_location_id_barcode_rma";
			if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
				$error7[$field_name] = "Required";
			}
			$tracking_no_rma = "";
		} else {
			$field_name = "tracking_no_rma";
			if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
				$error7[$field_name] = "Required";
			}
			$new_value 		= "";
			$repair_type 	= $sub_location_id_barcode_rma = "0";
		}
	}
 
	$field_name = "receive_id_barcode_rma";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error7[$field_name] = "Required";
	}
	if (empty($error7)) {
		if (po_permisions("RMA") == 0) {
			$error7['msg'] = "You do not have add permissions.";
		} else { 
			$credit_memo = 0;
			$actual_price = $reduced_price = 0;
			$sql_pd1		= "	SELECT a.*, a.price
								FROM purchase_order_detail_receive a
								INNER JOIN product_stock b ON a.id = b.receive_id
								WHERE a.id = '" . $receive_id_barcode_rma . "' ";
			$result_pd1		= $db->query($conn, $sql_pd1);
			$count_pd1		= $db->counter($result_pd1);
			if ($count_pd1 > 0) {
				$row_st1 = $db->fetch($result_pd1);
				$actual_price = $row_st1[0]['price'];
			}
			if ($status_id_rma == '19' || $status_id_rma == '18' || $status_id_rma == '22' || $status_id_rma == '23' || $status_id_rma == '24') {
				// Repair = 19   // Partial Refund = 18 
				if ($actual_price > $new_value) {
					$reduced_price = $actual_price - $new_value;
					if ($status_id_rma == '18') {  // Partial Refund
						$credit_memo = $reduced_price;
						$reduced_price = 0;
					}
				}
			} else {
				if ($status_id_rma == '8' || $status_id_rma == '16' || $status_id_rma == '17') {  // Return for Repair   // Return for Full Refund
					$credit_memo = $actual_price;
				}
				$reduced_price = 0;
			}
			if(!isset($repaire_status_id) || (isset($repaire_status_id) && $repaire_status_id != '18')){
				$repaire_status_id = "0";
			}

			$sql_pd1	= "	SELECT a.*
							FROM purchase_order_detail_receive_rma a
							WHERE a.receive_id = '" . $receive_id_barcode_rma . "' ";
			$result_pd1	= $db->query($conn, $sql_pd1);
			$count_pd1	= $db->counter($result_pd1);
			if ($count_pd1 == 0) {
				$sql6 = "INSERT INTO purchase_order_detail_receive_rma(receive_id, status_id, repaire_status_id, new_value, reduced_price, 
																		repair_type, sub_location_id, tracking_no, credit_memo, 
																		add_by_user_id, add_date,  add_by, add_ip, add_timezone)
						 VALUES('" . $receive_id_barcode_rma . "', '" . $status_id_rma . "', '" . $repaire_status_id . "', '" . $new_value . "', '" . $reduced_price . "', 
						 '" . $repair_type . "', '" . $sub_location_id_barcode_rma . "', '" . $tracking_no_rma . "', '" . $credit_memo . "', 
						 '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
				// echo "<br><br>" . $sql6;
				$ok = $db->query($conn, $sql6);
				if ($ok) {
					$sql_c_up 	= "UPDATE purchase_order_detail_receive SET is_rma_added = '1'  WHERE id = '" . $receive_id_barcode_rma . "' ";
					$db->query($conn, $sql_c_up);
					$new_value = $repair_type = "";
				}
			} else {

				$row_pd1 	= $db->fetch($result_pd1);
				$rma_id 	= $row_pd1[0]['id'];

				$sql_c_up 	= "	UPDATE purchase_order_detail_receive_rma SET 	status_id 				= '" . $status_id_rma . "',
																				new_value 				= '" . $new_value . "',
																				reduced_price			= '" . $reduced_price . "',
																				repair_type 			= '" . $repair_type . "',
																				sub_location_id 		= '" . $sub_location_id_barcode_rma . "',
																				tracking_no 			= '" . $tracking_no_rma . "',
																				credit_memo 			= '" . $credit_memo . "',
																				repaire_status_id		= '" . $repaire_status_id . "',
 
																				update_timezone			= '" . $timezone . "',
																				update_date				= '" . $add_date . "',
																				update_by				= '" . $_SESSION['username'] . "',
																				update_by_user_id		= '" . $_SESSION['user_id'] . "',
																				update_from_module_id	= '" . $module_id . "',
																				update_ip				= '" . $add_ip . "'

																				
								WHERE edit_lock = 0 
								AND id = '" . $rma_id . "'  ";
				$db->query($conn, $sql_c_up);

				$sql_c_up 	= "UPDATE purchase_order_detail_receive SET is_rma_added = '1'  WHERE id = '" . $receive_id_barcode_rma . "' ";
				$db->query($conn, $sql_c_up);
			}
			$msg7['msg_success'] = "RMA Detail has been updated successfully.";
		}
	} else {
		$error7['msg'] = "Please check Error in form.";
	}
}
