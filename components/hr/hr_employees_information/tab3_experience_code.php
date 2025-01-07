<?php

//Query for employee experience
if (isset($is_Submit_experience) && $is_Submit_experience == 'Y') {
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if (isset($e_job_title) && $e_job_title == "") {
		$error['msg'] = "Enter Job Title";
		$e_job_title_valid = "invalid";
	}
	if (isset($e_job_role) && $e_job_role == "") {
		$error['msg'] = "Enter Job Role";
		$e_job_role_valid = "invalid";
	}
	if (!isset($id) || (isset($id)  && ($id == "0" || $id == ""))) {
		$error['msg'] = "Please add employee information first";
	}
	if (isset($e_date_from) && $e_date_from == "") {
		$error['msg'] = "Enter Appointment Date";
		$e_date_from_valid = "invalid";
	} else {
		$e_date_from1 	= "0000-00-00";
		$e_date_from1 	= convert_date_mysql_slash($e_date_from);
	}
	if (isset($e_date_to) && $e_date_to == "") {
		$error['msg'] = "Enter Termination Date";
		$e_date_to_valid = "invalid";
	} else {
		$e_date_to1 	= "0000-00-00";
		$e_date_to1 	= convert_date_mysql_slash($e_date_to);
	}
	if (isset($e_company) && $e_company == "") {
		$error['msg'] = "Enter Company Name";
		$e_company_valid = "invalid";
	}
	if (isset($e_job_description) && $e_job_description == "") {
		$error['msg'] = "Enter Jb Description";
		$e_job_description_valid = "invalid";
	}
	if (empty($error)) {
		// check_id($db, $conn, $id, "employee_profile", $subscriber_users_id, $selected_db_name);
		if ($cmd3 == 'add') {
			$sql_ee1 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_experience a 
									WHERE a.subscriber_users_id 			= '" . $subscriber_users_id . "'   
									AND a.emp_profile_id				= '" . $id . "'    
									AND a.e_job_title 					= '" . $e_job_title . "' 
									AND a.e_company 					= '" . $e_company . "' ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql = "INSERT INTO " . $selected_db_name . ".employee_experience(subscriber_users_id, emp_profile_id, e_job_title, e_job_role, e_company, e_job_description, e_date_from, e_date_to, add_date, add_by, add_ip)
						VALUES('" . $subscriber_users_id . "', '" . $id . "', '" . $e_job_title . "', '" . $e_job_role . "', '" . $e_company . "', '" . $e_job_description . "', '" . $e_date_from1 . "', '" . $e_date_to1 . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				//echo $sql;
				$ok = $db->query($conn, $sql);
				if ($ok) {
					$e_job_title = $e_job_role = $e_company = $e_job_description = $e_date_from = $e_date_to = "";
					$msg['msg_success'] = "Experience record  has been added successfully.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This record already exist.";
			}
		} else if ($cmd3 == 'edit') {
			check_id($db, $conn, $detail_id, "employee_experience", $subscriber_users_id, $selected_db_name);
			$sql_ee1 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_experience a 
									WHERE a.subscriber_users_id 	= '" . $subscriber_users_id . "' 
									AND a.e_job_title 			= '" . $e_job_title . "'
									AND a.e_job_role 			= '" . $e_job_role . "' 
									AND a.e_date_from			= '" . $e_date_from1 . "' 
									AND a.e_date_to 			= '" . $e_date_to1 . "' 
									AND a.e_company 			= '" . $e_company . "' 
									AND a.e_job_description 	= '" . $e_job_description . "'
									AND a.id 				   != '" . $detail_id . "' ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql_c_up = "UPDATE " . $selected_db_name . ".employee_experience SET 	e_job_title				= '" . $e_job_title . "',
																					e_job_role				= '" . $e_job_role . "',				
																					e_date_from				= '" . $e_date_from1 . "',
																					e_date_to				= '" . $e_date_to1 . "',
																					e_company				= '" . $e_company . "',
																					e_job_description		= '" . $e_job_description . "',			
																					update_date 			= '" . $add_date . "',
																					update_by 	 			= '" . $_SESSION['username'] . "',
																					update_ip 	 			= '" . $add_ip . "'
							WHERE id = '" . $detail_id . "' AND subscriber_users_id = '" . $subscriber_users_id . "' ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$button_exp = "Edit";
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "There is Error, record did not update, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "Employee record already exist.";
			}
		}
	}
} else if (isset($cmd3) && $cmd3 == 'delete' && isset($detail_id)) {
	$sql_del 			= "	DELETE FROM " . $selected_db_name . ".employee_experience WHERE id = '" . $detail_id . "' ";
	$ok = $db->query($conn, $sql_del);
	if ($ok) {
		$cmd3 = "add";
		$error['msg'] = "Record Deleted Successfully";
	} else {
		$error['msg'] = "There is Error, record did not delete, Please check it again OR contact Support Team.";
	}
} else if (isset($cmd3) && $cmd3 == 'edit' && isset($detail_id)) {
	$button_exp 			= "Edit";
	$sql_ee 				= "	SELECT a.* FROM " . $selected_db_name . ".employee_experience a 
								WHERE a.id = '" . $detail_id . "' AND a.subscriber_users_id = '" . $subscriber_users_id . "' ";
	$result_ee 				= $db->query($conn, $sql_ee);
	$row_ee 				= $db->fetch($result_ee);
	$e_job_title 			=  $row_ee[0]['e_job_title'];
	$e_job_role 			=  $row_ee[0]['e_job_role'];
	$e_date_from 			=  $row_ee[0]['e_date_from'];
	$e_date_to 				=  $row_ee[0]['e_date_to'];
	$e_company 				=  $row_ee[0]['e_company'];
	$e_job_description 		=  $row_ee[0]['e_job_description'];
	$e_date_from			= str_replace("-", "/", convert_date_display($row_ee[0]['e_date_from']));
	$e_date_to				= str_replace("-", "/", convert_date_display($row_ee[0]['e_date_to']));
}
