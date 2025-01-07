<?php

if ($cmd == 'add') {
	$title_heading 	= "Create Subscriber";
	$button_val 	= "Create";
	$id 			= "";
}

if (isset($test_on_local) && $test_on_local == 1) {
	$username 					= "azadmin2";
	$a_password 				= "azadmin2";
	$first_name 				= "aftab";
	$middle_name 				= "ali";
	$last_name 					= "tunio";
	$email 						= "aftabatunio@gmail.com";
	$s_address 					= "s_address";
	$phone_no 					= "3333333333";
	$company_name 				= "Company nnnnname";
	$starting_date 				= "15/04/2021";
	$closing_date 				= "15/04/2024";
	$about_desc					= "about_desc about_desc about_desc about_desc about_desc about_desc ";
	$email_send 				= 0;
	$sms_send					= 0;
} else {
	$email_send		= 1;
	$sms_send		= 1;
}
$company_logo		= "no_image.png";

$authorizied_doc	= "";
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_sadmin_directory_access();
}
$db = new mySqlDB;
if ($cmd == 'edit') {
	$title_heading = "Edit Subscriber";
	$button_val = "Update";
}
if (isset($cmd_detail) && $cmd_detail == 'delete') {
	$sql_ee2 	= " DELETE FROM  user_roles WHERE id = '" . $detail_id . "' ";
	$ok_del = $db->query($conn, $sql_ee2);
	if ($ok_del) {
		$msg['msg_success'] = "Role has been removed.";
	}
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee 	= "	SELECT a.*,a2.username, a2.a_password, a2.first_name, a2.middle_name, a2.last_name, a2.email, a2.phone_no, a2.id AS user_id
					FROM subscribers_users a 
					INNER JOIN users a2 ON a2.subscriber_users_id = a.id AND a2.user_type = 'Admin'
					WHERE a.id = '" . $id . "' "; // echo $sql_ee;die;
	$result_ee 	= $db->query($conn, $sql_ee);
	$row_ee 	= $db->fetch($result_ee);
	$email 						= $row_ee[0]['email'];
	$subscribers_users_username	= $row_ee[0]['username'];
	$s_address 					= $row_ee[0]['s_address'];
	$phone_no 					= $row_ee[0]['phone_no'];
	$company_name 				= $row_ee[0]['company_name'];
	$reg_status 				= $row_ee[0]['reg_status'];
	$about_desc 				= $row_ee[0]['about_desc'];
	$company_logo				= $row_ee[0]['company_logo'];
	$school_add_by				= $row_ee[0]['add_by'];

	$username					= $row_ee[0]['username'];
	$a_password					= $row_ee[0]['a_password'];

	$first_name					= $row_ee[0]['first_name'];
	$middle_name				= $row_ee[0]['middle_name'];
	$last_name					= $row_ee[0]['last_name'];
	$user_id					= $row_ee[0]['user_id'];

	if ($company_logo == "") {
		$company_logo = "no_image.png";
	}
	$email_verification_status 	= $row_ee[0]['email_verification_status'];
	$phone_verification_status 	= $row_ee[0]['phone_verification_status'];
	$starting_date 				= str_replace("-", "/", convert_date_display($row_ee[0]['starting_date']));
	$closing_date 				= str_replace("-", "/", convert_date_display($row_ee[0]['closing_date']));
	$reg_date 					= str_replace("-", "/", convert_date_display($row_ee[0]['reg_date']));
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}

