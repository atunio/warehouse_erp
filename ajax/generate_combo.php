<?php
include('../conf/session_start.php');
include("../conf/connection.php");
include("../conf/functions.php");
$db = new mySqlDB;
foreach ($_REQUEST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
extract($_REQUEST);
switch ($source_field) {
	case 'status_id_rma_record':

		$sql_pd1	= "	SELECT a.*
						FROM purchase_order_detail_receive_rma a
						WHERE a.receive_id = '" . $id . "' ";
		$result_pd1	= $db->query($conn, $sql_pd1);
		$count_pd1	= $db->counter($result_pd1);
		if ($count_pd1 == 0) {
			$sql6 = "INSERT INTO purchase_order_detail_receive_rma(receive_id, status_id, add_by_user_id, add_date,  add_by, add_ip, add_timezone)
							VALUES('" . $id . "', '" . $source_field_val . "', '" . $_SESSION['user_id'] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "')";
			// echo "<br><br>" . $sql6;
			$ok = $db->query($conn, $sql6);
			if ($ok) {
				$sql_c_up 	= "UPDATE purchase_order_detail_receive SET is_rma_processed = '1'  WHERE id = '" . $id . "' ";
				$db->query($conn, $sql_c_up);
				$k++;
			}
		} else {
			$sql_c_up 	= "	UPDATE purchase_order_detail_receive_rma SET status_id = '" . $source_field_val . "' 
							WHERE edit_lock = 0 
							AND receive_id = '" . $id . "' ";
			$db->query($conn, $sql_c_up);

			$sql_c_up 	= "UPDATE purchase_order_detail_receive SET is_rma_processed = '1'  WHERE id = '" . $id . "' ";
			$db->query($conn, $sql_c_up);
		}
		$array[] = array('1' => 'Record Updated Successfully');
		break;

	case 'product_id_for_package_material':
		$sql 	= "	SELECT  a.id, b.category_name, a.package_name
					FROM packages a
					INNER JOIN product_categories b ON b.id = a.product_category
					WHERE 1=1
					AND a.enabled = 1
					AND FIND_IN_SET(  " . $product_id_for_package_material . " , a.product_ids) > 0 ";
		$result	= $db->query($conn, $sql);
		$count	= $db->counter($result);
		if ($count > 0) {
			$row = $db->fetch($result);
			$array[] = array('0' => 'Select');
			if ($count > 1) {
			}
			foreach ($row as $data) {
				$array[] = array($data['id'] => $data['package_name'] . " (" . $data['category_name'] . ")");
			}
		} else {
			$array[] = array('0' => 'No Packaging Material / Part Available');
		}
		break;

	case 'stock_id_for_package_material':
		$sql 	= "	SELECT d.*, e.category_name, a.is_mandatory
					FROM product_packages a 
					INNER JOIN products b ON b.id = a.product_id
					INNER JOIN product_stock c ON b.id = c.product_id
					INNER JOIN packages d ON d.id = a.package_id
					INNER JOIN product_categories e ON e.id = d.product_category
					WHERE c.id = '" . $stock_id_for_package_material . "'
					AND d.stock_in_hand >0
					ORDER BY a.is_mandatory DESC, d.package_name  ";
		$result	= $db->query($conn, $sql);
		$count	= $db->counter($result);
		if ($count > 0) {
			$row = $db->fetch($result);
			$array[] = array('0' => 'Select');
			if ($count > 1) {
			}
			foreach ($row as $data) {
				$mandatory_optional = "";
				if ($data['is_mandatory'] == "Yes") {
					$mandatory_optional = " - Mandatory -";
				}
				if ($data['is_mandatory'] == "No") {
					$mandatory_optional = " - Optional -";
				}
				$array[] = array($data['id'] => $data['package_name'] . " (" . $data['category_name'] . ")," . $mandatory_optional . " Total Stock Available: " . $data['stock_in_hand']);
			}
		} else {
			$array[] = array('0' => 'No Packaging Material / Part Available');
		}
		break;
	case 'stock_id_for_package_material2':
		$sql 	= "	SELECT DISTINCT d.id, e.category_name, d.package_name, d.stock_in_hand
					FROM products b
					INNER JOIN product_stock c ON b.id = c.product_id
					INNER JOIN packages d ON FIND_IN_SET(  b.id , d.product_ids) > 0
					INNER JOIN product_categories e ON e.id = d.product_category
					WHERE c.id = '" . $stock_id_for_package_material2 . "'
					ORDER BY d.package_name ";
		$result	= $db->query($conn, $sql);
		$count	= $db->counter($result);
		if ($count > 0) {
			$row = $db->fetch($result);
			$array[] = array('0' => 'Select');
			if ($count > 1) {
			}
			foreach ($row as $data) {
				$array[] = array($data['id'] => $data['package_name'] . " (" . $data['category_name'] . "), Total Stock Available: " . $data['stock_in_hand']);
			}
		} else {
			$array[] = array('0' => 'No Packaging Material / Part Available');
		}
		break;
	case 'po_no_for_product_ids':
		$sql 	= "	SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid
					FROM purchase_order_detail a
					INNER JOIN purchase_orders b ON b.id = a.po_id
					INNER JOIN products c ON c.id = a.product_id
					INNER JOIN product_categories d ON d.id = c.product_category
					WHERE 1 		= 1
					AND a.enabled 	= 1
					AND b.po_no = '" . $po_no_for_product_ids . "'
					ORDER BY c.product_uniqueid, a.product_condition";
		$result	= $db->query($conn, $sql);
		$count	= $db->counter($result);
		if ($count > 0) {
			$row = $db->fetch($result);
			$array[] = array('0' => 'Select');
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
