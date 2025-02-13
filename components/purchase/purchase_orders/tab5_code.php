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
	$sql_c_up = "DELETE FROM  purchase_order_detail_receive  WHERE id = '" . $detail_id . "' ";
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
					$delete_id = " 	recevied_product_category = '" . $receviedProductId_array[1] . "' 
									AND sub_location_id = '" . $receviedProductId_array[2] . "'
									AND po_id = '" . $id . "' ";
				} else {
					$delete_id = " id= '" . $receviedProductId_array[1] . "' ";
				}

				$sql_ee12 		= " SELECT po_detail_id FROM purchase_order_detail_receive   WHERE " . $delete_id . " ";
				$result_rc3 	= $db->query($conn, $sql_ee12);
				$counter_rc3	= $db->counter($result_rc3);
				if ($counter_rc3 > 0) {
					$row_cl_rc3 = $db->fetch($result_rc3);
					foreach ($row_cl_rc3 as $data_rc3) {
						$po_detail_id1 = $data_rc3['po_detail_id'];
						if ($po_detail_id1 > 0) {
							$sql_c_up = "UPDATE purchase_order_detail SET is_fk_serial_generated = 0 WHERE id = '" . $po_detail_id1 . "'";
							$db->query($conn, $sql_c_up);
						}
					}
				}

				$sql_c_up = "DELETE FROM  purchase_order_detail_receive  
							 WHERE " . $delete_id . "  AND (is_diagnost = 0 OR is_diagnostic_bypass = 1)  ";
				//echo "<br><br>" . $sql_c_up;
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$sql_c_up = "DELETE FROM  product_stock WHERE receive_id = '" . $receviedProductId . "' ";
					$db->query($conn, $sql_c_up);
					$k++;
				}
			}
			if ($k > 0) {
				$sql_ee13 		= " SELECT id FROM purchase_order_detail_receive   WHERE po_id = '" . $id . "' ";
				$result_rc3 	= $db->query($conn, $sql_ee13);
				$counter_rc3	= $db->counter($result_rc3);
				if ($counter_rc3 == 0) {
					update_po_detail_status2($db, $conn, $id, $arrival_status_dynamic);
					update_po_status($db, $conn, $id, $arrival_status_dynamic);
					$disp_status_name = get_status_name($db, $conn, $arrival_status_dynamic);
				}
				if ($k == 1) {
					$msg5['msg_success'] = $k . " record has been deleted successfully.";
				} else {
					$msg5['msg_success'] = $k . " records have been deleted successfully.";
				}
			}
		}
	}
}
if (isset($_POST['is_Submit_tab5_6']) && $_POST['is_Submit_tab5_6'] == 'Y') {
	extract($_POST);
	if (!isset($receiving_location2) || (isset($receiving_location2)  && sizeof($receiving_location2) == "0")) {
		$error5['receiving_location2'] = "Required";
	} else {
		$receiving_location_error = 1;
		foreach ($receiving_location2 as $data_r1) {
			if ($data_r1 > 0) {
				$receiving_location_error = 0;
			}
		}
		if ($receiving_location_error == 1) {
			$error5['receiving_location2'] = "Required";
		}
	}
	if (!isset($receiving_qties2) || (isset($receiving_qties2)  && sizeof($receiving_qties2) == "0")) {
		$error5['receiving_qties2'] = "Required";
	} else {
		$receiving_qty_error = 1;
		foreach ($receiving_qties2 as $data_r1) {
			if ($data_r1 > 0) {
				$receiving_qty_error = 0;
			}
		}
		if ($receiving_qty_error == 1) {
			$error5['receiving_qties2'] = "Required";
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

			$sql_ee1 = " SELECT a.* FROM purchase_order_detail_receive_package_material a 
						 WHERE a.duplication_check_token = '" . $duplication_check_token . "' ";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				foreach ($receiving_qties2 as $key => $receiving_qty) {
					if ($receiving_qty > 0) {

						$sql_ee12 = " SELECT a.* FROM purchase_order_detail_receive_package_material a 
										WHERE a.po_detail_id = '" . $key . "' ";
						// echo $sql_ee1;
						$result_ee12 	= $db->query($conn, $sql_ee12);
						$counter_ee12	= $db->counter($result_ee12);
						if ($counter_ee12 > 0) {
							$sql_c_del = "DELETE FROM purchase_order_detail_receive_package_material WHERE po_detail_id = '" . $key . "' ";
							$db->query($conn, $sql_c_del);

							$sql_c_up = "	UPDATE purchase_order_packages_detail a
											INNER JOIN packages b ON b.id = a.package_id
											SET b.stock_in_hand = (b.stock_in_hand-" . $previous_receiving_qties2[$key] . "),
												b.avg_price = (b.stock_in_hand*b.avg_price - $previous_receiving_qties2[$key]*a.order_price)/(b.stock_in_hand-$previous_receiving_qties2[$key])
											WHERE a.id = '" . $key . "' ";
							$db->query($conn, $sql_c_up);
						}

						for ($m = 0; $m < $receiving_qty; $m++) {
							$receiving_location_add = $receiving_location2[$key];
							$sql6 = "INSERT INTO purchase_order_detail_receive_package_material(po_detail_id, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone)
									VALUES('" . $key . "', '" . $_SESSION['user_id'] . "', '" . $receiving_location_add . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
							$ok = $db->query($conn, $sql6);
							if ($ok) {
								$k++;

								$sql_c_up = "	UPDATE purchase_order_packages_detail a
												INNER JOIN packages b ON b.id = a.package_id

												SET b.stock_in_hand = (b.stock_in_hand+1),
													b.avg_price = ((b.stock_in_hand*b.avg_price)+(a.order_price))/(b.stock_in_hand+1)
												WHERE a.id = '" . $key . "' ";
								$db->query($conn, $sql_c_up);
							}
						}
					}
				}
				if ($k > 0) {
					$msg5['msg_success'] = "Package Materials have been received successfully.";
					unset($receiving_qties2);
					unset($receiving_location2);
				}
			} else {
				$error5['msg'] = "The record is already exist";
			}
		}
	} else {
		$error5['msg'] = "Please check Error in form.";
	}
}
/*
if (isset($_POST['is_Submit_tab5_5']) && $_POST['is_Submit_tab5_5'] == 'Y') {
	extract($_POST);

	if (!isset($serial_no_manual) || (isset($serial_no_manual)  && ($serial_no_manual == "0" || $serial_no_manual == ""))) {
		$error5['serial_no_manual'] = "Required";
	}
	if (!isset($sub_location_id_manual) || (isset($sub_location_id_manual)  && ($sub_location_id_manual == "0" || $sub_location_id_manual == ""))) {
		$error5['sub_location_id_manual'] = "Required";
	}
	if (!isset($logistic_id_manual) || (isset($logistic_id_manual)  && ($logistic_id_manual == "0" || $logistic_id_manual == ""))) {
		$error5['logistic_id_manual'] = "Required";
	}
	if (!isset($product_id_manual) || (isset($product_id_manual)  && ($product_id_manual == "0" || $product_id_manual == ""))) {
		$error5['product_id_manual'] = "Required";
	}
	foreach ($serial_no_manual as $data) {
		if ($data != "" && $data != null) {
			$serial_no_manual = array_filter($serial_no_manual, function ($data) {
				return $data !== "" && $data !== null;
			});
		}
	}
	$serial_no_manual = array_values($serial_no_manual);

	if (empty($error5)) {
		if (po_permisions("Receive") == 0) {
			$error5['msg'] = "You do not have add permissions.";
		} else {
			$k = $n = 0;
			$sql_ee1 = "SELECT a.* FROM purchase_order_detail_receive a 
						INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
						WHERE a.enabled = 1 
						AND (
								a.duplication_check_token = '" . $duplication_check_token . "' 
								AND a.logistic_id 	= '" . $logistic_id_manual . "' 
							)  ";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$m = 1;
				foreach ($serial_no_manual as $data) {
					if ($data != "" && $data != null) {
						$sql_ee1 = "SELECT a.* FROM purchase_order_detail_receive a 
									INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
									WHERE a.enabled = 1 
									AND ( 
											b.po_id = '" . $id . "'
											AND a.serial_no_barcode = '" . $data . "'
										)  ";
						// echo $sql_ee1;
						$result_ee1 	= $db->query($conn, $sql_ee1);
						$counter_ee1	= $db->counter($result_ee1);
						if ($counter_ee1 == 0) {

							$product_uniqueid_main1 = "";
							$sql_pd3		= "	SELECT a.product_id, a.product_condition, c.product_uniqueid, a2.is_tested_po, a2.is_wiped_po, a2.is_imaged_po, a.order_price,a.expected_status
												FROM purchase_order_detail a 
												INNER JOIN products c ON c.id = a.product_id
												INNER JOIN purchase_orders a2 ON a2.id = a.po_id
												WHERE 1 = 1
												AND a.id 	= '" . $product_id_manual . "'";
							$result_pd3		= $db->query($conn, $sql_pd3);
							$count_pd3		= $db->counter($result_pd3);
							if ($count_pd3 > 0) {
								$row_pd3 						= $db->fetch($result_pd3);
								$order_price					= $row_pd3[0]['order_price'];
								$product_uniqueid_main1			= $row_pd3[0]['product_uniqueid'];
								$c_product_id2 					= $row_pd3[0]['product_id'];
								$c_product_condition2 			= $row_pd3[0]['product_condition'];
								$c_expected_status2     		= $row_pd3[0]['expected_status'];
								$sql6 = "INSERT INTO purchase_order_detail_receive(logistic_id, po_detail_id, serial_no_barcode, price, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone)
										VALUES('" . $logistic_id_manual . "', '" . $product_id_manual . "', '" . $data . "',  '" . $order_price . "', '" . $_SESSION['user_id'] . "', '" . $sub_location_id_manual . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
								$ok = $db->query($conn, $sql6);
								if ($ok) {
									$receive_id = mysqli_insert_id($conn);
									if ($row_pd3[0]['is_tested_po'] == 'No' && $row_pd3[0]['is_wiped_po'] == 'No' && $row_pd3[0]['is_imaged_po'] == 'No') {
										$serial_no_barcode = $data;
										$sql6 = "INSERT INTO product_stock(subscriber_users_id, receive_id, product_id, serial_no, p_total_stock, stock_grade, p_inventory_status, sub_location, add_by_user_id, add_date, add_by, add_ip, add_timezone)
												VALUES('" . $subscriber_users_id . "', '" . $receive_id . "', '" . $c_product_id2 . "', '" . $serial_no_barcode . "', 1, '" . $c_product_condition2 . "', '" . $c_expected_status2 . "', '" . $sub_location_id_manual . "', '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
										$db->query($conn, $sql6);
										if (isset($serial_no_barcode) && $serial_no_barcode == '') {
											$serial_no_barcode = "GEN" . $receive_id;
										}
										$sql_c_up = "UPDATE purchase_order_detail_receive SET 	serial_no_barcode			= '" . $serial_no_barcode . "',
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
									}
									update_po_detail_status($db, $conn, $product_id_manual, $receive_status_dynamic);
									/////////////////////////// Create Stock  END /////////////////////////////
									$k++;
								}
							}
						} else {
							$n++;
							$error5["field_name_" . $m] = "Exist";
						}
					}
					$m++;
				}
				if ($k > 0) {

					$sql_c_up = "UPDATE  purchase_orders SET	order_status				= '" . $receive_status_dynamic . "',
																update_timezone				= '" . $timezone . "',
																update_date					= '" . $add_date . "',
																update_by					= '" . $_SESSION['username'] . "',
																update_ip					= '" . $add_ip . "'
									WHERE id = '" . $id . "' ";
					$db->query($conn, $sql_c_up);


					$msg5['msg_success'] = "Product with manual " . $k . " Serial No has been received successfully.";
					unset($serial_no_manual);
					// $serial_no_manual	= $sub_location_id_barcode = "";
				} else if ($n > 0) {
					$error5['msg'] = "These Serial Nos already exist.";
				}
			} else {
				$error5['msg'] = "The record is already exist";
			}
		}
	}
}
*/
/*
if (isset($_POST['is_Submit_tab5_4']) && $_POST['is_Submit_tab5_4'] == 'Y') {
	extract($_POST);
	if (empty($error5)) {
		if (po_permisions("Receive") == 0) {
			$error5['msg'] = "You do not have add permissions.";
		} else {
			if (isset($serialNumbers) && sizeof($serialNumbers) > 0) {
				$k = 0;
				foreach ($serialNumbers as $serialNumber) {

					$sql            = " SELECT a.*
										FROM purchase_order_detail_receive a 
										WHERE a.enabled 	= 1 
										AND a.po_detail_id 	= '" . $product_id_barcode_deduct . "'
										AND (a.serial_no_barcode IS NULL || a.serial_no_barcode = '')
										ORDER BY a.id LIMIT 1 ";
					$result_d1     = $db->query($conn, $sql);
					$count_d1      = $db->counter($result_d1);
					if ($count_d1 > 0) {
						$row_cl1 = $db->fetch($result_d1);
						$receive_id = $row_cl1[0]['id'];
						$sql_c_up = "UPDATE  purchase_order_detail_receive SET 		serial_no_barcode	= '" . $serialNumber . "',
																					update_timezone		= '" . $timezone . "',
																					update_date			= '" . $add_date . "',
																					update_by			= '" . $_SESSION['username'] . "',
																					update_ip			= '" . $add_ip . "'
								WHERE id = '" . $receive_id . "' ";
						$ok = $db->query($conn, $sql_c_up);
						if ($ok) {
							$k++;
							if (isset($error5['msg'])) unset($error5['msg']);
						} else {
							$error5['msg'] = "There is Error, Please check it again OR contact Support Team.";
						}
					}
				}
				if ($k > 0) {
					if (isset($msg5['msg_success'])) {
						$msg5['msg_success'] .= "<br>Deduct Serial Number has been updated successfully.";
					} else {
						$msg5['msg_success'] = "Deduct Serial Number has been updated successfully.";
					}
					$logistics_status = "";
				}
			} else {
				$error5['msg'] = "Please select atleast one record.";
			}
		}
	} else {
		$error5['msg'] = "Please check required fields in the form.";
	}
}
*/
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
			$sql_ee1 = "SELECT a.* FROM purchase_order_detail_receive a 
						INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
						WHERE a.enabled = 1 
						AND ( 
								b.po_id = '" . $id . "'
								AND a.serial_no_barcode = '" . $serial_no_barcode . "'
							) ";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {

				$product_uniqueid_main1 = "";
				$package_id1 = $package_material_qty1 = $package_material_qty_received1 = 0;

				$sql_pd3		= "	SELECT a.product_id, a.product_condition, c.product_uniqueid, a2.is_tested_po, a2.is_wiped_po, a2.is_imaged_po, a.order_price,a.expected_status
									FROM purchase_order_detail a 
									INNER JOIN products c ON c.id = a.product_id
									INNER JOIN purchase_orders a2 ON a2.id = a.po_id
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

					$sql6 = "INSERT INTO purchase_order_detail_receive(po_detail_id, serial_no_barcode, price, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone)
							 VALUES('" . $product_id_barcode . "', '" . $serial_no_barcode . "',  '" . $order_price . "', '" . $_SESSION['user_id'] . "', '" . $sub_location_id_barcode . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {

						$receive_id = mysqli_insert_id($conn);
						/////////////////////////// Create Stock  START /////////////////////////////

						if ($row_pd3[0]['is_tested_po'] == 'No' && $row_pd3[0]['is_wiped_po'] == 'No' && $row_pd3[0]['is_imaged_po'] == 'No') {
							$sql6 = "INSERT INTO product_stock(subscriber_users_id, receive_id, product_id, p_total_stock, stock_grade, p_inventory_status, sub_location,  add_by_user_id, add_date, add_by, add_ip, add_timezone)
									 VALUES('" . $subscriber_users_id . "', '" . $receive_id . "', '" . $c_product_id2 . "', 1, '" . $c_product_condition2 . "', '" . $c_expected_status2 . "', '" . $sub_location_id_barcode . "', '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
							$db->query($conn, $sql6);

							if (isset($serial_no_barcode) && $serial_no_barcode == '') {
								$serial_no_barcode = "GEN" . $receive_id;
							}

							$sql_c_up = "UPDATE purchase_order_detail_receive SET 	serial_no_barcode			= '" . $serial_no_barcode . "',
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
						}

						update_po_detail_status($db, $conn, $product_id_barcode, $receive_status_dynamic);
						update_po_status($db, $conn, $id, $receive_status_dynamic);
						$disp_status_name = get_status_name($db, $conn, $receive_status_dynamic);

						/////////////////////////// Create Stock  END /////////////////////////////
						$msg5['msg_success']	= "Product with barcode has been received successfully.";
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
			$sql_ee1 = " SELECT a.* FROM purchase_order_detail_receive a WHERE a.duplication_check_token = '" . $duplication_check_token . "' ";
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
						$sql_pd3 		= "	SELECT  a.id, a.product_id, a.product_condition, b.product_uniqueid, 
													a2.is_tested_po, a2.is_wiped_po, a2.is_imaged_po, a.order_price, a.order_qty, a.expected_status
											FROM purchase_order_detail a
											INNER JOIN products b ON b.id = a.product_id
											INNER JOIN purchase_orders a2 ON a2.id = a.po_id
 											WHERE b.product_category 	= '" . $recevied_product_category . "' 
 											AND a.po_id 				= '" . $id . "' ";
						$result_pd3		= $db->query($conn, $sql_pd3);
						$count_pd3		= $db->counter($result_pd3);
						if ($count_pd3 > 0) {
							$row_pd3 = $db->fetch($result_pd3);
							$stopLoops = false; // Flag to control breaking multiple loops
							foreach ($row_pd3 as $data3_rv) {

								$po_detail_id			= $data3_rv['id'];
								$order_price			= $data3_rv['order_price'];
								$order_qty				= $data3_rv['order_qty'];
								$product_uniqueid_main1	= $data3_rv['product_uniqueid'];
								$c_product_id2			= $data3_rv['product_id'];
								$c_product_condition2	= $data3_rv['product_condition'];
								$c_expected_status2		= $data3_rv['expected_status'];

								if ($rn == $count_pd3) {
									$allocated_qty = $total_receiving_qty;
								} else {
									$allocated_qty 			= min($total_receiving_qty, $order_qty);
									$total_receiving_qty   -= $allocated_qty; // Deduct allocated quantity
								}

								for ($m = 0; $m < $allocated_qty; $m++) {
									$receiving_location_add = $receiving_location[$key];
									$sql6 = "INSERT INTO purchase_order_detail_receive(po_id, recevied_product_category,  receive_type, price, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone)
											 VALUES('" . $id . "', '" . $recevied_product_category . "', 'CateogryReceived', '" . $order_price . "', '" . $_SESSION['user_id'] . "', '" . $receiving_location_add . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
									$ok = $db->query($conn, $sql6);
									if ($ok) {
										$receive_id = mysqli_insert_id($conn);

										if ($data3_rv['is_tested_po'] == 'No' && $data3_rv['is_wiped_po'] == 'No' && $data3_rv['is_imaged_po'] == 'No') {
											$sql6 = "INSERT INTO product_stock(subscriber_users_id, receive_id, product_id, p_total_stock, stock_grade, p_inventory_status, sub_location,  add_by_user_id, add_date, add_by, add_ip, add_timezone)
													VALUES('" . $subscriber_users_id . "', '" . $receive_id . "', '" . $c_product_id2 . "', 1, '" . $c_product_condition2 . "', '" . $c_expected_status2 . "', '" . $sub_location_id_manual . "', '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
											$db->query($conn, $sql6);
											$serial_no_fake = "GEN" . $receive_id;

											$sql_c_up = "UPDATE purchase_order_detail_receive SET 	serial_no_barcode			= '" . $serial_no_fake . "',
																									overall_grade				= '" . $c_product_condition2 . "',
																									inventory_status			= '" . $c_expected_status2 . "',
																									edit_lock 					= '1',
																									is_import_diagnostic_data	= '1',
																									is_diagnost					= '1',
																									is_diagnostic_bypass 		= 1,

																									update_by				= '" . $_SESSION['username'] . "',
																									update_by_user_id		= '" . $_SESSION['user_id'] . "',
																									update_timezone			= '" . $timezone . "',
																									update_date				= '" . $add_date . "',
																									update_ip				= '" . $add_ip . "',
																									update_from_module_id	= '" . $module_id . "'
														WHERE id = '" . $receive_id . "' ";
											$db->query($conn, $sql_c_up);
										}

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
					$disp_status_name = get_status_name($db, $conn, $receive_status_dynamic);

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
