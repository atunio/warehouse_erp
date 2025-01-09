<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$vender_name	= "xyz";
	$address		= "address";
	$phone_no		= "876544321";
	$warranty_period_in_days	= "15";
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if ($cmd == 'edit') {
	$title_heading = "Update Vendor";
	$button_val = "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Vendor";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee			= "SELECT a.* FROM venders a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee		= $db->query($conn, $sql_ee);
	$row_ee			= $db->fetch($result_ee);
	$vender_name		= $row_ee[0]['vender_name'];
	$phone_no			=  $row_ee[0]['phone_no'];
	$address			= $row_ee[0]['address'];
	$note_about_vender	= $row_ee[0]['note_about_vender'];
	$vender_type		= $row_ee[0]['vender_type'];
	$warranty_period_in_days	= $row_ee[0]['warranty_period_in_days'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {

	$field_name = "vender_type";
	if (isset(${$field_name}) && (${$field_name} == "" || ${$field_name} == "0")) {
		$error[$field_name]			= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "address";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name]			= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "phone_no";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "vender_name";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "warranty_period_in_days";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}

	if (empty($error)) {
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM venders a 
								WHERE a.vender_name	= '" . $vender_name . "'
								AND a.phone_no		= '" . $phone_no . "'
								AND a.address		= '" . $address . "'
								AND a.vender_type	= '" . $vender_type . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".venders(subscriber_users_id, vender_name, address, phone_no, vender_type, note_about_vender,warranty_period_in_days, add_date, add_by, add_ip)
							VALUES('" . $subscriber_users_id . "', '" . $vender_name . "', '" . $address . "', '" . $phone_no  . "', '" . $vender_type  . "', '" . $note_about_vender  . "', '". $warranty_period_in_days ."','" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {

						$id 			= mysqli_insert_id($conn);
						$vender_no 		= "V" . $id;
						$sql6 			= "UPDATE venders SET vender_no = '" . $vender_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						if (isset($error['msg'])) unset($error['msg']);
						$msg['msg_success'] = "Record has been added successfully.";
						$vender_name = $address = $phone_no = "";
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
				$sql_dup	= " SELECT a.* FROM venders a 
								WHERE a.vender_name	= '" . $vender_name . "'
								AND a.phone_no		= '" . $phone_no . "'
								AND a.address		= '" . $address . "'
								AND a.vender_type	= '" . $vender_type . "'
								AND a.id		   != '" . $id . "'";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE venders SET vender_name					= '" . $vender_name . "', 
													phone_no        			= '" . $phone_no . "',
													address                     = '" . $address . "', 
													vender_type					= '" . $vender_type . "', 
													note_about_vender			= '" . $note_about_vender . "', 
													warranty_period_in_days		= '" . $warranty_period_in_days . "', 
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
								$field_name 	= "vender_name";
								$field_label 	= "Vendor Name";
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
							<div class="input-field col m2 s12">
								<?php
								$field_name 	= "phone_no";
								$field_label 	= "Vendor Phone";
								?>
								<i class="material-icons prefix pt-2">phone</i>
								<input type="text" id="<?= $field_name; ?>" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
								$field_name 	= "address";
								$field_label 	= "Address";
								?>
								<i class="material-icons prefix">add_location</i>
								<input type="text" id="<?= $field_name; ?>" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																echo ${$field_name};
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

							<div class="input-field col m2 s12">
								<?php
								$field_name 	= "vender_type";
								$field_label 	= "Vendor Type";
								$sql1 			= "	SELECT * FROM vender_types 
													WHERE enabled = 1 
													AND subscriber_users_id = '" . $subscriber_users_id . "' 
													ORDER BY type_name ";
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
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['type_name']; ?></option>
										<?php }
										} ?>
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
							<div class="input-field col m2 s12">
								<?php
								$field_name 	= "warranty_period_in_days";
								$field_label 	= "Warranty in Days";
								?>
								<i class="material-icons prefix pt-2">question_answer</i>
								<input type="number" id="<?= $field_name; ?>" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
							
							<div class="input-field col m12 s12">
								<?php
								$field_name 	= "note_about_vender";
								$field_label 	= "Note About Vendor";
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