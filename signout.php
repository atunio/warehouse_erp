<?php
include("conf/session_start.php");
include('path.php');
include($directory_path . "conf/connection.php");
include($directory_path . "conf/functions.php");
$db 	 = new mySqlDB;
$user_id 	= 0;
if (isset($_SESSION['user_id'])) $user_id = $_SESSION['user_id'];
if (isset($user_id) && $user_id > 0) {
	$log_history = "INSERT INTO user_login_logout_history (user_id, user_type, entry_type, add_date, add_ip)
					VALUES('" . $user_id . "', '" . $_SESSION['user_type'] . "', 'User Logout', '" . $add_date . "', '" . $add_ip . "')";
	$db->query($conn, $log_history);
	$log_2 			= "UPDATE users SET sec_users = 0 WHERE id = '" . $user_id . "'";
	$db->query($conn, $log_2);
}
session_unset();
session_destroy();
echo redirect_to_page("signin");
exit();
