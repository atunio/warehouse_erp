<?php

include("../conf/session_start.php");
include("../conf/connection.php");
include("../conf/functions.php");
$db 	= new mySqlDB;
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == $project_folder &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {
} else {
	echo redirect_to_page("signin");
	exit();
}
$sql_d 			= "	SELECT a.*, b.profile_pic, b.first_name, b.last_name
					FROM subscribers_users a
					INNER JOIN users b ON b.subscriber_users_id = a.id
					WHERE b.id = '" . $_SESSION["user_id"] . "' ";
//echo $sql_d; die;
$result_d 		= $db->query($conn, $sql_d);
$count_d		= $db->counter($result_d);
if ($count_d == 0) {
	echo redirect_to_page("signout");
	exit();
}
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == $project_folder &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {

	extract($_REQUEST);
	if (isset($string)) {
		//die;
		$parm 				= "?string=" . $string;
		$string 			= decrypt($string);
		$string_explode 	= explode('&', $string);
		$module 			= "";
		$page 				= "";
		$detail_id 			= "";
		$editmaster 		= "";
		$action 			= "";
		foreach ($string_explode as $value) {
			$string_data_explode = explode('=', $value);
			if ($string_data_explode[0] == 'module') {
				$module 			= $string_data_explode[1];
			}
			if ($string_data_explode[0] == 'module_id') {
				$module_id 			= $string_data_explode[1];
			}
			if ($string_data_explode[0] == 'page') {
				$page 	= $string_data_explode[1];
			}
			
		}
	}
	$parm1 = $parm2 = $parm3 = "";$error = [];
	check_session_exist4($db, $conn, $_SESSION["user_id"], $_SESSION["username"], $_SESSION["user_type"], $_SESSION["db_name"], $parm2, $parm3);
	$check_module_permission = "";
	$check_module_permission = check_module_permission($db, $conn, $module_id, $_SESSION["user_id"], $_SESSION["user_type"]);
	if ($check_module_permission == "") {
		echo redirect_to_page("signout");
	} else {
		ob_start(); 
		$filename = "export_product_stock_details_" . date('YmdHis') . ".csv";
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		if (!empty($records)) {
			$i = 0;
			foreach ($records as $row) {
				$i++;
				if (!$heading) {
					echo implode(",", array_keys($row)) . "\n";
					$heading = true;
				}

				$escaped_row = array_map(function ($value, $key) {
					return prevent_excel_date_format($value, $key);
				}, array_values($row), array_keys($row));
				// Output the row after processing
				echo implode(",", $escaped_row) . "\n";
			}
		}	
	}
} else {
	echo redirect_to_page("signin");
}


function enclose_in_quotes($value)
{
	return '"' . $value . '"';
}

function prevent_excel_date_format($value, $column_name)
{
	if ($column_name == 'body_grade' || $column_name == 'lcd_grade' || $column_name == 'digitizer_grade') {
		if ($value != "") {
			$position_s = strpos($value, "-");
			if ($position_s !== false) {
				return "'" . $value;  // Add a single quote for lcd_grade only
			} else {
				return $value;  // Add a single quote for lcd_grade only
			}
		} else return "-";
	} else {
		if ($value == "" || $value == null) {
			return "-";
		} else {
			return $value;
		}
	}
}
