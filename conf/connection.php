<?php
define("HTTP_HOST_IP", "localhost");
if ($_SERVER['HTTP_HOST'] == HTTP_HOST_IP) {

	$mysql_database = "albert_warehouse_erp";
	$mysql_hostname = HTTP_HOST_IP;

	$mysql_user 	= "ipuser";
	$mysql_password = "ipuserAMIZ@1#";

	if (HTTP_HOST_IP == 'localhost') {
		$mysql_user 	= "root";
		$mysql_password = "";
	}
} else {
	$mysql_user 	= "ujjhto9uzggi0";
	$mysql_password = "325@abc1#$%f";
	$mysql_database = "dbetunpprdklld";
	$mysql_hostname = "localhost";
}
$prefix = "";
$conn = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Could not connect mysql");
mysqli_select_db($conn, $mysql_database) or die("Could not select database");
