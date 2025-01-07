<?php
include("../conf/session_start.php");
include("../conf/connection.php");
include("../conf/functions.php");
$db = new mySqlDB; 
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
				$module_id	= $string_data_explode[1];
			}
			if ($string_data_explode[0] == 'id') {
				$id			= $string_data_explode[1];
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

		$sql_cl = "	SELECT 	b.so_no, b.customer_po_no, DATE_FORMAT(b.order_date, '%M %d %Y') as order_date,
					c.product_uniqueid As product_id, c.product_desc, d.category_name, a.serial_no_barcode AS serial_no,
					CONCAT(b2.packing_type, ' ' , a.box_no) AS box_no, 
					IF(a.pallet_no IS NOT NULL AND a.pallet_no != '', CONCAT('Pallet ', a.pallet_no), '') AS pallet_no
			FROM `sales_order_detail_packing` a  
			INNER JOIN sales_orders b ON b.id = a.sale_order_id
			INNER JOIN `sales_order_detail` b1 ON b1.sales_order_id = a.sale_order_id
			INNER JOIN product_stock c1 ON c1.id = b1.product_stock_id AND c1.serial_no = a.serial_no_barcode
			INNER JOIN products c ON c.id = c1.product_id
			INNER JOIN packing_types b2 ON b2.id = a.packing_type
			LEFT JOIN product_categories d ON d.id = c.product_category
			WHERE b1.sales_order_id ='" . $id . "'
			ORDER BY a.serial_no_barcode "; //AND a2.is_final_pricing = 1
		// echo $sql_cl;die;
		$result_cl 	= $db->query($conn, $sql_cl);
		$count_cl 	= $db->counter($result_cl);
		if ($count_cl > 0) {
			while ($rows = mysqli_fetch_assoc($result_cl)) {
				$records[] = $rows;
			}
			// Submission from
			$filename = "sales_order_packed_products_" . date('YmdHis') . ".csv";
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
