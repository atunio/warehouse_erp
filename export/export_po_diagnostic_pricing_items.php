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
			if ($string_data_explode[0] == 'assignment_id') {
				$assignment_id 			= $string_data_explode[1];
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

		$sql2		= " SELECT distinct a.location_id 
						FROM users_bin_for_diagnostic a 
						INNER JOIN users b ON a.bin_user_id = b.id 
						INNER JOIN warehouse_sub_locations c ON c.id = a.location_id 
						INNER JOIN purchase_order_detail_receive d ON d.sub_location_id = c.id 
						WHERE a.enabled = 1 
						AND a.is_processing_done = 0
						AND d.po_id = '".$id."'
						AND a.bin_user_id = '".$_SESSION['user_id']."'";
		$result2	= $db->query($conn, $sql2);
		$user_no_of_assignments = $db->counter($result2);
		
		if (!isset($assignment_id)) {
			$assignment_id = "";
		}
		 
		if (isset($assignment_id) && $assignment_id > 0 && $assignment_id != "" && isset($id) && $id > 0) {
			$sql_ee			= " SELECT a.assignment_no, a.location_id AS sub_location_id, IFNULL(SUM(b.enabled), 0) AS assignment_qty
								FROM users_bin_for_diagnostic a
								LEFT JOIN purchase_order_detail_receive b ON a.location_id = b.sub_location_id AND b.po_id = '" . $id . "'
								WHERE a.id = '" . $assignment_id . "' "; //echo $sql_ee;
			$result_ee		= $db->query($conn, $sql_ee);
			$row_ee			= $db->fetch($result_ee);
			$assignment_qty			= $row_ee[0]['assignment_qty'];
 			$assignment_location_id	= $row_ee[0]['sub_location_id']; 
		}

		$sql_cl = "	SELECT * FROM(
									SELECT a.id as record_id, a.serial_no_barcode as serial_no, d2.`product_uniqueid` as product_id, d2.`product_desc`, e.`category_name`, IF(a.price = 0, d.order_price, a.price) AS price
									FROM purchase_order_detail_receive a
									INNER JOIN purchase_order_detail d ON d.id = a.po_detail_id
									INNER JOIN products d2 ON d2.id = d.`product_id`
									INNER JOIN product_categories e ON e.id = d2.`product_category`
									WHERE  d.enabled = 1 AND a.enabled = 1
									AND a.po_id = '" . $id . "' 
									AND a.edit_lock = 0

									UNION ALL 

									SELECT a.id as record_id, a.serial_no_barcode as serial_no, d.`product_uniqueid` as product_id, d.`product_desc`,  e.`category_name`, a.price AS price
									FROM purchase_order_detail_receive a
									INNER JOIN products d ON d.id = a.`product_id`
									INNER JOIN product_categories e ON e.id = d.`product_category`
									WHERE  d.enabled = 1 AND a.enabled = 1
									AND a.po_id = '" . $id . "'
									AND a.edit_lock = 0
								) AS t1
								WHERE 1 = 1
								ORDER BY category_name, product_id DESC";
		// echo $sql_cl; die;
		$result_cl 	= $db->query($conn, $sql_cl);
		$count_cl 	= $db->counter($result_cl);
		if ($count_cl > 0) {
			while ($rows = mysqli_fetch_assoc($result_cl)) {
				$records[] = $rows;
			}
			// Submission from
			$filename = "export_po_diagnostic_pricing_items_" . date('YmdHis') . ".csv";
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