if (isset($is_Submit2) && $is_Submit2 == 'Y') {
	if (isset($add_role_id) && $add_role_id == "") {
		$error['msg'] 	= "Please Select Role";
	}
	if (empty($error)) {
		$sql1_2 		= "	SELECT * FROM user_roles WHERE  subscriber_users_id = '" . $id . "'  AND role_id = '" . $add_role_id . "' ";
		$result1_2 	= $db->query($conn, $sql1_2);
		$count2_2 	= $db->counter($result1_2);
		if ($count2_2 > 0) {
			$error['msg'] = "Sorry! This Role is already exist";
		} else {
			$sql1_1 = "INSERT INTO user_roles (subscriber_users_id, role_id, add_date, add_by, add_ip)
						VALUES('" . $id . "', '" . $add_role_id . "', '" . $add_date . "', '" . $_SESSION['username_super_admin'] . "', '" . $add_ip . "')";
			$ok = $db->query($conn, $sql1_1);
			if ($ok) {
				$msg['msg_success'] = "Role has been assigned.";
				$add_role_id 	= "";
			} else {
				$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
			}
		}
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	if ($cmd == 'add') {
		$sql1 		= "	SELECT * FROM users WHERE username = '" . $username . "' ";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['msg'] 	= "Sorry! This username is already exist, try another.";
			$username_valid	= "invalid";
		}
		$sql1 		= "	SELECT * FROM users WHERE email = '" . $email . "' ";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['msg'] 	= "Sorry! This Email is already exist, try another.";
			$email_valid	= "invalid";
		}
		$sql1 		= "	SELECT * FROM users WHERE phone_no = '" . $phone_no . "'";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['msg'] 	= "Sorry! This Mobile No is already exist, try another.";
			$phone_no_valid	= "invalid";
		}
	}
	if (is_array($_FILES) && isset($_FILES["company_logo"]["name"]) && $_FILES["company_logo"]["name"] != "") {
		$allowedExts = array("gif", "GIF", "jpeg", "JPEG", "JPG", "jpg", "png", "PNG");
		$temp = explode(".", $_FILES["company_logo"]["name"]);
		$extension = end($temp);
		$valid_formats  	= array("image/JPEG", "image/jpeg", "image/JPG", "image/jpg", "image/PNG", "image/png", "image/gif", "image/GIF"); //add the formats you want to upload
		$mime          		= mime_content_type($_FILES['company_logo']['tmp_name']);
		if ($_FILES["company_logo"]["name"] != "") {
			if ((($_FILES["company_logo"]["type"] == "image/gif")
					|| ($_FILES["company_logo"]["type"] == "image/jpeg")
					|| ($_FILES["company_logo"]["type"] == "image/jpg")
					|| ($_FILES["company_logo"]["type"] == "image/pjpeg")
					|| ($_FILES["company_logo"]["type"] == "image/x-png")
					|| ($_FILES["company_logo"]["type"] == "image/png"))
				&& in_array($extension, $allowedExts) &&  in_array($mime, $valid_formats)
			) {;
			} else {
				$error['msg'] = "Invalid Picture format, Please choose only gif, jpeg, jpg or png Picture";
			}
		}
	}
	if (isset($a_password) && strlen($a_password) < 6) {
		$error['msg'] 	= "Password should be greater than 5 characters.";
		$a_password_valid = "invalid";
	}
	if (isset($a_password) && $a_password == "") {
		$error['msg'] 	= "Please Enter Password";
		$a_password_valid = "invalid";
	}
	if (isset($username) && $username == "") {
		$error['msg'] 	= "Please Enter Username";
		$username_valid = "invalid";
	}
	if (isset($email) && $email == "") {
		$error['msg'] 	= "Please Enter Email";
		$email_valid = "invalid";
	}
	if (isset($s_address) && $s_address == "") {
		$error['msg'] 	= "Please Enter Address";
		$s_address_valid = "invalid";
	}
	if (isset($phone_no) && $phone_no == "") {
		$error['msg'] 	= "Please Enter Phone No";
		$phone_no_valid = "invalid";
	}
	if (isset($company_name) && $company_name == "") {
		$error['msg'] 	= "Please Enter Company Name";
		$company_name_valid = "invalid";
	}
	if (isset($last_name) && $last_name == "") {
		$error['msg'] 	= "Please Enter Last Name";
		$last_name_valid = "invalid";
	}
	if (isset($first_name) && $first_name == "") {
		$error['msg'] 	= "Please Enter First Name";
		$first_name_valid = "invalid";
	}

	$starting_date1 			= "0000-00-00";
	$closing_date1 				= "0000-00-00";

	if (isset($starting_date) && $starting_date != "") {
		$starting_date1 	= convert_date_mysql_slash($starting_date);
	}
	if (isset($closing_date) && $closing_date != "") {
		$closing_date1 	= convert_date_mysql_slash($closing_date);
	}
	if (empty($error)) {
		if ($cmd == 'add') {

			$uniq_pass 			= uniqid();
			$current_reg_date 	= date('Y-m-d');
			$sql6 = "INSERT INTO subscribers_users (s_address, company_name, reg_status,  starting_date, closing_date, reg_date, about_desc, add_date, add_by, add_ip)
				VALUES( '" . $s_address . "', '" . $company_name . "', '2', '" . $starting_date1 . "', '" . $closing_date1 . "', '" . $current_reg_date . "', '" . $about_desc . "', '" . $add_date . "', '" . $_SESSION['username_super_admin'] . "', '" . $add_ip . "')";
			$ok = $db->query($conn, $sql6);
			if ($ok) {
				$subscriber_users_id 	= mysqli_insert_id($conn);
				if (is_array($_FILES) && isset($_FILES["company_logo"]["name"]) && $_FILES["company_logo"]["name"] != "") {
					$picture_uniq_id = $subscriber_users_id . "_" . uniqid();
					$allowedExts = array("gif", "GIF", "jpeg", "JPEG", "JPG", "jpg", "png", "PNG");
					$temp = explode(".", $_FILES["company_logo"]["name"]);
					$extension = end($temp);
					$valid_formats  	= array("image/JPEG", "image/jpeg", "image/JPG", "image/jpg", "image/PNG", "image/png", "image/gif", "image/GIF"); //add the formats you want to upload
					$mime          		= mime_content_type($_FILES['company_logo']['tmp_name']);
					if ($_FILES["company_logo"]["name"] != "") {
						if ((($_FILES["company_logo"]["type"] == "image/gif")
								|| ($_FILES["company_logo"]["type"] == "image/jpeg")
								|| ($_FILES["company_logo"]["type"] == "image/jpg")
								|| ($_FILES["company_logo"]["type"] == "image/pjpeg")
								|| ($_FILES["company_logo"]["type"] == "image/x-png")
								|| ($_FILES["company_logo"]["type"] == "image/png"))
							&& in_array($extension, $allowedExts) &&  in_array($mime, $valid_formats)
						) {
							$sourcePath			= $_FILES['company_logo']['tmp_name'];
							$company_logo 		= $picture_uniq_id . "." . $extension;
							$targetPath 		= "../app-assets/images/logo/" . $company_logo;
							if (move_uploaded_file($sourcePath, $targetPath)) {
								$sql_upd 	= "UPDATE subscribers_users SET company_logo = '" . $company_logo . "' WHERE id = " . $subscriber_users_id . " ";
								$db->query($conn, $sql_upd);
							}
						} else {
							$error['msg'] = "Invalid Picture format, Please choose only gif, jpeg, jpg or png Picture";
						}
					}
				}
				$sql = "INSERT INTO users (subscriber_users_id, email, phone_no, username, a_password, user_type, first_name, middle_name, last_name, reg_status, add_ip, add_date, add_by)
						VALUES('" . $subscriber_users_id . "', '" . $email . "', '" . $phone_no . "', '" . $username . "', '" . $a_password . "', 'Admin',
								'" . $first_name . "', '" . $middle_name . "', '" . $last_name . "', 2,  '" . $add_ip . "', '" . $add_date . "', '" . $_SESSION["username_super_admin"] . "') "; //echo $sql;die;
				$db->query($conn, $sql);
				$user_id 	= mysqli_insert_id($conn);

				$sql = "INSERT INTO users_history(user_id, email, phone_no, username, a_password, user_type, first_name, middle_name, last_name, reg_status, add_ip, add_date, add_by)
						VALUES('" . $user_id . "', '" . $email . "', '" . $phone_no . "', '" . $username . "', '" . $a_password . "', 'Admin',
								'" . $first_name . "', '" . $middle_name . "', '" . $last_name . "', 2,  '" . $add_ip . "', '" . $add_date . "', '" . $_SESSION["username_super_admin"] . "') "; //echo $sql;die;
				$db->query($conn, $sql);

				/// Default First Role ////
				$sql1_2 		= "	SELECT * FROM user_roles WHERE  subscriber_users_id = '" . $subscriber_users_id . "'  ";
				$result1_2 	= $db->query($conn, $sql1_2);
				$count2_2 	= $db->counter($result1_2);
				if ($count2_2 == 0) {
					$sql1_1 = "INSERT INTO user_roles (subscriber_users_id, role_id, add_date, add_by, add_ip)
						VALUES('" . $subscriber_users_id . "', '1', '" . $add_date . "', '" . $_SESSION['username_super_admin'] . "', '" . $add_ip . "')";
					$db->query($conn, $sql1_1);
				}
				require '../sendGrid/vendor/autoload.php';
				$subject_to = "Your account For " . $project_name . " has been Created - " . $project_domain;
				$toEmail 	= $email;
				$toname 	= $first_name;
				$body = "<b>Dear, " . $first_name . "</b>";
				$body .= "<br><br> Your account for " . $project_name . " has been created.<br><br>Please note URL and login details.<br><br>";
				$body .= "<b>URL: </b><a href='" . PROJECT_URL . "' target='_blank'>" . PROJECT_URL . "</a><br>";
				$body .= "<b>Username: </b>" . $username . "<br>";
				$body .= "<b>Password: </b>" . $a_password . "<br>";
				$body .= "<br><b>Regards<br>Team " . FROMNAME . "</b>";
				$body .= "<br><br>";
				$parm1 = "";
				$parm2 = "";
				$parm3 = "";
				$parm4 = "";
				$parm5 = "";
				$parm6 = "";
				if ($email_send == 1) {
					sendEmailSendGrid($subject_to, $toEmail, $toname, $body, $parm1, $parm2, $parm3, $parm4, $parm5, $parm6);
				}
				if ($sms_send == 1) {
					$msg_content = "Dear " . $first_name . ", \n\nYour account at " . $project_domain . " has been has created.\n";
					$msg_content .= "Please note system URL and login details.\n";
					$msg_content .= "Now your admin can login with following details and can use the system.\n\n";
					$msg_content .= "URL: " . PROJECT_URL . "\n";
					$msg_content .= "Username: " . $username . "\n";
					$msg_content .= "Password: " . $a_password . "\n\n";
					$msg_content .= "Regards\nTeam " . FROMNAME;
					sendSMS_MainSite($db, $conn, $username, $phone_no, $msg_content, 'Super Admin  Creation to Subscriber Account');
				}
				$msg['msg_success'] = "Account has been created Successfully. Please go to edit mode and assign the role to this user.";
			} else {
				$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
			}
		} else if ($cmd == 'edit') {
			//////////////////////////////// Logo Validation END///////////////////////////

			$sql3 		= "	SELECT * FROM users WHERE username = '" . $username . "' AND id != '" . $user_id . "' ";
			$result3 	= $db->query($conn, $sql3);
			$count3 	= $db->counter($result3);

			$sql4 		= "	SELECT * FROM users WHERE email = '" . $email . "' AND id != '" . $user_id . "' ";
			$result4 	= $db->query($conn, $sql4);
			$count4 	= $db->counter($result4);

			$sql5 		= "	SELECT * FROM users WHERE phone_no = '" . $phone_no . "' AND id != '" . $user_id . "' ";
			$result5 	= $db->query($conn, $sql5);
			$count5 	= $db->counter($result5);

			if ($count5 > 0) {
				$error['msg'] 	= "Sorry! This Phone is already exist, try another.";
				$phone_no_valid	= "invalid";
			} else if ($count3 > 0) {
				$error['msg'] 	= "Sorry! This Username is already exist, try another.";
				$username_valid	= "invalid";
			} else if ($count4 > 0) {
				$error['msg'] 	= "Sorry! This Email is already exist, try another.";
				$email_valid	= "invalid";
			} else {
				$sql_c_up = "UPDATE subscribers_users SET 	company_name 		= '" . $company_name . "',
															s_address 			= '" . $s_address . "',
															reg_status 			= '" . $reg_status . "',
															about_desc 			= '" . $about_desc . "',
															starting_date 		= '" . $starting_date1 . "',
															closing_date 		= '" . $closing_date1 . "',
															update_date 		= '" . $add_date . "',
															update_by 			= '" . $_SESSION['username_super_admin'] . "',
															update_ip 			= '" . $add_ip . "'
							WHERE id = '" . $id . "' ";
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					if (is_array($_FILES) && isset($_FILES["company_logo"]["name"]) && $_FILES["company_logo"]["name"] != "") {
						$picture_uniq_id = $id . "_" . uniqid();
						$allowedExts = array("gif", "GIF", "jpeg", "JPEG", "JPG", "jpg", "png", "PNG");
						$temp = explode(".", $_FILES["company_logo"]["name"]);
						$extension = end($temp);
						$valid_formats  	= array("image/JPEG", "image/jpeg", "image/JPG", "image/jpg", "image/PNG", "image/png", "image/gif", "image/GIF"); //add the formats you want to upload
						$mime          		= mime_content_type($_FILES['company_logo']['tmp_name']);
						if ($_FILES["company_logo"]["name"] != "") {
							if ((($_FILES["company_logo"]["type"] == "image/gif")
									|| ($_FILES["company_logo"]["type"] == "image/jpeg")
									|| ($_FILES["company_logo"]["type"] == "image/jpg")
									|| ($_FILES["company_logo"]["type"] == "image/pjpeg")
									|| ($_FILES["company_logo"]["type"] == "image/x-png")
									|| ($_FILES["company_logo"]["type"] == "image/png"))
								&& in_array($extension, $allowedExts) &&  in_array($mime, $valid_formats)
							) {
								$sourcePath			= $_FILES['company_logo']['tmp_name'];
								$company_logo 		= $picture_uniq_id . "." . $extension;
								$targetPath 		= "../app-assets/images/logo/" . $company_logo;
								if (move_uploaded_file($sourcePath, $targetPath)) {
									$sql_upd 	= "UPDATE subscribers_users SET company_logo = '" . $company_logo . "' WHERE id = " . $id . " ";
									$db->query($conn, $sql_upd);
								}
							} else {
								$error['msg'] = "Invalid Picture format, Please choose only gif, jpeg, jpg or png Picture";
							}
						}
					}

					$sql = "UPDATE users  SET 	
												username 			= '" . $username . "',
												email 				= '" . $email . "',
												phone_no 			= '" . $phone_no . "',
												reg_status 			= '" . $reg_status . "',
												a_password 			= '" . $a_password . "',
												first_name 			= '" . $first_name . "',
												middle_name 		= '" . $middle_name . "',
												last_name 			= '" . $last_name . "',
												update_date 		= '" . $add_date . "',
												update_by 			= '" . $_SESSION['username_super_admin'] . "',
												update_ip 			= '" . $add_ip . "'
							WHERE id = '" . $user_id . "' AND subscriber_users_id = '" . $id . "' ";
					$db->query($conn, $sql);

					$sql = "INSERT INTO users_history(user_id, email, phone_no, username, a_password, first_name, middle_name, last_name, reg_status, add_ip, add_date, add_by)
							VALUES('" . $user_id . "', '" . $email . "', '" . $phone_no . "', '" . $username . "', '" . $a_password . "',  
									'" . $first_name . "', '" . $middle_name . "', '" . $last_name . "', '" . $reg_status . "', '" . $add_ip . "', '" . $add_date . "', '" . $_SESSION["username_super_admin"] . "') "; //echo $sql;die;
					$db->query($conn, $sql);
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
				}
			}
		}
		if ($prev_reg_status != $reg_status && $reg_status == '2' && $cmd == "edit") {

			/// Default First Role ////
			$sql1_2 		= "	SELECT * FROM user_roles WHERE  subscriber_users_id = '" . $id . "'  ";
			$result1_2 	= $db->query($conn, $sql1_2);
			$count2_2 	= $db->counter($result1_2);
			if ($count2_2 == 0) {
				$sql1_1 = "INSERT INTO user_roles (subscriber_users_id, role_id, add_date, add_by, add_ip)
					VALUES('" . $id . "', '1', '" . $add_date . "', '" . $_SESSION['username_super_admin'] . "', '" . $add_ip . "')";
				$db->query($conn, $sql1_1);
			}

			require '../sendGrid/vendor/autoload.php';
			$subject_to = "Your account For " . $project_name . " has been approved - " . $project_domain;
			$toEmail 	= $email;
			$toname 	= $first_name;
			$body = "<b>Dear, " . $first_name . "</b>";
			$body .= "<br><br> Your account for " . $project_name . " has been approved.<br><br>Please note URL and login details.<br><br>";
			$body .= "<b>URL: </b><a href='" . PROJECT_URL . "' target='_blank'>" . PROJECT_URL . "</a><br>";
			$body .= "<b>Username: </b>" . $username . "<br>";
			$body .= "<b>Password: </b>" . $a_password . "<br>";
			$body .= "<br><b>Regards<br>Team " . FROMNAME . "</b>";
			$body .= "<br><br>";
			$parm1 = "";
			$parm2 = "";
			$parm3 = "";
			$parm4 = "";
			$parm5 = "";
			$parm6 = "";
			if ($email_send == 1) {
				sendEmailSendGrid($subject_to, $toEmail, $toname, $body, $parm1, $parm2, $parm3, $parm4, $parm5, $parm6);
			}
			if ($sms_send == 1) {
				$msg_content = "Dear " . $first_name . ", \n\nYour account at " . $project_domain . " has been has approved.\n";
				$msg_content .= "Please note system URL and login details.\n";
				$msg_content .= "Now your admin can login with following details and can use the system.\n\n";
				$msg_content .= "URL: " . PROJECT_URL . "\n";
				$msg_content .= "Username: " . $username . "\n";
				$msg_content .= "Password: " . $a_password . "\n\n";
				$msg_content .= "Regards\nTeam " . FROMNAME;
				sendSMS_MainSite($db, $conn, $username, $phone_no, $msg_content, 'Super Admin  Creation to Subscriber Account');
			}
		} else if ($prev_reg_status != $reg_status && $reg_status != '2' && $cmd == "edit") {
			$sql_st 		= "	SELECT * FROM user_reg_status WHERE id = '" . $reg_status . "'";
			$result_st 		= $db->query($conn, $sql_st);
			$count_st 		= $db->counter($result_st);
			if ($count_st > 0) {
				$row_st 			= $db->fetch($result_st);
				$reg_status_email 	= $row_st[0]['reg_status'];
				require '../sendGrid/vendor/autoload.php';
				$subject_to = "Your account has been " . $reg_status_email . " - " . $project_domain;
				$toEmail 	= $email;
				$toname 	= $first_name;
				$body = "<b>Dear " . $first_name . ", </b>";
				$body .= "<br><br> Your account has been " . $reg_status_email . ". 
						 <br><br> Please contact <b>" . $project_name . "</b> administration for further detail.<br>";
				$body .= "<br><b>Regards<br>Team " . FROMNAME . "</b>";
				$body .= "<br><br>";
				$parm1 = "";
				$parm2 = "";
				$parm3 = "";
				$parm4 = "";
				$parm5 = "";
				$parm6 = "";
				if ($email_send == 1) {
					sendEmailSendGrid($subject_to, $toEmail, $toname, $body, $parm1, $parm2, $parm3, $parm4, $parm5, $parm6);
				}

				if ($sms_send == 1) {
					$msg_content = "Dear " . $first_name . ", \n\nYour account at " . $project_domain . " has been has been " . $reg_status_email . "\n";
					$msg_content .= "Please contact " . $project_name . " administration for further detail.\n\n";
					$msg_content .= "Regards\nTeam " . FROMNAME;
					sendSMS_MainSite($db, $conn, $username, $phone_no, $msg_content, 'Super Admin School Approval');
				}
			}
		}
		if (empty($error) && $cmd == 'add') {
			$username 					= "";
			$a_password 				= "";
			$first_name 				= "";
			$middle_name 				= "";
			$last_name 					= "";
			$email 						= "";
			$s_address 					= "";
			$phone_no 					= "";
			$company_name 				= "";
			$starting_date 				= "";
			$closing_date 				= "";
			$about_desc					= "";
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
					<div class="row">
						<div class="col s10 m6 l6">
							<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $title_heading; ?></span></h5>
							<ol class="breadcrumbs mb-0">
								<li class="breadcrumb-item"><?php echo $title_heading; ?>
								</li>
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">List</a>
								</li>
							</ol>
						</div>
						<div class="col s2 m6 l6">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
								List
							</a>
						</div>
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
					<h4 class="card-title">Detail Form</h4>
					<form method="post" autocomplete="off" enctype="multipart/form-data">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
						<input type="hidden" name="prev_reg_status" value="<?php if (isset($reg_status)) echo $reg_status; ?>" />
						<input type="hidden" name="user_id" value="<?php if (isset($user_id)) echo $user_id; ?>" />

						<div class="row">
							<div class="input-field col m4 s12">
								<input id="first_name" type="text" name="first_name" class="validate <?php if (isset($first_name_valid)) {
																											echo $first_name_valid;
																										} ?>" required="" value="<?php if (isset($first_name)) {
																																		echo $first_name;
																																	} ?>">
								<label for="first_name">Admin First Name</label>
							</div>
							<div class="input-field col m4 s12">
								<input id="middle_name" type="text" name="middle_name" class="validate <?php if (isset($middle_name_valid)) {
																											echo $$middle_name_valid;
																										} ?>" value="<?php if (isset($middle_name)) {
																															echo $middle_name;
																														} ?>">
								<label for="middle_name">Admin Middle Name</label>
							</div>
							<div class="input-field col m4 s12">
								<input id="last_name" type="text" name="last_name" class="validate <?php if (isset($last_name_valid)) {
																										echo $last_name_valid;
																									} ?>" required="" value="<?php if (isset($last_name)) {
																																	echo $last_name;
																																} ?>">
								<label for="last_name">Admin Last Name</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<input id="company_name" type="text" name="company_name" class="validate <?php if (isset($company_name_valid)) {
																												echo $company_name_valid;
																											} ?>" required="" value="<?php if (isset($company_name)) {
																																			echo $company_name;
																																		} ?>">
								<label for="company_name">Company Name</label>
							</div>
							<div class="input-field col m3 s12">
								<?php if (isset($phone_verification_status) && $phone_verification_status == 'Verified') { ?>
									<i class="material-icons prefix">check</i>
								<?php } ?>
								<input id="phone_no" type="text" name="phone_no" class="validate <?php if (isset($phone_no_valid)) {
																										echo $phone_no_valid;
																									} ?>" required="" value="<?php if (isset($phone_no)) {
																																	echo $phone_no;
																																} ?>">
								<label for="phone_no">Phone No</label>
							</div>
							<div class="input-field col m5 s12">
								<input id="s_address" type="text" name="s_address" class="validate <?php if (isset($s_address_valid)) {
																										echo $s_address_valid;
																									} ?>" value="<?php if (isset($s_address)) {
																														echo $s_address;
																													} ?>">
								<label for="s_address">Address</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<?php
								if (isset($email_verification_status) && $email_verification_status == 'Verified') { ?>
									<i class="material-icons prefix">check</i>
								<?php } ?>
								<input id="email" type="email" name="email" required="" value="<?php if (isset($email)) {
																									echo $email;
																								} ?>" class="validate <?php if (isset($email_valid)) {
																															echo $email_valid;
																														} ?>">
								<label for="email">Admin Email</label>
							</div>
							<div class="input-field col m4 s12">
								<input id="username" type="text" required="" name="username" value="<?php if (isset($username)) {
																										echo $username;
																									} ?>" class="validate <?php if (isset($username_valid)) {
																																echo $username_valid;
																															} ?>">
								<label for="username">Username</label>
							</div>
							<div class="input-field col m4 s12">
								<input id="a_password" type="password" name="a_password" required="" value="<?php if (isset($a_password)) {
																												echo $a_password;
																											} ?>" class="validate <?php if (isset($a_password_valid)) {
																																		echo $a_password_valid;
																																	} ?>">
								<label for="a_password">Password</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m12 s12">
								<textarea id="about_desc" class="materialize-textarea validate <?php if (isset($about_desc_valid)) {
																									echo $about_desc_valid;
																								} ?>" name="about_desc"><?php if (isset($about_desc)) {
																															echo $about_desc;
																														} ?></textarea>
								<label for="about_desc">About Description</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m3 s12">
								<select id="reg_status" name="reg_status" class=" validate <?php if (isset($reg_status_valid)) {
																								echo $reg_status_valid;
																							} ?>">
									<?php
									$sql1 		= "SELECT * FROM user_reg_status WHERE enabled = 1 AND id != '6' ";
									if ($cmd == 'add') {
										$sql1 .= " AND id = 2";
									}
									$sql1 .= " ORDER BY id ";
									$result1 	= $db->query($conn, $sql1);
									$count1 	= $db->counter($result1);
									if ($count1 > 0) {
										$row1	= $db->fetch($result1);
										foreach ($row1 as $data) { ?>
											<option value="<?php echo $data['id']; ?>" <?php if (isset($reg_status) && $reg_status == $data['id']) { ?> selected="selected" <?php } ?>><?php echo $data['reg_status']; ?></option>
									<?php }
									} ?>
								</select>
							</div>
							<div class="input-field col m2 s12">
								<input type="text" readonly id="reg_date" required="" name="reg_date" class=" validate <?php if (isset($reg_date_valid)) {
																															echo $reg_date_valid;
																														} ?>" value="<?php if (isset($reg_date)) {
																																			echo $reg_date;
																																		} else {
																																			echo date('d/m/Y');
																																		} ?>">
								<label for="reg_date">Registration Date</label>
							</div>
							<div class="input-field col m2 s12">
								<input type="text" class="datepicker validate <?php if (isset($starting_date_valid)) {
																					echo $starting_date_valid;
																				} ?>" id="starting_date" required="" name="starting_date" value="<?php if (isset($starting_date)) {
																																						echo $starting_date;
																																					} else {
																																						echo date('d/m/Y');
																																					} ?>">
								<label for="starting_date">Starting Date</label>
							</div>
							<div class="input-field col m2 s12">
								<input type="text" class="datepicker validate <?php if (isset($closing_date_valid)) {
																					echo $closing_date_valid;
																				} ?>" id="closing_date" required="" name="closing_date" value="<?php if (isset($closing_date)) {
																																					echo $closing_date;
																																				} else {
																																					$start_date1 	= date('Y-m-d');
																																					echo date('d/m/Y', strtotime('+1 year', strtotime($start_date1)));
																																				}
																																				?>">
								<label for="closing_date">Closing Date</label>
							</div>
						</div>
						<div id="file-upload" class="section">
							<!--Default version-->
							<div class="row section">
								<div class="col s12 m1 1">
									<br><br>
									<b>Change Logo</b>
								</div>
								<div class="col s12 m2 2">
									<input type="file" name="company_logo" id="input-file-now" class="dropify" data-default-file="" />
								</div>
								<div class="col s12 m3 3">
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
												<img src="<?php echo $directory_path; ?>app-assets/images/logo/<?php echo $company_logo; ?>">
											</span>
											<div class="dropify-infos">
												<div class="dropify-infos-inner">
													<p class="dropify-filename">
														<span class="file-icon"></span>
														<span class="dropify-filename-inner"><?php echo $company_logo; ?></span>
													</p>
													<p class="dropify-infos-message">Drag and drop or click to replace</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col m12 s12"><br><br></div>
							<div class="col m4 s12"></div>
							<div class="col m4 s12">
								<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action"><?php echo $button_val; ?>
								</button>
							</div>
							<div class="col m4 s12"></div>
							<div class="col m12 s12"><br><br></div>
						</div>
					</form>
				</div>
				<?php //include('sub_files/right_sidebar.php');
				?>
			</div>
		</div>
		<?php if ($cmd == 'edit') { ?>
			<div class="col s6 m6 l6">
				<div id="Form-advance" class="card card card-default scrollspy">
					<div class="card-content">
						<h4 class="card-title">Role Assign Entry</h4>
						<form method="post" action="">
							<input type="hidden" name="is_Submit2" value="Y" />
							<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
							<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<select required id="add_role_id" name="add_role_id" class="form-control <?php if (isset($error['add_role_id'])) {
																														echo 'is-warning';
																													} ?>">
											<option value="">Select Role</option>
											<?php //required
											$sql_c 		= " SELECT * FROM roles a WHERE a.enabled = 1   "; //echo $sql_c;
											$result_c 	= $db->query($conn, $sql_c);
											$row_c 		= $db->fetch($result_c);
											foreach ($row_c as $data) { ?>
												<option value="<?php echo $data['id']; ?>" <?php if (isset($add_role_id) && $add_role_id == $data['id']) { ?> selected="selected" <?php } ?>><?php echo $data['role_name']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<button class="btn cyan waves-effect waves-light right" type="submit" name="action">
											Add Role
											<i class="material-icons right">send</i>
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="col s12 m6 l12">
			<div id="Form-advance" class="card card card-default scrollspy">
				<?php
				if (isset($user_id) && $user_id > 0) {
					$sql_cl = "	SELECT b.*, a.role_name
								FROM roles a
								INNER JOIN user_roles b ON b.role_id = a.id
								WHERE a.enabled = 1 AND b.enabled = 1
								AND b.subscriber_users_id = '" . $id . "' ";
					//echo $sql_cl;
					$result_cl 	= $db->query($conn, $sql_cl);
					$count_cl 	= $db->counter($result_cl);
					if (isset($count_cl) && $count_cl > 0) { ?>
						<div class="card subscriber-list-card animate fadeRight">
							<div class="card-content pb-1">
								<h4 class="card-title mb-0">List of all Roles</h4>
							</div>
							<table class="subscription-table responsive-table highlight">
								<thead>
									<tr>
										<th>S.No</th>
										<th>Role Name</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$prev_patient_id = 0;
									$row_cl = $db->fetch($result_cl);
									$i = 0;
									foreach ($row_cl as $data2) {
										$i = $i + 1; ?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo ucwords(strtolower($data2['role_name'])); ?></td>
											<td>
												<a class="waves-effect waves-light  btn gradient-45deg-red-pink box-shadow-none border-round mr-1 mb-1" href="?string=<?php echo encrypt("module=" . $module . "&page=add&cmd=edit&id=" . $id . "&cmd_detail=delete&detail_id=" . $data2['id']) ?>" onclick="return confirm('Are you sure! You want to Remove this Role?')">
													Delete
												</a>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
				<?php }
				} ?>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->