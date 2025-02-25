<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$store_id					= "1";
	$return_date				= date('d/m/Y');
	$internal_note					= "purchase order desc : " . date('YmdHis');
	$removal_order_id			= date('YmdHis');
	$return_type				= "Shipstation";
	$return_status = 1;
	$stage_status    			= "Draft";
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

if ($cmd == 'edit') {
	$title_heading 	= "Update Purchase Order";
	$button_val 	= "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Create Purchase Order";
	$button_val 	= "Create";
	$id 			= "";
}

$title_heading2	= "Add Order Product";
$button_val2 	= "Add";
if (isset($cmd2) &&  $cmd2 == 'edit') {
	$title_heading2  = "Update Order Product";
	$button_val2 	= "Save";
}
if ($cmd == 'edit' && isset($id) && $id > 0) {
	

	$sql_ee		= " SELECT a.*, b.store_name , a.return_type , a.removal_order_id , a.return_date  , a.public_note  , a.internal_note
					FROM `returns` a  JOIN `stores` b ON b.`id`= a.`store_id`   WHERE a.id = '" . $id . "'"; // echo $sql_ee;
	$result_ee	= $db->query($conn, $sql_ee);
	$count_ee1  = $db->counter($result_ee);
	if ($count_ee1 > 0) {
		$row_ee					= $db->fetch($result_ee);
		$store_id				=  $row_ee[0]['store_id'];
		$store_name				= $row_ee[0]['store_name'];
		$return_no				= $row_ee[0]['return_no']; 
		$return_status			= $row_ee[0]['return_status'];   
		$return_type			= $row_ee[0]['return_type'];   
		$removal_order_id		= $row_ee[0]['removal_order_id'];   
		$return_date			= $row_ee[0]['return_date'];   
		$internal_note			= $row_ee[0]['internal_note'];   
		$public_note			= $row_ee[0]['public_note']; 
		$stage_status    		= $row_ee[0]['stage_status'];  
		
		$return_date			= str_replace("-", "/", convert_date_display($row_ee[0]['return_date']));
	}

	$return_qty				= [];
	$expected_status		= [];
	$product_ids			= [];

	$sql_ee1	= "SELECT a.* FROM return_items_detail a WHERE a.return_id = '" . $id . "' ";
	$result_ee1	= $db->query($conn, $sql_ee1);
	$count_ee1  = $db->counter($result_ee1);
	if ($count_ee1 > 0) {
		$row_ee1	= $db->fetch($result_ee1);

		foreach ($row_ee1 as $data2) {
			$return_qty[]			= $data2['return_qty'];
			$product_ids[]			= $data2['product_id'];
			$expected_status[]		= $data2['expected_status'];
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
	$field_name = "store_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "return_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	$field_name = "return_type";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	$field_name = "removal_order_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		$return_date1 = NULL;
		if (isset($return_date) && $return_date != "") {
			$return_date1 = convert_date_mysql_slash($return_date);
		} 
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM `returns` a 
								WHERE a.store_id	= '" . $store_id . "'
								AND a.return_date	= '" . $return_date1 . "' 
								AND a.removal_order_id	= '" . $removal_order_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".returns(subscriber_users_id, store_id,  return_date  ,  return_type , removal_order_id,  add_date, add_by, add_by_user_id, add_ip, add_timezone)
							 VALUES('" . $subscriber_users_id . "', '" . $store_id . "','" . $return_date1  . "' ,'" . $return_type  . "' ,'" . $removal_order_id  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id				= mysqli_insert_id($conn);
						$return_no			= "RO" . $id;
						$return_status	= 1;

						$sql6 = " UPDATE `returns` SET return_no = '" . $return_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						$msg['msg_success'] = "Return Order has been created successfully.";
						$cmd = 'edit';
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
				}
			}
		}
	}
}

