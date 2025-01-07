<?php

//Query for Employee's Profile
if (isset($is_submit_profile) && $is_submit_profile == 'Y') {

	if (is_array($_FILES) && isset($_FILES["e_profile_pic"]["name"]) && $_FILES["e_profile_pic"]["name"] != "") {
		$picture_uniq_id 			= $_SESSION['user_id'] . "_" . uniqid();
		$temp 						= explode(".", $_FILES["e_profile_pic"]["name"]);
		$extension 					= end($temp);
		$file_type 					= $_FILES['e_profile_pic']['type'];
		$profile_pic_file_name		= $_FILES['e_profile_pic']['name'];
		$file_tmp  					= $_FILES['e_profile_pic']['tmp_name'];
		$valid_formats				= array("image/JPEG", "image/jpeg", "image/JPG", "image/jpg", "image/PNG", "image/png", "image/gif", "image/GIF"); //add the formats you want to upload
		$mime          				= mime_content_type($file_tmp);
		if ($profile_pic_file_name != "") {
			if (!in_array($file_type, $valid_formats) && !in_array($mime, $valid_formats)) {
				$error['msg'] = "Invalid Profile Picture format, Please choose only JPG, PNG or GIF  Picture";
			} else {
				$sourcePath				= $file_tmp;
				$profile_pic_file_name	= $picture_uniq_id . "." . $extension;
				$targetPath 			= "app-assets/images/logo/" . $profile_pic_file_name;
				move_uploaded_file($sourcePath, $targetPath);
			}
		}
	} else if (isset($cmd) && $cmd == 'edit') {
		$profile_pic_file_name = $old_profile_pic_file_name;
	}
	if (is_array($_FILES) && isset($_FILES["e_resume_upload"]["name"]) && $_FILES["e_resume_upload"]["name"] != "") {
		$picture_uniq_id 			= $_SESSION['user_id'] . "_" . uniqid();
		$temp 						= explode(".", $_FILES["e_resume_upload"]["name"]);
		$extension 					= end($temp);
		$file_type 					= $_FILES['e_resume_upload']['type'];
		$resume_file_file_name		= $_FILES['e_resume_upload']['name'];
		$file_tmp  					= $_FILES['e_resume_upload']['tmp_name'];
		$valid_formats				= array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"); //add the formats you want to upload
		$mime          				= mime_content_type($file_tmp);
		if ($resume_file_file_name != "") {
			if (!in_array($file_type, $valid_formats) && !in_array($mime, $valid_formats)) {
				$error['msg'] = "Invalid File Format, Please choose only PDF, DOC Word File.";
				$resume_file_file_name = "";
			} else {
				$sourcePath				= $file_tmp;
				$resume_file_file_name	= $picture_uniq_id . "." . $extension;
				$targetPath 			= "app-assets/employee_resumes/" . $resume_file_file_name;
				move_uploaded_file($sourcePath, $targetPath);
			}
		}
	} else {
		$resume_file_file_name = $old_resume_file_file_name;
	}

	if ($cmd == 'add') {

		$sql_ee1		= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
								WHERE a.subscriber_users_id = '" . $subscriber_users_id . "' 
								AND a.e_email = '" . $e_email . "' ";
		$result_ee1		= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 > 0) {
			$error['e_email'] = "The Email is already exist.";
		}
		$sql_ee1		= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
								WHERE a.subscriber_users_id = '" . $subscriber_users_id . "' 
								AND a.e_national_id_no = '" . $e_national_id_no . "'  ";
		$result_ee1		= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 > 0) {
			$error['msg'] = "The National ID is already exist.";
		}
		$sql_ee1		= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
								WHERE a.subscriber_users_id = '" . $subscriber_users_id . "' 
								AND a.emp_code 			= '" . $emp_code . "'  ";
		$result_ee1		= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 > 0) {
			$error['msg'] = "The Employment Code is already exist.";
		}
		$sql_ee1		= "	SELECT a.* FROM " . $selected_db_name . ".users a 
								WHERE a.subscriber_users_id = '" . $subscriber_users_id . "' 
								AND a.username 			= '" . $username . "' ";
		$result_ee1		= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 > 0) {
			$error['msg'] = "The Username is already exist.";
		}
		$sql_ee1		= "	SELECT * FROM " . $selected_db_name . ".users a
 							WHERE a.subscriber_users_id = '" . $subscriber_users_id . "' 
							AND a.email					= '" . $e_email . "'  ";
		$result_ee1		= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 > 0) {
			$error['msg'] = "The Email is is already exist in another user.";
		}
	} else if ($cmd == 'edit') {

		$sql_ee1		= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
								WHERE a.subscriber_users_id = '" . $subscriber_users_id . "' 
								AND a.e_email = '" . $e_email . "'
								AND a.id	 != '" . $id . "' ";
		$result_ee1		= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 > 0) {
			$error['e_email'] = "The Email is already exist.";
		}
		$sql_ee1		= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
								WHERE a.subscriber_users_id = '" . $subscriber_users_id . "' 
								AND a.e_national_id_no 	= '" . $e_national_id_no . "'
								AND a.id	 			!= '" . $id . "' ";
		$result_ee1		= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 > 0) {
			$error['msg'] = "The National ID is already exist.";
		}
		$sql_ee1		= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
								WHERE a.subscriber_users_id = '" . $subscriber_users_id . "' 
								AND a.emp_code 			= '" . $emp_code . "'
								AND a.id	 		   != '" . $id . "' ";
		$result_ee1		= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 > 0) {
			$error['msg'] = "The Employment Code is already exist.";
		}
		$sql_ee1		= "	SELECT * FROM " . $selected_db_name . ".users a
							INNER JOIN employee_profile b ON b.user_id = a.id  
							WHERE a.subscriber_users_id = '" . $subscriber_users_id . "' 
							AND a.email 			= '" . $e_email . "'
							AND b.id	 		   != '" . $id . "' ";
		$result_ee1		= $db->query($conn, $sql_ee1);
		$counter_ee1	= $db->counter($result_ee1);
		if ($counter_ee1 > 0) {
			$error['msg'] = "The Email is is already exist in another user.";
		}
	}

	$profile_pic_file_name = "";
	$resume_file_file_name = "";
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if (isset($user_type) && $user_type == "" && $cmd == "add") {
		$error['user_type'] = "Required";
		$user_type_valid 	= "invalid";
	}
	if (isset($hourly_rate) && ($hourly_rate == "" ||  $hourly_rate == "0")) {
		$error['hourly_rate'] 	= "Required";
		$hourly_rate_valid 		= "invalid";
	}
	if (isset($username) && $username == "") {
		$error['username'] = "Required";
		$username_valid 	= "invalid";
	}
	if (isset($u_password) && $u_password == "") {
		$error['u_password'] = "Required";
		$u_password_valid 	= "invalid";
	}
	if (isset($emp_code) && $emp_code == "") {
		$error['emp_code'] = "Required";
		$emp_code_valid 	= "invalid";
	}
	if (isset($parent_name) && $parent_name == "") {
		$error['parent_name']	= "Required ";
		$parent_name_valid		= "invalid";
	}
	if (isset($e_gender) && $e_gender == "") {
		$error['e_gender'] = "Required";
		$e_gender_valid		= "invalid";
	}
	if (isset($e_birth_date) && $e_birth_date == "") {
		$error['e_birth_date'] 	= "Required ";
		$e_birth_date_valid		= "invalid";
	} else {
		$e_birth_date1 	= "0000-00-00";
		$e_birth_date1 	= convert_date_mysql_slash($e_birth_date);
	}
	if (isset($e_marital_status) && $e_marital_status == "") {
		$error['e_marital_status'] 	= "Required ";
		$e_marital_status_valid 	= "invalid";
	}
	if (isset($e_phone) && $e_phone == "") {
		$error['e_phone'] 	= "Required";
		$e_phone_valid		= "invalid";
	}
	if (isset($e_email) && $e_email == "") {
		$error['e_email'] 	= "Required";
		$e_email_valid		= "invalid";
	}
	if (isset($e_national_id_no) && $e_national_id_no == "") {
		$error['e_national_id_no'] 	= "Required";
		$e_national_id_no_valid 	= "invalid";
	}
	if (isset($e_joining_date) && $e_joining_date == "") {
		$error['e_joining_date']	= "Required";
		$e_joining_date_valid 		= "invalid";
	} else {
		$e_joining_date1 	= "0000-00-00";
		$e_joining_date1 	= convert_date_mysql_slash($e_joining_date);
	}
	$e_exit_date1 	= "0000-00-00";
	if (isset($e_exit_date) && $e_exit_date == "") {;
	} else {
		$e_exit_date1 = convert_date_mysql_slash($e_exit_date);
	}
	if (isset($e_mailing_address) && $e_mailing_address == "") {
		$error['e_mailing_address'] = "Required";
		$e_mailing_address_valid 	= "invalid";
	}
	if (isset($e_mailing_city) && $e_mailing_city == "") {
		$error['e_mailing_city'] 	= "Required";
		$e_mailing_city_valid 		= "invalid";
	}
	if (isset($e_mailing_state) && $e_mailing_state == "") {
		$error['e_mailing_state']	= "Required";
		$e_mailing_state_valid 		= "invalid";
	}
	if (isset($e_mailing_country) && $e_mailing_country == "") {
		$error['e_mailing_country'] 	= "Required";
		$e_mailing_country_valid 		= "invalid";
	}
	$field_name = "last_name";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name]	= "Required";
		${$field_name . "_valid"}		= "invalid";
	}
	$field_name = "first_name";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name]	= "Required";
		${$field_name . "_valid"}		= "invalid";
	}
	if (empty($error)) {
		$e_full_name = $first_name . " " . $last_name;
		$user_sections_str = implode(",", $user_sections);
		if ($cmd == 'add') {
			$sql = "INSERT INTO " . $selected_db_name . ".employee_profile(subscriber_users_id, e_full_name, e_gender, e_birth_date, e_marital_status,
																		e_phone, e_email, e_national_id_no, e_joining_date, parent_name,  
																		e_mailing_address, e_mailing_city, e_mailing_state, e_mailing_country,
																		e_emergency_contact_name, e_emergency_contact_relationship, e_emergency_contact_phone,
																		e_emergency_contact_email, e_exit_date,  e_exit_reason, e_profile_pic, 
																		e_resume_upload,  emp_status, emp_code, hourly_rate,
																		add_date, add_by, add_by_user_id, add_ip)
				VALUES('" . $subscriber_users_id . "', '" . $e_full_name . "', '" . $e_gender . "', '" . $e_birth_date1 . "', '" . $e_marital_status . "', 
						'" . $e_phone . "', '" . $e_email . "', '" . $e_national_id_no . "', '" . $e_joining_date1 . "', '" . $parent_name . "', 
						'" . $e_mailing_address . "', '" . $e_mailing_city . "', '" . $e_mailing_state . "', '" . $e_mailing_country . "', 
						'" . $e_emergency_contact_name . "', '" . $e_emergency_contact_relationship . "', '" . $e_emergency_contact_phone . "', 
						'" . $e_emergency_contact_email . "', '" . $e_exit_date1 . "',  '" . $e_exit_reason . "', '" . $profile_pic_file_name . "', 
						'" . $resume_file_file_name . "',  '" . $emp_status . "', '" . $emp_code . "',  '" . $hourly_rate . "', 
							'" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "')";
			// echo $sql;
			$ok = $db->query($conn, $sql);
			if ($ok) {
				$cmd 			= "edit";
				$id 			= mysqli_insert_id($conn);
				$u_password_md5 = md5($u_password);

				$sql6 = "INSERT INTO " . $selected_db_name . ".users(subscriber_users_id, first_name, last_name, email, phone_no, date_of_birth, username, 
																						a_password,  user_type, gender, user_sections, add_date, add_by, add_ip)
								VALUES('" . $subscriber_users_id . "', '" . $first_name . "', '" . $last_name . "',  '" . $e_email . "', '" . $e_phone . "', '" . $e_birth_date1 . "', '" . $username . "', '" . $u_password . "', 
									'" . $user_type . "', '" . $e_gender . "', '" . $user_sections_str . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				$db->query($conn, $sql6);
				$emp_user_id = mysqli_insert_id($conn);

				$sql_c_up3 = "	UPDATE " . $selected_db_name . ".employee_profile 
										SET user_id = '" . $emp_user_id . "'
								WHERE id = '" . $id . "' 
								AND subscriber_users_id = '" . $subscriber_users_id . "' ";
				$db->query($conn, $sql_c_up3);
				$msg['msg_success'] = "Employee record  has been added successfully.";
			} else {
				$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
			}
		} else if ($cmd == 'edit') {
			$sql_c_up = "UPDATE " . $selected_db_name . ".employee_profile SET 	e_full_name							= '" . $e_full_name . "',
																				e_gender							= '" . $e_gender . "',
																				e_birth_date						= '" . $e_birth_date1 . "',
																				e_marital_status					= '" . $e_marital_status . "', 
																				e_phone								= '" . $e_phone . "',
																				e_email								= '" . $e_email . "',
																				e_national_id_no					= '" . $e_national_id_no . "',
																				e_joining_date						= '" . $e_joining_date1 . "',
																				parent_name							= '" . $parent_name . "',
																				e_mailing_address					= '" . $e_mailing_address . "',
																				e_mailing_city						= '" . $e_mailing_city . "',
																				e_mailing_state						= '" . $e_mailing_state . "',
																				e_mailing_country					= '" . $e_mailing_country . "',
																				e_emergency_contact_name			=	'" . $e_emergency_contact_name . "',
																				e_emergency_contact_relationship	= '" . $e_emergency_contact_relationship . "',
																				e_emergency_contact_phone			= '" . $e_emergency_contact_phone . "',
																				e_emergency_contact_email			= '" . $e_emergency_contact_email . "',
																				e_earn_leave						= '" . $e_earn_leave . "',
																				e_exit_date							= '" . $e_exit_date1 . "',
																				e_exit_reason						= '" . $e_exit_reason . "',
																				e_profile_pic						= '" . $profile_pic_file_name . "',
																				e_resume_upload						= '" . $resume_file_file_name . "', 
																				emp_status							= '" . $emp_status . "',
																				emp_code							= '" . $emp_code . "',
																				hourly_rate							= '" . $hourly_rate . "',
																				
																				update_date 						= '" . $add_date . "',
																				update_by 	 						= '" . $_SESSION['username'] . "',
																				update_ip 	 						= '" . $add_ip . "'
						WHERE id = '" . $id . "' AND subscriber_users_id = '" . $subscriber_users_id . "' ";
			$ok = $db->query($conn, $sql_c_up);
			if ($ok) {

				$sql_c_up = "UPDATE " . $selected_db_name . ".users a
							INNER JOIN employee_profile b ON b.user_id = a.id 
											SET 	a.first_name 		= '" . $first_name . "',
													a.last_name 		= '" . $last_name . "', 
													a.email				= '" . $e_email . "',
													user_sections		= '" . $user_sections_str . "',

													a.update_date			= '" . $add_date . "',
													a.update_by			= '" . $_SESSION['username'] . "',
													a.update_ip			= '" . $add_ip . "'
					WHERE b.id = '" . $id . "' 
					AND a.subscriber_users_id = '" . $subscriber_users_id . "' ";
				$db->query($conn, $sql_c_up);
				$msg['msg_success'] = "Record Updated Successfully.";
			} else {
				$error['msg'] = "There is Error, record did not update, Please check it again OR contact Support Team.";
			}
		}
	}
} else if ($cmd == 'edit' && isset($id)) {
	$sql_ee 								= " SELECT a.*, b.username, b.a_password, b.user_type, b.user_sections, b.first_name, b.last_name
												FROM " . $selected_db_name . ".employee_profile a 
												LEFT JOIN " . $selected_db_name . ".users b ON b.id = a.user_id
												WHERE a.id = '" . $id . "' 
												AND a.subscriber_users_id = '" . $subscriber_users_id . "'   "; //echo $sql_ee;
	$result_ee 								= $db->query($conn, $sql_ee);
	$counter1 								= $db->counter($result_ee);
	if ($counter1 > 0) {
		$row_ee 							= $db->fetch($result_ee);
		$first_name 						= $row_ee[0]['first_name'];
		$last_name 							= $row_ee[0]['last_name'];
		$e_full_name 						= $row_ee[0]['e_full_name'];
		$parent_name 						= $row_ee[0]['parent_name'];
		$e_gender 							= $row_ee[0]['e_gender'];
		$e_birth_date						= str_replace("-", "/", convert_date_display($row_ee[0]['e_birth_date']));
		$e_marital_status 					= $row_ee[0]['e_marital_status'];
		$e_phone 							= $row_ee[0]['e_phone'];
		$e_email 							= $row_ee[0]['e_email'];
		$e_national_id_no					= $row_ee[0]['e_national_id_no'];
		$e_joining_date						= str_replace("-", "/", convert_date_display($row_ee[0]['e_joining_date']));
		$e_mailing_address 					= $row_ee[0]['e_mailing_address'];
		$e_mailing_city 					= $row_ee[0]['e_mailing_city'];
		$e_mailing_state 					= $row_ee[0]['e_mailing_state'];
		$e_mailing_country 					= $row_ee[0]['e_mailing_country'];
		$e_emergency_contact_name 			= $row_ee[0]['e_emergency_contact_name'];
		$e_emergency_contact_relationship	= $row_ee[0]['e_emergency_contact_relationship'];
		$e_emergency_contact_phone 			= $row_ee[0]['e_emergency_contact_phone'];
		$e_emergency_contact_email 			= $row_ee[0]['e_emergency_contact_email'];
		$e_emergency_contact_email 			= $row_ee[0]['e_emergency_contact_email'];
		$e_earn_leave 						= $row_ee[0]['e_earn_leave'];
		$e_exit_date						= str_replace("-", "/", convert_date_display($row_ee[0]['e_exit_date']));
		$e_exit_reason 						= $row_ee[0]['e_exit_reason'];
		$resume_file_file_name 				= $row_ee[0]['e_resume_upload'];
		$profile_pic_file_name 				= $row_ee[0]['e_profile_pic'];
		$emp_status 						= $row_ee[0]['emp_status'];
		$emp_code 							= $row_ee[0]['emp_code'];
		$username 							= $row_ee[0]['username'];
		$u_password 						= $row_ee[0]['a_password'];
		$user_type 							= $row_ee[0]['user_type'];
		$hourly_rate						= $row_ee[0]['hourly_rate'];
		$user_sections						= explode(",", $row_ee[0]['user_sections']);
	}
}
