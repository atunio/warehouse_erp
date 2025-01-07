<?php
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db = new mySqlDB;
$subscriber_users_id	= $_SESSION["subscriber_users_id"];
$button_val 			= "Update Password";
$title_heading 			= "Change Password";
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	if (access("edit_perm") == 0) {
		$error['msg'] = "You do not have edit permissions.";
	} else {
		if (isset($confirm_new_password) && $confirm_new_password == "") {
			$error['msg'] = "Confirm Password Required";
		} else if (isset($new_password) && isset($confirm_new_password) && $confirm_new_password != $new_password) {
			$error['msg'] = "Confirm password does not match.";
		}
		if (isset($new_password) && $new_password == "") {
			$error['msg'] = "New Password Required";
		} else if (strlen($new_password) < 5) {
			$error['msg'] = "New Password must be greater than 4 Characters";
		}
		if (isset($old_password) && $old_password == "") {
			$error['msg'] = "Current Password is Required";
		} else {
			$sql			= " SELECT * FROM users
								WHERE username = '" . $_SESSION['username'] . "'
								AND a_password = '" . $old_password . "' ";
			$result 		= $db->query($conn, $sql);
			$count_pas 		= $db->counter($result);
			if ($count_pas == 0) {
				$error['msg'] = "Current password is incorrect.";
			}
		}
		if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
			header("location: signout");
			exit();
		}
		if (empty($error)) {
			$a_password_md5 = md5($new_password);
			$sql_c_up = "UPDATE users 
												SET a_password		= '" . $new_password . "', 
													a_password_md5	= '" . $a_password_md5 . "', 
													update_date		= '" . $add_date . "',
													update_by		= '" . $_SESSION['username'] . "',
													update_ip		= '" . $add_ip . "'
						WHERE username = '" . $_SESSION['username'] . "' 
						AND subscriber_users_id='" . $subscriber_users_id . "' ";
			$ok = $db->query($conn, $sql_c_up);
			if ($ok) {
				$new_password 			= "";
				$confirm_new_password 	= "";
				$old_password 			= "";
				$msg['msg_success'] = "Password Updated Successfully.";
			} else {
				$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
			}
		}
	}
} ?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="col s10 m6 l6">
						<h5 class="breadcrumbs-title mt-0 mb-0"><span>Change Password</span></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m6 16">
			<div id="Form-advance" class="card card card-default scrollspy">
				<div class="card-content">
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
					<?php } else if (isset($msg['msg_success'])) { ?>
						<div class="card-alert card green lighten-5">
							<div class="card-content green-text">
								<p><?php echo $msg['msg_success']; ?></p>
							</div>
							<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
					<?php } ?>
					<h4 class="card-title">Change Password</h4>
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																			echo encrypt($_SESSION['csrf_session']);
																		} ?>">
						<div class="row">
							<div class="input-field col m12 s12">
								<i class="material-icons prefix">lock_outline</i>
								<input id="old_password" type="password" name="old_password" value="<?php if (isset($old_password)) {
																										echo $old_password;
																									} ?>">
								<label for="old_password">Current Password</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m12 s12">
								<i class="material-icons prefix">lock_outline</i>
								<input id="new_password" type="password" name="new_password" value="<?php if (isset($new_password)) {
																										echo $new_password;
																									} ?>">
								<label for="new_password">New Password</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m12 s12">
								<i class="material-icons prefix">lock_outline</i>
								<input id="confirm_new_password" type="password" name="confirm_new_password" value="<?php if (isset($confirm_new_password)) {
																														echo $confirm_new_password;
																													} ?>">
								<label for="confirm_new_password">Confirm New Password</label>

							</div>
						</div>
						<div class="row">
							<div class="row">
								<div class="input-field col m4 s12"></div>
								<div class="input-field col m4 s12">
									<?php if (access("edit_perm") == 1) { ?>
										<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action">Change</button>
										</button>
									<?php } ?>
								</div>
								<div class="input-field col m4 s12"></div>
							</div>
						</div>
					</form>
				</div>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div>
</div>
<!-- END: Page Main-->