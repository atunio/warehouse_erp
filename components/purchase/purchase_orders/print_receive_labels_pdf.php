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
		if ($string_data_explode[0] == 'sub_location_id') {
			$sub_location_id = $string_data_explode[1];
		}
		if ($string_data_explode[0] == 'product_category') {
			$product_category = $string_data_explode[1];
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
			font-size: 25px;
		}
	</style>";
require_once $directory_path . 'mpdf/vendor/autoload.php';
// $mpdf = new \Mpdf\Mpdf();
$mpdf = new \Mpdf\Mpdf(['format' => [101.6, 152.4]]);

$total_boxes 		= 3;
$box_arrival_no 	= 1;


$sql_ee1 = " SELECT po_no, vender_name, sub_location_id, sub_location_name, sub_location_type, product_category, category_name, SUM(total_products) AS total_products
			FROM (
				SELECT b1.po_no, f.vender_name, a.sub_location_id, e.sub_location_name, e.sub_location_type, c.product_category, d.`category_name`, COUNT(a.id) AS total_products
				FROM purchase_order_detail b 
				INNER JOIN purchase_orders b1 ON b1.id = b.po_id
				INNER JOIN products c ON c.id = b.product_id
				INNER JOIN purchase_order_detail_receive a ON a.`po_detail_id` = b.id
				LEFT JOIN warehouse_sub_locations e ON e.id = a.sub_location_id
				INNER JOIN product_categories d ON d.id = c.product_category  
				LEFT JOIN venders f ON f.id = b1.vender_id
				WHERE a.enabled = 1 
				AND b.po_id = '" . $id . "'
				AND a.`receive_type` != 'CateogryReceived'
				GROUP BY c.product_category, a.sub_location_id

				UNION ALL 

				SELECT b1.po_no,  f.vender_name, a.sub_location_id, e.sub_location_name, e.sub_location_type, a.recevied_product_category AS product_category, d.`category_name`, COUNT(a.id) AS total_products
				FROM purchase_order_detail_receive a 
				INNER JOIN purchase_orders b1 ON b1.id = a.po_id
				INNER JOIN product_categories d ON d.id = a.recevied_product_category  
				LEFT JOIN warehouse_sub_locations e ON e.id = a.sub_location_id
				LEFT JOIN venders f ON f.id = b1.vender_id
				WHERE a.po_id = '" . $id . "'
				GROUP BY a.recevied_product_category, a.sub_location_id
			) AS t1
			WHERE product_category 	=  '" . $product_category . "'
			GROUP BY category_name, sub_location_name
			ORDER BY category_name, sub_location_name ";
// echo $sql_ee1;die;
$k = 1;
$result_ee1 	= $db->query($conn, $sql_ee1);
$counter_ee1	= $db->counter($result_ee1);
if ($counter_ee1 > 0) {
	$row_ee1				= $db->fetch($result_ee1);
	foreach ($row_ee1 as $data) {
		$vender_name			= $data['vender_name'];
		$po_no					= $data['po_no'];
		$sub_location_name		= $data['sub_location_name'];
		$sub_location_type		= $data['sub_location_type'];
		$total_products			= $data['total_products'];
		if ($sub_location_type != "") {
			$sub_location_name .= "(" . $sub_location_type . ")";
		}
		$category_name			= $data['category_name'];


		$report_data = '
			<div class="text_align_center main_font">
				<br>
				PO#: ' . $po_no . '
				<br>
				<barcode code="' . $po_no . '" type="C39" size="1" height="1" />
				<br><br>
				Vendor: ' . $vender_name . '
				<br><br>
				' . $category_name . '
				<br>
				Qty: ' . $total_products . '
				<br><br>
				' . $sub_location_name . '
				<br>
				' . $arrived_date . '
			</div>  ';  //echo $report_data;die;
		$report_data = $report_data . $css;
		$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
		$mpdf->writeHTML($report_data);
		$k++;
	}

	$mpdf->SetTitle('Receive Label PO#: ' . $po_no);
	$file_name = "Receive Label " . $po_no . ".pdf";
	$mpdf->output($file_name, 'I');
} else {
	$report_data = '
	<div class="text_align_center main_font"> No record found </div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('Receive Label PO_NO ' . $id);
	$file_name = "Receive Label " . $id . ".pdf";
	$mpdf->output($file_name, 'I');
}
