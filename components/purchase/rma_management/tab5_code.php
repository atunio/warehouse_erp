<?php

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$sub_location_id_barcode	= 1737;
	$product_id_barcode 		= 5;
	$logistic_id_barcode 		= 1;
	$sub_location_id_manual		= 1737;
	$logistic_id 				= 1;
	$receiving_qties[5] 		= 15;
	$receiving_location[5] 		= 1737;
	$product_id_manual 			= 6;
	$logistic_id_manual			= 1;
	$serial_no_manual 			= array("DLXN2FKQFK10", "DMQD7TMFMF3M17", "DMQD7TMFMF3M18", "DMQD7TMFMF3M19", "DMQD7TMFMF3M20");
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
				$sql_c_up = "DELETE FROM  purchase_order_detail_receive  WHERE id = '" . $receviedProductId . "' ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$sql_c_up = "DELETE FROM  product_stock  WHERE receive_id = '" . $receviedProductId . "' ";
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
							$sql_pd3 		= "	SELECT a.*, b.product_uniqueid 
												FROM purchase_order_detail a 
												INNER JOIN products b ON b.id = a.product_id
												WHERE 1 	= 1
												AND a.id 	= '" . $product_id_manual . "' ";
							$result_pd3		= $db->query($conn, $sql_pd3);
							$count_pd3		= $db->counter($result_pd3);
							if ($count_pd3 > 0) {
								$row_pd3				= $db->fetch($result_pd3);
								$order_price			= $row_pd3[0]['order_price'];
								$product_uniqueid_main1	= $row_pd3[0]['product_uniqueid'];
							}
							$sql6 = "INSERT INTO purchase_order_detail_receive(base_product_id, logistic_id, po_detail_id, serial_no_barcode, price, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone)
									VALUES('" . $product_uniqueid_main1 . "', '" . $logistic_id_manual . "', '" . $product_id_manual . "', '" . $data . "',  '" . $order_price . "', '" . $_SESSION['user_id'] . "', '" . $sub_location_id_manual . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
							$ok = $db->query($conn, $sql6);
							if ($ok) {

								$receive_id = mysqli_insert_id($conn);

								update_po_detail_status($db, $conn, $product_id_manual, $receive_status_dynamic);

								/////////////////////////// Create Stock  START /////////////////////////////
								$sql_pd1 		= "	SELECT a.*, c.product_uniqueid
													FROM purchase_order_detail a 
													INNER JOIN products c ON c.id = a.product_id
													WHERE 1 	= 1
													AND a.id 	= '" . $product_id_manual . "' ";
								$result_pd1	= $db->query($conn, $sql_pd1);
								$count_pd1	= $db->counter($result_pd1);
								if ($count_pd1 > 0) {
									$row_pd1 = $db->fetch($result_pd1);
									$c_product_uniqueid 	= $row_pd1[0]['product_uniqueid'];
									$c_product_id 			= $row_pd1[0]['product_id'];
									$c_product_condition 	= $row_pd1[0]['product_condition'];

									$storage 	= $battery = $memory = $processor = "";

									$sql_pd2	= "	SELECT a.* 
													FROM vender_po_data a  
													WHERE 1 = 1 
													AND a.po_id	= '" . $id . "' 
													AND a.product_uniqueid	= '" . $c_product_uniqueid . "' ";
									$result_pd2	= $db->query($conn, $sql_pd2);
									$count_pd2	= $db->counter($result_pd2);
									if ($count_pd2 > 0) {
										$row_pd2			= $db->fetch($result_pd2);
										$storage			= $row_pd2[0]['storage'];
										$battery			= $row_pd2[0]['battery'];
										$memory				= $row_pd2[0]['memory'];
										$processor			= $row_pd2[0]['processor'];
										$defects_or_notes	= $row_pd2[0]['defects_or_notes'];
									}

									if ($c_product_condition == 'A Grade' || $c_product_condition == 'A' || $c_product_condition == 'AGrade') {
										$new_stock_product_uniqueid = $c_product_uniqueid . "-AXA";
									} else if ($c_product_condition == 'B Grade' || $c_product_condition == 'B' || $c_product_condition == 'BGrade') {
										$new_stock_product_uniqueid = $c_product_uniqueid . "-B";
									} else {
										$new_stock_product_uniqueid = $c_product_uniqueid . "-Z";
									}

									if ($row_pd1[0]['is_tested'] == 'Yes' && $row_pd1[0]['is_wiped'] == 'Yes' && $row_pd1[0]['is_imaged'] == 'Yes') {
										$sql6 = "INSERT INTO product_stock(subscriber_users_id, receive_id, product_id, serial_no, stock_product_uniqueid, p_total_stock, stock_grade, p_inventory_status, sub_location,	battery_percentage, ram_size, storage_size, processor_size, defects_or_notes, add_by_user_id, add_date,  add_by, add_ip, add_timezone)
												VALUES('" . $subscriber_users_id . "', '" . $receive_id . "', '" . $c_product_id . "', '" . $data . "', '" . $new_stock_product_uniqueid . "', 1, '" . $c_product_condition . "', 13, '" . $sub_location_id_manual . "', '" . $battery . "',  '" . $memory . "',  '" . $storage . "', '" . $processor . "', '" . $defects_or_notes . "', '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
										$db->query($conn, $sql6);
									}
								}
								/////////////////////////// Create Stock  END /////////////////////////////
								$k++;
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
if (isset($_POST['is_Submit_tab5_2']) && $_POST['is_Submit_tab5_2'] == 'Y') {
	extract($_POST);

	if (!isset($serial_no_barcode) || (isset($serial_no_barcode)  && ($serial_no_barcode == "0" || $serial_no_barcode == ""))) {
		$error5['serial_no_barcode'] = "Required";
	}
	if (!isset($sub_location_id_barcode) || (isset($sub_location_id_barcode)  && ($sub_location_id_barcode == "0" || $sub_location_id_barcode == ""))) {
		$error5['sub_location_id_barcode'] = "Required";
	}
	if (!isset($logistic_id_barcode) || (isset($logistic_id_barcode)  && ($logistic_id_barcode == "0" || $logistic_id_barcode == ""))) {
		$error5['logistic_id_barcode'] = "Required";
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
								(	a.duplication_check_token = '" . $duplication_check_token . "' 
									AND a.logistic_id 	= '" . $logistic_id_barcode . "' 
								)
								OR 
								(
									b.po_id = '" . $id . "'
									AND a.serial_no_barcode = '" . $serial_no_barcode . "'
								)
						)  ";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$product_uniqueid_main1 = "";
				$sql_pd3	= "	SELECT a.*, b.product_uniqueid 
								FROM purchase_order_detail a 
								INNER JOIN products b ON b.id = a.product_id
								WHERE 1 	= 1
								AND a.id 	= '" . $product_id_barcode . "' ";
				$result_pd3	= $db->query($conn, $sql_pd3);
				$count_pd3	= $db->counter($result_pd3);
				if ($count_pd3 > 0) {
					$row_pd3 				= $db->fetch($result_pd3);
					$order_price			= $row_pd3[0]['order_price'];
					$product_uniqueid_main1	= $row_pd3[0]['product_uniqueid'];
				}

				$sql6 = "INSERT INTO purchase_order_detail_receive(base_product_id, logistic_id, po_detail_id, serial_no_barcode, price, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone)
						VALUES('" . $product_uniqueid_main1 . "', '" . $logistic_id_barcode . "', '" . $product_id_barcode . "', '" . $serial_no_barcode . "',  '" . $order_price . "', '" . $_SESSION['user_id'] . "', '" . $sub_location_id_barcode . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
				$ok = $db->query($conn, $sql6);
				if ($ok) {

					$receive_id = mysqli_insert_id($conn);

					update_po_detail_status($db, $conn, $product_id_barcode, $receive_status_dynamic);
					update_po_status($db, $conn, $id, $receive_status_dynamic);

					/////////////////////////// Create Stock  START /////////////////////////////
					$sql_pd1 		= "	SELECT a.*, c.product_uniqueid
										FROM purchase_order_detail a 
										INNER JOIN products c ON c.id = a.product_id
										 WHERE 1 	= 1
										AND a.id 	= '" . $product_id_barcode . "' ";
					$result_pd1	= $db->query($conn, $sql_pd1);
					$count_pd1	= $db->counter($result_pd1);
					if ($count_pd1 > 0) {
						$row_pd1 = $db->fetch($result_pd1);
						$c_product_uniqueid 	= $row_pd1[0]['product_uniqueid'];
						$c_product_id 			= $row_pd1[0]['product_id'];
						$c_product_condition 	= $row_pd1[0]['product_condition'];
						$c_order_price 			= $row_pd1[0]['order_price'];

						$storage 	= $battery = $memory = $processor = $defects_or_notes = "";

						$sql_pd2	= "	SELECT a.* 
										FROM vender_po_data a  
										WHERE 1 = 1 
										AND a.po_id	= '" . $id . "' 
										AND a.product_uniqueid	= '" . $c_product_uniqueid . "' ";
						$result_pd2	= $db->query($conn, $sql_pd2);
						$count_pd2	= $db->counter($result_pd2);
						if ($count_pd2 > 0) {
							$row_pd2			= $db->fetch($result_pd2);
							$storage			= $row_pd2[0]['storage'];
							$battery			= $row_pd2[0]['battery'];
							$memory				= $row_pd2[0]['memory'];
							$processor			= $row_pd2[0]['processor'];
							$defects_or_notes	= $row_pd2[0]['defects_or_notes'];
						}

						if ($c_product_condition == 'A Grade' || $c_product_condition == 'A' || $c_product_condition == 'AGrade') {
							$new_stock_product_uniqueid = $c_product_uniqueid . "-A";
						} else if ($c_product_condition == 'B Grade' || $c_product_condition == 'B' || $c_product_condition == 'BGrade') {
							$new_stock_product_uniqueid = $c_product_uniqueid . "-B";
						} else if ($c_product_condition == 'C Grade' || $c_product_condition == 'C' || $c_product_condition == 'CGrade') {
							$new_stock_product_uniqueid = $c_product_uniqueid . "-C";
						} else {
							$new_stock_product_uniqueid = $c_product_uniqueid . "-D";
						}

						// $po_receiving_labor	= po_receiving_labor($db, $conn, $id);
						// $c_order_price 		= round(($c_order_price + $po_receiving_labor), 2);

						if ($row_pd1[0]['is_tested'] == 'Yes' && $row_pd1[0]['is_wiped'] == 'Yes' && $row_pd1[0]['is_imaged'] == 'Yes') {
							$sql6 = "INSERT INTO product_stock(subscriber_users_id, receive_id, product_id, serial_no, stock_product_uniqueid, p_total_stock, 
																stock_grade, p_inventory_status, sub_location,	battery_percentage, ram_size, storage_size, 
																processor_size, defects_or_notes, price, add_by_user_id, add_date,  add_by, add_ip, add_timezone)
									VALUES('" . $subscriber_users_id . "', '" . $receive_id . "', '" . $c_product_id . "', '" . $serial_no_barcode . "', '" . $new_stock_product_uniqueid . "', 1, 
											'" . $c_product_condition . "', 13, '" . $sub_location_id_manual . "', '" . $battery . "',  '" . $memory . "',  '" . $storage . "', 
											'" . $processor . "', '" . $defects_or_notes . "',  '" . $c_order_price . "', 
											'" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
							$db->query($conn, $sql6);
						}
					}
					/////////////////////////// Create Stock  END /////////////////////////////
					$msg5['msg_success']	= "Product with barcode has been received successfully.";
					$serial_no_barcode		=  "";
					// $serial_no_barcode	= $sub_location_id_barcode = "";
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
	if (!isset($logistic_id) || (isset($logistic_id)  && ($logistic_id == "0" || $logistic_id == ""))) {
		$error5['logistic_id'] = "Required";
	}
	if (!isset($id) || (isset($id)  && ($id == "0" || $id == ""))) {
		$error5['msg'] = "Please add master record first";
	}

	if (empty($error5)) {
		if (po_permisions("Receive") == 0) {
			$error5['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;

			$sql_ee1 = " SELECT a.* FROM purchase_order_detail_receive a WHERE a.duplication_check_token = '" . $duplication_check_token . "' AND a.logistic_id = '" . $logistic_id . "'";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				foreach ($receiving_qties as $key => $receiving_qty) {
					if ($receiving_qty > 0) {
						for ($m = 0; $m < $receiving_qty; $m++) {
							$receiving_location_add = $receiving_location[$key];

							$product_uniqueid_main1 = "";
							$sql_pd3 		= "	SELECT a.*, b.product_uniqueid 
												FROM purchase_order_detail a 
												INNER JOIN products b ON b.id = a.product_id
												WHERE 1 	= 1
												AND a.id 	= '" . $key . "' ";
							$result_pd3	= $db->query($conn, $sql_pd3);
							$count_pd3	= $db->counter($result_pd3);
							if ($count_pd3 > 0) {
								$row_pd3 				= $db->fetch($result_pd3);
								$order_price			= $row_pd3[0]['order_price'];
								$product_uniqueid_main1	= $row_pd3[0]['product_uniqueid'];
							}
							$sql6 = "INSERT INTO purchase_order_detail_receive(base_product_id, logistic_id, po_detail_id, price, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone)
									VALUES('" . $product_uniqueid_main1 . "', '" . $logistic_id . "', '" . $key . "',   '" . $order_price . "', '" . $_SESSION['user_id'] . "', '" . $receiving_location_add . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
							$ok = $db->query($conn, $sql6);
							if ($ok) {
								$k++;

								update_po_detail_status($db, $conn, $key, $receive_status_dynamic);
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
