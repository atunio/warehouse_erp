<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$task_name					= "xyz " . date('YmdHis');
	$task_assign_to_user_id		= "6";
	$po_id						= "3";
	$task_start_date			= date('d/m/Y');
	$task_type					= "1";
	$task_desc					= "task_desc  " . date('YmdHis');
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if ($cmd == 'edit') {
	$title_heading = "Update Task";
	$button_val = "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add New Task";
	$button_val 	= "Add";
	$id 			= "";
}
if ($cmd == 'edit' && isset($id)) {
	$sql_ee					= "SELECT a.* FROM tasks a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$task_name				= $row_ee[0]['task_name'];
	$task_assign_to_user_id	= $row_ee[0]['task_assign_to_user_id'];
	$po_id					= $row_ee[0]['po_id'];
	$task_start_date		= str_replace("-", "/", convert_date_display($row_ee[0]['task_start_date']));
	$task_type				= $row_ee[0]['task_type'];
	$task_desc				= $row_ee[0]['task_desc'];
	$task_status			= $row_ee[0]['task_status'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {
	$field_name = "task_status";
	if (isset($cmd) && $cmd == 'edit' && isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		$field_name = "task_start_date";
		${$field_name . "1"} = "0000-00-00";
		if (isset(${$field_name}) && ${$field_name} != "") {
			${$field_name . "1"} = convert_date_mysql_slash(${$field_name});
		}
		if (access("edit_perm") == 0) {
			$error['msg'] = "You do not have edit permissions.";
		} else {

			$po_to_user = 0;
			if ($po_id > 0) {
				$sql_dup	= " SELECT a.* 
								FROM tasks a 
								WHERE a.task_assign_to_user_id	= '" . $task_assign_to_user_id . "'
								AND a.po_id	= '" . $po_id . "' 
								AND a.id	!= '" . $id . "'";
				// echo $sql_dup;
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup > 0) {
					$po_to_user = 1;
				}
			}
			if ($po_to_user == 0) {
				$sql_c_up = "UPDATE tasks SET 	  
												task_status				= '" . $task_status . "',   

												update_date				= '" . $add_date . "',
												update_by				= '" . $_SESSION['username'] . "',
												update_by_user_id		= '" . $_SESSION['user_id'] . "',
												update_ip				= '" . $add_ip . "',
												update_timezone			= '" . $timezone . "'
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
							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "task_name";
								$field_label 	= "Write a Task Name";
								?>
								<i class="material-icons prefix">description</i>
								<input id="<?= $field_name; ?>" disabled type="text" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
								$field_name 	= "task_assign_to_user_id";
								$field_label 	= "User";
								$sql1 			= "SELECT * FROM users WHERE enabled = 1 AND user_type != 'admin' ORDER BY first_name, middle_name, last_name ";
								$result1 		= $db->query($conn, $sql1);
								$count1 		= $db->counter($result1);
								?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" disabled name="<?= $field_name; ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																echo ${$field_name . "_valid"};
																															} ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1	= $db->fetch($result1);
											foreach ($row1 as $data2) { ?>
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['first_name']; ?> <?php echo $data2['middle_name']; ?> <?php echo $data2['last_name']; ?></option>
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
							<?php
							if (isset($po_id) && $po_id > 0) { ?>
								<div class="input-field col m4 s12">
									<?php
									$field_name 	= "po_id";
									$field_label 	= "Purchase Order";
									$sql1 			= "SELECT * FROM purchase_orders WHERE enabled = 1 ORDER BY id DESC ";
									$result1 		= $db->query($conn, $sql1);
									$count1 		= $db->counter($result1);
									?>
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" disabled name="<?= $field_name; ?>" class="  validate <?php if (isset(${$field_name . "_valid"})) {
																																	echo ${$field_name . "_valid"};
																																} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1	= $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['po_no']; ?></option>
											<?php }
											} ?>
										</select>
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
							<?php } ?>
						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<?php
								$field_name 	= "task_start_date";
								$field_label 	= "Task Start Date";
								?>
								<i class="material-icons prefix">date_range</i>
								<input id="<?= $field_name; ?>" type="text" disabled name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
								$field_name 	= "task_type";
								$field_label 	= "Task Type";
								$sql1 			= "SELECT * FROM task_types WHERE enabled = 1 ORDER BY task_type DESC ";
								$result1 		= $db->query($conn, $sql1);
								$count1 		= $db->counter($result1);
								?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" disabled name="<?= $field_name; ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																echo ${$field_name . "_valid"};
																															} ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1	= $db->fetch($result1);
											foreach ($row1 as $data2) { ?>
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['task_type']; ?></option>
										<?php }
										} ?>
									</select>
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
							<?php
							if (isset($cmd) && $cmd == 'edit') { ?>
								<div class="input-field col m4 s12">
									<?php
									$field_name 	= "task_status";
									$field_label 	= "Status";
									$sql1 			= "SELECT * FROM task_status WHERE enabled = 1 AND id IN(1, 2, 5) ORDER BY status_name DESC ";
									$result1 		= $db->query($conn, $sql1);
									$count1 		= $db->counter($result1);
									?>
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																														echo ${$field_name . "_valid"};
																													} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1	= $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['status_name']; ?></option>
											<?php }
											} ?>
										</select>
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
							<?php } ?>
						</div>
						<div class="row">
							<div class="input-field col m12 s12">
								<?php
								$field_name 	= "task_desc";
								$field_label 	= "Description";
								?>
								<i class="material-icons prefix">description</i>
								<textarea id="<?= $field_name; ?>" disabled name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
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
	</div>
</div><br><br><br><br>
<!-- END: Page Main-->