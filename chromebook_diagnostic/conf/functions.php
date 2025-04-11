<?php
$timezone = "America/New_York";
date_default_timezone_set($timezone);
define("TIME_ZONE", $timezone);
$test_on_local					= 0; // For Live
$logistics_page_status			= "1,4,10, 11";
$rma_process_status 			= "8, 16, 17, 18, 19, 21, 22, 23, 24";
$repaire_status_ids 			= "5, 19, 20";
$receive_status_dynamic			= "3";
$logistic_status_dynamic		= "4";
$diagnost_status_dynamic		= "5";
$arrival_status_dynamic			= "12";
$before_logistic_status_dynamic	= "1";
$inventory_status_dynamic		= "14";
$tested_or_graded_status 		= 5;
$status_ids_rma_status 			= 7;

$add_ip 					= $_SERVER['REMOTE_ADDR'];
$add_date 					= date("Y-m-d H:i:s");
$project_domain 			= "creativeworkflows.ai";
$project_domain2 			= "creativeworkflows.ai";
$project_name				= "Creative Work Flows";
$project_folder				= "albert_warehouse_erp";
$emailProvider 				= "sendGrid";
$admin_email 				= "info@creativeworkflows.ai";
define("PROJECT_TITLE", "Warehouse ERP");
define("PROJECT_TITLE2", "Warehouse ERP");
define("FROMEMAIL", "info@creativeworkflows.ai");
define("FROMNAME", "Warehouse ERP");
define("SUB_DOMAIN_STATIC", "albert_warehouse_erp");
$subdomain = SUB_DOMAIN_STATIC;
$selected_for_test_on_local = "albert_warehouse_erp";
if ($_SERVER['HTTP_HOST'] == HTTP_HOST_IP) {
	$test_on_local = 1;
	define("HTTP_HOST", $_SERVER['HTTP_HOST'] . "/" . $project_folder);
	$http_protocol = "http://";
	$selected_db_name = $selected_for_test_on_local;
} else {
	define("HTTP_HOST", $_SERVER['HTTP_HOST']);
	$http_protocol = "https://";
	$selected_db_name = "dbetunpprdklld";   // 32
}
define("PROJECT_URL", $http_protocol . HTTP_HOST);
define("HTTP_PROTOCOL", $http_protocol);
///////////////////////////////////////// MAC Address START////////////////////////
/* * Getting MAC Address using PHP
* Md. Nazmul Basher */
$mac_address = "";
ob_start(); // Turn on output buffering
system('ipconfig /all'); //Execute external program to display output
$mycom	= ob_get_contents(); // Capture the output into a variable
ob_clean(); // Clean (erase) the output buffer
$findme 		= "Physical";
$pmac 			= strpos($mycom, $findme); // Find the position of Physical text
$mac_address	= substr($mycom, ($pmac + 36), 17); // Get Physical Address
///////////////////////////////////////// MAC Address END////////////////////////

$todaymysql_date_comparison	= date("Ymd");
$time_auth_code_now			= date("Y-m-d H:i");

$phoneCheck_apiKey 			= "3b6dff0d-ecbb-424a-baf1-c6bbfffdbef2";

class mySqlDB
{
	// Methods
	function query($conn, $query)
	{
		return mysqli_query($conn, $query);
	}
	function counter($result)
	{
		return mysqli_num_rows($result);
	}
	function fetch($result)
	{
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
		}
		return $data;
	}
}
function addZero($number)
{
	if ($number < 10 || $number == 0) $number = "0" . $number;
	return $number;
}
function setTimezone($timezone)
{
	if ($timezone == 'undefined' || $timezone == 'PST8PDT' || $timezone == 'UTC' || $timezone == '') {
		$timezone = "Asia/Karachi";
		date_default_timezone_set($timezone);
	} else {
		date_default_timezone_set($timezone);
	}
	return $timezone;
}
function convertTimeAMPM($time)
{
	$time_Array = explode(':', $time);
	$hour 		= $time_Array[0];
	$minutes 	= $time_Array[1];
	if ($hour > 11 && $hour <= 24) $ampm = "PM";
	else $ampm = "AM";
	if ($hour > 12) $hour = $hour - 12;
	if ($hour < 10 && $hour > 0) $hour = "0" . $hour;
	else if ($hour == '00') $hour = "12";
	else if ($hour == '0') $hour = "12";
	$time = $hour . ":" . $minutes . " " . $ampm;
	return $time;
}
function timeAMPMWithSeconds($time)
{
	$time_Array = explode(':', $time);
	$hour 		= $time_Array[0];
	$minutes 	= $time_Array[1];
	$seconds 	= $time_Array[2];
	if ($hour > 11 && $hour <= 24) $ampm = "PM";
	else $ampm = "AM";
	if ($hour > 12) $hour = $hour - 12;
	if ($hour == '00') $hour = "12";
	else if ($hour == '0') $hour = "12";
	$time = $hour . ":" . $minutes . ":" . $seconds . " " . $ampm;
	return $time;
}
//////////////// Function to reset session if record does not found in table ///////////
function site_traffic($db, $conn, $page, $timezone)
{

	$add_ip 		= $_SERVER['REMOTE_ADDR'];
	$add_date 		=  date("Y-m-d H:i:s");
	////// Traffic history  START //////////////
	$sql_traffic1 		= "SELECT * FROM site_traffic_detail WHERE page_name = '" . $page . "' AND user_ip = '" . $add_ip . "' ";
	//echo $sql_traffic1;die;
	$result_traffic1 	= $db->query($conn, $sql_traffic1);
	$count_traffic1 	= $db->counter($result_traffic1);
	if ($count_traffic1 == 0) {
		$sql_inst1 = "INSERT INTO site_traffic_detail (page_name, user_ip, total_views, timezone, add_date)
												values('" . $page . "', '" . $add_ip . "', 1, '" . $timezone . "', '" . $add_date . "') ";
		$db->query($conn, $sql_inst1);
	} else {
		$row_traffic1 	= $db->fetch($result_traffic1);
		$total_views 	= $row_traffic1[0]['total_views'];
		$total_views 	= $total_views + 1;

		$sql_upd1 = "UPDATE site_traffic_detail SET total_views = '" . $total_views . "', visitor_detail_seen = 0,  update_date = '" . $add_date . "'
						WHERE page_name = '" . $page . "' AND user_ip = '" . $add_ip . "' ";
		$db->query($conn, $sql_upd1);
	}
	////// Traffic history  END //////////////
}
//////////////// Disallow Direct Access Admin ///////////
function disallow_direct_school_directory_access()
{
	echo '<script> location.replace("' . HTTP_PROTOCOL . HTTP_HOST . '/signout"); </script>';
	exit();
	//*/
}
//////////////// Disallow Direct Access Super Admin ///////////
function disallow_direct_sadmin_directory_access()
{
	echo '<script> location.replace("' . HTTP_PROTOCOL . HTTP_HOST . '/sadmin/signout"); </script>';
	exit();
	//*/
}
function access($perm_type)
{
	$selected_db_name 	= $_SESSION["db_name"];
	$user_id         	= $_SESSION["user_id"];
	$menu_id 			= $_SESSION["module_menue_id"];
	$db 				= $_SESSION["db"];
	$conn 				= $_SESSION["conn"];
	$output = 0;
	if ($_SESSION["user_type"] != 'Admin') {
		$sql	= " SELECT *
					FROM " . $selected_db_name . ".sub_users_role_permissions a
					INNER JOIN " . $selected_db_name . ".sub_users_user_roles b ON b.role_id = a.role_id
					WHERE b.user_id = '" . $user_id . "'
					AND a.menu_id = '" . $menu_id . "'  ";
		if ($perm_type == "add_perm") {
			$sql 		.= " AND add_perm = 1";
		}
		if ($perm_type == "edit_perm") {
			$sql 		.= " AND edit_perm = 1";
		}
		if ($perm_type == "delete_perm") {
			$sql 		.= " AND delete_perm = 1";
		}
		if ($perm_type == "view_perm") {
			$sql 		.= " AND view_perm = 1";
		}
		if ($perm_type == "print_perm") {
			$sql 		.= " AND print_perm = 1";
		}
		// echo $sql;die;
		$result		= $db->query($conn, $sql);
		$counter	= $db->counter($result);
		if ($counter > 0) {
			$output = 1;
		}
	} else {
		$output = 1;
	}
	return $output;
}

