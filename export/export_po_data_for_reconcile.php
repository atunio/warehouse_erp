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

		$sql_cl = "	SELECT * FROM(
						SELECT  a.base_product_id, a.sub_product_id, c.product_desc,  d.category_name, a.serial_no_barcode AS serial_no, a.model_name, a.model_no, a.make_name, a.carrier_name, 
								a.color_name, a.battery, a.body_grade, a.lcd_grade, a.digitizer_grade, a.overall_grade, a.ram, a.storage, 
								a.processor, a.defects_or_notes,	 
								f.tracking_no, i.sub_location_name AS sub_location_name, b.order_price, h.status_name, k.status AS vender_status, '1' as rec_sort
						FROM purchase_order_detail_receive a
						INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
						INNER JOIN products c ON c.id = b.product_id
						LEFT JOIN product_categories d ON d.id =c.product_category
						LEFT JOIN users e ON e.id = a.add_by_user_id
						INNER JOIN purchase_order_detail_logistics f ON f.id = a.logistic_id
						LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
						LEFT JOIN vender_po_data k ON k.serial_no = a.serial_no_barcode AND k.po_id = b.po_id
						LEFT JOIN inventory_status h ON h.id = a.inventory_status
 						LEFT JOIN warehouse_sub_locations i ON i.id = a.sub_location_id_after_diagnostic
						INNER JOIN product_stock j ON j.receive_id = a.id
						WHERE a.enabled = 1 
						AND b.po_id = '" . $id . "'
						AND (j.p_inventory_status != '" . $tested_or_graded_status . "')

						UNION ALL 

						SELECT  a.base_product_id, a.sub_product_id, c.product_desc,  d.category_name, a.serial_no_barcode AS serial_no, a.model_name, a.model_no, a.make_name, a.carrier_name, 
								a.color_name, a.battery, a.body_grade, a.lcd_grade, a.digitizer_grade, a.overall_grade, a.ram, a.storage, 
								a.processor, a.defects_or_notes,	 
								f.tracking_no, i.sub_location_name AS sub_location_name, b.order_price, h.status_name, k.status AS vender_status, '1' as rec_sort
						FROM purchase_order_detail_receive a
						INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
						INNER JOIN products c ON c.id = b.product_id
						LEFT JOIN product_categories d ON d.id =c.product_category
						LEFT JOIN users e ON e.id = a.add_by_user_id
						INNER JOIN purchase_order_detail_logistics f ON f.id = a.logistic_id
						LEFT JOIN warehouse_sub_locations g ON g.id = a.sub_location_id
						LEFT JOIN vender_po_data k ON k.serial_no = a.serial_no_barcode AND k.po_id = b.po_id
						LEFT JOIN inventory_status h ON h.id = a.inventory_status
 						LEFT JOIN warehouse_sub_locations i ON i.id = a.sub_location_id_after_diagnostic
						INNER JOIN product_stock j ON j.receive_id = a.id
						WHERE a.enabled = 1 
						AND b.po_id = '" . $id . "'
						AND (j.p_inventory_status = '" . $tested_or_graded_status . "')
						AND k.id IS NULL


						UNION ALL 

						SELECT 
                                    
								a.product_uniqueid AS base_product_id, '' AS sub_product_id, 
								'' AS product_desc, a.product_category AS category_name, a.serial_no,
								
								'' AS model_name, '' AS model_no, '' AS make_name, '' AS carrier_name, 
								'' AS color_name, a.battery, '' AS body_grade, '' AS lcd_grade, '' AS digitizer_grade, a.overall_grade, a.memory AS ram, a.storage, 
								a.processor, a.defects_or_notes,	 
								'' AS tracking_no, '' AS sub_location_name, a.price as order_price, '' AS status_name, a.status AS vender_status, '2' as rec_sort

                                FROM vender_po_data a
                                WHERE po_id = 3
                                AND serial_no NOT IN(
                                    SELECT a.serial_no_barcode 
                                    FROM purchase_order_detail_receive a 
                                    INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
                                    WHERE b.po_id = '" . $id . "'
                                    AND is_diagnost = 1 
								)
					) AS t1
					ORDER BY rec_sort, vender_status, status_name ";
		// echo $sql_cl;die;
		$result_cl 	= $db->query($conn, $sql_cl);
		$count_cl 	= $db->counter($result_cl);
		if ($count_cl > 0) {
			while ($rows = mysqli_fetch_assoc($result_cl)) {
				$records[] = $rows;
			}
			// Submission from
			$filename = "export_po_data_for_reconcile_" . date('YmdHis') . ".csv";
			header("Content-type: text/csv");
			header("Content-Disposition: attachment; filename=\"$filename\"");
			if (!empty($records)) {
				$i = 0;
				foreach ($records as $row) {
					$i++;
					$system_Generated_Comment	= "";
					if (!$heading) {
						echo implode(",", array_keys($row));
						echo ",system_Generated_Comment";
						echo "\n";
						$heading = true;
					}
					$serial_no		= $row['serial_no'];
					$status_name	= $row['status_name'];
					$vender_status	= $row['vender_status'];
					$overall_grade	= $row['overall_grade'];
					$rec_sort		= $row['rec_sort'];

					$vender_grade = "";
					$sql2 		= " SELECT * FROM vender_po_data 
									WHERE serial_no = '" . $serial_no . "'
									AND po_id 		= '" . $id . "'";
					$result2 	= $db->query($conn, $sql2);
					$count2 	= $db->counter($result2);
					if ($count2 == 0) {

						if ($system_Generated_Comment != "") {
							$system_Generated_Comment .= " - This Serial# does not exist in Vender Data";
						} else {
							$system_Generated_Comment .= "This Serial# does not exist in Vender Data";
						}
					} else {
						$row_vender = $db->fetch($result2);
						$vender_grade = $row_vender[0]['overall_grade'];
					}

					if ($system_Generated_Comment != "") {
						$system_Generated_Comment .= " - Actual Status: " . $status_name . "";
					} else {
						$system_Generated_Comment .= "Actual Status: " . $status_name . "";
					}

					if ($status_name != $vender_status && $vender_status != "") {
						if ($system_Generated_Comment != "") {
							$system_Generated_Comment .= " - Vender Status: " . $vender_status . " - Difference in Vender Status and Actual Status";
						} else {
							$system_Generated_Comment .= "Vender Status: " . $vender_status . " - Difference in Vender Status and Actual Status";
						}
					}

					if ($system_Generated_Comment != "") {
						$system_Generated_Comment .= " - Actual Grade: " . $overall_grade . "";
					} else {
						$system_Generated_Comment .= "Actual Grade: " . $overall_grade . "";
					}

					if ($overall_grade != $vender_grade && $vender_grade != "") {
						if ($system_Generated_Comment != "") {
							$system_Generated_Comment .= " - Vender Grade: " . $vender_grade . " - Difference in Vender Grade and Actual Grade";
						} else {
							$system_Generated_Comment .= "Vender Grade: " . $vender_grade . " - Difference in Vender Grade and Actual Grade";
						}
					}
					if ($rec_sort == '2') {
						$system_Generated_Comment = "This serial number does not entered yet in ERP";
					}

					$escaped_row = array_map(function ($value, $key) {
						return prevent_excel_date_format($value, $key);
					}, array_values($row), array_keys($row));
					// Output the row after processing
					echo implode(",", $escaped_row);
					echo "," . $system_Generated_Comment;
					echo "\n";
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
