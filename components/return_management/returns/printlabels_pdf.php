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


$sql_ee1 = " SELECT  a.*, b.return_no, c.store_name
FROM return_order_detail_logistics a  INNER JOIN RETURNS b ON b.id = a.return_id LEFT JOIN `stores` c  ON c.id = b.store_id
WHERE a.return_id ='" . $id . "'
			ORDER BY date_format(a.arrived_date, '%Y%m%d') "; //echo $sql_ee1;die;
$k = 1;
$result_ee1 	= $db->query($conn, $sql_ee1);
$counter_ee1	= $db->counter($result_ee1);
if ($counter_ee1 > 0) {
	$row_ee1				= $db->fetch($result_ee1);
	foreach ($row_ee1 as $data) {

		$logistic_id			= $data['id'];
		$store_name				= $data['store_name'];
		$return_no				= $data['return_no'];
		$arrived_date			= dateformat2($data['arrived_date']);
		$arrival_no				= $data['arrival_no'];
		$no_of_boxes			= $data['no_of_boxes'];

		$sql_ee12 		= "	SELECT IFNULL(SUM(a.no_of_boxes), 0) as total_boxes
							FROM return_order_detail_logistics a
							WHERE a.return_id = '" . $id . "' ";
		$result_ee12 	= $db->query($conn, $sql_ee12);
		$counter_ee12	= $db->counter($result_ee12);
		$total_boxes 	= 0;
		if ($counter_ee12 > 0) {
			$row_ee12		= $db->fetch($result_ee12);
			$total_boxes	= $row_ee12[0]['total_boxes'];
		}
		for ($i = 1; $i <= $no_of_boxes; $i++) {
			$report_data = '
				<div class="text_align_center main_font">
					<br>
					Return#: ' . $return_no . '
					<br>
					<barcode code="' . $return_no . '" type="C39" size="1" height="1" />
					<br><br>
					Store Name: ' . $store_name . '
					<br><br>
					Box/Pallet # ' . $k . ' out ' . $total_boxes . '
					<br><br>
					' . $arrived_date . '
				</div>  ';  //echo $report_data;die;
			$report_data = $report_data . $css;
			$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
			$mpdf->writeHTML($report_data);
			$k++;
		}
	}

	$mpdf->SetTitle('Arrival Label PO_NO ' . $return_no);
	$file_name = "Arrival Label " . $return_no . ".pdf";
	$mpdf->output($file_name, 'I');
} else {
	$report_data = '
	<div class="text_align_center main_font"> No record found </div>  ';  //echo $report_data;die;
	$report_data = $report_data . $css;
	$mpdf->AddPage('P', '', '', '', '', 10, 10, 15, 10, 0, 0);
	$mpdf->writeHTML($report_data);

	$mpdf->SetTitle('Arrival Label PO_NO ' . $return_no);
	$file_name = "Arrival Label " . $return_no . ".pdf";
	$mpdf->output($file_name, 'I');
}