function po_permisions($perm_type)
{
	$selected_db_name 	= $_SESSION["db_name"];
	$user_id         	= $_SESSION["user_id"];
	$menu_id 			= $_SESSION["module_menue_id"];
	$db 				= $_SESSION["db"];
	$conn 				= $_SESSION["conn"];
	$output = 0;
	if ($_SESSION["user_type"] != 'Admin') {
		$sql	= " SELECT *
					FROM " . $selected_db_name . ".sub_users_role_permissions a
					INNER JOIN " . $selected_db_name . ".sub_users_user_roles b ON b.role_id = a.role_id
					WHERE b.user_id = '" . $user_id . "'
					AND a.menu_id = '" . $menu_id . "' 
					AND FIND_IN_SET(  '" . $perm_type . "' , special_module_permisions) > 0 ";
		$result		= $db->query($conn, $sql);
		$counter	= $db->counter($result);
		if ($counter > 0) {
			$output = 1;
		}
	} else {
		$output = 1;
	}
	return $output;
}
//////////////// Function to reset session of Super Admin if record does not found in table ///////////
function check_session_exist2($db, $conn, $user_id, $username, $user_type)
{
	$sql 		= "SELECT * FROM super_admin
					WHERE enabled = 1
					AND id 			= '" . $user_id . "'
					AND username 	= '" . $username . "'
					AND user_type 	= '" . $user_type . "' "; //echo $sql;die;
	$result 	= $db->query($conn, $sql);
	$count 		= $db->counter($result);
	///*
	if ($count == 0) {
		session_unset();
		session_destroy();
		echo '<script> location.replace("' . HTTP_PROTOCOL . HTTP_HOST . '/sadmin/signout"); </script>';
		exit();
	}
	//*/
}
//////////////// Function to reset session of School Login if record does not found in table ///////////
function check_session_exist4($db, $conn, $user_id, $username, $user_type, $db_name, $parm2, $parm3)
{
	$sql 		= " SELECT * FROM users
					WHERE id = '" . $user_id . "'
					AND username = '" . $username . "' 
					AND user_type = '" . $user_type . "' 
					AND enabled = 1 "; //echo $sql;die;
	$result 	= $db->query($conn, $sql);
	$count 		= $db->counter($result);
	///*
	if ($count == 0) {
		session_unset();
		session_destroy();
		echo '<script> location.replace("' . HTTP_PROTOCOL . HTTP_HOST . '/signout"); </script>';
		exit();
	}
	//*/
}
//////////////// Function to reset session of User Login if record does not found in table ///////////
function check_session_exist3($db, $conn, $user_id, $username, $user_type)
{
	$sql 		= "SELECT * FROM users
					WHERE enabled = 1
					AND id 			= '" . $user_id . "'
					AND username 	= '" . $username . "'
					AND user_type 	= '" . $user_type . "' "; //echo $sql;die;
	$result 	= $db->query($conn, $sql);
	$count 		= $db->counter($result);
	///*
	if ($count == 0) {
		session_unset();
		session_destroy();
		echo '<script> location.replace("' . HTTP_PROTOCOL . HTTP_HOST . '/signout"); </script>';
		exit();
	}
	//*/
}
//////////////// Function check menu permissions For Super Admin ///////////
function check_menu_permissions_super_admin($db, $conn, $user_id, $menu_id)
{
	$sql 		= "	SELECT * FROM super_admin_role_permissions a
					INNER JOIN super_admin_roles b ON b.id = a.role_id
					INNER JOIN super_admin_user_roles c ON c.role_id = b.id
					WHERE c.user_id = '" . $user_id . "'
					AND a.menu_id 	= '" . $menu_id . "' "; //echo $sql;//die;
	$result 	= $db->query($conn, $sql);
	$count 		= $db->counter($result);
	if ($count > 0) {
		return 1;
	} else {
		return 0;
	}
}
//////////////// Function check menu permissions For School Users ///////////
function check_menu_permissions($db, $conn, $user_id, $subscriber_users_id, $user_type, $menu_id, $db_name, $parm2, $parm3)
{
	$sql 		= "	SELECT * FROM role_permissions a
					INNER JOIN roles b ON b.id = a.role_id
					INNER JOIN user_roles c ON c.role_id = b.id
					WHERE  c.subscriber_users_id = '" . $subscriber_users_id . "' 
					AND a.menu_id 	= '" . $menu_id . "' ";
	// echo $sql;die;
	$result 	= $db->query($conn, $sql);
	$count 		= $db->counter($result);
	if ($user_type == 'Admin') {
		if ($count > 0) {
			return 1;
		} else {
			return 0;
		}
	} else {
		if ($count > 0) {
			$sql 		= "	SELECT * FROM sub_users_role_permissions a
							INNER JOIN sub_users_roles b ON b.id = a.role_id
							INNER JOIN sub_users_user_roles c ON c.role_id = b.id
							WHERE  c.user_id = '" . $user_id . "' 
							AND a.menu_id 	= '" . $menu_id . "' ";
			// echo $sql; die;
			$result 	= $db->query($conn, $sql);
			$count 		= $db->counter($result);
			if ($count > 0) {
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
}
//////////////// Function check menu child ///////////
function check_module_permission_super_admin($db, $conn, $module, $user_id)
{
	$sql 		= "	SELECT a.* FROM super_admin_menus a
					INNER JOIN super_admin_role_permissions b ON b.menu_id = a.id
					INNER JOIN super_admin_user_roles c ON c.role_id = b.role_id
					WHERE a.enabled = 1 AND b.enabled = 1
					AND a.folder_name 	= '" . $module . "'
					AND c.user_id 		= '" . $user_id . "' ";
	$result 	= $db->query($conn, $sql); //echo $sql;die;
	$count 		= $db->counter($result);
	if ($count > 0) {
		$row = $db->fetch($result);
		return $row[0]['menu_name'];
	} else {
		return "";
	}
}
//////////////// Function check menu child ///////////
function check_module_permission($db, $conn, $module_id, $user_id, $user_type)
{
	if ($user_type == 'Admin') {
		$sql 		= " SELECT a.menu_name 
						FROM menus a
						INNER JOIN role_permissions b ON b.menu_id = a.id
						INNER JOIN user_roles c ON c.role_id = b.role_id
						WHERE a.enabled	= 1
						AND b.enabled	= 1
						AND a.id		= '" . $module_id . "'
						ORDER BY b.id DESC LIMIT 1 ";
		$result 	= $db->query($conn, $sql); //echo $sql;die;
		$count 		= $db->counter($result);
		if ($count > 0) {
			$row = $db->fetch($result);
			return $row[0]['menu_name'];
		} else {
			return "";
		}
	} else {
		$sql 		= " SELECT a.menu_name 
						FROM menus a
						INNER JOIN sub_users_role_permissions b ON b.menu_id = a.id
						INNER JOIN sub_users_user_roles c ON c.role_id = b.role_id
						WHERE a.enabled 		= 1
						AND b.enabled 			= 1
						AND a.id 		= '" . $module_id . "'
						AND c.user_id 	= '" . $user_id . "'
						ORDER BY b.id DESC LIMIT 1 ";
		$result 	= $db->query($conn, $sql); //echo $sql;die;
		$count 		= $db->counter($result);
		if ($count > 0) {
			$row = $db->fetch($result);
			return $row[0]['menu_name'];
		} else {
			return "";
		}
	}
}
//////////////// Function check menu child ///////////
function check_menu_child_super_admin($db, $conn, $parent_id, $m_level)
{
	$sql 		= "	SELECT * FROM super_admin_menus
					WHERE parent_id = '" . $parent_id . "'
					AND m_level 	= '" . $m_level . "' 
					AND enabled 	= 1 ORDER BY sort_order ";
	//echo $sql;die;
	$result 	= $db->query($conn, $sql);
	$count 		= $db->counter($result);
	if ($count > 0) {
		return 1;
	} else {
		return 0;
	}
}
//////////////// Function check menu child ///////////
function check_id($db, $conn, $id, $table_name)
{
	$sql 		= "	SELECT * FROM " . $table_name . "
					WHERE id = '" . $id . "'  ";
	$result 	= $db->query($conn, $sql);
	$count 		= $db->counter($result);
	if ($count == 0) {
		session_unset();
		session_destroy();
		echo '<script> location.replace("' . HTTP_PROTOCOL . HTTP_HOST . '/signout"); </script>';
		exit();
	}
}
//////////////// Function check menu child ///////////
function check_menu_child($db, $conn, $parent_id, $m_level)
{
	$sql 		= "	SELECT * FROM menus
					WHERE parent_id = '" . $parent_id . "'
					AND m_level 	= '" . $m_level . "' 
					AND enabled 	= 1 ORDER BY sort_order ";
	// echo $sql;
	$result 	= $db->query($conn, $sql);
	$count 		= $db->counter($result);
	if ($count > 0) {
		return 1;
	} else {
		return 0;
	}
}
function insert_error($db, $conn, $error_type, $field_name, $field_value, $error_msg, $error_page_name)
{

	$add_ip 		= $_SERVER['REMOTE_ADDR'];
	$add_date 		=  date("Y-m-d H:i:s");
	$timezone 		= date_default_timezone_get();
	$field_value 	= trim(htmlspecialchars(strip_tags(stripslashes($field_value)), ENT_QUOTES, 'UTF-8'));
	$error_msg 		= trim(htmlspecialchars(strip_tags(stripslashes($error_msg)), ENT_QUOTES, 'UTF-8'));

	$sql_error 		= "INSERT INTO error_log (error_type, field_name, field_value, error_msg, error_page_name,
																				timezone, add_date, add_ip)
						VALUES ('" . $error_type . "', '" . $field_name . "','" . $field_value . "', '" . $error_msg . "', '" . $error_page_name . "',
																'" . $timezone . "', '" . $add_date . "', '" . $add_ip . "')";
	$db->query($conn, $sql_error);
}
function remove_special_character($field)
{
	// single and double codes = &#039;, &quot;
	$field = str_replace(array("'", "$", "\"", "&#039;", "&quot;", "=", "||", "%"), "", $field);
	return $field;
}
function test_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
// date conversion from british to mysql compatible
function convert_date_mysql($date)
{
	$date_explode = explode("-", $date);

	$day = $date_explode[0];
	$month = $date_explode[1];
	$year = $date_explode[2];

	$mysql_date = $year . "-" . $month . "-" . $day;
	if ($mysql_date == '--') $mysql_date = '';

	return $mysql_date;
}
// date conversion from british to mysql compatible
function convert_date_mysql_slash($date)
{
	$date_explode = explode("/", $date);

	$day = $date_explode[0];
	$month = $date_explode[1];
	$year = $date_explode[2];

	$mysql_date = $year . "-" . $month . "-" . $day;
	if ($mysql_date == '--') $mysql_date = '';

	return $mysql_date;
}
// date conversion from mysql to british
function convert_date_display($date)
{

	if ($date != '0000-00-00' && $date != ''  && $date != "0000-00-00 00:00:00") {
		$date_explode = explode("-", $date);

		$year = $date_explode[0];
		$month = $date_explode[1];
		$day = $date_explode[2];

		$mysql_date = trim($day) . "-" . trim($month) . "-" . trim($year);
	} else {
		$mysql_date = '';
	}
	return $mysql_date;
}
function sort_date_format($date, $sort_type_current, $sort_type_new, $separator, $new_separator)
{ //  type = 'Mysql or USA OR Normal'
	$date_explode 	= explode($separator, $date);

	if (sizeof($date_explode) == 3) {
		$date_array1 	= $date_explode[0];
		$date_array2 	= $date_explode[1];
		$date_array3 	= $date_explode[2];
		if ($sort_type_current == 'Mysql' && $sort_type_new == 'USA') { // Mysql 2020-03-25
			$retundate = $date_array2 . $new_separator . $date_array3 . $new_separator . $date_array1;
		} else if ($sort_type_current == 'Mysql' && $sort_type_new == 'Normal') { // Mysql 2020-03-25
			$retundate = $date_array3 . $new_separator . $date_array2 . $new_separator . $date_array1;
		} else if ($sort_type_current == 'USA' && $sort_type_new == 'Normal') { // USA 03-25-2020
			$retundate = $date_array2 . $new_separator . $date_array1 . $new_separator . $date_array3;
		} else if ($sort_type_current == 'USA' && $sort_type_new == 'Mysql') { // USA 03-25-2020
			$retundate = $date_array3 . $new_separator . $date_array1 . $new_separator . $date_array2;
		} else if ($sort_type_current == 'Normal' && $sort_type_new == 'Mysql') { // Normal 25-03-2020
			$retundate = $date_array3 . $new_separator . $date_array2 . $new_separator . $date_array1;
		} else if ($sort_type_current == 'Normal' && $sort_type_new == 'USA') { // Normal 25-03-2020
			$retundate = $date_array2 . $new_separator . $date_array1 . $new_separator . $date_array3;
		}
	} else {
		$retundate = "";
	}
	return $retundate;
}
function replace_date_separator($date, $separator, $new_separator)
{
	$date_explode = explode($separator, $date);
	if (sizeof($date_explode) == 3) {
		$month 	= $date_explode[0];
		$day 	= $date_explode[1];
		$year 	= $date_explode[2];
		$dateDashes = $month . $new_separator . $day . $new_separator . $year;
	} else {
		$dateDashes = "";
	}
	return $dateDashes;
}
function remove_date_separator($date, $separator)
{
	$date_explode = explode($separator, $date);
	if (sizeof($date_explode) == 3) {
		$day 	= $date_explode[0];
		$month 	= $date_explode[1];
		$year 	= $date_explode[2];
		$new_date = $day . $month . $year;
	} else {
		$new_date = "";
	}
	return $new_date;
}
function dateformat1($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
		$date = date_create($date);
		$date = date_format($date, "F d, Y");
	} else {
		$date = "";
	}
	return $date;
}
function dateformat2($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
		$date = date_create($date);
		$date = date_format($date, "M d, Y");
	} else {
		$date = "";
	}
	return $date;
}
function dateformat3($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
		$date = date_create($date);
		$date = date_format($date, "d/m/Y");
	} else {
		$date = "";
	}
	return $date;
}
function dateformat4($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
		$date = date_create($date);
		$date = date_format($date, "m/d");
	} else {
		$date = "";
	}
	return $date;
}
function dateformat5($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
		$date = date_create($date);
		$date = date_format($date, "Y/m/d");
	} else {
		$date = "";
	}
	return $date;
}
function display_trip_duration($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
		$date = date_create($date);
		$date = date_format($date, "m/d");
	} else {
		$date = "";
	}
	return $date;
}

