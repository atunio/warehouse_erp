<?php
$sku_code = "";
foreach ($device_detail_array as $key1 => $data_api) {
    foreach ($data_api as $key2 => $data2) {
        if ($key2 == 'BatteryHealthPercentage') {
            $battery = $data2;
        }
        if ($key2 == 'SKUCode') {
            $phone_check_product_id = $data2;
            $sku_code = $data2;
        }
        if ($key2 == 'Model') {
            $model_name = str_replace('"', '', $data2);
        }
        if ($key2 == 'Model#') {
            $model_no = $data2;
        }
        if ($key2 == 'Make') {
            $make_name = $data2;
        }
        if ($key2 == 'Carrier') {
            $carrier_name = $data2;
        }
        if ($key2 == 'Color') {
            $color_name = $data2;
        }
        if ($key2 == 'Cosmetics') {
            $body_grade = $lcd_grade = $digitizer_grade = $etching = "";
            if ($data2 != "") {
                $pass_array = explode(",", $data2);
                foreach ($pass_array as $data_cosmetic) {
                    list($prefix, $value) = explode("-", $data_cosmetic);
                    // Categorize based on the prefix
                    if ($prefix == 'D') {
                        $digitizer_grade = $value;
                    } elseif ($prefix == 'ET') {
                        $etching = $value;
                    } elseif ($prefix == 'B') {
                        $body_grade = $value;
                    } elseif ($prefix == 'L') {
                        $lcd_grade = $value;
                    }
                }
            }
        }
        if ($key2 == 'Ram') {
            $ram = $data2;
        }
        if ($key2 == 'Memory') {
            $memory = $data2;
        }
        if ($key2 == 'DefectsCode') {
            $defectsCode = $data2;
        }
        if ($key2 == 'Grade') {
            $overall_grade = $data2;
        }
    }
}

include("overall_grade_calculation.php");

$inventory_status = '6';
$status_name = "Defective";
if ($defectsCode == '' || $defectsCode == NULL) {
    if ($lcd_grade == '' || $digitizer_grade == '' || $body_grade == '') {
        $inventory_status = '';
        $status_name = "";
    } else if (is_numeric($battery) && $battery >= '60') {
        $inventory_status = '5';
        $status_name = "Tested/Graded";
    } else {
        $inventory_status = '';
        $status_name = "";
    }
}

if (isset($serial_no_barcode_diagnostic) && $serial_no_barcode_diagnostic != "") {
    $serial_no_barcode = $serial_no_barcode_diagnostic;
}
if (!isset($insert_bin_and_po_id_fields)) {
    $insert_bin_and_po_id_fields = "";
    $insert_bin_and_po_id_values = "";
}
if(isset($phone_check_api_data_id) && $phone_check_api_data_id > 0){
    $sql6 = "UPDATE phone_check_api_data SET
                                                model_name              = '" . $model_name . "',
                                                model_no                = '" . $model_no . "',
                                                make_name               = '" . $make_name . "',
                                                sku_code                = '" . $sku_code . "',
                                                carrier_name            = '" . $carrier_name . "',
                                                color_name              = '" . $color_name . "',
                                                battery                 = '" . $battery . "',
                                                body_grade              = '" . $body_grade . "',
                                                lcd_grade               = '" . $lcd_grade . "',
                                                digitizer_grade         = '" . $digitizer_grade . "',
                                                etching                 = '" . $etching . "',
                                                ram                     = '" . $ram . "',
                                                memory                  = '" . $memory . "',
                                                defectsCode             = '" . $defectsCode . "',
                                                overall_grade           = '" . $overall_grade . "',
                                                phone_check_api_data    = '" . $jsonData2 . "',

                                                update_date 			= '" . $add_date . "', 
                                                update_by 				= '" . $_SESSION['username'] . "', 
                                                update_by_user_id 		= '" . $_SESSION['user_id'] . "', 
                                                update_ip 				= '" . $add_ip . "', 
                                                update_timezone 		= '" . $timezone . "', 
                                                update_from_module_id 	= '" . $module_id . "'
                WHERE id 	= '" . $phone_check_api_data_id . "'  "; //echo "<br><br>".$sql6;
    $db->query($conn, $sql6);
}
else{
    if ($serial_no_barcode != "" && $serial_no_barcode != NULL) {
        $sql = "INSERT INTO phone_check_api_data(" . $insert_bin_and_po_id_fields . " imei_no, model_name, model_no, make_name, sku_code,
                    carrier_name, color_name, battery, body_grade, lcd_grade, digitizer_grade, etching, 
                    `ram`, `memory`, defectsCode, overall_grade, phone_check_api_data, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id)
                VALUES	(" . $insert_bin_and_po_id_values . " '" . $serial_no_barcode . "', '" . $model_name . "', '" . $model_no . "','" . $make_name . "', '" . $sku_code . "', 
                    '" . $carrier_name . "', '" . $color_name . "','" . $battery . "', '" . $body_grade . "', '" . $lcd_grade . "', '" . $digitizer_grade . "','" . $etching . "', 
                    '" . $ram . "', '" . $memory . "',  '" . $defectsCode . "',  '" . $overall_grade . "', '" . $jsonData2 . "', 
                    '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . TIME_ZONE . "', '" . $module_id . "')";
        $db->query($conn, $sql);
    }
} 
