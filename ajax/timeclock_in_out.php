<?php
$directory_path_for_ajax = "../";
include($directory_path_for_ajax . "conf/session_start.php");
include($directory_path_for_ajax . "conf/connection.php");
include($directory_path_for_ajax . "conf/functions.php");
$db = new mySqlDB;
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == $project_folder &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {

    $check_module_permission = check_module_permission($db, $conn, '10', $_SESSION["user_id"], $_SESSION["user_type"]);
    if ($check_module_permission == "") {
        header("location: " . $directory_path_for_ajax . "signout");
        exit();
    } else {

        $selected_db_name       = $_SESSION["db_name"];
        $subscriber_users_id    = $_SESSION["subscriber_users_id"];
        $user_id                = $_SESSION["user_id"];
        $module_id              = $_SESSION["module_menue_id"];
        extract($_POST);

        $EntryTimeText = $time;

        $id_field_name = "po_id";
        if ($entry_type == 'repair') {
            $id_field_name = "location_or_bin_id";
        }
        if ($entry_type == 'process') {
            $id_field_name = "location_or_bin_id";
        }
        if (isset($type) && $type == 'start') {
            $sql_c_insert = "INSERT INTO " . $selected_db_name . ".time_clock_detail (subscriber_users_id, user_id, entry_type, " . $id_field_name . ", entryDate, startTime, EntryTimeText, add_date, add_by, add_by_user_id, add_ip, add_timezone, added_from_module_id) 
                             VALUES( '" . $subscriber_users_id . "', '" . $user_id . "', '" . $entry_type . "', '" . $record_id . "', '" . date('Y-m-d') . "', '" . $add_date . "', '" . $EntryTimeText . "', '" . $add_date . "', '" . $_SESSION['username'] . "',  '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "' ) "; //echo $sql_c_up;
            $ok = $db->query($conn, $sql_c_insert);
            if ($ok) {
                $_SESSION['is_start'] = 1;
                if ($entry_type == 'receive') {
                    $_SESSION['startTime'] = $time;
                }
                if ($entry_type == 'diagnostic') {
                    $_SESSION['startTime_Diagnostic'] = $time;
                }
                if ($entry_type == 'repair') {
                    $_SESSION['startTime_Repair'] = $time;
                }
                if ($entry_type == 'process') {
                    $_SESSION['startTime_Process'] = $time;
                }
                $_SESSION[$id_field_name]   = $record_id;
                $_SESSION[$entry_type]      = $entry_type;
            }
        } else if (isset($type) && $type == 'stop') {
            $sql = "SELECT a.* FROM " . $selected_db_name . ".time_clock_detail a
                    WHERE a.enabled                 = 1
                    AND a.subscriber_users_id       = '" . $subscriber_users_id . "'
                    AND a.user_id    				= '" . $_SESSION['user_id'] . "'
                    AND a." . $id_field_name . "    = '" . $record_id . "'
                    AND a.entryDate                 = '" . date('Y-m-d') . "'
                    AND a.entry_type                = '" . $entry_type . "'
                     AND (a.stopTime = NULL OR a.stopTime IS NULL ) 
                    ORDER BY a.id DESC LIMIT 1";
            $result_cl     = $db->query($conn, $sql);
            $count_cl     = $db->counter($result_cl);
            if ($count_cl > 0) {
                $row_cl     = $db->fetch($result_cl);
                $update_id  = $row_cl[0]['id'];

                $sql  = "UPDATE " . $selected_db_name . ".time_clock_detail 
                                            SET entryDate               = '" . date('Y-m-d') . "',
                                                stopTime                = '" . $add_date . "' ,

                                                update_date         = '" . $add_date . "' ,
                                                update_by 	        = '" . $_SESSION['username'] . "' ,
                                                update_by_user_id   = '" . $_SESSION['user_id'] . "' ,
                                                update_ip 	        = '" . $add_ip . "'
                        WHERE id = '" . $update_id . "' 
                        AND subscriber_users_id = '" . $subscriber_users_id . "' ";
                // pause_start_time        = NULL ,
                // pause_start_timeText    = '' ,
                // pause_end_time          = NULL ,
                // pause_end_timeText      = '' ,
            } else {
                $sql   = "INSERT INTO " . $selected_db_name . ".time_clock_detail (subscriber_users_id, user_id, entry_type, " . $id_field_name . ", entryDate, stopTime, EntryTimeText, add_date, add_by,add_by_user_id, add_ip, add_timezone, added_from_module_id) 
                          VALUES( '" . $subscriber_users_id . "', '" . $user_id . "', '" . $entry_type . "', '" . $record_id . "', '" . date('Y-m-d') . "', '" . $add_date . "', '" . $EntryTimeText . "', '" . $add_date . "', '" . $_SESSION['username'] . "',  '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "' ) "; //echo $sql_c_up;
            }
            $ok = $db->query($conn, $sql);
            if ($ok) {
                if ($entry_type == 'receive') {
                    unset($_SESSION['startTime']);
                    unset($_SESSION['is_paused']);
                }
                if ($entry_type == 'diagnostic') {
                    unset($_SESSION['startTime_Diagnostic']);
                    unset($_SESSION['d_is_paused']);
                }
                if ($entry_type == 'process') {
                    unset($_SESSION['startTime_Process']);
                    unset($_SESSION['p_is_paused']);
                }
                if ( $entry_type == 'repair') {
                    unset($_SESSION['startTime_Repair']);
                    unset($_SESSION['r_is_paused']);
                }
                unset($_SESSION[$id_field_name]);
                unset($_SESSION[$entry_type]);
                unset($_SESSION['is_start']);
            }
        } else if (isset($type) && $type == 'pause') {
            $sql = "SELECT a.* FROM " . $selected_db_name . ".time_clock_detail a
                    WHERE a.enabled                 = 1
                    AND a.subscriber_users_id       = '" . $subscriber_users_id . "'
                    AND a.user_id    				= '" . $_SESSION['user_id'] . "'
                    AND a." . $id_field_name . "    = '" . $record_id . "'
                    AND a.entryDate                 = '" . date('Y-m-d') . "'
                    AND a.entry_type                = '" . $entry_type . "'
                     AND (a.stopTime = NULL OR a.stopTime IS NULL ) 
                    ORDER BY a.id DESC LIMIT 1";
            $result_cl     = $db->query($conn, $sql);
            $count_cl     = $db->counter($result_cl);
            if ($count_cl > 0) {
                $row_cl     = $db->fetch($result_cl);
                $update_id  = $row_cl[0]['id'];
                if ($entry_type == 'receive') {
                    $_SESSION['r_pause_start_time'] = $time;
                    $_SESSION['is_paused']          = 1;
                }
                if ($entry_type == 'diagnostic') {
                    $_SESSION['d_pause_start_time'] = $time;
                    $_SESSION['d_is_paused']        = 1;
                }
                if ($entry_type == 'process') {
                    $_SESSION['p_pause_start_time'] = $time;
                    $_SESSION['p_is_paused']        = 1;
                }
                if ($entry_type == 'repair') {
                    $_SESSION['r_pause_start_time'] = $time;
                    $_SESSION['r_is_paused']        = 1;
                }
                $sql  = "UPDATE " . $selected_db_name . ".time_clock_detail SET 
                                                                            pause_start_time        = '" . $add_date . "' ,
                                                                            pause_start_timeText    = '" . $EntryTimeText . "' ,
                                                                            pause_end_time          = NULL ,
                                                                            pause_end_timeText      = '' ,
                                                                            is_paused               = '1' ,

                                                                            update_date             = '" . $add_date . "' ,
                                                                            update_by 	            = '" . $_SESSION['username'] . "' ,
                                                                            update_by_user_id       = '" . $_SESSION['user_id'] . "' ,
                                                                            update_ip 	            = '" . $add_ip . "'
                        WHERE id = '" . $update_id . "' 
                        AND subscriber_users_id = '" . $subscriber_users_id . "' ";
            }
            $db->query($conn, $sql);
        } else if (isset($type) && $type == 'resume') {
            $sql = "SELECT a.* FROM " . $selected_db_name . ".time_clock_detail a
                    WHERE a.enabled                 = 1
                    AND a.subscriber_users_id       = '" . $subscriber_users_id . "'
                    AND a.user_id    				= '" . $_SESSION['user_id'] . "'
                    AND a." . $id_field_name . "    = '" . $record_id . "'
                    AND a.entryDate                 = '" . date('Y-m-d') . "'
                    AND a.entry_type                = '" . $entry_type . "'
                     AND (a.stopTime = NULL OR a.stopTime IS NULL ) 
                    ORDER BY a.id DESC LIMIT 1";
            $result_cl     = $db->query($conn, $sql);
            $count_cl     = $db->counter($result_cl);
            if ($count_cl > 0) {
                $row_cl             = $db->fetch($result_cl);
                $update_id          = $row_cl[0]['id'];
                $pause_start_time   = $row_cl[0]['pause_start_time'];

                $startTimestamp     = strtotime($pause_start_time);
                $endTimestamp       = strtotime($add_date);
                $pause_duration     = ($endTimestamp - $startTimestamp) * 1000;

                if ($entry_type == 'receive') {
                    $_SESSION['r_pause_end_time'] = $time;
                    unset($_SESSION['is_paused']);
                }
                if ($entry_type == 'diagnostic') {
                    $_SESSION['d_pause_start_time'] = $time;
                    unset($_SESSION['d_is_paused']);
                }
                if ($entry_type == 'process') {
                    $_SESSION['p_pause_start_time'] = $time;
                    unset($_SESSION['p_is_paused']);
                }
                if ($entry_type == 'repair') {
                    $_SESSION['r_pause_start_time'] = $time;
                    unset($_SESSION['r_is_paused']);
                }

                $sql  = "UPDATE " . $selected_db_name . ".time_clock_detail SET 
                                                                            pause_end_time          = '" . $add_date . "' ,
                                                                            pause_end_timeText      = '" . $EntryTimeText . "' ,
                                                                            is_paused               = '0' ,
                                                                            pause_duration          = (pause_duration+" . $pause_duration . ") ,

                                                                            update_date             = '" . $add_date . "' ,
                                                                            update_by 	            = '" . $_SESSION['username'] . "' ,
                                                                            update_by_user_id       = '" . $_SESSION['user_id'] . "' ,
                                                                            update_ip 	            = '" . $add_ip . "'
                        WHERE id = '" . $update_id . "' 
                        AND subscriber_users_id = '" . $subscriber_users_id . "' ";
            }
            $db->query($conn, $sql);
        }
        echo "1";
    }
}
