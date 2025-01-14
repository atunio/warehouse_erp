<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$e_full_name 						= "e_full_name12";
	$e_gender 							= "Male";
	$e_birth_date 						= "01/01/1990"; //
	$e_marital_status 					= "Single";
	$e_phone 							= "e_phone";
	$e_email 							= "aftabatunio1@gmail.com";
	$e_national_id_no 					= "e_national_id_no1";
	$e_joining_date 					= "01/01/2021";
	$parent_name	 					= "parent_name1";
	$parent_phone 						= "01222222111";
	$e_mailing_address 					= "e_mailing_address";
	$e_mailing_city 					= "e_mailing_city";
	$e_mailing_state 					= "e_mailing_state";
	$e_mailing_country 					= "e_mailing_country";
	$e_emergency_contact_name 			= "e_emergency_contact_name";
	$e_emergency_contact_relationship 	= "Uncle";
	$e_emergency_contact_phone 			= "00001111111111";
	$e_emergency_contact_email 			= "e_emergency_contact_email";
	$e_earn_leave 						= "0";
	$e_casual_leave 					= "0";
	$e_sick_leave 						= "0";
	$e_exit_date 						= "";
	$e_exit_reason 						= "";
	$e_bank_name 						= "e_bank_name";
	$e_bank_account_name 				= "e_bank_account_name";
	$e_bank_account_number 				= "25522222";
	$e_bank_branch_location 			= "e_bank_branch_location";
	$gross_salary 						= "15000";
	$e_exit_reason 						= "";
	$emp_code 							= "225EMP";
}
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 				= new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$school_admin_id 	= $_SESSION["school_admin_id"];
$user_id 	= $_SESSION["user_id"];
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
$button_edu = "Add";
$button_exp = "Add";
if (!isset($cmd2)) {
	$cmd2 = "add";
}
if (!isset($cmd3)) {
	$cmd3 = "add";
}
if (!isset($cmd4)) {
	$cmd4 = "add";
}
$emp_status = "Active";
$increament_amount 		= "0";
$increament_amount_old 	= $increament_amount;
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
include('tab2_education_code.php');
include('tab3_experience_code.php');
include('tab1_profile_code.php');
if ($cmd == 'edit') {
	$title_heading = "Edit Employee Profile";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Employee Profile";
	$button_val 	= "Add";
	$id 			= "";
}
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="row">
						<div class="col s10 m10 20">
							<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $title_heading; ?></span></h5>
							<ol class="breadcrumbs mb-0">
								<li class="breadcrumb-item"><?php echo $title_heading; ?>
								</li>
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>">List</a></li>
							</ol>
						</div>
						<div class="col m2 s12 m2 4">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&page=listing") ?>" data-target="dropdown1">
								List
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col m12 s12">
			<div class="container">
				<!-- Account settings -->
				<section class="tabs-vertical mt-1 section">
					<div class="row">
						<div class="col l3 s12">
							<!-- tabs  -->
							<div class="card-panel">
								<ul class="tabs">
									<li class="tab">
										<a href="#general" class="<?php if (isset($active_tab) && $active_tab == 'tab1') {
																		echo "active";
																	} ?>">
											<i class="material-icons">person_outline</i>
											<span>Profile</span>
										</a>
									</li>
									<li class="tab">
										<a href="#info" class="<?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2')) {
																	echo "active";
																} ?>">
											<i class="material-icons">school</i>
											<span>Education</span>
										</a>
									</li>
									<li class="tab">
										<a href="#experience" class="<?php if (isset($active_tab) && $active_tab == 'tab3') {
																			echo "active";
																		} ?>">
											<i class="material-icons">description</i>
											<span>Experience</span>
										</a>
									</li>
									<li class="indicator" style="left: 0px; right: 0px;"></li>
									<?php
									if (isset($id) && $id > 0) { ?>
										<?php
										if (isset($cmd2) && $cmd2 != "") { ?><br>
											<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit&cmd=edit&id=" . $id . "&cmd2=add&active_tab=tab2") ?>">
												Add New Eduction
											</a>
										<?php }
										if (isset($cmd3) && $cmd3 != "") { ?><br>
											<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit&cmd=edit&id=" . $id . "&cmd3=add&active_tab=tab3") ?>">
												Add New Experience
											</a>
									<?php }
									} ?>
								</ul>
							</div>
						</div>
						<div class="col l9 s12">
							<?php
							if (isset($error['msg'])) { ?>
								<div class="card-panel">
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card red lighten-5">
												<div class="card-content red-text">
													<p><?php echo $error['msg']; ?></p>
												</div>
												<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										</div>
									</div>
								</div>
							<?php } else if (isset($msg['msg_success'])) { ?>
								<div class="card-panel">
									<div class="row">
										<div class="col 24 s12">
											<div class="card-alert card green lighten-5">
												<div class="card-content green-text">
													<p><?php echo $msg['msg_success']; ?></p>
												</div>
												<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
							<!-- tabs content -->
							<!--General Tab Begin-->
							<div id="general" style="display: <?php if (isset($active_tab) && $active_tab == 'tab1') {
																	echo "block";
																} else {
																	echo "none";
																} ?>;">
								<div class="card-panel">
									<form class="infovalidate" action="" method="post" enctype="multipart/form-data">
										<input type="hidden" name="is_submit_profile" value="Y" />
										<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
										<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
										<input type="hidden" name="old_profile_pic_file_name" value="<?php if (isset($profile_pic_file_name)) echo $profile_pic_file_name; ?>" />
										<input type="hidden" name="old_resume_file_file_name" value="<?php if (isset($resume_file_file_name)) echo $resume_file_file_name; ?>" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<input type="hidden" name="active_tab" value="tab1" />
										<div class="divider mb-1 mt-1"></div>
										<div class="row">
											<div class="row">
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix pt-2">person_outline</i>
														<input type="text" id="e_full_name" name="e_full_name" value="<?php if (isset($e_full_name)) echo $e_full_name; ?>" data-error=".errorTxt1" required>
														<label for="e_full_name">Full Name</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix pt-2">people_outline</i>
														<select id="e_gender" name="e_gender" class="validate <?php if (isset($e_gender)) {
																													echo $e_gender;
																												} ?>" required>
															<option value="">Select Gender</option>
															<option value="Male" <?php if (isset($e_gender) && $e_gender == "Male") { ?> selected="selected" <?php } ?>>Male</option>
															<option value="Female" <?php if (isset($e_gender) && $e_gender == "Female") { ?> selected="selected" <?php } ?>>Female</option>
														</select>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">date_range</i>
														<input id="e_birth_date" type="text" name="e_birth_date" class="datepicker" value="<?php if (isset($e_birth_date)) {
																																				echo $e_birth_date;
																																			} ?>" required>
														<label for="e_birth_date">Date of Birth</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix pt-2">people_outline</i>
														<select id="e_marital_status" name="e_marital_status" class="validate <?php if (isset($e_marital_status)) {
																																	echo $e_marital_status;
																																} ?>" required>
															<option value="">Select Marital Status</option>
															<option value="Single" <?php if (isset($e_marital_status) && $e_marital_status == "Single") { ?> selected="selected" <?php } ?>>Single</option>
															<option value="Married" <?php if (isset($e_marital_status) && $e_marital_status == "Married") { ?> selected="selected" <?php } ?>>Married</option>
															<option value="Divorced" <?php if (isset($e_marital_status) && $e_marital_status == "Divorced") { ?> selected="selected" <?php } ?>>Divorced</option>
															<option value="Other" <?php if (isset($e_marital_status) && $e_marital_status == "Other") { ?> selected="selected" <?php } ?>>Other</option>
														</select>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix pt-2">phone_iphone_outline</i>
														<input type="text" id="e_phone" name="e_phone" value="<?php if (isset($e_phone)) echo $e_phone; ?>" data-error=".errorTxt1" readonly required>
														<label for="e_phone">Phone</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix pt-2">mail_outline</i>
														<input type="email" id="e_email" name="e_email" value="<?php if (isset($e_email)) echo $e_email; ?>" data-error=".errorTxt1" readonly required>
														<label for="e_email">Email</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">person_outline</i>
														<input type="text" id="e_national_id_no" name="e_national_id_no" value="<?php if (isset($e_national_id_no)) echo $e_national_id_no; ?>" data-error=".errorTxt1" required>
														<label for="e_national_id_no">National ID Number</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">person_outline</i>
														<input id="parent_name" type="text" name="parent_name" value="<?php if (isset($parent_name)) {
																															echo $parent_name;
																														} ?>" required>
														<label for="parent_name">Parent full name</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m8 s12">
													<div class="input-field">
														<i class="material-icons prefix">place</i>
														<input id="e_mailing_address" type="text" name="e_mailing_address" value="<?php if (isset($e_mailing_address)) {
																																		echo $e_mailing_address;
																																	} ?>" required>
														<label for="e_mailing_address">Mailing address</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">place</i>
														<input id="e_mailing_city" type="text" name="e_mailing_city" value="<?php if (isset($e_mailing_city)) {
																																echo $e_mailing_city;
																															} ?>" required>
														<label for="e_mailing_city">Mailing city</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">phone_iphone</i>
														<input id="parent_phone" type="text" name="parent_phone" value="<?php if (isset($parent_phone)) {
																															echo $parent_phone;
																														} ?>">
														<label for="parent_phone">Parent phone number</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">place</i>
														<input id="e_mailing_state" type="text" name="e_mailing_state" value="<?php if (isset($e_mailing_state)) {
																																	echo $e_mailing_state;
																																} ?>" required>
														<label for="e_mailing_state">Mailing state</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">place</i>
														<input id="e_mailing_country" type="text" name="e_mailing_country" value="<?php if (isset($e_mailing_country)) {
																																		echo $e_mailing_country;
																																	} ?>" required>
														<label for="e_mailing_country">Mailing country</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">person_outline</i>
														<input id="e_emergency_contact_name" type="text" name="e_emergency_contact_name" value="<?php if (isset($e_emergency_contact_name)) {
																																					echo $e_emergency_contact_name;
																																				} ?>">
														<label for="e_emergency_contact_name">Emergency contact name</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">people_outline</i>
														<input id="e_emergency_contact_relationship" type="text" name="e_emergency_contact_relationship" value="<?php if (isset($e_emergency_contact_relationship)) {
																																									echo $e_emergency_contact_relationship;
																																								} ?>">
														<label for="e_emergency_contact_relationship">Emergency contact relationship</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">phone_iphone</i>
														<input id="e_emergency_contact_phone" type="text" name="e_emergency_contact_phone" value="<?php if (isset($e_emergency_contact_phone)) {
																																						echo $e_emergency_contact_phone;
																																					} ?>">
														<label for="e_emergency_contact_phone">Emergency contact phone</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">mail_outline</i>
														<input id="e_emergency_contact_email" type="text" name="e_emergency_contact_email" value="<?php if (isset($e_emergency_contact_email)) {
																																						echo $e_emergency_contact_email;
																																					} ?>">
														<label for="e_emergency_contact_email">Emergency contact email</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">account_balance</i>
														<input id="e_bank_name" type="text" name="e_bank_name" value="<?php if (isset($e_bank_name)) {
																															echo $e_bank_name;
																														} ?>">
														<label for="e_bank_name">Bank name</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">account_balance</i>
														<input id="e_bank_account_name" type="text" name="e_bank_account_name" value="<?php if (isset($e_bank_account_name)) {
																																			echo $e_bank_account_name;
																																		} ?>">
														<label for="e_bank_account_name">Bank account name</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">account_balance</i>
														<input id="e_bank_account_number" type="text" name="e_bank_account_number" value="<?php if (isset($e_bank_account_number)) {
																																				echo $e_bank_account_number;
																																			} ?>">
														<label for="e_bank_account_number">Bank account number</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">place</i>
														<input id="e_bank_branch_location" type="text" name="e_bank_branch_location" value="<?php if (isset($e_bank_branch_location)) {
																																				echo $e_bank_branch_location;
																																			} ?>">
														<label for="e_bank_branch_location">Bank branch location</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">person_outline</i>
														<input id="emp_code" type="text" name="emp_code" class="validate <?php if (isset($emp_code_valid)) {
																																echo $emp_code_valid;
																															} ?>" value="<?php if (isset($emp_code)) {
																																				echo $emp_code;
																																			} ?>" readonly required>
														<label for="emp_code">Employment Code</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">person_outline</i>
														<input id="username" type="text" name="username" <?php if ($cmd == 'edit') { ?> readonly <?php } ?> class="validate <?php if (isset($username_valid)) {
																																												echo $username_valid;
																																											} ?>" value="<?php if (isset($username)) {
																																																echo $username;
																																															} ?>" readonly required>
														<label for="username">Login Username</label>
													</div>
												</div>
												<div class="col m4 s12">
													<div class="input-field">
														<i class="material-icons prefix">lock_outline</i>
														<input id="u_password" type="password" name="u_password" <?php if ($cmd == 'edit') { ?> readonly <?php } ?> class="validate <?php if (isset($u_password_valid)) {
																																														echo $u_password_valid;
																																													} ?>" value="<?php if (isset($u_password)) {
																																																		echo $u_password;
																																																	} ?>" readonly required>
														<label for="u_password">Login Password</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m4 s12">
													<label for="user_type">Login User Type</label>
													<div class="input-field">
														<i class="material-icons prefix pt-2">person_outline</i>
														<select name="user_type" id="user_type" class="validate <?php if (isset($user_type_valid)) {
																													echo $user_type_valid;
																												} ?>">
															<option value="<?php echo $user_type; ?>" selected="selected"><?php echo $user_type; ?></option>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m6 s12">
													<div class="custom-file input-field">
														<input id="customFile" type="file" name="e_profile_pic" class="custom-file-input dropify" value="<?php if (isset($e_profile_pic)) {
																																								echo $e_profile_pic;
																																							} ?>" data-default-file>
														<label class="custom-file-label" for="e_profile_pic">&nbsp;Upload profile photo</label>
													</div>
												</div>
												<div class="col m6 s12"><br>
													<?php if (isset($profile_pic_file_name) && $profile_pic_file_name != "") { ?>
														<img src="<?php echo $directory_path; ?>app-assets/images/logo/<?php echo $profile_pic_file_name; ?>" style="Height: 200px;" class="responsive-img" />
													<?php } ?>
												</div>
											</div>
											<div class="row">
												<div class="col m6 s12">
													<div class="custom-file input-field">
														<input id="customFile" type="file" name="e_resume_upload" class="custom-file-input dropify" value="<?php if (isset($e_resume_upload)) {
																																								echo $e_resume_upload;
																																							} ?>" data-default-file>
														<label class="custom-file-label" for="e_resume_upload">&nbsp; Upload resume</label>
													</div>
												</div>
												<div class="col m6 s12"><br><br><br><br>
													<?php if (isset($resume_file_file_name) && $resume_file_file_name != "") { ?>
														<a target="_blank" href="<?php echo $directory_path; ?>app-assets/employee_resumes/<?php echo $resume_file_file_name; ?>" class="waves-effect waves-light green darken-1  btn gradient-45deg-light-green-cyan box-shadow-none border-round mr-1 mb-1">
															<i class="material-icons">attachment</i>
														</a>
													<?php } ?>
												</div>
											</div>
											<div class="row">
												<div class="input-field col m12 s12">
													<button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add"><?php echo $button_val; ?></button>
												</div>
												<div class="input-field col m4 s12"></div>
											</div>
											<div class="col m4 s12"></div>
										</div>
									</form>
								</div>
							</div>
							<!--General Tab End-->

							<!--Info Tab Begin-->
							<div id="info" class="active" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2')) {
																				echo "block";
																			} else {
																				echo "none";
																			} ?>;">
								<div class="card-panel">
									<form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&page=add_edit&cmd=edit&id=" . $id . "&cmd2=" . $cmd2 . "&detail_id=" . $detail_id . "&active_tab=tab2") ?>" method="post">
										<input type="hidden" name="is_Submit_Education" value="Y" />
										<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
										<input type="hidden" name="cmd2" value="<?php if (isset($cmd2)) echo $cmd2; ?>" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<input type="hidden" name="active_tab" value="tab2" />

										<div class="row">
											<div class="col m12 s12">
												<div class="input-field col m12 s12">
													<i class="material-icons prefix">school</i>
													<input id="e_school" type="text" name="e_institution_name" value="<?php if (isset($e_institution_name)) {
																															echo $e_institution_name;
																														} ?>" class="validate <?php if (isset($e_institution_name)) {
																																					echo $e_institution_name;
																																				} ?>" required>
													<label for="e_school">School</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col m6 s12">
												<div class="input-field col m12 s12">
													<i class="material-icons prefix">date_range</i>
													<input id="date_from" type="text" name="date_from" class="datepicker" value="<?php if (isset($date_from)) {
																																		echo $date_from;
																																	} ?>" required>
													<label for="date_from">From</label>
												</div>
											</div>
											<div class="col m6 s12">
												<div class="input-field col m12 s12">
													<i class="material-icons prefix">date_range</i>
													<input id="date_to" type="text" name="date_to" class="datepicker" value="<?php if (isset($date_to)) {
																																	echo $date_to;
																																} ?>" required>
													<label for="date_to">To</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col m6 s12">
												<div class="input-field col m12 s12">
													<i class="material-icons prefix">local_library</i>
													<input id="degree_name" type="text" name="degree_name" value="<?php if (isset($degree_name)) {
																														echo $degree_name;
																													} ?>" class="validate <?php if (isset($degree_name)) {
																																				echo $degree_name;
																																			} ?>" required>
													<label for="degree_name">Degree</label>
												</div>
											</div>
											<div class="col m6 s12">
												<div class="input-field col m12 s12">
													<i class="material-icons prefix">local_library</i>
													<input id="study_area" type="text" name="study_area" value="<?php if (isset($study_area)) {
																													echo $study_area;
																												} ?>" class="validate <?php if (isset($study_area)) {
																																			echo $study_area;
																																		} ?>" required>
													<label for="study_area">Area of Study</label>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="input-field col m4 s12"></div>
											<div class="input-field col m4 s12">
												<button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add"><?php echo $button_edu; ?></button>
											</div>
											<div class="input-field col m4 s12"></div>
										</div>
									</form>
								</div>
								<div class="section section-data-tables">
									<div class="row">
										<div class="col m12 s12">
											<div class="card">
												<div class="card-content">
													<div class="row">
														<div class="col m12 s12">
															<?php
															$sql_cl1 = "SELECT a.* 
												FROM " . $selected_db_name . ".employee_education a
 												WHERE a.enabled 		= 1 
												AND a.school_admin_id 	= '" . $school_admin_id . "' 
												AND a.emp_profile_id	= '" . $id . "' 
												ORDER BY a.id DESC "; //echo $sql_cl1;
															$result_cl1 	= $db->query($conn, $sql_cl1);
															$count_cl1 	= $db->counter($result_cl1);
															if ($count_cl1 > 0) { ?>
																<table id="page-length-option" class="display">
																	<thead>
																		<tr>
																			<th>S.No</th>
																			<th>Degree<br>Institute</th>
																			<th>Area of Study</th>
																			<th>Date From</br>Date To</th>
																			<th>Action</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		$i = 0;
																		if ($count_cl1 > 0) {
																			$row_cl1 = $db->fetch($result_cl1);
																			foreach ($row_cl1 as $data) { ?>
																				<tr>
																					<td><?php echo $i + 1; ?></td>
																					<td><?php echo $data['degree_name']; ?><br> <?php echo $data['e_institution_name']; ?></td>
																					<td><?php echo $data['study_area']; ?></td>
																					<td><?php echo dateformat2($data['date_from']); ?></br><?php echo dateformat2($data['date_to']); ?></td>
																					<td>
																						<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit&cmd=edit&cmd2=edit&active_tab=tab2&id=" . $id . "&detail_id=" . $data['id']) ?>">
																							<i class="material-icons dp48">edit</i>
																						</a>
																						&nbsp;&nbsp;
																						<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit&cmd=edit&cmd2=delete&active_tab=tab2&id=" . $id . "&detail_id=" . $data['id']) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																							<i class="material-icons dp48">delete</i>
																						</a>
																					</td>
																				</tr>
																		<?php
																				$i++;
																			}
																		} ?>
																	</tbody>
																</table>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="content-overlay"></div>
									<!-- Multi Select -->
								</div>
							</div>
							<!--Info Tab End-->

							<!--Experience Tab Begin-->
							<div id="experience" class="active" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
																					echo "block";
																				} else {
																					echo "none";
																				} ?>;">
								<div class="card-panel">
									<form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&page=add_edit&cmd=edit&id=" . $id . "&cmd3=" . $cmd3 . "&detail_id=" . $detail_id . "&active_tab=tab3") ?>" method="post">
										<input type="hidden" name="is_Submit_experience" value="Y" />
										<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
										<input type="hidden" name="cmd3" value="<?php if (isset($cmd3)) echo $cmd3; ?>" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<input type="hidden" name="active_tab" value="tab3" />

										<div id="experience_field">
											<div class="row">
												<div class="col m6 s12">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">local_library</i>
														<input id="e_job_title" type="text" name="e_job_title" value="<?php if (isset($e_job_title)) {
																															echo $e_job_title;
																														} ?>" required="required" class="validate <?php if (isset($e_job_title)) {
																																										echo $e_job_title;
																																									} ?>">
														<label for="e_job_title">Job Title</label>
													</div>
												</div>
												<div class="col m6 s12">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">work_outline</i>
														<input id="e_job_role" type="text" name="e_job_role" value="<?php if (isset($e_job_role)) {
																														echo $e_job_role;
																													} ?>" required="required" class="validate <?php if (isset($e_job_role)) {
																																									echo $e_job_role;
																																								} ?>">
														<label for="e_job_role">Job Role</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m6 s12">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">date_range</i>
														<input id="e_date_from" type="text" name="e_date_from" class="datepicker" value="<?php if (isset($e_date_from)) {
																																				echo $e_date_from;
																																			} ?>" required>
														<label for="e_date_from">From</label>
													</div>
												</div>
												<div class="col m6 s12">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">date_range</i>
														<input id="e_date_to" type="text" name="e_date_to" class="datepicker" value="<?php if (isset($e_date_to)) {
																																			echo $e_date_to;
																																		} ?>" required>
														<label for="e_date_to">To</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col m6 s12">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">business</i>
														<input id="e_company" type="text" name="e_company" value="<?php if (isset($e_company)) {
																														echo $e_company;
																													} ?>" required="required" class="validate <?php if (isset($e_company)) {
																																									echo $e_company;
																																								} ?>">
														<label for="e_company">Company</label>
													</div>
												</div>
												<div class="col m6 s12">
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">description_outline</i>
														<input id="e_job_description" type="text" name="e_job_description" value="<?php if (isset($e_job_description)) {
																																		echo $e_job_description;
																																	} ?>" required="required" class="validate <?php if (isset($e_job_description)) {
																																													echo $e_job_description;
																																												} ?>">
														<label for="e_job_description">Job Description</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="input-field col m12 s12">
													<button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add"><?php echo $button_exp; ?></button>
												</div>
												<div class="input-field col m4 s12"></div>
											</div>
										</div>
									</form>
								</div>
								<div class="section section-data-tables">
									<div class="row">
										<div class="col m12 s12">
											<div class="card">
												<div class="card-content">
													<div class="row">
														<div class="col m12 s12">
															<?php
															$sql_cl1 = "SELECT a.*
												FROM " . $selected_db_name . ".employee_experience a
												WHERE a.enabled 		= 1 
												AND a.school_admin_id 	= '" . $school_admin_id . "' 
												AND a.emp_profile_id	= '" . $id . "' 
												ORDER BY a.id DESC ";
															$result_cl1 	= $db->query($conn, $sql_cl1);
															$count_cl1 	= $db->counter($result_cl1);
															if ($count_cl1 > 0) { ?>
																<table id="page-length-option" class="display">
																	<thead>
																		<tr>
																			<th>S.No</th>
																			<th>Job Title<br>Company Name</th>
																			<th>Job Role</th>
																			<th>Job Description</th>
																			<th> Date From<br>Date To</th>
																			<th>Action</th>
																		</tr>
																	</thead>
																	<tbody>
																		<?php
																		$i = 0;
																		if ($count_cl1 > 0) {
																			$row_cl1 = $db->fetch($result_cl1);
																			foreach ($row_cl1 as $data) { ?>
																				<tr>
																					<td><?php echo $i + 1; ?></td>
																					<td><?php echo $data['e_job_title']; ?><br> <?php echo $data['e_company']; ?></td>
																					<td><?php echo $data['e_job_role']; ?></td>
																					<td><?php echo $data['e_job_description']; ?></td>
																					<td><?php echo dateformat2($data['e_date_from']); ?><br><?php echo dateformat2($data['e_date_to']); ?></td>
																					<td>
																						<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit&cmd=edit&cmd3=edit&active_tab=tab3&id=" . $id . "&detail_id=" . $data['id']) ?>">
																							<i class="material-icons dp48">edit</i>
																						</a>
																						&nbsp;&nbsp;
																						<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add_edit&cmd=edit&cmd3=delete&active_tab=tab3&id=" . $id . "&detail_id=" . $data['id']) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																							<i class="material-icons dp48">delete</i>
																						</a>
																					</td>
																				</tr>
																		<?php
																				$i++;
																			}
																		} ?>
																	</tbody>
																</table>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="content-overlay"></div>
									<!-- Multi Select -->
								</div>
							</div>
							<!--Experience Tab End-->
						</div>
					</div>
				</section>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->