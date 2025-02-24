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

		$sql_cl = "	SELECT  a.product_uniqueid as product_id, a.product_desc, b.category_name, a2.serial_no, a2.price as order_price, a2.stock_grade as `Condition`, d.sub_location_name as Location 
					FROM products a
					INNER JOIN product_stock a2 ON a2.product_id = a.id
					LEFT JOIN product_categories b ON b.id = a.product_category
					LEFT JOIN inventory_status c ON c.id = a2.p_inventory_status
					LEFT JOIN warehouse_sub_locations d ON d.id = a2.sub_location
					LEFT JOIN purchase_order_detail_receive d2 ON d2.id = a2.receive_id
 					LEFT JOIN purchase_orders d4 ON d4.id = d2.po_id
					LEFT JOIN venders d5 ON d5.id = d4.vender_id
					LEFT JOIN vender_types d6 ON d6.id = d5.vender_type
					WHERE 1=1  
					AND a2.p_total_stock >0
					AND a.enabled = 1 "; //AND a2.is_final_pricing = 1
		if (isset($detail_id) && $detail_id > 0) {
			$sql_cl		.= " AND a.product_uniqueid = '" . $detail_id . "' ";
		}
		if (isset($is_final_inventory) && $is_final_inventory != "") {
			$sql_cl		.= " AND a2.is_final_pricing = '" . $is_final_inventory . "' ";
		}
		if (isset($flt_product_id) && $flt_product_id != "") {
			$sql_cl 	.= " AND a.product_uniqueid LIKE '%" . trim($flt_product_id) . "%' ";
		}
		if (isset($flt_stock_grade) && $flt_stock_grade > 0) {
			$sql_cl		.= " AND a2.stock_grade = '" . $flt_stock_grade . "' ";
		}
		if (isset($flt_stock_status) && $flt_stock_status > 0) {
			$sql_cl		.= " AND a2.p_inventory_status = '" . $flt_stock_status . "' ";
		}
		if (isset($flt_product_desc) && $flt_product_desc != "") {
			$sql_cl 	.= " AND a.product_desc LIKE '%" . trim($flt_product_desc) . "%' ";
		}
		if (isset($flt_product_category) && $flt_product_category != "") {
			$sql_cl 	.= " AND a.product_category = '" . trim($flt_product_category) . "' ";
		}
		if (isset($flt_serial_no) && $flt_serial_no > 0) {
			$sql_cl		.= " AND a2.serial_no LIKE '%" . $flt_serial_no . "%' ";
		}
		if (isset($flt_bin_id) && $flt_bin_id > 0) {
			$sql_cl		.= " AND a2.sub_location = '" . $flt_bin_id . "' ";
		}
		$sql_cl		.= " ORDER BY a.product_uniqueid, a2.stock_grade, a2.serial_no, d.sub_location_name";
		// echo $sql_cl;die;
		$result_cl 	= $db->query($conn, $sql_cl);
		$count_cl 	= $db->counter($result_cl);
		if ($count_cl > 0) {
			while ($rows = mysqli_fetch_assoc($result_cl)) {
				$records[] = $rows;
			}
			// Submission from
			$filename = "export_available_stock_" . date('YmdHis') . ".csv";
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