function dateformat1_with_time($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
		$date = date_create($date);
		$date = date_format($date, "M d, Y h:i:s A");
	} else {
		$date = "";
	}
	return $date;
}

function dateformat1_with_time_USA($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
		$date = date_create($date);
		$date = date_format($date, "d/m/Y h:i A");
	} else {
		$date = "";
	}
	return $date;
}
function convert_date_display_date_from_datetime($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
		$date = date_create($date);
		$date = date_format($date, "d-m-Y");
	} else {
		$date = "";
	}
	return $date;
}
function convert_date_display_date_from_datetime2($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
		$date = date_create($date);
		$date = date_format($date, "d/m/Y");
	} else {
		$date = "";
	}
	return $date;
}
function convert_date_display_time_from_datetime($date)
{
	$time = "";
	if ($date != NULL && $date != "") {
		if (substr($date, 11, 8) != '00:00:00') {
			$date = date_create($date);
			$time = date_format($date, "h:i A");
		}
	}
	return $time;
}
function convert_month_letter($month)
{
	return date("F", mktime(null, null, null, $month));
}
function dateDifference($date_1, $date_2, $differenceFormat = '%a')
{
	$datetime1 = date_create($date_1);
	$datetime2 = date_create($date_2);

	$interval = date_diff($datetime1, $datetime2);

	return $interval->format($differenceFormat);

	//////////////////////////////////////////////////////////////////////
	//PARA: Date Should In YYYY-MM-DD Format
	//RESULT FORMAT:
	// '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
	// '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
	// '%m Month %d Day'                                            =>  3 Month 14 Day
	// '%d Day %h Hours'                                            =>  14 Day 11 Hours
	// '%d Day'                                                        =>  14 Days
	// '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
	// '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
	// '%h Hours                                                    =>  11 Hours
	// '%a Days                                                        =>  468 Days

}
function displayPost_Time($date_difference)
{
	$break_date_time = explode(" ", $date_difference);
	$date_array 	= explode("-", $break_date_time[0]);
	$time_array 	= explode(":", $break_date_time[1]);

	$years 			= $date_array[0];
	$months 		= $date_array[1];
	$days 			= $date_array[2];

	$hours 			= $time_array[0];
	$minutes 		= $time_array[1];
	$seconds 		= $time_array[2];
	$ago 			= "Ago";
	$post_Time 	= "";
	$comma 		= "";
	if ($years > 0) {
		if ($years > 1) {
			$post_Time .= $years . " Years";
		} else {
			$post_Time .= $years . " Year";
		}
		$comma 		= ", ";
	}
	if ($months > 0) {
		if ($months > 1) {
			$post_Time .= $comma . $months . " Months";
		} else {
			$post_Time .= $comma . $months . " Month";
		}
		$comma 		= " and ";
	}
	if ($days > 0) {
		if ($days > 1) {
			$post_Time .= $comma . $days . " Days";
		} else {
			$post_Time .= $comma . $days . " Day";
		}
	}
	if ($years == '0' && $months == '0' && $days == '0') {
		$post_Time 	= "";
		$comma 		= "";
		if ($hours > 0) {
			if ($hours > 1) {
				$post_Time .= $hours . " Hours";
			} else {
				$post_Time .= $hours . " Hour";
			}
			$comma 		= " and ";
		}
		if ($minutes > 0) {
			if ($minutes > 1) {
				$post_Time .= $comma . $minutes . " Minutes";
			} else {
				$post_Time .= $comma . $minutes . " Minute";
			}
			$comma 		= ", ";
		}
		if ($hours == '0' && $minutes == '0') {
			if ($seconds > 1) {
				$post_Time .= $comma . $seconds . " Seconds";
			} else {
				$post_Time .= $comma . " Just Added ";
				$ago = "";
			}
		}
	}
	return $post_Time . " " . $ago;
}
// Compress image
function compressImage($source, $destination, $quality)
{
	$info = getimagesize($source);
	if ($info['mime'] == 'image/jpeg')
		$image = imagecreatefromjpeg($source);
	elseif ($info['mime'] == 'image/gif')
		$image = imagecreatefromgif($source);
	elseif ($info['mime'] == 'image/png')
		$image = imagecreatefrompng($source);
	imagejpeg($image, $destination, $quality);
}
function imageWidthHeight($image)
{
	$info = getimagesize($image);
	list($width, $height, $type, $attr) = getimagesize($image);
	//echo "Width of image : " . $width . "<br>"."Height of image : " . $height . "<br>";
	//echo "Image type :" . $type . "<br>"."Image attribute :" .$attr;
	$imageWidthHeight[] = $width;
	$imageWidthHeight[] = $height;
	return $imageWidthHeight;
}
function resizePicture($targetPath,  $original_img_width,  $original_img_height,  $new_width, $new_pic_name, $saveDirctory)
{

	$perc_reduce 			= 100 - (($new_width / $original_img_width) * 100);
	$new_height 			= $original_img_height - (($original_img_height / 100) * $perc_reduce);
	// (1) READ THE ORIGINAL IMAGE
	$info = getimagesize($targetPath);
	if ($info['mime'] == 'image/jpeg') 		$original = imagecreatefromjpeg($targetPath);
	elseif ($info['mime'] == 'image/gif') 	$original = imagecreatefromgif($targetPath);
	elseif ($info['mime'] == 'image/png') 	$original = imagecreatefrompng($targetPath);
	// (2) EMPTY CANVAS WITH REQUIRED DIMENSIONS
	$resized = imagecreatetruecolor($new_width, $new_height); // SMALLER
	// (3) RESIZE THE IMAGE
	imagecopyresampled($resized, $original, 0, 0, 0, 0, $new_width, $new_height, $original_img_width, $original_img_height);
	// (4) SAVE/OUTPUT RESIZED IMAGE
	$brand_logo_sm = $new_pic_name . ".jpeg";
	$resized_path = $saveDirctory . "/" . $brand_logo_sm;
	imagejpeg($resized, $resized_path);
	// (5) OPTIONAL - CLEAN UP
	imagedestroy($original);
	imagedestroy($resized);

	return $brand_logo_sm;
}
/// For Height still neeed test //////////
function resizePictureSetHeight($targetPath,  $original_img_width,  $original_img_height,  $new_height, $new_pic_name, $saveDirctory)
{

	$perc_reduce 			= 100 - (($new_height / $original_img_height) * 100);
	$new_width 				= $original_img_width - (($original_img_width / 100) * $perc_reduce);
	// (1) READ THE ORIGINAL IMAGE
	$info = getimagesize($targetPath);
	if ($info['mime'] == 'image/jpeg') 		$original = imagecreatefromjpeg($targetPath);
	elseif ($info['mime'] == 'image/gif') 	$original = imagecreatefromgif($targetPath);
	elseif ($info['mime'] == 'image/png') 	$original = imagecreatefrompng($targetPath);
	// (2) EMPTY CANVAS WITH REQUIRED DIMENSIONS
	$resized = imagecreatetruecolor($new_width, $new_height); // SMALLER
	// (3) RESIZE THE IMAGE
	imagecopyresampled($resized, $original, 0, 0, 0, 0, $new_width, $new_height, $original_img_width, $original_img_height);
	// (4) SAVE/OUTPUT RESIZED IMAGE
	$img_name = $new_pic_name . ".jpeg";
	$resized_path = $saveDirctory . "/" . $img_name;
	imagejpeg($resized, $resized_path);
	// (5) OPTIONAL - CLEAN UP
	imagedestroy($original);
	imagedestroy($resized);
	return $img_name;
}
function encrypt($sData)
{
	$sKey = '24234#dd1133a$a123-*';
	$sResult = '';
	for ($i = 0; $i < strlen($sData); $i++) {
		$sChar    = substr($sData, $i, 1);
		$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
		$sChar    = chr(ord($sChar) + ord($sKeyChar));
		$sResult .= $sChar;
	}
	return encode_base64($sResult);
}
function decrypt($sData)
{
	$sKey = '24234#dd1133a$a123-*';
	$sResult = '';
	$sData   = decode_base64($sData);

	for ($i = 0; $i < strlen($sData); $i++) {
		$sChar    = substr($sData, $i, 1);
		$sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);
		$sChar    = chr(ord($sChar) - ord($sKeyChar));
		$sResult .= $sChar;
	}
	return $sResult;
}
function encode_base64($sData)
{
	$sBase64 = base64_encode($sData);
	return strtr($sBase64, '+/', '-_');
}
function decode_base64($sData)
{
	$sBase64 = strtr($sData, '-_', '+/');
	return base64_decode($sBase64);
}

