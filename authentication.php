<?php
include("conf/session_start.php");
include('path.php');
include($directory_path . "conf/connection.php");
include($directory_path . "conf/functions.php");
$db = new mySqlDB;
$msg['success'] = "Login authentication code has been sent on your Email.";
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == $project_folder &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {
	echo redirect_to_page("home");
} else if (!isset($_SESSION["user_id"])) {
	echo redirect_to_page("signin");
} else {
	if (!isset($_SESSION['csrf_session'])) {
		$_SESSION['csrf_session'] = session_id();
	}
	extract($_POST);
	if (isset($is_submit) && $is_submit == "Y") {
		foreach ($_POST as $key => $value) {
			if (!is_array($value)) {
				$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
				$$key = $data[$key];
			}
		}
		$username_without_dashes = 0;
		if ($authentication_code == "") {
			$error['msg'] = "<span class='color-red'>Enter Authentication Code</span>";
		}
		if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
			echo redirect_to_page("signout");
			exit();
		}
		if (empty($error)) {
			$sql 	= "	SELECT b.*, a.time_auth_code_exp_from
						FROM verification_codes a 
						INNER JOIN users b ON b.id = a.user_id
						WHERE a.user_id = '" . $_SESSION["user_id"] . "' 
						AND a.email_v_code = '" . $authentication_code . "' 
						AND a.enabled = 1";
			//echo $sql; die;
			$result = $db->query($conn, $sql);
			$count 	= $db->counter($result);
			if ($count > 0) {
				$row 	 					= $db->fetch($result);
				$user_id					= $row[0]['id'];
				$enabled 					= $row[0]['enabled'];
				$user_type 					= $row[0]['user_type'];
				$subscriber_users_id		= $row[0]['subscriber_users_id'];
				$time_auth_code_exp_from	= $row[0]['time_auth_code_exp_from'];
				$time_auth_code_exp_to 		= strtotime($time_auth_code_exp_from . ' +30 minute');
				if ($time_auth_code_exp_to > strtotime($time_auth_code_now)) {
					if ($enabled > 0) {
						$sql 		= "UPDATE users SET sec_users = 1, last_login = '" . $add_date . "', last_login_ip = '" . $add_ip . "'
									WHERE id = '" . $user_id . "' "; //echo $sql;die;
						$db->query($conn, $sql);
						$log_history = "INSERT INTO user_login_logout_history (user_id, user_type, entry_type, add_date, add_ip)
										VALUES('" . $user_id . "', '" . $user_type . "', 'Login', '" . $add_date . "', '" . $add_ip . "')"; //echo $log_history;die;
						$db->query($conn, $log_history);
						//START USER SESSIONS
						$_SESSION["user_id"]            	= $user_id;
						$_SESSION["subscriber_users_id"]	= $subscriber_users_id;
						$_SESSION["username"]           	= $row[0]["username"];
						$_SESSION["db_name"]          		= $selected_db_name;
						$_SESSION["user_type"]				= $user_type;
						$_SESSION["schoolDirectory"]		= $project_folder;
						$_SESSION["project_name"]			= $project_name;
						echo redirect_to_page("home");
					} else {
						$error['msg'] = "<span class='color-red'>Your account is disabled</span>";
						insert_error($db, $conn, 'Authentication', 'username', $username, $error['msg'], 'Login');
					}
				} else {
					$error['msg'] = "<span class='color-red'>Authentication code has been expired, Please Sign In again</span>";
					insert_error($db, $conn, 'Authentication', 'authentication_code', $authentication_code, $error['msg'], 'Login');
				}
			} else {
				$error['msg'] = "<span class='color-red'>Incorrect Authentication Code</span>";
				unset($msg);
			}
		}
	} ?>
	<!DOCTYPE html>
	<html class="loading" lang="en" data-textdirection="ltr">
	<!-- BEGIN: Head-->

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<meta name="author" content="ThemeSelect">
		<title>Login Authentication | <?php echo ucwords(strtolower(PROJECT_TITLE2)); ?> </title>
		<link rel="alternate" hreflang="en" href="<?php echo PROJECT_URL; ?>/signin" />
		<link rel="canonical" href="<?php echo PROJECT_URL; ?>/signin" />
		<link rel="apple-touch-icon" href="<?php echo $directory_path; ?>app-assets/images/favicon/apple-touch-icon-152x152.png">
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $directory_path; ?>app-assets/images/favicon/favicon-32x32.png">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!-- BEGIN: VENDOR CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/vendors/vendors.min.css">
		<!-- END: VENDOR CSS-->
		<!-- BEGIN: Page Level CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/themes/vertical-modern-menu-template/materialize.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/themes/vertical-modern-menu-template/style.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/pages/login.css">
		<!-- END: Page Level CSS-->
		<!-- BEGIN: Custom CSS-->
		<link rel="stylesheet" type="text/css" href="<?php echo $directory_path; ?>app-assets/css/custom/custom.css">
		<!-- END: Custom CSS-->
	</head>
	<!-- END: Head-->

	<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 1-column login-bg   blank-page blank-page" data-open="click" data-menu="vertical-modern-menu" data-col="1-column">
		<div class="row">
			<div class="col s12">
				<div class="container">
					<div id="login-page" class="row">
						<div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 login-card bg-opacity-8">
							<form class="login-form" method="post" action="">
								<input type="hidden" name="is_submit" id="is_submit" value="Y">
								<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																					echo encrypt($_SESSION['csrf_session']);
																				} ?>">
								<div class="row">
									<div class="input-field col s12">
										<h5 class="ml-4"> Login Authentication </h5>
									</div>
								</div>
								<?php if (isset($error['msg'])) { ?>
									<div class="card-alert card red lighten-5">
										<div class="card-content red-text">
											<p><?php echo $error['msg']; ?></p>
										</div>
									</div>
								<?php } else if (isset($msg['success'])) { ?>
									<div class="card-alert card green lighten-5">
										<div class="card-content green-text">
											<p><?php echo $msg['success']; ?></p>
										</div>
									</div>
								<?php } ?>
								<div class="row margin">
									<div class="input-field col s12">
										<i class="material-icons prefix pt-2">person_outline</i>
										<input id="authentication_code" type="text" name="authentication_code" value="<?php if (isset($authentication_code)) {
																															echo $authentication_code;
																														} ?>">
										<label for="authentication_code">Authentication Code</label>
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12">
										<button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">Authenticate to Sign In</button>
									</div>
								</div>
								<div class="row">
									<div class="input-field col s6 m6 l6">
										<p class="margin medium-small"><a href="signin">Sign In</a></p>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="content-overlay"></div>
			</div>
		</div>
		<!-- BEGIN VENDOR JS-->
		<script src="<?php echo $directory_path; ?>app-assets/js/vendors.min.js"></script>
		<!-- BEGIN VENDOR JS-->
		<!-- BEGIN THEME  JS-->
		<script src="<?php echo $directory_path; ?>app-assets/js/plugins.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/js/search.js"></script>
		<script src="<?php echo $directory_path; ?>app-assets/js/custom/custom-script.js"></script>
		<!-- END THEME  JS-->
	</body>

	</html>
<?php
	mysqli_close($conn);
} ?>