<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$e_full_name 						= "e_full_name";
	$e_gender 							= "Male";
	$e_birth_date 						= "01/01/1990"; //
	$e_marital_status 					= "Single";
	$e_phone 							= "e_phone";
	$e_email 							= "aftabatunio@gmail.com";
	$e_national_id_no 					= "e_national_id_no";
	$e_joining_date 					= "01/01/2021";
	$parent_name	 					= "parent_name";
	$parent_phone 						= "01222222111";
	$e_mailing_address 					= "e_mailing_address";
	$e_mailing_city 					= "e_mailing_city";
	$e_mailing_state 					= "e_mailing_state";
	$e_mailing_country 					= "e_mailing_country";
	$e_emergency_contact_name 			= "e_emergency_contact_name";
	$e_emergency_contact_relationship 	= "Uncle";
	$e_emergency_contact_phone 			= "00001111111111";
	$e_emergency_contact_email 			= "e_emergency_contact_email";
	$e_earn_leave 						= "10";
	$e_casual_leave 					= "5";
	$e_sick_leave 						= "10";
	$e_exit_date 						= "";
	$e_exit_reason 						= "";
	$e_bank_name 						= "e_bank_name";
	$e_bank_account_name 				= "e_bank_account_name";
	$e_bank_account_number 				= "25522222";
	$e_bank_branch_location 			= "e_bank_branch_location";
	$e_exit_reason 						= "";
}
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 				= new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$school_admin_id 	= $_SESSION["school_admin_id"];
$user_id 	= $_SESSION["user_id"];
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
$button_edu = "Add";
$button_exp = "Add";
if (!isset($cmd2)) {
	$cmd2 = "add";
}
if (!isset($cmd3)) {
	$cmd3 = "add";
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
//Query for employee's education
if (isset($is_Submit_Education) && $is_Submit_Education == 'Y') {
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if (isset($e_institution_name) && $e_institution_name == "") {
		$error['msg'] = "Enter Employee school";
		$e_institution_name_valid = "invalid";
	}
	if (isset($date_from) && $date_from == "") {
		$error['msg'] = "Enter Employee entry year";
		$date_from_valid = "invalid";
	} else {
		$date_from1 	= "0000-00-00";
		$date_from1 	= convert_date_mysql_slash($date_from);
	}
	if (isset($date_to) && $date_to == "") {
		$error['msg'] = "Enter Employee graduation year ";
		$date_to_valid = "invalid";
	} else {
		$date_to1 	= "0000-00-00";
		$date_to1 	= convert_date_mysql_slash($date_to);
	}
	if (isset($degree_name) && $degree_name == "") {
		$error['msg'] = "Enter Employee degree";
		$degree_name_valid = "invalid";
	}
	if (isset($study_area) && $study_area == "") {
		$error['msg'] = "Enter Employee area of study";
		$study_area_valid = "invalid";
	}
	if (empty($error)) {
		check_id($db, $conn, $id, "employee_profile", $school_admin_id, $selected_db_name);
		if ($cmd2 == 'add') {
			$sql_ee1 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_education a 
									WHERE a.school_admin_id 	= '" . $school_admin_id . "'   
									AND a.emp_profile_id		= '" . $id . "'    
									AND a.degree_name			= '" . $degree_name . "' ";

			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql = "INSERT INTO " . $selected_db_name . ".employee_education(school_admin_id, emp_profile_id, e_institution_name, date_from, date_to, degree_name, study_area, add_date, add_by, add_ip)
						VALUES('" . $school_admin_id . "', '" . $id . "', '" . $e_institution_name . "', '" . $date_from1 . "', '" . $date_to1 . "', '" . $degree_name . "', '" . $study_area . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";

				$ok = $db->query($conn, $sql);
				if ($ok) {
					$e_institution_name = $date_from = $date_to = $degree_name = $study_area = "";
					$msg['msg_success'] = "Education record  has been added successfully.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This record already exist.";
			}
		} else if ($cmd2 == 'edit') {
			check_id($db, $conn, $detail_id, "employee_education", $school_admin_id, $selected_db_name);
			$sql_ee1 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_education a 
									WHERE a.school_admin_id 	= '" . $school_admin_id . "'   
									AND a.emp_profile_id		= '" . $id . "'    
									AND a.degree_name			= '" . $degree_name . "' 
									AND a.id 				   != '" . $detail_id . "'";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql_c_up = "UPDATE " . $selected_db_name . ".employee_education SET 	e_institution_name			= '" . $e_institution_name . "',
																					date_from					= '" . $date_from1 . "',
																					date_to						= '" . $date_to1 . "',
																					degree_name					= '" . $degree_name . "',
																					study_area					= '" . $study_area . "',			
																					update_date 				= '" . $add_date . "',
																					update_by 	 				= '" . $_SESSION['username'] . "',
																					update_ip 	 				= '" . $add_ip . "'
							WHERE id = '" . $detail_id . "' AND school_admin_id = '" . $school_admin_id . "' ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$button_edu = "Edit";
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "There is Error, record did not update, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This record already exist.";
			}
		}
	}
} else if (isset($cmd2) && $cmd2 == 'delete' && isset($detail_id)) {
	$sql_del 			= "	DELETE FROM " . $selected_db_name . ".employee_education WHERE id = '" . $detail_id . "' ";
	$ok = $db->query($conn, $sql_del);
	if ($ok) {
		$cmd2 = "add";
		$error['msg'] = "Record Deleted Successfully";
	} else {
		$error['msg'] = "There is Error, record did not delete, Please check it again OR contact Support Team.";
	}
} else if (isset($cmd2) && $cmd2 == 'edit' && isset($detail_id)) {
	$button_edu 		= "Edit";
	$sql_ee 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_education a 
							WHERE a.id = '" . $detail_id . "' AND a.school_admin_id = '" . $school_admin_id . "'   ";
	$result_ee 			= $db->query($conn, $sql_ee);
	$row_ee 			= $db->fetch($result_ee);
	$e_institution_name	=  $row_ee[0]['e_institution_name'];
	$date_from 			=  $row_ee[0]['date_from'];
	$date_to 			=  $row_ee[0]['date_to'];
	$degree_name 		=  $row_ee[0]['degree_name'];
	$study_area 		=  $row_ee[0]['study_area'];
	$date_from			= str_replace("-", "/", convert_date_display($row_ee[0]['date_from']));
	$date_to			= str_replace("-", "/", convert_date_display($row_ee[0]['date_to']));
}

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
		// check_id($db, $conn, $id, "employee_profile", $school_admin_id, $selected_db_name);
		if ($cmd3 == 'add') {
			$sql_ee1 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_experience a 
									WHERE a.school_admin_id 			= '" . $school_admin_id . "'   
									AND a.emp_profile_id				= '" . $id . "'    
									AND a.e_job_title 					= '" . $e_job_title . "' 
									AND a.e_company 					= '" . $e_company . "' ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql = "INSERT INTO " . $selected_db_name . ".employee_experience(school_admin_id, emp_profile_id, e_job_title, e_job_role, e_company, e_job_description, e_date_from, e_date_to, add_date, add_by, add_ip)
						VALUES('" . $school_admin_id . "', '" . $id . "', '" . $e_job_title . "', '" . $e_job_role . "', '" . $e_company . "', '" . $e_job_description . "', '" . $e_date_from1 . "', '" . $e_date_to1 . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				echo $sql;
				$ok = $db->query($conn, $sql);
				if ($ok) {
					$msg['msg_success'] = "Experience record  has been added successfully.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "This record already exist.";
			}
		} else if ($cmd3 == 'edit') {
			check_id($db, $conn, $detail_id, "employee_experience", $school_admin_id, $selected_db_name);
			$sql_ee1 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_experience a 
									WHERE a.school_admin_id 	= '" . $school_admin_id . "' 
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
							WHERE id = '" . $detail_id . "' AND school_admin_id = '" . $school_admin_id . "' ";
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
								WHERE a.id = '" . $detail_id . "' AND a.school_admin_id = '" . $school_admin_id . "' ";
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

//Query for Employee's Profile
if (isset($is_submit_profile) && $is_submit_profile == 'Y') {
	$profile_pic_file_name = "";
	$resume_file_file_name = "";
	if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
		header("location: signout");
		exit();
	}
	if (isset($e_full_name) && $e_full_name == "") {
		$error['msg'] = "Enter Employee Full Name";
		$e_full_name_valid 			= "invalid";
	}
	if (isset($parent_name) && $parent_name == "") {
		$error['msg'] = "Enter Employee Parent Name ";
		$parent_name_valid 			= "invalid";
	}
	if (isset($e_gender) && $e_gender == "") {
		$error['msg'] = "Select Employee Gender";
		$e_gender_valid 			= "invalid";
	}
	if (isset($e_birth_date) && $e_birth_date == "") {
		$error['msg'] = "Enter Employee date of birth ";
		$e_birth_date_valid 		= "invalid";
	} else {
		$e_birth_date1 	= "0000-00-00";
		$e_birth_date1 = convert_date_mysql_slash($e_birth_date);
	}
	if (isset($e_marital_status) && $e_marital_status == "") {
		$error['msg'] = "Select Employee marital status ";
		$e_marital_status_valid 	= "invalid";
	}
	if (isset($e_phone) && $e_phone == "") {
		$error['msg'] = "Enter Employee phone number";
		$e_phone_valid 				= "invalid";
	}
	if (isset($e_email) && $e_email == "") {
		$error['msg'] = "Enter Employee email";
		$e_email_valid 				= "invalid";
	}
	if (isset($e_national_id_no) && $e_national_id_no == "") {
		$error['msg'] = "Enter Employee National ID Number";
		$e_national_id_no_valid 	= "invalid";
	}
	if (isset($e_joining_date) && $e_joining_date == "") {
		$error['msg'] = "Enter Employee date of joining";
		$e_joining_date_valid 		= "invalid";
	} else {
		$e_joining_date1 	= "0000-00-00";
		$e_joining_date1 = convert_date_mysql_slash($e_joining_date);
	}
	if (isset($e_mailing_address) && $e_mailing_address == "") {
		$error['msg'] = "Enter Employee mailing address";
		$e_mailing_address_valid 	= "invalid";
	}
	if (isset($e_mailing_city) && $e_mailing_city == "") {
		$error['msg'] = "Enter Employee mailing city";
		$e_mailing_city_valid 		= "invalid";
	}
	if (isset($e_mailing_state) && $e_mailing_state == "") {
		$error['msg'] = "Enter Employee mailing state";
		$e_mailing_state_valid 		= "invalid";
	}
	if (isset($e_mailing_country) && $e_mailing_country == "") {
		$error['msg'] = "Enter Employee mailing country";
		$e_mailing_country_valid 		= "invalid";
	}

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
	} else {
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

	if (empty($error)) {
		if ($cmd == 'add') {
			$sql_ee1 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
									WHERE a.school_admin_id = '" . $school_admin_id . "' 
									AND (a.e_email = '" . $e_email . "'  OR a.e_national_id_no = '" . $e_national_id_no . "')  ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql = "INSERT INTO " . $selected_db_name . ".employee_profile(school_admin_id, e_full_name, e_gender, e_birth_date, e_marital_status, 
																			e_phone, e_email, e_national_id_no, e_joining_date, parent_name, parent_phone, 
																			e_mailing_address, e_mailing_city, e_mailing_state, e_mailing_country, 
																			e_emergency_contact_name, e_emergency_contact_relationship, e_emergency_contact_phone, 
																			e_emergency_contact_email, e_earn_leave, e_casual_leave, e_sick_leave, e_exit_date, 
																			e_exit_reason, e_profile_pic, e_resume_upload, e_bank_name, e_bank_account_name, 
																			e_bank_account_number, e_bank_branch_location, add_date, add_by, add_ip)
					VALUES('" . $school_admin_id . "', '" . $e_full_name . "', '" . $e_gender . "', '" . $e_birth_date1 . "', '" . $e_marital_status . "', 
							'" . $e_phone . "', '" . $e_email . "', '" . $e_national_id_no . "', '" . $e_joining_date1 . "', '" . $parent_name . "', 
							'" . $parent_phone . "', '" . $e_mailing_address . "', '" . $e_mailing_city . "', '" . $e_mailing_state . "', '" . $e_mailing_country . "', 
							'" . $e_emergency_contact_name . "', '" . $e_emergency_contact_relationship . "', '" . $e_emergency_contact_phone . "', 
							'" . $e_emergency_contact_email . "', '" . $e_earn_leave . "', '" . $e_casual_leave . "', '" . $e_sick_leave . "', '" . $e_exit_date . "', 
							'" . $e_exit_reason . "', '" . $profile_pic_file_name . "', '" . $resume_file_file_name . "', '" . $e_bank_name . "', '" . $e_bank_account_name . "', 
							'" . $e_bank_account_number . "', '" . $e_bank_branch_location . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				// echo $sql;
				$ok = $db->query($conn, $sql);
				if ($ok) {
					$cmd 	= "edit";
					$id 	= mysqli_insert_id($conn);
					$msg['msg_success'] = "Employee record  has been added successfully.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "Employee record is already exist.";
			}
		} else if ($cmd == 'edit') {
			check_id($db, $conn, $id, "employee_profile", $school_admin_id, $selected_db_name);
			$sql_ee1 			= "	SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
									WHERE a.school_admin_id = '" . $school_admin_id . "' 
									AND (a.e_email 	= '" . $e_email . "'  OR a.e_national_id_no = '" . $e_national_id_no . "') 
									AND a.id		!= '" . $id . "' ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1);
			if ($counter_ee1 == 0) {
				$sql_c_up = "UPDATE " . $selected_db_name . ".employee_profile SET 	e_full_name					= '" . $e_full_name . "',
																		e_gender							= '" . $e_gender . "',
																		e_birth_date						= '" . $e_birth_date1 . "',
																		e_marital_status					= '" . $e_marital_status . "', 
																		e_phone								= '" . $e_phone . "',
																		e_email								=	'" . $e_email . "',
																		e_national_id_no					= '" . $e_national_id_no . "',
																		e_joining_date						= '" . $e_joining_date1 . "',
																		parent_name							= '" . $parent_name . "',
																		parent_phone						=	'" . $parent_phone . "',
																		e_mailing_address					= '" . $e_mailing_address . "',
																		e_mailing_city						= '" . $e_mailing_city . "',
																		e_mailing_state						= '" . $e_mailing_state . "',
																		e_mailing_country					= '" . $e_mailing_country . "',
																		e_emergency_contact_name			=	'" . $e_emergency_contact_name . "',
																		e_emergency_contact_relationship	= '" . $e_emergency_contact_relationship . "',
																		e_emergency_contact_phone			= '" . $e_emergency_contact_phone . "',
																		e_emergency_contact_email			= '" . $e_emergency_contact_email . "',
																		e_earn_leave						= '" . $e_earn_leave . "',
																		e_casual_leave						= '" . $e_casual_leave . "',
																		e_sick_leave						= '" . $e_sick_leave . "',
																		e_exit_date							= '" . $e_exit_date . "',
																		e_exit_reason						= '" . $e_exit_reason . "',
																		e_profile_pic						= '" . $profile_pic_file_name . "',
																		e_resume_upload						= '" . $resume_file_file_name . "',
																		e_bank_name							= '" . $e_bank_name . "',
																		e_bank_account_name					= '" . $e_bank_account_name . "',
																		e_bank_account_number				= '" . $e_bank_account_number . "',
																		e_bank_branch_location				= '" . $e_bank_branch_location . "',
																		update_date 						= '" . $add_date . "',
																		update_by 	 						= '" . $_SESSION['username'] . "',
																		update_ip 	 						= '" . $add_ip . "'
							WHERE id = '" . $id . "' AND school_admin_id = '" . $school_admin_id . "' ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "There is Error, record did not update, Please check it again OR contact Support Team.";
				}
			} else {
				$error['msg'] = "Employee is already exist.";
			}
		}
	}
} else if ($cmd == 'edit' && isset($id)) {
	$sql_ee 							= "SELECT a.* FROM " . $selected_db_name . ".employee_profile a 
											WHERE a.id = '" . $id . "' 
											AND a.school_admin_id = '" . $school_admin_id . "'   "; //echo $sql_ee;
	$result_ee 							= $db->query($conn, $sql_ee);
	$row_ee 							= $db->fetch($result_ee);

	$e_full_name 						= $row_ee[0]['e_full_name'];
	$parent_name 						= $row_ee[0]['parent_name'];
	$parent_phone 						= $row_ee[0]['parent_phone'];
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
	$e_casual_leave 					= $row_ee[0]['e_casual_leave'];
	$e_sick_leave 						= $row_ee[0]['e_sick_leave'];
	$e_exit_date						= str_replace("-", "/", convert_date_display($row_ee[0]['e_exit_date']));
	$e_exit_reason 						= $row_ee[0]['e_exit_reason'];
	$e_bank_name 						= $row_ee[0]['e_bank_name'];
	$e_bank_account_name 				= $row_ee[0]['e_bank_account_name'];
	$e_bank_account_number 				= $row_ee[0]['e_bank_account_number'];
	$e_bank_branch_location 			= $row_ee[0]['e_bank_branch_location'];
	$e_bank_branch_location 			= $row_ee[0]['e_bank_branch_location'];
	$resume_file_file_name 				= $row_ee[0]['e_resume_upload'];
	$profile_pic_file_name 				= $row_ee[0]['e_profile_pic'];
}
if ($cmd == 'edit') {
	$title_heading = "Edit Employee Profile";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Employee Profile";
	$button_val 	= "Add";
	$id 			= "";
}
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="row">
						<div class="col s10 m10 20">
							<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $title_heading; ?></span></h5>
							<ol class="breadcrumbs mb-0">
								<li class="breadcrumb-item"><?php echo $title_heading; ?>
								</li>
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">List</a></li>
							</ol>
						</div>
						<div class="col s2 m2 4">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
								List
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12">
			<div class="container">
				<!-- Account settings -->
				<section class="tabs-vertical mt-1 section">
					<div class="row">
						<div class="col l3 s12">
							<!-- tabs  -->
							<div class="card-panel">
								<ul class="tabs">
									<li class="tab">
										<a href="#general" class="<?php if (isset($active_tab) && $active_tab == 'tab1') {
																		echo "active";
																	} ?>">
											<i class="material-icons">person_outline</i>
											<span>Profile</span>
										</a>
									</li>
									<li class="tab">
										<a href="#info" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2')) {
																	echo "active";
																} ?>">
											<i class="material-icons">school</i>
											<span>Education</span>
										</a>
									</li>
									<li class="tab">
										<a href="#experience" class="<?php if (isset($active_tab) && $active_tab == 'tab3') {
																			echo "active";
																		} ?>">
											<i class="material-icons">description</i>
											<span>Experience</span>
										</a>
									</li>
									<li class="indicator" style="left: 0px; right: 0px;"></li>
									<?php
									if (isset($id) && $id > 0) { ?>
										<br>
										<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit_fees&cmd=add&active_tab=tab1") ?>">
											Add New Employee
										</a>
										<?php
										if (isset($cmd2) && $cmd2 != "") { ?><br>
											<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit_fees&cmd=edit&id=" . $id . "&cmd2=add&active_tab=tab2") ?>">
												Add New Eduction
											</a>
										<?php }
										if (isset($cmd3) && $cmd3 != "") { ?><br>
											<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit_fees&cmd=edit&id=" . $id . "&cmd3=add&active_tab=tab3") ?>">
												Add New Experience
											</a>
									<?php }
									} ?>
								</ul>
							</div>
						</div>
						<div class="col l9 s12">
							<?php
							if (isset($error['msg'])) { ?>
								<div class="card-panel">
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card red lighten-5">
												<div class="card-content red-text">
													<p><?php echo $error['msg']; ?></p>
												</div>
												<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										</div>
									</div>
								</div>
							<?php } else if (isset($msg['msg_success'])) { ?>
								<div class="card-panel">
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card green lighten-5">
												<div class="card-content green-text">
													<p><?php echo $msg['msg_success']; ?></p>
												</div>
												<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<!-- tabs content -->
							<!--General Tab Begin-->
							<div id="general" style="display: <?php if (isset($active_tab) && $active_tab == 'tab1') {
																	echo "block";
																} else {
																	echo "none";
																} ?>;">
								<div class="card-panel">
									<form class="infovalidate" action="" method="post" enctype="multipart/form-data">
										<input type="hidden" name="is_submit_profile" value="Y" />
										<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
										<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
										<input type="hidden" name="old_profile_pic_file_name" value="<?php if (isset($profile_pic_file_name)) echo $profile_pic_file_name; ?>" />
										<input type="hidden" name="old_resume_file_file_name" value="<?php if (isset($resume_file_file_name)) echo $resume_file_file_name; ?>" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<input type="hidden" name="active_tab" value="tab1" />
										<div class="divider mb-1 mt-1"></div>
										<div class="row">
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix pt-2">person_outline</i>
														<input type="text" id="e_full_name" name="e_full_name" value="<?php if (isset($e_full_name)) echo $e_full_name; ?>" data-error=".errorTxt1" required>
														<label for="e_full_name">Full Name</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix pt-2">people_outline</i>
														<select id="e_gender" name="e_gender" class="validate <?php if (isset($e_gender)) {
																													echo $e_gender;
																												} ?>" required>
															<option value="" disabled selected>Select Gender</option>
															<option value="Male" <?php if (isset($e_gender) && $e_gender == "Male") { ?> selected="selected" <?php } ?>>Male</option>
															<option value="Female" <?php if (isset($e_gender) && $e_gender == "Female") { ?> selected="selected" <?php } ?>>Female</option>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">date_range</i>
														<input id="e_birth_date" type="text" name="e_birth_date" class="datepicker" value="<?php if (isset($e_birth_date)) {
																																				echo $e_birth_date;
																																			} ?>" required>
														<label for="e_birth_date">Date of Birth</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix pt-2">people_outline</i>
														<select id="e_marital_status" name="e_marital_status" class="validate <?php if (isset($e_marital_status)) {
																																	echo $e_marital_status;
																																} ?>" required>
															<option value="" disabled selected>Select Marital Status</option>
															<option value="Single" <?php if (isset($e_marital_status) && $e_marital_status == "Single") { ?> selected="selected" <?php } ?>>Single</option>
															<option value="Married" <?php if (isset($e_marital_status) && $e_marital_status == "Married") { ?> selected="selected" <?php } ?>>Married</option>
															<option value="Divorced" <?php if (isset($e_marital_status) && $e_marital_status == "Divorced") { ?> selected="selected" <?php } ?>>Divorced</option>
															<option value="Other" <?php if (isset($e_marital_status) && $e_marital_status == "Other") { ?> selected="selected" <?php } ?>>Other</option>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix pt-2">phone_iphone_outline</i>
														<input type="text" id="e_phone" name="e_phone" value="<?php if (isset($e_phone)) echo $e_phone; ?>" data-error=".errorTxt1" required>
														<label for="e_phone">Phone</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix pt-2">mail_outline</i>
														<input type="email" id="e_email" name="e_email" value="<?php if (isset($e_email)) echo $e_email; ?>" data-error=".errorTxt1" required>
														<label for="e_email">Email</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix pt-2">payment_outline</i>
														<input type="text" id="e_national_id_no" name="e_national_id_no" value="<?php if (isset($e_national_id_no)) echo $e_national_id_no; ?>" data-error=".errorTxt1" required>
														<label for="e_national_id_no">National ID Number</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">date_range</i>
														<input id="e_joining_date" type="text" name="e_joining_date" class="datepicker" value="<?php if (isset($e_joining_date)) {
																																					echo $e_joining_date;
																																				} ?>" required>
														<label for="e_joining_date">Joining Date</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">person_outline</i>
														<input id="parent_name" type="text" name="parent_name" value="<?php if (isset($parent_name)) {
																															echo $parent_name;
																														} ?>" required>
														<label for="parent_name">Parent full name</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">phone_iphone</i>
														<input id="parent_phone" type="text" name="parent_phone" value="<?php if (isset($parent_phone)) {
																															echo $parent_phone;
																														} ?>">
														<label for="parent_phone">Parent phone number</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s12">
													<div class="input-field">
														<i class="material-icons prefix">place</i>
														<input id="e_mailing_address" type="text" name="e_mailing_address" value="<?php if (isset($e_mailing_address)) {
																																		echo $e_mailing_address;
																																	} ?>" required>
														<label for="e_mailing_address">Mailing address</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">place</i>
														<input id="e_mailing_city" type="text" name="e_mailing_city" value="<?php if (isset($e_mailing_city)) {
																																echo $e_mailing_city;
																															} ?>" required>
														<label for="e_mailing_city">Mailing city</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">place</i>
														<input id="e_mailing_state" type="text" name="e_mailing_state" value="<?php if (isset($e_mailing_state)) {
																																	echo $e_mailing_state;
																																} ?>" required>
														<label for="e_mailing_state">Mailing state</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">place</i>
														<input id="e_mailing_country" type="text" name="e_mailing_country" value="<?php if (isset($e_mailing_country)) {
																																		echo $e_mailing_country;
																																	} ?>" required>
														<label for="e_mailing_country">Mailing country</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">person_outline</i>
														<input id="e_emergency_contact_name" type="text" name="e_emergency_contact_name" value="<?php if (isset($e_emergency_contact_name)) {
																																					echo $e_emergency_contact_name;
																																				} ?>">
														<label for="e_emergency_contact_name">Emergency contact name</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">people_outline</i>
														<input id="e_emergency_contact_relationship" type="text" name="e_emergency_contact_relationship" value="<?php if (isset($e_emergency_contact_relationship)) {
																																									echo $e_emergency_contact_relationship;
																																								} ?>">
														<label for="e_emergency_contact_relationship">Emergency contact relationship</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">phone_iphone</i>
														<input id="e_emergency_contact_phone" type="text" name="e_emergency_contact_phone" value="<?php if (isset($e_emergency_contact_phone)) {
																																						echo $e_emergency_contact_phone;
																																					} ?>">
														<label for="e_emergency_contact_phone">Emergency contact phone</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">mail_outline</i>
														<input id="e_emergency_contact_email" type="text" name="e_emergency_contact_email" value="<?php if (isset($e_emergency_contact_email)) {
																																						echo $e_emergency_contact_email;
																																					} ?>">
														<label for="e_emergency_contact_email">Emergency contact email</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">schedule</i>
														<input id="e_earn_leave" type="text" name="e_earn_leave" value="<?php if (isset($e_earn_leave)) {
																															echo $e_earn_leave;
																														} ?>">
														<label for="e_earn_leave">Earn leave</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">schedule</i>
														<input id="e_casual_leave" type="text" name="e_casual_leave" value="<?php if (isset($e_casual_leave)) {
																																echo $e_casual_leave;
																															} ?>">
														<label for="e_casual_leave">Casual leave</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">schedule</i>
														<input id="e_sick_leave" type="text" name="e_sick_leave" value="<?php if (isset($e_sick_leave)) {
																															echo $e_sick_leave;
																														} ?>">
														<label for="e_sick_leave">Sick leave</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">date_range</i>
														<input id="e_exit_date" type="text" name="e_exit_date" class="datepicker" value="<?php if (isset($e_exit_date)) {
																																				echo $e_exit_date;
																																			} ?>">
														<label for="e_exit_date">Exit date</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">speaker_notes_outline</i>
														<input id="e_exit_reason" type="text" name="e_exit_reason" value="<?php if (isset($e_exit_reason)) {
																																echo $e_exit_reason;
																															} ?>">
														<label for="e_exit_reason">Exit reason</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">account_balance</i>
														<input id="e_bank_name" type="text" name="e_bank_name" value="<?php if (isset($e_bank_name)) {
																															echo $e_bank_name;
																														} ?>">
														<label for="e_bank_name">Bank name</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">account_balance</i>
														<input id="e_bank_account_name" type="text" name="e_bank_account_name" value="<?php if (isset($e_bank_account_name)) {
																																			echo $e_bank_account_name;
																																		} ?>">
														<label for="e_bank_account_name">Bank account name</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">account_balance</i>
														<input id="e_bank_account_number" type="text" name="e_bank_account_number" value="<?php if (isset($e_bank_account_number)) {
																																				echo $e_bank_account_number;
																																			} ?>">
														<label for="e_bank_account_number">Bank account number</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field">
														<i class="material-icons prefix">place</i>
														<input id="e_bank_branch_location" type="text" name="e_bank_branch_location" value="<?php if (isset($e_bank_branch_location)) {
																																				echo $e_bank_branch_location;
																																			} ?>">
														<label for="e_bank_branch_location">Bank branch location</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="custom-file input-field">
														<input id="customFile" type="file" name="e_profile_pic" class="custom-file-input dropify" value="<?php if (isset($e_profile_pic)) {
																																								echo $e_profile_pic;
																																							} ?>" data-default-file>
														<label class="custom-file-label" for="e_profile_pic">&nbsp;Upload profile photo</label>
													</div>
												</div>
												<div class="col s6"><br>
													<?php if (isset($profile_pic_file_name)) { ?>
														<img src="<?php echo $directory_path; ?>app-assets/images/logo/<?php echo $profile_pic_file_name; ?>" style="Height: 200px;" class="responsive-img" />
													<?php } ?>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="custom-file input-field">
														<input id="customFile" type="file" name="e_resume_upload" class="custom-file-input dropify" value="<?php if (isset($e_resume_upload)) {
																																								echo $e_resume_upload;
																																							} ?>" data-default-file>
														<label class="custom-file-label" for="e_resume_upload">&nbsp; Upload resume</label>
													</div>
												</div>
												<div class="col s6"><br><br><br><br>
													<?php if (isset($profile_pic_file_name)) { ?>
														<a target="_blank" href="<?php echo $directory_path; ?>app-assets/employee_resumes/<?php echo $resume_file_file_name; ?>" class="waves-effect waves-light green darken-1  btn gradient-45deg-light-green-cyan box-shadow-none border-round mr-1 mb-1">
															<i class="material-icons">attachment</i>
														</a>
													<?php } ?>
												</div>
											</div>
											<div class="row">
												<div class="input-field col m4 s12"></div>
												<div class="input-field col m4 s12">
													<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="add"><?php echo $button_val; ?></button>
												</div>
												<div class="input-field col m4 s12"></div>
											</div>
											<div class="col s4"></div>
										</div>
									</form>
								</div>
							</div>
							<!--General Tab End-->

							<!--Info Tab Begin-->
							<div id="info" class="active" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2')) {
																				echo "block";
																			} else {
																				echo "none";
																			} ?>;">
								<div class="card-panel">
									<form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&page=add_edit_fees&cmd=edit&id=" . $id . "&cmd2=" . $cmd2 . "&detail_id=" . $detail_id . "&active_tab=tab2") ?>" method="post">
										<input type="hidden" name="is_Submit_Education" value="Y" />
										<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
										<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
										<input type="hidden" name="cmd2" value="<?php if (isset($cmd2)) echo $cmd2; ?>" />
										<input type="hidden" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<input type="hidden" name="active_tab" value="tab2" />

										<div class="row">
											<div class="col s12">
												<div class="input-field col m12 s12">
													<i class="material-icons prefix">school</i>
													<input id="e_school" type="text" name="e_institution_name" value="<?php if (isset($e_institution_name)) {
																															echo $e_institution_name;
																														} ?>" class="validate <?php if (isset($e_institution_name)) {
																																																				echo $e_institution_name;
																																																			} ?>" required>
													<label for="e_school">School</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col s6">
												<div class="input-field col m12 s12">
													<i class="material-icons prefix">date_range</i>
													<input id="date_from" type="text" name="date_from" class="datepicker" value="<?php if (isset($date_from)) {
																																		echo $date_from;
																																	} ?>" required>
													<label for="date_from">From</label>
												</div>
											</div>
											<div class="col s6">
												<div class="input-field col m12 s12">
													<i class="material-icons prefix">date_range</i>
													<input id="date_to" type="text" name="date_to" class="datepicker" value="<?php if (isset($date_to)) {
																																	echo $date_to;
																																} ?>" required>
													<label for="date_to">To</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col s6">
												<div class="input-field col m12 s12">
													<i class="material-icons prefix">local_library</i>
													<input id="degree_name" type="text" name="degree_name" value="<?php if (isset($degree_name)) {
																														echo $degree_name;
																													} ?>" class="validate <?php if (isset($degree_name)) {
																																																echo $degree_name;
																																															} ?>" required>
													<label for="degree_name">Degree</label>
												</div>
											</div>
											<div class="col s6">
												<div class="input-field col m12 s12">
													<i class="material-icons prefix">local_library</i>
													<input id="study_area" type="text" name="study_area" value="<?php if (isset($study_area)) {
																													echo $study_area;
																												} ?>" class="validate <?php if (isset($study_area)) {
																																															echo $study_area;
																																														} ?>" required>
													<label for="study_area">Area of Study</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="input-field col m4 s12"></div>
											<div class="input-field col m4 s12">
												<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="add"><?php echo $button_edu; ?></button>
											</div>
											<div class="input-field col m4 s12"></div>
										</div>
									</form>
								</div>
								<div class="section section-data-tables">
									<div class="row">
										<div class="col s12">
											<div class="card">
												<div class="card-content">
													<div class="row">
														<div class="col s12">
															<?php
															$sql_cl1 = "SELECT a.* 
												FROM " . $selected_db_name . ".employee_education a
 												WHERE a.enabled 		= 1 
												AND a.school_admin_id 	= '" . $school_admin_id . "' 
												AND a.emp_profile_id	= '" . $id . "' 
												ORDER BY a.id DESC "; //echo $sql_cl1;
															$result_cl1 	= $db->query($conn, $sql_cl1);
															$count_cl1 	= $db->counter($result_cl1);
															if ($count_cl1 > 0) { ?>
																<table id="page-length-option" class="display">
																	<thead>
																		<tr>
																			<th>S.No</th>
																			<th>Degree</th>
																			<th>Institute</th>
																			<th>Area of Study</th>
																			<th>Date From</th>
																			<th>Date To</th>
																			<th>Action</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		$i = 0;
																		if ($count_cl1 > 0) {
																			$row_cl1 = $db->fetch($result_cl1);
																			foreach ($row_cl1 as $data) { ?>
																				<tr>
																					<td><?php echo $i + 1; ?></td>
																					<td><?php echo $data['degree_name']; ?></td>
																					<td><?php echo $data['e_institution_name']; ?></td>
																					<td><?php echo $data['study_area']; ?></td>
																					<td><?php echo dateformat2($data['date_from']); ?></td>
																					<td><?php echo dateformat2($data['date_to']); ?></td>
																					<td>
																						<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit_fees&cmd=edit&cmd2=edit&active_tab=tab2&id=" . $id . "&detail_id=" . $data['id']) ?>">
																							<i class="material-icons dp48">edit</i>
																						</a>
																						&nbsp;&nbsp;
																						<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit_fees&cmd=edit&cmd2=delete&active_tab=tab2&id=" . $id . "&detail_id=" . $data['id']) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																							<i class="material-icons dp48">delete</i>
																						</a>
																					</td>
																				</tr>
																		<?php
																				$i++;
																			}
																		} ?>
																	</tbody>
																</table>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="content-overlay"></div>
									<!-- Multi Select -->
								</div>
							</div>
							<!--Info Tab End-->

							<!--Experience Tab Begin-->
							<div id="experience" class="active" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
																					echo "block";
																				} else {
																					echo "none";
																				} ?>;">
								<div class="card-panel">
									<form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&page=add_edit_fees&cmd=edit&id=" . $id . "&cmd3=" . $cmd3 . "&detail_id=" . $detail_id . "&active_tab=tab3") ?>" method="post">
										<input type="hidden" name="is_Submit_experience" value="Y" />
										<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
										<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
										<input type="hidden" name="cmd3" value="<?php if (isset($cmd3)) echo $cmd3; ?>" />
										<input type="hidden" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<input type="hidden" name="active_tab" value="tab3" />

										<div id="experience_field">
											<div class="row">
												<div class="col s6">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">work_outline</i>
														<input id="e_job_title" type="text" name="e_job_title" value="<?php if (isset($e_job_title)) {
																															echo $e_job_title;
																														} ?>" required="required" class="validate <?php if (isset($e_job_title)) {
																																																						echo $e_job_title;
																																																					} ?>">
														<label for="e_job_title">Job Title</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">work_outline</i>
														<input id="e_job_role" type="text" name="e_job_role" value="<?php if (isset($e_job_role)) {
																														echo $e_job_role;
																													} ?>" required="required" class="validate <?php if (isset($e_job_role)) {
																																																					echo $e_job_role;
																																																				} ?>">
														<label for="e_job_role">Job Role</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">date_range</i>
														<input id="e_date_from" type="text" name="e_date_from" class="datepicker" value="<?php if (isset($e_date_from)) {
																																				echo $e_date_from;
																																			} ?>" required>
														<label for="e_date_from">From</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">date_range</i>
														<input id="e_date_to" type="text" name="e_date_to" class="datepicker" value="<?php if (isset($e_date_to)) {
																																			echo $e_date_to;
																																		} ?>" required>
														<label for="e_date_to">To</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col s6">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">business</i>
														<input id="e_company" type="text" name="e_company" value="<?php if (isset($e_company)) {
																														echo $e_company;
																													} ?>" required="required" class="validate <?php if (isset($e_company)) {
																																																				echo $e_company;
																																																			} ?>">
														<label for="e_company">Company</label>
													</div>
												</div>
												<div class="col s6">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">description_outline</i>
														<input id="e_job_description" type="text" name="e_job_description" value="<?php if (isset($e_job_description)) {
																																		echo $e_job_description;
																																	} ?>" required="required" class="validate <?php if (isset($e_job_description)) {
																																																												echo $e_job_description;
																																																											} ?>">
														<label for="e_job_description">Job Description</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="input-field col m4 s12"></div>
												<div class="input-field col m4 s12">
													<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="add"><?php echo $button_exp; ?></button>
												</div>
												<div class="input-field col m4 s12"></div>
											</div>
										</div>
									</form>
								</div>
								<div class="section section-data-tables">
									<div class="row">
										<div class="col s12">
											<div class="card">
												<div class="card-content">
													<div class="row">
														<div class="col s12">
															<?php
															$sql_cl1 = "SELECT a.*
												FROM " . $selected_db_name . ".employee_experience a
												WHERE a.enabled 		= 1 
												AND a.school_admin_id 	= '" . $school_admin_id . "' 
												AND a.emp_profile_id	= '" . $id . "' 
												ORDER BY a.id DESC ";
															$result_cl1 	= $db->query($conn, $sql_cl1);
															$count_cl1 	= $db->counter($result_cl1);
															if ($count_cl1 > 0) { ?>
																<table id="page-length-option" class="display">
																	<thead>
																		<tr>
																			<th>S.No</th>
																			<th>Job Title</th>
																			<th>Job Role</th>
																			<th>Company Name</th>
																			<th>Job Description</th>
																			<th> Date From</th>
																			<th> Date To</th>
																			<th>Action</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		$i = 0;
																		if ($count_cl1 > 0) {
																			$row_cl1 = $db->fetch($result_cl1);
																			foreach ($row_cl1 as $data) { ?>
																				<tr>
																					<td><?php echo $i + 1; ?></td>
																					<td><?php echo $data['e_job_title']; ?></td>
																					<td><?php echo $data['e_job_role']; ?></td>
																					<td><?php echo $data['e_company']; ?></td>
																					<td><?php echo $data['e_job_description']; ?></td>
																					<td><?php echo dateformat2($data['e_date_from']); ?></td>
																					<td><?php echo dateformat2($data['e_date_to']); ?></td>
																					<td>
																						<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit_fees&cmd=edit&cmd3=edit&active_tab=tab3&id=" . $id . "&detail_id=" . $data['id']) ?>">
																							<i class="material-icons dp48">edit</i>
																						</a>
																						&nbsp;&nbsp;
																						<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit_fees&cmd=edit&cmd3=delete&active_tab=tab3&id=" . $id . "&detail_id=" . $data['id']) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																							<i class="material-icons dp48">delete</i>
																						</a>
																					</td>
																				</tr>
																		<?php
																				$i++;
																			}
																		} ?>
																	</tbody>
																</table>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="content-overlay"></div>
									<!-- Multi Select -->
								</div>
							</div>
							<!--Experience Tab End-->
						</div>
					</div>
				</section>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->