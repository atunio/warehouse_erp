<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$first_name 				= "Aftab";
	$first_name 				= "Ahmed";
	$user_type 					= "Sub Users";
	$last_name 					= "Tunio";
	$email 						= "aftabatunio22a@gmail.com";
	$phone_no 					= "34343434";
	$username 					= "aftabtunio2";
	$a_password 				= "aftabtunio";
	$date_of_birth 				= "01/05/1992";
	$hourly_rate 				= 25;
}
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if ($cmd == 'edit') {
	$title_heading = "Update Sub User";
	$button_val = "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Create New Sub User";
	$button_val 	= "Create";
	$id 			= "";
}
if (access("delete_perm") == 0) {
	$error['msg'] = "You do not have delete permissions.";
} else {
	if (isset($cmd_detail) && $cmd_detail == 'delete') {
		$sql_ee2 	= " DELETE FROM sub_users_user_roles WHERE id = '" . $detail_id . "' AND user_id = '" . $id . "' ";
		$ok_del = $db->query($conn, $sql_ee2);
		if ($ok_del) {
			$msg['msg_success'] = "Role has been removed.";
		}
	}
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee 	= " SELECT a.*,  b.hourly_rate
					FROM users a 
					INNER JOIN employee_profile b ON b.user_id = a.id
					WHERE a.id = '" . $id . "' 
					AND a.subscriber_users_id ='" . $subscriber_users_id . "' ";
	// echo $sql_ee;
	$result_ee 	= $db->query($conn, $sql_ee);
	$row_ee 	= $db->fetch($result_ee);

	$first_name 				= $row_ee[0]['first_name'];
	$last_name 					= $row_ee[0]['last_name'];
	$email 						= $row_ee[0]['email'];
	$username 					= $row_ee[0]['username'];
	$hourly_rate				= $row_ee[0]['hourly_rate'];
	$a_password 				= $row_ee[0]['a_password'];
	$user_sections				= explode(",", $row_ee[0]['user_sections']);
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
		$error['msg'] 		= "Please Select Role";
		$add_role_id_valid = "invalid";
	}
	if (empty($error)) {
		$sql1_2		= "	SELECT * FROM sub_users_user_roles WHERE user_id = '" . $id . "' AND role_id = '" . $add_role_id . "' ";
		$result1_2 	= $db->query($conn, $sql1_2);
		$count2_2 	= $db->counter($result1_2);
		if ($count2_2 > 0) {
			$error['msg'] = "Sorry! This Role is already exist";
			$add_role_id_valid = "invalid";
		} else {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql1_1 = "INSERT INTO sub_users_user_roles (user_id, role_id, add_date, add_by, add_ip)
							VALUES('" . $id . "',  '" . $add_role_id . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
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
}
if (isset($is_Submit) && $is_Submit == 'Y') {

	if (isset($first_name) && $first_name == "") {
		$error['first_name'] 	= "Required";
		$first_name_valid 	= "invalid";
	}
	if (isset($last_name) && $last_name == "") {
		$error['last_name']	= "Required";
		$last_name_valid 	= "invalid";
	}
	if (isset($email) && $email == "") {
		$error['email']	= "Required";
		$email_valid 	= "invalid";
	}

	if (isset($username) && $username == "") {
		$error['username'] 	= "Required";
		$username_valid 	= "invalid";
	} else if (isset($a_password) && $a_password == "") {
		$error['a_password']	= "Required";
		$a_password_valid 		= "invalid";
	} else if (isset($a_password) && strlen($a_password) < 4) {
		$error['a_password'] 	= "Password should be greater than 3 characters.";
		$a_password_valid 		= "invalid";
	}
	if ($cmd == 'add') {
		$sql1 		= "	SELECT * FROM users WHERE username = '" . $username . "' ";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['username'] 	= "Sorry! This username is not available, try another.";
			$username_valid 	= "invalid";
		}
		$sql1 		= "	SELECT * FROM users WHERE email = '" . $email . "' ";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['email']	= "Sorry! This Email is already exist, try another.";
			$email_valid 	= "invalid";
		}
		$sql1 		= "	SELECT * FROM employee_profile WHERE e_email = '" . $email . "' ";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['email'] = "Sorry! This Email is already in Another Employee Profile exist, try another.";
			$email_valid 	= "invalid";
		}
	} else if ($cmd == 'edit') {
		$sql1 		= "	SELECT * FROM users 
						WHERE username = '" . $username . "' AND id != '" . $id . "' ";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['username'] 	= "Sorry! This username is not available, try another.";
			$username_valid 	= "invalid";
		}
		$sql1 		= "	SELECT * FROM users WHERE email = '" . $email . "'  AND id != '" . $id . "' ";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['email'] = "Sorry! This Email is already exist, try another.";
			$email_valid 	= "invalid";
		}

		$sql1 		= "	SELECT * FROM employee_profile a
						INNER JOIN users b ON b.id = a.user_id 
						WHERE a.e_email = '" . $email . "'  
						AND b.id != '" . $id . "' ";
		// echo 	"<br><br><br><br><br><br><br>aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa" . $sql1;
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['email'] = "Sorry! This Email is already in Another Employee Profile exist, try another.";
			$email_valid 	= "invalid";
		}
	}
	if (empty($error)) {
		if (isset($_POST['user_sections']) && sizeof($_POST['user_sections']) > 0) {
			$user_sections_str = implode(",", $user_sections);
		} else {
			$user_sections_str = "";
		}
		if ($hourly_rate == "") $hourly_rate = 0;
		$a_password_md5 = md5($a_password);
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql6 = "INSERT INTO " . $selected_db_name . ".users(subscriber_users_id, first_name, last_name, email, username, a_password, 
																				a_password_md5, user_type, user_sections, add_date, add_by, add_ip)
						VALUES('" . $subscriber_users_id . "', '" . $first_name . "', '" . $last_name . "', '" . $email . "', '" . $username . "', '" . $a_password . "', 
										'" . $a_password_md5 . "', 'Sub Users', '" . $user_sections_str . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
				$ok = $db->query($conn, $sql6);
				if ($ok) {
					$id 	= mysqli_insert_id($conn);
					$cmd 	= 'edit';
					$e_full_name = $first_name . " " . $last_name;
					$sql = "INSERT INTO " . $selected_db_name . ".employee_profile(subscriber_users_id, user_id, e_full_name, e_email, hourly_rate, emp_status, add_date, add_by, add_by_user_id, add_ip)
							VALUES('" . $subscriber_users_id . "', '" . $id . "', '" . $e_full_name . "', '" . $email . "', '" . $hourly_rate . "', 'Active', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "')";
					// echo $sql;
					$db->query($conn, $sql);
					if (isset($error['msg'])) unset($error['msg']);
					$msg['msg_success'] 		= "Account Has been created Successfully.";
				} else {
					$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_c_up = "UPDATE users SET 	a_password 					= '" . $a_password . "', 
												a_password_md5				= '" . $a_password_md5 . "', 
												first_name 					= '" . $first_name . "', 
												last_name 					= '" . $last_name . "', 
												username 					= '" . $username . "', 
												email 						= '" . $email . "', 
												user_sections 				= '" . $user_sections_str . "', 
				
												update_date 				= '" . $add_date . "',
												update_by 					= '" . $_SESSION['username'] . "',
												update_by_user_id			= '" . $_SESSION['user_id'] . "',
												update_ip 					= '" . $add_ip . "'
							WHERE id = '" . $id . "'   "; // echo "<br><br><br><br><br><br><br>" . $sql_c_up;
				$ok = $db->query($conn, $sql_c_up);
				if ($ok) {
					$sql_c_up = "UPDATE employee_profile SET 	hourly_rate			= '" . $hourly_rate . "', 
																e_full_name			= '" . $first_name . " " . $last_name . "', 
																e_email				= '" . $email . "',  
								
																update_date			= '" . $add_date . "',
																update_by			= '" . $_SESSION['username'] . "',
																update_by_user_id	= '" . $_SESSION['user_id'] . "',
																update_ip			= '" . $add_ip . "'
								WHERE user_id = '" . $id . "'   "; // echo "<br>" . $sql_c_up;
					$ok = $db->query($conn, $sql_c_up);
					$msg['msg_success'] = "Record Updated Successfully.";
				} else {
					$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
				}
			}
		}
	}
} ?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12 m12 l12">
			<div class="section section-data-tables">
				<div class="card custom_margin_card_table_top custom_margin_card_table_bottom">
					<div class="card-content custom_padding_card_content_table_top_bottom">
						<div class="row">
							<div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
								<h6 class="media-heading">
									<?php echo $title_heading; ?>
								</h6>
							</div>
							<div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
								<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">
									List
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy custom_margin_card_table_top custom_margin_card_table_bottom">
				<div class="card-content custom_padding_card_content_table_top">
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
					<br>
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<div class="row">

							<div class="input-field col m4 s12">
								<?php
								$field_name		= "first_name";
								$field_label 	= "First Name";
								?>
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="<?= $field_name; ?>" name="<?= $field_name; ?>" required="" type="text" value="<?php if (isset(${$field_name})) {
																																echo ${$field_name};
																															} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																						echo ${$field_name . "_valid"};
																																					} ?> ">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m4 s12">
								<?php
								$field_name		= "last_name";
								$field_label 	= "Last Name";
								?>
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="<?= $field_name; ?>" name="<?= $field_name; ?>" required="" type="text" value="<?php if (isset(${$field_name})) {
																																echo ${$field_name};
																															} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																						echo ${$field_name . "_valid"};
																																					} ?> ">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m4 s12">
								<?php
								$field_name		= "email";
								$field_label 	= "Email";
								?>
								<i class="material-icons prefix pt-2">mail_outline</i>
								<input id="<?= $field_name; ?>" type="email" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																			echo ${$field_name . "_valid"};
																																		} ?> ">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<?php
								$field_name		= "username";
								$field_label 	= "Username";
								?>
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="<?= $field_name; ?>" type="text" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																echo ${$field_name};
																															} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																						echo ${$field_name . "_valid"};
																																					} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m4 s12">
								<?php
								$field_name		= "a_password";
								$field_label 	= "Password";
								?>
								<i class="material-icons prefix pt-2">lock_outline</i>
								<input id="<?= $field_name; ?>" type="password" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m4 s12">
								<?php
								$field_name		= "hourly_rate";
								$field_label 	= "Hourly Rate";
								?>
								<i class="material-icons prefix pt-2">attach_money</i>
								<input id="<?= $field_name; ?>" name="<?= $field_name; ?>" type="text" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>" class="twoDecimalNumber validate  <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?> ">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
						</div>
						<div class="row">
							<div class="col m12 s12">
								<?php
								$field_name 	= "user_sections";
								$field_label 	= "User Section";
								?>
								<div class="input-field col m2 s12">
									<label>
										<?php $field_value 	= "Processing"; ?>
										<input type="checkbox" value="<?php echo $field_value; ?>" name="<?= $field_name; ?>[]" id="<?= $field_name; ?>" class="checkbox" <?php if (isset(${$field_name}) && in_array($field_value, ${$field_name})) { ?> checked <?php } ?>>
										<span><?php echo $field_value; ?></span>
									</label>
								</div>
								<div class="input-field col m2 s12">
									<label>
										<?php $field_value 	= "Repair"; ?>
										<input type="checkbox" value="<?php echo $field_value; ?>" name="<?= $field_name; ?>[]" id="<?= $field_name; ?>" class="checkbox" <?php if (isset(${$field_name}) && in_array($field_value, ${$field_name})) { ?> checked <?php } ?>>
										<span><?php echo $field_value; ?></span>
									</label>
								</div>
								<div class="input-field col m2 s12">
									<label>
										<?php $field_value 	= "Diagnostic"; ?>
										<input type="checkbox" value="<?php echo $field_value; ?>" name="<?= $field_name; ?>[]" id="<?= $field_name; ?>" class="checkbox" <?php if (isset(${$field_name}) && in_array($field_value, ${$field_name})) { ?> checked <?php } ?>>
										<span><?php echo $field_value; ?></span>
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col m6 s12"><br><br></div>
						</div>
						<div class="row">
							<div class="input-field col m6 s12">
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn cyan waves-effect waves-light right custom_btn_size" type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
				<?php //include('sub_files/right_sidebar.php');
				?>
			</div>
		</div>
		<?php
		if ($cmd == 'edit') { ?>
			<div class="col s6 m6 l6">
				<div id="Form-advance" class="card card card-default scrollspy">
					<div class="card-content">
						<h4 class="card-title">Role Assign Entry</h4>
						<form method="post" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&id=" . $id) ?>">
							<input type="hidden" name="is_Submit2" value="Y" />

							<div class="row">
								<div class="input-field col m12 s12">
								</div>
							</div>
							<div class="row">
								<div class="input-field col m12 s12">
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<?php
										$field_name 	= "add_role_id";
										$field_label 	= "Role";
										?>
										<select required id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate   <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
											<option value="">Select</option>
											<?php
											$sql_c 		= " SELECT * FROM sub_users_roles a WHERE a.enabled = 1  AND a.subscriber_users_id = '" . $subscriber_users_id . "' "; //echo $sql_c;
											$result_c 	= $db->query($conn, $sql_c);
											$row_c 		= $db->fetch($result_c);
											foreach ($row_c as $data) { ?>
												<option value="<?php echo $data['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data['id']) { ?> selected="selected" <?php } ?>><?php echo $data['role_name']; ?></option>
											<?php } ?>
										</select>
										<label for="<?= $field_name; ?>">
											<?= $field_label; ?>
											<span class="color-red">* <?php
																		if (isset($error[$field_name])) {
																			echo $error[$field_name];
																		} ?>
											</span>
										</label>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
											<button class="btn cyan waves-effect waves-light right" type="submit" name="action">
												Add Role
												<i class="material-icons right">send</i>
											</button>
										<?php } ?>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php }
		if (isset($id) && $id > 0) { ?>
			<div class="col s12 m6 l12">
				<div id="Form-advance" class="card card card-default scrollspy">
					<?php
					$sql_cl = "	SELECT b.*, a.role_name
								FROM sub_users_roles a
								INNER JOIN sub_users_user_roles b ON b.role_id = a.id
								WHERE a.enabled = 1 
								AND b.enabled = 1
								AND b.user_id = '" . $id . "'";
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
												<?php if (access("add_perm") == 1) { ?>
													<a class="waves-effect waves-light  btn gradient-45deg-red-pink box-shadow-none border-round mr-1 mb-1" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&id=" . $id . "&cmd_detail=delete&detail_id=" . $data2['id']) ?>" onclick="return confirm('Are you sure! You want to Remove this Role?')">
														Delete
													</a>
												<?php } ?>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					<?php } ?>
					<?php include('sub_files/right_sidebar.php'); ?>
				</div>
			</div>
		<?php } ?>
	</div><br><br>
	<!-- END: Page Main-->