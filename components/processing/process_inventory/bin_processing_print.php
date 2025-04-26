<?php
include('path.php');
include($directory_path . "conf/session_start.php");
include($directory_path . "conf/connection.php");
include($directory_path . "conf/functions.php");
$db 	= new mySqlDB;
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == $project_folder &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {
} else {
	echo redirect_to_page($directory_path . "signin");
	exit();
}

$db 				= new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$user_id 			= $_SESSION["user_id"];

if (isset($_GET['string'])) {
	$string 			= $_GET['string'];
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
			$module_id = $string_data_explode[1];
		}
		if ($string_data_explode[0] == 'detail_id') {
			$detail_id = $string_data_explode[1];
		}
		if ($string_data_explode[0] == 'id') {
			$id = $string_data_explode[1];
		}
	}
} else {
	echo redirect_to_page($directory_path . "signout");
}

$parm1 = $parm2 = $parm3 = "";
check_session_exist4($db, $conn, $_SESSION["user_id"], $_SESSION["username"], $_SESSION["user_type"], $_SESSION["db_name"], $parm2, $parm3);

extract($_POST);
$check_module_permission = check_module_permission($db, $conn, $module_id, $_SESSION["user_id"], $_SESSION["user_type"]);
if ($check_module_permission == "") {
	echo redirect_to_page($directory_path . "signout");
}
$css = "
		<style>
			body {
				font-family: Arial, sans-serif;
				margin: 0;
				padding: 20px;
				line-height: 1.6;
			}
			.header {
				text-align: right;
				margin-bottom: 20px;
				color:#0066CC;
			}
			.header h1 {
				font-size: 24px;
				margin: 0;
			}
			.header p {
				font-size: 14px;
				margin: 5px 0;
			}
		
			.table {
				width: 100%;
				border-collapse: collapse;
				margin-top: 20px;
			}
			.table th, .table td {
				border: 1px solid #000;
				padding: 4px;
				text-align: left;
				font-size: 10px;
			}
			.table th {
				background-color:#0066CC;
				color:whitesmoke;
			}
			.table1 th {
				background-color:rgb(236, 237, 244);
			}
			.table1 {
				width: 100%;
				border-collapse: collapse;
				margin-top: 20px;
			}
			.table1 th, .table1 td {
				border: 1px solid #000;
				padding: 4px;
				text-align: left;
				font-size: 10px;
			}
			.total {
				text-align: right;
				margin-top: 10px;
				font-size: 14px;
				font-weight: bold;
			}
			hr{
				color:#E0E0E0;
			} 
			.text_align_right{
				text-align: right !important;
			}
			.text_align_left{
				text-align: left !important;
			}
		</style>";
require_once $directory_path . 'mpdf/vendor/autoload.php';
// $mpdf = new \Mpdf\Mpdf();
$mpdf = new \Mpdf\Mpdf([
	'format' => 'A4',
	'margin_top' => 5,
	'margin_bottom' => 5,
	'margin_left' => 5,
	'margin_right' => 5,
]);

$sql_ee1 = "SELECT a.*, b.phone_no
			FROM subscribers_users a
			INNER JOIN users b ON b.subscriber_users_id = a.id AND b.user_type = 'Admin'
			LIMIT 1";
$result_ee1 	= $db->query($conn, $sql_ee1);
$counter_ee1	= $db->counter($result_ee1);
if ($counter_ee1 > 0) {
	$row_ee1				= $db->fetch($result_ee1);
	$company_name			= $row_ee1[0]['company_name'];
	$company_logo			= $row_ee1[0]['company_logo'];
	$s_address				= $row_ee1[0]['s_address'];
	$compnay_phone_no		= $row_ee1[0]['phone_no'];
}
$sql_ee1 = "
			SELECT  a.id, a.location_id, b.sub_location_name, b.sub_location_type, 
					GROUP_CONCAT(DISTINCT CONCAT( '', COALESCE(d.first_name, ''), ' ', COALESCE(d.middle_name, ''), ' ', COALESCE(d.last_name, ''), ' (', COALESCE(d.username, ''), ')') ) AS task_user_details, 
					a.add_date
			FROM users_bin_for_processing a
			INNER JOIN warehouse_sub_locations b ON b.id = a.location_id
			INNER JOIN users d ON d.id = a.bin_user_id 
			WHERE 1 = 1
			AND a.id = '" . $id . "'
			GROUP BY a.id
			ORDER BY a.id DESC";
