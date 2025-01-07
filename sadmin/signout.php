<?php
include('../conf/session_start.php');
include("../conf/connection.php");
include("../conf/functions.php");
$db 	 = new mySqlDB;
$user_id = 0;
if (isset($_SESSION['user_id_super_admin'])) $user_id = $_SESSION['user_id_super_admin'];
if (isset($user_id) && $user_id > 0) {
	$log_history = "INSERT INTO user_login_logout_history (user_type, user_id, entry_type, add_date, add_ip)
					VALUES('" . $_SESSION['user_type_super_admin'] . "', '" . $user_id . "', 'Super Admin Logout', '" . $add_date . "', '" . $add_ip . "')";
	$db->query($conn, $log_history);
	$log_2 = "UPDATE super_admin SET sec_users = 0 WHERE id = '" . $user_id . "'";
	$db->query($conn, $log_2);
}
session_unset();
session_destroy();
header("location: signin");
exit();
