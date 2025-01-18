<?php

if ($_SERVER['HTTP_HOST'] == 'localhost' && (!isset($cmd2) || (isset($cmd2) && $cmd2 != 'edit'))) {
	$shipment_courier_id	= 1;
	$shipment_tracking_no	= date('YmdHis');
	$shipment_sent_date		= date('d/m/Y');
	$expected_delivery_date	= date('d/m/Y');
} 
if((isset($cmd3) && $cmd3 == 'delete') && (isset($detail_id) && $detail_id > 0)){
 	$sql_ee1 = "DELETE FROM sales_order_shipment_detail 
				WHERE id = '" . $detail_id . "' ";
	$ok = $db->query($conn, $sql_ee1);
	if ($ok) {
		$sql_c_up = "UPDATE  sales_order_detail_packing
												SET is_shipped				= '0',
													update_timezone			= '" . $timezone . "',
													update_date				= '" . $add_date . "',
													update_by				= '" . $_SESSION['username'] . "',
													update_ip				= '" . $add_ip . "',
													update_from_module_id	= '" . $module_id . "'
						WHERE id = '" . $detail_id2 . "' 
						AND sale_order_id = '".$id."' ";
		$db->query($conn, $sql_c_up);
		
		$sql6 = " 	UPDATE product_stock a
					INNER JOIN sales_order_detail_packing b ON b.product_stock_id = a.id
						SET a.p_total_stock = '1' 
				  	WHERE b.id = '" . $detail_id2 . "'
					AND b.sale_order_id = '".$id."' ";
		$db->query($conn, $sql6);

		$sql_pd01 		= "	SELECT a.* 
							FROM sales_order_shipments b  
							INNER JOIN  sales_order_shipment_detail a ON b.id = a.shipment_id 
							WHERE b.sales_order_id = '".$id."' ";
		$result_pd01	= $db->query($conn, $sql_pd01);
		$count_pd01		= $db->counter($result_pd01);
		if ($count_pd01 == 0) {
			update_so_detail_status($db, $conn, $id, $module_id, $packing_status_dynamic);
			update_so_status($db, $conn, $id, $module_id, $packing_status_dynamic);
		} 
		$msg2['msg_success'] = "Shippment has been removed.";
	}
}
if (isset($_POST['is_Submit_tab2']) && $_POST['is_Submit_tab2'] == 'Y') {
	extract($_POST);

	if (!isset($shipment_courier_id) || (isset($shipment_courier_id)  && ($shipment_courier_id == "0" || $shipment_courier_id == ""))) {
		$error2['shipment_courier_id'] = "Required";
	}
	if (isset($shipment_tracking_no) && $shipment_tracking_no == "") {
		$error2['shipment_tracking_no'] = "Required";
	}  
	if (isset($shipment_sent_date) && $shipment_sent_date == "") {
		$shipment_sent_date1 = NULL;
		$error2['shipment_sent_date'] = "Required";
	} else {
		$shipment_sent_date1 = convert_date_mysql_slash($shipment_sent_date);
	}
	if (isset($expected_delivery_date) && $expected_delivery_date == "") {
		$expected_delivery_date1 = NULL;
		$error2['expected_delivery_date'] = "Required";
	} else {
		$expected_delivery_date1 = convert_date_mysql_slash($expected_delivery_date);
	}
	if (!isset($id) || (isset($id)  && ($id == "0" || $id == ""))) {
		$error2['msg'] = "Please add master record first";
	}
	if (isset($courier_name) && $courier_name == "") {
		$error2['courier_name'] = "Required";
	}
	if ((!isset($packed_pallet_no) && !isset($packed_box_no)) || ($packed_box_no == "0" && $packed_pallet_no == "0" ) || ($packed_box_no == "" && $packed_pallet_no == "" )) {
		$error2['msg'] = "Either Box or Pallet is required. Please select at least one.";
	}

	if (empty($error2)) {
		if (access("add_perm") == 0) {
			$error2['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			$sql_dup	= " SELECT a.* FROM sales_order_shipments a 
							WHERE  a.shipment_tracking_no = '" . $shipment_tracking_no . "' "; //echo $sql_dup;
			$result_dup	= $db->query($conn, $sql_dup);
			$count_dup	= $db->counter($result_dup);
			if ($count_dup > 0) {
				$row_dup 		= $db->fetch($result_dup);
				$shipment_id	= $row_dup[0]['id'];
			}
			else{
				$sql6 = "INSERT INTO sales_order_shipments(sales_order_id, shipment_sent_date, shipment_courier_id, shipment_tracking_no, expected_delivery_date, shipment_status, add_date, add_by, add_ip, add_timezone,added_from_module_id)
						VALUES('" . $id . "', '" . $shipment_sent_date1 . "', '" . $shipment_courier_id . "', '"  . $shipment_tracking_no  . "', '" . $expected_delivery_date1  . "',  '11',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "','".$module_id."')";
				$ok = $db->query($conn, $sql6);
				if ($ok) {
					$shipment_id	= mysqli_insert_id($conn);
					$shipment_no	= "SH" . $shipment_id;

					$sql6	= " UPDATE sales_order_shipments SET shipment_no = '" . $shipment_no . "' WHERE id = '" . $shipment_id . "' ";
					$db->query($conn, $sql6); 

					if (isset($error2['msg'])) unset($error2['msg']);
				} else {
					$error2['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			}

			if(isset($packed_pallet_no) && $packed_pallet_no>0){
				$sql_dup	= "SELECT * 
								FROM  sales_order_detail_packing 
								WHERE sale_order_id = '". $id ."' 
								AND pallet_no = '". $packed_pallet_no ."'
								AND enabled = 1
								AND is_shipped = 0 "; //echo $sql_dup;
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup > 0) {
					$row_dup = $db->fetch($result_dup);
					foreach($row_dup as $data){
						$sql_dup	= " SELECT a.* FROM sales_order_shipment_detail a WHERE  a.shipment_id = '" . $shipment_id . "'  AND a.packed_id = '" . $data['id'] . "' "; //echo $sql_dup;
						$result_dup	= $db->query($conn, $sql_dup);
						$count_dup	= $db->counter($result_dup);
						if ($count_dup == 0) {
							$sql6 = "INSERT INTO sales_order_shipment_detail(shipment_id, packed_id, add_date, add_by, add_ip, add_timezone,added_from_module_id)
										VALUES('" . $shipment_id . "', '" . $data['id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "','".$module_id."')";
							$db->query($conn, $sql6);
							
							$sql6 = " UPDATE sales_order_detail_packing SET is_shipped = '1' WHERE id = '" . $data['id'] . "' ";
							$db->query($conn, $sql6);
							
							$sql6 = " UPDATE product_stock SET p_total_stock = '0' WHERE id = '" . $data['product_stock_id'] . "' ";
							$db->query($conn, $sql6);

							$k++;
							update_so_detail_status($db, $conn, $id, $module_id, 0, $shipped_status_dynamic);
						}
					}
				}
				if($k>0){
					update_so_status($db, $conn, $id, $module_id, $shipped_status_dynamic);
					$msg2['msg_success'] = "Packing has been added in shipment successfully.";
				}
				else{
					$error2['msg'] = "No Packing added.";
				}
			}
			else if(isset($packed_box_no) && $packed_box_no>0){
				$sql_dup	= "SELECT * 
								FROM  sales_order_detail_packing 
								WHERE sale_order_id = '". $id ."' 
								AND box_no = '". $packed_box_no ."'
								AND enabled = 1
								AND is_shipped = 0 "; //echo $sql_dup;
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup > 0) {
					$row_dup 		= $db->fetch($result_dup);
					foreach($row_dup as $data){
						$sql_dup	= " SELECT a.* FROM sales_order_shipment_detail a WHERE  a.shipment_id = '" . $shipment_id . "'  AND a.packed_id = '" . $data['id'] . "' "; //echo $sql_dup;
						$result_dup	= $db->query($conn, $sql_dup);
						$count_dup	= $db->counter($result_dup);
						if ($count_dup == 0) {
							$sql6 = "INSERT INTO sales_order_shipment_detail(shipment_id, packed_id, add_date, add_by, add_ip, add_timezone,added_from_module_id)
										VALUES('" . $shipment_id . "', '" . $data['id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "','".$module_id."')";
							$db->query($conn, $sql6);
							
							$sql6 = " UPDATE sales_order_detail_packing SET is_shipped = '1' WHERE id = '" . $data['id'] . "' ";
							$db->query($conn, $sql6);
							
							$sql6 = " UPDATE product_stock SET p_total_stock = '0' WHERE id = '" . $data['product_stock_id'] . "' ";
							$db->query($conn, $sql6);
							
							$k++;
							update_so_detail_status($db, $conn, $id, $module_id, 0, $shipped_status_dynamic);
						}
					}
				}
				if($k>0){
					update_so_status($db, $conn, $id, $module_id, $shipped_status_dynamic);
					$msg2['msg_success'] = "Packing has been added in shipment successfully.";
				}
				else{
					$error2['msg'] = "No Packing added.";
				}
			}  
		}
	} 
}