function sendEmailSendGrid($subject_to, $toEmail, $toname, $body, $parm1, $parm2, $parm3, $parm4, $parm5, $parm6)
{
	$email = new \SendGrid\Mail\Mail();
	$sendgrid = new \SendGrid('');
	$email->setFrom(FROMEMAIL, FROMNAME);
	$email->setSubject($subject_to);
	$email->addTo($toEmail, $toname);
	$email->addContent("text/html", $body);
	try {
		$response = $sendgrid->send($email);
	} catch (Exception $e) {
		$response = "";
	}
	return $response;
}
function tep_db_prepare_input($string)
{
	if (is_string($string)) {
		return trim(stripslashes($string));
	} elseif (is_array($string)) {
		reset($string);
		while (list($key, $value) = each($string)) {
			$string[$key] = tep_db_prepare_input($value);
		}
		return $string;
	} else {
		return $string;
	}
}
function numberFormatLeadingZeros($number, $digitsLimit)
{ // numberLimit= how much digits should at least
	$value = str_pad($number, $digitsLimit, '0', STR_PAD_LEFT);
	return $value;
}
function nameFormat1($first_name, $middle_name, $last_name)
{
	$std_full_name = "";
	if ($last_name == "") {
		$std_full_name = $middle_name . " " . $first_name;
	} else if ($middle_name == "") {
		$std_full_name = $last_name . ", " . $first_name;
	} else {
		$std_full_name = $last_name . ", " . $middle_name . " " . substr($first_name, 0, 1);
	}
	echo $std_full_name;
}
function nameFormat_return($first_name, $middle_name, $last_name)
{
	$std_full_name = "";
	if ($last_name == "") {
		$std_full_name = $middle_name . " " . $first_name;
	} else if ($middle_name == "") {
		$std_full_name = $last_name . ", " . $first_name;
	} else {
		$std_full_name = $last_name . ", " . $middle_name . " " . substr($first_name, 0, 1);
	}
	return $std_full_name;
}
function getIndianCurrency(float $number)
{
	$decimal = round($number - ($no = floor($number)), 2) * 100;
	$hundred = null;
	$digits_length = strlen($no);
	$i = 0;
	$str = array();
	$words = array(
		0 => '',
		1 => 'one',
		2 => 'two',
		3 => 'three',
		4 => 'four',
		5 => 'five',
		6 => 'six',
		7 => 'seven',
		8 => 'eight',
		9 => 'nine',
		10 => 'ten',
		11 => 'eleven',
		12 => 'twelve',
		13 => 'thirteen',
		14 => 'fourteen',
		15 => 'fifteen',
		16 => 'sixteen',
		17 => 'seventeen',
		18 => 'eighteen',
		19 => 'nineteen',
		20 => 'twenty',
		30 => 'thirty',
		40 => 'forty',
		50 => 'fifty',
		60 => 'sixty',
		70 => 'seventy',
		80 => 'eighty',
		90 => 'ninety'
	);
	$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');

	while ($i < $digits_length) {
		$divider = ($i == 2) ? 10 : 100;
		$number = floor($no % $divider);
		$no = floor($no / $divider);
		$i += $divider == 10 ? 1 : 2;
		if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
		} else $str[] = null;
	}

	$rupees = implode('', array_reverse($str));
	$paise = '';

	// if ($decimal) {
	//     $paise = 'and ';
	//     $decimal_length = strlen($decimal);

	//     if ($decimal_length == 2) {
	//         if ($decimal >= 20) {
	//             $dc = $decimal % 10;
	//             $td = $decimal - $dc;
	//             $ps = ($dc == 0) ? '' : '-' . $words[$dc];

	//             $paise .= $words[$td] . $ps;
	//         } else {
	//             $paise .= $words[$decimal];
	//         }
	//     } else {
	//         $paise .= $words[$decimal % 10];
	//     }
	//     $paise .= 'Cent';
	// }
	// return ($rupees ? $rupees . 'rupees' : '') . $paise ;


	return ($rupees ? $rupees . '' : '');
}
function mysqlDateValidation($date)
{
	$is_validate 	= 0;
	$strLength 		= strlen($date);
	if ($strLength == '10') {
		if (strpos($date, '-', 6) !== false) {
			if (strpos($date, '-', 3) !== false) {
				$is_validate = 1;
			}
		}
	}
	return $is_validate;
}
function timeValidation($time)
{
	$is_validate 	= 0;
	$strLength 		= strlen($time);
	if ($strLength == '8') {
		$time_last_str = substr($time, -2);
		$time_first_str = substr($time, 2, 1);
		if ($time_last_str == 'AM' ||  $time_last_str == 'am' ||  $time_last_str == 'PM' ||  $time_last_str == 'pm') {
			if ($time_first_str == ':') {
				$is_validate = 1;
			}
		}
	}
	return $is_validate;
}

