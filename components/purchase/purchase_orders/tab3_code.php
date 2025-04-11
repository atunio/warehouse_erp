<?php

if ($_SERVER['HTTP_HOST'] == HTTP_HOST_IP && !isset($cmd3_1)) {
	$logistics_id 		= 1;
	$sub_location_id	= 2299;
	$no_of_box_arried	= 1;
}

if (isset($cmd3_1) && $cmd3_1 == 'edit' && isset($detail_id)) {
	$sql_ee1 = "SELECT b.* FROM purchase_order_detail_logistics_receiving b  WHERE b.id = '" . $detail_id . "'";
	$result_ee1 	= $db->query($conn, $sql_ee1);
	$counter_ee1	= $db->counter($result_ee1);
	if ($counter_ee1 > 0) {
		$row_ee1 							= $db->fetch($result_ee1);
		$update_logistics_id				= $row_ee1[0]['logistics_id'];
		$update_sub_location_id				= $row_ee1[0]['sub_location_id'];
		$update_arrived_date				= str_replace("-", "/", convert_date_display($row_ee1[0]['arrived_date']));
		$update_no_of_box_arried			= $row_ee1[0]['no_of_box_arried'];
	} else {
		$error3['msg'] = "No record found";
	}
}
if (isset($cmd3_1) && $cmd3_1 == 'delete' && isset($detail_id)) {
	if (po_permisions("Logistics") == 0) {
		$error3['msg'] = "You do not have add permissions.";
	} else {
		$sql_ee1 = " SELECT b.* FROM purchase_order_detail_logistics_receiving b WHERE b.po_id = '" . $id . "'";
		// echo $sql_ee1;
		$result_ee1 	= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 <= 1) {

			$sql_c_up = "UPDATE  purchase_order_detail_logistics a
					INNER JOIN purchase_order_detail_logistics_receiving b ON b.logistics_id = a.id
										SET a.logistics_status		= '10',
											a.edit_lock 			= 0,
											a.update_timezone		= '" . $timezone . "',
											a.update_date			= '" . $add_date . "',
											a.update_by				= '" . $_SESSION['username'] . "',
											a.update_ip				= '" . $add_ip . "'
				WHERE b.id = '" . $detail_id . "' ";
			$db->query($conn, $sql_c_up);

			$sql_c_up = "UPDATE  purchase_order_detail
											SET 	
												order_product_status	= '" . $logistic_status_dynamic . "',

												update_timezone			= '" . $timezone . "',
												update_date				= '" . $add_date . "',
												update_by				= '" . $_SESSION['username'] . "',
												update_ip				= '" . $add_ip . "'
					WHERE po_id = '" . $id . "' ";
			$db->query($conn, $sql_c_up);

			$sql_c_up = "UPDATE  purchase_orders
											SET 	
												order_status		= '" . $logistic_status_dynamic . "',

												update_timezone		= '" . $timezone . "',
												update_date			= '" . $add_date . "',
												update_by			= '" . $_SESSION['username'] . "',
												update_ip			= '" . $add_ip . "'
					WHERE id = '" . $id . "' ";
			$db->query($conn, $sql_c_up);

			$table		= "inventory_status";
			$columns	= array("status_name");
			$get_col_from_table = get_col_from_table($db, $conn, $selected_db_name, $table, $logistic_status_dynamic, $columns);
			foreach ($get_col_from_table as $array_key1 => $array_data1) {
				${$array_key1} = $array_data1;
			}
		}
		$sql_ee1 = " DELETE FROM purchase_order_detail_logistics_receiving WHERE id = '" . $detail_id . "'";
		$ok = $db->query($conn, $sql_ee1);
		if ($ok) {
			$msg3['msg_success'] = "Record has been added successfully.";
		} else {
			$error3['msg'] = "There is error.";
		}
		unset($cmd3_1);
	}
}
if (isset($_POST['is_Submit_tab3']) && $_POST['is_Submit_tab3'] == 'Y') {
	extract($_POST);
	foreach ($_POST as $key => $value) {
		if (!is_array($value)) {
			$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
			$$key = $data[$key];
		}
	}
	$field_name = "no_of_box_arried";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3[$field_name] = "Required";
	}
	$arrived_date1 = "0000-00-00";
	$field_name = "arrived_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error3[$field_name] = "Required";
	} else {
		$arrived_date1 = convert_date_mysql_slash(${$field_name});
	}
	$field_name = "sub_location_id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3[$field_name] = "Required";
	}
	$field_name = "logistics_id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3[$field_name] = "Required";
	}
	$field_name = "id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3['msg'] = "Please add master record first";
	}
	if (empty($error3)) {
		if (po_permisions("Arrival") == 0) {
			$error3['msg'] = "You do not have add permissions.";
		} else {
			$file_landing = "";
			$file_1 = "bill_of_landing";
			if (is_array($_FILES) && isset($_FILES[$file_1]["name"]) && $_FILES[$file_1]["name"] != "") {
				$file_uniq_id 		= $_SESSION['user_id'] . "_" . time() . "_" . uniqid();
				$temp 				= explode(".", $_FILES[$file_1]["name"]);
				$extension 			= end($temp);
				$file_type 			= $_FILES[$file_1]['type'];
				$file_name 			= $_FILES[$file_1]['name'];
				$file_tmp  			= $_FILES[$file_1]['tmp_name'];
				$mimeTypes  		= array("application/pdf", "image/JPEG", "image/jpeg", "image/JPG", "image/jpg", "image/PNG", "image/png", "image/gif", "image/GIF"); //add the formats you want to upload
				$mime          		= mime_content_type($file_tmp);
				if ($file_name != "") {
					if (!in_array($file_type, $mimeTypes) && !in_array($mime, $mimeTypes)) {
						$error3['msg'] = "Invalid file format, Please upload jpeg. png or pdf file";
					} else {
						$sourcePath		= $file_tmp;
						$file_landing	= $file_uniq_id . "." . $extension;
						$targetPath		= "app-assets/bills_of_landing/" . $file_landing;
						move_uploaded_file($sourcePath, $targetPath);
					}
				}
			} else {
				$file_landing = $old_bill_of_landing;
			}

			$sql6 = "INSERT INTO purchase_order_detail_logistics_receiving(po_id, logistics_id, duplication_check_token, bill_of_landing, sub_location_id, arrived_date, no_of_box_arried, add_date, add_by, add_ip, add_timezone, added_from_module_id)
						VALUES('" . $id . "', '" . $logistics_id . "', '" . $csrf_token . "', '" . $file_landing . "', '" . $sub_location_id . "', '"  . $arrived_date1  . "', '" . $no_of_box_arried  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
			$ok = $db->query($conn, $sql6);
			if ($ok) {

				$sql_c_up = "UPDATE  purchase_order_detail_logistics SET edit_lock 				= 1,
																		logistics_status		= '" . $arrival_status_dynamic . "',
																		update_timezone			= '" . $timezone . "',
																		update_date				= '" . $add_date . "',
																		update_by				= '" . $_SESSION['username'] . "',
																		update_ip				= '" . $add_ip . "',
																		update_from_module_id	= '" . $module_id . "'
									WHERE id = '" . $logistics_id . "' ";

				$db->query($conn, $sql_c_up);

				update_po_detail_status2($db, $conn, $id, $arrival_status_dynamic);
				update_po_status($db, $conn, $id, $arrival_status_dynamic);
				$disp_status_name = get_status_name($db, $conn, $arrival_status_dynamic);
				if (isset($error3['msg'])) unset($error3['msg']);

				if (isset($msg3['msg_success'])) {
					$msg3['msg_success'] .= "<br>Arrival info has been added successfully.";
				} else {
					$msg3['msg_success'] = "Arrival info has been added successfully.";
				}
				if ($_SERVER['HTTP_HOST'] != HTTP_HOST_IP) {
					$logistics_id = $no_of_box_arried =  $sub_location_id = "";
				}
			} else {
				$error3['msg'] = "There is Error, Please check it again OR contact Support Team.";
			}
		}
	} else {
		$error3['msg'] = "Please check Error in form.";
	}
}

