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
$sql_ee1 = "SELECT   a.*, c.po_no,c.po_date, c.vender_invoice_no, d.vender_name, d.address, d.phone_no
			FROM  package_materials_order_detail a
			INNER JOIN package_materials_orders c ON c.id = a.po_id
			INNER JOIN venders d ON d.id = c.vender_id 
			WHERE a.po_id = '" . $id . "'
			GROUP BY a.po_id "; 
$result_ee11 	= $db->query($conn, $sql_ee1);
$counter_ee11	= $db->counter($result_ee11);
if ($counter_ee11 > 0) { 
	$row_ee11			= $db->fetch($result_ee11);
	$po_no				= $row_ee11[0]['po_no'];
	$po_date			= $row_ee11[0]['po_date'];
	$vender_name		= $row_ee11[0]['vender_name'];
	$phone_no			= $row_ee11[0]['phone_no'];
	$address			= $row_ee11[0]['address'];
	$po_id				= $row_ee11[0]['po_id'];
	$vender_invoice_no	= $row_ee11[0]['vender_invoice_no'];

	$report_data = '<div class="">
						<div class="header">
							<h1>PURCHASE ORDER (Package/Parts) </h1><hr>
						</div>
						<table border="0"> 
							<tbody>
								<tr>
									<td>
										<p align="center"><img src="../../../app-assets/images/logo/' . $company_logo . '" style="width:50px;height:50px;"></p>
										<p>' . $s_address . '</p>
										<p>Phone: ' . $compnay_phone_no . '</p>
									</td>
									<td width="150"></td>
									<td>
										<table border="0"> 
											<tbody>
												<tr>
													<td><strong>PO#: </strong></td>
													<td><p>' . $po_no . '</p></td>
												</tr> 
												<tr>
													<td><strong>PO Date: </strong></td>
													<td><p>' . dateformat2($po_date) . '</p></td>
												</tr> 
												<tr>
													<td><strong>Vendor Invoice#: </strong></td>
													<td><p>' . dateformat2($vender_invoice_no) . '</p></td>
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
									<th>Bill To</th>
									<th>Ship From</th>
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
										<p>Name : ' . $vender_name . '</p>
										<p>Phone : ' . $phone_no . '</p>
										<p>Address : ' . $address . '</p>
									</td>
								</tr> 
							</tbody>
						</table>
						<table class="table1">
							<thead>
								<tr>
									<th>Package/Part</th>
									<th>Price</th>
									<th>Qty</th>
									<th>Value</th>
								</tr>
							</thead>
							<tbody>';

					$sql_sub = "SELECT  b1.order_price, b1.product_po_desc, b1.order_qty,
										c.package_name, b.order_status, d.category_name
								FROM package_materials_orders b 
								INNER JOIN `package_materials_order_detail` b1 ON b1.po_id = b.id 
								INNER JOIN packages c ON c.id = b1.package_id
								LEFT JOIN product_categories d ON d.id = c.product_category
 								WHERE b.id = '". $po_id ."'
								ORDER BY b1.id"; //echo $sql_sub;die;
					$result_sub 	= $db->query($conn, $sql_sub);
					$counter_sub	= $db->counter($result_sub);	
					$sub_total = $total = $sum_order_qty = $sum_value = 0;	
						if($counter_sub > 0){
							$row_sub				= $db->fetch($result_sub);
							foreach($row_sub as $data_sub){
 								$package_name			= $data_sub['package_name'];
								$category_name			= $data_sub['category_name'];
								$order_price			= $data_sub['order_price'];
								$order_qty				= $data_sub['order_qty'];
 								$serial_no				= "";
 								$sum_order_qty			+= $order_qty;
								$value 					= $order_qty*$order_price;
								$sum_value			   += $value;
								$report_data .= '
								<tr>
 									<td>'.$package_name.'( '.$category_name.' ) </td>
									<td>'.number_format($order_price,2).'</td>
									<td>'.$order_qty.' </td>
									<td>'.number_format($value,2).'</td>
								</tr>';
							}
						}
					$report_data .= '
									<tr> 
										<td colspan="2"></td>
										<td>'.$sum_order_qty.'</td>
										<td><b>'.number_format($sum_value,2).'</b></td>
									</tr>';

		$report_data .= '	</tbody>
						</table> 
					</div>';
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('Purchase  Order Invoice Pre - ' . $po_no);
	$file_name = "Purchase _Order_Invoice_Pre_" . $po_no . "_" . date('YmdHis') . ".pdf";
	$mpdf->output($file_name, 'I');
} else {
	$report_data = '
	<div class="text_align_center main_font"> No detail found </div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);
	$mpdf->SetTitle('Purchase  Order Invoice Pre - ' . $po_no);
	$file_name = "Purchase _Order_Invoice_Pre_" . $po_no . "_" . date('YmdHis') . ".pdf";
	$mpdf->output($file_name, 'I');
}
