<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_sadmin_directory_access();
}
$db = new mySqlDB;
$button_val 				= "Update Profile";
$title_heading 				= "Update Profile";
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	if (empty($error)) {
		$sql_c_up = "UPDATE super_admin SET 
											first_name 			= '" . $first_name . "',
											middle_name 		= '" . $middle_name . "',
											last_name 			= '" . $last_name . "',

											update_date 		= '" . $add_date . "',
											update_by 			= '" . $_SESSION['username_super_admin'] . "',
											update_ip 			= '" . $add_ip . "'
					WHERE username = '" . $_SESSION['username_super_admin'] . "' ";
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
} else {
	$sql_ee 	= "SELECT a.* FROM super_admin a WHERE a.username = '" . $_SESSION['username_super_admin'] . "' ";
	$result_ee 	= $db->query($conn, $sql_ee);
	$row_ee 	= $db->fetch($result_ee);

	$first_name 		= $row_ee[0]['first_name'];
	$middle_name 		= $row_ee[0]['middle_name'];
	$last_name 			= $row_ee[0]['last_name'];
	$username 			= $row_ee[0]['username'];
	$email 				= $row_ee[0]['email'];
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
						<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $title_heading; ?></span></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
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
					<h4 class="card-title"><?php echo $title_heading; ?></h4>
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
						<div class="row">
							<div class="input-field col m4 s12">
								<i class="material-icons prefix">person_outline</i>
								<input id="first_name" type="text" name="first_name" value="<?php if (isset($first_name)) {
																								echo $first_name;
																							} ?>">
								<label for="first_name">First Name</label>
							</div>
							<div class="input-field col m4 s12">
								<i class="material-icons prefix">person_outline</i>
								<input id="middle_name" type="text" name="middle_name" value="<?php if (isset($middle_name)) {
																									echo $middle_name;
																								} ?>">
								<label for="middle_name">Middle Name</label>
							</div>
							<div class="input-field col m4 s12">
								<i class="material-icons prefix">person_outline</i>
								<input id="last_name" type="text" name="last_name" value="<?php if (isset($last_name)) {
																								echo $last_name;
																							} ?>">
								<label for="last_name">Last Name</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<i class="material-icons prefix">person_outline</i>
								<input readonly id="username" type="text" name="username" value="<?php if (isset($username)) {
																										echo $username;
																									} ?>">
								<label for="username">Username</label>
							</div>
							<div class="input-field col m4 s12">
								<i class="material-icons prefix">mail_outline</i>
								<input readonly id="email" type="text" name="email" value="<?php if (isset($email)) {
																								echo $email;
																							} ?>">
								<label for="email">Email</label>
							</div>
						</div>
						<div class="row">
							<div class="row">
								<div class="input-field col m3 s12"></div>
								<div class="input-field col m6 s12">
									<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?>
									</button>
								</div>
								<div class="input-field col m3 s12"></div>
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