if (isset($_POST['is_Submit_tab3_1']) && $_POST['is_Submit_tab3_1'] == 'Y') {
	extract($_POST);
	foreach ($_POST as $key => $value) {
		if (!is_array($value)) {
			$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
			$$key = $data[$key];
		}
	}
	$field_name = "update_no_of_box_arried";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3[$field_name] = "Required";
	}
	$arrived_date1 = "0000-00-00";
	$field_name = "update_arrived_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error3[$field_name] = "Required";
	} else {
		$arrived_date1 = convert_date_mysql_slash(${$field_name});
	}
	$field_name = "update_sub_location_id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3[$field_name] = "Required";
	}
	$field_name = "update_logistics_id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3[$field_name] = "Required";
	}
	$field_name = "id";
	if (!isset(${$field_name}) || (isset(${$field_name})  && (${$field_name} == "0" || ${$field_name} == ""))) {
		$error3['msg'] = "Please add master record first";
	}
	if (empty($error3)) {
		if (po_permisions("Arrival") == 0) {
			$error3['msg'] = "You do not have add permissions.";
		} else {
			$file_landing = "";
			$file_1 = "update_bill_of_landing";
			if (is_array($_FILES) && isset($_FILES[$file_1]["name"]) && $_FILES[$file_1]["name"] != "") {
				$file_uniq_id 		= $_SESSION['user_id'] . "_" . time() . "_" . uniqid();
				$temp 				= explode(".", $_FILES[$file_1]["name"]);
				$extension 			= end($temp);
				$file_type 			= $_FILES[$file_1]['type'];
				$file_name 			= $_FILES[$file_1]['name'];
				$file_tmp  			= $_FILES[$file_1]['tmp_name'];
				$mimeTypes  		= array("application/pdf", "image/JPEG", "image/jpeg", "image/JPG", "image/jpg", "image/PNG", "image/png", "image/gif", "image/GIF"); //add the formats you want to upload
				$mime          		= mime_content_type($file_tmp);
				if ($file_name != "") {
					if (!in_array($file_type, $mimeTypes) && !in_array($mime, $mimeTypes)) {
						$error3['msg'] = "Invalid file format, Please upload jpeg. png or pdf file";
					} else {
						$sourcePath		= $file_tmp;
						$file_landing	= $file_uniq_id . "." . $extension;
						$targetPath		= "app-assets/bills_of_landing/" . $file_landing;
						move_uploaded_file($sourcePath, $targetPath);
					}
				}
			} else {
				//$file_landing = $old_bill_of_landing;
			}
			$sql_c_up = "UPDATE  purchase_order_detail_logistics_receiving SET 
																				logistics_id 			= '" . $update_logistics_id . "',
																				bill_of_landing 		= '" . $file_landing . "',
																				sub_location_id 		= '" . $update_sub_location_id . "',
																				arrived_date 			= '" . $arrived_date1 . "',
																				no_of_box_arried		= '" . $update_no_of_box_arried . "',
																				
																				update_timezone			= '" . $timezone . "',
																				update_date				= '" . $add_date . "',
																				update_by				= '" . $_SESSION['username'] . "',
																				update_ip				= '" . $add_ip . "',
																				update_from_module_id	= '" . $module_id . "'
									WHERE id = '" . $detail_id . "' ";

			$ok = $db->query($conn, $sql_c_up);
			if ($ok) {
				update_po_detail_status2($db, $conn, $id, $arrival_status_dynamic);
				update_po_status($db, $conn, $id, $arrival_status_dynamic);
				$disp_status_name = get_status_name($db, $conn, $arrival_status_dynamic);
				if (isset($error3['msg'])) unset($error3['msg']);

				if (isset($msg3['msg_success'])) {
					$msg3['msg_success'] .= "<br>Record has been updated successfully.";
				} else {
					$msg3['msg_success'] = "Record has been updated successfully.";
				}
				if ($_SERVER['HTTP_HOST'] != HTTP_HOST_IP) {
					$update_logistics_id = $update_no_of_box_arried =  $update_sub_location_id = "";
				}
			} else {
				$error3['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
			}
		}
	} else {
		$error3['msg'] = "Please check the error in form.";
	}
}
