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
	$title_heading 	= "Update RMA Order";
	$button_val 	= "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Create RMA Order";
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
	$sql_ee					= "SELECT a.* FROM rma_orders a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$po_id					=  $row_ee[0]['po_id'];
	$rma_no					=  $row_ee[0]['rma_no'];
	$rma_date				= str_replace("-", "/", convert_date_display($row_ee[0]['rma_date']));
	$rma_date_disp			= dateformat2($row_ee[0]['rma_date']);
	$rma_desc				=  $row_ee[0]['rma_desc'];
	$rma_desc_public 		=  $row_ee[0]['rma_desc_public'];
	$order_status    		= $row_ee[0]['order_status'];
	$received_ids 		= [];
	$rma_status 		= [];
	$sql_ee1		= "SELECT a.* FROM rma_order_detail a WHERE a.rma_id = '" . $id . "' ";  //echo $sql_ee1;
	$result_ee1		= $db->query($conn, $sql_ee1);
	$count_ee1  	= $db->counter($result_ee1);
	if($count_ee1 > 0){
		$row_ee1	= $db->fetch($result_ee1);
		foreach($row_ee1 as $data2){
			$received_ids[]	= $data2['received_id'];
			$rma_status[]	= $data2['rma_status'];
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

	$field_name = "po_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "rma_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	if (empty($error)) {
		$rma_date1 = NULL;
		if (isset($rma_date) && $rma_date != "") {
			$rma_date1 = convert_date_mysql_slash($rma_date);
		}
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM rma_orders a 
								WHERE a.po_id	= '" . $po_id . "'
								AND a.rma_date		= '" . $rma_date1 . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".rma_orders(subscriber_users_id,po_id,rma_date,add_date, add_by, add_by_user_id, add_ip, add_timezone) 
							 VALUES('" . $subscriber_users_id . "', '" . $po_id . "', '" . $rma_date1  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id					= mysqli_insert_id($conn);
						$rma_no				= "RMA" . $id;
						$order_date_disp	= dateformat2($rma_date1);
						$cmd 				= 'edit';
						$order_status		= 1;

						$sql6 = " UPDATE rma_orders SET rma_no = '" . $rma_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);
						$msg['msg_success'] = "RMA Order has been created successfully.";
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
				$sql_dup	= " SELECT a.* FROM rma_orders a 
								WHERE a.po_id	= '" . $po_id . "'
								AND a.rma_date	= '" . $rma_date1 . "' 
								AND a.id		   != '" . $id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE sales_orders SET	po_id					= '" . $po_id . "',
															rma_date				= '" . $rma_date1 . "',
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

	$field_name = "po_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "rma_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	if (empty($error)) {
		$rma_date1 = NULL;
		if (isset($rma_date) && $rma_date != "") {
			$rma_date1 = convert_date_mysql_slash($rma_date);
		}
		$sql_dup	= " SELECT a.* FROM rma_orders a 
								WHERE a.po_id	= '" . $po_id . "'
								AND a.rma_date	= '" . $rma_date1 . "' 
								AND a.id		   != '" . $id . "' ";
		$result_dup	= $db->query($conn, $sql_dup);
		$count_dup	= $db->counter($result_dup);
		if ($count_dup == 0) {
			$sql_c_up = "UPDATE rma_orders 	SET		po_id					= '" . $po_id . "',
													rma_date				= '" . $rma_date1 . "',
													rma_desc				= '" . $rma_desc . "', 
													rma_desc_public			= '" . $rma_desc_public . "', 
													update_date				= '" . $add_date . "',
													update_by				= '" . $_SESSION['username'] . "',
													update_by_user_id		= '" . $_SESSION['user_id'] . "',
													update_ip				= '" . $add_ip . "',
													update_timezone			= '" . $timezone . "'
						WHERE id = '" . $id . "' ";
			$ok = $db->query($conn, $sql_c_up);
		}
		$k = 0;
		if(isset($order_status) && $order_status == 1){
			$sql_dup = " DELETE FROM rma_order_detail WHERE rma_id	= '" . $id . "'";
			$db->query($conn, $sql_dup);

			$filtered_product_ids = (array_values(array_filter($received_ids)));

			$i = 0; // Initialize the counter before the loop
			$r = 1;
			foreach ($filtered_product_ids as $received_id) {
				$sql_dup	= " SELECT a.* 
								FROM rma_order_detail a 
								WHERE a.rma_id	= '" . $id . "'
								AND a.received_id	= '" . $received_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".rma_order_detail(rma_id, received_id,rma_status, add_date, add_by, add_by_user_id, add_ip, add_timezone)
							VALUES('" . $id . "', '" . $received_id . "', '" . $rma_status[$i]  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$k++; // Increment the counter only if the insertion is successful
					}
					$i++;
				}
				else{ 
					$received_ids[$i] 	= "";
					$rma_status[$i] 	= "";
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