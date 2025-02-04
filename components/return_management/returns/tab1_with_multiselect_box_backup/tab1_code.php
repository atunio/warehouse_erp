<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
} 
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$vender_id					= "1";
	$po_date 					= date('d/m/Y');
	$po_desc					= "purchase order desc : " . date('YmdHis');
	$is_tested_po				= "Yes";
	$is_wiped_po				= "Yes";
	$is_imaged_po				= "Yes";
	$vender_invoice_no			= date('YmdHis'); 
	$return_status = 1;
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
	$sql_ee		= " SELECT a.* 
					FROM purchase_orders a  
 					WHERE a.id = '" . $id . "'"; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$vender_id				=  $row_ee[0]['vender_id'];
	$po_desc				= $row_ee[0]['po_desc'];
	$po_no					= $row_ee[0]['po_no'];
	$po_desc_public			= $row_ee[0]['po_desc_public'];
	$vender_invoice_no		= $row_ee[0]['vender_invoice_no'];
	$is_tested_po			= $row_ee[0]['is_tested_po'];
	$is_wiped_po			= $row_ee[0]['is_wiped_po'];
	$is_imaged_po			= $row_ee[0]['is_imaged_po'];
 	$return_status           = $row_ee[0]['return_status']; 
	$po_date				= str_replace("-", "/", convert_date_display($row_ee[0]['po_date']));
 
	$product_condition 		= [];
	$order_price 			= [];
	$order_qty 				= [];
	$product_ids			= [];
	$is_tested 				= [];
	$is_wiped 				= [];
	$is_imaged 				= [];

	$sql_ee1	= "SELECT a.* FROM purchase_order_detail a WHERE a.po_id = '" . $id . "' ";
	$result_ee1	= $db->query($conn, $sql_ee1);
	$count_ee1  = $db->counter($result_ee1);
	if($count_ee1 > 0){
		$row_ee1	= $db->fetch($result_ee1);
		
		foreach($row_ee1 as $data2){
			$product_condition[]	= $data2['product_condition'];
			$order_price[]			= $data2['order_price'];
			$order_qty[]			= $data2['order_qty'];
			$product_ids[]			= $data2['product_id'];
			$is_tested[]			= $data2['is_tested'];
			$is_wiped[]				= $data2['is_wiped'];
			$is_imaged[]			= $data2['is_imaged'];
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
	$field_name = "vender_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "po_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "is_tested_po";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "is_wiped_po";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "is_imaged_po";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	} 
	if (empty($error)) {
		$po_date1 = "0000-00-00";
		if (isset($po_date) && $po_date != "") {
			$po_date1 = convert_date_mysql_slash($po_date);
		}
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM purchase_orders a 
								WHERE a.vender_id		= '" . $vender_id . "'
								AND a.vender_invoice_no	= '" . $vender_invoice_no . "' 
								AND a.po_date			= '" . $po_date1 . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_orders(subscriber_users_id, vender_id, vender_invoice_no, po_date, is_tested_po,  is_wiped_po, is_imaged_po, add_date, add_by, add_by_user_id, add_ip, add_timezone)
							 VALUES('" . $subscriber_users_id . "', '" . $vender_id . "', '" . $vender_invoice_no . "', '" . $po_date1  . "', '" . $is_tested_po  . "', '" . $is_wiped_po  . "', '" . $is_imaged_po  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id			= mysqli_insert_id($conn);
						$po_no		= "PO" . $id;

						$sql6		= " UPDATE purchase_orders SET po_no = '" . $po_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						$msg['msg_success'] = "Purchase Order has been created successfully.";
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
	
	$field_name = "vender_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "po_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "is_tested_po";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "is_wiped_po";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "is_imaged_po";
	if (!isset(${$field_name}) || (isset(${$field_name}) && ${$field_name} == "")) {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	} 

	if (empty($error)) { 
		$po_date1 = NULL;
		if (isset($po_date) && $po_date != "") {
			$po_date1 = convert_date_mysql_slash($po_date);
		} 
		$sql_c_up = "UPDATE purchase_orders SET	vender_id				= '" . $vender_id . "',
												po_date					= '" . $po_date1 . "',
 												po_desc					= '" . $po_desc . "', 
												po_desc_public			= '" . $po_desc_public . "', 
												vender_invoice_no		= '" . $vender_invoice_no . "', 
												is_tested_po			= '" . $is_tested_po . "', 
												is_wiped_po				= '" . $is_wiped_po . "', 
												is_imaged_po			= '" . $is_imaged_po . "', 
 												update_date				= '" . $add_date . "',
												update_by				= '" . $_SESSION['username'] . "',
												update_by_user_id		= '" . $_SESSION['user_id'] . "',
												update_ip				= '" . $add_ip . "',
												update_timezone			= '" . $timezone . "'
					WHERE id = '" . $id . "' ";
		$ok = $db->query($conn, $sql_c_up);
		$k = 0;
		if(isset($return_status) && $return_status == 1){
			$sql_dup = " DELETE FROM purchase_order_detail WHERE po_id	= '" . $id . "'";
			$db->query($conn, $sql_dup);

			$filtered_product_ids = array_values(array_filter($product_ids));
			$i = 0; // Initialize the counter before the loop
			$r = 1;
			foreach ($filtered_product_ids as $data_p) {
				$is_tested_val = "No";
				$is_imaged_val = "No";
				$is_wiped_val = "No";
				if (isset(${"isimage_iswipped_istested_".$r} ) && is_array(${"isimage_iswipped_istested_".$r})) {
					// Initialize flags
					foreach (${"isimage_iswipped_istested_".$r} as $datas) {
						if ($datas == "Tested") {
							$is_tested_val = "Yes";
						} 
						if ($datas == "Imaged") {
							$is_imaged_val = "Yes";
						} 
						if ($datas == "Wipped") {
							$is_wiped_val = "Yes";
						}
					}
					// Set the arrays with the final values
					$is_tested[$i] = $is_tested_val;
					$is_imaged[$i] = $is_imaged_val;
					$is_wiped[$i] = $is_wiped_val;
				}
				$r++;
				
				$sql_dup 	= "SELECT a.* FROM purchase_order_detail a WHERE a.po_id = '" . $id . "' AND a.product_id = '" . $data_p . "'";
				$result_dup = $db->query($conn, $sql_dup);
				$count_dup 	= $db->counter($result_dup);

				if ($count_dup == 0) {
					// Check if all required array elements exist
					$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_order_detail (po_id, product_id, order_qty, order_price, product_condition, is_tested, is_wiped, is_imaged, add_date, add_by, add_by_user_id, add_ip, add_timezone) 
							 VALUES ('" . $id . "', '" . $data_p . "', '" . $order_qty[$i] . "', '" . $order_price[$i] . "', '" . $product_condition[$i] . "', '". $is_tested_val ."' , '". $is_wiped_val ."' , '". $is_imaged_val ."' ,'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$k++; // Increment the counter only if the insertion is successful
					}
					$i++;
				} else {
					$product_ids[$i] 			= "";
					$product_condition[$i] 		= "";
					$order_price[$i] 			= "";
					$order_qty[$i] 				= "";
					$is_tested[$i] 				= "";
					$is_wiped[$i] 				= "";
					$is_imaged[$i] 				= ""; 
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
		$return_status =  $logistics_status;
	}
}
