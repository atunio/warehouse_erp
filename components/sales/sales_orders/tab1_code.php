<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$order_date = date('d/m/Y');
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$customer_id				= "1";
	$source_id					= "1";
	$origin_id					= "1";
 	$customer_invoice_no		= date('YmdHis');
	$fullfilment_id				= "1";
	$terms_id					= "1";
	$requested_shipment_id		= "1";
	$batch_id					= "1";
	$public_note				= "public_note " . date('YmdHis');
	$internal_note				= "internal_note " . date('YmdHis');
	$order_status    			= "1";
	$stage_status    			= "Draft";
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

// if (!isset($is_Submit) && $cmd == 'edit' && isset($msg['msg_success']) && isset($id)) {
// 	echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id));
// }
if (isset($cmd3) && $cmd3 == 'disabled') {
	$sql_c_upd = "UPDATE sales_order_detail set 	enabled = 0,
													update_date = '" . $add_date . "' ,
													update_by 	= '" . $_SESSION['username'] . "' ,
													update_ip 	= '" . $add_ip . "'
				WHERE id = '" . $detail_id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg2['msg_success'] = "Record has been disabled.";
	} else {
		$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
	}
}
if (isset($cmd3) && $cmd3 == 'enabled') {
	$sql_c_upd = "UPDATE sales_order_detail set 	enabled 	= 1,
													update_date = '" . $add_date . "' ,
													update_by 	= '" . $_SESSION['username'] . "' ,
													update_ip 	= '" . $add_ip . "'
				WHERE id = '" . $detail_id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg2['msg_success'] = "Record has been enabled.";
	}
}

if ($cmd == 'edit') {
	$title_heading 	= "Update Sale Order";
	$button_val 	= "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Create Sale Order";
	$button_val 	= "Create";
	$id 			= "";
}

$title_heading2	= "Add Order Product";
$button_val2 	= "Add";
if (isset($cmd2) && $cmd2 == 'edit') {
	$title_heading2  = "Update Order Product";
	$button_val2 	= "Save";
}

