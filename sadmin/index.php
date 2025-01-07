<?php
$test_on_local = 1;
include("path.php");
include($directory_path . "conf/session_start.php");
include($directory_path . "conf/connection.php");
require_once($directory_path . "conf/functions.php");
extract($_REQUEST);
//echo $_SESSION["sadmin"]."-------------------------";die;
if (isset($_SESSION["username_super_admin"]) && isset($_SESSION["user_id_super_admin"]) && isset($_SESSION["sadmin"]) && $_SESSION["sadmin"] == 'Super_Admin' &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {

	$db = new mySqlDB;
	$pageTitle = "Super Admin Dashboard"; ?>
	<!DOCTYPE html>
	<html class="loading" lang="en" data-textdirection="ltr">
	<!-- BEGIN: Head-->

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $directory_path; ?>/app-assets/images/favicon/favicon-32x32.png">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!-- BEGIN: VENDOR CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/vendors/vendors.min.css">

		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/vendors/data-tables/css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/vendors/data-tables/css/select.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/vendors/animate-css/animate.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/vendors/chartist-js/chartist.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/vendors/chartist-js/chartist-plugin-tooltip.css">
		<!-- END: VENDOR CSS-->
		<!-- BEGIN: Page Level CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/css/themes/vertical-modern-menu-template/materialize.css">

		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/flag-icon/css/flag-icon.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/dropify/css/dropify.min.css">

		<!-- END: Page Level CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/css/themes/vertical-modern-menu-template/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/css/pages/page-users.min.css">

		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/css/themes/vertical-modern-menu-template/style.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/css/pages/data-tables.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/css/pages/dashboard-modern.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/css/pages/intro.css">
		<!-- BEGIN: Custom CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>/app-assets/css/custom/custom.css">

		<!-- END: Custom CSS-->
		<?php
		check_session_exist2($db, $conn, $_SESSION["user_id_super_admin"], $_SESSION["username_super_admin"], $_SESSION["user_type_super_admin"]);

		$sql 				= "SELECT * FROM super_admin WHERE id = '" . $_SESSION["user_id_super_admin"] . "'";
		$result_d_profile 	= $db->query($conn, $sql);
		$row_d_profile		= $db->fetch($result_d_profile);
		//echo $sql;die;
		$full_name 			= $row_d_profile[0]['first_name'] . " " . $row_d_profile[0]['middle_name'] . " " . $row_d_profile[0]['last_name'];
		$admin_profile_id	= $_SESSION["admin_id_super_admin"];

		$nav_layout = "sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square";
		$page_width = "";
		$nav_check 		= "radio_button_checked";
		$top_nav_layout = "navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow";
		// module page
		if (isset($string)) {
			$parm 				= "?string=" . $string;
			$string 			= decrypt($string);
			$string_explode 	= explode('&', $string);

			$module 		= "";
			$page 			= "";
			$editmaster 	= "";
			$detail_id 		= "";
			foreach ($string_explode as $value) {
				$string_data_explode = explode('=', $value);
				if ($string_data_explode[0] == 'module') {
					$module 			= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'page') {
					$page 				= $string_data_explode[1];
				}
				if ($string_data_explode[0] == 'cmd') {
					$cmd 				= $string_data_explode[1];
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
				if ($string_data_explode[0] == 'editmaster') {
					$editmaster 		= $string_data_explode[1];
				}
				$sub_page = "components/" . $module . "/" . $module . "-" . $page . ".php";

				$sql_md 		= "SELECT * FROM super_admin_menus WHERE folder_name = '" . $module . "' ORDER BY id DESC LIMIT 1 ";
				$result_md 		= $db->query($conn, $sql_md);
				$count_md		= $db->counter($result_md);
				if ($count_md > 0) {
					$row_md			= $db->fetch($result_md);
					$layout_type	= $row_md[0]["layout_type"];
					if ($layout_type == 'nav-collapsible') {
						$nav_layout 	= "sidenav-main nav-collapsible sidenav-light sidenav-active-square nav-collapsed";
						$page_width 	= "main-full";
						$nav_check 		= "radio_button_unchecked";
						$top_nav_layout = "navbar-main navbar-color nav-collapsible navbar-dark gradient-45deg-indigo-purple no-shadow nav-collapsed";
					} else {
						$nav_layout = "sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square";
						$page_width = "";
						$nav_check 		= "radio_button_checked";
						$top_nav_layout = "navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow";
					}
				} else {
					$nav_layout 	= "sidenav-main nav-collapsible sidenav-light sidenav-active-square nav-collapsed";
					$page_width 	= "main-full";
					$nav_check 		= "radio_button_unchecked";
					$top_nav_layout = "navbar-main navbar-color nav-collapsible navbar-dark gradient-45deg-indigo-purple no-shadow nav-collapsed";
				}
			}
			$check_module_permission = "";
			$check_module_permission = check_module_permission_super_admin($db, $conn, $module, $_SESSION["user_id_super_admin"]);
			$pageTitle 	= $check_module_permission;
			if ($check_module_permission == "") {
				header("location: signout");
			}
		} else {
			$parm = "?url=";
			$sub_page = 'components/main_content.php';
		}
		$allow_password_change	= 1;
		//sidebar-collapse    this is css class to hide side bar
		$nav_layout = "sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square";
		$page_width = "";
		$nav_check 		= "radio_button_checked";
		$top_nav_layout = "navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow";
		?>
		<title><?php echo $pageTitle; ?> | <?php echo PROJECT_TITLE2; ?></title>
	</head>
	<!-- END: Head-->

	<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

		<!-- BEGIN: Header-->
		<?php include('sub_files/header.php'); ?>
		<!-- END: Header-->

		<!-- BEGIN: SideNav-->
		<?php include('sub_files/sidebar.php'); ?>
		<!-- END: SideNav-->
		<!-- BEGIN: Page Main-->
		<?php include($sub_page); ?>
		<!-- END: Page Main-->
		<!-- Theme Customizer -->

		<!-- BEGIN: Footer-->
		<?php include("sub_files/footer.php"); ?>
		<!-- END: Footer-->
		<!-- BEGIN VENDOR JS-->
		<script src="<?php echo $directory_path; ?>/app-assets/js/vendors.min.js"></script>
		<!-- BEGIN VENDOR JS-->
		<!-- BEGIN PAGE VENDOR JS-->
		<script src="<?php echo $directory_path; ?>/app-assets/vendors/chartjs/chart.min.js"></script>
		<script src="<?php echo $directory_path; ?>/app-assets/vendors/chartist-js/chartist.min.js"></script>
		<script src="<?php echo $directory_path; ?>/app-assets/vendors/chartist-js/chartist-plugin-tooltip.js"></script>
		<script src="<?php echo $directory_path; ?>/app-assets/vendors/chartist-js/chartist-plugin-fill-donut.min.js"></script>
		<!-- END PAGE VENDOR JS-->

		<!-- BEGIN PAGE VENDOR JS-->
		<script src="<?php echo $directory_path; ?>/app-assets/vendors/data-tables/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo $directory_path; ?>/app-assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js"></script>
		<script src="<?php echo $directory_path; ?>/app-assets/vendors/data-tables/js/dataTables.select.min.js"></script>
		<!-- END PAGE VENDOR JS-->
		<!-- BEGIN THEME  JS-->
		<script src="<?php echo $directory_path; ?>/app-assets/js/plugins.js"></script>
		<script src="<?php echo $directory_path; ?>/app-assets/js/search.js"></script>
		<script src="<?php echo $directory_path; ?>/app-assets/js/custom/custom-script.js"></script>
		<script src="<?php echo $directory_path; ?>/app-assets/js/scripts/customizer.js"></script>
		<!-- END THEME  JS-->
		<!-- BEGIN PAGE LEVEL JS-->
		<script src="<?php echo $directory_path; ?>/app-assets/js/scripts/dashboard-modern.js"></script>
		<!-- END PAGE LEVEL JS-->
		<!-- Alert -->
		<script src="<?php echo $directory_path; ?>/app-assets/js/scripts/ui-alerts.min.js"></script>
		<!-- BEGIN PAGE LEVEL JS-->
		<script src="<?php echo $directory_path; ?>/app-assets/js/scripts/page-users.min.js"></script>
		<!-- BEGIN PAGE LEVEL JS-->
		<script src="<?php echo $directory_path; ?>/app-assets/js/scripts/data-tables.js"></script>
		<!-- END PAGE LEVEL JS-->

		<script src="<?php echo $directory_path; ?>app-assets/vendors/dropify/js/dropify.min.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/js/scripts/form-file-uploads.min.js"></script>
		<!-- END PAGE LEVEL JS-->
	</body>

	</html>
<?php
	mysqli_close($conn);
} else {
	header("location: signin");
}
if (isset($module) && ($module == 'system_roles' || $module == 'super_roles')) { ?>
	<script language="JavaScript">
		$(".checkbox").click(function() {
			var className = $(this).attr('class');
			var result = className.split(" ");
			$.each(result, function(key, value) {
				if (value != 'checkbox') {
					$("#" + value).prop("checked", true);
				}
			});
			var menu_id = $(this).attr("id");
			if ($(this).prop("checked")) {
				$("." + menu_id).prop("checked", true);
			} else {
				$("." + menu_id).prop("checked", false);
			}
		});
		$("#all_checked").click(function() {
			if ($(this).prop("checked")) {
				$(".checkbox").prop("checked", true);
			} else {
				$(".checkbox").prop("checked", false);
			}
		});
	</script>
<?php }
if (isset($module) && ($module == 'school_users' || $module == 'super_roles')) { ?>
	<script>
		$(document).ready(function() {
			$("#country").change(function() {
				var country = $("#country").val();
				if (country == '2') {
					$("#show_liberia_countries").show();
				} else {
					$("#show_liberia_countries").hide();
				}
			});
			$("#school_size").change(function() {
				var school_size = $("#school_size").val();
				if (school_size == 'Group of Schools') {
					$("#show_school_group_name").show();
				} else {
					$("#show_school_group_name").hide();
				}
			});
		});
	</script>
<?php }
if (isset($module) && ($module == 'school_owner_accounts_sadmin' || $module == 'school_owner_accounts_sadmin')) { ?>
	<script>
		$(document).ready(function() {
			$("#country").change(function() {
				var country = $("#country").val();
				if (country == '2') {
					$("#show_liberia_countries").show();
				} else {
					$("#show_liberia_countries").hide();
				}
			});
			$("#school_size").change(function() {
				var school_size = $("#school_size").val();
				if (school_size == 'Group of Schools') {
					$("#show_school_group_name").show();
				} else {
					$("#show_school_group_name").hide();
				}
			});
		});
	</script>
<?php } ?>