<?php
 
if ($_SERVER['HTTP_HOST'] == 'localhost' && $test_on_local == 1) {
	$product_id_barcode_diagnostic 		= "5";
	$product_id_manual_diagnostic 		= "5";
	$sub_location_id_manual_diagnostic	= 2311;
	$phone_check_username				= 'Ctinno2';
	$serial_no_manual_diagnostic		= array("DMQD7TMFMF3M1", "DMQD7TMFMF3M2", "DMQD7TMFMF3M3", "DMQD7TMFMF3M4", "DMQD7TMFMF3M5", "DMQD7TMFMF3M6", "DMQD7TMFMF3M7", "DMQD7TMFMF3M8", "DMQD7TMFMF3M9", "DMQD7TMFMF3M10", "DMQD7TMFMF3M11", "DMQD7TMFMF3M12", "R72F1QJ62X", "F9FRFN0HGHKH", "DLXN2FKQFK10");
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
		$error6['msg'] = "Select atleast one record";
	}
	if (empty($error6)) {
		if (po_permisions("Move as Inventory") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			foreach ($ids_for_stock as $id_for_stock) {
				/////////////////////////// Create Stock  START /////////////////////////////
				$sql_pd1	= "	SELECT * FROM (
									SELECT 'PO_products' as rec_type, a.*, b.product_id as inv_product_id, c.product_uniqueid, d.logistics_cost
									FROM purchase_order_detail_receive a
									INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
									INNER JOIN products c ON c.id = b.product_id
									INNER JOIN purchase_orders d ON d.id = b.po_id
									WHERE a.id = '" . $id_for_stock . "'

									UNION ALL 

									SELECT 'non_PO_products' as rec_type, a.*, a.product_id as inv_product_id, c.product_uniqueid, d.logistics_cost
									FROM purchase_order_detail_receive a
									INNER JOIN products c ON c.id = a.product_id
									INNER JOIN purchase_orders d ON d.id = a.po_id
									WHERE a.id = '" . $id_for_stock . "'
									) AS t1 ";
				// echo "<br><br>" . $sql_pd1;
				$result_pd1	= $db->query($conn, $sql_pd1);
				$count_pd1	= $db->counter($result_pd1);
				if ($count_pd1 > 0) {

					$row_pd1 				= $db->fetch($result_pd1);
					$inv_receive_id			= $row_pd1[0]['id'];
					$inv_po_id				= $row_pd1[0]['po_id'];
					$inv_po_detail_id		= $row_pd1[0]['po_detail_id'];
					$inv_product_id			= $row_pd1[0]['inv_product_id'];
					$old_base_product_id	= $row_pd1[0]['product_uniqueid'];
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

					$po_logistic_cost = $po_receiving_labor = $po_diagnostic_labor = 0;
					// $po_logistic_cost 		= po_logistic_cost($db, $conn, $inv_po_id, $logistics_cost);
					// $po_receiving_labor 		= po_receiving_labor($db, $conn, $inv_po_id);
					// $po_diagnostic_labor 	= po_diagnostic_labor($db, $conn, $inv_po_id);
					// $inv_price 				= round(($inv_price + $po_receiving_labor + $po_diagnostic_labor + $po_logistic_cost), 2);

					$sql_pd2	= "	SELECT a.id FROM product_stock a WHERE a.receive_id = '" . $inv_receive_id . "' ";
					// echo "<br><br>" . $sql_pd2;
					$result_pd2	= $db->query($conn, $sql_pd2);
					$count_pd2	= $db->counter($result_pd2);
					if ($count_pd2 == 0) {
						$sql6 = "INSERT INTO product_stock(subscriber_users_id, receive_id, product_id, serial_no, 
															p_total_stock, stock_grade, p_inventory_status, sub_location,
															battery_percentage, ram_size, storage_size, processor_size, defects_or_notes, 
															model_name, model_no, make_name, color_name, 
															body_grade, lcd_grade, digitizer_grade,  price,
															add_by_user_id, add_date,  add_by, add_ip, add_timezone)
								VALUES('" . $subscriber_users_id . "', '" . $inv_receive_id . "', '" . $inv_product_id . "', '" . $inv_serial_no . "',
										1, '" . $inv_overall_grade . "', '" . $inv_inventory_status . "', '" . $inv_sub_location . "', 
										'" . $inv_battery . "',  '" . $inv_ram . "',  '" . $inv_storage . "', '" . $inv_processor . "', '" . $inv_defects_or_notes . "', 
										'" . $inv_model_name . "', '" . $inv_model_no . "', '" . $inv_make_name . "',  '" . $inv_color_name . "',
										'" . $inv_body_grade . "', '" . $inv_lcd_grade . "', '" . $inv_digitizer_grade . "', '" . $inv_price . "', 
										'" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
						// echo "<br><br>product_stock: " . $sql6;
						$db->query($conn, $sql6);
					} else {
						$sql_c_up = "UPDATE product_stock SET 	serial_no 			= '" . $inv_serial_no . "',
																stock_grade 		= '" . $inv_overall_grade . "',
																p_inventory_status	= '" . $inv_inventory_status . "',
																sub_location 		= '" . $inv_sub_location . "',
																battery_percentage	= '" . $inv_battery . "',
																ram_size 			= '" . $inv_ram . "',
																storage_size 		= '" . $inv_storage . "',
																processor_size 		= '" . $inv_processor . "',
																defects_or_notes	= '" . $inv_defects_or_notes . "',
																model_name 			= '" . $inv_model_name . "',
																model_no 			= '" . $inv_model_no . "',
																make_name 			= '" . $inv_make_name . "',
																color_name 			= '" . $inv_color_name . "',
																body_grade 			= '" . $inv_body_grade . "',
																lcd_grade 			= '" . $inv_lcd_grade . "',
																digitizer_grade		= '" . $inv_digitizer_grade . "',
																price 				= '" . $inv_price . "',
																
																update_by				= '" . $_SESSION['username'] . "',
																update_by_user_id		= '" . $_SESSION['user_id'] . "',
																update_timezone			= '" . $timezone . "',
																update_date				= '" . $add_date . "',
																update_ip				= '" . $add_ip . "',
																added_from_module_id	= '" . $module_id . "'
									 WHERE receive_id = '" . $inv_receive_id . "' ";
						// echo "<br><br>purchase_order_detail_receive: " . $sql_c_up;
						$db->query($conn, $sql_c_up);
					}
 
					$sql_c_up = "UPDATE purchase_order_detail_receive 
																		SET edit_lock 			= '1',
																			Logistic_cost 		= '" . round($po_logistic_cost, 2) . "',
																			receiving_labor 	= '" . round($po_receiving_labor, 2) . "',
																			diagnostic_labor 	= '" . round($po_diagnostic_labor, 2) . "'
									 WHERE id = '" . $inv_receive_id . "' ";
					// echo "<br><br>purchase_order_detail_receive: " . $sql_c_up;
					$db->query($conn, $sql_c_up);
					if (isset($inv_po_detail_id) && $inv_po_detail_id > 0) {
						update_po_detail_status($db, $conn, $inv_po_detail_id, $tested_or_graded_status);
					}
					update_po_status($db, $conn, $id, $tested_or_graded_status);
					$disp_status_name = get_status_name($db, $conn, $tested_or_graded_status);

					$k++;
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

if (isset($_POST['is_Submit_tab6_6']) && $_POST['is_Submit_tab6_6'] == 'Y') {
	extract($_POST);
	if (isset($assignment_id)  && ($assignment_id == "")) {
		$error6['assignment_id'] = "Required";
	}
	if (isset($phone_check_username)  && ($phone_check_username == "")) {
		$error6['phone_check_username'] = "Required";
	}
	if (isset($diagnostic_date)  && ($diagnostic_date == "")) {
		$error6['diagnostic_date'] = "Required";
	}
	if (empty($error6)) {
		if (po_permisions("Diagnostic") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {
			$diagnostic_date1 	= convert_date_mysql_slash($diagnostic_date);

			$sql_as1		= " SELECT a.assignment_no FROM users_bin_for_diagnostic a WHERE a.id = '" . $assignment_id . "'  "; // echo $sql_ee;
			$result_as1		= $db->query($conn, $sql_as1);
			$row_as1		= $db->fetch($result_as1);
 			$assignment_no	=  $row_as1[0]['assignment_no']; 

			$invoiceNo 	= $assignment_no;
			$limit		= 500;  // Optional, max 500 records
			$offset		= 1;  // Optional

			if ($_SERVER['HTTP_HOST'] == 'localhost' && $test_on_local == 1) {
				$invoiceNo 			= "121824";  // Optional
				$diagnostic_date1	= "2025-01-28";  // Filter by Date (optional)
			}

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
			// echo "<br><br><pre>"; print_r($all_devices_info);die;
			if (isset($all_devices_info['imei']) && sizeof($all_devices_info['imei']) > 0) {
				$m = 1;
				foreach ($all_devices_info['imei'] as $data) {
					// $data = "DMTPD5R1FK10";
					if ($data != "" && $data != null) {

						$insert_bin_and_po_id_fields 	= "po_id, assignment_id, ";
						$insert_bin_and_po_id_values 	= "'" . $id . "', '" . $assignment_id . "', ";
						$serial_no_barcode_diagnostic 	= $data;

						$sql_pd01_4		= "	SELECT  a.*
											FROM phone_check_api_data a 
											WHERE a.enabled = 1 
											AND a.imei_no = '" . $data . "'
											ORDER BY a.id DESC LIMIT 1";
						$result_pd01_4	= $db->query($conn, $sql_pd01_4);
						$count_pd01_4	= $db->counter($result_pd01_4);
						if ($count_pd01_4 == 0) {
							$model_name = $model_no = $make_name = $carrier_name = $color_name = $battery = $body_grade = $lcd_grade = $digitizer_grade = $ram = $memory = $defectsCode = $lcd_grade = $lcd_grade = $lcd_grade = $overall_grade = $sku_code = "";
							$device_detail_array 	= getinfo_phonecheck_imie($data);
							$jsonData2				= json_encode($device_detail_array);
							if ($jsonData2 != '[]' && $jsonData2 != 'null' && $jsonData2 != null && $jsonData2 != '' && $jsonData2 != '{"msg":"token expired"}') {
								include("components/purchase/purchase_orders/process_phonecheck_response.php");
							} else {
								$sql = "INSERT INTO phone_check_api_data(" . $insert_bin_and_po_id_fields . " imei_no, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
										VALUES	(" . $insert_bin_and_po_id_values . " '" . $data . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . TIME_ZONE . "', '" . $module_id . "')";
								$db->query($conn, $sql);
							}
							$k++;
						}
					}
				}
			}
			if (!isset($all_devices_info['imei']) || (isset($all_devices_info['imei']) && sizeof($all_devices_info['imei']) == 0)) {
				$error6['msg'] = "No Serial# is avaible again this PO# in the date.";
			}
			if ($k > 0) {
				$msg6['msg_success'] = "Total " . $k . " Serial# have been updated successfully.";
			}
		}
	} else {
		$error6['msg'] = "There is error, Please check it.";
	}
}
if (isset($_POST['is_Submit2_preview']) && $_POST['is_Submit2_preview'] == 'Y') {
	extract($_POST);
	if (empty($error6)) {
		if (access("add_perm") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			if (isset($bulkserialNo) && $bulkserialNo != null) {
				foreach ($bulkserialNo as $data) {
					$phone_check_product_id		= $product_ids[$data];
					$single_model_no			= $model_nos[$data];
					$product_id_fetched 		= 0;

					if ($phone_check_product_id != "") {
						$sql_pd01 		= "	SELECT a.id, a.product_id, c.product_category
											FROM purchase_order_detail a 
											INNER JOIN purchase_orders b ON b.id = a.po_id
											INNER JOIN products c ON c.id = a.product_id
											WHERE 1=1 
											AND a.po_id = '" . $id . "' 
											AND c.product_uniqueid = '" . $phone_check_product_id . "'  ";
						$result_pd01	= $db->query($conn, $sql_pd01);
						$count_pd01		= $db->counter($result_pd01);
						if ($count_pd01 > 0) {
							$row_pd01						= $db->fetch($result_pd01);
							$product_category_diagn			= $row_pd01[0]['product_category'];
							$id_identification_field_name	= "po_detail_id";
							$id_identification_field_value	= $row_pd01[0]['id'];
							$product_id_fetched				= $row_pd01[0]['product_id'];
						} else {
							$sql_pd01 		= "	SELECT c.id, c.product_category FROM products c WHERE 1=1 AND c.product_uniqueid = '" . $phone_check_product_id . "'  ";
							$result_pd01	= $db->query($conn, $sql_pd01);
							$count_pd01		= $db->counter($result_pd01);
							if ($count_pd01 > 0) {
								$row_pd01						= $db->fetch($result_pd01);
								$product_category_diagn			= $row_pd01[0]['product_category'];
								$id_identification_field_name	= "product_id_not_in_po";
								$id_identification_field_value	= $row_pd01[0]['id'];
								$product_id_fetched				= $id_identification_field_value;
							}
						}
						$sql_pd01_4 		= "	SELECT  a.*
												FROM purchase_order_detail_receive_diagnostic_fetch a 
												WHERE a.enabled = 1 
												AND a.po_id = '" . $id . "'
												AND a.assignment_id = '" . $assignment_id . "'
												AND a.serial_no = '" . $data . "'
												ORDER BY a.id DESC LIMIT 1";
						$result_pd01_4	= $db->query($conn, $sql_pd01_4);
						$count_pd01_4	= $db->counter($result_pd01_4);
						if ($count_pd01_4 == 0) {
							//  echo "<br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: ".$phone_check_product_id;
							$sql = "INSERT INTO purchase_order_detail_receive_diagnostic_fetch (po_id, assignment_id, product_id, " . $id_identification_field_name . ", product_category, serial_no, model_no,  add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
									VALUES	('" . $id . "', '" . $assignment_id . "', '" . $product_id_fetched . "', '" . $id_identification_field_value . "', '" . $product_category_diagn . "', '" . $data . "', '" . $single_model_no . "',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . TIME_ZONE . "', '" . $module_id . "')";
							$ok = $db->query($conn, $sql);
							if ($ok) {
								$sql_c_up = "UPDATE  phone_check_api_data SET 	 
																				is_processed						= '1', 

																				update_timezone		   	 			= '" . $timezone . "',
																				update_date			    			= '" . $add_date . "',
																				update_by_user_id	   	 			= '" . $_SESSION['user_id'] . "',
																				update_by			    			= '" . $_SESSION['username'] . "',
																				update_ip			    			= '" . $add_ip . "',
																				update_from_module_id				= '" . $module_id . "'
										WHERE imei_no = '" . $data . "' ";
								$db->query($conn, $sql_c_up);
								$k++;
							}
						}
					}
				}
			}

			if ($k > 0) {
				$msg6['msg_success'] = "Totl " . $k . " Serial#s have been maped.";
			}
		}
	} else {
		$error6['msg'] = "There is error, Please check it.";
	}
}

/*
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
																					is_import_diagnostic_data			= '1',
																					edit_lock							= '1',
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
						$disp_status_name = get_status_name($db, $conn, $diagnost_status_dynamic);

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
*/
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
	$phone_check_product_id = "";
	if (!isset($sub_location_id_barcode_diagnostic) || (isset($sub_location_id_barcode_diagnostic)  && ($sub_location_id_barcode_diagnostic == "0" || $sub_location_id_barcode_diagnostic == ""))) {
		$error6['sub_location_id_barcode_diagnostic'] = "Required";
	}
	if (!isset($serial_no_barcode_diagnostic) || (isset($serial_no_barcode_diagnostic)  && ($serial_no_barcode_diagnostic == "0" || $serial_no_barcode_diagnostic == ""))) {
		$error6['serial_no_barcode_diagnostic'] = "Required";
	} else {
		$sql_pd01_4		= "	SELECT  a.*
							FROM phone_check_api_data a 
							WHERE a.enabled = 1 
							AND a.imei_no = '" . $serial_no_barcode_diagnostic . "'
							ORDER BY a.id DESC LIMIT 1";
		$result_pd01_4	= $db->query($conn, $sql_pd01_4);
		$count_pd01_4	= $db->counter($result_pd01_4);
		if ($count_pd01_4 > 0) {
			$row_pd01_4					= $db->fetch($result_pd01_4);
			$phone_check_model_no		= $row_pd01_4[0]['model_no'];
		} else {
			// DMPHTE3SDFHW
			$model_name = $model_no = $make_name = $carrier_name = $color_name = $battery = $body_grade = $lcd_grade = $digitizer_grade = $ram = $memory = $defectsCode = $overall_grade = $sku_code = "";
			$device_detail_array	= getinfo_phonecheck_imie($serial_no_barcode_diagnostic);
			$jsonData2				= json_encode($device_detail_array);
			if ($jsonData2 != '[]' && $jsonData2 != 'null' && $jsonData2 != null && $jsonData2 != '' && $jsonData2 != '{"msg":"token expired"}') {
				include("process_phonecheck_response.php");
			}
		}
	}
	// $phone_check_product_id = "iPad2WiFi64GBSpaceGray";
	if ($phone_check_model_no != "") {
		$sql_pd01 		= "	SELECT a.*, c.product_desc, c.product_uniqueid
							FROM purchase_order_detail a 
							INNER JOIN purchase_orders b ON b.id = a.po_id
							INNER JOIN products c ON c.id = a.product_id
							WHERE 1=1 
							AND a.po_id = '" . $id . "' 
							AND c.product_model_no = '" . $phone_check_model_no . "'  ";
		$result_pd01	= $db->query($conn, $sql_pd01);
		$count_pd01		= $db->counter($result_pd01);
		if ($count_pd01 > 0) {
			$row_pd01						= $db->fetch($result_pd01);
			$product_id_barcode_diagnostic 	= $row_pd01[0]['id'];
		}
	}
	if (!isset($product_id_barcode_diagnostic) || (isset($product_id_barcode_diagnostic)  && ($product_id_barcode_diagnostic == "0" || $product_id_barcode_diagnostic == ""))) {
		$error6['product_id_barcode_diagnostic'] = "Required";
	}

	if (empty($error6)) {
		///*
		if (po_permisions("Diagnostic") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {

			$sql_pd01 		= "	SELECT c.product_category
								FROM purchase_order_detail a 
								INNER JOIN products c ON c.id = a.product_id
								WHERE 1=1 
								AND a.id = '" . $product_id_barcode_diagnostic . "'  ";
			$result_pd01	= $db->query($conn, $sql_pd01);
			$count_pd01		= $db->counter($result_pd01);
			if ($count_pd01 > 0) {
				$row_pd01				= $db->fetch($result_pd01);
				$product_category_diagn	= $row_pd01[0]['product_category'];
			}

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
									AND a.recevied_product_category = '" . $product_category_diagn . "' 
									AND a.po_id = '" . $id . "' 
									AND (a.serial_no_barcode IS NULL OR a.serial_no_barcode = '')
									LIMIT 1";
				$result_pd01	= $db->query($conn, $sql_pd01);
				$count_pd01		= $db->counter($result_pd01);
				if ($count_pd01 > 0) {
					$row_pd01		= $db->fetch($result_pd01);
					$receive_id_2 	= $row_pd01[0]['id'];
					$sql_c_up = "UPDATE  purchase_order_detail_receive SET 		po_detail_id						= '" . $product_id_barcode_diagnostic . "',
																				serial_no_barcode					= '" . $serial_no_barcode_diagnostic . "',
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
						$disp_status_name = get_status_name($db, $conn, $diagnost_status_dynamic);

						$serial_no_barcode_diagnostic = $product_id_barcode_diagnostic = "";
						$msg6['msg_success']	= "Serial No has been updated successfully.";
					} else {
						$error6['msg'] = "There is error, Please check it.";
					}
				}
			} else {
				$error5['msg'] = "The record is already exist";
			}
		}
		//*/
	} else {
		$error6['msg'] = "Please check Error in form.";
	}
}
if (isset($_POST['is_Submit_tab6_2_1']) && $_POST['is_Submit_tab6_2_1'] == 'Y') {
	extract($_POST);
	if (!isset($product_id_boken_device) || (isset($product_id_boken_device)  && ($product_id_boken_device == "0" || $product_id_boken_device == ""))) {
		$error6['product_id_boken_device'] = "Required";
	}
	if (!isset($serial_no_boken_device) || (isset($serial_no_boken_device)  && ($serial_no_boken_device == "0" || $serial_no_boken_device == ""))) {
		$error6['serial_no_boken_device'] = "Required";
	}
	if (!isset($sub_location_id_boken_device) || (isset($sub_location_id_boken_device)  && ($sub_location_id_boken_device == "0" || $sub_location_id_boken_device == ""))) {
		$error6['sub_location_id_boken_device'] = "Required";
	}
	if (!isset($battery_boken_device) || (isset($battery_boken_device)  && ($battery_boken_device == "0" || $battery_boken_device == ""))) {
		$error6['battery_boken_device'] = "Required";
	}
	if (!isset($body_grade_boken_device) || (isset($body_grade_boken_device)  && ($body_grade_boken_device == "0" || $body_grade_boken_device == ""))) {
		$error6['body_grade_boken_device'] = "Required";
	}
	if (!isset($lcd_grade_boken_device) || (isset($lcd_grade_boken_device)  && ($lcd_grade_boken_device == "0" || $lcd_grade_boken_device == ""))) {
		$error6['lcd_grade_boken_device'] = "Required";
	}
	if (!isset($digitizer_grade_boken_device) || (isset($digitizer_grade_boken_device)  && ($digitizer_grade_boken_device == "0" || $digitizer_grade_boken_device == ""))) {
		$error6['digitizer_grade_boken_device'] = "Required";
	}
	if (!isset($overall_grade_boken_device) || (isset($overall_grade_boken_device)  && ($overall_grade_boken_device == "0" || $overall_grade_boken_device == ""))) {
		$error6['overall_grade_boken_device'] = "Required";
	}
	if (!isset($inventory_status_boken_device) || (isset($inventory_status_boken_device)  && ($inventory_status_boken_device == "0" || $inventory_status_boken_device == ""))) {
		$error6['inventory_status_boken_device'] = "Required";
	}

	if (empty($error6)) {

		if (po_permisions("Diagnostic") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {
			$update_rec 	= "";
			$sql_pd01 		= "	SELECT c.product_category
								FROM purchase_order_detail a 
								INNER JOIN products c ON c.id = a.product_id
								WHERE 1=1 
								AND a.id = '" . $product_id_boken_device . "'  ";
			$result_pd01	= $db->query($conn, $sql_pd01);
			$count_pd01		= $db->counter($result_pd01);
			if ($count_pd01 > 0) {
				$row_pd01				= $db->fetch($result_pd01);
				$product_category_diagn	= $row_pd01[0]['product_category'];
			}

			$sql_pd01 		= "	SELECT a.* 
								FROM purchase_order_detail_receive a 
								WHERE a.enabled = 1  
								AND a.serial_no_barcode	= '" . $serial_no_boken_device . "' ";
			$result_pd01	= $db->query($conn, $sql_pd01);
			$count_pd01		= $db->counter($result_pd01);
			if ($count_pd01 == 0) {
				$sql_pd01 		= "	SELECT a.* 
									FROM purchase_order_detail_receive a 
									WHERE a.enabled = 1 
									AND a.recevied_product_category = '" . $product_category_diagn . "' 
									AND a.po_id = '" . $id . "' 
									AND (a.serial_no_barcode IS NULL OR a.serial_no_barcode = '')
									LIMIT 1";
				$result_pd01	= $db->query($conn, $sql_pd01);
				$count_pd01		= $db->counter($result_pd01);
				if ($count_pd01 > 0) {
					$row_pd01		= $db->fetch($result_pd01);
					$receive_id_2 	= $row_pd01[0]['id'];
					$update_rec = " id='" . $receive_id_2 . "'";
				}
			} else {
				$update_rec = " serial_no_barcode='" . $serial_no_boken_device . "'";
			}
			if ($update_rec != "") {
				$sql_c_up = "UPDATE  purchase_order_detail_receive SET 		
																			po_detail_id						= '" . $product_id_boken_device . "',
																			serial_no_barcode					= '" . $serial_no_boken_device . "',
																			sub_location_id_after_diagnostic	= '" . $sub_location_id_boken_device . "',
																			battery								= '" . $battery_boken_device . "',
																			body_grade							= '" . $body_grade_boken_device . "',
																			lcd_grade							= '" . $lcd_grade_boken_device . "',
																			digitizer_grade						= '" . $digitizer_grade_boken_device . "',
																			overall_grade						= '" . $overall_grade_boken_device . "',
																			ram									= '" . $ram_boken_device . "',
																			storage								= '" . $storage_boken_device . "',
																			processor							= '" . $processor_boken_device . "',
																			inventory_status					= '" . $inventory_status_boken_device . "',
																			defects_or_notes					= '" . $defects_or_notes_boken_device . "',

																			is_diagnost							= '1',
																			is_import_diagnostic_data			= '1',

																			diagnose_by_user					= '" . $_SESSION['username'] . "',
																			diagnose_by_user_id					= '" . $_SESSION['user_id'] . "',
																			diagnose_timezone					= '" . $timezone . "',
																			diagnose_date						= '" . $add_date . "',
																			diagnose_ip							= '" . $add_ip . "' 
						WHERE " . $update_rec . "  ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {

					update_po_detail_status($db, $conn, $product_id_boken_device, $diagnost_status_dynamic);
					update_po_status($db, $conn, $id, $diagnost_status_dynamic);
					$disp_status_name = get_status_name($db, $conn, $diagnost_status_dynamic);

					$serial_no_boken_device =  $sub_location_id_boken_device = $battery_boken_device = $body_grade_boken_device = $inventory_status_boken_device = $processor_boken_device = $storage_boken_device = $overall_grade_boken_device = $ram_boken_device = $lcd_grade_boken_device = $digitizer_grade_boken_device = "";
					$msg6['msg_success']	= "Serial No has been updated successfully.";
				} else {
					$error6['msg'] = "There is error, Please check it.";
				}
			}
		}
	} else {
		$error6['msg'] = "Please check Error in form.";
	}
}
if (isset($_POST['is_Submit_tab6_2_2']) && $_POST['is_Submit_tab6_2_2'] == 'Y') {
	extract($_POST);
	if (!isset($product_id_generate) || (isset($product_id_generate)  && ($product_id_generate == "0" || $product_id_generate == ""))) {
		$error6['product_id_generate'] = "Required";
	}
	if (!isset($received_qty) || (isset($received_qty)  && ($received_qty == "0" || $received_qty == ""))) {
		$error6['received_qty'] = "Required";
	}
	if (empty($error6)) {
		if (po_permisions("Diagnostic") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {
			$sql_dup		= " SELECT a.* 
								FROM purchase_order_detail_receive a
								WHERE a.enabled = 1
								AND a.po_id = '" . $id . "'
								AND a.duplication_check_token2 = '" . $duplication_check_token . "' ";
			$result_dup	= $db->query($conn, $sql_dup);
			$count_dup	= $db->counter($result_dup);
			if ($count_dup == 0) {
				$sql_ee1 = "SELECT a.id, a.product_id, a.product_condition, a.order_price, a.expected_status, a.order_qty, c.product_category
							FROM purchase_order_detail a 
							INNER JOIN products c ON c.id = a.product_id
							INNER JOIN purchase_orders a2 ON a2.id = a.po_id
							WHERE 1 = 1 
							AND a.po_id = '" . $id . "'
							AND a.id = '" . $product_id_generate . "' ";
				//echo $sql_ee1;
				$result_ee1 	= $db->query($conn, $sql_ee1);
				$counter_ee1	= $db->counter($result_ee1);
				if ($counter_ee1 > 0) {
					$row_ee1 = $db->fetch($result_ee1);
					foreach ($row_ee1 as $data_ee1) {
						$po_detail_id				= $data_ee1['id'];
						$c_product_id2 				= $data_ee1['product_id'];
						$c_product_condition2		= $data_ee1['product_condition'];
						$c_expected_status2			= $data_ee1['expected_status'];
						$product_category2			= $data_ee1['product_category'];

						$sql_pd01		= " SELECT a.* 
											FROM purchase_order_detail_receive a
											WHERE a.enabled = 1
											AND a.recevied_product_category = '" . $product_category2 . "' ";
						$result_pd01_2	= $db->query($conn, $sql_pd01);
						$count_pd01_2	= $db->counter($result_pd01_2);
						if ($count_pd01_2 > 0) {
							$k = 0;
							for ($i = 0; $i < $received_qty; $i++) {
								$sql_pd01		= " SELECT a.* 
													FROM purchase_order_detail_receive a
													WHERE a.enabled = 1
													AND a.recevied_product_category = '" . $product_category2 . "' 
													AND (a.serial_no_barcode IS NULL OR a.serial_no_barcode = '')
													LIMIT 1 ";
								$result_pd01	= $db->query($conn, $sql_pd01);
								$count_pd01		= $db->counter($result_pd01);

								if ($count_pd01 > 0) {
									$row_pd01 = $db->fetch($result_pd01);
									foreach ($row_pd01 as $data_pd01) {
										$receive_id_2 		= $data_pd01['id'];
										$sub_location_id 	= $data_pd01['sub_location_id'];
										$price 				= $data_pd01['price'];
										$serial_no_fake 	= "GEN" . $receive_id_2;

										$sql6 = "	INSERT INTO product_stock(subscriber_users_id, receive_id, product_id, serial_no, p_total_stock, 
																				stock_grade, p_inventory_status, sub_location, price, 
																				add_by_user_id, add_date, add_by, add_ip, add_timezone)
													VALUES('" . $subscriber_users_id . "', '" . $receive_id_2 . "', '" . $c_product_id2 . "', '" . $serial_no_fake . "', 1, 
														'" . $c_product_condition2 . "', '" . $c_expected_status2 . "', '" . $sub_location_id . "', '" . $price . "',  
														'" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
										$db->query($conn, $sql6);

										$sql_c_up = "UPDATE purchase_order_detail_receive SET 	
																								po_detail_id						= '" . $po_detail_id . "',
																								serial_no_barcode					= '" . $serial_no_fake . "',
																								edit_lock 							= '1',
																								is_import_diagnostic_data			= '1',
																								is_diagnost							= '1',
																								overall_grade						= '" . $c_product_condition2 . "',
																								inventory_status					= '" . $c_expected_status2 . "',
																								sub_location_id_after_diagnostic 	= '" . $sub_location_id . "',
																								is_diagnostic_bypass 				= 1,
																								duplication_check_token2 			= '" . $duplication_check_token . "',
																								

																								update_by				= '" . $_SESSION['username'] . "',
																								update_by_user_id		= '" . $_SESSION['user_id'] . "',
																								update_timezone			= '" . $timezone . "',
																								update_date				= '" . $add_date . "',
																								update_ip				= '" . $add_ip . "',
																								update_from_module_id	= '" . $module_id . "'
													WHERE id = '" . $receive_id_2 . "' ";
										$db->query($conn, $sql_c_up);
										$k++;
									}
								}
							}
							if ($k > 0) {
								$sql_c_up = "UPDATE purchase_order_detail SET 	
																				is_fk_serial_generated	= 1,

																				update_by				= '" . $_SESSION['username'] . "',
																				update_by_user_id		= '" . $_SESSION['user_id'] . "',
																				update_timezone			= '" . $timezone . "',
																				update_date				= '" . $add_date . "',
																				update_ip				= '" . $add_ip . "',
																				update_from_module_id	= '" . $module_id . "'
											WHERE id = '" . $po_detail_id . "' ";
								$db->query($conn, $sql_c_up);
							}
						} else {
							$error6['msg'] = "No product receive yet for the product's Category.";
						}
					}
					if (isset($k) && $k > 0) {
						$msg6['msg_success']	= "Serial No has been updated successfully.";
						$received_qty = "";
					}
				}
			} else {
				$error6['msg'] = "Serial Numbers already genereated for the product.";
			}
		}
	} else {
		$error6['msg'] = "Please check Error in form.";
	}
}
if (isset($_POST['is_Submit_tab6_2_3']) && $_POST['is_Submit_tab6_2_3'] == 'Y') {
	extract($_POST);
	$field_name = "sub_location_id_fetched";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error6[$field_name] = "Required";
	}
	$field_name = "diagnostic_fetch_id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error6[$field_name] = "Required";
	}
	if (empty($error6)) {
		if (po_permisions("Diagnostic") == 0) {
			$error6['msg'] = "You do not have add permissions.";
		} else {
			$sql_pd01 		= "	SELECT a.* 
								FROM purchase_order_detail_receive_diagnostic_fetch a 
								WHERE a.enabled = 1  
								AND a.id	= '" . $diagnostic_fetch_id . "' ";
			$result_pd01	= $db->query($conn, $sql_pd01);
			$count_pd01		= $db->counter($result_pd01);
			if ($count_pd01 > 0) {
				$row_pd01						= $db->fetch($result_pd01);
				$po_detail_id1					= $row_pd01[0]['po_detail_id'];
				$product_id_not_in_po			= $row_pd01[0]['product_id_not_in_po'];
				$product_category_diagn			= $row_pd01[0]['product_category'];
				$data 							= $row_pd01[0]['serial_no'];
				$model_no						= $row_pd01[0]['model_no'];
				$fetched_amount 				= $row_pd01[0]['amount'];

				$sql_pd01_4 		= "	SELECT  a.*
										FROM phone_check_api_data a 
										WHERE a.enabled = 1 
										AND a.imei_no = '" . $data . "'
										ORDER BY a.id DESC LIMIT 1";
				$result_pd01_4	= $db->query($conn, $sql_pd01_4);
				$count_pd01_4	= $db->counter($result_pd01_4);
				if ($count_pd01_4 > 0) {
					$row_pd01_4 = $db->fetch($result_pd01_4);

					include("db_phone_check_api_data.php");
					include("overall_grade_calculation.php");

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
											AND a.recevied_product_category = '" . $product_category_diagn . "'
											AND a.po_id = '" . $id . "' 
											AND (a.serial_no_barcode IS NULL OR a.serial_no_barcode = '')
											LIMIT 1 ";
						$result_pd01	= $db->query($conn, $sql_pd01);
						$count_pd01		= $db->counter($result_pd01);
						if ($count_pd01 > 0 && $product_id_not_in_po == '0') {
							$row_pd01		= $db->fetch($result_pd01);
							$receive_id_2 	= $row_pd01[0]['id'];
						} else {
							$sql = "INSERT INTO purchase_order_detail_receive(po_id, recevied_product_category, product_id, receive_type, sub_location_id, add_by_user_id, add_date, add_by, add_ip, add_timezone, added_from_module_id)
									VALUES('" . $id . "' , '" . $product_category_diagn . "' ,'" . $product_id_not_in_po . "' , 'CateogryReceived' , '" . $sub_location_id_fetched . "',  '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
							$db->query($conn, $sql);
							$receive_id_2 = mysqli_insert_id($conn);
						}
						$sql_c_up = "UPDATE  purchase_order_detail_receive SET 		
																				po_detail_id						= '" . $po_detail_id1 . "', 
																				serial_no_barcode					= '" . $data . "', 

																				phone_check_api_data				= '" . $jsonData2 . "',
																				model_name							= '" . $model_name . "',
																				make_name							= '" . $make_name . "',
																				model_no							= '" . $model_no . "',
																				carrier_name						= '" . $carrier_name . "',
																				color_name							= '" . $color_name . "',
																				battery								= '" . $battery . "',
																				body_grade	           	 			= '" . $body_grade . "',
																				lcd_grade							= '" . $lcd_grade . "',
																				digitizer_grade	        			= '" . $digitizer_grade . "',
																				ram									= '" . $ram . "',
																				storage								= '" . $memory . "',
																				defects_or_notes					= '" . $defectsCode . "',
																				overall_grade		    			= '" . $overall_grade . "', 
																				inventory_status					= '" . $inventory_status . "', 
																				sku_code							= '" . $sku_code . "',
																				price								= '" . $fetched_amount . "',

																				sub_location_id_after_diagnostic	= '" . $sub_location_id_fetched . "',
																				is_diagnost							= '1',
																				is_import_diagnostic_data			= '1',

																				diagnose_by_user					= '" . $_SESSION['username'] . "',
																				diagnose_by_user_id					= '" . $_SESSION['user_id'] . "',
																				diagnose_timezone					= '" . $timezone . "',
																				diagnose_date						= '" . $add_date . "',
																				diagnose_ip							= '" . $add_ip . "',

																				update_timezone		   	 			= '" . $timezone . "',
																				update_date			    			= '" . $add_date . "',
																				update_by_user_id	   	 			= '" . $_SESSION['user_id'] . "',
																				update_by			    			= '" . $_SESSION['username'] . "',
																				update_ip			    			= '" . $add_ip . "',
																				update_from_module_id				= '" . $module_id . "'
								WHERE id = '" . $receive_id_2 . "' ";
						$ok = $db->query($conn, $sql_c_up);
						if ($ok) {
							$sql_c_up = "UPDATE  purchase_order_detail_receive_diagnostic_fetch SET 	
													is_processed			= '1',
													update_timezone			= '" . $timezone . "',
													update_date				= '" . $add_date . "',
													update_by				= '" . $_SESSION['username'] . "',
													update_ip 				= '" . $add_ip . "',
													update_from_module_id	= '" . $module_id . "'
									WHERE id = '" . $diagnostic_fetch_id . "' ";
							$db->query($conn, $sql_c_up);

							update_po_detail_status($db, $conn, $po_detail_id1, $diagnost_status_dynamic);
							update_po_status($db, $conn, $id, $diagnost_status_dynamic);
							$disp_status_name = get_status_name($db, $conn, $diagnost_status_dynamic);
						}
						$msg6['msg_success'] = "Serial# has been assigned to product id.";
					}
				}
			} else {
				$error5['msg'] = "The record is already exist";
			}
		}
		//*/
	} else {
		$error6['msg'] = "Please check Error in form.";
	}
}
