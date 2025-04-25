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
		$k 						= 0;

		$sql1           = " SELECT DISTINCT a.id, a.assignment_no 
							FROM users_bin_for_diagnostic a 
							INNER JOIN users b ON a.bin_user_id = b.id 
							INNER JOIN warehouse_sub_locations c ON c.id = a.location_id 
							INNER JOIN purchase_order_detail_receive d ON d.sub_location_id = c.id 
							WHERE a.enabled = 1 
							AND d.po_id = '" . $id . "'
							AND a.is_processing_done = 0 ";
		//echo $sql1;
		$sql1           .= "ORDER BY assignment_no ";
		$result1        = $db->query($conn, $sql1);
		$count1         = $db->counter($result1);
		if ($count1 > 0) {
			$row1 = $db->fetch($result1);
			foreach ($row1 as $data1) {
				$invoiceNo 		= $data1['assignment_no'];  // Optional
				$assignment_id 	= $data1['id'];  // Optional

				if ($_SERVER['HTTP_HOST'] == HTTP_HOST_IP && $test_on_local == 1) {
					if ($assignment_id == '1') {
						$invoiceNo 			= "121824";  // Optional
						$diagnostic_date1	= "2025-01-28";  // Filter by Date (optional)
						$phone_check_username	= "ctinno2";  // Filter by Date (optional)
					} else if ($assignment_id == '2') {
						$invoiceNo 				= "PO16";  // Optional
						$diagnostic_date1		= "2025-03-03";  // Filter by Date (optional)
						$phone_check_username	= "ctinno2";  // Filter by Date (optional)
					} else if ($assignment_id == '3') {
						$invoiceNo 				= "207";  // Optional
						$diagnostic_date1		= "2025-02-26";  // Filter by Date (optional)
						$phone_check_username	= "ctinno5";  // Filter by Date (optional)
					}
				}
				$data = [
					'Apikey' 		=> $phoneCheck_apiKey,
					'Username' 		=> $phone_check_username,
					'Invoiceno' 	=> $invoiceNo,
					'Date' 			=> $diagnostic_date1
				];
				$all_devices_info = v2_devices_call_phonecheck($data);
				if (isset($all_devices_info['serial']) && sizeof($all_devices_info['serial']) > 0) {
					$m = 1;
					foreach ($all_devices_info['serial'] as $data) {
						$phone_check_api_data_id = 0;
						// $data = "DMTPD5R1FK10";
						if ($data != "" && $data != null) {
							$insert_bin_and_po_id_fields 	= "po_id, assignment_id, ";
							$insert_bin_and_po_id_values 	= "'" . $id . "', '" . $assignment_id . "', ";
							$serial_no_barcode_diagnostic 	= $data;

							$sql_pd01_4		= "	SELECT  a.*
												FROM phone_check_api_data a 
												WHERE a.enabled = 1 
												AND a.imei_no = '" . $data . "'
												ORDER BY a.id DESC LIMIT 1";
							// echo "<br>" . $sql_pd01_4;die;
							$result_pd01_4	= $db->query($conn, $sql_pd01_4);
							$count_pd01_4	= $db->counter($result_pd01_4);
							if ($count_pd01_4 == 0) {
								// echo "<br>".$sql_pd01_4;  
								$mdm = $failed = $model_name = $model_no = $make_name = $carrier_name = $color_name = $battery = $body_grade = $lcd_grade = $digitizer_grade = $ram = $memory = $defectsCode = $lcd_grade = $lcd_grade = $lcd_grade = $overall_grade = $sku_code = "";
								$device_detail_array 	= getinfo_phonecheck_imie($data);
								$jsonData2				= json_encode($device_detail_array);

								if ($jsonData2 != '[]' && $jsonData2 != 'null' && $jsonData2 != null && $jsonData2 != '' && $jsonData2 != '{"msg":"token expired"}') {
									include("../components/purchase/purchase_orders/process_phonecheck_response.php");
								} else {
									$sql = "INSERT INTO phone_check_api_data(" . $insert_bin_and_po_id_fields . " imei_no, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
											VALUES	(" . $insert_bin_and_po_id_values . " '" . $data . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . TIME_ZONE . "', '" . $module_id . "')";
									$db->query($conn, $sql);
								}
								$k++;
							} else {
								$row_pd01					= $db->fetch($result_pd01_4);
								$phone_check_api_data_prev 	= $row_pd01[0]['phone_check_api_data'];
								$phone_check_api_data_id 	= $row_pd01[0]['id'];
								if ($phone_check_api_data_prev == '' || $phone_check_api_data_prev == NULL) {
									$mdm = $failed = $model_name = $model_no = $make_name = $carrier_name = $color_name = $battery = $body_grade = $lcd_grade = $digitizer_grade = $ram = $memory = $defectsCode = $lcd_grade = $lcd_grade = $lcd_grade = $overall_grade = $sku_code = "";

									$device_detail_array 	= getinfo_phonecheck_imie($data);
									$jsonData2				= json_encode($device_detail_array);

									if ($jsonData2 != '[]' && $jsonData2 != 'null' && $jsonData2 != null && $jsonData2 != '' && $jsonData2 != '{"msg":"token expired"}') {
										include("../components/purchase/purchase_orders/process_phonecheck_response.php");
									}
									$k++;
								}
							}
						}
					}
				}
			}
		}
		$sql_cl = "	SELECT c.po_no, b.assignment_no, a.imei_no, a.model_name, a.model_no, a.make_name, a.sku_code, 
						a.carrier_name, a.color_name, a.battery, a.body_grade, a.lcd_grade, a.digitizer_grade, a.etching, 
						a.ram, a.MEMORY, a.defectsCode, a.overall_grade, a.mdm, a.failed
					FROM phone_check_api_data a
					LEFT JOIN users_bin_for_diagnostic b ON b.id = a.assignment_id
					LEFT JOIN purchase_orders c ON c.id = a.po_id
					WHERE a.po_id = '" . $id . "' 
					AND a.enabled = 1 
					ORDER BY b.assignment_no, a.imei_no ";
		// echo $sql_cl; die;
		$result_cl 	= $db->query($conn, $sql_cl);
		$count_cl 	= $db->counter($result_cl);
		if ($count_cl > 0) {
			while ($rows = mysqli_fetch_assoc($result_cl)) {
				$records[] = $rows;
			}
			// Submission from
			$filename = "export_phone_check_date_" . date('YmdHis') . ".csv";
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
			echo "No Data Found";
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
	if ($value != "") {
		$value = str_replace(',', '', $value);
	}
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
