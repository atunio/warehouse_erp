<?php
include('path.php');
include($directory_path . "conf/session_start.php");
include($directory_path . "conf/connection.php");
include($directory_path . "conf/functions.php");
$db 	= new mySqlDB;
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == $project_folder &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {
} else {
	echo redirect_to_page("signin");
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
 $sql_ee1 = "SELECT  a.*, c.return_no,c.return_date, c.removal_order_id, d.store_name
			FROM  return_items_detail a
			INNER JOIN returns c ON c.id = a.return_id
			INNER JOIN stores d ON d.id = c.store_id 
			WHERE a.return_id = '" . $id . "'
			GROUP BY a.id ";
			
$result_ee11 	= $db->query($conn, $sql_ee1);
$counter_ee11	= $db->counter($result_ee11);
if ($counter_ee11 > 0) {
	$row_ee11			= $db->fetch($result_ee11);
	$return_no				= $row_ee11[0]['return_no'];
	$po_date			= $row_ee11[0]['return_date'];
	$store_name		= $row_ee11[0]['store_name'];
	$return_id				= $row_ee11[0]['return_id'];
	$removal_order_id	= $row_ee11[0]['removal_order_id'];

	$report_data = '<div class="">
						<div class="header">
							<h1>RETURN ORDER DETAIL</h1><hr>
						</div>
						<table border="0"> 
							<tbody>
								<tr>
									<td>
										<p align="center"><img src="../../../app-assets/images/logo/' . $company_logo . '" style="width:50px;height:50px;"></p>
									</td>
									
									<td width="2%"></td>
 									<td width="40%">
										<table border="0"> 
											<tbody>
												<tr>
													<td><strong>Return#: </strong></td>
													<td><p>' . $return_no . '</p></td>
												</tr> 
												<tr>
													<td><strong>Return Date: </strong></td>
													<td><p>' . dateformat2($po_date) . '</p></td>
												</tr> 
												<tr>
													<td><strong>Order / Removal ID#: </strong></td>
													<td><p>' . ($removal_order_id) . '</p></td>
												</tr> 
											</tbody>
										</table>
									</td> 
								</tr> 
							</tbody>
						</table>
						<table class="table"> 
							<thead>
								<tr>
									<th>To</th>
									<th>Store From</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<p>' . $company_name . '</p>
										<p>' . $s_address . '</p>
									</td>
									<td>
										<h3></h3>
										<p>Name : ' . $store_name . '</p>
									</td>
								</tr> 
							</tbody>
						</table>
						<table class="table1">
							<thead>
								<tr>
									<th>Product ID</th>
									<th>Description</th>
									<th>Status</th>
								
									<th>Qty</th>
								</tr>
							</thead>
							<tbody>';

	$sql_sub 		= "SELECT  b1.product_id, b1.order_price, b1.product_po_desc, b1.return_qty, b1.product_condition,
								c.product_uniqueid, c.product_desc, b.return_status, d.category_name, e.status_name
						FROM returns b 
						INNER JOIN `return_items_detail` b1 ON b1.return_id = b.id 
						INNER JOIN products c ON c.id = b1.product_id
						LEFT JOIN product_categories d ON d.id = c.product_category
						LEFT JOIN inventory_status e ON e.id = b1.expected_status
						WHERE b.id = '" . $return_id . "'
						ORDER BY b1.id"; //echo $sql_sub;die;
	$result_sub 	= $db->query($conn, $sql_sub);
	$counter_sub	= $db->counter($result_sub);
	$sub_total = $total = $sum_return_qty = $sum_value = 0;
	if ($counter_sub > 0) {
		$row_sub				= $db->fetch($result_sub);
		foreach ($row_sub as $data_sub) {
			$product_uniqueid		= $data_sub['product_uniqueid'];
			$product_desc			= remove_special_character($data_sub['product_desc']);
			$category_name			= $data_sub['category_name'];
			$order_price			= $data_sub['order_price'];
			$return_qty				= $data_sub['return_qty'];
			$status_name			= $data_sub['status_name'];
			$product_condition		= $data_sub['product_condition'];
			$serial_no				= "";
			$sum_return_qty			+= $return_qty;
			$value 					= $return_qty * $order_price;
			$sum_value			   += $value;
			$report_data .= '
								<tr>
									<td>' . $product_uniqueid . '</td>
									<td>' . $product_desc . ' (' . $category_name . ') </td>
									<td>' . $status_name . ' </td>
									<td>' . $return_qty . ' </td>
								</tr>';
		}
	}
	$report_data .= '
									<tr> 
										<td colspan="3"></td>
										<td>' . $sum_return_qty . '</td>
									
									</tr>';

	$report_data .= '	</tbody>
					</table> '; 
	$report_data .= '
					</div>';
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('Purchase  Order Invoice Pre - ' . $return_no);
	$file_name = "Purchase _Order_Invoice_Pre_" . $return_no . "_" . date('YmdHis') . ".pdf";
	$mpdf->output($file_name, 'I');
} else {
	$report_data = '
	<div class="text_align_center main_font"> No detail found </div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);
	$mpdf->SetTitle('Purchase  Order Invoice Pre - ' . $return_no);
	$file_name = "Purchase _Order_Invoice_Pre_" . $return_no . "_" . date('YmdHis') . ".pdf";
	$mpdf->output($file_name, 'I');
}
