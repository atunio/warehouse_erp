<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$vender_name	= "xyz";
	$address		= "address";
	$phone_no		= "876544321";
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if ($cmd == 'edit') {
	$title_heading = "Update Customer";
	$button_val = "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Customer";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee			= "SELECT a.* FROM customers a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee		= $db->query($conn, $sql_ee);
	$row_ee			= $db->fetch($result_ee);
	$customer_name					= $row_ee[0]['customer_name'];
	$phone_primary					= $row_ee[0]['phone_primary'];
	$phone_secondary				= $row_ee[0]['phone_secondary'];
	$email_primary					= $row_ee[0]['email_primary'];
	$email_secondary				= $row_ee[0]['email_secondary'];
	$address_primary				= $row_ee[0]['address_primary'];
	$address_primary_city			= $row_ee[0]['address_primary_city'];
	$address_primary_state			= $row_ee[0]['address_primary_state'];
	$address_primary_country_id		= $row_ee[0]['address_primary_country'];
	$address_secondary				= $row_ee[0]['address_secondary'];
	$address_secondary_city			= $row_ee[0]['address_secondary_city'];
	$address_secondary_state		= $row_ee[0]['address_secondary_state'];
	$address_secondary_country_id	= $row_ee[0]['address_secondary_country'];
	$fax_no							= $row_ee[0]['fax_no'];
	$note_about_customer			= $row_ee[0]['note_about_customer'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {

	$field_name = "customer_name";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name]			= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	$field_name = "phone_primary";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	if (empty($error)) {
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM customers a 
								WHERE a.customer_name		= '" . $customer_name . "'
								AND a.phone_primary			= '" . $phone_primary . "'
								AND a.email_primary			= '" . $email_primary . "'";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".customers(subscriber_users_id, customer_name, phone_primary,phone_secondary,email_primary,
								email_secondary,address_primary,address_primary_city,address_primary_state,address_primary_country,
								address_secondary,address_secondary_city, address_secondary_state,address_secondary_country,
								fax_no,note_about_customer, add_date, add_by, add_ip)
							VALUES('" . $subscriber_users_id . "', '" . $customer_name . "', '" . $phone_primary . "','" . $phone_secondary . "', '" . $email_primary . "' , 
							'" . $email_secondary . "' ,'" . $address_primary . "', '" . $address_primary_city . "','" . $address_primary_state . "','" . $address_primary_country_id . "',
							'" . $address_secondary . "','" . $address_secondary_city . "','" . $address_secondary_state . "','" . $address_secondary_country_id . "',
							'" . $fax_no . "','" . $note_about_customer . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id 			= mysqli_insert_id($conn);
						$customer_no 	= "C" . $id;
						$sql6 			= "UPDATE customers SET customer_no = '" . $customer_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
						$customer_name = $phone_primary = $phone_secondary = $email_primary =  $email_secondary =  $address_primary =  $address_primary_city =
							$address_primary_state =   $address_primary_country_id =   $address_secondary =   $address_secondary_city =
							$address_secondary_state = $address_secondary_country_id = $fax_no = $note_about_customer =  "";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM customers a 
								WHERE a.customer_name		= '" . $customer_name . "'
								AND a.phone_primary			= '" . $phone_primary . "'
								AND a.email_primary			= '" . $email_primary . "'
								AND a.id		   != '" . $id . "'";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE customers SET customer_name				= '" . $customer_name . "', 
													phone_primary				= '" . $phone_primary . "', 
													phone_secondary				= '" . $phone_secondary . "', 
													email_primary				= '" . $email_primary . "', 
													email_secondary				= '" . $email_secondary . "', 
													address_primary				= '" . $address_primary . "', 
													address_primary_city		= '" . $address_primary_city . "', 
													address_primary_state		= '" . $address_primary_state . "', 
													address_primary_country		= '" . $address_primary_country_id . "', 
													address_secondary			= '" . $address_secondary . "', 
													address_secondary_city		= '" . $address_secondary_city . "', 
													address_secondary_state		= '" . $address_secondary_state . "', 
													address_secondary_country	= '" . $address_secondary_country_id . "', 
													fax_no						= '" . $fax_no . "', 
													note_about_customer			= '" . $note_about_customer . "',  
													update_date 				= '" . $add_date . "',
													update_by 					= '" . $_SESSION['username'] . "',
													update_ip 					= '" . $add_ip . "'
								WHERE id = '" . $id . "'   ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
					} else {
						$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
				}
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
						<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $title_heading; ?></span></h5>
						<ol class="breadcrumbs mb-0">
							<li class="breadcrumb-item"><?php echo $title_heading; ?>
							</li>
							<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">List</a>
							</li>
						</ol>
					</div>
					<div class="col s2 m6 l6">
						<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
							List
						</a>
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
					<h4 class="card-title">Detail Form</h4><br>
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<div class="row">
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "customer_name";
								$field_label 	= "Customer Name";
								?>
								<i class="material-icons prefix pt-2">person_outline</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "phone_primary";
								$field_label 	= "Customer Primary Phone No";
								?>
								<i class="material-icons prefix pt-2">phone</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "phone_secondary";
								$field_label 	= "Customer Secondary Phone No";
								?>
								<i class="material-icons prefix pt-2">phone</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																			echo ${$field_name . "_valid"};
																																		} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "email_primary";
								$field_label 	= "Customer Email Primary";
								?>
								<i class="material-icons prefix">mail_outline</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "email_secondary";
								$field_label 	= "Customer Email Secondary";
								?>
								<i class="material-icons prefix">mail_outline</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>

							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "address_primary";
								$field_label 	= "Address Primary";
								?>
								<i class="material-icons prefix">add_location</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "address_primary_city";
								$field_label 	= "Address Primary City";
								?>
								<i class="material-icons prefix">add_location</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "address_primary_state";
								$field_label 	= "Address Primary State";
								?>
								<i class="material-icons prefix">add_location</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>

							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "address_primary_country_id";
								$field_label 	= "Address Primary Country";
								$sql1 			= "	SELECT * FROM countries WHERE enabled = 1 ORDER BY country_name ";
								$result1 		= $db->query($conn, $sql1);
								$count1 		= $db->counter($result1); ?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1	= $db->fetch($result1);
											foreach ($row1 as $data2) { ?>
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['country_name']; ?></option>
										<?php }
										} ?>
									</select>
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

							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "address_secondary";
								$field_label 	= "Address Secondary";
								?>
								<i class="material-icons prefix">add_location</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "address_secondary_city";
								$field_label 	= "Address Secondary City";
								?>
								<i class="material-icons prefix">add_location</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "address_secondary_state";
								$field_label 	= "Address Secondary State";
								?>
								<i class="material-icons prefix">add_location</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "address_secondary_country";
								$field_label 	= "Address Secondary Country";
								$sql1 			= "	SELECT * FROM countries WHERE enabled = 1 ORDER BY country_name ";
								$result1 		= $db->query($conn, $sql1);
								$count1 		= $db->counter($result1); ?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1	= $db->fetch($result1);
											foreach ($row1 as $data2) { ?>
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['country_name']; ?></option>
										<?php }
										} ?>
									</select>
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
							<div class="input-field col m3 s12">
								<?php
								$field_name 	= "fax_no";
								$field_label 	= "Fax No";
								?>
								<i class="material-icons prefix">phone</i>
								<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red"> <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m12 s12">
								<?php
								$field_name 	= "note_about_customer";
								$field_label 	= "Note About Customer";
								?>
								<i class="material-icons prefix">description</i>
								<textarea id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
																																			echo ${$field_name};
																																		} ?></textarea>
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
							<div class="input-field col m6 s12">
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
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


	</div><br><br><br><br>
	<!-- END: Page Main-->