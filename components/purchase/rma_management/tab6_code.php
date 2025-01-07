<?php

if ($_SERVER['HTTP_HOST'] == 'localhost' && $test_on_local == 1) {
	$product_id_barcode_diagnostic 		= "5";
	$product_id_manual_diagnostic 		= "5";
	$sub_location_id_barcode_diagnostic	= 1737;
	$phone_check_username				= 'Ctinno7';
	$serial_no_manual_diagnostic		= array("DMQD7TMFMF3M1", "DMQD7TMFMF3M2", "DMQD7TMFMF3M3", "DMQD7TMFMF3M4", "DMQD7TMFMF3M5", "DMQD7TMFMF3M6", "DMQD7TMFMF3M7", "DMQD7TMFMF3M8", "R72F1QJ62X", "F9FRFN0HGHKH", "DLXN2FKQFK10");
}

if (isset($cmd6) && $cmd6 == 'delete' && isset($detail_id)) {
	if (po_permisions("Diagnostic") == 0) {
		$error6['msg'] = "You do not have add permissions.";
	} else {
		$sql_c_up = "DELETE FROM  purchase_order_detail_receive  WHERE id = '" . $detail_id . "' ";
		$ok = $db->query($conn, $sql_c_up);
		if ($ok) {
			$msg6['msg_success'] = "Record has been deleted successfully.";
		}
	}
}
if (isset($_POST['is_Submit_tab6_7']) && $_POST['is_Submit_tab6_7'] == 'Y') {
	extract($_POST);
	if (!isset($ids_for_stock) || (isset($ids_for_stock) && sizeof($ids_for_stock) == 0)) {
		$error6['msg'] = "Select atleast one record to delete";
	}
	if (empty($error6)) {
		if (po_permisions("Move as Inventory") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			foreach ($ids_for_stock as $id_for_stock) {
				/////////////////////////// Create Stock  START /////////////////////////////
				$sql_pd1	= "	SELECT a.*, b.product_id, c.product_uniqueid, b.po_id, d.logistics_cost
								FROM purchase_order_detail_receive a
								INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
								INNER JOIN products c ON c.id = b.product_id
								INNER JOIN purchase_orders d ON d.id = b.po_id
								WHERE a.id = '" . $id_for_stock . "' ";
				$result_pd1	= $db->query($conn, $sql_pd1);
				$count_pd1	= $db->counter($result_pd1);
				if ($count_pd1 > 0) {
					$row_pd1 				= $db->fetch($result_pd1);
					$inv_receive_id			= $row_pd1[0]['id'];
					$inv_po_id				= $row_pd1[0]['po_id'];
					$inv_po_detail_id		= $row_pd1[0]['po_detail_id'];
					$inv_product_id			= $row_pd1[0]['product_id'];
					$old_base_product_id	= $row_pd1[0]['product_uniqueid'];
					$inv_base_product_id	= $row_pd1[0]['base_product_id'];
					$inv_sub_product_id		= $row_pd1[0]['sub_product_id'];
					$inv_serial_no			= $row_pd1[0]['serial_no_barcode'];
					$inv_sub_location		= $row_pd1[0]['sub_location_id_after_diagnostic'];
					$inv_battery 			= $row_pd1[0]['battery'];
					$inv_body_grade 		= $row_pd1[0]['body_grade'];
					$inv_lcd_grade 			= $row_pd1[0]['lcd_grade'];
					$inv_digitizer_grade	= $row_pd1[0]['digitizer_grade'];
					$inv_overall_grade 		= $row_pd1[0]['overall_grade'];
					$inv_ram 				= $row_pd1[0]['ram'];
					$inv_storage 			= $row_pd1[0]['storage'];
					$inv_processor 			= $row_pd1[0]['processor'];
					$inv_price 				= $row_pd1[0]['price'];
					$inv_defects_or_notes	= $row_pd1[0]['defects_or_notes'];
					$inv_inventory_status	= $row_pd1[0]['inventory_status'];
					$inv_model_name			= $row_pd1[0]['model_name'];
					$inv_model_no			= $row_pd1[0]['model_no'];
					$inv_make_name			= $row_pd1[0]['make_name'];
					$inv_color_name			= $row_pd1[0]['color_name'];
					$logistics_cost			= $row_pd1[0]['logistics_cost'];


					$sql_pd2	= "	SELECT a.id
									FROM product_stock a
									WHERE a.receive_id = '" . $inv_receive_id . "' ";
					$result_pd2	= $db->query($conn, $sql_pd2);
					$count_pd2	= $db->counter($result_pd2);
					if ($count_pd2 == 0) {

						// $po_logistic_cost = 0;
						$po_logistic_cost 		= po_logistic_cost($db, $conn, $inv_po_id, $logistics_cost);
						$po_receiving_labor 	= po_receiving_labor($db, $conn, $inv_po_id);
						$po_diagnostic_labor 	= po_diagnostic_labor($db, $conn, $inv_po_id);
						// echo "<br><br><br><br><br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa po_receiving_labor:" . $po_receiving_labor;
						// echo "<br><br><br><br><br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa po_diagnostic_labor:" . $po_diagnostic_labor;
						$inv_price 				= round(($inv_price + $po_receiving_labor + $po_diagnostic_labor + $po_logistic_cost), 2);

						$sql6 = "INSERT INTO product_stock(subscriber_users_id, receive_id, product_id, serial_no, 
															stock_product_uniqueid, p_total_stock, stock_grade, p_inventory_status, sub_location,
															battery_percentage, ram_size, storage_size, processor_size, defects_or_notes, 
															model_name, model_no, make_name, color_name, 
															body_grade, lcd_grade, digitizer_grade,  price,
															add_by_user_id, add_date,  add_by, add_ip, add_timezone)
								VALUES('" . $subscriber_users_id . "', '" . $inv_receive_id . "', '" . $inv_product_id . "', '" . $inv_serial_no . "',
										'" . $inv_sub_product_id . "', 1, '" . $inv_overall_grade . "', '" . $inv_inventory_status . "', '" . $inv_sub_location . "', 
										'" . $inv_battery . "',  '" . $inv_ram . "',  '" . $inv_storage . "', '" . $inv_processor . "', '" . $inv_defects_or_notes . "', 
										'" . $inv_model_name . "', '" . $inv_model_no . "', '" . $inv_make_name . "',  '" . $inv_color_name . "',
										'" . $inv_body_grade . "', '" . $inv_lcd_grade . "', '" . $inv_digitizer_grade . "', '" . $inv_price . "', 
										'" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
						// echo "<br><br>" . $sql6;
						$ok = $db->query($conn, $sql6);
						if ($ok) {
							if ($old_base_product_id != $inv_base_product_id) {
								$sql_c_up = "UPDATE products SET product_uniqueid = '" . $inv_base_product_id . "' WHERE id = '" . $inv_product_id . "' ";
								$db->query($conn, $sql_c_up);
							}
							$sql_c_up = "UPDATE purchase_order_detail_receive SET edit_lock = '1' WHERE id = '" . $inv_receive_id . "' ";
							$db->query($conn, $sql_c_up);

							update_po_detail_status($db, $conn, $inv_po_detail_id, $inventory_status_dynamic);
							update_po_status($db, $conn, $id, $inventory_status_dynamic);

							$k++;
						}
					}
				}
				/////////////////////////// Create Stock  END /////////////////////////////
			}
			if ($k > 0) {
				if ($k == 1) {
					$msg6['msg_success'] = $k . " record has been deleted successfully.";
				} else {
					$msg6['msg_success'] = $k . " records have been deleted successfully.";
				}
			}
		}
	}
}
/*
if (isset($_POST['is_Submit_tab6_6']) && $_POST['is_Submit_tab6_6'] == 'Y') {
	extract($_POST);
	if (isset($phone_check_username)  && ($phone_check_username == "")) {
		$error6['phone_check_username'] = "Required";
	}
	if (isset($diagnostic_date)  && ($diagnostic_date == "")) {
		$error6['diagnostic_date'] = "Required";
	}
	// if (isset($diagnostic_invoice_no)  && ($diagnostic_invoice_no == "")) {
	// 	$error6['diagnostic_invoice_no'] = "Required";
	// }
	if (empty($error6)) {
		if (po_permisions("Diagnostic") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {
			$diagnostic_date1 	= convert_date_mysql_slash($diagnostic_date);
			$sql_pd02 			= "	SELECT a.po_no, b.*
									FROM  purchase_order_detail b  
									INNER JOIN purchase_orders a ON a.id = b.po_id
									WHERE a.enabled = 1 
									AND a.id = '" . $id . "'  ";
			// AND b.id 		= '" . $diagnostic_invoice_no . "' 
			$result_pd02		= $db->query($conn, $sql_pd02);
			$row_pd02			= $db->fetch($result_pd02);
			$invoiceNo 			= $row_pd02[0]['po_no'];

			if ($_SERVER['HTTP_HOST'] == 'localhost' && $test_on_local == 1) {
				$invoiceNo 			= "19200";  // Optional
				$diagnostic_date1	= "2024-10-04";  // Filter by Date (optional)
			}

			$limit		= 500;  // Optional, max 500 records
			$offset		= 1;  // Optional

			// $station = "Kai3";  // Optional
			// $invoiceNo = "164312";  // Optional 
			// $startDate = "2024-10-08";  // Optional
			// $endDate = "2024-10-04";  // Optional
			// $deviceDisconnect = "2024-10-04 14:00:00";  // Optional

			$data = [
				'Apikey' 		=> $phoneCheck_apiKey,
				'Username' 		=> $phone_check_username,
				'Invoiceno' 	=> $invoiceNo,
				'Date' 			=> $diagnostic_date1,
				'limit' 		=> $limit,
				'offset' 		=> $offset
			];
			$imei_already = $phone_check_sku_codes = "";
			$k = $n = 0;
			$all_devices_info = v2_devices_call_phonecheck($data);
			if (isset($all_devices_info['imei']) && sizeof($all_devices_info['imei']) > 0) {
				$m = 1;
				foreach ($all_devices_info['imei'] as $data) {
					if ($data != "" && $data != null) {
						$sql_pd01 		= "	SELECT a.* 
											FROM purchase_order_detail_receive a 
											WHERE a.enabled = 1  
											AND a.serial_no_barcode	= '" . $data . "' ";
						$result_pd01	= $db->query($conn, $sql_pd01);
						$count_pd01		= $db->counter($result_pd01);
						if ($count_pd01 == 0) {
							$sku_code_array 		= array();

							$sql_pd01_4 		= "	SELECT  a.*
													FROM phone_check_api_data a 
													WHERE a.enabled = 1 
													AND a.imei_no = '" . $data . "'
													ORDER BY a.id DESC LIMIT 1";
							$result_pd01_4	= $db->query($conn, $sql_pd01_4);
							$count_pd01_4	= $db->counter($result_pd01_4);
							if ($count_pd01_4 > 0) {
								$row_pd01_4					= $db->fetch($result_pd01_4);
								$jsonData2					= $row_pd01_4[0]['phone_check_api_data'];
								$model_name					= $row_pd01_4[0]['model_name'];
								$model_no					= $row_pd01_4[0]['model_no'];
								$make_name					= $row_pd01_4[0]['make_name'];
								$carrier_name				= $row_pd01_4[0]['carrier_name'];
								$color_name					= $row_pd01_4[0]['color_name'];
								$battery					= $row_pd01_4[0]['battery'];
								$body_grade					= $row_pd01_4[0]['body_grade'];
								$lcd_grade					= $row_pd01_4[0]['lcd_grade'];
								$digitizer_grade			= $row_pd01_4[0]['digitizer_grade'];
								$ram						= $row_pd01_4[0]['ram'];
								$memory						= $row_pd01_4[0]['memory'];
								$defectsCode				= $row_pd01_4[0]['defectsCode'];
							} else {
								$device_detail_array1 	= getinfo_phonecheck_imie($data);
								$jsonData2				= json_encode($device_detail_array1);
								$model_name = $model_no = $make_name = $carrier_name = $color_name = $battery = $body_grade = $lcd_grade = $digitizer_grade = $ram = $memory = $defectsCode = $lcd_grade = $lcd_grade = $lcd_grade = $overall_grade = "";
								foreach ($device_detail_array1 as $key1 => $data1) {
									foreach ($data1 as $key2 => $data2) {
										if ($key2 == 'BatteryHealthPercentage') {
											$battery = $data2;
										}
										if ($key2 == 'Model') {
											$model_name = str_replace('"', '', $data2);
										}
										if ($key2 == 'Model#') {
											$model_no = $data2;
										}
										if ($key2 == 'Make') {
											$make_name = $data2;
										}
										if ($key2 == 'Carrier') {
											$carrier_name = $data2;
										}
										if ($key2 == 'Color') {
											$color_name = $data2;
										}
										if ($key2 == 'Cosmetics') {
											$body_grade = $lcd_grade = $digitizer_grade = "";
											if ($data2 != "") {
												$pass_array         = explode(",", $data2);
												if (sizeof($pass_array) == 3) {
													$body_grade         = $pass_array[0];
													$lcd_grade          = $pass_array[1];
													$digitizer_grade    = $pass_array[2];
												}
											}
										}
										if ($key2 == 'Ram') {
											$ram = $data2;
										}
										if ($key2 == 'Memory') {
											$memory = $data2;
										}
										if ($key2 == 'DefectsCode') {
											$defectsCode = $data2;
										}
									}
								}

								$sql = "INSERT INTO phone_check_api_data(imei_no, model_name, model_no, make_name, 
																		carrier_name, color_name, battery, body_grade, lcd_grade,digitizer_grade, 
																		`ram`, `memory`, defectsCode, phone_check_api_data, add_date, add_by, add_by_user_id, add_ip)
										VALUES	('" . $data . "', '" . $model_name . "', '" . $model_no . "','" . $make_name . "', 
												'" . $carrier_name . "', '" . $color_name . "','" . $battery . "', '" . $body_grade . "', '" . $lcd_grade . "', '" . $digitizer_grade . "', 
												'" . $ram . "', '" . $memory . "',  '" . $defectsCode . "', '" . $jsonData2 . "', 
												'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "')";
								// echo $sql;die;
								$db->query($conn, $sql);
							}

							/////////////////////// For Testing //////////////////////
							/////////////////////////////////////////////////////////
							if ($_SERVER['HTTP_HOST'] == 'localhost' && $test_on_local == 1) {
								$model_no 		= "A1395";
								$carrier_name 	= "WiFi";
								$memory 		= "32GB";
								$color_name		= "SpaceGray";
							}
							////////////////////////////////////////////////////////

							$sku_code	 		 = get_sku($db, $conn, 'Model#', $model_no);
							$sku_code			.= get_sku($db, $conn, 'Carrier', $carrier_name);
							$sku_code			.= get_sku($db, $conn, 'Capacity', $memory);
							$sku_code			.= get_sku($db, $conn, 'Color', $color_name);
							$sku_code		 	 = str_replace(" ", '', $sku_code);

						
							$sql_pd01 		= "	SELECT c.product_uniqueid, a.*
												FROM purchase_order_detail_receive a 
												INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
												INNER JOIN products c ON c.id = b.product_id
												WHERE a.enabled = 1 
												AND b.po_id 	= '" . $id . "'
												AND (a.serial_no_barcode IS NULL OR a.serial_no_barcode = '')  ";

							$sql_pd01_2		=  $sql_pd01 . " LIMIT 1 ";

							$sql_pd01 		.= " AND a.base_product_id = '" . $sku_code . "'
												LIMIT 1 "; // AND a.po_detail_id = '" . $diagnostic_invoice_no . "' 
							$result_pd01	= $db->query($conn, $sql_pd01);
							$count_pd01		= $db->counter($result_pd01);
							if ($count_pd01 > 0) {
								$row_pd01				= $db->fetch($result_pd01);
								$receive_id_2 			= $row_pd01[0]['id'];

								$inventory_status = '6';
								if ($defectsCode == '' || $defectsCode == NULL) {
									if ($battery >= '60') {
										$inventory_status = '5';
									}
								}
								if ($battery < '60') {
									$defectsCode .= ' - Battery Health is less then 60%';
								}
								if ($battery > '60' && ($lcd_grade == 'D' || $digitizer_grade == 'D')) {
									$overall_grade = "D";
								} else if ($battery >= '70') {
									if ($lcd_grade == 'A' && $digitizer_grade == 'A') {
										$overall_grade = "A";
									}
									if ($lcd_grade == 'B' && $digitizer_grade == 'A') {
										$overall_grade = "B";
									}
									if ($lcd_grade == 'C' && $digitizer_grade == 'A') {
										$overall_grade = "C";
									}

									if ($lcd_grade == 'A' && $digitizer_grade == 'B') {
										$overall_grade = "B";
									}
									if ($lcd_grade == 'B' && $digitizer_grade == 'B') {
										$overall_grade = "B";
									}
									if ($lcd_grade == 'C' && $digitizer_grade == 'B') {
										$overall_grade = "C";
									}

									if ($lcd_grade == 'A' && $digitizer_grade == 'C') {
										$overall_grade = "C";
									}
									if ($lcd_grade == 'B' && $digitizer_grade == 'C') {
										$overall_grade = "C";
									}
									if ($lcd_grade == 'C' && $digitizer_grade == 'C') {
										$overall_grade = "C";
									}
								} else if ($battery < '70' && $battery >= '60') {
									if ($lcd_grade == 'A' && $digitizer_grade == 'A') {
										$overall_grade = "B";
									}
									if ($lcd_grade == 'B' && $digitizer_grade == 'A') {
										$overall_grade = "B";
									}
									if ($lcd_grade == 'C' && $digitizer_grade == 'A') {
										$overall_grade = "C";
									}

									if ($lcd_grade == 'A' && $digitizer_grade == 'B') {
										$overall_grade = "B";
									}
									if ($lcd_grade == 'B' && $digitizer_grade == 'B') {
										$overall_grade = "B";
									}
									if ($lcd_grade == 'C' && $digitizer_grade == 'B') {
										$overall_grade = "C";
									}

									if ($lcd_grade == 'A' && $digitizer_grade == 'C') {
										$overall_grade = "C";
									}
									if ($lcd_grade == 'B' && $digitizer_grade == 'C') {
										$overall_grade = "C";
									}
									if ($lcd_grade == 'C' && $digitizer_grade == 'C') {
										$overall_grade = "C";
									}
								}

								if ($_SERVER['HTTP_HOST'] == 'localhost' && $test_on_local == 1) {
									$overall_grade = "A";
								}

								if ($overall_grade != "") {
									$sub_product_id = $sku_code . "-" . $overall_grade;
								} else {
									$sub_product_id = $sku_code;
								}

								$sql_c_up	= "UPDATE  purchase_order_detail_receive SET	phone_check_api_data	= '" . $jsonData2 . "',
																							make_name				= '" . $make_name . "',
																							model_name				= '" . $model_name . "',
																							model_no				= '" . $model_no . "',
																							carrier_name			= '" . $carrier_name . "',
																							color_name				= '" . $color_name . "',
																							battery					= '" . $battery . "',
																							lcd_grade				= '" . $lcd_grade . "',
																							digitizer_grade	= '" . $digitizer_grade . "',
																							overall_grade			= '" . $overall_grade . "',
																							ram						= '" . $ram . "',
																							storage					= '" . $memory . "',
																							defects_or_notes		= '" . $defectsCode . "',
																							inventory_status		= '" . $inventory_status . "', 
																							sku_code				= '" . $sku_code . "',
																							sub_product_id			= '" . $sub_product_id . "',

																							serial_no_barcode		= '" . $data . "',

																							update_timezone		    = '" . $timezone . "',
																							update_date			    = '" . $add_date . "',
																							update_by_user_id	    = '" . $_SESSION['user_id'] . "',
																							update_by			    = '" . $_SESSION['username'] . "',
																							update_ip			    = '" . $add_ip . "'
											WHERE id = '" . $receive_id_2 . "' ";
								// echo "<br><br>" . $sql_c_up;
								$ok = $db->query($conn, $sql_c_up);
								if ($ok) {
									$k++;
								}
							} else {
								$result_pd01_2	= $db->query($conn, $sql_pd01_2);
								$count_pd01_2	= $db->counter($result_pd01_2);
								if ($count_pd01_2 > 0) {
									$n++;
									$sku_code_array[] = $sku_code;
								}
							}
							 
						} else {
							$n++;
							$imei_already .= $data . "<br>";
						}
					}
					$m++;
				}
			}
			if (!isset($all_devices_info['imei']) || (isset($all_devices_info['imei']) && sizeof($all_devices_info['imei']) == 0)) {
				$error6['msg'] = "No IMEI # is avaible again this invoice# in the date.";
			}
			if ($k > 0) {
				$msg6['msg_success'] = "Total " . $k . " IMEI # have been updated successfully.";
				if ($n > 0) {
					if ($imei_already != "") {
						$msg6['msg_success'] .= "<br><br>These IMEI # already exist:<br>" . $imei_already;
					}
					if (isset($sku_code_array) && sizeof($sku_code_array) > 0) {
						$sku_code_array = array_unique($sku_code_array);
						$msg6['msg_success'] .= "<br><br>These PhoneCheck SKU does not match with Any Other Product ID in PO :<br>";
						foreach ($sku_code_array as $d1) {
							$msg6['msg_success'] .= $d1 . "<br>";
						}
					}
				}
				// $serial_no_manual	= $sub_location_id_barcode = "";
			} else if ($n > 0) {
				$error6['msg'] = "";
				if ($imei_already != "") {
					$error6['msg'] .= "<br><br>These IMEI # already exist:<br>" . $imei_already;
				}
				if (isset($sku_code_array) && sizeof($sku_code_array) > 0) {
					$sku_code_array = array_unique($sku_code_array);
					$error6['msg'] .= "<br><br>These PhoneCheck SKU does not match with Any Other Product ID in PO :<br>";
					foreach ($sku_code_array as $d1) {
						$error6['msg'] .= $d1 . "<br>";
					}
				}
			}
		}
	} else {
		$error6['msg'] = "There is error, Please check it.";
	}
}
*/
if (isset($_POST['is_Submit_tab6_5']) && $_POST['is_Submit_tab6_5'] == 'Y') {
	extract($_POST);

	if (!isset($sub_location_id_manual_diagnostic) || (isset($sub_location_id_manual_diagnostic)  && ($sub_location_id_manual_diagnostic == "0" || $sub_location_id_manual_diagnostic == ""))) {
		$error6['sub_location_id_manual_diagnostic'] = "Required";
	}
	if (!isset($serial_no_manual_diagnostic) || (isset($serial_no_manual_diagnostic)  && ($serial_no_manual_diagnostic == "0" || $serial_no_manual_diagnostic == ""))) {
		$error6['serial_no_manual_diagnostic'] = "Required";
	}
	if (!isset($product_id_manual_diagnostic) || (isset($product_id_manual_diagnostic)  && ($product_id_manual_diagnostic == "0" || $product_id_manual_diagnostic == ""))) {
		$error6['product_id_manual_diagnostic'] = "Required";
	}
	foreach ($serial_no_manual as $data) {
		if ($data != "" && $data != null) {
			$serial_no_manual = array_filter($serial_no_manual, function ($data) {
				return $data !== "" && $data !== null;
			});
		}
	}
	$serial_no_manual_diagnostic = array_values($serial_no_manual_diagnostic);

	if (empty($error6)) {
		if (po_permisions("Diagnostic") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {
			$k = $n = 0;
			$m = 1;
			foreach ($serial_no_manual_diagnostic as $data) {
				if ($data != "" && $data != null) {
					$sql_pd01 		= "	SELECT a.* 
										FROM purchase_order_detail_receive a 
										WHERE a.enabled = 1  
										AND a.serial_no_barcode	= '" . $data . "' ";
					$result_pd01	= $db->query($conn, $sql_pd01);
					$count_pd01		= $db->counter($result_pd01);
					if ($count_pd01 == 0) {
						$sql_pd01 		= "	SELECT a.* 
											FROM purchase_order_detail_receive a 
											WHERE a.enabled = 1 
											AND a.po_detail_id = '" . $product_id_manual_diagnostic . "' 
											AND (a.serial_no_barcode IS NULL OR a.serial_no_barcode = '')
											LIMIT 1";
						$result_pd01	= $db->query($conn, $sql_pd01);
						$count_pd01		= $db->counter($result_pd01);
						if ($count_pd01 > 0) {
							$row_pd01		= $db->fetch($result_pd01);
							$receive_id_2 	= $row_pd01[0]['id'];
							$sql_c_up = "UPDATE  purchase_order_detail_receive SET	serial_no_barcode					= '" . $data . "',
																					sub_location_id_after_diagnostic	= '" . $sub_location_id_manual_diagnostic . "',
																					is_diagnost							= '1',
																					diagnose_by_user					= '" . $_SESSION['username'] . "',
																					diagnose_by_user_id					= '" . $_SESSION['user_id'] . "',
																					diagnose_timezone					= '" . $timezone . "',
 																					diagnose_date						= '" . $add_date . "',
																					diagnose_ip							= '" . $add_ip . "'
							WHERE id = '" . $receive_id_2 . "' ";
							$ok = $db->query($conn, $sql_c_up);
							if ($ok) {
								$k++;

								update_po_detail_status($db, $conn, $product_id_manual_diagnostic, $diagnost_status_dynamic);

								$msg6['msg_success']	= "Serial No has been updated successfully.";
							} else {
								$error6['msg'] = "There is error, Please check it.";
							}
						}
					} else {
						$n++;
						$error6["field_name_" . $m] = "Exist";
					}

					if ($k > 0) {

						update_po_status($db, $conn, $id, $diagnost_status_dynamic);

						$msg6['msg_success'] = "Totl " . $k . " Serial No has been updated successfully.";
						unset($serial_no_manual_diagnostic);
						// $serial_no_manual	= $sub_location_id_barcode = "";
					} else if ($n > 0) {
						$error6['msg'] = "These Serial Nos already exist.";
					}
				}
				$m++;
			}
			if ($k > 0) {
				$msg6['msg_success'] = "Product with manual " . $k . " Serial No has been received successfully.";
				unset($serial_no_manual);
				// $serial_no_manual	= $sub_location_id_barcode = "";
			} else if ($n > 0) {
				$error6['msg'] = "These Serial Nos already exist.";
			}
		}
	}
}
if (isset($_POST['is_Submit_tab6_4']) && $_POST['is_Submit_tab6_4'] == 'Y') {
	extract($_POST);
	if (empty($error6)) {
		if (access("edit_perm") == 0) {
			$error6['msg'] = "You do not have edit permissions.";
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
																					is_diagnost			= '1',
																					diagnose_by_user	= '" . $_SESSION['username'] . "',
																					diagnose_by_user_id	= '" . $_SESSION['user_id'] . "',
																					diagnose_timezone	= '" . $timezone . "',
 																					diagnose_date		= '" . $add_date . "',
																					diagnose_ip			= '" . $add_ip . "'
								WHERE id = '" . $receive_id . "' ";
						$ok = $db->query($conn, $sql_c_up);
						if ($ok) {
							$k++;
							if (isset($error6['msg'])) unset($error6['msg']);
						} else {
							$error6['msg'] = "There is Error, Please check it again OR contact Support Team.";
						}
					}
				}
				if ($k > 0) {
					if (isset($msg6['msg_success'])) {
						$msg6['msg_success'] .= "<br>Deduct Serial Number has been updated successfully.";
					} else {
						$msg6['msg_success'] = "Deduct Serial Number has been updated successfully.";
					}
					$logistics_status = "";
				}
			} else {
				$error6['msg'] = "Please select atleast one record.";
			}
		}
	} else {
		$error6['msg'] = "Please check required fields in the form.";
	}
}

