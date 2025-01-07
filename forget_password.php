<?php
$test_on_local = 0;
include("conf/session_start.php");
include('path.php');
include($directory_path . "conf/connection.php");
include($directory_path . "conf/functions.php");
$db = new mySqlDB;
if (isset($_SESSION["username"]) && isset($_SESSION["user_id"]) && isset($_SESSION["schoolDirectory"]) && $_SESSION["schoolDirectory"] == 'fireg' &&  isset($_SESSION["project_name"]) && $_SESSION["project_name"] == $project_name) {
	echo redirect_to_page("home");
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
		if ($email == "") {
			$error['msg'] = "<span class='color-red'>Enter Email</span>";
		}
		if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
			echo redirect_to_page("signout");
			exit();
		}
		if (empty($error)) {
			$sql 	= "	SELECT a.*, b.company_name
						FROM users a 
						INNER JOIN subscribers_users b ON b.id = a.subscriber_users_id
						WHERE a.email = '" . $email . "' ";
			// echo $sql;
			$result = $db->query($conn, $sql);
			$count 	= $db->counter($result);
			if ($count > 0) {
				$row 	 			= $db->fetch($result);
				$user_id			= $row[0]['id'];
				$enabled 			= $row[0]['enabled'];
				$user_type 			= $row[0]['user_type'];
				$reg_status 		= $row[0]['reg_status'];
				$first_name 		= $row[0]['first_name'];
				$company_name 		= $row[0]['company_name'];
				$username 			= $row[0]['username'];
				$a_password 		= $row[0]['a_password'];
				if ($enabled > 0) {
					if ($reg_status == '2') { // Approved 
						require 'sendGrid/vendor/autoload.php';
						$subject_to = $project_domain . " Account Password - ";
						$toEmail 	= $email;
						$toname 	= $first_name;
						$body = "<b>Dear, " . $first_name . "</b>";
						$body .= "<br><br> Please note your login details for <b>" . $company_name . ".</b><br><br>";
						$body .= "<b>URL: </b><a href='" . PROJECT_URL . "' target='_blank'>" . PROJECT_URL . "</a><br>";
						$body .= "<b>Username: </b>" . $username . "<br>";
						$body .= "<b>Password: </b>" . $a_password . "<br>";
						$body .= "<br><br><b>Regards<br>Team " . $project_domain . "</b>";
						$body .= "<br><br>";
						$parm1 = "";
						$parm2 = "";
						$parm3 = "";
						$parm4 = "";
						$parm5 = "";
						$parm6 = "";
						if ($test_on_local == 0) {
							sendEmailSendGrid($subject_to, $toEmail, $toname, $body, $parm1, $parm2, $parm3, $parm4, $parm5, $parm6);
						}
						$msg['msg_success'] = "<span class='color-green'>The login details have been sent. Please check your email.</span>";
						$email = "";
					} else {
						$error['msg'] = "<span class='color-red'>Your account is not active, Please contact support team.</span>";
					}
				} else {
					$error['msg'] = "<span class='color-red'>Your account is disabled</span>";
					insert_error($db, $conn, 'Login', 'username', $username, $error['msg'], 'login');
				}
			} else {
				$error['msg'] = "<span class='color-red'>Email is Incorrect</span>";
				$sql2 		= "SELECT * FROM users WHERE email = '" . $email . "'";
				$result2 	= $db->query($conn, $sql2);
				$count2 	= $db->counter($result2);
				if ($count2 == 0) {
					$error['msg'] = "<span class='color-red'>Email is Incorrect</span>";
					insert_error($db, $conn, 'Forget Password', 'email', "email:" . $email, $error['msg'], 'Forget Password');
				}
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
		<title>Forget Password | <?php echo ucwords(strtolower(PROJECT_TITLE)); ?> </title>
		<link rel="alternate" hreflang="en" href="<?php echo PROJECT_URL; ?>/forget-password" />
		<link rel="canonical" href="<?php echo PROJECT_URL; ?>/forget-password" />
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
										<h5 class="ml-4">Get Your Password</h5>
									</div>
								</div>
								<?php
								if (isset($error['msg'])) { ?>
									<div class="card-alert card red lighten-5">
										<div class="card-content red-text">
											<p><?php echo $error['msg']; ?></p>
										</div>
										<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
								<?php }
								if (isset($msg['msg_success'])) { ?>
									<div class="card-alert card green lighten-5">
										<div class="card-content green-text">
											<p><?php echo $msg['msg_success']; ?></p>
										</div>
										<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
										</button>
									</div>
								<?php } ?>
								<div class="row margin">
									<div class="input-field col s12">
										<i class="material-icons prefix pt-2">email</i>
										<input id="email" type="email" name="email" value="<?php if (isset($email)) {
																								echo $email;
																							} ?>">
										<label for="email">Email</label>
									</div>
								</div>
								<div class="row">
									<div class="input-field col s12">
										<button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">Continue</button>
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