function sendSMS($db, $conn, $school_admin_id, $selected_db_name, $username, $phone_no, $msg_content, $module_menue_id, $sending_purpose, $parm1 = "", $parm2 = "", $parm3 = "")
{
	$add_ip 					= $_SERVER['REMOTE_ADDR'];
	$add_date 					= date("Y-m-d H:i:s");
	return "";
}
function sendSMS_MainSite($db, $conn, $username, $phone_no, $msg_content, $sending_purpose, $parm1 = "", $parm2 = "", $parm3 = "")
{
	$add_ip 					= $_SERVER['REMOTE_ADDR'];
	$add_date 					= date("Y-m-d H:i:s");
	return "";
}

//////////////// Function check menu child ///////////
function check_module_permission_school($db, $conn, $menu_id, $school_user_id, $subscriber_users_id, $user_type, $db_name)
{
	if ($user_type == 'Admin') {
		$sql 		= " SELECT a.menu_name 
						FROM menus a
						INNER JOIN role_permissions b ON b.menu_id = a.id
						INNER JOIN user_roles c ON c.role_id = b.role_id
						WHERE a.enabled 		= 1
						AND b.enabled 			= 1
						AND a.id 				= '" . $menu_id . "'
						AND c.subscriber_users_id 	= '" . $subscriber_users_id . "' 
						ORDER BY b.id DESC LIMIT 1";
		$result 	= $db->query($conn, $sql);
		$count 		= $db->counter($result);
		if ($count > 0) {
			$row = $db->fetch($result);
			return $row[0]['menu_name'];
		} else {
			return "";
		}
	} else {
		$sql 		= " SELECT a.menu_name
						FROM menus a
						INNER JOIN sub_users_role_permissions b ON b.menu_id = a.id
						INNER JOIN sub_users_user_roles c ON c.role_id = b.role_id
						INNER JOIN sub_users_roles d ON c.`role_id`
						WHERE a.enabled = 1
						AND b.enabled = 1
						AND c.enabled = 1
						AND a.id = '" . $menu_id . "'
						AND d.subscriber_users_id = '" . $subscriber_users_id . "'
						AND c.user_id = '" . $school_user_id . "'
						ORDER BY b.id DESC
						LIMIT 1  ";
		$result 	= $db->query($conn, $sql);
		// echo $sql;die;
		$count 		= $db->counter($result);
		if ($count > 0) {
			$row = $db->fetch($result);
			return $row[0]['menu_name'];
		} else {
			return "";
		}
	}
}
function convert_from_usa_to_mysql($date)
{
	if ($date != NULL && $date != "" && $date != "0000-00-00") {
		$date = date_create($date);
		$date = date_format($date, "Y-m-d");
	} else {
		$date = "";
	}
	return $date;
}

