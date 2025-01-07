<?php

//Query for employee's education
if(isset($is_Submit_Education) && $is_Submit_Education == 'Y'){ 
	if(decrypt($csrf_token) != $_SESSION["csrf_session"]){ header( "location: signout"); exit(); }
	if(isset($e_institution_name) && $e_institution_name == ""){	$error['msg'] = "Enter Employee school"; 					$e_institution_name_valid = "invalid";}
	if(!isset($id) || (isset($id)  && ($id == "0" || $id == ""))){ $error['msg'] = "Please add employee information first"; } 
	if(isset($date_from) && $date_from == ""){ 						$error['msg'] = "Enter Employee entry year"; 				$date_from_valid = "invalid";}
	else{
		$date_from1 	= "0000-00-00"; 
		$date_from1 	= convert_date_mysql_slash($date_from);
	} 
	if(isset($date_to) && $date_to == ""){ 							$error['msg'] = "Enter Employee graduation year "; 	$date_to_valid = "invalid";}
	else{
		$date_to1 	= "0000-00-00"; 
		$date_to1 	= convert_date_mysql_slash($date_to);
	} 
	if(isset($degree_name) && $degree_name == ""){ 					$error['msg'] = "Enter Employee degree"; 			$degree_name_valid = "invalid";} 
	if(isset($study_area) && $study_area == ""){ 					$error['msg'] = "Enter Employee area of study"; 	$study_area_valid = "invalid";}  
	if(empty($error)){
		// check_id($db, $conn, $id, "employee_profile", $school_admin_id, $selected_db_name);
		if($cmd2 == 'add'){ 
			$sql_ee1 			= "	SELECT a.* FROM ".$selected_db_name.".employee_education a 
									INNER JOIN ".$selected_db_name.".employee_profile b ON b.id = a.emp_profile_id
									WHERE a.school_admin_id 	= '".$school_admin_id."'  
									AND b.user_id		= '".$user_id."' 
									AND a.emp_profile_id		= '".$id."'    
									AND a.degree_name			= '".$degree_name."' ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1);
			if($counter_ee1 == 0){
				$sql = "INSERT INTO ".$selected_db_name.".employee_education(school_admin_id, emp_profile_id, e_institution_name, date_from, date_to, degree_name, study_area, add_date, add_by, add_ip)
						VALUES('".$school_admin_id."', '".$id."', '".$e_institution_name."', '".$date_from1."', '".$date_to1."', '".$degree_name."', '".$study_area."', '".$add_date."', '".$_SESSION['username']."', '".$add_ip."')";
				
				$ok = $db->query($conn, $sql);
				if($ok){
					$e_institution_name = $date_from= $date_to= $degree_name= $study_area = "";
					$msg['msg_success'] = "Education record  has been added successfully.";
				}
				else{
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			}
			else{
				$error['msg'] = "This record already exist.";
			}
		}
		else if($cmd2 == 'edit'){
			check_id($db, $conn, $detail_id, "employee_education", $school_admin_id, $selected_db_name);
			$sql_ee1 			= "	SELECT a.* FROM ".$selected_db_name.".employee_education a 
									INNER JOIN ".$selected_db_name.".employee_profile b ON b.id = a.emp_profile_id
									WHERE a.school_admin_id 	= '".$school_admin_id."'  
									AND b.user_id		= '".$user_id."' 
									AND a.emp_profile_id		= '".$id."'    
									AND a.degree_name			= '".$degree_name."' 
									AND a.id 				   != '".$detail_id."'"; 
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1); 
			if($counter_ee1 == 0){
				$sql_c_up = "UPDATE ".$selected_db_name.".employee_education SET 	e_institution_name			= '".$e_institution_name."',
																					date_from					= '".$date_from1."',
																					date_to						= '".$date_to1."',
																					degree_name					= '".$degree_name."',
																					study_area					= '".$study_area."',			
																					update_date 				= '".$add_date."',
																					update_by 	 				= '".$_SESSION['username']."',
																					update_ip 	 				= '".$add_ip."'
							WHERE 	id 					= '".$detail_id."' 
									AND school_admin_id = '".$school_admin_id."'";
				$ok = $db->query($conn, $sql_c_up);
				if($ok){
					$button_edu = "Edit";
					$msg['msg_success'] = "Record Updated Successfully.";
				}
				else{
					$error['msg'] = "There is Error, record did not update, Please check it again OR contact Support Team.";
				}
			}
			else{
				$error['msg'] = "This record already exist.";
			}
		}
	}
} 
else if(isset($cmd2) && $cmd2 == 'delete' && isset($detail_id)){
	$sql_del 			= "	DELETE a.*
							FROM ".$selected_db_name.".employee_education a
							INNER JOIN ".$selected_db_name.".employee_profile b ON b.id = a.emp_profile_id
							WHERE a.school_admin_id = '".$school_admin_id."' 
							AND b.user_id	= '".$user_id."'  
							AND a.id 				= '".$detail_id."' "; //echo $sql_del;
	$ok = $db->query($conn, $sql_del);
	if($ok){
		$cmd2 = "add";
		$error['msg'] = "Record Deleted Successfully";
	}
	else{
		$error['msg'] = "There is Error, record did not delete, Please check it again OR contact Support Team.";
	}
}	
else if(isset($cmd2) && $cmd2 == 'edit' && isset($detail_id)){
	$button_edu 		= "Edit";
	$sql_ee 			= "	SELECT a.* FROM ".$selected_db_name.".employee_education a 
							INNER JOIN ".$selected_db_name.".employee_profile b ON b.id = a.emp_profile_id
							WHERE a.school_admin_id 	= '".$school_admin_id."'  
							AND b.user_id		= '".$user_id."' 
							AND a.id 					= '".$detail_id."' ";
	$result_ee 			= $db->query($conn, $sql_ee);
	$row_ee 			= $db->fetch($result_ee);  
	$e_institution_name	=  $row_ee[0]['e_institution_name']; 
	$date_from 			=  $row_ee[0]['date_from'];
	$date_to 			=  $row_ee[0]['date_to'];
	$degree_name 		=  $row_ee[0]['degree_name'];
	$study_area 		=  $row_ee[0]['study_area'];
	$date_from			= str_replace("-", "/", convert_date_display($row_ee[0]['date_from']));
	$date_to			= str_replace("-", "/", convert_date_display($row_ee[0]['date_to']));
}?>