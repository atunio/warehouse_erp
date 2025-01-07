<?php 
include('../../path.php');
include($directory_path_for_module_index."conf/session_start.php");
include($directory_path_for_module_index."conf/connection.php");
include($directory_path_for_module_index."conf/functions.php");
$db = new mySqlDB;
if(isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == 'School'){
	$check_module_permission = check_module_permission_user($db, $conn, "hr_monthly_deductions", $_SESSION["user_id"], $_SESSION["school_admin_id"], $_SESSION["user_type"], $_SESSION["db_name"]);
	if($check_module_permission == ""){
		header( "location: ".$directory_path_for_module_index."signout"); exit();
	}
	else{  
		if(isset($_POST['type']) && $_POST['type'] == 'update'){ 
			$db = new mySqlDB;
			$selected_db_name = $_POST["selected_db_name"];
			$record_id 		  = $_POST['record_id'];
			$new_status       = $_POST['value']; 
			$sql_c_up = "UPDATE ".$selected_db_name.".hr_payroll_detail SET enabled			= '".$new_status."',
																			update_date 	= '".$add_date."',
																			update_by 	 	= '".$_SESSION['username']."',
																			update_ip 	 	= '".$add_ip."'
						WHERE id = '".$record_id."' ";
			$ok = $db->query($conn, $sql_c_up);
			echo $ok;
		}
	}
}?>