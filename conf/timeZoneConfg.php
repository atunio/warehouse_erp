<script language="javascript">
	//var d = new Date();
	timestamp();

	function timestamp() {
		var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
		var callurl = 'http://<?php echo HTTP_HOST; ?>/setTimezoneSession.php?timezone=' + timezone;
		$.ajax({
			url: callurl,
			success: function(data) {},
		});
	}
</script>
<?php
//echo $_SESSION['timezone'];
// set current local system timezone
if (isset($_SESSION['timezone'])) {
	$timezone = $_SESSION['timezone'];
	if ($timezone == 'undefined' || $timezone == 'PST8PDT' || $timezone == 'UTC' || $timezone == '') {
		$timezone = "Asia/Shanghai";
		date_default_timezone_set($timezone);
	} else {
		date_default_timezone_set($timezone);
	}
} else {
	$timezone = "Asia/Shanghai";
	date_default_timezone_set($timezone);
}
$add_date = date("Y-m-d H:i:s");
?>