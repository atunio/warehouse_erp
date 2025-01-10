<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$customer_id				= "1";
	$source_id					= "1";
	$order_date 				= date('d/m/Y');
	$origin_id					= "1";
	$estimated_ship_date	 	= date('d/m/Y');
	$customer_po_no				= date('YmdHis');
	$fullfilment_id				= "1";
	$terms_id					= "1";
	$requested_shipment_id		= "1";
	$batch_id					= "1";
	$public_note				= "public_note " . date('YmdHis');
	$internal_note				= "internal_note " . date('YmdHis');
}
if (isset($test_on_local) && $test_on_local == 1 && (!isset($cmd2))) {
	$product_stock_id			= "1487";
	$order_qty					= "1";
	$order_price				= "500";
	$product_so_desc			= "product_so_desc: " . date('YmdHis');
	$product_condition			= "A Grade";
	$warranty_period_in_days	= "15";
	$customer_po_no				= date('YmdHis');
	$is_tested					= "Yes";
	$is_wiped					= "Yes";
	$is_imaged					= "Yes";
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

if (isset($cmd2) && $cmd2 == 'edit') {
	$title_heading2  = "Update Order Product";
	$button_val2 	= "Save";
} else {
	$title_heading2	= "Add Order Product";
	$button_val2 	= "Add";
	$detail_id		= "";
}

if ($cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee					= "SELECT a.* FROM sales_orders a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee					= $db->query($conn, $sql_ee);
	$row_ee						= $db->fetch($result_ee);
	$customer_id				=  $row_ee[0]['customer_id'];
	$source_id					=  $row_ee[0]['source_id'];
	$order_date					= str_replace("-", "/", convert_date_display($row_ee[0]['order_date']));
	$origin_id					=  $row_ee[0]['origin_id'];
	$estimated_ship_date		= str_replace("-", "/", convert_date_display($row_ee[0]['estimated_ship_date']));
	$customer_po_no				=  $row_ee[0]['customer_po_no'];
	$fullfilment_id				=  $row_ee[0]['fullfilment_id'];
	$terms_id					=  $row_ee[0]['terms_id'];
	$requested_shipment_id		=  $row_ee[0]['requested_shipment_id'];
	$batch_id					=  $row_ee[0]['batch_id'];
	$public_note				=  $row_ee[0]['public_note'];
	$internal_note				=  $row_ee[0]['internal_note'];
}
if (isset($cmd2) && $cmd2 == 'edit' && isset($detail_id) && $detail_id > 0) {
	$sql_ee						= "SELECT a.* FROM sales_order_detail a WHERE a.id = '" . $detail_id . "' "; // echo $sql_ee;
	$result_ee					= $db->query($conn, $sql_ee);
	$row_ee						= $db->fetch($result_ee);
	$product_stock_id			= $row_ee[0]['product_stock_id'];
	$order_qty					= $row_ee[0]['order_qty'];
	$order_price				= $row_ee[0]['order_price'];
	$product_so_desc			= $row_ee[0]['product_so_desc'];
	$product_condition			= $row_ee[0]['product_condition'];
	$warranty_period_in_days	= $row_ee[0]['warranty_period_in_days'];
	$package_id					= $row_ee[0]['package_id'];
	$package_material_qty		= $row_ee[0]['package_material_qty'];
	$is_tested					= $row_ee[0]['is_tested'];
	$is_wiped					= $row_ee[0]['is_wiped'];
	$is_imaged					= $row_ee[0]['is_imaged'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
//echo "================================================================>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> herreeee.........";die;
if (isset($is_Submit) && $is_Submit == 'Y') {

	$field_name = "customer_po_no";
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
	$field_name = "estimated_ship_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		$order_date1 = NULL;
		if (isset($order_date) && $order_date != "") {
			$order_date1 = convert_date_mysql_slash($order_date);
		}
		$estimated_ship_date1 = NULL;
		if (isset($estimated_ship_date) && $estimated_ship_date != "") {
			$estimated_ship_date1 = convert_date_mysql_slash($estimated_ship_date);
		}
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM sales_orders a 
								WHERE a.customer_id	= '" . $customer_id . "'
								AND a.order_date		= '" . $order_date1 . "'
								AND a.customer_po_no	= '" . $customer_po_no . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".sales_orders(subscriber_users_id, customer_id, source_id, order_date, origin_id,estimated_ship_date, 
											customer_po_no,fullfilment_id,terms_id,requested_shipment_id,batch_id,public_note,internal_note,add_date, add_by, add_by_user_id, add_ip, add_timezone) 
									VALUES('" . $subscriber_users_id . "', '" . $customer_id . "',  '" . $source_id . "', '" . $order_date1  . "', '" . $origin_id  . "', '" . $estimated_ship_date1  . "',
									'" . $customer_po_no  . "','" . $fullfilment_id . "' , '" . $terms_id . "' , '" . $requested_shipment_id . "' , '" . $batch_id . "' , '" . $public_note . "' , '" . $internal_note . "',
											'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";



					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id			= mysqli_insert_id($conn);
						$so_no		= "SO" . $id;

						$sql6		= " UPDATE sales_orders SET so_no = '" . $so_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						$msg['msg_success'] = "Sale Order has been created successfully.";
						echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id . "&msg_success=" . $msg['msg_success']));
						$customer_id =  $order_date = $estimated_ship_date = "";
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
								AND a.customer_po_no	= '" . $customer_po_no . "' 
								AND a.id		   != '" . $id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE sales_orders SET	customer_id				= '" . $customer_id . "',
															source_id				= '" . $source_id . "',
															order_date				= '" . $order_date1 . "',
															origin_id				= '" . $origin_id . "',
															estimated_ship_date 	= '" . $estimated_ship_date1 . "',
															customer_po_no 			= '" . $customer_po_no . "',
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

	$field_name = "order_price";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "order_qty";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "product_stock_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		if ($cmd2 == 'add') {
			if (access("add_perm") == 0) {
				$error2['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM sales_order_detail a 
								WHERE a.sales_order_id	= '" . $id . "'
								AND a.product_stock_id		= '" . $product_stock_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".sales_order_detail(sales_order_id, product_stock_id, order_qty, order_price, product_so_desc, add_date, add_by, add_by_user_id, add_ip, add_timezone)
							 VALUES('" . $id . "', '" . $product_stock_id . "', '" . $order_qty  . "', '" . $order_price  . "', '" . $product_so_desc  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						if (isset($error2['msg'])) unset($error2['msg']);
						$msg2['msg_success'] = "Record has been added successfully.";
						$product_stock_id = $order_price = $order_qty = $product_so_desc  = "";
					} else {
						$error2['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error2['msg'] = "This record is already exist.";
				}
			}
		} else if ($cmd2 == 'edit') {
			if (access("edit_perm") == 0) {
				$error2['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM sales_order_detail a 
								WHERE a.sales_order_id	= '" . $id . "'
								AND a.product_stock_id		= '" . $product_stock_id . "' 
 								AND a.id			   != '" . $detail_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE sales_order_detail SET 	product_stock_id		= '" . $product_stock_id . "', 
																order_qty				= '" . $order_qty . "', 
																order_price				= '" . $order_price . "', 
																product_so_desc			= '" . $product_so_desc . "',  

																update_date				= '" . $add_date . "',
																update_by				= '" . $_SESSION['username'] . "',
																update_by_user_id		= '" . $_SESSION['user_id'] . "',
																update_ip				= '" . $add_ip . "',
																update_timezone			= '" . $timezone . "'
								WHERE id = '" . $detail_id . "'   ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg2['msg_success'] = "Record Updated Successfully.";
					} else {
						$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error2['msg'] = "This record is already exist.";
				}
			}
		}
	}
}  
//include("sales_orders-profile.php");
?>

<br><br><br><br>
<!-- END: Page Main-->
<!-- END: Page Main-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<?php include("sub_files/add_customer_js_code.php") ?>