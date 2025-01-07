<?php


if (isset($_POST['is_Submit_tab2_1']) && $_POST['is_Submit_tab2_1'] == 'Y') {
	extract($_POST);
	if (!isset($repaire_ids) || (isset($repaire_ids) && sizeof($repaire_ids) == 0)) {
		$error2['msg'] = "Select atleast one record to delete";
	}
	if (empty($error2)) {
		if (po_permisions("Repair Process") == 0) {
			$error2['msg'] = "You do not have add permissions.";
		} else {

			$k = 0;
			foreach ($repaire_ids as $repaire_id) {
				$sql_pd1	= "	SELECT a.*, b.sub_location_id_after_diagnostic, d.product_uniqueid
								FROM purchase_order_detail_receive_rma a 
								INNER JOIN purchase_order_detail_receive b ON b.id = a.receive_id
								INNER JOIN purchase_order_detail c ON c.id = b.po_detail_id
								INNER JOIN products d ON d.id = c.product_id
 								WHERE a.id = '" . $repaire_id . "'
								AND a.edit_lock = 0 ";
				$result_pd1	= $db->query($conn, $sql_pd1);
				$count_pd1	= $db->counter($result_pd1);
				if ($count_pd1 > 0) {
					$row_pd2						= $db->fetch($result_pd1);
					$inv_product_uniqueid			= $row_pd2[0]['product_uniqueid'];
					$device_repaire_labor_cost 		= device_repaire_labor_cost($db, $conn, $repaire_id);
					$inv_total_repair_cost			= round(($row_pd2[0]['total_repair_cost'] + $device_repaire_labor_cost), 2);
					$inv_repaire_status_id			= $row_pd2[0]['repaire_status_id'];
					$inv_grade_after_repaire		= $row_pd2[0]['grade_after_repaire'];
					$inv_parts_stock_ids			= $row_pd2[0]['parts_stock_ids'];
					$inv_location_id				= $row_pd2[0]['sub_location_id_after_diagnostic'];
					$inv_receive_id					= $row_pd2[0]['receive_id'];
					$part_ids						= explode(",", $row_pd2[0]['parts_stock_ids']);

					$sql_c_up = "UPDATE  product_stock 
												SET 
													p_inventory_status		= '" . $inv_repaire_status_id . "',
													stock_grade				= '" . $inv_grade_after_repaire . "',
													sub_location			= '" . $inv_location_id . "',
													price					= ROUND( (price+" . $inv_total_repair_cost . "), 2),
													stock_product_uniqueid	= '" . $inv_product_uniqueid . "-" . $inv_grade_after_repaire . "',
													
													update_timezone			= '" . $timezone . "',
													update_date				= '" . $add_date . "',
													update_by				= '" . $_SESSION['username'] . "',
													update_ip				= '" . $add_ip . "',
													update_from_module_id	= '" . $module_id . "'
								WHERE receive_id = '" . $inv_receive_id . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						foreach ($part_ids as $data_part) {
							$sql_c_up = "UPDATE  package_stock 
													SET  
														total_stock				= (total_stock-1),
														
														update_timezone			= '" . $timezone . "',
														update_date				= '" . $add_date . "',
														update_by				= '" . $_SESSION['username'] . "',
														update_ip				= '" . $add_ip . "',
														update_from_module_id	= '" . $module_id . "'
										WHERE id = '" . $data_part . "' ";
							$ok = $db->query($conn, $sql_c_up);
						}

						$sql_c_up = "UPDATE  purchase_order_detail_receive_rma
													SET 	
														edit_lock				= '1',
														is_repaired 			= 1,

														update_timezone			= '" . $timezone . "',
														update_date				= '" . $add_date . "',
														update_by				= '" . $_SESSION['username'] . "',
														update_ip				= '" . $add_ip . "',
														update_from_module_id	= '" . $module_id . "'
									WHERE id = '" . $repaire_id . "' ";
						$db->query($conn, $sql_c_up);
						$k++;
					} else {
						$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				}
			}

			if ($k > 0) {
				if ($k == 1) {
					$msg2['msg_success'] = $k . " repair has been processed successfully.";
				} else {
					$msg2['msg_success'] = $k . " repairs have been processed successfully.";
				}
			}
		}
	} else {
		if (!isset($error2)) {
			$error2['msg'] = "Please check the error in form.";
		}
	}
}
