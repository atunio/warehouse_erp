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
		.text_align_center{
			text-align: center;
		}
		.main_font{
			font-size: 28px;
		}
	</style>";
require_once $directory_path . 'mpdf/vendor/autoload.php';
// $mpdf = new \Mpdf\Mpdf();
$mpdf = new \Mpdf\Mpdf(['format' => [152.4, 101.6]]);

$k 				= 1;
$sql_ee1 		= " SELECT a.*, b.po_no, b.po_date, c.vender_name, d.package_name, d.package_desc, e.category_name, d.sku_code, b1.order_case_pack
					FROM package_materials_order_detail_logistics a
					INNER JOIN package_materials_order_detail b1 ON b1.id = a.po_detail_id
					INNER JOIN package_materials_orders b ON b.id = b1.po_id
					LEFT JOIN venders c ON c.id = b.vender_id
					INNER JOIN packages d ON d.id = b1.package_id
					LEFT JOIN product_categories e ON e.id = d.product_category
					WHERE a.po_id	= '" . $id . "' "; //echo $sql_ee1;die;
$result_ee1 	= $db->query($conn, $sql_ee1);
$counter_ee1	= $db->counter($result_ee1);
if ($counter_ee1 > 0) {
	$row_ee1 = $db->fetch($result_ee1);
	foreach ($row_ee1 as $data) {

		$vender_name		= $data['vender_name'];
		$po_no				= $data['po_no'];
		$po_date			= dateformat2($data['po_date']);
		$sku_code			= $data['sku_code'];
		$no_of_boxes		= $data['no_of_boxes'];
		$order_case_pack	= $data['order_case_pack'];
		$package_name		= $data['package_name'];
		$category_name		= $data['category_name'];
		$package_desc		= $data['package_desc'];

		$sql_ee12 			= "	SELECT IFNULL(SUM(a.no_of_boxes), 0) as total_boxes
								FROM package_materials_order_detail_logistics a
								WHERE a.po_id = '" . $id . "' ";
		$result_ee12 		= $db->query($conn, $sql_ee12);
		$counter_ee12		= $db->counter($result_ee12);
		$total_boxes 		= 0;
		if ($counter_ee12 > 0) {
			$row_ee12		= $db->fetch($result_ee12);
			$total_boxes	= $row_ee12[0]['total_boxes'];
		}
		for ($i = 1; $i <= $no_of_boxes; $i++) {
			$report_data = '
				<table style="border: 1px solid #eee; width: 100%;">
					<tr>
						<td style="border: 1px solid #eee; width: 40%; text-align: center;">Date: ' . $po_date . '</td>
						<td style="border: 1px solid #eee; width: 60%;">
							&nbsp;&nbsp;&nbsp;PO#: ' . $po_no . '
							<br><barcode code="' . $po_no . '" type="C39" size="1" height=".6" />
						</td>
					</tr>
					<tr>
						<td style="border: 1px solid #eee; text-align: center;">
							<span style="font-size: 40px;"><b>CTI</b></span>
							<br>
							</b>Case Pack:  ' . $order_case_pack . '</b>
						</td>
						<td style="border: 1px solid #eee;">
							&nbsp;&nbsp;&nbsp;SKU: <span style="font-size: 20px; font-weight: bold;">' . $sku_code . '</span>
							<br><barcode code="' . $sku_code . '" type="C39" size="1" height=".6" />
						</td>
					</tr>
					<tr>
						<td style="border: 1px solid #eee; text-align: center;">Package:</td>
						<td style="border: 1px solid #eee;">' . $package_name . '</td>
					</tr>
					<tr>
						<td style="border: 1px solid #eee; text-align: center;">Category:</td>
						<td style="border: 1px solid #eee;">' . $category_name . '</td>
					</tr>
					<tr>
						<td style="border: 1px solid #eee; text-align: center;">Description:</td>
						<td style="border: 1px solid #eee;">' . $package_desc . '</td>
					</tr>
				</table>  ';  //echo $report_data;die;
			$report_data = $report_data . $css;
			$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
			$mpdf->writeHTML($report_data);
			$k++;
		}
	}

	$mpdf->SetTitle('Packages Recived Labels Box Wise PO#: ' . $po_no);
	$file_name = "Packages Recived Labels Box Wise " . $po_no . ".pdf";

	$mpdf->output($file_name, 'I');
} else {
	$report_data = '
	<div class="text_align_center main_font"> No record found </div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('Packages Recived Labels Box Wise PO_NO ' . $id);
	$file_name = "Packages Recived Labels Box Wise " . $id . ".pdf";
	$mpdf->output($file_name, 'I');
}
