<?php
if (isset($cmd) && $cmd == 'edit' && isset($id)) {
	$button_edu = "Edit";
	$sql_ee 	= " SELECT a.* FROM purchase_orders a WHERE a.id = '" . $id . "' ";
	//echo $sql_ee;
	$result_ee 			= $db->query($conn, $sql_ee);
	$counter_ee1		= $db->counter($result_ee);
	if ($counter_ee1 > 0) {
		$row_ee					= $db->fetch($result_ee);
		$po_no					= $row_ee[0]['po_no'];
		$sub_user_id			= $row_ee[0]['sub_user_id'];
		$vender_id				= $row_ee[0]['vender_id'];
		$order_status			= $row_ee[0]['order_status'];
		$po_desc				= $row_ee[0]['po_desc'];
		$vender_invoice_no		= $row_ee[0]['vender_invoice_no'];
		$po_date				= str_replace("-", "/", convert_date_display($row_ee[0]['po_date']));
		$estimated_receive_date	= str_replace("-", "/", convert_date_display($row_ee[0]['estimated_receive_date']));
	} else {
		$error['msg'] = "No record found";
	}
}


if (isset($_POST['is_Submit']) && $_POST['is_Submit'] == 'Y') {
	extract($_POST);
	if (isset($sub_user_id) && $sub_user_id == "") {
		$error['sub_user_id'] = "Required";
	}
	if (empty($error)) {
		if (po_permisions("PO Detail") == 0) {
			$error['msg'] = "You do not have edit permissions.";
		} else {
			$sql_c_up = "UPDATE  purchase_orders 
										SET 
											sub_user_id 			= '" . $sub_user_id . "',
											
											update_timezone			= '" . $timezone . "',
											update_date				= '" . $add_date . "',
											update_by				= '" . $_SESSION['username'] . "',
											update_by_user_id		= '" . $_SESSION['user_id'] . "',
											update_ip				= '" . $add_ip . "'
						WHERE id = '" . $id . "' ";
			$ok = $db->query($conn, $sql_c_up);
			if ($ok) {
				$msg['msg_success'] = "Record has been updated successfully.";
			} else {
				$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
			}
		}
	}
}

if (isset($_POST['is_Submit_tab2']) && $_POST['is_Submit_tab2'] == 'Y') {
	if (empty($error2)) {
		$order_status =  $logistic_status_dynamic;
	}
}
if (isset($cmd2_1) && $cmd2_1 == 'delete' && isset($detail_id)) {
	$order_status =  $before_logistic_status_dynamic;
}

if (isset($_POST['is_Submit_tab2_1']) && $_POST['is_Submit_tab2_1'] == 'Y') {
	if (empty($error2)) {
		$order_status =  $logistic_status_dynamic;
	}
}
if (isset($_POST['is_Submit_tab2_3']) && $_POST['is_Submit_tab2_3'] == 'Y') {
	if (empty($error2)) {
		$order_status =  $logistics_status;
	}
}
