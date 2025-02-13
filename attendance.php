<?php
$test_email 	= 0;
$module_js 		= "";
$dashboard_section_classes 	= "card gradient-shadow gradient-45deg-red-pink border-radius-3 animate fadeUp";
include("conf/session_start.php");
include('path.php');
include($directory_path . "conf/connection.php");
include($directory_path . "conf/functions.php");
$db 	= new mySqlDB;
$pageTitle = PROJECT_TITLE . ". Panel Dashboard"; ?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $directory_path; ?>app-assets/images/favicon/favicon-32x32.png">

	<!-- Comment if there is no internet -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!-- BEGIN: VENDOR CSS-->
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/vendors.min.css">
	<link rel="stylesheet" href="<?php echo $directory_path; ?>app-assets/vendors/select2/select2.min.css" type="text/css">
	<link rel="stylesheet" href="<?php echo $directory_path; ?>app-assets/vendors/select2/select2-materialize.css" type="text/css">

	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/data-tables/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/data-tables/css/select.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/animate-css/animate.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/chartist-js/chartist.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/chartist-js/chartist-plugin-tooltip.css">
	<!-- END: VENDOR CSS-->
	<!-- BEGIN: Page Level CSS-->
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/themes/vertical-modern-menu-template/materialize.css">

	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/flag-icon/css/flag-icon.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/quill/quill.snow.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/dropify/css/dropify.min.css">

	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/themes/vertical-modern-menu-template/materialize.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/page-users.min.css">

	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/themes/vertical-modern-menu-template/style.css">
	<?php
	$menu_horizontal = 1;
	if ($menu_horizontal == 1) { ?>
		<link rel="stylesheet" type="text/css" href="app-assets/css/themes/horizontal-menu-template/materialize.css">
		<link rel="stylesheet" type="text/css" href="app-assets/css/themes/horizontal-menu-template/style.css">
		<link rel="stylesheet" type="text/css" href="app-assets/css/layouts/style-horizontal.css">
	<?php } ?>

	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/data-tables.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/dashboard-modern.css">
	<link rel="stylesheet" type="text/css" href="app-assets/css/pages/dashboard.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/intro.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/form-select2.min.css">

	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/app-sidebar.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/app-todo.css">

	<!-- END: Page Level CSS-->

	<!-- BEGIN: Custom CSS-->
	<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/custom/custom.css">
	<!-- END: Page Level CSS-->


	<!-- Comment if there is no internet -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<?php
	// This is if sidebar show default for all pages ////////////
	/////////////////////////////////////////////////////////////
	$nav_layout = "sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square";
	$page_width = "";
	$nav_check 	= "radio_button_checked";
	$top_nav_layout = "navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow";
	/////////////////////////////////////////////////////////////

	// module page
 
 	//sidebar-collapse    this is css class to hide side bar
	?>
	<title><?php echo $pageTitle; ?> | <?php echo PROJECT_TITLE2; ?></title>
	<?php $menu_horizontal = 1; ?>
