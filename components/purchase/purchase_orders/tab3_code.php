<?php

if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$sub_location_id		= 1737;
}

if (isset($cmd3) && $cmd3 == 'delete' && isset($detail_id)) {
	if (po_permisions("Arrival") == 0) {
		$error3['msg'] = "You do not have add permissions.";
	} else {
		$sql_c_up = "UPDATE  purchase_order_detail_logistics 
												SET 	
													bill_of_landing		= '',
													sub_location_id		= '0',
													arrival_no			= '0',
													arrived_date		= NULL,
													logistics_status	= '" . $logistic_status_dynamic . "',

													update_timezone		= '" . $timezone . "',
													update_date			= '" . $add_date . "',
													update_by			= '" . $_SESSION['username'] . "',
													update_ip			= '" . $add_ip . "'
					WHERE id = '" . $detail_id . "' ";
		$ok = $db->query($conn, $sql_c_up);
		if ($ok) {

			$sql_ee1 = " SELECT b.* FROM purchase_order_detail_logistics b WHERE b.po_id = '" . $id . "' AND b.arrived_date IS NOT NULL";
			// echo $sql_ee1;
			$result_ee1 	= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
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
			}
			$msg3['msg_success'] = "Arrival record has been deleted successfully.";
		}
	}
}

if (isset($_POST['is_Submit_tab3']) && $_POST['is_Submit_tab3'] == 'Y') {
	extract($_POST);
	$arrived_date1 = "0000-00-00";
	if (!isset($sub_location_id) || (isset($sub_location_id)  && ($sub_location_id == "0" || $sub_location_id == ""))) {
		$error3['sub_location_id'] = "Required";
	}
	if (isset($arrived_date) && $arrived_date == "") {
		$error3['arrived_date'] = "Required";
	} else {
		$arrived_date1 = convert_date_mysql_slash($arrived_date);
	}
	if (!isset($id) || (isset($id)  && ($id == "0" || $id == ""))) {
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
			$k = 0;
			if (isset($logistics_ids_2) && sizeof($logistics_ids_2) > 0) {
				foreach ($logistics_ids_2 as $logistics_id) {
					$arrival_no = 0;
					$sql_ee1 = " SELECT b.* FROM purchase_order_detail_logistics b WHERE b.id = '" . $logistics_id . "'";
					// echo $sql_ee1;
					$result_ee1 	= $db->query($conn, $sql_ee1);
					$counter_ee1	= $db->counter($result_ee1);
					if ($counter_ee1 > 0) {
						$row_ee1_ano	= $db->fetch($result_ee1);
						$arrival_no		= $row_ee1_ano[0]['arrival_no'];
					}
					if ($arrival_no == '0') {
						$sql_ee1 = " SELECT IFNULL(max(a.arrival_no), 0) as max_arrival_no FROM purchase_order_detail_logistics a WHERE a.po_id = '" . $id . "'";
						// echo $sql_ee1;
						$result_ee1 	= $db->query($conn, $sql_ee1);
						$counter_ee1	= $db->counter($result_ee1);
						if ($counter_ee1 > 0) {
							$row_ee1_ano	= $db->fetch($result_ee1);
							$arrival_no		= $row_ee1_ano[0]['max_arrival_no'] + 1;
						}

						for ($k = 1; $k < $arrival_no; $k++) {
							$sql_ee1 = " SELECT a.arrival_no FROM purchase_order_detail_logistics a WHERE a.po_id = '" . $id . "' AND a.arrival_no = '" . $k . "'";
							// echo $sql_ee1;
							$result_ee1 	= $db->query($conn, $sql_ee1);
							$counter_ee1	= $db->counter($result_ee1);
							if ($counter_ee1 == 0) {
								$arrival_no		= $k;
								break;
							}
						}
					}

					$sql_c_up = "UPDATE  purchase_order_detail_logistics SET 	bill_of_landing		= '" . $file_landing . "',
																				sub_location_id		= '" . $sub_location_id . "',
																				arrived_date		= '" . $arrived_date1 . "',
																				logistics_status	= '" . $arrival_status_dynamic . "',
																				arrival_no			= '" . $arrival_no . "',
																				update_timezone		= '" . $timezone . "',
																				update_date			= '" . $add_date . "',
																				update_by			= '" . $_SESSION['username'] . "',
																				update_ip			= '" . $add_ip . "'
										WHERE id = '" . $logistics_id . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$k++;
						if (isset($error3['msg'])) unset($error3['msg']);
					} else {
						$error3['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				}
			} else {
				$error3['msg'] = "Please select atleast one record.";
			}

			if ($k > 0) {
				update_po_detail_status2($db, $conn, $id, $arrival_status_dynamic);
				update_po_status($db, $conn, $id, $arrival_status_dynamic);

				if (isset($msg2['msg_success'])) {
					$msg3['msg_success'] .= "<br>Arrival has been processed successfully.";
				} else {
					$msg3['msg_success'] = "Arrival has been processed successfully.";
				}

				if ($_SERVER['HTTP_HOST'] != 'localhost') {
					$bill_of_landing = $sub_location_id = "";
				}
			}
		}
	} else {
		$error3['msg'] = "Please check Error in form.";
	}
}
