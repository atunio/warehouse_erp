<?php
session_name("albert_warehouse_erp_dg");
session_start(); //We start the session 
$directory_path = "../";
include($directory_path . "conf/connection.php");
include($directory_path . "conf/functions.php");
$db = new mySqlDB;
foreach ($_REQUEST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
extract($_REQUEST);
switch ($source_field) {
	case 'po_no_for_product_ids':
		$sql 	= "	SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid
					FROM purchase_order_detail a 
					INNER JOIN purchase_orders b ON b.id = a.po_id
					INNER JOIN products c ON c.id = a.product_id
					INNER JOIN product_categories d ON d.id = c.product_category
					WHERE 1=1 
					AND a.enabled = 1 
					AND b.po_no = '" . $po_no_for_product_ids . "'
					ORDER BY c.product_uniqueid, a.product_condition  ";
		$result	= $db->query($conn, $sql);
		$count	= $db->counter($result);
		if ($count > 0) {
			$row = $db->fetch($result);
			if ($count > 1) {
				$array[] = array('0' => 'Select');
			}
			foreach ($row as $data) {
				$display_product_detail = " Product ID: " . $data['product_uniqueid'] . " -  Product: " . $data['product_desc'];
				if ($data['category_name'] != "") {
					$display_product_detail .= " (" . $data['category_name'] . ") ";
				}
				$array[] = array($data['id'] => $display_product_detail);
			}
		} else {
			$array[] = array('0' => 'No Product Available');
		}
		break;
}
echo json_encode($array);
