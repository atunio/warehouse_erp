<?php 
if(isset($is_Submit_allowances) && $is_Submit_allowances == 'Y'){ 
	if(decrypt($csrf_token) != $_SESSION["csrf_session"]){ header( "location: signout"); exit(); }
	if(isset($total_amount) && $total_amount == ""){				$error['msg'] = "Enter Amount";        	$total_amount_valid	= "invalid";}
	if(isset($entry_type) && $entry_type == ""){					$error['msg'] = "Select Entry Type";	$entry_type_valid	= "invalid";}
	if(isset($entry_desc) && $entry_desc == ""){ 		            $error['msg'] = "Enter Entry Detail";	$entry_desc_valid  	= "invalid";} 
	if(!isset($id) || (isset($id)  && ($id == "0" || $id == ""))){ 	$error['msg'] = "Please add employee information first"; } 
	if(empty($error)){
		// check_id($db, $conn, $id, "employee_profile", $school_admin_id, $selected_db_name);
		if($cmd5 == 'add'){ 
			$sql_ee1 			= "	SELECT a.* FROM ".$selected_db_name.".hr_other_allowances_or_benefits a 
									WHERE a.school_admin_id = '".$school_admin_id."'   
									AND a.emp_id			= '".$id."'    
									AND a.entry_type    	= '".$entry_type."' 
									AND a.entry_desc		= '".$entry_desc."' 
									AND a.total_amount		= '".$total_amount."' 
									AND enabled				= 1 ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1); 
			if($counter_ee1 == 0){
				$sql = "INSERT INTO ".$selected_db_name.".hr_other_allowances_or_benefits(school_admin_id, emp_id, entry_desc, entry_type, total_amount, 
																							add_date, add_by, add_ip)
						VALUES('".$school_admin_id."', '".$id."', '".$entry_desc."', '".$entry_type."', '".$total_amount."', '".$add_date."', '".$_SESSION['username']."', '".$add_ip."')";
				// echo $sql; 
				$ok = $db->query($conn, $sql);
				if($ok){ 
					$entry_desc = $entry_type = $total_amount = "";
                	$msg['msg_success'] = "Allowance or Benefit has been added successfully."; 
				}
				else{
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			}
			else{
				$error['msg'] = "This record already exist.";
			}
		}
		else if($cmd5 == 'edit'){
			check_id($db, $conn, $detail_id, "hr_other_allowances_or_benefits", $school_admin_id, $selected_db_name);
			$sql_ee1 			= "	SELECT a.* FROM ".$selected_db_name.".hr_other_allowances_or_benefits a 
									WHERE a.school_admin_id	= '".$school_admin_id."' 
									AND a.emp_id			= '".$id."'   
									AND a.entry_desc		= '".$entry_desc."' 
									AND a.entry_type		= '".$entry_type."' 
									AND a.total_amount		= '".$total_amount."'
									AND a.id				!= '".$detail_id."'  
									AND enabled				= 1  ";
			$result_ee1 			= $db->query($conn, $sql_ee1);
			$counter_ee1			= $db->counter($result_ee1); 
			if($counter_ee1 == 0){
                $sql_c_up = "UPDATE ".$selected_db_name.".hr_other_allowances_or_benefits SET
																								entry_desc				= '".$entry_desc."',
																								entry_type				= '".$entry_type."',
																								total_amount			= '".$total_amount."',
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
else if(isset($cmd5) && $cmd5 == 'delete' && isset($detail_id)){ 
	$sql_del 			= "	UPDATE ".$selected_db_name.".hr_other_allowances_or_benefits SET enabled = 0 WHERE id = '".$detail_id."' ";
	$ok = $db->query($conn, $sql_del);
	if($ok){
		$cmd5           = "add";
		$error['msg']   = "Record Deleted Successfully"; 
	}
	else{
		$error['msg'] = "There is Error, record did not delete, Please check it again OR contact Support Team.";
	}
}
else if(isset($cmd5) && $cmd5 == 'edit' && isset($detail_id)){
	$button_edu 			= "Edit";
	$sql_ee 				= "	SELECT a.* FROM ".$selected_db_name.".hr_other_allowances_or_benefits a 
								WHERE a.id = '".$detail_id."' AND a.school_admin_id = '".$school_admin_id."' ";
	$result_ee 				= $db->query($conn, $sql_ee);
	$row_ee 				= $db->fetch($result_ee);
	$entry_desc				=  $row_ee[0]['entry_desc'];   
	$entry_type 			=  $row_ee[0]['entry_type']; 
	$total_amount			=  $row_ee[0]['total_amount']; 
}?>