</head>
<!-- END: Head-->
<body class="horizontal-layout page-header-light horizontal-menu preload-transitions 2-columns"
	data-open="click" data-menu="horizontal-menu" data-col="2-columns">
	<?php
	include('sub_files/header_top_general.php'); ?>
	<style>
		/* Centering the entire container */
		.container {
			display: flex;
			flex-direction: column; /* Align items vertically */
			justify-content: center; /* Vertically center the content */
			align-items: center; /* Horizontally center the content */
			text-align: center;
		}

		/* Styling for the password container */
		.password-container {
			display: flex;
			justify-content: center; /* Horizontally center circles */
			align-items: center; /* Vertically center circles */
			margin-bottom: 20px; /* Add some space between the password and keyboard */
		}

		/* Circle styling */
		.circle {
			width: 30px;
			height: 30px;
			border-radius: 50%;
			background-color: #ddd;
			margin: 2px;
			transition: background-color 0.3s;
		}

		/* Styling for the keyboard */
		.keyboard {
			display: grid;
			grid-gap: 0px;
			margin-top: 5px;
			margin-bottom: 5px;
			justify-items: center; /* Horizontally center the keys */
		}

		/* Styling for each key */
		.key {
			width: 200px;
			height: 200px;
			font-size: 22px;
			background-color:rgb(202, 207, 202);
			border: none;
			color: rgb(14, 15, 14);
			cursor: pointer;
			border-radius: 4px;
			margin-bottom: 2px;
			transition: background-color 0.3s;
		}
		.timestamp{
			width: 130px;
			height: 50px;
			border-radius: 3px;
			border: none;
			background-color: #ddd;
			margin-right: 70px;
			transition: background-color 0.3s;
		}
		.key:hover {
			background-color: #45a049;
		}

		/* Styling for Clear and Back buttons */
		.clear {
			background-color: #FF5722;
			border: none;
			color: white;
			cursor: pointer;
			border-radius: 4px;
			margin-bottom: 2px;
			margin-top: 0px;
			transition: background-color 0.3s;
		}

		.back {
			background-color: #FFC107;
			border: none;
			color: white;
			cursor: pointer;
			border-radius: 4px;
			margin-bottom: 2px;
			margin-top: 0px;
			transition: background-color 0.3s;
		}

		.clear:hover {
			background-color: #e64a19;
		}

		.back:hover {
			background-color: #ffb300;
		}

		/* Adjusting the layout for the last row */
		.row1 button {
			width: 240px;
			height: 125px;
		}
	</style>
	
	<div id="main" class="<?php echo $page_width; ?>">
		<div class="row">
			<div class="col s12 m12 l12">
				<div class="section section-data-tables">
					<div class="card custom_margin_card_table_top custom_margin_card_table_bottom">
						<div class="card-content custom_padding_card_content_table_top_bottom">
							<div class="row">
								<div class="input-field col m12 s12" style="margin-top: 3px; margin-bottom: 3px;">
									<h6 class="media-heading" style="text-align:center;font-weight:bold;font-size:22px;">
										Enter Your Pin For Clock IN / Clock Out 
									</h6>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col s12 m12 l12">
				<div id="Form-advance" class="card card card-default scrollspy custom_margin_card_table_top custom_margin_card_table_bottom">
					<div class="card-content custom_padding_card_content_table_top">
						<!-- <form method="post" autocomplete="off"> -->
							<input type="hidden" name="is_Submit" value="Y" />
								<div style="margin-left: 174px; position: absolute; top: 455px;">
									<?php
										// First date: "12 Feb 2025"
										$firstDate = date("d M Y"); 

										// Initial time: current time (in 12-hour format)
										$currentTime = date("ga");  // e.g., "3pm"
										
										echo "<strong>" . $firstDate . "<br><span id='time' style='font-size:36px;'>" . $currentTime . "</span></strong>";
									?>
								</div>
								<div class="container" >
									<div class="password-container" >
										<button class="timestamp" name="timestamp" value="timein">Time in</button>
										<div class="circle" id="circle-1"></div>
										<div class="circle" id="circle-2"></div>
										<div class="circle" id="circle-3"></div>
										<div class="circle" id="circle-4"></div>
										<div class="circle" id="circle-5"></div>
										<div class="circle" id="circle-6"></div>
									</div>

									<div class="keyboard">
										<div class="row1">
											<button class="key" data-key="1">1</button>
											<button class="key" data-key="2">2</button>
											<button class="key" data-key="3">3</button>
										</div>
										<div class="row1">
											<button class="key" data-key="4">4</button>
											<button class="key" data-key="5">5</button>
											<button class="key" data-key="6">6</button>
										</div>
										<div class="row1">
											<button class="key" data-key="7">7</button>
											<button class="key" data-key="8">8</button>
											<button class="key" data-key="9">9</button>
										</div>
										<div class="row1">
											<button class="clear">Clear</button>
											<button class="key" data-key="0">0</button>
											<button class="back">Back</button>
											
										</div>
									</div>
								</div>
							<!--  -->
						<!-- </form> -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END: Page Main-->
	
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			let currentIndex = 1;
			let pressedKeys = [];  
			$(".key").on("click", function() {
				var key = $(this).data("key");  
				var circle = $("#circle-" + currentIndex);  
				circle.css("background-color", "grey");
				pressedKeys.push(key);
				let arrayString = pressedKeys.join('');
				var time_flag = $(".timestamp").val();
				var module_id = $("#module_id").val();
				if (pressedKeys.length == 6) {

					var dataString = 'type=attendance&emp_pin_code=' + arrayString + '&time_flag=' + time_flag + '&module_id=' + module_id;
					
					$.ajax({
						type: "POST",
						url: "ajax/attendance_in_out.php",
						data: dataString,
						cache: false,
						success: function(response) {
							if (response) {
								$(".circle").css("background-color", "#ddd");
								pressedKeys = [];
								currentIndex = 1;
								 
								if (response === "Invalid") {
									var toastHTML = 'Invalid Pin code entered kindly enter the correct pin code.';
									showToast(toastHTML, "Fail");
								}else if(response === "FailClockIn"){
									var toastHTML = 'Please Clock in before then you may clock out.';
									showToast(toastHTML, "Fail");
								} 
								else if(response === "FailClockOut"){
									var toastHTML = 'Please Clock out before then you may clock in again.';
									showToast(toastHTML, "Fail");
								} 
								else if(response === "ClockOut"){
									var toastHTML = 'Already you have done clock out.';
									showToast(toastHTML, "Fail");
								} 
								else if(response === "ClockIn"){
									var toastHTML = 'Already you have done clock in kindly clock out.';
									showToast(toastHTML, "Fail");
								} 
								else if(response === "ClockInOut"){
									var toastHTML = 'Already you have done today clock in & clock out.';
									showToast(toastHTML, "Fail");
								} 
								else{
									var toastHTML = response;
									showToast(toastHTML, "Success");
								}
								
							}
						},
						error: function() {
							
						}
					});
				}
				if (currentIndex > 6) currentIndex = 6;
				currentIndex++;
				
			});

			$(".clear").on("click", function() {
				$(".circle").css("background-color", "#ddd");
				pressedKeys = [];
				currentIndex = 1;
			});

			$(".back").on("click", function() {
				if (pressedKeys.length > 0) {
					pressedKeys.pop();
					currentIndex--;  
					var circle = $("#circle-" + currentIndex);
					circle.css("background-color", "#ddd");
					console.log(pressedKeys);
				}
			});
			$(".timestamp").on("click", function() {
				var val = $(this).val();
				if(val == 'timein'){
					$(".timestamp").val('timeout');
					$(".timestamp").text('Time Out');
				}else{
					$(".timestamp").val('timein');
					$(".timestamp").text('Time In');
				}
			});
		});

		function updateClock() {
			var now = new Date();
			var hours = now.getHours();
			var minutes = now.getMinutes();
			var seconds = now.getSeconds();
			var ampm = hours >= 12 ? 'pm' : 'am';
			hours = hours % 12;
			hours = hours ? hours : 12; 
			minutes = minutes < 10 ? '0' + minutes : minutes; 
			seconds = seconds < 10 ? '0' + seconds : seconds; 
			var timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
			document.getElementById('time').textContent = timeString;
		}
		setInterval(updateClock, 1000);
		updateClock();
		function showToast(message, type) {
			var toastClass = type === 'Success' ? 'green' : 'red';
			M.toast({
				html: message,
				classes: toastClass
			});
		} 
	</script>
		<!-- END: Page Main-->
		<!-- Theme Customizer -->

		<!-- BEGIN: Footer-->
		<?php include("sub_files/footer.php"); ?>
		<!-- END: Footer-->
		<!-- BEGIN VENDOR JS-->
		<script src="<?php echo $directory_path; ?>app-assets/js/vendors.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/vendors/sortable/jquery-sortable-min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/vendors/quill/quill.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/vendors/select2/select2.full.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/vendors/chartjs/chart.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/vendors/chartist-js/chartist.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/vendors/chartist-js/chartist-plugin-tooltip.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/vendors/chartist-js/chartist-plugin-fill-donut.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/vendors/data-tables/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/vendors/data-tables/js/dataTables.select.min.js"></script>

		<!-- BEGIN THEME  JS-->
		<script src="<?php echo $directory_path; ?>app-assets/js/plugins.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/js/search.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/js/custom/custom-script.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/js/scripts/customizer.js"></script>
		<!-- END THEME  JS-->
		<!-- BEGIN PAGE LEVEL JS-->
		<script src="<?php echo $directory_path; ?>app-assets/js/scripts/dashboard-modern.js"></script>
		<script src="app-assets/js/scripts/dashboard-ecommerce.js"></script>
		<!-- END PAGE LEVEL JS-->
		<!-- Alert -->
		<script src="<?php echo $directory_path; ?>app-assets/js/scripts/ui-alerts.min.js"></script>
		<!-- BEGIN PAGE LEVEL JS-->
		<script src="<?php echo $directory_path; ?>app-assets/js/scripts/page-users.min.js"></script>
		<!-- BEGIN PAGE LEVEL JS-->
		<script src="<?php echo $directory_path; ?>app-assets/js/scripts/data-tables.js"></script>
		<!-- END PAGE LEVEL JS-->

		<script src="<?php echo $directory_path; ?>app-assets/vendors/dropify/js/dropify.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/js/scripts/form-file-uploads.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/js/scripts/form-select2.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/js/scripts/advance-ui-modals.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/ckeditor/ckeditor.js"></script>
		<!-- END PAGE LEVEL JS-->
		<script src="<?php echo $directory_path; ?>app-assets/js/scripts/app-todo.js"></script>
</body>
</html>
<?php
mysqli_close($conn);
 
include("sub_files/index2_js.php");
if (file_exists($module_js)) { ?>
	<script src="<?php echo $directory_path . $module_js; ?>"></script>
<?php } ?>