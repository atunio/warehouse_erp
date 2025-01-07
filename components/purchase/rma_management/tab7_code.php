<?php

if ($_SERVER['HTTP_HOST'] == 'localhost' && $test_on_local == 1) {
}

if (isset($cmd7) && $cmd7 == 'delete' && isset($detail_id)) {
	// if (po_permisions("Diagnostic") == 0) {
	// 	$error7['msg'] = "You do not have add permissions.";
	// } else {
	// 	$sql_c_up = "DELETE FROM  purchase_order_detail_receive  WHERE id = '" . $detail_id . "' ";
	// 	$ok = $db->query($conn, $sql_c_up);
	// 	if ($ok) {
	// 		$msg7['msg_success'] = "Record has been deleted successfully.";
	// 	}
	// }
}
if (isset($_POST['is_Submit_tab7_7']) && $_POST['is_Submit_tab7_7'] == 'Y') {
	extract($_POST);
	if (!isset($ids_for_rma) || (isset($ids_for_rma) && sizeof($ids_for_rma) == 0)) {
		$error7['msg'] = "Select atleast one record to delete";
	}
	if (isset($status_id_update_rma) && $status_id_update_rma == "") {
		$error7['status_id_update_rma'] = "Required";
	}
	if (empty($error7)) {
		if (po_permisions("RMA Process") == 0) {
			$error7['msg'] = "You do not have add permissions.";
		} else {
			$k = 0;
			foreach ($ids_for_rma as $id_for_rma) {
				$sql_pd1	= "	SELECT a.*
								FROM purchase_order_detail_receive_rma a
  								WHERE a.receive_id = '" . $id_for_rma . "' ";
				$result_pd1	= $db->query($conn, $sql_pd1);
				$count_pd1	= $db->counter($result_pd1);
				if ($count_pd1 == 0) {
					$sql6 = "INSERT INTO purchase_order_detail_receive_rma(receive_id, status_id, add_by_user_id, add_date,  add_by, add_ip, add_timezone)
							VALUES('" . $id_for_rma . "', '" . $status_id_update_rma . "', '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
					// echo "<br><br>" . $sql6;
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$sql_c_up 	= "UPDATE purchase_order_detail_receive SET is_rma_processed = '1' WHERE id = '" . $id_for_rma . "' ";
						$db->query($conn, $sql_c_up);
						$k++;
					}
				}
			}
			if ($k > 0) {
				if ($k == 1) {
					$msg7['msg_success'] = $k . " record has been mvoe to RMA successfully.";
				} else {
					$msg7['msg_success'] = $k . " records have been mvoe to RMA successfully.";
				}
			}
		}
	} else {
		if (!isset($error7['msg'])) {
			$error7['msg'] = "Please select all required fields";
		}
	}
}
