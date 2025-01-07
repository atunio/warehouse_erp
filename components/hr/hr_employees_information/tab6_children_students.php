<?php 
if(isset($is_Submit_children_students) && $is_Submit_children_students == 'Y'){ 
	if(decrypt($csrf_token) != $_SESSION["csrf_session"]){ header( "location: signout"); exit(); }
	if(isset($student_id) && $student_id == ""){ $error['msg'] = "Please Select Student"; $student_id_valid	= "invalid";} 
	if(empty($error)){
		// check_id($db, $conn, $id, "employee_profile", $school_admin_id, $selected_db_name);
		if($cmd6 == 'add'){ 
			$sql_ee1 			= "	SELECT a.* FROM ".$selected_db_name.".hr_emp_children_as_students a 
									WHERE a.school_admin_id = '".$school_admin_id."'   
 									AND a.student_id		= '".$student_id."' 
									AND enabled				= 1 ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1); 
			if($counter_ee1 == 0){
				$sql = "INSERT INTO ".$selected_db_name.".hr_emp_children_as_students(school_admin_id, emp_id, student_id, add_date, add_by, add_ip)
						VALUES('".$school_admin_id."', '".$id."', '".$student_id."',  '".$add_date."', '".$_SESSION['username']."', '".$add_ip."')";
				// echo $sql; 
				$ok = $db->query($conn, $sql);
				if($ok){ 
					$student_id = "";
                	$msg['msg_success'] = "Record has been added successfully."; 
				}
				else{
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			}
			else{
				$error['msg'] = "This record already exist.";
			}
		}
		else if($cmd6 == 'edit'){
			check_id($db, $conn, $detail_id, "hr_emp_children_as_students", $school_admin_id, $selected_db_name);
			$sql_ee1 			= "	SELECT a.* FROM ".$selected_db_name.".hr_emp_children_as_students a 
									WHERE a.school_admin_id	= '".$school_admin_id."'  
									AND a.student_id		= '".$student_id."'  
									AND a.id				!= '".$detail_id."'  
									AND enabled				= 1  ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1); 
			if($counter_ee1 == 0){
                $sql_c_up = "UPDATE ".$selected_db_name.".hr_emp_children_as_students SET 	student_id				= '".$student_id."',
																							update_date 			= '".$add_date."',
																							update_by 	 			= '".$_SESSION['username']."',
																							update_ip 	 			= '".$add_ip."'
                WHERE id = '".$detail_id."' AND school_admin_id = '".$school_admin_id."' ";
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
				$error['msg'] = "Record already exist.";
			}
		}
	}
}
else if(isset($cmd6) && $cmd6 == 'delete' && isset($detail_id)){ 
	$sql_del 			= "	UPDATE ".$selected_db_name.".hr_emp_children_as_students SET enabled = 0 WHERE id = '".$detail_id."' ";
	$ok = $db->query($conn, $sql_del);
	if($ok){
		$cmd6           = "add";
		$error['msg']   = "Record Deleted Successfully"; 
	}
	else{
		$error['msg'] = "There is Error, record did not delete, Please check it again OR contact Support Team.";
	}
}
else if(isset($cmd6) && $cmd6 == 'edit' && isset($detail_id)){
	$button_edu 			= "Edit";
	$sql_ee 				= "	SELECT a.* FROM ".$selected_db_name.".hr_emp_children_as_students a 
								WHERE a.id = '".$detail_id."' AND a.school_admin_id = '".$school_admin_id."' ";
	$result_ee 				= $db->query($conn, $sql_ee);
	$row_ee 				= $db->fetch($result_ee);
	$student_id				=  $row_ee[0]['student_id'];   
}?>