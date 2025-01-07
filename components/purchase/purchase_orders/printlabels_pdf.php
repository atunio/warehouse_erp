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


$sql_ee1 = " SELECT a.*, b.po_no, c.vender_name
			FROM purchase_order_detail_logistics a
			INNER JOIN purchase_orders b ON b.id = a.po_id
			LEFT JOIN venders c ON c.id = b.vender_id
			WHERE a.id = '" . $detail_id . "'";
$result_ee1 	= $db->query($conn, $sql_ee1);
$counter_ee1	= $db->counter($result_ee1);
if ($counter_ee1 > 0) {
	$row_ee1				= $db->fetch($result_ee1);
	$vender_name			= $row_ee1[0]['vender_name'];
	$po_no					= $row_ee1[0]['po_no'];
	$arrived_date			= dateformat2($row_ee1[0]['arrived_date']);
	$arrival_no				= $row_ee1[0]['arrival_no'];

	$sql_ee1 		= "	SELECT a.id
						FROM purchase_order_detail_logistics a
						WHERE a.po_id = '" . $id . "' ";
	$result_ee1 	= $db->query($conn, $sql_ee1);
	$total_boxes	= $db->counter($result_ee1);

	$report_data = '
	<div class="text_align_center main_font">
		<br>
		PO#: ' . $po_no . '
		<br>
		<barcode code="' . $po_no . '" type="C39" size="1" height="1" />
		<br><br>
		Vender Name: ' . $vender_name . '
		<br><br>
		Box/Pallet # ' . $arrival_no . ' out ' . $total_boxes . '
		<br><br>
		' . $arrived_date . '
	</div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('Arrival Label PO_NO ' . $po_no);
	$file_name = "Arrival Label " . $po_no . ".pdf";
	$mpdf->output($file_name, 'I');
} else {
	$report_data = '
	<div class="text_align_center main_font"> No record found </div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('Arrival Label PO_NO ' . $po_no);
	$file_name = "Arrival Label " . $po_no . ".pdf";
	$mpdf->output($file_name, 'I');
}
