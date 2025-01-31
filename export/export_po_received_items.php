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

		$sql_cl = "	SELECT * FROM (
						SELECT a.id as record_id, a.base_product_id, serial_no_barcode AS `serial`, a.battery ,
							a.body_grade, a.lcd_grade, a.digitizer_grade,
							a.overall_grade, a.ram, a.storage, a.processor, a.warranty, a.price,
							a.defects_or_notes, h.status_name AS inventory_status, g.sub_location_name
						
						FROM purchase_order_detail_receive a
						INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
						INNER JOIN purchase_orders b1 ON b1.id = b.po_id
						INNER JOIN products c ON c.id = b.product_id
						LEFT JOIN product_categories d ON d.id =c.product_category
						LEFT JOIN users e ON e.id = a.add_by_user_id
						LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
						LEFT JOIN inventory_status h ON h.id = a.inventory_status
						LEFT JOIN warehouse_sub_locations i ON i.id = a.sub_location_id_after_diagnostic
						WHERE a.enabled = 1
						AND b.po_id = '" . $id . "'
						AND (a.recevied_product_category = 0 || a.recevied_product_category IS NULL || a.serial_no_barcode IS NOT NULL)
					) AS t1
					ORDER BY record_id  ";
		// echo $sql_cl;
		$result_cl 	= $db->query($conn, $sql_cl);
		$count_cl 	= $db->counter($result_cl);
		if ($count_cl > 0) {
			while ($rows = mysqli_fetch_assoc($result_cl)) {
				$records[] = $rows;
			}
			// Submission from
			$filename = "export_po_received_items_" . date('YmdHis') . ".csv";
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
		} else {
			echo "No Serial# Found";
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
			if ($column_name == 'serial') {
				if (is_numeric($value)) {
					$value = "'" . $value;
				}
			}
			return $value;
		}
	}
}