function redirect_to_page($page_path)
{
	return '<script> location.replace("' . $page_path . '"); </script>';
}
function calculateTotalDays($fromDate, $toDate)
{
	$from = new DateTime($fromDate);
	$to = new DateTime($toDate);
	$interval = $from->diff($to);
	return $interval->days;
}

function date_for_db($date)
{
	$return_date = (($date == '') ? "NULL" :  "$date");
	$return_date = (($return_date == '0000-00-00') ? "NULL" :  "'$return_date'");
	return $return_date;
}

function get_col_from_table($db, $conn, $db_name, $table, $id, $columns)
{
	$output = array();
	$sql	= " SELECT a.* 
				FROM " . $table . " a 
				WHERE a.id = '" . $id . "'  ";
	$result	= $db->query($conn, $sql);
	$row	= $db->fetch($result);
	foreach ($columns as $data) {
		$output[$data] = $row[0][$data];
	}
	return $output;
}

function athenticate_phonecheck()
{
	// API URL
	$url = "https://api.phonecheck.com/v2/auth/master/login";

	// Your master username and password
	$username = "ctinnovationsnyc"; // Replace with actual username
	$password = "ucxt3wzthx"; // Replace with actual password

	// Prepare the request body
	$data = json_encode([
		"username" => $username,
		"password" => $password
	]);

	// Initialize cURL session
	$ch = curl_init($url);

	// Set cURL options
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json'
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	// Execute the cURL request and get the response
	$response = curl_exec($ch);

	// Check for cURL errors
	if (curl_errno($ch)) {
		echo 'Request Error: ' . curl_error($ch);
		curl_close($ch);
		exit();
	}

	// Close the cURL session
	curl_close($ch);

	// Decode the response
	$responseData = json_decode($response, true);

	// Check if the response contains a token
	if (isset($responseData['token'])) {
		// Store the token in session
		$_SESSION['token'] = $responseData['token'];
	} else {
		// Handle errors or invalid credentials
		if (isset($responseData['err'])) {
			echo "Error: " . $responseData['err'];
		} elseif (isset($responseData['msg'])) {
			echo "Error: " . $responseData['msg'];
		} else {
			echo "Unexpected error.";
		}
	}
}

function getinfo_phonecheck_imie($imei)
{
	// Set the IMEI or other identifier

	// API URL with the IMEI
	$url = "https://api.phonecheck.com/v2/master/imei/device-info-legacy/$imei";

	$ch = curl_init($url);
	// Set cURL options for GET request
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'token_master: ' . $_SESSION['token'], // Send token in token_master key
		'Content-Type: application/json'
	]);
	// Execute the cURL request and get the response
	$response = curl_exec($ch);
	// Check for cURL errors
	if (curl_errno($ch)) {
		echo 'Request Error: ' . curl_error($ch);
		curl_close($ch);
		exit();
	}
	// Close the cURL session
	curl_close($ch);
	// Decode the response
	$responseData = json_decode($response, true);
	// Check if the response contains device information
	if (isset($responseData)) {
		// Display device information
		return $responseData;
	} else {
		echo "No device information found.";
	}
}

function v2_devices_call_phonecheck($data)
{
	// https://phonecheck.atlassian.net/wiki/spaces/KB/pages/2271772692/Phonecheck+API+to+Get+All+Devices+-+V2
	// Set the API endpoint URL (US version, switch to EU URL if needed)
	$url = "https://clientapiv2.phonecheck.com/cloud/CloudDB/v2/GetAllDevices";
	// API key and other parameters (replace with your actual values)
	// Initialize a cURL session
	$ch = curl_init();
	// Set the cURL options for a POST request
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		"Content-Type: application/json"
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

	// Execute the cURL request and get the response
	$response = curl_exec($ch);

	// Check for errors in the cURL execution
	if ($response === false) {
		echo "cURL Error: " . curl_error($ch);
	} else {
		// Decode the JSON response
		$data = json_decode($response, true);

		$all_imeis 		= array();
		$all_serials 	= array();
		$all_udids		= array();

		$c = 1;
		// Check if data is received successfully
		if (isset($data) && !empty($data)) {
			if (is_array($data)) {
				foreach ($data as $key1 => $data1) {
					if (is_array($data1)) {
						foreach ($data1 as $key2 => $data2) {
							if ($key2 == 'IMEI') {
								$all_imeis[] = $data2;
								$c++;
							} else if ($key2 == 'Serial') {
								$all_serials[] = $data2;
							} else if ($key2 == 'UDID') {
								$all_udids[] = $data2;
							}
						}
					}
				}
			}
		} else {
			echo "No data received or error in response.\n";
		}
	}
	// Close the cURL session
	curl_close($ch);

	$responseArray = [
		'imei' => $all_imeis,
		'serial' => $all_serials,
		'udid' => $all_udids
	];

	return $responseArray;
}

function autoCorrectJson($jsonString)
{
	// Step 1: Clean up the JSON string
	$jsonString = str_replace('" (', ' (', $jsonString);
	$jsonString = stripslashes($jsonString);
	$jsonString = preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', $jsonString); // Remove control characters
	$jsonString = trim($jsonString); // Trim whitespace
	// Step 2: Ensure iCloudInfo is properly formatted
	$jsonString = preg_replace('/"iCloudInfo":\s*"[^"]*"/', '"iCloudInfo":""', $jsonString);
	// Step 3: Handle nested JSON strings correctly
	$jsonString = preg_replace_callback('/"Parts":"(.*?)"/s', function ($matches) {
		return '"Parts":' . json_encode(json_decode($matches[1], true));
	}, $jsonString);
	// Step 4: Return the corrected JSON string
	return $jsonString;
}

function isJson($string)
{
	json_decode($string);
	return (json_last_error() === JSON_ERROR_NONE);
}

// Function to sanitize individual string values
function sanitizeString($string)
{
	// Remove or replace problematic characters
	// For example, remove double quotes that are not escaped
	// You can customize this based on your specific needs
	$string = str_replace('"', '\"', $string);
	return $string;
}

// Recursive function to sanitize the entire array
function sanitizeArray(&$array)
{
	foreach ($array as $key => &$value) {
		if (is_string($value)) {
			// Handle incomplete or invalid fields
			if (trim($value) === '') {
				$value = ""; // Set to empty string
			} elseif (strpos($value, '"') !== false) {
				// Sanitize strings with quotes
				$value = sanitizeString($value);
			}

			// Check if the string is a JSON string that needs to be decoded
			if (isJson($value)) {
				$decoded = json_decode($value, true);
				if (is_array($decoded)) {
					$value = $decoded; // Replace string with decoded array
				}
			}
		} elseif (is_array($value)) {
			// Recursively sanitize nested arrays
			sanitizeArray($value);
		} elseif (is_null($value)) {
			// Optionally, set nulls to empty strings or handle as needed
			// $value = "";
		}
		// Add more conditions as necessary based on your data
	}
}

