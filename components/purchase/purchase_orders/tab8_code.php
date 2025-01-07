<?php
if ($_SERVER['HTTP_HOST'] == 'localhost' && $test_on_local == 1) {
}
if (isset($_POST['is_Submit_tab8']) && $_POST['is_Submit_tab8'] == 'Y') {
	extract($_POST);
	if (empty($error8)) {
		if (po_permisions("PriceSetup") == 0) {
			$error8['msg'] = "You do not have add permissions.";
		} else {
			$sql_8_p1		= "  SELECT * FROM temp_po_pricing a WHERE po_id = '" . $id . "' AND uniq_session_id = '" . $uniq_session_id . "' "; // echo $sql_8_p1;
			$result_8_p1 	= $db->query($conn, $sql_8_p1);
			$count_8_p1  	= $db->counter($result_8_p1);
			if ($count_8_p1 > 0) {
				$row_8_p1 = $db->fetch($result_8_p1);
				foreach ($row_8_p1 as $data_8_p1) {
					$po_product_uniq_id = $data_8_p1['po_product_uniq_id'];
					$price_grade 		= $data_8_p1['price_grade'];
					$suggested_price 	= $data_8_p1['suggested_price'];
					$sql_c_up = "	UPDATE product_stock a
									INNER JOIN purchase_order_detail_receive b ON b.id = a.receive_id
									INNER JOIN purchase_order_detail c ON c.id = b.po_detail_id
									INNER JOIN products d ON d.id = a.product_id
									SET a.price = round(" . $suggested_price . ", 2),
										a.is_final_pricing = 1
									WHERE c.po_id 				= '" . $id . "' 
									AND d.product_uniqueid 		= '" . $po_product_uniq_id . "'
									AND a.stock_grade 			= '" . $price_grade . "'
									AND a.p_inventory_status 	= 5  ";
					// echo "<br>" . $sql_c_up;
					$db->query($conn, $sql_c_up);
				}
			}
			$msg8['msg_success'] = " Pricing has been processed successfully.";
		}
	} else {
		if (!isset($error8['msg'])) {
			$error8['msg'] = "Please select all required fields";
		}
	}
}
