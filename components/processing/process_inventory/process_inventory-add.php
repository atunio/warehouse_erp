<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1) {
	$stock_id 			= 1369;
	$finale_condition 	= "54";
	$custom_product_id 	= "IPAD6-32C-" . date("YmdHis");
	$package_id1 		= "6";
	$package_id2 		= "1";
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
$title_heading 			= "Processing";
$button_val 			= "Save";
$title_heading2			= "Product Processing";
$button_val2 			= "Add";
$total_parts 			= 3;

if ($cmd == 'edit' && isset($detail_id) && $detail_id > 0) {
	$sql_ee				= " SELECT  b.id, b.sub_location_name, b.sub_location_type, 
									GROUP_CONCAT(DISTINCT CONCAT( '', COALESCE(d.first_name, ''), ' ', COALESCE(d.middle_name, ''), ' ', COALESCE(d.last_name, ''), ' (', COALESCE(d.username, ''), ')') ) AS task_user_details,
									c.bin_user_id
							FROM warehouse_sub_locations b 
							INNER JOIN users_bin_for_processing c ON b.id = c.location_id
							INNER JOIN users d ON d.id = c.bin_user_id
							WHERE c.id = '" . $detail_id . "' "; //echo $sql_ee;
	$result_ee			= $db->query($conn, $sql_ee);
	$row_ee				= $db->fetch($result_ee);
	$sub_location_name	= $row_ee[0]['sub_location_name'];
	$sub_location_type	= $row_ee[0]['sub_location_type'];
	$bin_user_id		= $row_ee[0]['bin_user_id'];
	$id					= $row_ee[0]['id'];
	if ($sub_location_type != "") {
		$sub_location_name .= "(" . ucwords(strtolower($sub_location_type)) . ")";
	}
	$task_user_details		= $row_ee[0]['task_user_details'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
// For Testing
$is_test = 0;
if ($is_test == 1) {
	echo "<br><br><br><br><br><br>";
}
if (isset($is_Submit2) && $is_Submit2 == 'Y') {

	$field_name = "stock_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	$field_name = "sub_location";
	$field_name2 = "p_inventory_status";
	if (isset(${$field_name}) && ${$field_name} == "" && isset(${$field_name2}) && ${$field_name2} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";

		$error[$field_name2] 		= "Required";
		${$field_name2 . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		if (access("add_perm") == 0) {
			$error['msg'] = "You do not have add permissions.";
		} else {
			$sql  		= " UPDATE " . $selected_db_name . ".product_stock SET 			 
																		sub_location            = '" . $sub_location . "' ,
																		p_inventory_status      = '" . $p_inventory_status . "' ,
																		is_update_in_process    = '1' ,
																		update_date         	= '" . $add_date . "' ,
																		update_by 	        	= '" . $_SESSION['username'] . "' ,
																		update_by_user_id   	= '" . $_SESSION['user_id'] . "' ,
																		update_ip 	        	= '" . $add_ip . "',
																		update_from_module_id	= '" . $module_id . "'
							WHERE id = '" . $stock_id . "' 
							AND subscriber_users_id = '" . $subscriber_users_id . "' ";
			$ok = $db->query($conn, $sql);
			if ($ok) {
				$msg['msg_success'] = "Record Updated Successfully.";
			} else {
				$error['msg'] = "Error Record updateding.";
			}
		}
	}
}

if (isset($is_Submit2_2) && $is_Submit2_2 == 'Y') {

	$field_name = "finale_condition";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error2[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "stock_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error2[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error2)) {
		if (access("add_perm") == 0) {
			$error2['msg'] = "You do not have add permissions.";
		} else {
			for ($k = 1; $k <= $total_parts; $k++) {
				if (${"package_id" . $k} == "") {
					${"package_id" . $k} = 0;
				}
			}
			$sql = "SELECT a.* 
					FROM " . $selected_db_name . ".time_clock_detail a
					WHERE a.enabled                 = 1
					AND a.subscriber_users_id       = '" . $subscriber_users_id . "'
					AND a.user_id    				= '" . $_SESSION['user_id'] . "'
					AND a.location_or_bin_id    	= '" . $id . "'
					AND a.entryDate                 = '" . date('Y-m-d') . "'
					AND a.entry_type                = 'process'
					AND (a.stopTime = NULL OR a.stopTime IS NULL) 
					ORDER BY a.id DESC LIMIT 1";
			// echo $sql;die;
			$result_cl     = $db->query($conn, $sql);
			$count_cl     = $db->counter($result_cl);
			if ($count_cl > 0) {
				$row_cl1     = $db->fetch($result_cl);
				$update_id  = $row_cl1[0]['id'];
				$sql  		= " UPDATE " . $selected_db_name . ".time_clock_detail SET 	entryDate           	= '" . date('Y-m-d') . "',
																						stopTime            	= '" . $add_date . "' ,
																						stock_id            	= '" . $stock_id . "' ,

																						update_date         	= '" . $add_date . "' ,
																						update_by 	        	= '" . $_SESSION['username'] . "' ,
																						update_by_user_id   	= '" . $_SESSION['user_id'] . "' ,
																						update_ip 	        	= '" . $add_ip . "',
																						update_from_module_id	= '" . $module_id . "'
								WHERE id = '" . $update_id . "' 
								AND subscriber_users_id = '" . $subscriber_users_id . "' ";
				$ok = $db->query($conn, $sql);
				if ($ok) {
					unset($_SESSION['startTime_Process']);
					unset($_SESSION['location_or_bin_id']);
					unset($_SESSION['process']);
					unset($_SESSION['is_start']);
				}
			} else {
				$sql = "SELECT a.* 
						FROM " . $selected_db_name . ".time_clock_detail a
						WHERE a.enabled                 = 1
						AND a.subscriber_users_id       = '" . $subscriber_users_id . "'
						AND a.user_id    				= '" . $_SESSION['user_id'] . "'
						AND a.location_or_bin_id    	= '" . $id . "'
						AND a.entryDate                 = '" . date('Y-m-d') . "'
						AND a.entry_type                = 'process'
						AND (a.stock_id = 0 || a.stock_id = null)
						AND a.id = (SELECT MAX(id) FROM time_clock_detail)
						ORDER BY a.id DESC LIMIT 1";
				// echo $sql;die;
				$result_cl     = $db->query($conn, $sql);
				$count_cl     = $db->counter($result_cl);
				if ($count_cl > 0) {
					$row_cl1     = $db->fetch($result_cl);
					$update_id  = $row_cl1[0]['id'];
					$sql  		= " UPDATE " . $selected_db_name . ".time_clock_detail SET  stock_id            	= '" . $stock_id . "' ,

																							update_date         	= '" . $add_date . "' ,
																							update_by 	        	= '" . $_SESSION['username'] . "' ,
																							update_by_user_id   	= '" . $_SESSION['user_id'] . "' ,
																							update_ip 	        	= '" . $add_ip . "',
																							update_from_module_id	= '" . $module_id . "'
								WHERE id = '" . $update_id . "' 
								AND subscriber_users_id = '" . $subscriber_users_id . "' ";
					$db->query($conn, $sql);
				}
			}

			$parts_ids_array = array();
			for ($k = 1; $k <= $total_parts; $k++) {
				if (isset(${"package_id" . $k}) && ${"package_id" . $k} > 0) {
					$parts_ids_array[] = ${"package_id" . $k};
				}
			}
			$device_processing_parts_price 	= device_parts_price($db, $conn, $parts_ids_array);
			$device_processing_labor 		= device_processing_labor($db, $conn, $stock_id);

			$sql_ee						= " SELECT a.*, b.product_uniqueid, b.product_desc, b.detail_desc, c.sub_location_name, f.po_no, f.po_date
											FROM product_stock a
											INNER JOIN products b ON b.id = a.product_id 
											LEFT JOIN warehouse_sub_locations c ON c.id = a.sub_location 
											INNER JOIN purchase_order_detail_receive d ON d.id = a.receive_id
											INNER JOIN purchase_order_detail e ON e.id = d.po_detail_id
											INNER JOIN purchase_orders f ON f.id = e.po_id
											WHERE a.id = '" . $stock_id . "' ";
			// echo $sql_ee;die;
			$result_ee					= $db->query($conn, $sql_ee);
			$count_ee     				= $db->counter($result_ee);
			if ($count_ee > 0) {
				$row_ee						= $db->fetch($result_ee);
				$serial_no					= $row_ee[0]['serial_no'];
				$stock_price				= $row_ee[0]['price'];
				$finale_product_unique_id	= $row_ee[0]['product_uniqueid'] . "-" . $finale_condition;
				$product_desc				= $row_ee[0]['product_desc'];
				$detail_desc				= $row_ee[0]['detail_desc'];
				$sub_location_name			= $row_ee[0]['sub_location_name'];
				$total_price				= $device_processing_parts_price + $device_processing_labor + $stock_price;

				if ($is_test == 1) {
					// // $po_no = "Da20241205121343";
					// $finale_product_unique_id = "pppp" . date('YmdHis');
					// // $finale_product_unique_id = "pppp20241205111224";
					// $serial_no = "S" . date("YmdHis");
					// echo "<br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa  finale_product_unique_id: " . $finale_product_unique_id;
					// echo "<br><br>";
				}

				if (isset($custom_product_id) && $custom_product_id != "") {
					$finale_product_unique_id	= $custom_product_id;
				}

				begin_transaction($conn);
				$sql_c_up	= "	UPDATE product_stock a
														SET	a.finale_product_unique_id 		= '" . $finale_product_unique_id . "',
															a.is_move_finale				= '1', 
															a.price_finale					= '" . $total_price . "',
															a.finale_condition				= '" . $finale_condition . "',
															a.device_processing_labor		= '" . $device_processing_labor . "',
															a.device_processing_parts_price	= '" . $device_processing_parts_price . "',
															a.package_id1					= '" . $package_id1 . "',
															a.package_id2					= '" . $package_id2 . "',
															a.package_id3					= '" . $package_id3 . "',
															a.update_date					= '" . $add_date . "',
															a.update_by						= '" . $_SESSION['username'] . "',
															a.update_by_user_id				= '" . $_SESSION['user_id'] . "',
															a.update_ip						= '" . $add_ip . "',
															a.update_from_module_id			= '" . $module_id . "',
															a.bin_user_id					= '" . $bin_user_id . "'
								WHERE a.id = '" . $stock_id . "' 
								AND a.is_move_finale = 0 ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {

					$sql_c_up	= "	UPDATE users_bin_for_processing a
														SET	a.is_processing_start 			= '1',
															a.update_date					= '" . $add_date . "',
															a.update_by						= '" . $_SESSION['username'] . "',
															a.update_by_user_id				= '" . $_SESSION['user_id'] . "',
															a.update_ip						= '" . $add_ip . "',
															a.update_from_module_id			= '" . $module_id . "'
								WHERE a.location_id = '" . $id . "' 
								AND a.is_processing_done = 0 ";
					$db->query($conn, $sql_c_up);

					///////////////////////////////////// Add Location In Finale START /////////////////////////////////
					$data = [
						"facilityName" 		=> $sub_location_name,
						"parentFacilityUrl" => "/cti/api/facility/10037"
					];
					$finale_location_id = addSetupInFinale("/cti/api/facility/", $sub_location_name, "facilityName", "facilityId", $data);
					// echo "<br><br><br><br><br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: " . $finale_location_id;// die;
					///////////////////////////////////// Add Location In Finale END /////////////////////////////////

					///////////////////////////////////// Add Product In Finale START /////////////////////////////////
					$data = [
						"productId"         => $finale_product_unique_id,
						"internalName"      => $product_desc,
						"longDescription"   => $detail_desc
					];
					$productUrl = addSetupInFinale("/cti/api/product/", $finale_product_unique_id, "productId", "productUrl", $data);
					// echo "<br><br><br><br><br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: " . $productUrl;die;
					$prefix = "/cti/api/product";
					if (strpos($productUrl, $prefix) === 0) {
					} else {
						$error2['msg'] = "Issue in Getting Product URL.";
					}
					///////////////////////////////////// Add Product In Finale END /////////////////////////////////
					if (empty($error2)) {
						$sql_c_up = " UPDATE product_stock SET finale_location_id = '" . $finale_location_id . "', productUrl = '" . $productUrl . "'  WHERE  id = '" . $stock_id . "' "; // 
						$db->query($conn, $sql_c_up);

						///////////////////////////////////// Add Product lOOKUP In Finale START /////////////////////////////////
						$apiUrl = "https://app.finaleinventory.com/cti/api/scanlookup";
						$data1 = [
							"scanKey"       => $serial_no,
							"productUrl"    => $productUrl,
							"lotId"         => $serial_no,
							"scanTypeId"    => "UNSPECIFIED_TEXT"
						];
						$response = sendPostRequestFinale($data1, $apiUrl);
						if (isset($response['scanKey']) && $response['scanKey'] != "") {
						} else {
							$error2['msg'] = "Issue Addding Product Lookup.";
						}
						///////////////////////////////////// Add Product lOOKUP In Finale END /////////////////////////////////
					}
					///////////////////////////////////// Create Order In Finale END ///////////////////////////////// 
				} else {
					$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
				}
				if (empty($error2)) {
					commit($conn);
					$msg2['msg_success'] = "Record Updated Successfully.";
				} else {
					rollback($conn);
				}
			} else {
				$error2['msg'] = "Error in Stock.";
			}
		}
	}
}
if (isset($is_Submit3) && $is_Submit3 == 'Y') {
	if (!isset($ids_for_stock) || (isset($ids_for_stock) && sizeof($ids_for_stock) == 0)) {
		$error['msg'] = "Select atleast one record";
	}
	if (empty($error)) {
		if (po_permisions("Move to Finale") == 0) {
			$error['msg'] = "You do not have add permissions.";
		} else {
			$all_stock_ids = implode(",", $ids_for_stock);
			$sql 	= "		SELECT DISTINCT a.id, a.po_no, a.po_date
							FROM purchase_orders a
							INNER JOIN purchase_order_detail b ON b.po_id = a.id
							INNER JOIN purchase_order_detail_receive c ON c.po_detail_id = b.id
							INNER JOIN product_stock d ON d.receive_id = c.id
							WHERE 1=1 ";
			$sql   .= " 	AND d.id IN(" . $all_stock_ids . ") 
							AND d.enabled = 1  AND b.enabled = 1 AND c.enabled = 1"; // echo $sql;
			$result_po3	= $db->query($conn, $sql);
			$count_po3	= $db->counter($result_po3);
			if ($count_po3 > 0) {
				$row_ee3 = $db->fetch($result_po3);

				///////////////////////////////// Supplier START /////////////////////////////////
				$setup_name = $user_full_name;
				$data = [
					"groupName"         => $setup_name,
					"roleTypeIdList"    => ["SUPPLIER"],
					"statusId"          => "PARTY_ENABLED"
				];
				$finale_supplier_id = addSetupInFinale("/cti/api/partygroup/", $setup_name, "groupName", "partyId", $data);
				///////////////////////////////////// Add Supplier In Finale END /////////////////////////////////

				foreach ($row_ee3 as $data_p3) {
					$po_no 		= $data_p3['po_no'];
					$po_date 	= $data_p3['po_date'];

					if ($is_test == 1) {
						$po_no = "Da" . date('YmdHis');
						// $po_no = "Da20241205121343";
					}
					///////////////////////////////////// Create Order in Finale START /////////////////////////////////
					$apiUrl = "https://app.finaleinventory.com/cti/api/order/";
					$data = [
						"orderId"                   => $po_no,
						"orderDate"                 => $po_date . 'T07:00:00.000Z',
						"orderTypeId"               => "PURCHASE_ORDER",
						"statusId"                  => "ORDER_CREATED",
						"orderUrl"                  => "/cti/api/order/" . $po_no,
						"shipmentList"  => [
							["shipmentTypeId" => "PURCHASE_SHIPMENT"]
						],
						"orderRoleList"  => [
							["roleTypeId" => "SUPPLIER", "partyId" => $finale_supplier_id]
						]
					];
					$response = sendPostRequestFinale($data, $apiUrl);
					if (isset($response['msg']) && $response['msg'] == 'existing order with orderIdUser=' . $po_no) {
						if (isset($is_test) && $is_test == 1) {
							echo "<br><br><br><br><br><br><br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: already po: " . $po_no;
						}
					} else if (isset($response['orderUrl']) && $response['orderUrl'] != "") {
						if (isset($is_test) && $is_test == 1) {
							echo "<br><br><br><br><br><br><br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: created po: " . $po_no;
						}
					} else {
						$error2['msg'] = "Issue in creating Order.";
					}
					///////////////////////////////////// Create Order In Finale END ///////////////////////////////// 

					if (empty($error2)) {
						$sql = "SELECT d.id, d.finale_product_unique_id, d.price_finale, d.serial_no, d.finale_location_id, d.productUrl
								FROM purchase_orders a
								INNER JOIN purchase_order_detail b ON b.po_id = a.id
								INNER JOIN purchase_order_detail_receive c ON c.po_detail_id = b.id
								INNER JOIN product_stock d ON d.receive_id = c.id
								WHERE 1=1 ";
						if (isset($is_test) && $is_test == 1) {
							$sql .= " AND a.po_no = 'PO3'";
						} else {
							$sql .= " AND a.po_no = '" . $po_no . "'";
						}
						$sql .= " 	AND a.subscriber_users_id = '" . $subscriber_users_id . "'
 									AND d.is_move_finale = 1
									AND d.enabled = 1 
									AND b.enabled = 1
									AND c.enabled = 1 
									AND d.id IN(" . $all_stock_ids . ")  ";
						// echo $sql;
						$result_po     = $db->query($conn, $sql);
						$count_po     = $db->counter($result_po);
						$m = 0;
						if ($count_po > 0) {
							$row_po = $db->fetch($result_po);
							foreach ($row_po as $data_po) {
								$finale_product_unique_id2  = $data_po['finale_product_unique_id'];
								$price_finale2  			= $data_po['price_finale'];
								$finale_location_id			= $data_po['finale_location_id'];
								$serial_no					= $data_po['serial_no'];
								$productUrl2				= $data_po['productUrl'];

								if ($is_test == 1) {
									$serial_no = "SL" . date('YmdHis');
								}
								$orderItemList[$m] = [
									"productId" => $finale_product_unique_id2,
									"quantity" => 1,
									"productUrl"    => $productUrl2,
									"unitPrice" => (float)$price_finale2
								];
								$shipmentItemList[$m] = [
									"productUrl"    => $productUrl2,
									"facilityUrl" => "/cti/api/facility/" . $finale_location_id,
									"quantity" => 1,
									"lotId" => "L_" . $serial_no
								];
								$m++;
							}
						}
						$apiUrl = "https://app.finaleinventory.com/cti/api/order/" . $po_no;
						$data = [
							"orderId"				=> $po_no,
							"orderUrl"				=> "/cti/api/order/" . $po_no,
							"orderHistoryListUrl"	=> "/cti/api/order/" . $po_no . "/history/",
							"orderItemList"			=> $orderItemList
						];
						$response = sendPostRequestFinale($data, $apiUrl);
						if (isset($response['orderUrl']) && $response['orderUrl'] != "") {
							$shipmentUrl = $response['shipmentUrlList'][0];
							/// Add Shipment
							$apiUrl = "https://app.finaleinventory.com" . $shipmentUrl;
							$data = [
								"primaryOrderUrl"   => "/cti/api/order/" . $po_no,
								"shipmentUrl"   	=> $shipmentUrl,
								"shipmentIdUser"    => $po_no . "-1",
								"shipmentItemList"	=> $shipmentItemList,
								"shipmentTypeId" 	=> "PURCHASE_SHIPMENT",
								"statusId" 			=> "SHIPMENT_INPUT"
							];
							$response = sendPostRequestFinale($data, $apiUrl);
							if (isset($response['shipmentUrl']) && $response['shipmentUrl'] != "") {
								$sql = "SELECT d.id, d.finale_product_unique_id, d.price_finale, d.serial_no, d.finale_location_id, d.productUrl
										FROM purchase_orders a
										INNER JOIN purchase_order_detail b ON b.po_id = a.id
										INNER JOIN purchase_order_detail_receive c ON c.po_detail_id = b.id
										INNER JOIN product_stock d ON d.receive_id = c.id
										WHERE 1=1 ";
								if (isset($is_test) && $is_test == 1) {
									$sql .= " AND a.po_no = 'PO3'";
								} else {
									$sql .= " AND a.po_no = '" . $po_no . "'";
								}
								$sql .= " 	AND a.subscriber_users_id 	= '" . $subscriber_users_id . "'
											AND d.is_move_finale 		= 0
											AND d.enabled 				= 1 
											AND b.enabled 				= 1
											AND c.enabled 				= 1 ";
								$result_po2     = $db->query($conn, $sql);
								$count_po2     	= $db->counter($result_po2);
								if (isset($is_test) && $is_test == 1) {
									// echo "<br><br><br><br><br><br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: remaining items: " . $count_po2;
								}
								if ($count_po2 == 0) {
									$apiUrl = $apiUrl . "/receive";
									$data = [
										"receiveDate" => date('Y-m-d') . "T07:00:00.000"
									];
									sendPostRequestFinale($data, $apiUrl);
									$apiUrl = "https://app.finaleinventory.com/cti/api/order/" . $po_no . "/lock";
									$data = [];
									sendPostRequestFinale($data, $apiUrl);
								}
								$sql_c_up	= "	UPDATE product_stock a
													SET	a.is_processed					= '1', 
														a.p_total_stock					= '0',
														a.processed_date				= '" . $add_date . "',
														a.processed_by					= '" . $_SESSION['username'] . "',
														a.processed_by_user_id			= '" . $_SESSION['user_id'] . "',
														a.processed_ip					= '" . $add_ip . "',
														a.update_from_module_id			= '" . $module_id . "'
												WHERE  a.id IN(" . $all_stock_ids . ")  ";
								$db->query($conn, $sql_c_up);
								$msg2['msg_success'] = "Records has been processed to Finale.";
							} else {
								$error2['msg'] = "Issue in adding products in Shipment.";
							}
						} else {
							$error2['msg'] = "Issue in adding products in Order.";
						}
					}
					///////////////////////////////////////////////////////////////////////////////////////////////////////////
				}
			}
		}
	}
} ?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="col s10 m6 l6">
						<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $title_heading; ?></span></h5>
						<ol class="breadcrumbs mb-0">
							<li class="breadcrumb-item"><?php echo $title_heading; ?>
							</li>
							<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">List</a>
							</li>
						</ol>
					</div>
					<div class="col s2 m6 l6">
						<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
							List
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy">
				<div class="card-content">
					<h4 class="card-title">Detail</h4><br>
					<div class="row">
						<?php
						$field_name     = "sub_location_name";
						$field_label     = "Location / Bin";
						?>
						<div class="input-field col m2 s12">
							<i class="material-icons prefix">description</i>
							<input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
																										echo ${$field_name};
																									} ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
																																echo ${$field_name . "_valid"};
																															} ?>">
							<label for="<?= $field_name; ?>">
								<?= $field_label; ?>
								<span class="color-red"><?php
														if (isset($error[$field_name])) {
															echo $error[$field_name];
														} ?>
								</span>
							</label>
						</div>
						<?php
						$field_name     = "task_user_details";
						$field_label     = "Task User Detail";
						?>
						<div class="input-field col m5 s12">
							<i class="material-icons prefix">description</i>
							<input id="<?= $field_name; ?>" type="text" readonly disabled value="<?php if (isset(${$field_name})) {
																										echo ${$field_name};
																									} ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
																																echo ${$field_name . "_valid"};
																															} ?>">
							<label for="<?= $field_name; ?>">
								<?= $field_label; ?>
								<span class="color-red"><?php
														if (isset($error[$field_name])) {
															echo $error[$field_name];
														} ?>
								</span>
							</label>
						</div>

						<div class="input-field col m5 s12">
							<?php
							$entry_type = "process";  ?>
							<a class="btn gradient-45deg-light-blue-cyan timer_<?= $entry_type; ?>" title="Timer" href="javascript:void(0)" id="timer_<?= $entry_type; ?>_<?= $id ?>"
								<?php
								if (
									!isset($_SESSION['is_start']) ||
									!isset($_SESSION[$entry_type]) ||
									(isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] != $entry_type)
								) { ?> style="display: none;" <?php } ?>>00:00:00 </a>
							<a class="btn gradient-45deg-green-teal startButton_<?= $entry_type; ?>" title="Start <?= $entry_type; ?>" href="javascript:void(0)" id="startButton_<?= $entry_type; ?>_<?= $id ?>" onclick="startTimer(<?= $id ?>, '<?= $entry_type ?>')" style="<?php
																																																																				if ((
																																																																					isset($_SESSION['is_start']) && $_SESSION['is_start'] == 1) && (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] == $entry_type)) {
																																																																					echo "display: none;";
																																																																				} ?>">
								Start
							</a> &nbsp;
							<a class="btn gradient-45deg-red-pink stopButton_<?= $entry_type; ?>" title="Stop <?= $entry_type; ?>" href="javascript:void(0)" id="stopButton_<?= $entry_type; ?>_<?= $id ?>" onclick="stopTimer(<?= $id ?>, '<?= $entry_type ?>' )" style=" <?php
																																																																			if (!isset($_SESSION['is_start']) || !isset($_SESSION[$entry_type])) {
																																																																				echo "display: none; ";
																																																																			} else if (isset($_SESSION['is_start']) && $_SESSION['is_start'] != 1 && isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] !=  $entry_type || (isset($_SESSION['p_is_paused']) && $_SESSION['p_is_paused'] == '1')) {
																																																																				echo "display: none;";
																																																																			} ?> ">
								Stop
							</a>&nbsp;
							<a class="btn gradient-45deg-amber-amber pauseButton_<?= $entry_type; ?>" title="Pause Timer" href="javascript:void(0)" id="pauseButton_<?= $entry_type; ?>_<?= $id ?>" onclick="pauseTimer(<?= $id ?>, '<?= $entry_type ?>')" style="<?php
																																																																	if (!isset($_SESSION['is_start']) || !isset($_SESSION[$entry_type])) {
																																																																		echo "display: none; ";
																																																																	} else if (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] ==  $entry_type && (isset($_SESSION['p_is_paused']) && $_SESSION['p_is_paused'] == '1')) {
																																																																		echo "display: none;";
																																																																	} ?> ">
								Pause
							</a>&nbsp;
							<a class="btn gradient-45deg-green-teal resumeButton_<?= $entry_type; ?>" title="Resume <?= $entry_type; ?>" href="javascript:void(0)" id="resumeButton_<?= $entry_type; ?>_<?= $id ?>" onclick="resumeTimer(<?= $id ?>, '<?= $entry_type ?>')" style="<?php
																																																																					if (!isset($_SESSION['p_is_paused']) || (isset($_SESSION['p_is_paused']) && $_SESSION['p_is_paused'] == '0') && (!isset($_SESSION[$entry_type]) || (isset($_SESSION[$entry_type]) && $_SESSION[$entry_type] == $entry_type))) {
																																																																						echo "display: none;";
																																																																					} ?> ">Resume <?php //echo $_SESSION[$entry_type]; 
																																																																									?>
							</a>&nbsp;
							<input type="hidden" name="p_total_pause_duration" id="p_total_pause_duration" value="0">
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		if (isset($cmd) && $cmd == 'edit') { ?>
			<div class="col s12 m12 l12">
				<div id="Form-advance2" class="card card card-default scrollspy">
					<div class="card-content">
						<?php
						if (isset($error['msg'])) { ?>
							<div class="card-alert card red lighten-5">
								<div class="card-content red-text">
									<p><?php echo $error['msg']; ?></p>
								</div>
								<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
						<?php } else if (isset($msg['msg_success'])) { ?>
							<div class="card-alert card green lighten-5">
								<div class="card-content green-text">
									<p><?php echo $msg['msg_success']; ?></p>
								</div>
								<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
						<?php } ?>
						<h4 class="card-title">Update Info</h4><br>
						<form method="post" autocomplete="off" action="">
							<input type="hidden" name="is_Submit2" value="Y" />
							<div class="row">
								<div class="input-field col m8 s12">
									<?php
									$field_name 	= "stock_id";
									$field_label 	= "Product";
									$sql1 			= " SELECT a1.id, a1.serial_no, a.product_uniqueid, a.product_desc, b.category_name
														FROM product_stock a1
														INNER JOIN products a ON a.id = a1.product_id
														INNER JOIN product_categories b ON b.id = a.product_category
 														WHERE a.enabled = 1 
														AND a1.p_total_stock > 0
														AND a1.p_inventory_status = 5
														AND a1.sub_location = '" . $id . "' 
														AND a1.is_move_finale = 0
														ORDER BY b.category_name, a.product_uniqueid  "; //echo $sql1;
									// AND a1.is_processed = 0
									$result1 		= $db->query($conn, $sql1);
									$count1 		= $db->counter($result1);
									?>
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>_2" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																												echo ${$field_name . "_valid"};
																																											} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1	= $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_desc']; ?>
														<?php
														if ($data2['category_name'] != "") {
															echo " (" . $data2['category_name'] . ") ";
														} ?> - <?php echo $data2['product_uniqueid']; ?> - <?php echo $data2['serial_no']; ?></option>
											<?php }
											} ?>
										</select>
										<label for="<?= $field_name; ?>_2">
											<?= $field_label; ?>
											<span class="color-red">* <?php
																		if (isset($error[$field_name])) {
																			echo $error[$field_name];
																		} ?>
											</span>
										</label>
									</div>
									<?php
									$field_name = "stock_id_for_package_material"; ?>
									<input type="hidden" name="<?= $field_name ?>" id="<?= $field_name ?>" value="" />
								</div>
								<div class="input-field col m2 s12">
									<?php
									$field_name     = "sub_location";
									$field_label    = "Location";
									$sql1           = "SELECT * FROM warehouse_sub_locations a WHERE a.enabled = 1  ORDER BY sub_location_name ";
									$result1        = $db->query($conn, $sql1);
									$count1         = $db->counter($result1);
									?>
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1    = $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>>
														<?php echo $data2['sub_location_name'];
														if ($data2['sub_location_type'] != "") {
															echo " (" . ucwords(strtolower($data2['sub_location_type'])) . ")";
														} ?>
													</option>
											<?php }
											} ?>
										</select>
										<label for="<?= $field_name; ?>">
											<?= $field_label; ?>
											<span class="color-red">* <?php
																		if (isset($error[$field_name])) {
																			echo $error[$field_name];
																		} ?>
											</span>
										</label>
									</div>
								</div>
								<div class="input-field col m2 s12">
									<?php
									$field_name     = "p_inventory_status";
									$field_label    = "Status";
									$sql1           = "SELECT a.id,a.status_name FROM inventory_status a WHERE a.enabled = 1 AND a.id IN(" . $status_for_update_info . ") ORDER BY a.id ";
									$result1        = $db->query($conn, $sql1);
									$count1         = $db->counter($result1);
									?>
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1    = $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>>
														<?php echo $data2['status_name'];
														?>
													</option>
											<?php }
											} ?>
										</select>
										<label for="<?= $field_name; ?>">
											<?= $field_label; ?>
											<span class="color-red">* <?php
																		if (isset($error[$field_name])) {
																			echo $error[$field_name];
																		} ?>
											</span>
										</label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="input-field col m4 s12"></div>
							</div>
							<div class="row">
								<div class="input-field col m3 s12"></div>
								<div class="input-field col m4 s12">
									<?php if (($cmd2 == 'add' && access("add_perm") == 1)  || ($cmd2 == 'edit' && access("edit_perm") == 1)) { ?>
										<button class="btn purple waves-effect waves-light right" type="submit" name="action" value="update_info">Update Info
											<i class="material-icons right">send</i>
										</button>
									<?php } ?>
								</div>
							</div>
						</form>
					</div>
					<?php //include('sub_files/right_sidebar.php'); 
					?>
				</div>
			</div>
			<div class="col s12 m12 l12">
				<div id="Form-advance2" class="card card card-default scrollspy">
					<div class="card-content">
						<?php
						if (isset($error2['msg'])) { ?>
							<div class="card-alert card red lighten-5">
								<div class="card-content red-text">
									<p><?php echo $error2['msg']; ?></p>
								</div>
								<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
						<?php } else if (isset($msg2['msg_success'])) { ?>
							<div class="card-alert card green lighten-5">
								<div class="card-content green-text">
									<p><?php echo $msg2['msg_success']; ?></p>
								</div>
								<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
						<?php } ?>
						<h4 class="card-title"><?php echo $title_heading2; ?></h4><br>
						<form method="post" autocomplete="off" action="">
							<input type="hidden" name="is_Submit2_2" value="Y" />
							<div class="row">
								<div class="input-field col m8 s12">
									<?php
									$field_name 	= "stock_id";
									$field_label 	= "Product";
									$sql1 			= " SELECT a1.id, a1.serial_no, a.product_uniqueid, a.product_desc, b.category_name
														FROM product_stock a1
														INNER JOIN products a ON a.id = a1.product_id
														INNER JOIN product_categories b ON b.id = a.product_category
 														WHERE a.enabled = 1 
														AND a1.p_total_stock > 0
														AND a1.sub_location = '" . $id . "' 
														AND a1.is_move_finale = 0
														ORDER BY b.category_name, a.product_uniqueid  "; //echo $sql1;
									// AND a1.is_processed = 0
									$result1 		= $db->query($conn, $sql1);
									$count1 		= $db->counter($result1);
									?>
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1	= $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_desc']; ?>
														<?php
														if ($data2['category_name'] != "") {
															echo " (" . $data2['category_name'] . ") ";
														} ?> - <?php echo $data2['product_uniqueid']; ?> - <?php echo $data2['serial_no']; ?></option>
											<?php }
											} ?>
										</select>
										<label for="<?= $field_name; ?>">
											<?= $field_label; ?>
											<span class="color-red">* <?php
																		if (isset($error2[$field_name])) {
																			echo $error2[$field_name];
																		} ?>
											</span>
										</label>
									</div>
									<?php
									$field_name = "stock_id_for_package_material"; ?>
									<input type="hidden" name="<?= $field_name ?>" id="<?= $field_name ?>" value="" />
								</div>
								<div class="input-field col m2 s12">
									<?php
									$field_name 	= "finale_condition";
									$field_label	= "Finale Condition";
									?>
									<i class="material-icons prefix">subtitles</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {  //a.product_sku, a.case_pack,a.pack_desc, b.category_name, c.total_stock
																																											echo ${$field_name . "_valid"};
																																										} ?>">
											<option value="">Select</option>
											<option value="54" <?php if (isset(${$field_name}) && ${$field_name} == '54') { ?> selected="selected" <?php } ?>>
												54
											</option>
											<option value="27" <?php if (isset(${$field_name}) && ${$field_name} == '27') { ?> selected="selected" <?php } ?>>
												27
											</option>
										</select>
										<label for="<?= $field_name; ?>">
											<?= $field_label; ?>
											<span class="color-red"> * <?php
																		if (isset($error2[$field_name])) {
																			echo $error2[$field_name];
																		} ?>
											</span>
										</label>
									</div>
								</div>
								<div class="input-field col m2 s12">
									<?php
									$field_name  = "custom_product_id";
									$field_label = "Custom Product ID";
									?>
									<i class="material-icons prefix">description</i>
									<input id="<?= $field_name; ?>" name="<?= $field_name; ?>" type="text" value="<?php if (isset(${$field_name})) {
																														echo ${$field_name};
																													} ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
																																		echo ${$field_name . "_valid"};
																																	} ?>">
									<label for="<?= $field_name; ?>">
										<?= $field_label; ?>
										<span class="color-red"><?php
																if (isset($error2[$field_name])) {
																	echo $error2[$field_name];
																} ?>
										</span>
									</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col m4 s12"></div>
							</div>
							<div class="row">
								<?php
								for ($k = 1; $k <= 3; $k++) { ?>
									<div class="input-field col m4 s12">
										<?php
										$field_name 	= "package_id" . $k;
										$field_label	= "Packaging Material / Part "  . $k;
										?>
										<i class="material-icons prefix">subtitles</i>
										<div class="select2div">
											<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {  //a.product_sku, a.case_pack,a.pack_desc, b.category_name, c.total_stock
																																												echo ${$field_name . "_valid"};
																																											} ?>">
												<?php
												if (isset($stock_id) && $stock_id > 0) {
													$sql1 			= " SELECT d.*, e.category_name, a.is_mandatory
																		FROM product_packages a 
																		INNER JOIN products b ON b.id = a.product_id
																		INNER JOIN product_stock c ON b.id = c.product_id
																		INNER JOIN packages d ON d.id = a.package_id
																		INNER JOIN product_categories e ON e.id = d.product_category
																		WHERE c.id = '" . $stock_id . "'
																		AND d.stock_in_hand >0
																		ORDER BY a.is_mandatory DESC, d.package_name ";
													$result1 		= $db->query($conn, $sql1);
													$count1 		= $db->counter($result1);
													if ($count1 > 0) {
														$row1	= $db->fetch($result1); ?>
														<option value="">Select</option>
														<?php
														foreach ($row1 as $data2) {
															$mandatory_optional = "";
															if ($data2['is_mandatory'] == "Yes") {
																$mandatory_optional = " - Mandatory -";
															}
															if ($data2['is_mandatory'] == "No") {
																$mandatory_optional = " - Optional -";
															} ?>
															<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>>
																<?php echo $data2['package_name']; ?> (<?php echo $data2['category_name'] . ")," . $mandatory_optional . " Total Stock Available: " . $data2['stock_in_hand']; ?>
															</option>
														<?php }
													} else { ?>
														<option value="">No <?= $field_label; ?> Available</option>
													<?php }
												} else { ?>
													<option value="">Select</option>
												<?php } ?>
											</select>
											<label for="<?= $field_name; ?>">
												<?= $field_label; ?>
												<span class="color-red"> <?php
																			if (isset($error[$field_name])) {
																				echo $error[$field_name];
																			} ?>
												</span>
											</label>
										</div>
										<?php
										$field_name = "product_id_for_package_material"; ?>
										<input type="hidden" name="<?= $field_name ?>" id="<?= $field_name ?>" value="" />
									</div>
								<?php } ?>
							</div>
							<div class="row">
								<div class="input-field col m4 s12"></div>
							</div>
							<div class="row">
								<div class="input-field col m3 s12"></div>
								<div class="input-field col m4 s12">
									<?php if (($cmd2 == 'add' && access("add_perm") == 1)  || ($cmd2 == 'edit' && access("edit_perm") == 1)) { ?>
										<button class="btn cyan waves-effect waves-light right" type="submit" value="move_finale" name="action">Process to Finale
											<i class="material-icons right">send</i>
										</button>
									<?php } ?>
								</div>
							</div>
						</form>
					</div>
					<?php //include('sub_files/right_sidebar.php'); 
					?>
				</div>
			</div>
			<?php
			$sql_cl		= "	SELECT a.product_uniqueid, a.product_desc, b.category_name, 
									a1.id, a1.serial_no, a1.price, a1.device_processing_parts_price, a1.processed_date, a1.finale_condition, 
									a1.finale_product_unique_id, a1.price_finale, a1.is_move_finale, a1.is_processed,
									c.package_name as package_name1, d.package_name as package_name2, e.package_name as package_name3, a1.device_processing_labor
							FROM product_stock a1
 							INNER JOIN products a ON a.id = a1.product_id
							INNER JOIN product_categories b ON b.id = a.product_category
							LEFT JOIN packages c ON c.id = package_id1
							LEFT JOIN packages d ON d.id = package_id2
							LEFT JOIN packages e ON e.id = package_id3
							WHERE a.enabled 				= 1 
							AND a1.is_move_finale 			= 1 
							AND a1.is_update_in_process		= 0 
							AND a1.sub_location 			= '" . $id . "' 
							AND a1.bin_user_id				= '" . $bin_user_id . "' 
							ORDER BY b.category_name, a.product_uniqueid, a1.finale_product_unique_id ";
			// echo $sql_cl;
			$result_cl	= $db->query($conn, $sql_cl);
			$count_cl	= $db->counter($result_cl);
			if ($count_cl > 0) { ?>
				<div class="col s12">
					<div class="container">
						<form method="post">
							<input type="hidden" name="is_Submit3" value="Y" />
							<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																				echo encrypt($_SESSION['csrf_session']);
																			} ?>">
							<div class="section section-data-tables">
								<!-- Page Length Options -->
								<h4 class="card-title">Processed Products</h4>
								<div class="row">
									<div class="col m6 s12">
									</div>
									<div class="col m3 s12">
										<a href="export/export_processed_items.php?string=<?php echo encrypt("module_id=" . $module_id . "&id=" . $id) ?>" class="btn waves-effect waves-light border-round gradient-45deg-amber-amber col m12 s12">Export Processed Data in Excel</a>
									</div>
								</div>
								<div class="row">
									<div class="col s12">
										<div class="card">
											<div class="card-content">
												<?php
												if (isset($error3['msg'])) { ?>
													<div class="card-alert card red lighten-5">
														<div class="card-content red-text">
															<p><?php echo $error3['msg']; ?></p>
														</div>
														<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">×</span>
														</button>
													</div>
												<?php } else if (isset($msg3['msg_success'])) { ?>
													<div class="card-alert card green lighten-5">
														<div class="card-content green-text">
															<p><?php echo $msg3['msg_success']; ?></p>
														</div>
														<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
															<span aria-hidden="true">×</span>
														</button>
													</div>
												<?php }  ?>
												<div class="row">
													<div class="col s12">
														<table id="page-length-option" class="display pagelength50_2">
															<thead>
																<tr>
																	<th class="sno_width_60">S.No
																		<?php
																		if (po_permisions("Move to Finale") == 1) { ?>
																			<label>
																				<input type="checkbox" id="all_checked" class="filled-in" name="all_checked" value="1" <?php if (isset($all_checked) && $all_checked == '1') {
																																											echo "checked";
																																										} ?> />
																				<span></span>
																			</label>
																		<?php } ?>
																	</th>
																	<?php
																	$headings = '	
																					<th>Product ID</br>Product Detail</th> 
																					<th>Serial#</th>
																					<th>Finale ProductID /<br>Processed Date</th>
																					<th>Finale Grade</th>
																					<th>Parts / Package /<br> Materials</th>
																					<th>Price</th>
																					<th>Labor Cost</th>
																					<th>Parts/Package /<br> Materials Cost</th>
																					<th>Final Price</th>';
																	echo $headings;
																	?>
																</tr>
															</thead>
															<tbody>
																<?php
																$i = 0;
																if ($count_cl > 0) {
																	$row_cl = $db->fetch($result_cl);
																	foreach ($row_cl as $data) {
																		$detail_id2						= $data['id'];
																		$price 							= $data['price'];
																		$device_processing_labor		= $data['device_processing_labor'];
																		$device_processing_parts_price	= $data['device_processing_parts_price'];
																		$total_price					= $data['price_finale'];
																		$is_processed					= $data['is_processed']; ?>
																		<tr>
																			<td>
																				<?php echo $i + 1;
																				if (po_permisions("Move to Finale") == 1 && $is_processed == "0") { ?>
																					<label style="margin-left: 25px;">
																						<input type="checkbox" name="ids_for_stock[]" id="ids_for_stock[]" value="<?= $detail_id2; ?>" <?php
																																														if (isset($ids_for_stock) && in_array($detail_id2, $ids_for_stock)) {
																																															echo "checked";
																																														} ?> class="checkbox filled-in" />
																						<span></span>
																					</label>
																				<?php } ?>
																			</td>
																			<td>
																				<?php echo $data['product_uniqueid']; ?></br>
																				<?php
																				echo ucwords(strtolower($data['product_desc']));
																				if ($data['category_name'] != "") {
																					echo "(" . $data['category_name'] . ")";
																				} ?>
																			</td>
																			<td><?php echo $data['serial_no']; ?></td>
																			<td>
																				<?php echo $data['finale_product_unique_id']; ?>
																				<br>
																				<?php echo dateformat1_with_time($data['processed_date']); ?>
																			</td>
																			<td>
																				<?php echo $data['finale_condition']; ?> &nbsp;&nbsp;
																				<a href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/printlabels_pdf.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id . "&detail_id=" . $detail_id2) ?>" target="_blank">
																					<i class="material-icons dp48">print</i>
																				</a>
																			</td>
																			<td>
																				<?php
																				$m = 1;
																				if ($data['package_name1'] != '') {
																					echo $m . ": " . $data['package_name1'];
																					$m++;
																				}
																				if ($data['package_name2'] != '') {
																					echo "<br>" . $m . ": " . $data['package_name2'];
																					$m++;
																				}
																				if ($data['package_name3'] != '') {
																					echo "<br>" . $m . ": " . $data['package_name3'];
																					$m++;
																				} ?>
																			</td>
																			<td><?php echo number_format($price, 2); ?></td>
																			<td><?php echo number_format($device_processing_labor, 2); ?></td>
																			<td><?php echo number_format(($device_processing_parts_price), 2); ?></td>
																			<td><?php echo number_format(($total_price), 2); ?></td>
																		</tr>
																<?php $i++;
																	}
																} ?>
															<tfoot>
																<tr>
																	<th class="sno_width_60">S.No</th>
																	<?php echo $headings; ?>
																</tr>
															</tfoot>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- Multi Select -->
							</div><!-- START RIGHT SIDEBAR NAV -->
							<?php
							if (po_permisions("Move to Finale") == 1) { ?>
								<div class="row">
									<div class="input-field col m4 s12"></div>
									<div class="input-field col m4 s12">
										<?php if (isset($id) && $id > 0) { ?>
											<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col m12 s12" type="submit" name="add">Move PO to Finale</button>
										<?php } ?>
									</div>
									<div class="input-field col m4 s12"></div>
								</div>
								<div class="row">
									<div class="input-field col m12 s12"></div>
								</div>
							<?php } ?>
						</form>
						<?php include('sub_files/right_sidebar.php'); ?>
					</div>

					<div class="content-overlay"></div>
				</div>
		<?php }
		} ?>
	</div>
	<?php include("sub_files/add_product_modal.php") ?>
	<?php include("sub_files/add_vender_modal.php") ?>
</div>
<br><br><br><br>
<!-- END: Page Main-->
<!-- END: Page Main-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<?php include("sub_files/add_product_js_code.php") ?>
<?php include("sub_files/add_vender_js_code.php") ?>