<?php
//Query for employee history
if (isset($is_Submit_history) && $is_Submit_history == 'Y') {
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if (isset($emp_history_entry_date) && $emp_history_entry_date == "") {
		$error['msg'] = "Select Entry Type";
		$entry_type_valid = "invalid";
	} else {
		$emp_history_entry_date1 	= "0000-00-00";
		$emp_history_entry_date1 	= convert_date_mysql_slash($emp_history_entry_date);
	}
	if (isset($entry_type) && $entry_type == "") {
		$error['msg'] = "Select Entry Type";
		$entry_type_valid       = "invalid";
	}
	if (isset($designation_id) && $designation_id == "") {
		$error['msg'] = "Select Designation";
		$designation_id_valid   = "invalid";
	}
	if (isset($dept_id) && $dept_id == "") {
		$error['msg'] = "Select Department";
		$dept_id_valid          = "invalid";
	}
	if (isset($scale_id) && $scale_id == "") {
		$error['msg'] = "Select Scale";
		$scale_id_valid         = "invalid";
	}
	if (isset($employment_type) && $employment_type == "") {
		$error['msg'] = "Select Employment Type";
		$employment_type_valid  = "invalid";
	}
	if (!isset($id) || (isset($id)  && ($id == "0" || $id == ""))) {
		$error['msg'] = "Please add employee information first";
	}

	if (empty($error)) {
		if ($increament_amount == "") $increament_amount = 0;
		if ($cmd4 == 'add') {
			$sql_ee1 			= "	SELECT a.* FROM " . $selected_db_name . ".hr_emp_employment_history a 
									WHERE a.subscriber_users_id = '" . $subscriber_users_id . "'   
									AND a.emp_id                    = '" . $id . "'    
									AND a.emp_history_entry_date    = '" . $emp_history_entry_date1 . "' 
									AND a.dept_id                   = '" . $dept_id . "' 
									AND a.entry_type                = '" . $entry_type . "' ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql = "INSERT INTO " . $selected_db_name . ".hr_emp_employment_history(subscriber_users_id, emp_id, designation_id, dept_id, scale_id, 
                                                                        employment_type, emp_history_entry_date, entry_type, increament_amount, add_date, add_by, add_ip)
						VALUES('" . $subscriber_users_id . "', '" . $id . "', '" . $designation_id . "', '" . $dept_id . "', '" . $scale_id . "', 
                                '" . $employment_type . "', '" . $emp_history_entry_date1 . "', '" . $entry_type . "',  '" . $increament_amount . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				// echo $sql; 
				$ok = $db->query($conn, $sql);
				if ($ok) {
					$msg['msg_success'] = "History record  has been added successfully.";
					$sql_c_up12 = "UPDATE " . $selected_db_name . ".employee_profile SET
														hourly_rate				= (hourly_rate-" . $increament_amount_old . ")+" . $increament_amount . ",
														update_date 			= '" . $add_date . "',
														update_by 	 			= '" . $_SESSION['username'] . "',
														update_ip 	 			= '" . $add_ip . "'
                            WHERE id = '" . $id . "' 
							AND subscriber_users_id = '" . $subscriber_users_id . "' ";
					$db->query($conn, $sql_c_up12);
					$designation_id = $dept_id = $scale_id = $employment_type = $emp_history_entry_date = $entry_type = $increament_amount = "";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This record already exist.";
			}
		} else if ($cmd4 == 'edit') {
			check_id($db, $conn, $detail_id, "hr_emp_employment_history", $subscriber_users_id, $selected_db_name);
			$sql_ee1		= "	SELECT a.* FROM " . $selected_db_name . ".hr_emp_employment_history a 
								WHERE a.subscriber_users_id		= '" . $subscriber_users_id . "' 
								AND a.emp_id                    = '" . $id . "'    
								AND a.emp_history_entry_date    = '" . $emp_history_entry_date1 . "' 
								AND a.dept_id                   = '" . $dept_id . "' 
								AND a.entry_type                = '" . $entry_type . "' 
								AND a.id 				       != '" . $detail_id . "' ";
			$result_ee1		= $db->query($conn, $sql_ee1);
			$counter_ee1	= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql_c_up = "UPDATE " . $selected_db_name . ".hr_emp_employment_history SET
                                                                                designation_id			= '" . $designation_id . "',
                                                                                dept_id					= '" . $dept_id . "',
                                                                                scale_id				= '" . $scale_id . "',
                                                                                employment_type			= '" . $employment_type . "',
                                                                                entry_type			    = '" . $entry_type . "',
                                                                                emp_history_entry_date	= '" . $emp_history_entry_date1 . "',
                                                                                increament_amount	    = '" . $increament_amount . "',
                                                                                update_date 			= '" . $add_date . "',
                                                                                update_by 	 			= '" . $_SESSION['username'] . "',
                                                                                update_ip 	 			= '" . $add_ip . "'
							WHERE id = '" . $detail_id . "' 
							AND subscriber_users_id = '" . $subscriber_users_id . "' ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$button_edu = "Edit";
					$msg['msg_success'] = "Record Updated Successfully.";
					$sql_c_up12 = " UPDATE " . $selected_db_name . ".employee_profile SET
                                                                hourly_rate				= (hourly_rate-" . $increament_amount_old . ")+" . $increament_amount . ",
                                                                update_date 			= '" . $add_date . "',
                                                                update_by 	 			= '" . $_SESSION['username'] . "',
                                                                update_ip 	 			= '" . $add_ip . "'
                                    WHERE id = '" . $id . "'
									AND subscriber_users_id = '" . $subscriber_users_id . "' ";
					$db->query($conn, $sql_c_up12);
				} else {
					$error['msg'] = "There is Error, record did not update, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "History record already exist.";
			}
		}
	}
} else if (isset($cmd4) && $cmd4 == 'delete' && isset($detail_id)) {
	$sql_ee2 				= "	SELECT a.* FROM " . $selected_db_name . ".hr_emp_employment_history a 
								WHERE a.id = '" . $detail_id . "' 
                                AND a.subscriber_users_id = '" . $subscriber_users_id . "' ";
	$result_ee2 			= $db->query($conn, $sql_ee2);
	$row_ee2 				= $db->fetch($result_ee2);
	$increament_amount2  	=  $row_ee2[0]['increament_amount'];
	$sql_del 			= "	DELETE FROM " . $selected_db_name . ".hr_emp_employment_history WHERE id = '" . $detail_id . "' ";
	$ok = $db->query($conn, $sql_del);
	if ($ok) {
		$cmd4           = "add";
		$error['msg']   = "Record Deleted Successfully";
		$sql_c_up12     = " UPDATE " . $selected_db_name . ".employee_profile SET
                                gross_salary			= (gross_salary-" . $increament_amount2 . "),
                                update_date 			= '" . $add_date . "',
                                update_by 	 			= '" . $_SESSION['username'] . "',
                                update_ip 	 			= '" . $add_ip . "'
                            WHERE id = '" . $id . "' AND subscriber_users_id = '" . $subscriber_users_id . "' ";
		$db->query($conn, $sql_c_up12);
	} else {
		$error['msg'] = "There is Error, record did not delete, Please check it again OR contact Support Team.";
	}
} else if (isset($cmd4) && $cmd4 == 'edit' && isset($detail_id)) {
	$button_edu 			= "Edit";
	$sql_ee 				= "	SELECT a.* FROM " . $selected_db_name . ".hr_emp_employment_history a 
								WHERE a.id = '" . $detail_id . "' AND a.subscriber_users_id = '" . $subscriber_users_id . "' ";
	$result_ee 				= $db->query($conn, $sql_ee);
	$row_ee 				= $db->fetch($result_ee);
	$emp_id 				=  $row_ee[0]['emp_id'];
	$designation_id 		=  $row_ee[0]['designation_id'];
	$dept_id 				=  $row_ee[0]['dept_id'];
	$scale_id 				=  $row_ee[0]['scale_id'];
	$employment_type 		=  $row_ee[0]['employment_type'];
	$entry_type 		    =  $row_ee[0]['entry_type'];
	$increament_amount	    =  $row_ee[0]['increament_amount'];
	$increament_amount_old   = $increament_amount;
	$emp_history_entry_date = str_replace("-", "/", convert_date_display($row_ee[0]['emp_history_entry_date']));
}
