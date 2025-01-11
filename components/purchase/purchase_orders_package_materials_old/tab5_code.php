<?php

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$sub_location_id_barcode	= 1737;
	$product_id_barcode 		= 1;
	$logistic_id_barcode 		= 1;
	$sub_location_id_manual		= 1737;
	$logistic_id 				= 1;
	$receiving_qties[5] 		= 15;
	$receiving_location[5] 		= 1737;
	$product_id_manual 			= 1;
	$logistic_id_manual			= 1;

	// echo "<br><br><br><br><br><br><br><br><br>";
}

if (isset($cmd5) && $cmd5 == 'delete' && isset($detail_id)) {
	$sql_c_up = "DELETE FROM  package_materials_order_detail_receive  WHERE id = '" . $detail_id . "' ";
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
		if (po_permisions("Pkg_Receive") == 0) {
			$error5['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			foreach ($receviedProductIds as $receviedProductId) {
				$sql5_pks1	= "	SELECT b.package_id, c.logistics_cost, b.order_price
								FROM package_materials_order_detail_receive a
								INNER JOIN package_materials_order_detail b ON b.id = a.po_detail_id
								INNER JOIN package_materials_orders c ON c.id = b.po_id
								WHERE a.id = '" . $receviedProductId . "' ";
				// echo "<br><br>" . $sql5_pks1;die;
				$result5_pks1	= $db->query($conn, $sql5_pks1);
				$count5_pks1	= $db->counter($result5_pks1);
				if ($count5_pks1 > 0) {

					$row_pd5_pks1		= $db->fetch($result5_pks1);
					$package_id			= $row_pd5_pks1[0]['package_id'];
					$order_price_upd	= $row_pd5_pks1[0]['order_price'];

					$per_item_logistics = 0;
					$sql_pd3 		= "	SELECT b.logistics_cost, sum(order_qty) as total_item_in_po
										FROM package_materials_order_detail a 
										INNER JOIN package_materials_orders b ON b.id = a.po_id
										WHERE 1 	= 1
										AND b.id 	= '" . $id . "' ";
					$result_pd3	= $db->query($conn, $sql_pd3);
					$count_pd3	= $db->counter($result_pd3);
					if ($count_pd3 > 0) {
						$row_pd3 				= $db->fetch($result_pd3);
						$logistics_cost_upd		= $row_pd3[0]['logistics_cost'];
						$total_item_in_po		= $row_pd3[0]['total_item_in_po'];

						if ($logistics_cost_upd > 0 && $total_item_in_po > 0) {
							$per_item_logistics		= ($logistics_cost_upd / $total_item_in_po);
						}
					}

					$item_price = ($order_price_upd + $per_item_logistics);
					// echo "<br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: " . $item_price;
					$sql_c_up 	= "UPDATE packages SET 
														stock_in_hand			= (stock_in_hand-1),
														avg_price				= round(2*avg_price-" . $item_price . ", 2),
														
														update_by				= '" . $_SESSION['username'] . "',
														update_by_user_id		= '" . $_SESSION['user_id'] . "',
														update_timezone			= '" . $timezone . "',
														update_date				= '" . $add_date . "',
														update_ip				= '" . $add_ip . "',
														added_from_module_id	= '" . $module_id . "'
									WHERE id = '" . $package_id . "' ";
					//echo "<br>" . $sql_c_up;
					$db->query($conn, $sql_c_up);

					$sql_c_up = "DELETE FROM  package_materials_order_detail_receive  WHERE id = '" . $receviedProductId . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$k++;
					}
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
		if (po_permisions("Pkg_Receive") == 0) {
			$error5['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;

			$sql_ee1 = "SELECT a.* FROM package_materials_order_detail_receive a 
						WHERE a.duplication_check_token = '" . $duplication_check_token . "' 
						AND a.logistic_id 				= '" . $logistic_id . "'";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				foreach ($receiving_qties as $key => $receiving_qty) {
					if ($receiving_qty > 0) {
						for ($m = 0; $m < $receiving_qty; $m++) {
							$receiving_location_add = $receiving_location[$key];

							$product_uniqueid_main1 = "";
							$sql_pd3 		= "	SELECT a.* 
												FROM package_materials_order_detail a 
												WHERE 1 	= 1
												AND a.id 	= '" . $key . "' ";
							$result_pd3	= $db->query($conn, $sql_pd3);
							$count_pd3	= $db->counter($result_pd3);
							if ($count_pd3 > 0) {
								$row_pd3 				= $db->fetch($result_pd3);
								$order_price			= $row_pd3[0]['order_price'];
								$inv_product_id			= $row_pd3[0]['package_id'];
							}
							$sql6 = "INSERT INTO package_materials_order_detail_receive(logistic_id, po_detail_id, price, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone)
									VALUES('" . $logistic_id . "', '" . $key . "',   '" . $order_price . "', '" . $_SESSION['user_id'] . "', '" . $receiving_location_add . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
							$ok = $db->query($conn, $sql6);
							if ($ok) {

								$receive_id_pk = mysqli_insert_id($conn);
								$k++;
								$sql_c_up = "UPDATE  package_materials_order_detail_logistics SET	logistics_status		= '" . $arrival_status_dynamic . "',
																									edit_lock				= '1',
																									update_by				= '" . $_SESSION['username'] . "',
																									update_by_user_id		= '" . $_SESSION['user_id'] . "',
																									update_timezone			= '" . $timezone . "',
																									update_date				= '" . $add_date . "',
																									update_ip				= '" . $add_ip . "'
											WHERE id = '" . $logistic_id . "' ";
								$ok = $db->query($conn, $sql_c_up);
								update_po_detail_status_package_materials($db, $conn, $key, $receive_status_dynamic);

								/////////////////////////// Create Stock  START /////////////////////////////
								$per_item_logistics = 0;
								$sql_pd3 		= "	SELECT b.logistics_cost, sum(order_qty) as total_item_in_po
													FROM package_materials_order_detail a 
													INNER JOIN package_materials_orders b ON b.id = a.po_id
													WHERE 1 	= 1
													AND b.id 	= '" . $id . "' ";
								$result_pd3	= $db->query($conn, $sql_pd3);
								$count_pd3	= $db->counter($result_pd3);
								if ($count_pd3 > 0) {
									$row_pd3 				= $db->fetch($result_pd3);
									$logistics_cost_upd		= $row_pd3[0]['logistics_cost'];
									$total_item_in_po		= $row_pd3[0]['total_item_in_po'];

									if ($logistics_cost_upd > 0 && $total_item_in_po > 0) {
										$per_item_logistics		= ($logistics_cost_upd / $total_item_in_po);
									}
								}

								$item_price = ($order_price + $per_item_logistics);
								// echo "<br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: " . $item_price;
								$sql_c_up 	= "UPDATE packages SET 
																	stock_in_hand			= (stock_in_hand+1),
																	avg_price				= IF(avg_price>0, round(((avg_price+" . $item_price . ")/2), 2), round(" . $item_price . ", 2) ),
																	
																	update_by				= '" . $_SESSION['username'] . "',
																	update_by_user_id		= '" . $_SESSION['user_id'] . "',
																	update_timezone			= '" . $timezone . "',
																	update_date				= '" . $add_date . "',
																	update_ip				= '" . $add_ip . "',
																	added_from_module_id	= '" . $module_id . "'
												WHERE id = '" . $inv_product_id . "' ";
								//echo "<br>" . $sql_c_up;
								$db->query($conn, $sql_c_up);
								$k++;
								/////////////////////////// Create Stock  END /////////////////////////////
							}
						}
					}
				}
				if ($k > 0) {
					update_po_status_package_materials($db, $conn, $id, $receive_status_dynamic);

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