if (isset($_POST['is_Submit_tab6_2']) && $_POST['is_Submit_tab6_2'] == 'Y') {
	extract($_POST);
	if (!isset($sub_location_id_barcode_diagnostic) || (isset($sub_location_id_barcode_diagnostic)  && ($sub_location_id_barcode_diagnostic == "0" || $sub_location_id_barcode_diagnostic == ""))) {
		$error6['sub_location_id_barcode_diagnostic'] = "Required";
	}
	if (!isset($serial_no_barcode_diagnostic) || (isset($serial_no_barcode_diagnostic)  && ($serial_no_barcode_diagnostic == "0" || $serial_no_barcode_diagnostic == ""))) {
		$error6['serial_no_barcode_diagnostic'] = "Required";
	}
	if (!isset($product_id_barcode_diagnostic) || (isset($product_id_barcode_diagnostic)  && ($product_id_barcode_diagnostic == "0" || $product_id_barcode_diagnostic == ""))) {
		$error6['product_id_barcode_diagnostic'] = "Required";
	}
	if (empty($error6)) {
		if (po_permisions("Diagnostic") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {
			$sql_pd01 		= "	SELECT a.* 
								FROM purchase_order_detail_receive a 
								WHERE a.enabled = 1  
								AND a.serial_no_barcode	= '" . $serial_no_barcode_diagnostic . "' ";
			$result_pd01	= $db->query($conn, $sql_pd01);
			$count_pd01		= $db->counter($result_pd01);
			if ($count_pd01 == 0) {
				$sql_pd01 		= "	SELECT a.* 
									FROM purchase_order_detail_receive a 
									WHERE a.enabled = 1 
									AND a.po_detail_id = '" . $product_id_barcode_diagnostic . "' 
									AND (a.serial_no_barcode IS NULL OR a.serial_no_barcode = '')
									LIMIT 1";
				$result_pd01	= $db->query($conn, $sql_pd01);
				$count_pd01		= $db->counter($result_pd01);
				if ($count_pd01 > 0) {
					$row_pd01		= $db->fetch($result_pd01);
					$receive_id_2 	= $row_pd01[0]['id'];
					$sql_c_up = "UPDATE  purchase_order_detail_receive SET 		serial_no_barcode					= '" . $serial_no_barcode_diagnostic . "',
																				sub_location_id_after_diagnostic	= '" . $sub_location_id_barcode_diagnostic . "',
																				is_diagnost							= '1',
																				diagnose_by_user					= '" . $_SESSION['username'] . "',
																				diagnose_by_user_id					= '" . $_SESSION['user_id'] . "',
																				diagnose_timezone					= '" . $timezone . "',
																				diagnose_date						= '" . $add_date . "',
																				diagnose_ip							= '" . $add_ip . "'
							WHERE id = '" . $receive_id_2 . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {

						update_po_detail_status($db, $conn, $product_id_barcode_diagnostic, $diagnost_status_dynamic);
						update_po_status($db, $conn, $id, $diagnost_status_dynamic);

						$serial_no_barcode_diagnostic = "";
						$msg6['msg_success']	= "Serial No has been updated successfully.";
					} else {
						$error6['msg'] = "There is error, Please check it.";
					}
				}
			} else {
				$error5['msg'] = "The record is already exist";
			}
		}
	} else {
		$error6['msg'] = "Please check Error in form.";
	}
}
