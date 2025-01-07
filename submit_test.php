<?php
die;
include('conf/session_start.php');
include("conf/connection.php");
include("conf/functions.php");
$db = new mySqlDB;
foreach ($_REQUEST as $key => $value) {
    if (!is_array($value)) {
        $data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
        $$key = $data[$key];
    }
}

$data = json_decode(file_get_contents("php://input"));

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "albert_warehouse_erp";
// $conn = new mysqli($servername, $username, $password, $dbname);
// encrypt user_access_token product_id

$camera_status          = $data->camera_status;
$speaker_status         = $data->speaker_status;
$mic_status             = $data->mic_status;
$keyboard_status        = $data->keyboard_status;
$keyboardTest_LeftKeys  = $data->keyboardTest_LeftKeys;
$battery_status         = $data->battery_status;
$battery_percentage     = $data->battery_percentage;
$charging_time          = $data->charging_time;
$serial_no              = $data->serial_no;
$user_access_token      = decrypt($data->user_access_token);
$product_id             = decrypt($data->product_id);
$sub_location_id        = $data->sub_location_id;
$body_grade             = $data->body_grade;
$lcd_grade              = $data->lcd_grade;
$digitizer_grade        = $data->digitizer_grade;
$overall_grade          = $data->overall_grade;
$diagnose_by_user       = $_SESSION['diagnose_by_user'];
$diagnose_by_user_id    = $_SESSION['diagnose_by_user_id'];

$sql_pd1    = "	SELECT a.*
                FROM purchase_order_detail_receive a
                WHERE a.serial_no_barcode = '" . $serial_no . "' ";
$result_pd1    = $db->query($conn, $sql_pd1);
$count_pd1    = $db->counter($result_pd1);
if ($count_pd1 > 0) {
    $row_pd01       = $db->fetch($result_pd1);
    $receive_id_2   = $row_pd01[0]['id'];
    $sql_c_up = "UPDATE  purchase_order_detail_receive SET	 
                                                            sub_location_id_after_diagnostic	= '" . $sub_location_id . "',
                                                            is_diagnost							= '1',
                                                            body_grade					        = '" . $body_grade . "',
                                                            lcd_grade					        = '" . $lcd_grade . "',
                                                            digitizer_grade					    = '" . $digitizer_grade . "',
                                                            overall_grade					    = '" . $overall_grade . "',

                                                            camera_status					    = '" . $camera_status . "',
                                                            speaker_status					    = '" . $speaker_status . "',
                                                            mic_status					        = '" . $mic_status . "',
                                                            keyboard_status					    = '" . $keyboard_status . "',
                                                            keyboardTest_LeftKeys               = '" . $keyboardTest_LeftKeys . "',
                                                            battery_status					    = '" . $battery_status . "',
                                                            battery_percentage                  = '" . $battery_percentage . "',
                                                            charging_time					    = '" . $charging_time . "',
 
                                                            diagnose_by_user					= '" . $diagnose_by_user . "',
                                                            diagnose_by_user_id					= '" . $diagnose_by_user_id . "',
                                                            diagnose_timezone					= '" . $timezone . "',
                                                            diagnose_date						= '" . $add_date . "',
                                                            diagnose_ip							= '" . $add_ip . "'
                WHERE id = '" . $receive_id_2 . "' ";
    $ok = $db->query($conn, $sql_c_up);
    if ($ok) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'Fail']);
    }
} else {

    $sql_pd01         = "	SELECT a.* 
                            FROM purchase_order_detail_receive a 
                            WHERE a.enabled = 1 
                            AND a.po_detail_id = '" . $product_id . "' 
                            AND (a.serial_no_barcode IS NULL OR a.serial_no_barcode = '')
                            LIMIT 1";
    $result_pd01    = $db->query($conn, $sql_pd01);
    $count_pd01        = $db->counter($result_pd01);
    if ($count_pd01 > 0) {
        $row_pd01        = $db->fetch($result_pd01);
        $receive_id_2     = $row_pd01[0]['id'];
        $sql_c_up = "UPDATE  purchase_order_detail_receive SET	serial_no_barcode					= '" . $serial_no . "',
                                                                sub_location_id_after_diagnostic	= '" . $sub_location_id . "',
                                                                body_grade					        = '" . $body_grade . "',
                                                                lcd_grade					        = '" . $lcd_grade . "',
                                                                digitizer_grade					    = '" . $digitizer_grade . "',
                                                                overall_grade					    = '" . $overall_grade . "',
                                                            
                                                                camera_status					    = '" . $camera_status . "',
                                                                speaker_status					    = '" . $speaker_status . "',
                                                                mic_status					        = '" . $mic_status . "',
                                                                keyboard_status					    = '" . $keyboard_status . "',
                                                                keyboardTest_LeftKeys               = '" . $keyboardTest_LeftKeys . "',
                                                                battery_status					    = '" . $battery_status . "',
                                                                battery_percentage                  = '" . $battery_percentage . "',
                                                                charging_time					    = '" . $charging_time . "',

                                                                is_diagnost							= '1',
                                                                diagnose_by_user					= '" . $diagnose_by_user . "',
                                                                diagnose_by_user_id					= '" . $diagnose_by_user_id . "',
                                                                diagnose_timezone					= '" . $timezone . "',
                                                                diagnose_date						= '" . $add_date . "',
                                                                diagnose_ip							= '" . $add_ip . "'
                    WHERE id = '" . $receive_id_2 . "' ";
        $ok = $db->query($conn, $sql_c_up);
        if ($ok) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'Fail']);
        }
    }
}
/*
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO chrome_book_test_results (camera_status, speaker_status, mic_status, keyboard_status, keyboardTest_LeftKeys, battery_status, battery_percentage, charging_time)
VALUES ('" . $data->camera_status . "', '" . $data->speaker_status . "', '" . $data->mic_status . "', '" . $data->keyboard_status . "', '" . $data->keyboardTest_LeftKeys . "', 

'" . $data->battery_status . "', '" . $data->battery_percentage . "', '" . $data->charging_time . "')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}
$conn->close();

*/