if (isset($is_Submit2) && $is_Submit2 == 'Y') {

	$field_name = "return_type";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "removal_order_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	$field_name = "return_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	
	$field_name = "store_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		$return_date1 = NULL;
		if (isset($return_date) && $return_date != "") {
			$return_date1 = convert_date_mysql_slash($return_date);
		} 
	  	$sql_c_up = "UPDATE returns SET	        store_id					= '" . $store_id . "',
												return_date					= '" . $return_date1 . "',
												removal_order_id			= '" . $removal_order_id . "', 
												return_type					= '" . $return_type . "',  
												internal_note					= '" . $internal_note . "',  
												public_note					= '" . $public_note . "',  

  												update_date					= '" . $add_date . "',
												update_by					= '" . $_SESSION['username'] . "',
												update_by_user_id			= '" . $_SESSION['user_id'] . "',
												update_ip					= '" . $add_ip . "',
												update_timezone				= '" . $timezone . "'
					WHERE id = '" . $id . "' "; 
		$ok = $db->query($conn, $sql_c_up);
		$k = 0;
		if (isset($stage_status) && $stage_status != "Committed") {
			
			$filtered_product_ids = array_values(array_filter($product_ids));
			$current_ids = implode(',', $filtered_product_ids);
			if($current_ids !=""){
				$sql_dup1 = "UPDATE return_items_detail SET enabled = 0 
							WHERE return_id	= '" . $id . "' 
							AND product_id NOT IN(" . $current_ids . ") ";
				$db->query($conn, $sql_dup1);
			}

			$i = 0; // Initialize the counter before the loop
			$r = 1;
			foreach ($filtered_product_ids as $data_p) {

				$sql_dup 	= "SELECT a.* FROM return_items_detail  a WHERE a.return_id = '" . $id . "' AND a.product_id = '" . $data_p . "'";
				$result_dup = $db->query($conn, $sql_dup);
				$count_dup 	= $db->counter($result_dup);

				if ($count_dup == 0) {
					// Check if all required array elements exist
					$sql6 = "INSERT INTO " . $selected_db_name . ".return_items_detail (return_id, product_id, return_qty,  expected_status , add_date, add_by, add_by_user_id, add_ip, add_timezone) 
							 VALUES ('" . $id . "', '" . $data_p . "', '" . $return_qty[$i] . "',  '" . $expected_status[$i] . "','" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$k++; // Increment the counter only if the insertion is successful
					}
					$i++;
				} else {
					$sql_c_up = "UPDATE  return_items_detail SET 
																return_qty     		= '" . $return_qty[$i] . "',
																expected_status		= '" . $expected_status[$i] . "',
																enabled 			= 1,
																
																update_timezone	= '" . $timezone . "',
																update_date		= '" . $add_date . "',
																update_by		= '" . $_SESSION['username'] . "',
																update_ip		= '" . $add_ip . "'
								WHERE return_id = '" . $id . "'  AND product_id = '" . $data_p . "' ";
					$db->query($conn, $sql_c_up);

					$product_ids[$i] 			= "";
					$expected_status[$i] 		= "";
					$return_qty[$i]				= "";
					$i++;
				}
			} 
		} 
		if ($k == 1) {
			if (isset($error2['msg'])) unset($error2['msg']);
			$msg2['msg_success'] = "Record has been added successfully.";
		} else {
			if (isset($error2['msg'])) unset($error2['msg']);
			$msg2['msg_success'] = "Record has been added successfully.";
		}
	}
}

if (isset($_POST['is_Submit_tab2']) && $_POST['is_Submit_tab2'] == 'Y') {
	if (empty($error2)) {
		$return_status =  $logistic_status_dynamic;
	}
}
if (isset($cmd2_1) && $cmd2_1 == 'delete' && isset($detail_id)) {
	$return_status =  $before_logistic_status_dynamic;
}

if (isset($_POST['is_Submit_tab2_1']) && $_POST['is_Submit_tab2_1'] == 'Y') {
	if (empty($error2)) {
		$return_status =  $logistic_status_dynamic;
	}
}
if (isset($_POST['is_Submit_tab2_3']) && $_POST['is_Submit_tab2_3'] == 'Y') {
	if (empty($error2)) {
		if ($logistics_status != "") {
			$return_status =  $logistics_status;
		}
	}
}
if (isset($_POST['is_Submit_tab5']) && $_POST['is_Submit_tab5'] == 'Y') {
	if (empty($error5)) {
		$return_status =  $receive_status_dynamic;
	}
}
if (isset($_POST['is_Submit_tab5_2']) && $_POST['is_Submit_tab5_2'] == 'Y') {
	if (empty($error5)) {
		$return_status =  $receive_status_dynamic;
	}
}
