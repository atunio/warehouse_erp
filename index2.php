<?php
$test_email 	= 0;
$module_js 		= "";
$dashboard_section_classes 	= "card gradient-shadow gradient-45deg-red-pink border-radius-3 animate fadeUp";
include("conf/session_start.php");
include('path.php');
include($directory_path . "conf/connection.php");
include($directory_path . "conf/functions.php");
$db 	= new mySqlDB;
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == $project_folder &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {
	if (!isset($_SESSION['token'])) {
		athenticate_phonecheck();
	}
} else {
	echo redirect_to_page("signin");
	exit();
}
$module = "";
$sql_d 			= "	SELECT a.*, b.profile_pic, b.first_name, b.last_name, b.user_access_token
					FROM subscribers_users a
					INNER JOIN users b ON b.subscriber_users_id = a.id
					WHERE b.id = '" . $_SESSION["user_id"] . "' ";
//echo $sql_d; die;
$result_d 		= $db->query($conn, $sql_d);
$count_d		= $db->counter($result_d);

if ($count_d == 0) {
	echo redirect_to_page("signout");
	exit();
} else {
	$row_d							= $db->fetch($result_d);
	$company_name_disp				= $row_d[0]['company_name'];
	$company_logo_disp				= $row_d[0]['company_logo'];
	$user_first_name				= $row_d[0]['first_name'];
	$user_last_name					= $row_d[0]['last_name'];
	$user_full_name					= $row_d[0]['first_name'] . " " . $row_d[0]['last_name'];
	$user_access_token				= $row_d[0]['user_access_token'];

	$_SESSION["user_first_name"] 	= $user_first_name;
	$_SESSION["user_last_name"] 	= $user_last_name;
	$_SESSION["user_full_name"] 	= $user_full_name;

	if ($company_logo_disp == "") {
		$company_logo_disp = "no_image.png";
	}
	$user_profile_pic				= $row_d[0]['profile_pic'];
	if ($user_profile_pic == "") {
		$user_profile_pic = "no_image.png";
	}
}
if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$selected_db_name 			= $selected_for_test_on_local;
}
$_SESSION["db_name"] 	= $selected_db_name;
$company_name_array		= explode(" ", $company_name_disp);
extract($_REQUEST);
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == $project_folder &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {

	$FirstCharacter_FirstName = $FirstCharacter_FirstName = "";
	if ($user_first_name != "") {
		$FirstCharacter_FirstName = substr($user_first_name, 0, 1);
	}
	if ($user_last_name != "") {
		$FirstCharacter_LastName = substr($user_last_name, 0, 1);
	}

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
		$parm1 = "";
		$parm2 = "";
		$parm3 = "";
		check_session_exist4($db, $conn, $_SESSION["user_id"], $_SESSION["username"], $_SESSION["user_type"], $_SESSION["db_name"], $parm2, $parm3);
		/*
		$nav_layout 	= "sidenav-main nav-collapsible sidenav-light sidenav-active-square nav-collapsed";
		$top_nav_layout = "navbar-main navbar-color nav-collapsible navbar-dark gradient-45deg-indigo-purple no-shadow nav-collapsed";
		$nav_check 		= "radio_button_unchecked";
		$page_width 	= "main-full";
		*/

		// This is if sidebar show default for all pages ////////////
		/////////////////////////////////////////////////////////////
		$nav_layout = "sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square";
		$page_width = "";
		$nav_check 	= "radio_button_checked";
		$top_nav_layout = "navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow";
		/////////////////////////////////////////////////////////////

		// module page
		if (isset($string)) {
			//die;
			$parm 				= "?string=" . $string;
			$string 			= decrypt($string);
			$string_explode 	= explode('&', $string);

			$module 			= "";
			$page 				= "";
			$detail_id 			= "";
			$editmaster 		= "";
			$action 			= "";
			foreach ($string_explode as $value) {
				$string_data_explode = explode('=', $value);
				if ($string_data_explode[0] == 'module') {
					$module 			= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'module_id') {
					$module_id = $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'module_folder') {
					$module_folder 			= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'page') {
					$page 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd') {
					$cmd 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd1_2') {
					$cmd1_2 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd1_3') {
					$cmd1_3 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd2') {
					$cmd2 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd2_1') {
					$cmd2_1				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd2_2') {
					$cmd2_2 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd3') {
					$cmd3 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd4') {
					$cmd4 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd5') {
					$cmd5 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd6') {
					$cmd6 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd7') {
					$cmd7 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd8') {
					$cmd8 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd9') {
					$cmd9 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd10') {
					$cmd10 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd11') {
					$cmd11 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd12') {
					$cmd12 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd_detail') {
					$cmd_detail 		= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'id') {
					$id 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'detail_id') {
					$detail_id 			= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'detail_id2') {
					$detail_id2 			= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'class_section_id') {
					$class_section_id 	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'editmaster') {
					$editmaster 		= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'marks_id') {
					$marks_id 			= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'subject_id') {
					$subject_id 		= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'student_profile_id') {
					$student_profile_id = $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'prev_date') {
					$prev_date 			= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'active_tab') {
					$active_tab 		= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'msg_main') {
					$msg_main 			= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'msg_success') {
					$msg['msg_success'] = $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'error_msg') {
					$error['msg'] = $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'error_msg2') {
					$error2['msg'] = $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'year_month') {
					$year_month	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'year') {
					$year	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'action') {
					$action	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'filter_1') {
					$filter_1	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'filter_2') {
					$filter_2	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'filter_3') {
					$filter_3	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'is_Submit') {
					$is_Submit	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'sub_location_id') {
					$sub_location_id	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'product_category') {
					$product_category	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'logistic_id') {
					$logistic_id	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'assignment_id') {
					$assignment_id	= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'active_subtab') {
					$active_subtab	= $string_data_explode[1];
				}
				 
			}
			$sql_md 		= "SELECT * FROM menus WHERE id = '" . $module_id . "' ORDER BY id DESC LIMIT 1 ";
			$result_md 		= $db->query($conn, $sql_md);
			$count_md		= $db->counter($result_md);
			if ($count_md > 0) {
				$module_folder 					= "";
				$row_md							= $db->fetch($result_md);
				$layout_type					= $row_md[0]["layout_type"];
				$module_folder					= $row_md[0]["module_folder"];
				$menu_id						= $row_md[0]["id"];
				$main_menu_name					= $row_md[0]["menu_name"];
				$module							= $row_md[0]["folder_name"];
				$_SESSION["module_menue_id"] 	= $menu_id;
				$_SESSION["db"]            		= $db;
				$_SESSION["conn"]           	= $conn;
				$_SESSION["module_folder"] 		= $module_folder;
				if ($module_folder != "") {
					$module_folder_directory = $module_folder . "/";
				} else {
					$module_folder_directory = "";
				}
				if ($layout_type == 'nav-collapsible') {
					$nav_layout = "sidenav-main nav-collapsible sidenav-light sidenav-active-square nav-collapsed";
					$page_width = "main-full";
					$nav_check 	= "radio_button_unchecked";
					$top_nav_layout = "navbar-main navbar-color nav-collapsible navbar-dark gradient-45deg-indigo-purple no-shadow nav-collapsed";
				} else {
					$nav_layout = "sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square";
					$page_width = "";
					$nav_check 	= "radio_button_checked";
					$top_nav_layout = "navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow";
				}
			} else {
				$nav_layout = "sidenav-main nav-collapsible sidenav-light sidenav-active-square nav-collapsed";
				$page_width = "main-full";
				$nav_check 	= "radio_button_unchecked";
				$top_nav_layout = "navbar-main navbar-color nav-collapsible navbar-dark gradient-45deg-indigo-purple no-shadow nav-collapsed";
			}
			if (isset($page) && $page == 'listing' && ($module_id == '10')) {
				///*
				$nav_layout = "sidenav-main nav-collapsible sidenav-light sidenav-active-square nav-collapsed";
				$page_width = "main-full";
				$nav_check 	= "radio_button_unchecked";
				$top_nav_layout = "navbar-main navbar-color nav-collapsible navbar-dark gradient-45deg-indigo-purple no-shadow nav-collapsed";
				//*/
			}
			$sub_page 	= "components/" . $module_folder . "/" . $module . "/" . $module . "-" . $page . ".php";
			$module_js 	= "components/" . $module_folder . "/" . $module . "/" . $module . ".js";
			$check_module_permission = "";
			$check_module_permission = check_module_permission($db, $conn, $module_id, $_SESSION["user_id"], $_SESSION["user_type"]);
			$pageTitle 	= $check_module_permission;
			if ($check_module_permission == "") {
				echo redirect_to_page("signout");
			}
		} else {
			$parm = "?url=";
			$sub_page = 'components/main_content.php';
		}
		$allow_password_change	= 1;
		//sidebar-collapse    this is css class to hide side bar
		?>
		<?php

		if (isset($module_id) && ($module_id == 46 || $module_id == 10 || $module_id == 34 || $module_id == 62)) { ?>
		<?php
		} else { ?>
			<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/page-account-settings.min.css">
		<?php } ?>
		<title><?php echo $pageTitle; ?> | <?php echo PROJECT_TITLE2; ?></title>
		<?php $menu_horizontal = 1; ?>

		<!-- Custom data tables Export Buttons -->
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/customdatatable-buttons.css">
		
		<!-- Custom data tables ends -->
 <!-- Custom CSS for Gradient Buttons -->
 	<style>
        /* General button styling */
        .dt-button {
            color: white !important; /* Text color */
            border-radius: 5px !important; /* Rounded corners */
            padding: 3px 8px !important; /* Padding */
            margin: 2px !important; /* Margin between buttons */
            border: none !important; /* Remove border */
            font-weight: bold !important; /* Bold text */
            background-size: 150% auto !important; /* Gradient animation */
            transition: 0.5s !important; /* Smooth transition */
        }

        /* Gradient colors for each button */
        .dt-button.buttons-copy {
            background-image: linear-gradient(45deg,rgb(163, 166, 163),rgb(218, 222, 218)) !important; /* Green gradient */
        }

        .dt-button.buttons-csv {
            background-image: linear-gradient(45deg, #2196F3, #64B5F6) !important; /* Blue gradient */
        }

        .dt-button.buttons-excel {
            background-image: linear-gradient(45deg, #4CAF50, #81C784) !important; /* Green gradient */
        }

        .dt-button.buttons-pdf {
            background-image: linear-gradient(45deg, #F44336, #E57373) !important; /* Red gradient */
        }

        .dt-button.buttons-print {
            background-image: linear-gradient(45deg, #9C27B0, #BA68C8) !important; /* Purple gradient */
        }

        /* Hover effects */
        .dt-button:hover {
            background-position: right center !important; /* Gradient animation on hover */
        }
    </style>
	</head>
	<!-- END: Head-->
	<body class="<?php
					if ($menu_horizontal == 1) {
						echo "horizontal-layout page-header-light horizontal-menu preload-transitions 2-columns";
					} else {
						echo "vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns";
					} ?>"
		data-open="click" data-menu="<?php if ($menu_horizontal == 1) {
											echo "horizontal-menu";
										} else {
											echo "vertical-modern-menu";
										} ?>" data-col="2-columns">
		<?php
		if ($menu_horizontal == 1) {
			include('sub_files/header_top_menu.php'); ?>
			<!-- END: Header-->
			<!-- BEGIN: SideNav-->
			<?php include('sub_files/sidebar_top_menu.php');  ?>
			<!-- END: SideNav-->
			<!-- BEGIN: Page Main-->
		<?php
		} else {
			include('sub_files/header.php'); ?>
			<!-- END: Header-->
			<!-- BEGIN: SideNav-->
			<?php include('sub_files/sidebar.php');  ?>
			<!-- END: SideNav-->
			<!-- BEGIN: Page Main-->
		<?php
		}
		include($sub_page);
		?>
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
		
		<?php
		//if (isset($module_id) && $module_id == '18') {?>
			<!-- Custom Datatables JS Export Buttons  -->
			<script src="<?php echo $directory_path; ?>app-assets/vendors/data-tables/js/dataTables.buttons.min.js"></script>
			<script src="<?php echo $directory_path; ?>app-assets/vendors/data-tables/js/jszip.min.js"></script>
			<script src="<?php echo $directory_path; ?>app-assets/vendors/data-tables/js/pdfmake.min.js"></script>
			<script src="<?php echo $directory_path; ?>app-assets/vendors/data-tables/js/vfs_fonts.js"></script>
			<script src="<?php echo $directory_path; ?>app-assets/vendors/data-tables/js/buttons.html5.min.js"></script>
			<script src="<?php echo $directory_path; ?>app-assets/vendors/data-tables/js/buttons.print.min.js"></script>
			<!-- Custom Datatables JS Ends-->
		 <?php //}?>

	
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

		<?php
		/*
		if (isset($module_id) && ($module_id == '10' || $module_id == '31' || $module_id == '37'  || $module_id == '41') && isset($id)) {
			$sql             = "SELECT a.* FROM " . $selected_db_name . ".time_clock_detail a
								WHERE a.enabled             = 1
								AND a.subscriber_users_id	= '" . $subscriber_users_id . "'
								AND a.user_id       		= '" . $_SESSION['user_id'] . "'
								AND a.po_id       			= '" . $id . "'
                    			AND a.entry_type            = 'diagnostic' 
								ORDER BY a.id DESC LIMIT 1";
			$result_cl		= $db->query($conn, $sql);
			$count_cl     	= $db->counter($result_cl);
			if ($count_cl > 0) {
				$row_cl1        = $db->fetch($result_cl);
				if ($row_cl1[0]['entryDate'] == date('Y-m-d')) {
					$stopTime = $row_cl1[0]['stopTime'];
					if ($stopTime == NULL || $stopTime == '' || $stopTime == '0000-00-00 00:00:00') {
						$_SESSION['is_start']	= 1;
						$_SESSION['startTime_Diagnostic']	= $row_cl1[0]['EntryTimeText'];
						$_SESSION['po_id']					= $row_cl1[0]['po_id'];
						$_SESSION['entry_type']				= $row_cl1[0]['entry_type'];
						$_SESSION['diagnostic']				= $row_cl1[0]['entry_type'];
						$_SESSION['d_pause_start_time']		= $row_cl1[0]['pause_start_timeText'];
						$_SESSION['d_pause_end_time']		= $row_cl1[0]['pause_end_timeText'];
						$_SESSION['d_pause_duration']		= $row_cl1[0]['pause_duration'];
						$_SESSION['d_is_paused']			= $row_cl1[0]['is_paused'];
					}
				} else {
					unset($_SESSION['startTime_Diagnostic']);
					unset($_SESSION['is_start']);
					unset($_SESSION['po_id']);
					unset($_SESSION['diagnostic']);
					unset($_SESSION['d_pause_start_time']);
					unset($_SESSION['d_pause_end_time']);
					unset($_SESSION['d_pause_duration']);
					unset($_SESSION['d_is_paused']);
				}
			}

			$sql             = "SELECT a.* FROM " . $selected_db_name . ".time_clock_detail a
								WHERE a.enabled             = 1
								AND a.subscriber_users_id	= '" . $subscriber_users_id . "'
								AND a.user_id       		= '" . $_SESSION['user_id'] . "'
								AND a.po_id       			= '" . $id . "'
                    			AND a.entry_type            = 'receive' 
								ORDER BY a.id DESC LIMIT 1";
			$result_cl		= $db->query($conn, $sql);
			$count_cl     	= $db->counter($result_cl);
			if ($count_cl > 0) {
				$row_cl1        = $db->fetch($result_cl);
				if ($row_cl1[0]['entryDate'] == date('Y-m-d')) {
					$stopTime	= $row_cl1[0]['stopTime'];
					// $is_paused 	= $row_cl1[0]['is_paused'];
					$is_receive_paused 		= 1;
					if ($stopTime == NULL || $stopTime == '' || $stopTime == '0000-00-00 00:00:00') {
						$_SESSION['is_start']			= 1;
						$_SESSION['startTime']			= $row_cl1[0]['EntryTimeText'];
						$_SESSION['po_id']				= $row_cl1[0]['po_id'];
						$_SESSION['entry_type']			= $row_cl1[0]['entry_type'];
						$_SESSION['receive']			= $row_cl1[0]['entry_type'];
						$_SESSION['r_pause_start_time']	= $row_cl1[0]['pause_start_timeText'];
						$_SESSION['r_pause_end_time']	= $row_cl1[0]['pause_end_timeText'];
						$_SESSION['r_pause_duration']	= $row_cl1[0]['pause_duration'];
						$_SESSION['is_paused']			= $row_cl1[0]['is_paused'];
					}
				} else {
					unset($_SESSION['startTime']);
					unset($_SESSION['is_start']);
					unset($_SESSION['po_id']);
					unset($_SESSION['receive']);
					unset($_SESSION['pause_start_time']);
					unset($_SESSION['pause_end_time']);
					unset($_SESSION['pause_duration']);
					unset($_SESSION['is_paused']);
				}
			} else {
				unset($_SESSION['startTime']);
				unset($_SESSION['is_start']);
				unset($_SESSION['po_id']);
				unset($_SESSION['receive']);
				unset($_SESSION['pause_start_time']);
				unset($_SESSION['pause_end_time']);
				unset($_SESSION['pause_duration']);
				unset($_SESSION['is_paused']);
			}

			$sql             = "SELECT a.* FROM " . $selected_db_name . ".time_clock_detail a
								WHERE a.enabled             = 1
								AND a.subscriber_users_id	= '" . $subscriber_users_id . "'
								AND a.user_id       		= '" . $_SESSION['user_id'] . "'
								AND a.location_or_bin_id	= '" . $id . "'
                    			AND a.entry_type            = 'process' 
								ORDER BY a.id DESC LIMIT 1";
			$result_cl		= $db->query($conn, $sql);
			$count_cl     	= $db->counter($result_cl);
			if ($count_cl > 0) {
				$row_cl1        = $db->fetch($result_cl);
				if ($row_cl1[0]['entryDate'] == date('Y-m-d')) {
					$stopTime = $row_cl1[0]['stopTime'];
					if ($stopTime == NULL || $stopTime == '' || $stopTime == '0000-00-00 00:00:00') {
						$_SESSION['is_start']	= 1;
						$_SESSION['startTime_Process']		= $row_cl1[0]['EntryTimeText'];
						$_SESSION['po_id']					= $row_cl1[0]['po_id'];
						$_SESSION['entry_type']				= $row_cl1[0]['entry_type'];
						$_SESSION['process']				= $row_cl1[0]['entry_type'];
						$_SESSION['p_pause_start_time']		= $row_cl1[0]['pause_start_timeText'];
						$_SESSION['p_pause_end_time']		= $row_cl1[0]['pause_end_timeText'];
						$_SESSION['p_pause_duration']		= $row_cl1[0]['pause_duration'];
						$_SESSION['p_is_paused']			= $row_cl1[0]['is_paused'];
					}
				} else {
					unset($_SESSION['startTime_Process']);
					unset($_SESSION['is_start']);
					unset($_SESSION['location_or_bin_id']);
					unset($_SESSION['process']);
					unset($_SESSION['p_pause_start_time']);
					unset($_SESSION['p_pause_end_time']);
					unset($_SESSION['p_pause_duration']);
					unset($_SESSION['p_is_paused']);
				}
			}

			$sql             = "SELECT a.* FROM " . $selected_db_name . ".time_clock_detail a
								WHERE a.enabled             = 1
								AND a.subscriber_users_id	= '" . $subscriber_users_id . "'
								AND a.user_id       		= '" . $_SESSION['user_id'] . "'
								AND a.location_or_bin_id	= '" . $id . "'
                    			AND a.entry_type            = 'repair' 
								ORDER BY a.id DESC LIMIT 1";
			$result_cl		= $db->query($conn, $sql);
			$count_cl     	= $db->counter($result_cl);
			if ($count_cl > 0) {
				$row_cl1        = $db->fetch($result_cl);
				if ($row_cl1[0]['entryDate'] == date('Y-m-d')) {
					$stopTime = $row_cl1[0]['stopTime'];
					if ($stopTime == NULL || $stopTime == '' || $stopTime == '0000-00-00 00:00:00') {
						$_SESSION['is_start']	= 1;
						$_SESSION['startTime_Repair']		= $row_cl1[0]['EntryTimeText'];
						$_SESSION['po_id']					= $row_cl1[0]['po_id'];
						$_SESSION['entry_type']				= $row_cl1[0]['entry_type'];
						$_SESSION['repair']				= $row_cl1[0]['entry_type'];
						$_SESSION['r_pause_start_time']		= $row_cl1[0]['pause_start_timeText'];
						$_SESSION['r_pause_end_time']		= $row_cl1[0]['pause_end_timeText'];
						$_SESSION['r_pause_duration']		= $row_cl1[0]['pause_duration'];
						$_SESSION['r_is_paused']			= $row_cl1[0]['is_paused'];
					}
				} else {
					unset($_SESSION['startTime_Repair']);
					unset($_SESSION['is_start']);
					unset($_SESSION['location_or_bin_id']);
					unset($_SESSION['repair']);
					unset($_SESSION['r_pause_start_time']);
					unset($_SESSION['r_pause_end_time']);
					unset($_SESSION['r_pause_duration']);
					unset($_SESSION['r_is_paused']);
				}
			}  ?>
			<script>
				var startTime = "";
				var startTime_Diagnostic = "";
				var startTime_Process = "";
				var startTime_Repair = "";

				var timerInterval;
				var timerInterval_Diagnostic;
				var timerInterval_Process;
				var timerInterval_Repair;

				var d_pause_start_time;
				var d_pause_end_time;
				var d_pause_duration;

				var p_pause_start_time;
				var p_pause_end_time;
				var p_pause_duration;

				var r_pause_start_time;
				var r_pause_end_time;
				var r_pause_duration;

				<?php
				$id_for_timer = $id;
				if (isset($_SESSION['startTime']) && $_SESSION['startTime'] != "") { ?>
					document.getElementById('total_pause_duration').value = <?= $_SESSION['r_pause_duration'] ?>;
					startTime = '<?= $_SESSION['startTime'] ?>';
					entry_type = 'receive';
					r_pause_duration = '<?= $_SESSION['r_pause_duration'] ?>';
					<?php
					if (isset($_SESSION['r_pause_start_time']) && $_SESSION['r_pause_start_time'] != "" && $_SESSION['r_pause_start_time'] != null) { ?>
						r_pause_start_time = '<?= $_SESSION['r_pause_start_time'] ?>';
						<?php
						if (isset($_SESSION['r_pause_end_time']) && $_SESSION['r_pause_end_time'] != "" && $_SESSION['r_pause_end_time'] != null) { ?>
							r_pause_end_time = '<?= $_SESSION['r_pause_end_time'] ?>';
							timerInterval = setInterval(function() {
								update_receive_timer_paused('<?= $id_for_timer ?>');
							}, 1000);
						<?php
						} else { ?>
							update_receive_timer_paused('<?= $id_for_timer ?>');
						<?php }
					} else { ?>
						timerInterval = setInterval(updateTimer, 1000);
						timerInterval = setInterval(function() {
							updateTimer('<?= $id_for_timer ?>');
						}, 1000);
					<?php }
				}
				if (isset($_SESSION['startTime_Diagnostic']) && $_SESSION['startTime_Diagnostic'] != "") {  ?>
					document.getElementById('d_total_pause_duration').value = <?= $_SESSION['d_pause_duration'] ?>;
					startTime_Diagnostic = '<?= $_SESSION['startTime_Diagnostic'] ?>';
					entry_type = 'diagnostic';
					d_pause_duration = '<?= $_SESSION['d_pause_duration'] ?>';
					<?php
					if (isset($_SESSION['d_pause_start_time']) && $_SESSION['d_pause_start_time'] != "" && $_SESSION['d_pause_start_time'] != null) { ?>
						d_pause_start_time = '<?= $_SESSION['d_pause_start_time'] ?>';
						<?php
						if (isset($_SESSION['d_pause_end_time']) && $_SESSION['d_pause_end_time'] != "" && $_SESSION['d_pause_end_time'] != null) { ?>
							d_pause_end_time = '<?= $_SESSION['d_pause_end_time'] ?>';
							timerInterval_Diagnostic = setInterval(function() {
								update_Diagnostic_timer_paused('<?= $id_for_timer ?>');
							}, 1000);
						<?php
						} else { ?>
							update_Diagnostic_timer_paused('<?= $id_for_timer ?>');
						<?php }
					} else { ?>
						timerInterval_Diagnostic = setInterval(updateTimer_Diagnostic, 1000);
						timerInterval_Diagnostic = setInterval(function() {
							updateTimer_Diagnostic('<?= $id_for_timer ?>');
						}, 1000);
					<?php }
				}
				if (isset($_SESSION['startTime_Process']) && $_SESSION['startTime_Process'] != "") {  ?>
					document.getElementById('p_total_pause_duration').value = <?= $_SESSION['p_pause_duration'] ?>;
					startTime_Process = '<?= $_SESSION['startTime_Process'] ?>';
					entry_type = 'process';
					p_pause_duration = '<?= $_SESSION['p_pause_duration'] ?>';
					<?php
					if (isset($_SESSION['p_pause_start_time']) && $_SESSION['p_pause_start_time'] != "" && $_SESSION['p_pause_start_time'] != null) { ?>
						p_pause_start_time = '<?= $_SESSION['p_pause_start_time'] ?>';
						<?php
						if (isset($_SESSION['p_pause_end_time']) && $_SESSION['p_pause_end_time'] != "" && $_SESSION['p_pause_end_time'] != null) { ?>
							p_pause_end_time = '<?= $_SESSION['p_pause_end_time'] ?>';
							timerInterval_Process = setInterval(function() {
								update_Process_timer_paused('<?= $id_for_timer ?>');
							}, 1000);
						<?php
						} else { ?>
							update_Process_timer_paused('<?= $id_for_timer ?>');
						<?php }
					} else { ?>
						timerInterval_Process = setInterval(updateTimer_Process, 1000);
						timerInterval_Process = setInterval(function() {
							updateTimer_Process('<?= $id_for_timer ?>');
						}, 1000);
					<?php }
				}
				if (isset($_SESSION['startTime_Repair']) && $_SESSION['startTime_Repair'] != "") {  ?>
					document.getElementById('r_total_pause_duration').value = <?= $_SESSION['r_pause_duration'] ?>;
					startTime_Repair = '<?= $_SESSION['startTime_Repair'] ?>';
					entry_type = 'repair';
					r_pause_duration = '<?= $_SESSION['r_pause_duration'] ?>';
					<?php
					if (isset($_SESSION['r_pause_start_time']) && $_SESSION['r_pause_start_time'] != "" && $_SESSION['r_pause_start_time'] != null) { ?>
						r_pause_start_time = '<?= $_SESSION['r_pause_start_time'] ?>';
						<?php
						if (isset($_SESSION['r_pause_end_time']) && $_SESSION['r_pause_end_time'] != "" && $_SESSION['r_pause_end_time'] != null) { ?>
							r_pause_end_time = '<?= $_SESSION['r_pause_end_time'] ?>';
							timerInterval_Repair = setInterval(function() {
								update_Repair_timer_paused('<?= $id_for_timer ?>');
							}, 1000);
						<?php
						} else { ?>
							update_Repair_timer_paused('<?= $id_for_timer ?>');
						<?php }
					} else { ?>
						timerInterval_Repair = setInterval(updateTimer_Repair, 1000);
						timerInterval_Repair = setInterval(function() {
							updateTimer_Repair('<?= $id_for_timer ?>');
						}, 1000);
				<?php }
				} ?>
				// Function to start the timer
				function startTimer(record_id, entry_type) {
					if (entry_type === 'receive') {
						if (startTime == '' || startTime == NULL || startTime == 'undefined') {
							startTime = new Date();
						}
						r_pause_start_time = "";
						r_pause_end_time = "";
						r_pause_duration = 0;
						document.getElementById('total_pause_duration').value = r_pause_duration;
						timerInterval = setInterval(function() {
							updateTimer(record_id);
						}, 1000);
						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "start",
							time: startTime,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".startButton_" + entry_type).hide();
								$("#stopButton_" + entry_type + "_" + record_id).show();
								$("#pauseButton_" + entry_type + "_" + record_id).show();
								$("#timer_" + entry_type + '_' + record_id).show();
							}
						});
					}
					if (entry_type === 'diagnostic') {
						if (startTime_Diagnostic == '' || startTime_Diagnostic == NULL || startTime_Diagnostic == 'undefined') {
							startTime_Diagnostic = new Date();
						}

						d_pause_start_time = "";
						d_pause_end_time = "";
						d_pause_duration = 0;
						document.getElementById('d_total_pause_duration').value = d_pause_duration;

						timerInterval_Diagnostic = setInterval(function() {
							updateTimer_Diagnostic(record_id);
						}, 1000);
						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "start",
							time: startTime_Diagnostic,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".startButton_" + entry_type).hide();
								$("#stopButton_" + entry_type + "_" + record_id).show();
								$("#pauseButton_" + entry_type + "_" + record_id).show();
								$("#timer_" + entry_type + '_' + record_id).show();
							}
						});
					}
					if (entry_type === 'process') {

						if (startTime_Process == '' || startTime_Process == NULL || startTime_Process == 'undefined') {
							startTime_Process = new Date();
						}
						p_pause_start_time = "";
						p_pause_end_time = "";
						p_pause_duration = 0;
						document.getElementById('p_total_pause_duration').value = p_pause_duration;

						timerInterval_Process = setInterval(function() {
							updateTimer_Process(record_id);
						}, 1000);
						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "start",
							time: startTime_Process,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".startButton_" + entry_type).hide();
								$("#stopButton_" + entry_type + "_" + record_id).show();
								$("#pauseButton_" + entry_type + "_" + record_id).show();
								$("#timer_" + entry_type + '_' + record_id).show();
							}
						});
					}
					if (entry_type === 'repair') {

						if (startTime_Repair == '' || startTime_Repair == NULL || startTime_Repair == 'undefined') {
							startTime_Repair = new Date();
						}
						r_pause_start_time = "";
						r_pause_end_time = "";
						r_pause_duration = 0;
						document.getElementById('r_total_pause_duration').value = r_pause_duration;

						timerInterval_Repair = setInterval(function() {
							updateTimer_Repair(record_id);
						}, 1000);
						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "start",
							time: startTime_Repair,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".startButton_" + entry_type).hide();
								$("#stopButton_" + entry_type + "_" + record_id).show();
								$("#pauseButton_" + entry_type + "_" + record_id).show();
								$("#timer_" + entry_type + '_' + record_id).show();
							}
						});
					}
				}

				function pauseTimer(record_id, entry_type) {
					if (entry_type === 'receive') {
						r_pause_start_time = new Date();
						clearInterval(timerInterval);
						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "pause",
							time: r_pause_start_time,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".pauseButton_" + entry_type).hide();
								$(".stopButton_" + entry_type).hide();
								$(".resumeButton_" + entry_type).show();
							}
						});
					}
					if (entry_type === 'diagnostic') {
						d_pause_start_time = new Date();
						clearInterval(timerInterval_Diagnostic);
						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "pause",
							time: d_pause_start_time,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".pauseButton_" + entry_type).hide();
								$(".stopButton_" + entry_type).hide();
								$(".resumeButton_" + entry_type).show();
							}
						});
					}
					if (entry_type === 'process') {
						p_pause_start_time = new Date();
						clearInterval(timerInterval_Process);
						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "pause",
							time: p_pause_start_time,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".pauseButton_" + entry_type).hide();
								$(".stopButton_" + entry_type).hide();
								$(".resumeButton_" + entry_type).show();
							}
						});
					}
					if (entry_type === 'repair') {
						r_pause_start_time = new Date();
						clearInterval(timerInterval_Repair);
						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "pause",
							time: r_pause_start_time,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".pauseButton_" + entry_type).hide();
								$(".stopButton_" + entry_type).hide();
								$(".resumeButton_" + entry_type).show();
							}
						});
					}
				}

				function resumeTimer(record_id, entry_type) {
					if (entry_type === 'receive') {
						r_pause_end_time = new Date();

						var pause_duration = new Date(r_pause_end_time) - new Date(r_pause_start_time);
						var total_pause_duration = document.getElementById('total_pause_duration').value;

						r_pause_duration = (parseInt(total_pause_duration) + parseInt(pause_duration));
						document.getElementById('total_pause_duration').value = r_pause_duration;

						timerInterval = setInterval(function() {
							update_receive_timer_paused(record_id);
						}, 1000);

						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "resume",
							time: r_pause_end_time,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".resumeButton_" + entry_type).hide();
								$(".pauseButton_" + entry_type).show();
								$(".stopButton_" + entry_type).show();
							}
						});
					}

					if (entry_type === 'diagnostic') {
						d_pause_end_time = new Date();

						var pause_duration = new Date(d_pause_end_time) - new Date(d_pause_start_time);
						var total_pause_duration = document.getElementById('d_total_pause_duration').value;

						d_pause_duration = (parseInt(total_pause_duration) + parseInt(pause_duration));
						document.getElementById('d_total_pause_duration').value = d_pause_duration;

						timerInterval_Diagnostic = setInterval(function() {
							update_Diagnostic_timer_paused(record_id);
						}, 1000);

						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "resume",
							time: d_pause_end_time,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".resumeButton_" + entry_type).hide();
								$(".pauseButton_" + entry_type).show();
								$(".stopButton_" + entry_type).show();
							}
						});
					}

					if (entry_type === 'process') {
						p_pause_end_time = new Date();

						var pause_duration = new Date(p_pause_end_time) - new Date(p_pause_start_time);
						var total_pause_duration = document.getElementById('p_total_pause_duration').value;

						p_pause_duration = (parseInt(total_pause_duration) + parseInt(pause_duration));
						document.getElementById('p_total_pause_duration').value = p_pause_duration;

						timerInterval_Process = setInterval(function() {
							update_Process_timer_paused(record_id);
						}, 1000);

						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "resume",
							time: p_pause_end_time,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".resumeButton_" + entry_type).hide();
								$(".pauseButton_" + entry_type).show();
								$(".stopButton_" + entry_type).show();
							}
						});
					}

					if (entry_type === 'repair') {
						r_pause_end_time = new Date();

						var pause_duration = new Date(r_pause_end_time) - new Date(r_pause_start_time);
						var total_pause_duration = document.getElementById('r_total_pause_duration').value;

						r_pause_duration = (parseInt(total_pause_duration) + parseInt(pause_duration));
						document.getElementById('r_total_pause_duration').value = r_pause_duration;

						timerInterval_Repair = setInterval(function() {
							update_Repair_timer_paused(record_id);
						}, 1000);

						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "resume",
							time: r_pause_end_time,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".resumeButton_" + entry_type).hide();
								$(".pauseButton_" + entry_type).show();
								$(".stopButton_" + entry_type).show();
							}
						});
					}
				}
				// Function to stop the timer
				function stopTimer(record_id, entry_type) {

					if (entry_type === 'receive') {
						stopTime = new Date();
						clearInterval(timerInterval);

						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "stop",
							time: stopTime,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".startButton_" + entry_type).show();
								$(".stopButton_" + entry_type).hide();
								$(".pauseButton_" + entry_type).hide();
								$(".timer_" + entry_type).hide();
								startTime = "";
							}
						});
					}
					if (entry_type === 'diagnostic') {
						var stopTime_Diagnostic = new Date();
						clearInterval(timerInterval_Diagnostic);

						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "stop",
							time: stopTime_Diagnostic,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".startButton_" + entry_type).show();
								$(".stopButton_" + entry_type).hide();
								$(".pauseButton_" + entry_type).hide();
								$(".timer_" + entry_type).hide();
								startTime_Diagnostic = "";
							}
						});
					}
					if (entry_type === 'process') {
						var stopTime_Process = new Date();
						clearInterval(timerInterval_Process);

						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "stop",
							time: stopTime_Process,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".startButton_" + entry_type).show();
								$(".stopButton_" + entry_type).hide();
								$(".pauseButton_" + entry_type).hide();
								$(".timer_" + entry_type).hide();
								startTime_Process = "";
							}
						});
					}
					if (entry_type === 'repair') {
						var stopTime_Repair = new Date();
						clearInterval(timerInterval_Repair);

						$.post('ajax/timeclock_in_out.php', {
							record_id: record_id,
							type: "stop",
							time: stopTime_Repair,
							entry_type: entry_type
						}, function(res) {
							if (res && res !== 'Error') {
								$(".startButton_" + entry_type).show();
								$(".stopButton_" + entry_type).hide();
								$(".pauseButton_" + entry_type).hide();
								$(".timer_" + entry_type).hide();
								startTime_Repair = "";
							}
						});
					}
				}
				// Function to update the timer
				function updateTimer(rec_id) {
					if (rec_id === 'undefined' || rec_id == '' || rec_id == null) {
						;
					} else {
						var currentTime = new Date();
						var elapsedTime = new Date(currentTime) - new Date(startTime);
						var hours = Math.floor(elapsedTime / (1000 * 60 * 60));
						var minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);
						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;
						seconds = seconds < 10 ? '0' + seconds : seconds;
						document.getElementById('timer_receive_' + rec_id).innerHTML = hours + ':' + minutes + ':' + seconds;
					}
				}

				function update_receive_timer_paused(rec_id) {

					if (rec_id === 'undefined' || rec_id == '' || rec_id == null) {
						;
					} else {
						var currentTime = new Date();
						var elapsedTime = new Date(currentTime) - new Date(startTime);
						if (r_pause_end_time == null || r_pause_end_time == "" || r_pause_end_time == "undefined") {
							var pause_duration = new Date(currentTime) - new Date(r_pause_start_time);
							var actualElapsedTime = elapsedTime - (parseInt(r_pause_duration) + parseInt(pause_duration));
						} else {
							var actualElapsedTime = elapsedTime - (parseInt(r_pause_duration));
						}

						var hours = Math.floor(actualElapsedTime / (1000 * 60 * 60));
						var minutes = Math.floor((actualElapsedTime % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((actualElapsedTime % (1000 * 60)) / 1000);

						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;
						seconds = seconds < 10 ? '0' + seconds : seconds;

						document.getElementById('timer_receive_' + rec_id).innerHTML = hours + ':' + minutes + ':' + seconds;
					}
				}

				function update_Diagnostic_timer_paused(rec_id) {

					if (rec_id === 'undefined' || rec_id == '' || rec_id == null) {
						;
					} else {
						var currentTime = new Date();
						var elapsedTime = new Date(currentTime) - new Date(startTime_Diagnostic);
						if (d_pause_end_time == null || d_pause_end_time == "" || d_pause_end_time == "undefined") {
							var pause_duration = new Date(currentTime) - new Date(d_pause_start_time);
							var actualElapsedTime = elapsedTime - (parseInt(d_pause_duration) + parseInt(pause_duration));
						} else {
							var actualElapsedTime = elapsedTime - (parseInt(d_pause_duration));
						}

						var hours = Math.floor(actualElapsedTime / (1000 * 60 * 60));
						var minutes = Math.floor((actualElapsedTime % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((actualElapsedTime % (1000 * 60)) / 1000);

						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;
						seconds = seconds < 10 ? '0' + seconds : seconds;

						document.getElementById('timer_diagnostic_' + rec_id).innerHTML = hours + ':' + minutes + ':' + seconds;
					}
				}

				function update_Process_timer_paused(rec_id) {

					if (rec_id === 'undefined' || rec_id == '' || rec_id == null) {
						;
					} else {
						var currentTime = new Date();
						var elapsedTime = new Date(currentTime) - new Date(startTime_Process);
						if (p_pause_end_time == null || p_pause_end_time == "" || p_pause_end_time == "undefined") {
							var pause_duration = new Date(currentTime) - new Date(p_pause_start_time);
							var actualElapsedTime = elapsedTime - (parseInt(p_pause_duration) + parseInt(pause_duration));
						} else {
							var actualElapsedTime = elapsedTime - (parseInt(p_pause_duration));
						}

						var hours = Math.floor(actualElapsedTime / (1000 * 60 * 60));
						var minutes = Math.floor((actualElapsedTime % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((actualElapsedTime % (1000 * 60)) / 1000);

						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;
						seconds = seconds < 10 ? '0' + seconds : seconds;

						document.getElementById('timer_process_' + rec_id).innerHTML = hours + ':' + minutes + ':' + seconds;
					}
				}

				function updateTimer_Diagnostic(rec_id) {
					if (rec_id === 'undefined' || rec_id == '' || rec_id == null) {
						;
					} else {
						var currentTime = new Date();
						var elapsedTime = new Date(currentTime) - new Date(startTime_Diagnostic);
						var hours = Math.floor(elapsedTime / (1000 * 60 * 60));
						var minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);
						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;
						seconds = seconds < 10 ? '0' + seconds : seconds;
						document.getElementById('timer_diagnostic_' + rec_id).innerHTML = hours + ':' + minutes + ':' + seconds;
					}
				}

				function updateTimer_Repaire(rec_id) {
					if (rec_id === 'undefined' || rec_id == '' || rec_id == null) {
						;
					} else {
						var currentTime = new Date();
						var elapsedTime = new Date(currentTime) - new Date(startTime_Repaire);
						var hours = Math.floor(elapsedTime / (1000 * 60 * 60));
						var minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);
						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;
						seconds = seconds < 10 ? '0' + seconds : seconds;
						document.getElementById('timer_repaire_' + rec_id).innerHTML = hours + ':' + minutes + ':' + seconds;
					}
				}

				function updateTimer_Process(rec_id) {
					if (rec_id === 'undefined' || rec_id == '' || rec_id == null) {
						;
					} else {
						var currentTime = new Date();
						var elapsedTime = new Date(currentTime) - new Date(startTime_Process);
						var hours = Math.floor(elapsedTime / (1000 * 60 * 60));
						var minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);
						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;
						seconds = seconds < 10 ? '0' + seconds : seconds;
						document.getElementById('timer_process_' + rec_id).innerHTML = hours + ':' + minutes + ':' + seconds;
					}
				}

				function update_Repair_timer_paused(rec_id) {

					if (rec_id === 'undefined' || rec_id == '' || rec_id == null) {
						;
					} else {
						var currentTime = new Date();
						var elapsedTime = new Date(currentTime) - new Date(startTime_Repair);
						if (r_pause_end_time == null || r_pause_end_time == "" || r_pause_end_time == "undefined") {
							var pause_duration = new Date(currentTime) - new Date(r_pause_start_time);
							var actualElapsedTime = elapsedTime - (parseInt(r_pause_duration) + parseInt(pause_duration));
						} else {
							var actualElapsedTime = elapsedTime - (parseInt(r_pause_duration));
						}

						var hours = Math.floor(actualElapsedTime / (1000 * 60 * 60));
						var minutes = Math.floor((actualElapsedTime % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((actualElapsedTime % (1000 * 60)) / 1000);

						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;
						seconds = seconds < 10 ? '0' + seconds : seconds;

						document.getElementById('timer_repair_' + rec_id).innerHTML = hours + ':' + minutes + ':' + seconds;
					}
				}

				function updateTimer_Repair(rec_id) {
					if (rec_id === 'undefined' || rec_id == '' || rec_id == null) {
						;
					} else {
						var currentTime = new Date();
						var elapsedTime = new Date(currentTime) - new Date(startTime_Repair);
						var hours = Math.floor(elapsedTime / (1000 * 60 * 60));
						var minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);
						hours = hours < 10 ? '0' + hours : hours;
						minutes = minutes < 10 ? '0' + minutes : minutes;
						seconds = seconds < 10 ? '0' + seconds : seconds;
						document.getElementById('timer_repair_' + rec_id).innerHTML = hours + ':' + minutes + ':' + seconds;
					}
				}
				// Function to update the timer
			</script>
		<?php } 
		*/ ?>
	</body>

	</html>
<?php
	mysqli_close($conn);
} else {
	echo redirect_to_page("signin");
}
include("sub_files/index2_js.php");?>
<script>
	$(document).ready(function() { 
		$('.toggle-column').on('change', function() {
			var columnClass = '.col-' + $(this).data('column');
			if ($(this).is(':checked')) {
				$(columnClass).show();
				$(columnClass).removeClass("display_none");
			} else {
				$(columnClass).hide();
				$(this).addClass("display_none");
			}
		}); 
	});
</script>
<?php 


if (file_exists($module_js)) { ?>
	<script src="<?php echo $directory_path . $module_js; ?>"></script>
<?php } ?>