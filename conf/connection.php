<?php
if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$mysql_user 	= "root";
	$mysql_password = "";
	$mysql_database = "albert_warehouse_erp";
} else {
	$mysql_user 	= "ujjhto9uzggi0";
	$mysql_password = "325@abc1#$%f";
	$mysql_database = "dbetunpprdklld";
}
$mysql_hostname = "localhost";
$prefix = "";
$conn = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Could not connect mysql");
mysqli_select_db($conn, $mysql_database) or die("Could not select database");
