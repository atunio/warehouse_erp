<?php

include("../conf/session_start.php");
include("../conf/connection.php");
include("../conf/functions.php");
$db 	= new mySqlDB;

if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == $project_folder &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {

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
			if ($string_data_explode[0] == 'module_id') {
				$module_id 			= $string_data_explode[1];
			}
			if ($string_data_explode[0] == 'id') {
				$id 			= $string_data_explode[1];
			}
		}
	}

	$parm1 = $parm2 = $parm3 = "";
	check_session_exist4($db, $conn, $_SESSION["user_id"], $_SESSION["username"], $_SESSION["user_type"], $_SESSION["db_name"], $parm2, $parm3);
	$check_module_permission = "";
	$check_module_permission = check_module_permission($db, $conn, $module_id, $_SESSION["user_id"], $_SESSION["user_type"]);
	if ($check_module_permission == "") {
		echo redirect_to_page("signout");
	} else {
		$heading 				= false;
		$db 					= new mySqlDB;
		$selected_db_name 		= $_SESSION["db_name"];
		$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
		$user_id 				= $_SESSION["user_id"];

		$sql_cl = "	SELECT h.po_no, a1.serial_no, a1.finale_product_unique_id,  a1.price_finale AS cost, 1 AS quantity
					FROM product_stock a1
					INNER JOIN products a ON a.id = a1.product_id
					INNER JOIN product_categories b ON b.id = a.product_category
					LEFT JOIN packages c ON c.id = package_id1
					LEFT JOIN packages d ON d.id = package_id2
					LEFT JOIN packages e ON e.id = package_id3
					INNER JOIN purchase_order_detail_receive  f ON f.id = a1.receive_id
 					INNER JOIN purchase_orders  h ON h.id = f.po_id
					WHERE a.enabled 		= 1
					AND a1.is_move_finale 	= 1
					AND a1.sub_location 	= '" . $id . "'
					ORDER BY b.category_name, a.product_uniqueid ";
		// echo $sql_cl;die;
		$result_cl 	= $db->query($conn, $sql_cl);
		$count_cl 	= $db->counter($result_cl);
		if ($count_cl > 0) {
			while ($rows = mysqli_fetch_assoc($result_cl)) {
				$records[] = $rows;
			}
			// Submission from
			$filename = "export_processed_items_" . date('YmdHis') . ".csv";
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
	}
} else {
	echo redirect_to_page("signin");
	exit();
}

function enclose_in_quotes($value)
{
	return '"' . $value . '"';
}
function prevent_excel_date_format($value, $column_name)
{
	$value = str_replace(',', '', $value);
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