function get_sku($db, $conn, $key2, $field_value)
{
	$sql	= " SELECT id, sku_code 
				FROM sku_codes  
				WHERE sku_type 	= '" . $key2 . "' 
				AND (field_value = '" . $field_value . "' || REPLACE(field_value, ' ', '') = '" . $field_value . "') ";
	$result	= $db->query($conn, $sql); //echo $sql;die;
	$count	= $db->counter($result);
	if ($count > 0) {
		$row = $db->fetch($result);
		return $row[0]['sku_code'];
	} else {
		return "";
	}
}
function status_color_class($status_name)
{
	$color_class = "blue-text";
	if ($status_name == 'To Do') {
		$color_class = "blue-text";
	}
	if ($status_name == 'In Progress') {
		$color_class = "purple-text";
	}
	if ($status_name == 'On Hold') {
		$color_class = "yellow-text";
	}
	if ($status_name == 'Cancelled ') {
		$color_class = "red-text";
	}
	if ($status_name == 'Done') {
		$color_class = "light-green-text";
	}
	return $color_class;
}
function priority_color_class($priority_name)
{
	$color_class = "blue-text";
	if ($priority_name == 'Low') {
		$color_class = "blue-text";
	}
	if ($priority_name == 'Medium') {
		$color_class = "green-text";
	}
	if ($priority_name == 'High') {
		$color_class = "red-text";
	}
	return $color_class;
}
function po_receiving_labor($db, $conn, $po_id)
{
	$per_product_labor = 0;
	$sql	= " SELECT 
					user_id, SUM(TIMESTAMPDIFF(SECOND, startTime, stopTime)) / 3600 AS total_hours
				FROM time_clock_detail
				WHERE po_id = '" . $po_id . "' 
				AND entry_type = 'receive' 
				AND enabled = 1
				GROUP BY user_id ";
	$result	= $db->query($conn, $sql); //echo $sql;die;
	$count	= $db->counter($result);
	if ($count > 0) {
		$row = $db->fetch($result);
		foreach ($row as $data) {
			$total_hours 	= $data['total_hours'];
			$user_id 		= $data['user_id'];
			$sql			= " SELECT 
									count(d.id) as total_received, e.hourly_rate
								FROM  purchase_orders b  
								INNER JOIN purchase_order_detail c ON c.po_id = b.id
								INNER JOIN purchase_order_detail_receive d ON d.po_detail_id = c.id
								INNER JOIN employee_profile e ON e.school_user_id = '" . $user_id . "'
								WHERE b.id = '" . $po_id . "' 
								AND (d.add_by_user_id = '" . $user_id . "') ";
			$result2		= $db->query($conn, $sql); //echo $sql;die;
			$total_received	= $db->counter($result2);
			if ($total_received > 0) {
				$row2 = $db->fetch($result2);
				$total_received 	= $row2[0]['total_received'];
				$hourly_rate 		= $row2[0]['hourly_rate'];
				$total_labor 		= round($total_hours * $hourly_rate, 2);
				if ($total_labor > 0 && $total_received > 0) {
					$per_product_labor += ($total_labor / $total_received);
				}
			}
		}
	}
	return $per_product_labor;
}

function po_diagnostic_labor($db, $conn, $po_id)
{
	$per_product_labor = 0;
	$sql	= " SELECT 
					user_id, SUM(TIMESTAMPDIFF(SECOND, startTime, stopTime)) / 3600 AS total_hours
				FROM time_clock_detail
				WHERE po_id = '" . $po_id . "' 
				AND entry_type = 'diagnostic' 
				AND enabled = 1
				GROUP BY user_id ";
	$result	= $db->query($conn, $sql); //echo $sql;die;
	$count	= $db->counter($result);
	if ($count > 0) {
		$row = $db->fetch($result);
		foreach ($row as $data) {
			$total_hours 	= $data['total_hours'];
			$user_id 		= $data['user_id'];
			$sql			= " SELECT 
									count(d.id) as total_received, e.hourly_rate
								FROM  purchase_orders b  
								INNER JOIN purchase_order_detail c ON c.po_id = b.id
								INNER JOIN purchase_order_detail_receive d ON d.po_detail_id = c.id
								INNER JOIN employee_profile e ON e.school_user_id = '" . $user_id . "'
								WHERE b.id = '" . $po_id . "' 
								AND d.is_diagnost = 1
								AND (d.diagnose_by_user_id = '" . $user_id . "') ";
			$result2			= $db->query($conn, $sql); //echo $sql;die;
			$total_received	= $db->counter($result2);
			if ($total_received > 0) {
				$row2 = $db->fetch($result2);
				$total_received 	= $row2[0]['total_received'];
				$hourly_rate 		= $row2[0]['hourly_rate'];
				// $hourly_rate 		= 3;   // For testing in actual this would be user hourly rate
				$total_labor 		= round($total_hours * $hourly_rate, 2);
				if ($total_labor > 0 && $total_received > 0) {
					$per_product_labor += ($total_labor / $total_received);
				}
			}
		}
	}
	return $per_product_labor;
}
function po_logistic_cost($db, $conn, $po_id, $logistics_cost)
{
	$po_logistic_cost = 0;
	$sql			= " SELECT 
							d.id
						FROM  purchase_orders b  
						INNER JOIN purchase_order_detail c ON c.po_id = b.id
						INNER JOIN purchase_order_detail_receive d ON d.po_detail_id = c.id
						WHERE b.id = '" . $po_id . "' ";
	$result			= $db->query($conn, $sql); //echo $sql;die;
	$total_received	= $db->counter($result);
	if ($logistics_cost > 0 && $total_received > 0) {
		$po_logistic_cost = $logistics_cost / $total_received;
	}
	return $po_logistic_cost;
}

function device_repaire_labor_cost($db, $conn, $receive_rma_id)
{
	$per_product_labor = 0;
	$sql	= " SELECT 
					user_id, SUM(TIMESTAMPDIFF(SECOND, startTime, stopTime)) / 3600 AS total_hours
				FROM time_clock_detail
				WHERE repaire_id = '" . $receive_rma_id . "' 
				AND entry_type = 'repaire' 
				AND enabled = 1
				GROUP BY user_id ";
	$result	= $db->query($conn, $sql); //echo $sql;die;
	$count	= $db->counter($result);
	if ($count > 0) {
		$row = $db->fetch($result);
		foreach ($row as $data) {
			$total_hours 	= $data['total_hours'];
			$user_id 		= $data['user_id'];

			$sql			= " SELECT e.hourly_rate FROM  employee_profile e WHERE e.school_user_id = '" . $user_id . "'   ";
			$result2		= $db->query($conn, $sql); //echo $sql;die;
			$count2			= $db->counter($result2);
			if ($count2 > 0) {
				$row2 = $db->fetch($result2);
				$hourly_rate 		= $row2[0]['hourly_rate'];
				$per_product_labor	= round($total_hours * $hourly_rate, 2);
			}
		}
	}
	return $per_product_labor;
}