if ($cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee					= "SELECT a.* FROM sales_orders a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$customer_id			=  $row_ee[0]['customer_id'];
	$so_no					=  $row_ee[0]['so_no'];
	$source_id				=  $row_ee[0]['source_id'];
	$order_date				= str_replace("-", "/", convert_date_display($row_ee[0]['order_date']));
	$order_date_disp		= dateformat2($row_ee[0]['order_date']);
	$origin_id				=  $row_ee[0]['origin_id'];
	$customer_invoice_no 	=  $row_ee[0]['customer_invoice_no'];
	$fullfilment_id			=  $row_ee[0]['fullfilment_id'];
	$terms_id				=  $row_ee[0]['terms_id'];
	$requested_shipment_id	=  $row_ee[0]['requested_shipment_id'];
	$batch_id				=  $row_ee[0]['batch_id'];
	$public_note			=  $row_ee[0]['public_note'];
	$internal_note			=  $row_ee[0]['internal_note'];
	$order_status    		= $row_ee[0]['order_status'];
	$stage_status    		= $row_ee[0]['stage_status'];
	$product_stock_ids 		= [];
	$order_price 			= [];
	$product_so_desc 		= [];
	$sql_ee1		= "SELECT a.* FROM sales_order_detail a WHERE a.sales_order_id = '" . $id . "' ";  //echo $sql_ee1;
	$result_ee1		= $db->query($conn, $sql_ee1);
	$count_ee1  	= $db->counter($result_ee1);
	if($count_ee1 > 0){
		$row_ee1	= $db->fetch($result_ee1);
		foreach($row_ee1 as $data2){
			$product_stock_ids[]	= $data2['product_stock_id'];
			$order_price[]			= $data2['order_price'];
			$product_so_desc[]			= $data2['product_so_desc'];
		}
	}
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {

	$field_name = "customer_invoice_no";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "customer_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "order_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	if (empty($error)) {
		$order_date1 = NULL;
		if (isset($order_date) && $order_date != "") {
			$order_date1 = convert_date_mysql_slash($order_date);
		}
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM sales_orders a 
								WHERE a.customer_id	= '" . $customer_id . "'
								AND a.order_date		= '" . $order_date1 . "'
								AND a.customer_invoice_no	= '" . $customer_invoice_no . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".sales_orders(subscriber_users_id, customer_id, source_id, order_date, origin_id, 
											customer_invoice_no,fullfilment_id,terms_id,requested_shipment_id,batch_id,public_note,internal_note,add_date, add_by, add_by_user_id, add_ip, add_timezone) 
							 VALUES('" . $subscriber_users_id . "', '" . $customer_id . "',  '" . $source_id . "', '" . $order_date1  . "', '" . $origin_id  . "',
									'" . $customer_invoice_no  . "','" . $fullfilment_id . "' , '" . $terms_id . "' , '" . $requested_shipment_id . "' , '" . $batch_id . "' , '" . $public_note . "' , '" . $internal_note . "',
											'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id					= mysqli_insert_id($conn);
						$so_no				= "SO" . $id;
						$order_date_disp	= dateformat2($order_date1);
						$cmd 				= 'edit';
						$order_status		= 1;

						$sql6 = " UPDATE sales_orders SET so_no = '" . $so_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);
						$msg['msg_success'] = "Sale Order has been created successfully.";
						// echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id . "&msg_success=" . $msg['msg_success']));
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM sales_orders a 
								WHERE a.customer_id	= '" . $customer_id . "'
								AND a.order_date	= '" . $order_date1 . "' 
								AND a.customer_invoice_no	= '" . $customer_invoice_no . "' 
								AND a.id		   != '" . $id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE sales_orders SET	customer_id				= '" . $customer_id . "',
															source_id				= '" . $source_id . "',
															order_date				= '" . $order_date1 . "',
															origin_id				= '" . $origin_id . "',
															customer_invoice_no 	= '" . $customer_invoice_no . "',
															fullfilment_id			= '" . $fullfilment_id . "',
															terms_id				= '" . $terms_id . "',
															requested_shipment_id	= '" . $requested_shipment_id . "',
															batch_id				= '" . $batch_id . "',
															public_note				= '" . $public_note . "',
															internal_note			= '" . $internal_note . "',
															update_date				= '" . $add_date . "',
															update_by				= '" . $_SESSION['username'] . "',
															update_by_user_id		= '" . $_SESSION['user_id'] . "',
															update_ip				= '" . $add_ip . "',
															update_timezone			= '" . $timezone . "'
								WHERE id = '" . $id . "' ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
					} else {
						$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
				}
			}
		}
	}
}
if (isset($is_Submit2) && $is_Submit2 == 'Y') {

	$field_name = "customer_invoice_no";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "customer_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "order_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	if (empty($error)) {
		$order_date1 = NULL;
		if (isset($order_date) && $order_date != "") {
			$order_date1 = convert_date_mysql_slash($order_date);
		}
		$sql_dup	= " SELECT a.* FROM sales_orders a 
						WHERE a.customer_id	= '" . $customer_id . "'
						AND a.order_date	= '" . $order_date1 . "' 
						AND a.customer_invoice_no	= '" . $customer_invoice_no . "' 
						AND a.id		   != '" . $id . "' ";
		$result_dup	= $db->query($conn, $sql_dup);
		$count_dup	= $db->counter($result_dup);
		if ($count_dup == 0) {
			$sql_c_up = "UPDATE sales_orders SET	customer_id				= '" . $customer_id . "',
													source_id				= '" . $source_id . "',
													order_date				= '" . $order_date1 . "',
													origin_id				= '" . $origin_id . "',
													customer_invoice_no 			= '" . $customer_invoice_no . "',
													fullfilment_id			= '" . $fullfilment_id . "',
													terms_id				= '" . $terms_id . "',
													requested_shipment_id	= '" . $requested_shipment_id . "',
													batch_id				= '" . $batch_id . "',
													public_note				= '" . $public_note . "',
													internal_note			= '" . $internal_note . "',
													update_date				= '" . $add_date . "',
													update_by				= '" . $_SESSION['username'] . "',
													update_by_user_id		= '" . $_SESSION['user_id'] . "',
													update_ip				= '" . $add_ip . "',
													update_timezone			= '" . $timezone . "'
						WHERE id = '" . $id . "' ";
			$ok = $db->query($conn, $sql_c_up);
		}
		$k = 0;
		if (isset($stage_status) && $stage_status != "Committed") {
			// $sql_dup = " DELETE FROM sales_order_detail WHERE sales_order_id	= '" . $id . "'";
			// $db->query($conn, $sql_dup);

			$filtered_product_ids = (array_values(array_filter($product_stock_ids)));
			$current_ids = implode(',', $filtered_product_ids);
			if($current_ids !=""){
				$sql_dup1 = "UPDATE sales_order_detail SET enabled = 0 
							WHERE sales_order_id	= '" . $id . "' 
							AND product_stock_id NOT IN(" . $current_ids . ") ";
				$db->query($conn, $sql_dup1);
			}

			$i = 0; // Initialize the counter before the loop
			$r = 1;
			foreach ($filtered_product_ids as $product_stock_id) {
				$sql_dup	= " SELECT a.* 
								FROM sales_order_detail a 
								WHERE a.sales_order_id	= '" . $id . "'
								AND a.product_stock_id	= '" . $product_stock_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".sales_order_detail(sales_order_id, product_stock_id, product_so_desc, order_price , add_date, add_by, add_by_user_id, add_ip, add_timezone)
							VALUES('" . $id . "', '" . $product_stock_id . "', '" . $product_so_desc[$i]  . "', '" . $order_price[$i]  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$k++; // Increment the counter only if the insertion is successful
					}
					$i++;
				}
				else{ 
					$sql_c_up = "UPDATE  sales_order_detail SET 
																product_so_desc     = '" . $product_so_desc[$i] . "',
																order_price			= '" . $order_price[$i] . "',
																enabled 			= 1,
																
																update_timezone	= '" . $timezone . "',
																update_date		= '" . $add_date . "',
																update_by		= '" . $_SESSION['username'] . "',
																update_ip		= '" . $add_ip . "'
								WHERE sales_order_id = '" . $id . "'  AND product_stock_id = '" . $product_stock_id . "' ";
					$db->query($conn, $sql_c_up);
					$product_stock_ids[$i] 	= "";
					$order_price[$i] 		= "";
					$product_so_desc[$i] 	= "";
					$i++;
				}
			}
		}
		if($k == 1){
			if (isset($error2['msg'])) unset($error2['msg']);
			$msg2['msg_success'] = "Record has been added successfully.";
		}else{
			if (isset($error2['msg'])) unset($error2['msg']);
			$msg2['msg_success'] = "Record has been added successfully.";
		} 
	}
}