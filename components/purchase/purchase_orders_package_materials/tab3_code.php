<?php

if ($_SERVER['HTTP_HOST'] == HTTP_HOST_IP) {
	$sub_location_id_barcode	= 1737;
	$product_id_barcode 		= 1;
	$logistic_id_barcode 		= 1;
	$sub_location_id_manual		= 1737;
	$receiving_location 		= 1737;
	$product_id_manual 			= 1;
	$logistic_id_manual			= 1;
	// echo "<br><br><br><br><br><br><br><br><br>";
}

if (isset($logistic_id) && $logistic_id > 0) {
	$sql_ee1 = "SELECT a.id 
				FROM package_materials_order_detail a 
				INNER JOIN package_materials_order_detail_logistics b ON b.po_detail_id = a.id
				WHERE a.po_id = '" . $id . "'
				AND b.id = '" . $logistic_id . "' ";
	$result_ee1 	= $db->query($conn, $sql_ee1);
	$counter_ee1	= $db->counter($result_ee1);
	if ($counter_ee1 > 0) {
		$row_ee1111					= $db->fetch($result_ee1);
		$package_order_detail_id	= $row_ee1111[0]['id'];
	}
}

if (isset($_POST['is_Submit_tab3_4_2']) && $_POST['is_Submit_tab3_4_2'] == 'Y') {
	extract($_POST);
	if (!isset($receviedProductIds) || (isset($receviedProductIds) && sizeof($receviedProductIds) == 0)) {
		$error3['msg'] = "Select atleast one record to delete";
	}
	if (empty($error3)) {
		if (po_permisions("Pkg_Receive") == 0) {
			$error3['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			foreach ($receviedProductIds as $receviedProductId_1) {
				$record_array = explode("_", $receviedProductId_1);

				$receviedProductId 	= $record_array[0];
				$record_location_id = $record_array[1];

				$sql5_pks1	= "	SELECT b.package_id, c.logistics_cost, b.order_price, b.po_id
								FROM package_materials_order_detail b 
								INNER JOIN package_materials_orders c ON c.id = b.po_id
								WHERE b.id = '" . $receviedProductId . "' ";
				// echo "<br><br>" . $sql5_pks1;die;
				$result5_pks1	= $db->query($conn, $sql5_pks1);
				$count5_pks1	= $db->counter($result5_pks1);
				if ($count5_pks1 > 0) {

					$row_pd5_pks1		= $db->fetch($result5_pks1);
					$package_id			= $row_pd5_pks1[0]['package_id'];
					$order_price_upd	= $row_pd5_pks1[0]['order_price'];
					$po_id1	            = $row_pd5_pks1[0]['po_id'];

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
					$sql_c_up 	= "UPDATE packages SET 
														stock_in_hand			= (stock_in_hand-1),
														avg_price				= round(2*avg_price-" . $item_price . ", 2),
														
														update_by				= '" . $_SESSION['username'] . "',
														update_by_user_id		= '" . $_SESSION['user_id'] . "',
														update_timezone			= '" . $timezone . "',
														update_date				= '" . $add_date . "',
														update_ip				= '" . $add_ip . "',
														update_from_module_id	= '" . $module_id . "'
									WHERE id = '" . $package_id . "' ";
					//echo "<br>" . $sql_c_up;
					$db->query($conn, $sql_c_up);

					$sql_c_up = "DELETE FROM  package_materials_order_detail_receive
								 WHERE po_detail_id 	= '" . $receviedProductId . "' 
								 AND sub_location_id 	= '" . $record_location_id . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$sql5_pks1	= " SELECT a.id
                                        FROM package_materials_order_detail_receive a
                                        INNER JOIN package_materials_order_detail b ON b.id = a.po_detail_id
                                        INNER JOIN package_materials_orders c ON c.id = b.po_id
                                        WHERE b.po_id = '" . $po_id1 . "' ";
						// echo "<br><br>" . $sql5_pks1;die;
						$result5_pks1	= $db->query($conn, $sql5_pks1);
						$count5_pks1	= $db->counter($result5_pks1);
						if ($count5_pks1 == 0) {

							update_po_status_package_materials($db, $conn, $id, $logistic_status_dynamic);
							update_po_detail_status_package_materials($db, $conn, $key, $logistic_status_dynamic);
							$disp_status_name 	= get_status_name($db, $conn, $logistic_status_dynamic);

							$sql_c_up = "UPDATE  package_materials_order_detail_logistics SET 	edit_lock	            = '0',
																								logistics_status		= '10',
                                                                                
                                                                                                update_date				= '" . $add_date . "',
                                                                                                update_by				= '" . $_SESSION['username'] . "',
                                                                                                update_by_user_id		= '" . $_SESSION['user_id'] . "',
                                                                                                update_ip				= '" . $add_ip . "',
                                                                                                update_timezone			= '" . $timezone . "',
                                                                                                update_from_module_id	= '" . $module_id . "'
                                WHERE po_detail_id = '" . $receviedProductId . "' ";
							$db->query($conn, $sql_c_up);
						}
						$k++;
					}
				}
			}
			if ($k > 0) {
				if ($k == 1) {
					$msg3['msg_success'] = $k . " record has been deleted successfully.";
				} else {
					$msg3['msg_success'] = $k . " records have been deleted successfully.";
				}
			}
		}
	}
}
if (isset($_POST['is_Submit_tab3']) && $_POST['is_Submit_tab3'] == 'Y') {
	extract($_POST);

	$field_name = "receiving_location";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3[$field_name] = "Required";
	}
	$field_name = "package_order_detail_id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3[$field_name] = "Required";
	}

	if (!isset($id) || (isset($id)  && ($id == "0" || $id == ""))) {
		$error3['msg'] = "Please add master record first";
	}

	if (empty($error3)) {
		if (po_permisions("Pkg_Receive") == 0) {
			$error3['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;

			$sql_ee1 = "SELECT a.* FROM package_materials_order_detail_receive a  WHERE a.duplication_check_token = '" . $duplication_check_token . "' ";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {

				$sql_pd3	= "	SELECT a.*, b.id as logistic_id  
								FROM package_materials_order_detail a  
								INNER JOIN package_materials_order_detail_logistics b ON b.po_detail_id = a.id
								WHERE 1 	= 1 
								AND a.id 	= '" . $package_order_detail_id . "' ";
				$result_pd3	= $db->query($conn, $sql_pd3);
				$count_pd3	= $db->counter($result_pd3);
				if ($count_pd3 > 0) {
					$row_pd3			= $db->fetch($result_pd3);
					$order_case_pack	= $row_pd3[0]['order_case_pack'];
					$inv_product_id		= $row_pd3[0]['package_id'];
					$order_price		= $row_pd3[0]['order_price'];
					$logistic_id		= $row_pd3[0]['logistic_id'];

					for ($m = 0; $m < $order_case_pack; $m++) {
						$product_uniqueid_main1 = "";
						$sql6 = "INSERT INTO package_materials_order_detail_receive(logistic_id, po_detail_id, price, add_by_user_id, sub_location_id, duplication_check_token, add_date,  add_by, add_ip, add_timezone, added_from_module_id)
								 VALUES('" . $logistic_id . "', '" . $package_order_detail_id . "',   '" . $order_price . "', '" . $_SESSION['user_id'] . "', '" . $receiving_location . "', '" . $duplication_check_token . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
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
																								update_ip				= '" . $add_ip . "',
																								update_from_module_id	= '" . $module_id . "'
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
							$sql_c_up 	= "UPDATE packages SET 
															stock_in_hand			= (stock_in_hand+1),
															avg_price				= IF(avg_price>0, round(((avg_price+" . $item_price . ")/2), 2), round(" . $item_price . ", 2) ),
															
															update_by				= '" . $_SESSION['username'] . "',
															update_by_user_id		= '" . $_SESSION['user_id'] . "',
															update_timezone			= '" . $timezone . "',
															update_date				= '" . $add_date . "',
															update_ip				= '" . $add_ip . "',
															update_from_module_id	= '" . $module_id . "'
										WHERE id = '" . $inv_product_id . "' "; //echo "<br>" . $sql_c_up;
							$db->query($conn, $sql_c_up);
							$k++;
							/////////////////////////// Create Stock  END /////////////////////////////
						}
					}
				}
				if ($k > 0) {
					update_po_status_package_materials($db, $conn, $id, $receive_status_dynamic);
					$disp_status_name 	= get_status_name($db, $conn, $receive_status_dynamic);
					$msg3['msg_success'] = "Receiving has been processed successfully.";
					unset($receiving_qties);
					unset($receiving_location);
					$package_order_detail_id = $receiving_location = "";
				}
			} else {
				$error3['msg'] = "The record is already exist";
			}
		}
	} else {
		$error3['msg'] = "Please check Error in form.";
	}
}
