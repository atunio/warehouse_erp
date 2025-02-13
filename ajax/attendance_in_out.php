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
        if($emp_pin_code != "" && $time_flag != ""){
            
            $sql = "SELECT id  , department_id, user_id, subscriber_users_id, e_full_name  FROM employee_profile WHERE emp_pin_code = '".$emp_pin_code."' ";
            $result = $db->query($conn, $sql);
            $count = $db->counter($result);
            if ($count > 0) {
                $row                    = $db->fetch($result);
                $emp_id                 = $row[0]['id'];
                $department_id          = $row[0]['department_id'];
                $user_id                = $row[0]['user_id'];
                $subscriber_users_id    = $row[0]['subscriber_users_id'];
                $e_full_name            = $row[0]['e_full_name'];
                
                $clocked_in = $clocked_out = "00:00";
                $now = new DateTime();
                $hours = $now->format('H'); // 24-hour format
                $minutes = $now->format('i');
                $seconds = $now->format('s');
                // For 24-hour format, no AM/PM is needed
                $timeString = $hours . ':' . $minutes . ':' . $seconds;

                $now_disp = new DateTime();
                $hours_disp = $now_disp->format('H');
                $minutes_disp = $now_disp->format('i');
                $seconds_disp = $now_disp->format('s');
                $ampm_disp = ($hours_disp >= 12) ? 'pm' : 'am';
                $hours_disp = $hours_disp % 12;
                $hours_disp = $hours_disp ? $hours_disp : 12; // if 0, set to 12

                $minutes_disp = $minutes_disp < 10 ? ' ' . $minutes_disp : $minutes_disp;
                $seconds_disp = $seconds_disp < 10 ? ' ' . $seconds_disp : $seconds_disp;

                $timeString12 = $hours_disp . ':' . $minutes_disp . ':' . $seconds_disp . ' ' . $ampm_disp;


                if($time_flag == 'timein'){
                    $dis_time_flag = "Clock In";
                    $clock_date = date("Y-m-d");
                     $clock_field_name = "clocked_in";
                }else{
                    $dis_time_flag = "Clock Out";
                    $clock_date = date("Y-m-d");
                     $clock_field_name = "clocked_out";
                }

                $sql = "SELECT id, clocked_in, clocked_out  FROM timesheets WHERE employee_id = '".$emp_id."' AND  clock_date = '".$clock_date."' ORDER BY id DESC LIMIT 1";
                $result = $db->query($conn, $sql);
                $count = $db->counter($result);
                if ($count == 0){
                    if($time_flag == 'timein'){
                        $sql6 = "INSERT INTO timesheets(subscriber_users_id, employee_id, department_id, clock_date, clocked_in, add_date, add_by, add_by_user_id, add_ip, add_timezone)
                        VALUES('" . $subscriber_users_id . "', '" . $emp_id . "', '" . $department_id . "', '" . $clock_date . "', '" . $timeString . "', '" . $add_date . "', '" . $emp_pin_code . "', '" . $user_id . "', '" . $add_ip . "', '" . $timezone . "')";
                        $ok = $db->query($conn, $sql6);
                        if($ok){
                            echo $e_full_name." - ".$dis_time_flag.": ".$timeString12;
                        }
                    } else if($time_flag == 'timeout'){
                        echo "FailClockIn";
                    }
                   
                }else{
                    $row1          = $db->fetch($result);
                    $id            = $row1[0]['id'];
                    $clocked_in    = $row1[0]['clocked_in'];
                    $clocked_out    = $row1[0]['clocked_out'];
                    if($clocked_in != '00:00:00' && $clocked_in != null && ($clocked_out == "00:00:00" || $clocked_out == null) && $time_flag == 'timeout'){

                        if (!empty($clocked_in) && !empty($timeString)) {
                            $in_time = strtotime($clocked_in);
                            $out_time = strtotime($timeString);
                    
                            if ($out_time < $in_time) {
                                $out_time += 86400;
                            }
                    
                            $diff = $out_time - $in_time;
                            $worked_minutes = floor($diff / 60);
                            $hours = floor($worked_minutes / 60);
                            $minutes = $worked_minutes % 60;
                    
                            $worked_hours = "$hours hr $minutes min";
                        } else {
                            $worked_hours = "0 hr 0 min";
                        }

                        $sql6 = "UPDATE timesheets SET  clocked_out             = '" . $timeString . "', 
                                                        worked_hours            = '" . $worked_hours . "',
                                                        update_date             = '" . $add_date . "', 
                                                        update_by               = '" . $emp_pin_code . "', 
                                                        update_by_user_id       = '" . $user_id . "', 
                                                        update_ip               = '" . $add_ip . "'
                                                        
                        WHERE id = '".$id."' ";
                        $ok = $db->query($conn, $sql6);
                        if($ok){
                            echo $e_full_name." - Clock Out : ".$timeString12;
                        }
                    
                    }else if($time_flag == 'timein' ){
                        if($clocked_in != '00:00:00' && $clocked_in != null && ($clocked_out != "00:00:00" && $clocked_out != null) ){
                            $sql6 = "INSERT INTO timesheets(subscriber_users_id, employee_id, department_id, clock_date, clocked_in, add_date, add_by, add_by_user_id, add_ip, add_timezone)
                            VALUES('" . $subscriber_users_id . "', '" . $emp_id . "', '" . $department_id . "', '" . $clock_date . "', '" . $timeString . "', '" . $add_date . "', '" . $emp_pin_code . "', '" . $user_id . "', '" . $add_ip . "', '" . $timezone . "')";
                            $ok = $db->query($conn, $sql6);
                            if($ok){
                                echo $e_full_name." - ".$dis_time_flag.": ".$timeString12;
                            }
                        } else if($time_flag == 'timein'){
                            echo "FailClockOut";
                        }
                    }else if($time_flag == 'timeout' && ($clocked_out != "00:00:00" && $clocked_out != null)){
                        echo "ClockOut";
                    }else if($clocked_in != '00:00:00' && $clocked_in != null && ($clocked_out != "00:00:00" && $clocked_out != null) ){
                        echo "ClockInOut";
                    }
                }
            }else{
                echo "Invalid";
            }
        }
    break;
}