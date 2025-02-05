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
		.text_align_center{
			text-align: center;
		}
		.main_font{
			font-size: 28px;
		}
	</style>";
require_once $directory_path . 'mpdf/vendor/autoload.php';
// $mpdf = new \Mpdf\Mpdf();
$mpdf = new \Mpdf\Mpdf(['format' => [101.6, 152.4]]);

$total_boxes 		= 3;
$box_arrival_no 	= 1;


$sql_ee1 = "SELECT DISTINCT b2.return_no, a.po_detail_id, c.product_desc, c.product_uniqueid, d.category_name, count(a.id) as total_products
			FROM purchase_order_detail_receive a
			INNER JOIN purchase_order_detail b ON b.id = a.po_detail_id
			INNER JOIN purchase_orders b2 ON b2.id = b.po_id
			INNER JOIN products c ON c.id = b.product_id
			LEFT JOIN product_categories d ON d.id =c.product_category
			WHERE a.enabled = 1 
			AND a.po_detail_id = '" . $detail_id . "'
			GROUP BY a.po_detail_id
			ORDER BY a.po_detail_id ";
$result_ee1 	= $db->query($conn, $sql_ee1);
$counter_ee1	= $db->counter($result_ee1);
if ($counter_ee1 > 0) {
	$row_ee1				= $db->fetch($result_ee1);
	$return_no					= $row_ee1[0]['return_no'];
	$po_productNo			= $row_ee1[0]['return_no'] . "P" . $row_ee1[0]['po_detail_id'];
	$product_uniqueid		= $row_ee1[0]['product_uniqueid'];
	$total_products			= $row_ee1[0]['total_products'];
	$product_desc			= $row_ee1[0]['product_desc'];
	if ($row_ee1[0]['category_name'] != "") {
		$product_desc =  $product_desc . " (" . $row_ee1[0]['category_name'] . ")";
	}

	$report_data = '
	<div class="text_align_center main_font">
		Diagnostic Invoice#: ' . $po_productNo . '
		<br>
		<barcode code="' . $po_productNo . '" type="C39" size="1" height="1" />
		<br><br>
		Product ID: ' . $product_uniqueid . '
		<br><br> 
		Product: ' . $product_desc . '
		<br><br>
		Qty: ' . $total_products . '
	</div>';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('PO Product No  Label Print, PO ProductNo ' . $po_productNo);
	$file_name = "PO_ProductNo_Label " . $po_productNo . ".pdf";
	$mpdf->output($file_name, 'I');
} else {
	$report_data = '
	<div class="text_align_center main_font"> No record found </div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);
	$mpdf->SetTitle('PO Product No  Label Print, PO ProductNo ' . $po_productNo);
	$file_name = "PO_ProductNo_Label " . $po_productNo . ".pdf";
	$mpdf->output($file_name, 'I');
}
