<?php
$directory_path_for_ajax = "../";
include($directory_path_for_ajax . "conf/session_start.php");
include($directory_path_for_ajax . "conf/connection.php");
include($directory_path_for_ajax . "conf/functions.php");
$db = new mySqlDB;

foreach ($_POST as $key => $value) {
    if (!is_array($value)) {
        $data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
        $$key = $data[$key];
    }
}
extract($_POST);
switch ($type) {
    case 'attendance':
        if($password != "" && $time_flag != ""){
            $time_in = $time_out = 0;
            if($time_flag == 'timein'){
                $time_in = 1;
            }else{
                $time_out = 1;
            }
            echo "working fine";
        }
    break;
}