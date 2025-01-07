<?php
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db = new mySqlDB;
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
$button_val 		= "Update Profile";
$title_heading 		= "Update Profile";
$profile_pic		= "no_image.png";
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
		if (decrypt($csrf_token) != $_SESSION["csrf_session"]) {
			header("location: signout");
			exit();
		}
		$sql1 		= "	SELECT * FROM users WHERE phone_no = '" . $phone_no . "' AND id != '" . $user_id . "' "; //echo $sql1;
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['msg'] 	= "Sorry! This Mobile No is already exist, try another.";
			$phone_no_valid	= "invalid";
		}
		$sql1 		= "	SELECT * FROM users WHERE email = '" . $email . "' AND id != '" . $user_id . "' "; //echo $sql1;
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['msg'] 	= "Sorry! This Email is already exist, try another.";
			$email_valid	= "invalid";
		}
		if ($_SESSION['user_type'] == 'Admin') {
			if (isset($s_address) && $s_address == "") {
				$error['msg'] 	= "Please Enter Address";
				$s_address_valid = "invalid";
			}
			if (isset($company_name) && $company_name == "") {
				$error['msg'] 	= "Please Enter Company Name";
				$company_name_valid = "invalid";
			}
			if (isset($phone_no) && $phone_no == "") {
				$error['msg'] 	= "Please Enter Company Mobile No";
				$phone_no_valid = "invalid";
			}
			if (isset($last_name) && $last_name == "") {
				$error['msg'] 	= "Please Enter Last Name";
				$last_name_valid = "invalid";
			}
			if (isset($first_name) && $first_name == "") {
				$error['msg'] 	= "Please Enter First Name";
				$first_name_valid = "invalid";
			}
			if (is_array($_FILES) && isset($_FILES["profile_pic"]["name"]) && $_FILES["profile_pic"]["name"] != "") {
				$picture_uniq_id = $_SESSION['user_id'] . "_" . uniqid();
				$allowedExts = array("gif", "GIF", "jpeg", "JPEG", "JPG", "jpg", "png", "PNG");
				$temp = explode(".", $_FILES["profile_pic"]["name"]);
				$extension = end($temp);
				$valid_formats  	= array("image/JPEG", "image/jpeg", "image/JPG", "image/jpg", "image/PNG", "image/png", "image/gif", "image/GIF"); //add the formats you want to upload
				$mime          		= mime_content_type($_FILES['profile_pic']['tmp_name']);
				if ($_FILES["profile_pic"]["name"] != "") {
					if ((($_FILES["profile_pic"]["type"] == "image/gif")
							|| ($_FILES["profile_pic"]["type"] == "image/jpeg")
							|| ($_FILES["profile_pic"]["type"] == "image/jpg")
							|| ($_FILES["profile_pic"]["type"] == "image/pjpeg")
							|| ($_FILES["profile_pic"]["type"] == "image/x-png")
							|| ($_FILES["profile_pic"]["type"] == "image/png"))
						&& in_array($extension, $allowedExts) &&  in_array($mime, $valid_formats)
					) {;
					} else {
						$error['msg'] = "Invalid Picture format, Please choose only gif, jpeg, jpg or png Picture";
					}
				}
			}
		} else {

			if (isset($phone_no) && $phone_no == "") {
				$error['msg'] 	= "Please Enter Mobile No";
				$phone_no_valid = "invalid";
			}
			if (isset($email) && $email == "") {
				$error['msg'] 	= "Please Enter Email";
				$email_valid = "invalid";
			}
			if (isset($last_name) && $last_name == "") {
				$error['msg'] 	= "Please Enter Last Name";
				$last_name_valid = "invalid";
			}
			if (isset($first_name) && $first_name == "") {
				$error['msg'] 	= "Please Enter First Name";
				$first_name_valid = "invalid";
			}
		}
		if (empty($error)) {
			if (is_array($_FILES) && isset($_FILES["profile_pic"]["name"]) && $_FILES["profile_pic"]["name"] != "") {
				$picture_uniq_id = $_SESSION['user_id'] . "_" . uniqid();
				$allowedExts = array("gif", "GIF", "jpeg", "JPEG", "JPG", "jpg", "png", "PNG");
				$temp = explode(".", $_FILES["profile_pic"]["name"]);
				$extension = end($temp);
				$valid_formats  	= array("image/JPEG", "image/jpeg", "image/JPG", "image/jpg", "image/PNG", "image/png", "image/gif", "image/GIF"); //add the formats you want to upload
				$mime          		= mime_content_type($_FILES['profile_pic']['tmp_name']);
				if ($_FILES["profile_pic"]["name"] != "") {
					if ((($_FILES["profile_pic"]["type"] == "image/gif")
							|| ($_FILES["profile_pic"]["type"] == "image/jpeg")
							|| ($_FILES["profile_pic"]["type"] == "image/jpg")
							|| ($_FILES["profile_pic"]["type"] == "image/pjpeg")
							|| ($_FILES["profile_pic"]["type"] == "image/x-png")
							|| ($_FILES["profile_pic"]["type"] == "image/png"))
						&& in_array($extension, $allowedExts) &&  in_array($mime, $valid_formats)
					) {
						$sourcePath			= $_FILES['profile_pic']['tmp_name'];
						$profile_pic 		= $picture_uniq_id . "." . $extension;
						$targetPath 		= "app-assets/images/logo/" . $profile_pic;
						if (move_uploaded_file($sourcePath, $targetPath)) {
							$sql_upd 	= "UPDATE users SET profile_pic = '" . $profile_pic . "'  WHERE id = " . $user_id . " ";
							$db->query($conn, $sql_upd);
						}
					} else {
						$error['msg'] = "Invalid Picture format, Please choose only gif, jpeg, jpg or png Picture";
					}
				}
			}
			if ($_SESSION['user_type'] == 'Admin') {
				$sql_c_up = "UPDATE subscribers_users SET 	company_name	= '" . $company_name . "',
															s_address		= '" . $s_address . "', 
															about_desc		= '" . $about_desc . "', 

															update_date		= '" . $add_date . "',
															update_by		= '" . $_SESSION['username'] . "',
															update_ip		= '" . $add_ip . "'
							WHERE id = '" . $subscriber_users_id . "' ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {

					$sql_c_up = "UPDATE users
										SET phone_no		= '" . $phone_no . "',
										update_date 		= '" . $add_date . "',
										update_by 			= '" . $_SESSION['username'] . "',
										update_ip 			= '" . $add_ip . "'
								WHERE id = '" . $user_id . "' AND subscriber_users_id = '" . $subscriber_users_id . "' ";
					$ok = $db->query($conn, $sql_c_up);
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
				}
			} else {
				$sql_c_up = "UPDATE users b SET b.first_name 			= '" . $first_name . "',
												b.last_name 			= '" . $last_name . "',
												b.phone_no 				= '" . $phone_no . "',
												b.email 				= '" . $email . "',
												b.update_date 			= '" . $add_date . "',
												b.update_by 			= '" . $_SESSION['username'] . "',
												b.update_ip 			= '" . $add_ip . "' 
							WHERE b.id  = '" . $user_id . "' AND b.subscriber_users_id = '" . $subscriber_users_id . "' ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
				}
			}
		}
	}
} else {
	$sql_ee = "	SELECT a.*, b.company_name, b.about_desc, b.s_address
				FROM users a
				INNER JOIN subscribers_users b ON b.id = a.subscriber_users_id
				WHERE a.id = '" . $user_id . "'
				AND b.id = '" . $subscriber_users_id . "' ";
	$result_ee 						= $db->query($conn, $sql_ee);
	$row_ee 						= $db->fetch($result_ee);
	$first_name 					= $row_ee[0]['first_name'];
	$last_name 						= $row_ee[0]['last_name'];
	$username 						= $row_ee[0]['username'];
	$email 							= $row_ee[0]['email'];
	$phone_no 						= $row_ee[0]['phone_no'];
	$s_address 						= $row_ee[0]['s_address'];
	$company_name 					= $row_ee[0]['company_name'];
	$about_desc 					= $row_ee[0]['about_desc'];
	$profile_pic					= $row_ee[0]['profile_pic'];
	if ($profile_pic == "") {
		$profile_pic = "no_image.png";
	}
	$email_verification_status 	= $row_ee[0]['email_verification_status'];
	$phone_verification_status 	= $row_ee[0]['phone_verification_status'];
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
					<form method="post" autocomplete="off" enctype="multipart/form-data">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																			echo encrypt($_SESSION['csrf_session']);
																		} ?>">

						<div class="row">
							<div class="input-field col m4 s12">
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="first_name" <?php if ($_SESSION['user_type'] == 'Admin') { ?> readonly <?php } ?> type="text" name="first_name" value="<?php if (isset($first_name)) {
																																										echo $first_name;
																																									} ?>">
								<label for="first_name">First Name</label>
							</div>
							<div class="input-field col m4 s12">
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="last_name" <?php if ($_SESSION['user_type'] == 'Admin') { ?> readonly <?php } ?> type="text" name="last_name" value="<?php if (isset($last_name)) {
																																									echo $last_name;
																																								} ?>">
								<label for="last_name">Last Name</label>
							</div>
							<div class="input-field col m4 s12">
								<i class="material-icons prefix pt-2">mail_outline</i>
								<input id="email" <?php if ($_SESSION['user_type'] == 'Admin') { ?> readonly <?php } ?> type="text" name="email" value="<?php if (isset($email)) {
																																							echo $email;
																																						} ?>">
								<label for="email">Email</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<i class="material-icons prefix pt-2">phone</i>
								<input id="phone_no" type="text" name="phone_no" value="<?php if (isset($phone_no)) {
																							echo $phone_no;
																						} ?>">
								<label for="phone_no">Mobile No</label>
							</div>
							<div class="input-field col m4 s12">
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="username" type="text" readonly name="username" value="<?php if (isset($username)) {
																										echo $username;
																									} ?>">
								<label for="username">Username</label>
							</div>
							<?php if ($_SESSION['user_type'] == 'Admin') { ?>
								<div class="input-field col m4 s12">
									<i class="material-icons prefix">school</i>
									<input id="company_name" type="text" name="company_name" value="<?php if (isset($company_name)) {
																										echo $company_name;
																									} ?>">
									<label for="company_name">Company Name</label>
								</div>
							<?php } ?>
						</div>
						<?php if ($_SESSION['user_type'] == 'Admin') { ?>
							<div class="row">
								<div class="input-field col m6 s12">
									<i class="material-icons prefix">map</i>
									<input id="s_address" type="text" name="s_address" value="<?php if (isset($s_address)) {
																									echo $s_address;
																								} ?>">
									<label for="s_address">Address</label>
								</div>
								<div class="input-field col m6 s12">
									<i class="material-icons prefix">description</i>
									<textarea id="about_desc" class="materialize-textarea" name="about_desc"><?php if (isset($about_desc)) {
																													echo $about_desc;
																												} ?></textarea>
									<label for="about_desc">Description About</label>
								</div>
							</div>
						<?php } ?>
						<div id="file-upload" class="section">
							<!--Default version-->
							<div class="row section">
								<div class="col s2 m1 2">
									<br><br>
									<b>Change Logo</b>
								</div>
								<div class="col s5 m5 5">
									<input type="file" name="profile_pic" id="input-file-now" class="dropify" data-default-file="" />
								</div>
								<div class="col s15 m5 5">
									<div class="dropify-wrapper disabled has-preview">
										<div class="dropify-message">
											<span class="file-icon"></span>
											<p>Drag and drop a file here or click</p>
											<p class="dropify-error">Ooops, something wrong appended.</p>
										</div>
										<div class="dropify-loader" style="display: none;"></div>
										<div class="dropify-errors-container">
											<ul></ul>
										</div>
										<input type="file" id="input-file-now-disabled-2" class="dropify" disabled="disabled" data-default-file="<?php echo $directory_path; ?>app-assets/images/gallery/1.png">
										<div class="dropify-preview" style="display: block;">
											<span class="dropify-render">
												<img src="<?php echo $directory_path; ?>app-assets/images/logo/<?php echo $profile_pic; ?>">
											</span>
											<div class="dropify-infos">
												<div class="dropify-infos-inner">
													<p class="dropify-filename">
														<span class="file-icon"></span>
														<span class="dropify-filename-inner"><?php echo $profile_pic; ?></span>
													</p>
													<p class="dropify-infos-message">Drag and drop or click to replace</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="divider mb-1 mt-1"></div>
						</div>
						<div class="row">
							<div class="row">
								<div class="input-field col m4 s12"></div>
								<div class="input-field col m4 s12">
									<?php if (access("edit_perm") == 1) { ?>
										<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?>
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