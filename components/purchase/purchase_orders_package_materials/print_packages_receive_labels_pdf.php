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
			font-size: 20px;
		}
	</style>";
require_once $directory_path . 'mpdf/vendor/autoload.php';
// $mpdf = new \Mpdf\Mpdf();
$mpdf = new \Mpdf\Mpdf(['format' => [152.4, 101.6]]);

$sql_ee1 = "SELECT c.po_no, c.po_date, g.vender_name, d.package_name, d.product_category, a.sub_location_id, f.sub_location_name, f.sub_location_type, e.`category_name`
			FROM package_materials_order_detail_receive a 
			INNER JOIN package_materials_order_detail b ON b.id = a.`po_detail_id`
			INNER JOIN package_materials_orders c ON c.id = b.po_id
			INNER JOIN packages d ON d.id = b.package_id
			INNER JOIN product_categories e ON e.id = d.product_category  
			LEFT JOIN warehouse_sub_locations f ON f.id = a.sub_location_id
			LEFT JOIN venders g ON g.id = c.vender_id
			WHERE b.po_id 			= '" . $id . "'
			AND b.package_id 		=  '" . $detail_id . "'
			ORDER BY b.package_id, f.sub_location_name ";
// echo $sql_ee1;die;
$k = 1;
$result_ee1 	= $db->query($conn, $sql_ee1);
$counter_ee1	= $db->counter($result_ee1);
if ($counter_ee1 > 0) {
	$row_ee1				= $db->fetch($result_ee1);
	foreach ($row_ee1 as $data) {
		$vender_name			= $data['vender_name'];
		$po_no					= $data['po_no'];
		$po_date				= $data['po_date'];
		$package_name			= $data['package_name'];
		$category_name			= $data['category_name'];
		$sub_location_name		= $data['sub_location_name'];
		$sub_location_type		= $data['sub_location_type'];
		if ($sub_location_type != "") {
			$sub_location_name .= " (" . $sub_location_type . ")";
		}
		if ($category_name != "") {
			$package_name .= " (" . $category_name . ")";
		}
		$report_data = '
			<div class="text_align_center main_font">
			
				PO#: ' . $po_no . '
				<br>
				<barcode code="' . $po_no . '" type="C39" size="1" height="1" />
				<br>' . dateformat2($po_date) . '
				<br><br>
				Vendor: ' . $vender_name . '
				<br><br>
				Package: ' . $package_name . '
				<br><br>
				Location: ' . $sub_location_name . ' 
			</div>  ';  //echo $report_data;die;
		$report_data = $report_data . $css;
		$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
		$mpdf->writeHTML($report_data);
		$k++;
	}

	$mpdf->SetTitle('Receive Package Material Label PO#: ' . $po_no);
	$file_name = "Receive Package Material Label " . $po_no . ".pdf";
	$mpdf->output($file_name, 'I');
} else {
	$report_data = '
	<div class="text_align_center main_font"> No record found </div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('Receive Package Material Label PO_NO ' . $id);
	$file_name = "Receive Label " . $id . ".pdf";
	$mpdf->output($file_name, 'I');
}