function device_processing_labor($db, $conn, $stock_id)
{
	$per_product_labor = 0;
	$sql	= " SELECT user_id, SUM(TIMESTAMPDIFF(SECOND, startTime, stopTime)) / 3600 AS total_hours
				FROM time_clock_detail
				WHERE stock_id = '" . $stock_id . "' 
				AND entry_type = 'process' 
				AND enabled = 1
				GROUP BY user_id ";
	$result	= $db->query($conn, $sql);
	// echo $sql; die;
	$count	= $db->counter($result);
	if ($count > 0) {
		$row = $db->fetch($result);
		foreach ($row as $data) {

			$total_hours 	= $data['total_hours'];
			$user_id 		= $data['user_id'];

			$sql			= " SELECT e.hourly_rate
								FROM employee_profile e
								WHERE e.school_user_id = '" . $user_id . "' ";
			$result2		= $db->query($conn, $sql);
			// echo $sql;die;
			$count_emp	= $db->counter($result2);
			if ($count_emp > 0) {
				$row2 = $db->fetch($result2);
				$hourly_rate 		= $row2[0]['hourly_rate'];
				$total_labor 		= round($total_hours * $hourly_rate, 2);
				$per_product_labor += $total_labor;
			}
		}
	}
	return $per_product_labor;
}


function update_po_status($db, $conn, $po_id, $order_status)
{
	$add_ip		= $_SERVER['REMOTE_ADDR'];
	$add_date	= date("Y-m-d H:i:s");
	$timezone	= TIME_ZONE;
	$sql_c_up 	= "UPDATE  purchase_orders SET	order_status		= '" . $order_status . "',
												update_timezone		= '" . $timezone . "',
												update_date			= '" . $add_date . "',
												update_by_user_id	= '" . $_SESSION['user_id'] . "',
												update_by			= '" . $_SESSION['username'] . "',
												update_ip			= '" . $add_ip . "'
					WHERE id = '" . $po_id . "' ";
	$db->query($conn, $sql_c_up);
}
function update_po_detail_status($db, $conn, $po_detail_id, $order_product_status)
{
	$add_ip		= $_SERVER['REMOTE_ADDR'];
	$add_date	= date("Y-m-d H:i:s");
	$timezone	= TIME_ZONE;
	$sql_c_up = "UPDATE  purchase_order_detail SET 	order_product_status	= '" . $order_product_status . "',
													update_timezone			= '" . $timezone . "',
													update_date				= '" . $add_date . "',
													update_by				= '" . $_SESSION['username'] . "',
													update_by_user_id		= '" . $_SESSION['user_id'] . "',
													update_ip				= '" . $add_ip . "'
				WHERE id = '" . $po_detail_id . "' ";
	$db->query($conn, $sql_c_up);
}
function update_po_detail_status2($db, $conn, $po_id, $order_product_status)
{
	$add_ip		= $_SERVER['REMOTE_ADDR'];
	$add_date	= date("Y-m-d H:i:s");
	$timezone	= TIME_ZONE;
	$sql_c_up = "UPDATE  purchase_order_detail SET 	order_product_status	= '" . $order_product_status . "',
													update_timezone			= '" . $timezone . "',
													update_date				= '" . $add_date . "',
													update_by				= '" . $_SESSION['username'] . "',
													update_by_user_id		= '" . $_SESSION['user_id'] . "',
													update_ip				= '" . $add_ip . "'
				WHERE po_id = '" . $po_id . "' ";
	$db->query($conn, $sql_c_up);
}

function update_po_status_package_materials($db, $conn, $po_id, $order_status)
{
	$add_ip		= $_SERVER['REMOTE_ADDR'];
	$add_date	= date("Y-m-d H:i:s");
	$timezone	= TIME_ZONE;
	$sql_c_up 	= "UPDATE  package_materials_orders SET	order_status		= '" . $order_status . "',
														update_timezone		= '" . $timezone . "',
														update_date			= '" . $add_date . "',
														update_by_user_id	= '" . $_SESSION['user_id'] . "',
														update_by			= '" . $_SESSION['username'] . "',
														update_ip			= '" . $add_ip . "'
					WHERE id = '" . $po_id . "' ";
	$db->query($conn, $sql_c_up);
}
function update_po_detail_status_package_materials($db, $conn, $po_detail_id, $order_product_status)
{
	$add_ip		= $_SERVER['REMOTE_ADDR'];
	$add_date	= date("Y-m-d H:i:s");
	$timezone	= TIME_ZONE;
	$sql_c_up = "UPDATE  package_materials_order_detail SET 	order_product_status	= '" . $order_product_status . "',
																update_timezone			= '" . $timezone . "',
																update_date				= '" . $add_date . "',
																update_by				= '" . $_SESSION['username'] . "',
																update_by_user_id		= '" . $_SESSION['user_id'] . "',
																update_ip				= '" . $add_ip . "'
				WHERE id = '" . $po_detail_id . "' ";
	$db->query($conn, $sql_c_up);
}
function update_po_detail_logistics_package_materials($db, $conn, $logistic_id, $order_product_status)
{
	$add_ip		= $_SERVER['REMOTE_ADDR'];
	$add_date	= date("Y-m-d H:i:s");
	$timezone	= TIME_ZONE;
	$sql_c_up = "UPDATE  package_materials_order_detail_logistics SET 	logistics_status		= '" . $order_product_status . "',
																		update_timezone			= '" . $timezone . "',
																		update_date				= '" . $add_date . "',
																		update_by				= '" . $_SESSION['username'] . "',
																		update_by_user_id		= '" . $_SESSION['user_id'] . "',
																		update_ip				= '" . $add_ip . "'
				WHERE id = '" . $logistic_id . "' ";
	$db->query($conn, $sql_c_up);
}


function calcualte_final_grade($battery, $lcd_grade, $digitizer_grade, $body_grade)
{
	$overall_grade = "";
	if ($battery > '60' && ($lcd_grade == 'D' || $digitizer_grade == 'D' || $body_grade == 'D')) {
		$overall_grade = "D";
	} else if ($battery != "" && $battery >= '70') {
		if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'A') {
			$overall_grade = "A";
		}
		if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'B') {
			$overall_grade = "B";
		}
		if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'C') {
			$overall_grade = "C";
		}

		if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'A') {
			$overall_grade = "B";
		}
		if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'B') {
			$overall_grade = "B";
		}
		if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'C') {
			$overall_grade = "C";
		}

		if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'A') {
			$overall_grade = "C";
		}
		if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'B') {
			$overall_grade = "C";
		}
		if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'C') {
			$overall_grade = "C";
		}

		if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'A') {
			$overall_grade = "B";
		}
		if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'B') {
			$overall_grade = "B";
		}
		if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'C') {
			$overall_grade = "C";
		}

		if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'A') {
			$overall_grade = "C";
		}
		if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'B') {
			$overall_grade = "C";
		}
		if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'C') {
			$overall_grade = "C";
		}
	} else if ($battery < '70' && $battery >= '60') {
		if ($lcd_grade == 'A' && $digitizer_grade == 'A' && $body_grade == 'A') {
			$overall_grade = "B";
		}
		if ($lcd_grade == 'B' && $digitizer_grade == 'A' && $body_grade == 'B') {
			$overall_grade = "B";
		}
		if ($lcd_grade == 'C' && $digitizer_grade == 'A' && $body_grade == 'C') {
			$overall_grade = "C";
		}

		if ($lcd_grade == 'A' && $digitizer_grade == 'B' && $body_grade == 'A') {
			$overall_grade = "B";
		}
		if ($lcd_grade == 'B' && $digitizer_grade == 'B' && $body_grade == 'B') {
			$overall_grade = "B";
		}
		if ($lcd_grade == 'C' && $digitizer_grade == 'B' && $body_grade == 'C') {
			$overall_grade = "C";
		}

		if ($lcd_grade == 'A' && $digitizer_grade == 'C' && $body_grade == 'A') {
			$overall_grade = "C";
		}
		if ($lcd_grade == 'B' && $digitizer_grade == 'C' && $body_grade == 'B') {
			$overall_grade = "C";
		}
		if ($lcd_grade == 'C' && $digitizer_grade == 'C' && $body_grade == 'C') {
			$overall_grade = "C";
		}
	}
	return $overall_grade;
}