$result_ee11 	= $db->query($conn, $sql_ee1);
$counter_ee11	= $db->counter($result_ee11);
if ($counter_ee11 > 0) {
	$row_ee11				= $db->fetch($result_ee11);
	$location_id 			= $row_ee11[0]['location_id'];
	$sub_location_name		= $row_ee11[0]['sub_location_name'];
	$sub_location_type		= $row_ee11[0]['sub_location_type'];
	if ($sub_location_type != "") {
		$sub_location_name .= "(" . ucwords(strtolower($sub_location_type)) . ")";
	}
	$task_user_details		= $row_ee11[0]['task_user_details'];
	$bin_porcess_date 		= $row_ee11[0]['add_date'];
	$location_type = "";
	if ($sub_location_type != "") {
		$location_type = "( " . $sub_location_type . " )";
	}
	$report_data = '<div class="">
						<div class="header">
							<h1> Package Materials for User Bin Processing</h1><hr>
						</div>
						<table border="0"> 
							<tbody>
								<tr>
									<td></td>
									<td></td>
									<td width="2%"></td>
 									<td width="40%">
										<table border="0"> 
											<tbody>
												<tr>
													<td><strong>User Detail : </strong></td>
													<td><p>' . $task_user_details . '</p></td>
												</tr>
												<tr>
													<td><strong>Location : </strong></td>
													<td><p>' . $sub_location_name . '</p></td>
												</tr>  
												<tr>
													<td><strong>Assign Date: </strong></td>
													<td><p>' . dateformat2($bin_porcess_date) . '</p></td>
												</tr> 
												
											</tbody>
										</table>
									</td> 
								</tr> 
							</tbody>
						</table>
						
						<table class="table1">
							<thead>
								<tr>
									<th>Package Material</th>
									<th>Category</th>
									<th align="center">Quantity</th>
									<th align="center">Mandatory</th>
								</tr>
							</thead>
							<tbody>';

	$sql_sub 		= " SELECT COUNT(a.id) AS total_packages, e.is_mandatory, b.product_id, b.price, c.product_uniqueid, c.product_desc, d.category_name, 
							f.package_name, f.package_desc, f.package_no, g.category_name AS package_materials_category
						FROM users_bin_for_processing a
						INNER JOIN product_stock b ON b.sub_location = a.location_id
						INNER JOIN products c ON c.id = b.product_id
						LEFT JOIN product_categories d ON d.id = c.product_category
						INNER JOIN product_packages e ON e.product_id = b.product_id
						INNER JOIN packages f ON f.id = e.package_id
						LEFT JOIN product_categories g ON g.id = f.product_category
						WHERE 1 = 1
						AND e.enabled 	= 1
						AND a.id 		= '" . $id . "'
						GROUP BY f.package_name, e.is_mandatory
						ORDER BY e.is_mandatory DESC "; //echo $sql_sub;die;
	$result_sub 	= $db->query($conn, $sql_sub);
	$counter_sub	= $db->counter($result_sub);
	$sub_total = $total = $sum_order_qty = $sum_value = 0;
	if ($counter_sub > 0) {
		$row_sub = $db->fetch($result_sub);
		foreach ($row_sub as $data_sub) {
			$package_name 				= $data_sub['package_name'];
			$package_materials_category	= $data_sub['package_materials_category'];
			$total_packages				= $data_sub['total_packages'];
			$is_mandatory 				= $data_sub['is_mandatory'];
			$report_data .= '
			<tr>
				<td>' . $package_name . '</td>
				<td>' . $package_materials_category . '</td>
				<td align="center">' . $total_packages . '</td>
				<td align="center">' . $is_mandatory . '</td>
			</tr> ';
		}
	}
	$report_data .= '	</tbody>
						</table> ';
	$report_data .= '
					</div>';
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('Package Materials for User Bin Processing');
	$file_name = "Package Materials for User Bin Processing " . date('YmdHis') . ".pdf";
	$mpdf->output($file_name, 'I');
} else {
	$report_data = '
	<div class="text_align_center main_font"> No detail found </div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);
	$mpdf->SetTitle('Package Materials for User Bin Processing');
	$file_name = "Package Materials for User Bin Processing " . date('YmdHis') . ".pdf";
	$mpdf->output($file_name, 'I');
}
