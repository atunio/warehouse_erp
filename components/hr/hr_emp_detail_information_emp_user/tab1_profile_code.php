<?php

//Query for Employee's Profile
if(isset($is_submit_profile) && $is_submit_profile == 'Y'){
	$profile_pic_file_name = "";
	$resume_file_file_name = "";
	if(decrypt($csrf_token) != $_SESSION["csrf_session"]){ header( "location: signout"); exit(); }
	if(isset($e_full_name) && $e_full_name == ""){				$error['msg'] = "Enter Employee Full Name"; 		$e_full_name_valid 			= "invalid";}  
	if(isset($parent_name) && $parent_name == ""){ 				$error['msg'] = "Enter Employee Parent Name "; 		$parent_name_valid 			= "invalid";} 
	if(isset($e_gender) && $e_gender == ""){ 					$error['msg'] = "Select Employee Gender"; 			$e_gender_valid 			= "invalid";}
	if(isset($e_birth_date) && $e_birth_date == ""){ 			$error['msg'] = "Enter Employee date of birth "; 	$e_birth_date_valid 		= "invalid";}
	else{
		$e_birth_date1 	= "0000-00-00"; 
		$e_birth_date1 = convert_date_mysql_slash($e_birth_date);
	} 
	if(isset($e_marital_status) && $e_marital_status == ""){ 	$error['msg'] = "Select Employee marital status "; 	$e_marital_status_valid 	= "invalid";} 
	if(isset($e_national_id_no) && $e_national_id_no == ""){ 	$error['msg'] = "Enter Employee National ID Number"; $e_national_id_no_valid 	= "invalid";} 
 
	if(isset($e_mailing_address) && $e_mailing_address == ""){ 	$error['msg'] = "Enter Employee mailing address"; 	$e_mailing_address_valid 	= "invalid";} 
	if(isset($e_mailing_city) && $e_mailing_city == ""){ 		$error['msg'] = "Enter Employee mailing city"; 		$e_mailing_city_valid 		= "invalid";}
	if(isset($e_mailing_state) && $e_mailing_state == ""){ 		$error['msg'] = "Enter Employee mailing state"; 	$e_mailing_state_valid 		= "invalid";}
	if(isset($e_mailing_country) && $e_mailing_country == ""){ 	$error['msg'] = "Enter Employee mailing country"; $e_mailing_country_valid 		= "invalid";} 
 
	if(is_array($_FILES) && isset($_FILES["e_profile_pic"]["name"]) && $_FILES["e_profile_pic"]["name"] !="") {
		$picture_uniq_id 			= $_SESSION['user_id']."_".uniqid();
		$temp 						= explode(".", $_FILES["e_profile_pic"]["name"]);
		$extension 					= end($temp);
		$file_type 					= $_FILES['e_profile_pic']['type'];
		$profile_pic_file_name		= $_FILES['e_profile_pic']['name'];
		$file_tmp  					= $_FILES['e_profile_pic']['tmp_name'];
		$valid_formats				= array("image/JPEG", "image/jpeg", "image/JPG", "image/jpg", "image/PNG", "image/png", "image/gif", "image/GIF"); //add the formats you want to upload
		$mime          				= mime_content_type($file_tmp);
		if ($profile_pic_file_name !=""){
			if(!in_array($file_type, $valid_formats) && !in_array($mime, $valid_formats)){
				$error['msg'] = "Invalid Profile Picture format, Please choose only JPG, PNG or GIF  Picture";
			}
			else{
				$sourcePath				= $file_tmp;
				$profile_pic_file_name	= $picture_uniq_id.".".$extension;
				$targetPath 			= "app-assets/images/logo/".$profile_pic_file_name;
				move_uploaded_file($sourcePath,$targetPath);
			}
		}
	} 
	else if(isset($cmd) && $cmd == 'edit'){
		$profile_pic_file_name = $old_profile_pic_file_name;
	}
	if(is_array($_FILES) && isset($_FILES["e_resume_upload"]["name"]) && $_FILES["e_resume_upload"]["name"] !="") {
		$picture_uniq_id 			= $_SESSION['user_id']."_".uniqid();
		$temp 						= explode(".", $_FILES["e_resume_upload"]["name"]);
		$extension 					= end($temp);
		$file_type 					= $_FILES['e_resume_upload']['type'];
		$resume_file_file_name		= $_FILES['e_resume_upload']['name'];
		$file_tmp  					= $_FILES['e_resume_upload']['tmp_name'];
		$valid_formats				= array("application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"); //add the formats you want to upload
		$mime          				= mime_content_type($file_tmp);
		if ($resume_file_file_name !=""){
			if(!in_array($file_type, $valid_formats) && !in_array($mime, $valid_formats)){
				$error['msg'] = "Invalid File Format, Please choose only PDF, DOC Word File.";
				$resume_file_file_name = "";
			}
			else{
				$sourcePath				= $file_tmp;
				$resume_file_file_name	= $picture_uniq_id.".".$extension;
				$targetPath 			= "app-assets/employee_resumes/".$resume_file_file_name;
				move_uploaded_file($sourcePath,$targetPath);
			}
		}
	} 
	else {
		$resume_file_file_name = $old_resume_file_file_name;
	}
	if(empty($error)){
		if($cmd == 'edit'){
			check_id($db, $conn, $id, "employee_profile", $school_admin_id, $selected_db_name);
			$sql_ee1_2 			= "	SELECT a.* FROM ".$selected_db_name.".employee_profile a 
									WHERE a.school_admin_id = '".$school_admin_id."' 
									AND a.user_id	= '".$user_id."' 
									AND a.e_national_id_no 	= '".$e_national_id_no."'
									AND a.id			   != '".$id."' ";
			$result_ee1_2 			= $db->query($conn, $sql_ee1_2);
			$counter_ee1_2			= $db->counter($result_ee1_2); 
			if($counter_ee1_2 == 0){ 
				$sql_c_up = "UPDATE ".$selected_db_name.".employee_profile SET 	e_full_name					= '".$e_full_name."',
																		e_gender							= '".$e_gender."',
																		e_birth_date						= '".$e_birth_date1."',
																		e_marital_status					= '".$e_marital_status."', 
																		e_national_id_no					= '".$e_national_id_no."',
																		parent_name							= '".$parent_name."',
																		parent_phone						=	'".$parent_phone."',
																		e_mailing_address					= '".$e_mailing_address."',
																		e_mailing_city						= '".$e_mailing_city."',
																		e_mailing_state						= '".$e_mailing_state."',
																		e_mailing_country					= '".$e_mailing_country."',
																		e_emergency_contact_name			=	'".$e_emergency_contact_name."',
																		e_emergency_contact_relationship	= '".$e_emergency_contact_relationship."',
																		e_emergency_contact_phone			= '".$e_emergency_contact_phone."',
																		e_emergency_contact_email			= '".$e_emergency_contact_email."',
																		e_profile_pic						= '".$profile_pic_file_name."',
																		e_resume_upload						= '".$resume_file_file_name."',
																		e_bank_name							= '".$e_bank_name."',
																		e_bank_account_name					= '".$e_bank_account_name."',
																		e_bank_account_number				= '".$e_bank_account_number."',
																		e_bank_branch_location				= '".$e_bank_branch_location."',
																		
																		update_date 						= '".$add_date."',
																		update_by 	 						= '".$_SESSION['username']."',
																		update_ip 	 						= '".$add_ip."'

							WHERE id = '".$id."' 
								  AND school_admin_id 	= '".$school_admin_id."'
								  AND user_id	= '".$user_id."'  ";
				$ok = $db->query($conn, $sql_c_up);
				if($ok){
					$msg['msg_success'] = "Record Updated Successfully.";
				}
				else{
					$error['msg'] = "There is Error, record did not update, Please check it again OR contact Support Team.";
				} 
			}
			else{
				$error['msg'] = "Employee with this National ID is already exist.";
			}
		}
	}
}
else if($cmd == 'edit' && isset($id)){ 
	$sql_ee 							= " SELECT a.*, b.username, b.a_password, b.user_type
											FROM ".$selected_db_name.".employee_profile a 
											LEFT JOIN ".$selected_db_name.".school_users b ON b.id = a.user_id
											WHERE a.id = '".$id."' 
											AND a.user_id	= '".$user_id."'
											AND a.school_admin_id = '".$school_admin_id."'   "; //echo $sql_ee;
	$result_ee 							= $db->query($conn, $sql_ee);
	$counter1 							= $db->counter($result_ee);   
	if($counter1 > 0){
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
		$e_bank_name 						= $row_ee[0]['e_bank_name'];
		$e_bank_account_name 				= $row_ee[0]['e_bank_account_name'];
		$e_bank_account_number 				= $row_ee[0]['e_bank_account_number'];
		$e_bank_branch_location 			= $row_ee[0]['e_bank_branch_location'];
		$e_bank_branch_location 			= $row_ee[0]['e_bank_branch_location'];
		$resume_file_file_name 				= $row_ee[0]['e_resume_upload'];
		$profile_pic_file_name 				= $row_ee[0]['e_profile_pic']; 
		$emp_code 							= $row_ee[0]['emp_code'];
		$username 							= $row_ee[0]['username'];
		$u_password 						= $row_ee[0]['a_password'];  
		$user_type 							= $row_ee[0]['user_type'];   
	}
}?>