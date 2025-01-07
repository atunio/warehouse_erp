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
				padding: 8px;
				text-align: left;
				font-size: 14px;
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
				padding: 8px;
				text-align: left;
				font-size: 14px;
			}
			.total {
				text-align: right;
				margin-top: 10px;
				font-size: 16px;
				font-weight: bold;
			}
			hr{
				color:#E0E0E0;
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
$sql_ee1 = "SELECT  d.customer_name,d.address_primary,d.phone_primary, a.*
			FROM sale_order_invoices AS a 
			INNER JOIN sales_order_detail b ON b.sales_order_id = a.sales_order_id
			INNER JOIN sales_orders c ON c.id = a.sales_order_id
			INNER JOIN customers d ON d.id = c.customer_id 
			WHERE a.id = '" . $id . "'
			GROUP BY a.id
			";
$result_ee11 	= $db->query($conn, $sql_ee1);
$counter_ee11	= $db->counter($result_ee1);
if ($counter_ee11 > 0) {
	$row_ee11				= $db->fetch($result_ee11);
	$invoice_no			= $row_ee11[0]['invoice_no'];
	$invoice_date		= $row_ee11[0]['invoice_date'];
	$customer_name		= $row_ee11[0]['customer_name'];
	$phone_primary		= $row_ee11[0]['phone_primary'];
	$address_primary	= $row_ee11[0]['address_primary'];
	$sales_order_id		= $row_ee11[0]['sales_order_id'];
	$report_data = '<div class="">
						<div class="header">
							<h1>SALES ORDER</h1><hr>
						</div>
						<table border="0"> 
							<tbody>
								<tr>
									<td>
										<p align="center"><img src="../../../app-assets/images/logo/' . $company_logo . '" style="width:50px;height:50px;"></p>
										<p>' . $s_address . '</p>
										<p>Phone: ' . $compnay_phone_no . '</p>
									</td>
									<td width="250"></td>
									<td>
										<p><strong>Invoice#:</strong> ' . $invoice_no . '</p>
										<p><strong>Invoice Date:</strong> ' . dateformat2($invoice_date) . '</p>
									</td> 
								</tr> 
							</tbody>
						</table>
						<table class="table"> 
							<thead>
								<tr>
									<th>Ship From</th>
									<th>Bill To</th>
									<th>Ship To</th>
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
										<p>Name : ' . $customer_name . '</p>
										<p>Phone : ' . $phone_primary . '</p>
									</td>
									<td>
										<h3></h3>
										<p>Address : ' . $address_primary . '</p>
									</td> 
								</tr> 
							</tbody>
						</table>
						<table class="table1">
							<thead>
								<tr>
									<th>Product ID</th>
									<th>Description</th>
									<th>Serial#</th>
									<th>Sale Price</th>
								</tr>
							</thead>
							<tbody>';

					$sql_sub = "SELECT a.*, c.product_desc,b2.packing_type,a.box_no,a.pallet_no, d.category_name,b.order_status, 
									c.product_uniqueid, b1.order_qty,b1.order_price,b1.order_qty,b1.product_so_desc,
									b1.product_stock_id, c1.serial_no
								FROM `sales_order_detail_packing` a  
								INNER JOIN product_stock c1 ON c1.id = a.product_stock_id 
								INNER JOIN sales_orders b ON b.id = a.sale_order_id
								INNER JOIN `sales_order_detail` b1 ON b1.sales_order_id = a.sale_order_id AND b1.product_stock_id = a.product_stock_id
								INNER JOIN products c ON c.id = c1.product_id
								INNER JOIN packing_types b2 ON b2.id = a.packing_type
								LEFT JOIN product_categories d ON d.id = c.product_category
								WHERE b1.sales_order_id = '". $sales_order_id ."'
								ORDER BY b2.id";
					$result_sub 	= $db->query($conn, $sql_sub);
					$counter_sub	= $db->counter($result_sub);	
					$sub_total = $grand_total = $total = 0;	
						if($counter_sub > 0){
							$row_sub				= $db->fetch($result_sub);
							$packing = "";
							foreach($row_sub as $data_sub){
								$product_uniqueid		= $data_sub['product_uniqueid'];
								$product_desc			= $data_sub['product_desc'];
								$category_name			= $data_sub['category_name'];
								$order_price			= $data_sub['order_price'];
								$order_qty				= $data_sub['order_qty'];
								$serial_no				= $data_sub['serial_no'];
								$packing_type			= $data_sub['packing_type'];
								$box_no					= $data_sub['box_no'];
								if($box_no > 0){
									$packing = $packing_type." ".$box_no;
								}else{
									$packing = $packing_type;
								}
								$pallet_no				= $data_sub['pallet_no'];
								$sub_total 				= ($order_price * $order_qty);
								$grand_total 			+= $sub_total;
								$report_data .= '
								<tr>
									<td>'.$product_uniqueid.'</td>
									<td>'.$product_desc.'( '.$category_name.' ) </td>
									<td>'.$serial_no.' </td>
									<td>'.number_format($order_price,2).'</td>
								</tr>';
							}
						}
					$report_data .= '
									<tr> 
										<td colspan="2"></td>
										<td><b>Total Price</b></td>
										<td><b>'.number_format($grand_total,2).'</b></td>
									</tr>';
					$sql_cl1 	= " SELECT  a.invoice_id, a.total_amount,b.invoice_no,c.other_payment_deduction,a.id AS detail_id, a.deduction_id
									FROM sale_order_invoice_details a
									INNER JOIN sale_order_invoices AS b ON b.id = a.invoice_id
									INNER JOIN other_payments_deductions c ON c.id = a.deduction_id
									WHERE a.invoice_id = '".$id."' ";
					$result_cl1	= $db->query($conn, $sql_cl1);
					$count_cl1	= $db->counter($result_cl1);
					if($count_cl1 > 0){
						$row_cl1 = $db->fetch($result_cl1);
						foreach ($row_cl1 as $data1) {
							$id    						= $data1['invoice_id'];
							$detail_id    				= $data1['detail_id'];
							$other_payment_deduction    = $data1['other_payment_deduction'];
							$total_amount         		= $data1['total_amount'];
							if($data1['deduction_id'] == 3){
								$total = ($grand_total - $total_amount);
							}
							else{
								$total = ($grand_total + $total_amount);
							}
							$report_data .= '
								<tr> 
									<td colspan="2"></td>
									<td>'.$other_payment_deduction.'</td>
									<td>'.number_format($total_amount,2).'</td>
								</tr>';
						}
					}
			$report_data .= '
							<tr> 
								<td colspan="2"></td>
								<td><b>Total</b></td>
								<td><b>'.number_format($total,2).'</b></td>
							</tr>';

		$report_data .= '	</tbody>
						</table> 
					</div>';
	$report_data = $report_data . $css;
	//echo $report_data;die;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('Sales Order Invoice - ' . $invoice_no);
	$file_name = "Sales_Order_Invoice_" . $invoice_no . "_" . date('YmdHis') . ".pdf";
	$mpdf->output($file_name, 'I');
} else {
	$report_data = '
	<div class="text_align_center main_font"> No record found </div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);
	$mpdf->SetTitle('Sales Order Invoice - ' . $invoice_no);
	$file_name = "Sales_Order_Invoice_" . $invoice_no . "_" . date('YmdHis') . ".pdf";
	$mpdf->output($file_name, 'I');
}
