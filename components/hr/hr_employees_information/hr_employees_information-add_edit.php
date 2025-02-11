<?php
if (isset($test_on_local) && $test_on_local == 1) {
	$e_full_name 						= "e_full_name12";
	$e_gender 							= "Male";
	$e_birth_date 						= "01/01/1990"; //
	$e_marital_status 					= "Single";
	$e_phone 							= date('YmdHis');
	$e_email 							= date('YmdHis') . "@gmail.com";
	$e_national_id_no 					= date('YmdHis');
	$e_joining_date 					= "01/01/2021";
	$parent_name	 					= "parent_name1";
	$e_mailing_address 					= "e_mailing_address";
	$e_mailing_city 					= "e_mailing_city";
	$e_mailing_state 					= "e_mailing_state";
	$e_mailing_country 					= "e_mailing_country";
	$e_emergency_contact_name 			= "e_emergency_contact_name";
	$e_emergency_contact_relationship 	= "Uncle";
	$e_emergency_contact_phone 			= "00001111111111";
	$e_emergency_contact_email			= date('YmdHis') . "111@gmail.com";
	$e_earn_leave 						= "0";
	$e_exit_date 						= "";
	$e_exit_reason 						= "";
	$hour_rate 							= "15";
	$e_exit_reason 						= "";
	$emp_code 							= date('YmdHis');
	$hourly_rate 						= "25";
	$username 							= date('YmdHis');
	$u_password 						= $username;
	$user_type 							= "Sub Users";

	$e_school 							= "e_school " . date('YmdHis');
	$date_from 							= date('d/m/Y');
	$date_to 							= date('d/m/Y');
	$degree_name 						= "study_area " . date('YmdHis');
	$study_area 						= "study_area " . date('YmdHis');


	$e_job_title 						= "e_job_title " . date('YmdHis');
	$e_job_role 						= "e_job_role " . date('YmdHis');
	$e_date_from 						= date('d/m/Y');
	$e_date_to 							= date('d/m/Y');
	$e_company 							= "e_company " . date('YmdHis');
	$e_job_description 					= "e_job_description " . date('YmdHis');



	$designation_id 					= "1";
	$dept_id 							= 1;
	$scale_id 							= 1;
	$entry_type 						= "New Hiring";
	$employment_type					= "Regular";
	$emp_history_entry_date				= date('d/m/Y');
	$e_job_description 					= "0";
}
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 				= new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 	= $_SESSION["user_id"];
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}

$button_edu 		= "Add";
$button_exp 		= "Add";
$button_allowances 	= "Add";

if (!isset($cmd2)) {
	$cmd2 = "add";
}
if (!isset($cmd3)) {
	$cmd3 = "add";
}
if (!isset($cmd4)) {
	$cmd4 = "add";
}
if (!isset($cmd5)) {
	$cmd5 = "add";
}
if (!isset($cmd6)) {
	$cmd6 = "add";
}
if (!isset($cmd7)) {
	$cmd7 = "add";
}
if (!isset($cmd8)) {
	$cmd8 = "add";
}

$emp_status 			= "Active";
$increament_amount 		= "0";
$increament_amount_old 	= $increament_amount;

extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
include('tab1_profile_code.php');
include('tab2_education_code.php');
include('tab3_experience_code.php');
include('tab4_emp_history_code.php');
// include('tab5_allowances_or_benefits_code.php');
// include('tab6_children_students.php');

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
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">List</a></li>
							</ol>
						</div>
						<div class="col m2 s12 m2 4">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
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
									<li class="tab">
										<a href="#employment_history" class="<?php if (isset($active_tab) && $active_tab == 'tab4') {
																					echo "active";
																				} ?>">
											<i class="material-icons">description</i>
											<span>Employment History</span>
										</a>
									</li>
									<?php /*?>
									<li class="tab">
										<a href="#allowances_or_benefits" class="<?php if (isset($active_tab) && $active_tab == 'tab5') {
																						echo "active";
																					} ?>">
											<i class="material-icons">description</i>
											<span>Allowances or Benefits</span>
										</a>
									</li>
									<li class="tab">
										<a href="#student_children" class="<?php if (isset($active_tab) && $active_tab == 'tab6') {
																				echo "active";
																			} ?>">
											<i class="material-icons">description</i>
											<span>Children As Students</span>
										</a>
									</li>
									<?php */ ?>
									<li class="indicator" style="left: 0px; right: 0px;"></li>
									<?php
									if (isset($id) && $id > 0) { ?>
										<br>
										<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=add&active_tab=tab1") ?>">
											Add New Employee
										</a>
										<?php
										if (isset($cmd2) && $cmd2 != "") { ?><br>
											<!-- 	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd2=add&active_tab=tab2") ?>">
												Add New Eduction
											</a> -->
										<?php }
										if (isset($cmd3) && $cmd3 != "") { ?><br>
											<!-- <a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd3=add&active_tab=tab3") ?>">
												Add New Experience
											</a> -->
										<?php }
										if (isset($cmd4) && $cmd4 != "") { ?><br>
											<!-- <a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd4=add&active_tab=tab4") ?>">
												Add New Employment History
											</a> -->
									<?php }
										/*
										if (isset($cmd5) && $cmd5 != "") { ?><br>
											<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd5=add&active_tab=tab5") ?>">
												Add New Allowances or Benefits
											</a>
										<?php }
										if (isset($cmd6) && $cmd6 != "") { ?><br>
											<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd6=add&active_tab=tab6") ?>">
												Add New Child Student
											</a>
									<?php }
									*/
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
										<h4>Profile</h4>
										<div class="row">
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "first_name";
												$field_label 	= "First Name";
												?>
												<i class="material-icons prefix">person_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "last_name";
												$field_label 	= "Last Name";
												?>
												<i class="material-icons prefix">person_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "parent_name";
												$field_label 	= "Father Name";
												?>
												<i class="material-icons prefix">person_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
										</div>
										<div class="row">
											<div class="input-field col m4 s12 custom_margin_bottom_col">
												<i class="material-icons prefix pt-2">people_outline</i>
												<div class="select2div">
													<?php
													$field_name 	= "e_gender";
													$field_label 	= "Gender";
													?>

													<select id="e_gender" name="e_gender" class="select2 browser-default select2-hidden-accessible validate  <?php if (isset($e_gender)) {
																																									echo $e_gender;
																																								} ?>">
														<option value="">Select</option>
														<option value="Male" <?php if (isset($e_gender) && $e_gender == "Male") { ?> selected="selected" <?php } ?>>Male</option>
														<option value="Female" <?php if (isset($e_gender) && $e_gender == "Female") { ?> selected="selected" <?php } ?>>Female</option>
													</select>
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"> * <?php
																					if (isset($error[$field_name])) {
																						echo $error[$field_name];
																					} ?>
														</span>
													</label>
												</div>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "e_email";
												$field_label 	= "Email";
												?>
												<i class="material-icons prefix">mail_outline</i>
												<input id="<?= $field_name; ?>" type="email" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "e_national_id_no";
												$field_label 	= "National ID Number";
												?>
												<i class="material-icons prefix">person_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
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
												$field_name 	= "e_birth_date";
												$field_label 	= "Date of Birth";
												?>
												<i class="material-icons prefix">date_range</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="datepicker validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12 custom_margin_bottom_col">
												<i class="material-icons prefix pt-2">people_outline</i>

												<div class="select2div">
													<?php
													$field_name 	= "e_marital_status";
													$field_label 	= "Marital Status";
													?>
													<select id="e_marital_status" name="e_marital_status" class="select2 browser-default select2-hidden-accessible validate <?php if (isset($e_marital_status)) {
																																												echo $e_marital_status;
																																											} ?>">
														<option value="">Select </option>
														<option value="Single" <?php if (isset($e_marital_status) && $e_marital_status == "Single") { ?> selected="selected" <?php } ?>>Single</option>
														<option value="Married" <?php if (isset($e_marital_status) && $e_marital_status == "Married") { ?> selected="selected" <?php } ?>>Married</option>
														<option value="Divorced" <?php if (isset($e_marital_status) && $e_marital_status == "Divorced") { ?> selected="selected" <?php } ?>>Divorced</option>
														<option value="Other" <?php if (isset($e_marital_status) && $e_marital_status == "Other") { ?> selected="selected" <?php } ?>>Other</option>
													</select>
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"> * <?php
																					if (isset($error[$field_name])) {
																						echo $error[$field_name];
																					} ?>
														</span>
													</label>
												</div>
											</div>












											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "e_phone";
												$field_label 	= "Phone";
												?>
												<i class="material-icons prefix">phone_iphone_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
										</div>
										<div class="row">
											<div class="input-field col m8 s12">
												<?php
												$field_name 	= "e_mailing_address";
												$field_label 	= "Mailing Address";
												?>
												<i class="material-icons prefix">place</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
																			if (isset($error[$field_name])) {
																				echo $error[$field_name];
																			} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "e_mailing_city";
												$field_label 	= "Mailing City";
												?>
												<i class="material-icons prefix">place</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
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
												$field_name 	= "e_mailing_state";
												$field_label 	= "Mailing State";
												?>
												<i class="material-icons prefix">place</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
																			if (isset($error[$field_name])) {
																				echo $error[$field_name];
																			} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "e_mailing_country";
												$field_label 	= "Mailing Country";
												?>
												<i class="material-icons prefix">place</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
																			if (isset($error[$field_name])) {
																				echo $error[$field_name];
																			} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "emp_code";
												$field_label 	= "Employment Code";
												?>
												<i class="material-icons prefix">person_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
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
												$field_name 	= "e_emergency_contact_name";
												$field_label 	= "Emergency Contact Name";
												?>
												<i class="material-icons prefix">person_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
																			if (isset($error[$field_name])) {
																				echo $error[$field_name];
																			} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "e_emergency_contact_relationship";
												$field_label 	= "Emergency Contact Relationship";
												?>
												<i class="material-icons prefix">people_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
																			if (isset($error[$field_name])) {
																				echo $error[$field_name];
																			} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "e_emergency_contact_phone";
												$field_label 	= "Emergency Contact Phone";
												?>
												<i class="material-icons prefix">phone_iphone</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
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
												$field_name 	= "e_emergency_contact_email";
												$field_label 	= "Emergency Contact email";
												?>
												<i class="material-icons prefix">email</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
																			if (isset($error[$field_name])) {
																				echo $error[$field_name];
																			} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "e_earn_leave";
												$field_label 	= "Yearly leaves";
												?>
												<i class="material-icons prefix">schedule</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
																			if (isset($error[$field_name])) {
																				echo $error[$field_name];
																			} ?>
													</span>
												</label>
											</div>

											
											<div class="input-field col m3 s12 custom_margin_bottom_col">
												<i class="material-icons prefix">person_outline</i>
												<div class=" select2div">

													<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset($emp_statuss_valid)) {
																																														echo $emp_statuss_valid;
																																													} ?>">
														<option value="">Select</option>
														<option value="Active" <?php if (isset($emp_status) && $emp_status == "Active") { ?> selected="selected" <?php } ?>>Active</option>
														<option value="Deactive" <?php if (isset($emp_status) && $emp_status == "Deactive") { ?> selected="selected" <?php } ?>>Deactive</option>
													</select>
													<label for="emp_status">Status</label>

												</div>
											</div>
										</div>
										<div class="row">
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "hourly_rate";
												$field_label 	= "Hourly Rate";
												?>
												<i class="material-icons prefix">attach_money</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="twoDecimalNumber validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "e_joining_date";
												$field_label 	= "Joining Date";
												?>
												<i class="material-icons prefix">date_range</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="datepicker validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "e_exit_date";
												$field_label 	= "Exit Date";
												?>
												<i class="material-icons prefix">date_range</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="datepicker validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
																			if (isset($error[$field_name])) {
																				echo $error[$field_name];
																			} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m12 s12">
												<?php
												$field_name 	= "e_exit_reason";
												$field_label 	= "Exit Reason";
												?>
												<i class="material-icons prefix">speaker_notes_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class=" validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"><?php
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
												$field_name 	= "username";
												$field_label 	= "Login Username";
												?>
												<i class="material-icons prefix">lock_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" <?php if ($cmd == 'edit') { ?> readonly <?php } ?> value="<?php if (isset(${$field_name})) {
																																														echo ${$field_name};
																																													} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																																				echo ${$field_name . "_valid"};
																																																			} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>
											<div class="input-field col m4 s12">
												<?php
												$field_name 	= "u_password";
												$field_label 	= "Login Password";
												?>
												<i class="material-icons prefix">lock_outline</i>
												<input id="<?= $field_name; ?>" type="password" name="<?= $field_name; ?>" <?php if ($cmd == 'edit') { ?> readonly <?php } ?> value="<?php if (isset(${$field_name})) {
																																															echo ${$field_name};
																																														} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																																					echo ${$field_name . "_valid"};
																																																				} ?>">
												<label for="<?= $field_name; ?>">
													<?= $field_label; ?>
													<span class="color-red"> * <?php
																				if (isset($error[$field_name])) {
																					echo $error[$field_name];
																				} ?>
													</span>
												</label>
											</div>

											<div class="input-field col m3 s12 custom_margin_bottom_col">
												<?php
												$field_name 	= "user_type";
												$field_label 	= "Login User Type";
												?>
												<i class="material-icons prefix pt-2">person_outline</i>

												<div class="select2div">
													<select name="user_type" id="user_type" class="select2 browser-default select2-hidden-accessible validate <?php if (isset($user_type_valid)) {
																																									echo $user_type_valid;
																																								} ?>">
														<?php if ($cmd == 'add') { ?>
															<option value="">Select </option>
															<option value="Sub Users" <?php if (isset($user_type) && $user_type == "Sub Users") { ?> selected="selected" <?php } ?>>Sub Users</option>
														<?php } else if ($cmd == 'edit') { ?>
															<option value="<?php echo $user_type; ?>" selected="selected"><?php echo $user_type; ?></option>
														<?php } ?>
													</select>
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"> * <?php
																					if (isset($error[$field_name])) {
																						echo $error[$field_name];
																					} ?>
														</span>
													</label>
												</div>
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
											<div class="input-field col m4 s12"></div>
											<div class="input-field col m4 s12">
												<button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add"><?php echo $button_val; ?></button>
											</div>
											<div class="input-field col m4 s12"></div>
										</div>
										<div class="col m4 s12"></div>
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
									<?php
									if (isset($id) && $id > 0) { ?>
										<form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd2=" . $cmd2 . "&detail_id=" . $detail_id . "&active_tab=tab2") ?>" method="post">
											<input type="hidden" name="is_Submit_Education" value="Y" />
											<input type="hidden" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
											<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																								echo encrypt($_SESSION['csrf_session']);
																							} ?>">
											<input type="hidden" name="active_tab" value="tab2" />

											<div class="row">
												<h4>Education</h4>
												<div class="input-field col m12 s12">
													<?php
													$field_name 	= "e_school";
													$field_label 	= "School";
													?>
													<i class="material-icons prefix">school</i>
													<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																		echo ${$field_name};
																																	} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																								echo ${$field_name . "_valid"};
																																							} ?>">
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"> * <?php
																					if (isset($error[$field_name])) {
																						echo $error[$field_name];
																					} ?>
														</span>
													</label>
												</div>
											</div>
											<div class="row">
												<div class="input-field col m6 s12">
													<?php
													$field_name 	= "date_from";
													$field_label 	= "From";
													?>
													<i class="material-icons prefix">date_range</i>
													<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																		echo ${$field_name};
																																	} ?>" class="datepicker validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"> * <?php
																					if (isset($error[$field_name])) {
																						echo $error[$field_name];
																					} ?>
														</span>
													</label>
												</div>
												<div class="input-field col m6 s12">
													<?php
													$field_name 	= "date_to";
													$field_label 	= "To";
													?>
													<i class="material-icons prefix">date_range</i>
													<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																		echo ${$field_name};
																																	} ?>" class="datepicker validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"> * <?php
																					if (isset($error[$field_name])) {
																						echo $error[$field_name];
																					} ?>
														</span>
													</label>
												</div>
											</div>
											<div class="row">
												<div class="input-field col m6 s12">
													<?php
													$field_name 	= "degree_name";
													$field_label 	= "Degree";
													?>
													<i class="material-icons prefix">local_library</i>
													<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																		echo ${$field_name};
																																	} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																								echo ${$field_name . "_valid"};
																																							} ?>">
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"> * <?php
																					if (isset($error[$field_name])) {
																						echo $error[$field_name];
																					} ?>
														</span>
													</label>
												</div>
												<div class="input-field col m6 s12">
													<?php
													$field_name 	= "study_area";
													$field_label 	= "Area of Study";
													?>
													<i class="material-icons prefix">local_library</i>
													<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																		echo ${$field_name};
																																	} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																								echo ${$field_name . "_valid"};
																																							} ?>">
													<label for="<?= $field_name; ?>">
														<?= $field_label; ?>
														<span class="color-red"> * <?php
																					if (isset($error[$field_name])) {
																						echo $error[$field_name];
																					} ?>
														</span>
													</label>
												</div>
											</div>
											<div class="row">
												<div class="input-field col m4 s12"></div>
												<div class="input-field col m2 s12">
													<button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add"><?php echo $button_edu; ?></button>
												</div>
												<div class="input-field col m3 s12">
													<?php
													if (isset($id) && $id > 0) { ?>
														<?php
														if (isset($cmd2) && $cmd2 != "") { ?>
															<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd2=add&active_tab=tab2") ?>">
																Add New Eduction
															</a>
													<?php }
													} ?>
												</div>
												<div class="input-field col m3 s12"></div> 
											</div>
										</form>
									<?php } else { ?>
										<div class="card-alert card red">
											<div class="card-content white-text">
												<p>Please add master record first</p>
											</div>
											<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									<?php } ?>
								</div>
								<?php
								if (isset($id) && $id > 0) { ?>
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
																			AND a.subscriber_users_id 	= '" . $subscriber_users_id . "' 
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
																							<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&cmd2=edit&active_tab=tab2&id=" . $id . "&detail_id=" . $data['id']) ?>">
																								<i class="material-icons dp48">edit</i>
																							</a>
																							&nbsp;&nbsp;
																							<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&cmd2=delete&active_tab=tab2&id=" . $id . "&detail_id=" . $data['id']) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
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
								<?php } ?>
							</div>
							<!--Info Tab End-->

							<!--Experience Tab Begin-->
							<div id="experience" class="active" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
																					echo "block";
																				} else {
																					echo "none";
																				} ?>;">
								<div class="card-panel">
									<?php
									if (isset($id) && $id > 0) { ?>
										<form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd3=" . $cmd3 . "&detail_id=" . $detail_id . "&active_tab=tab3") ?>" method="post">
											<input type="hidden" name="is_Submit_experience" value="Y" />
											<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
											<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
											<input type="hidden" name="cmd3" value="<?php if (isset($cmd3)) echo $cmd3; ?>" />
											<input type="hidden" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
											<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																								echo encrypt($_SESSION['csrf_session']);
																							} ?>">
											<input type="hidden" name="active_tab" value="tab3" />

											<div id="experience_field">
												<div class="row">
													<h4>Experience</h4> 
													<div class="input-field col m6 s12">
														<i class="material-icons prefix">work_outline</i>
														<input id="e_job_title" type="text" name="e_job_title" value="<?php if (isset($e_job_title)) {
																															echo $e_job_title;
																														} ?>" class="validate <?php if (isset($e_job_title_valid)) {
																																						echo $e_job_title_valid;
																																					} ?>">
														<label for="e_job_title">Job Title</label>
													</div> 
													<div class="input-field col m6 s12">
														<i class="material-icons prefix">work_outline</i>
														<input id="e_job_role" type="text" name="e_job_role" value="<?php if (isset($e_job_role)) {
																														echo $e_job_role;
																													} ?>" class="validate <?php if (isset($e_job_role_valid)) {
																																					echo $e_job_role_valid;
																																				} ?>">
														<label for="e_job_role">Job Role</label>
													</div> 
												</div>
												<div class="row"> 
													<div class="input-field col m6 s12">
														<i class="material-icons prefix">date_range</i>
														<input id="e_date_from" type="text" name="e_date_from" class="datepicker" value="<?php if (isset($e_date_from_valid)) {
																																				echo $e_date_from_valid;
																																			} ?>">
														<label for="e_date_from">From</label>
													</div> 
													<div class="input-field col m6 s12">
														<i class="material-icons prefix">date_range</i>
														<input id="e_date_to" type="text" name="e_date_to" class="datepicker" value="<?php if (isset($e_date_to_valid)) {
																																			echo $e_date_to_valid;
																																		} ?>">
														<label for="e_date_to">To</label>
													</div> 
												</div>
												<div class="row"> 
													<div class="input-field col m6 s12">
														<i class="material-icons prefix">business</i>
														<input id="e_company" type="text" name="e_company" value="<?php if (isset($e_company)) {
																														echo $e_company;
																													} ?>" class="validate <?php if (isset($e_company_valid)) {
																																					echo $e_company_valid;
																																				} ?>">
														<label for="e_company">Company</label>
													</div> 
													<div class="input-field col m6 s12">
														<i class="material-icons prefix">description_outline</i>
														<input id="e_job_description" type="text" name="e_job_description" value="<?php if (isset($e_job_description)) {
																																		echo $e_job_description;
																																	} ?>"  class="validate <?php if (isset($e_job_description_valid)) {
																																									echo $e_job_description_valid;
																																								} ?>">
														<label for="e_job_description">Job Description</label>
													</div>
												</div>
												<div class="row">
													<div class="input-field col m4 s12"></div>
													<div class="input-field col m2 s12">
														<button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add"><?php echo $button_exp; ?></button>
													</div>
													<div class="input-field col m3 s12">
														<?php
														if (isset($id) && $id > 0) { ?> 
															<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd3=add&active_tab=tab3") ?>">
																Add New Experience
															</a>
														<?php  } ?>
													</div>
													<div class="input-field col m3 s12"></div>
												</div>
											</div>
										</form>
									<?php } else { ?>
										<div class="card-alert card red">
											<div class="card-content white-text">
												<p>Please add master record first</p>
											</div>
											<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									<?php } ?>
								</div>
								<?php
								if (isset($id) && $id > 0) { ?>
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
																			AND a.subscriber_users_id 	= '" . $subscriber_users_id . "' 
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
																							<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&cmd3=edit&active_tab=tab3&id=" . $id . "&detail_id=" . $data['id']) ?>">
																								<i class="material-icons dp48">edit</i>
																							</a>
																							&nbsp;&nbsp;
																							<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&cmd3=delete&active_tab=tab3&id=" . $id . "&detail_id=" . $data['id']) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
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
								<?php } ?>
							</div>
							<!--Experience Tab End-->
							<!-- Employment History Begin-->
							<div id="employment_history" class="active" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab4')) {
																							echo "block";
																						} else {
																							echo "none";
																						} ?>;">
								<div class="card-panel">
									<?php
									if (isset($id) && $id > 0) { ?>
										<form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd4=" . $cmd4 . "&detail_id=" . $detail_id . "&active_tab=tab4") ?>" method="post">
											<input type="hidden" name="is_Submit_history" value="Y" />
											<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
											<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
											<input type="hidden" name="cmd4" value="<?php if (isset($cmd4)) echo $cmd4; ?>" />
											<input type="hidden" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
											<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																								echo encrypt($_SESSION['csrf_session']);
																							} ?>">
											<input type="hidden" name="active_tab" value="tab4" />

											<div id="employment_history_field">
												<div class="row">
													<h4>Employment History</h4>
													<br>
													<div class="input-field col m6 s12 custom_margin_bottom_col">
														<i class="material-icons prefix">work_outline</i>
														<div class="select2div">
															<select id="designation_id" name="designation_id"
																class="select2 browser-default select2-hidden-accessible validate 	  <?php if (isset($designation_id_valid)) {
																																		echo $designation_id_valid;
																																	} ?>">
																<option value="">Select Designation</option>
																<?php
																$sql1 		= "	SELECT a.*
																				FROM " . $selected_db_name . ".designations a
																				WHERE a.enabled = 1 AND a.subscriber_users_id = '" . $subscriber_users_id . "' 
																				ORDER BY a.designation ";
																$result1 	= $db->query($conn, $sql1);
																$count1 	= $db->counter($result1);
																if ($count1 > 0) {
																	$row1	= $db->fetch($result1);
																	foreach ($row1 as $data) { ?>
																		<option value="<?php echo $data['id']; ?>" <?php if (isset($designation_id) && $designation_id == $data['id']) { ?> selected="selected" <?php } ?>><?php echo $data['designation']; ?></option>
																<?php }
																} ?>
															</select>
															<label for="designation_id">Designation</label>
														</div>
													</div> 
													<div class="col m6 s12 input-field custom_margin_bottom_col  ">
													<i class="material-icons prefix">business</i>

														<div class="select2div">
															<?php
															$sql1 		= "	SELECT a.*
																			FROM " . $selected_db_name . ".departments a
																			WHERE a.enabled = 1 
																			AND a.subscriber_users_id = '" . $subscriber_users_id . "' 
																			ORDER BY a.department_name ";
															// echo $sql1; 
															?>
															<select id="dept_id" name="dept_id" class="select2 browser-default select2-hidden-accessible validate  <?php if (isset($dept_id_valid)) {
																														echo $dept_id_valid;
																													} ?>">
																<option value="">Select Department</option>
																<?php
																$result1 	= $db->query($conn, $sql1);
																$count1 	= $db->counter($result1);
																if ($count1 > 0) {
																	$row1	= $db->fetch($result1);
																	foreach ($row1 as $data) { ?>
																		<option value="<?php echo $data['id']; ?>" <?php if (isset($dept_id) && $dept_id == $data['id']) { ?> selected="selected" <?php } ?>><?php echo $data['department_name']; ?></option>
																<?php }
																} ?>
															</select>
															<label for="dept_id">Department</label>
														</div>
													</div>
												</div> <br>
												<div class="row">
													<div class="input-field col m6 s12 custom_margin_bottom_col">
													<i class="material-icons prefix">aspect_ratio</i>

														<div class="select2div ">
															<select id="scale_id" name="scale_id" class="select2 browser-default select2-hidden-accessible validate  <?php if (isset($scale_id_valid)) {
																														echo $scale_id_valid;
																													} ?>">
																<option value="">Select Scale</option>
																<?php
																$sql1 		= "	SELECT a.*, b.level_name
																FROM " . $selected_db_name . ".hr_scales a
																INNER JOIN scale_levels b ON b.id = a.scale_level
																WHERE a.enabled = 1 AND a.subscriber_users_id = '" . $subscriber_users_id . "' 
																ORDER BY b.level_name, a.scale_name";
																$result1 	= $db->query($conn, $sql1);
																$count1 	= $db->counter($result1);
																if ($count1 > 0) {
																	$row1	= $db->fetch($result1);
																	foreach ($row1 as $data) { ?>
																		<option value="<?php echo $data['id']; ?>" <?php if (isset($scale_id) && $scale_id == $data['id']) { ?> selected="selected" <?php } ?>><?php echo $data['scale_name']; ?> (<?php echo $data['level_name']; ?>)</option>
																<?php }
																} ?>
															</select>
															<label for="scale_id">Scale</label>
														</div>
													</div>
													<div class="input-field col m6 s12 custom_margin_bottom_col">
														<i class="material-icons prefix">group_work</i>
														<div class="select2div">
															<select id="entry_type" name="entry_type" class="select2 browser-default select2-hidden-accessible validate <?php if (isset($entry_type_valid)) {
																															echo $entry_type_valid;
																														} ?>">
																<option value="">Select Entry Type</option>
																<option value="New Hiring" <?php if (isset($entry_type) && $entry_type == "New Hiring") { ?> selected="selected" <?php } ?>>New Hiring</option>
																<option value="Transfer" <?php if (isset($entry_type) && $entry_type == "Transfer") { ?> selected="selected" <?php } ?>>Transfer</option>
																<option value="Promotion" <?php if (isset($entry_type) && $entry_type == "Promotion") { ?> selected="selected" <?php } ?>>Promotion</option>
																<option value="Increament" <?php if (isset($entry_type) && $entry_type == "Increament") { ?> selected="selected" <?php } ?>>Increament</option>
															</select>
															<label for="entry_type">Entry Type</label>
														</div>
													</div>
												</div>
												<br>
												<div class="row">
													<div class=" input-field col m6 s12 custom_margin_bottom_col ">
													<i class="material-icons prefix">group_work</i>

														<div class=" select2div ">
															<select id="employment_type" name="employment_type" class="select2 browser-default select2-hidden-accessible validate  <?php if (isset($employment_type_valid)) {
																																	echo $employment_type_valid;
																																} ?>">
																<option value="">Select Employment Type</option>
																<option value="Probation" <?php if (isset($employment_type) && $employment_type == "Probation") { ?> selected="selected" <?php } ?>>Probation</option>
																<option value="Regular" <?php if (isset($employment_type) && $employment_type == "Regular") { ?> selected="selected" <?php } ?>>Regular</option>
																<option value="Permanent" <?php if (isset($employment_type) && $employment_type == "Permanent") { ?> selected="selected" <?php } ?>>Permanent</option>
																<option value="Contract" <?php if (isset($employment_type) && $employment_type == "Contract") { ?> selected="selected" <?php } ?>>Contract</option>
																<option value="Part Time" <?php if (isset($employment_type) && $employment_type == "Part Time") { ?> selected="selected" <?php } ?>>Part Time</option>
																<option value="Full Time" <?php if (isset($employment_type) && $employment_type == "Full Time") { ?> selected="selected" <?php } ?>>Full Time</option>
															</select>
															<label for="employment_type">Employment Type</label>
														</div>
													</div> 
													<div class="input-field col m6 s12">
														<i class="material-icons prefix">date_range</i>
														<input id="emp_history_entry_date" type="text" name="emp_history_entry_date" class="datepicker validate <?php if (isset($emp_history_entry_date_valid)) {
																																									echo $emp_history_entry_date_valid;
																																								} ?>" value="<?php if (isset($emp_history_entry_date)) {
																																													echo $emp_history_entry_date;
																																												} ?>">
														<label for="emp_history_entry_date">Starting Date</label>
													</div>  
													<div class="input-field col m6 s12">
														<i class="material-icons prefix">attach_money</i>
														<input id="increament_amount" type="text" name="increament_amount" class="twoDecimalNumber validate <?php if (isset($increament_amount_valid)) {
																																								echo $increament_amount_valid;
																																							} ?>" value="<?php if (isset($increament_amount)) {
																																												echo $increament_amount;
																																											} ?>">
														<input type="hidden" name="increament_amount_old" value="<?php if (isset($increament_amount_old)) {
																														echo $increament_amount_old;
																													} ?>">
														<label for="increament_amount">Increament Hourly Rate</label>
													</div> 
												</div>
												<div class="row">
													<div class="input-field col m4 s12"></div>
													<div class="input-field col m2 s12">
														<button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add"><?php echo $button_edu; ?></button>
													</div>
													<div class="input-field col m3 s12">
														<?php
														if (isset($cmd4) && $cmd4 != "") {
															if (isset($cmd4) && $cmd4 != "") { ?>
																<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd4=add&active_tab=tab4") ?>">
																	Add New Employment History
																</a>
														<?php }
														} ?>
													</div>
													<div class="input-field col m3 s12"></div>
												</div>
											</div>
										</form>
									<?php } else { ?>
										<div class="card-alert card red">
											<div class="card-content white-text">
												<p>Please add master record first</p>
											</div>
											<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>




										</div>
									<?php } ?>
								</div>
								<?php
								if (isset($id) && $id > 0) { ?>
									<div class="section section-data-tables">
										<div class="row">
											<div class="col m12 s12">
												<div class="card">
													<div class="card-content">
														<div class="row">
															<div class="col m12 s12">
																<?php
																$sql_cl1 = "SELECT a.*, b.department_name, c.designation, d.scale_name, e.level_name
													FROM " . $selected_db_name . ".hr_emp_employment_history a
													INNER JOIN " . $selected_db_name . ".departments b ON b.id = a.dept_id
													INNER JOIN " . $selected_db_name . ".designations c ON c.id = a.designation_id
													INNER JOIN " . $selected_db_name . ".hr_scales d ON d.id = a.scale_id
													INNER JOIN scale_levels e ON e.id = d.scale_level
													WHERE a.enabled 		= 1 
													AND a.subscriber_users_id 	= '" . $subscriber_users_id . "' 
													AND a.emp_id			= '" . $id . "' 
													ORDER BY a.id DESC ";
																$result_cl1 	= $db->query($conn, $sql_cl1);
																$count_cl1 	= $db->counter($result_cl1);
																if ($count_cl1 > 0) { ?>
																	<table id="page-length-option" class="display">
																		<thead>
																			<tr>
																				<th>S.No</th>
																				<th>Designation <br>Scale</th>
																				<th>Department</th>
																				<th>Employment Type</th>
																				<th>Entry Type <br> Starting Date</th>
																				<th>Increment Amount</th>
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
																						<td><?php echo $data['designation']; ?> <br><?php echo $data['scale_name']; ?> (<?php echo $data['level_name']; ?>)</td>
																						<td><?php echo $data['department_name']; ?></td>
																						<td><?php echo $data['employment_type']; ?></td>
																						<td><?php echo $data['entry_type']; ?><br><?php echo dateformat2($data['emp_history_entry_date']); ?></td>
																						<td><?php echo $data['increament_amount']; ?></td>
																						<td>
																							<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&cmd4=edit&active_tab=tab4&id=" . $id . "&detail_id=" . $data['id']) ?>">
																								<i class="material-icons dp48">edit</i>
																							</a>
																							&nbsp;&nbsp;
																							<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&cmd4=delete&active_tab=tab4&id=" . $id . "&detail_id=" . $data['id']) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
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
								<?php } ?>
							</div>
							<!-- Employment History END-->

							<!-- Allowances or Benefits Begin-->
							<div id="allowances_or_benefits" class="active" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab5')) {
																								echo "block";
																							} else {
																								echo "none";
																							} ?>;">
								<div class="card-panel">
									<?php
									if (isset($id) && $id > 0) { ?>
										<form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&id=" . $id . "&cmd5=" . $cmd5 . "&detail_id=" . $detail_id . "&active_tab=tab5") ?>" method="post">
											<input type="hidden" name="is_Submit_allowances" value="Y" />
											<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
											<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
											<input type="hidden" name="cmd5" value="<?php if (isset($cmd5)) echo $cmd5; ?>" />
											<input type="hidden" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
											<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																								echo encrypt($_SESSION['csrf_session']);
																							} ?>">
											<input type="hidden" name="active_tab" value="tab5" />

											<div id="allowances_or_benefits_field">
												<div class="row">
													<h4>Allowances or Benefits</h4>
													<div class="input-field col m12 s12">
														<i class="material-icons prefix">person_outline</i>
														<input id="entry_desc" type="text" name="entry_desc" class="validate <?php if (isset($entry_desc_valid)) {
																																	echo $entry_desc_valid;
																																} ?>" value="<?php if (isset($entry_desc)) {
																																					echo $entry_desc;
																																				} ?>">
														<label for="entry_desc">Entry Detail</label>
													</div>
												</div>
												<div class="row">
													<div class="input-field col m6 s12">
														<i class="material-icons prefix">speaker_notes_outline</i>
														<select id="entry_type2" name="entry_type" class="validate <?php if (isset($entry_type_valid)) {
																														echo $entry_type_valid;
																													} ?>">
															<option value="">Select Entry Type</option>
															<option value="Allowance" <?php if (isset($entry_type) && $entry_type == "Allowance") { ?> selected="selected" <?php } ?>>Allowance</option>
															<option value="Benefit" <?php if (isset($entry_type) && $entry_type == "Benefit") { ?> selected="selected" <?php } ?>>Benefit</option>
														</select>
														<label for="entry_type2">Entry Type</label>
													</div>
													<div class="input-field col m6 s12">
														<i class="material-icons prefix">attach_money</i>
														<input id="total_amount" type="text" name="total_amount" class="validate <?php if (isset($total_amount_valid)) {
																																		echo $total_amount_valid;
																																	} ?>" value="<?php if (isset($total_amount)) {
																																						echo $total_amount;
																																					} ?>">
														<label for="total_amount">Amount</label>
													</div>
												</div>
												<div class="row">
													<div class="input-field col m4 s12"></div>
													<div class="input-field col m4 s12">
														<button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add"><?php echo $button_edu; ?></button>
													</div>
													<div class="input-field col m4 s12"></div>
												</div>
											</div>
										</form>
									<?php } else { ?>
										<div class="card-alert card red">
											<div class="card-content white-text">
												<p>Please add master record first</p>
											</div>
											<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									<?php } ?>
								</div>
								<?php
								if (isset($id) && $id > 0) { ?>
									<div class="section section-data-tables">
										<div class="row">
											<div class="col m12 s12">
												<div class="card">
													<div class="card-content">
														<div class="row">
															<div class="col m12 s12">
																<?php
																$sql_cl1 = "SELECT a.*
													FROM " . $selected_db_name . ".hr_other_allowances_or_benefits a
													WHERE a.enabled 		= 1 
													AND a.subscriber_users_id 	= '" . $subscriber_users_id . "' 
													AND a.emp_id			= '" . $id . "' 
													ORDER BY a.id DESC ";
																$result_cl1 	= $db->query($conn, $sql_cl1);
																$count_cl1 	= $db->counter($result_cl1);
																if ($count_cl1 > 0) { ?>
																	<table id="page-length-option" class="display">
																		<thead>
																			<tr>
																				<th>S.No</th>
																				<th>Entry Detail</th>
																				<th>Entry Type</th>
																				<th>Amount</th>
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
																						<td><?php echo $data['entry_desc']; ?></td>
																						<td><?php echo $data['entry_type']; ?></td>
																						<td><?php echo $data['total_amount']; ?></td>
																						<td>
																							<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&cmd5=edit&active_tab=tab5&id=" . $id . "&detail_id=" . $data['id']) ?>">
																								<i class="material-icons dp48">edit</i>
																							</a>
																							&nbsp;&nbsp;
																							<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&cmd5=delete&active_tab=tab5&id=" . $id . "&detail_id=" . $data['id']) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
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
								<?php } ?>
							</div>
							<!-- Allowances or Benefits END-->
						</div>
					</div>
				</section>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->