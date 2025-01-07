<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_sadmin_directory_access();
}
$db = new mySqlDB;
if ($cmd == 'edit') {
	$title_heading = "Edit Sub Super User";
	$button_val = "Edit";
}
if ($cmd == 'add') {
	$title_heading 	= "Create New Sub Super User";
	$button_val 	= "Create";
	$id 			= "";
}
if (isset($cmd_detail) && $cmd_detail == 'delete') {
	$sql_ee2 	= " DELETE FROM  super_admin_user_roles WHERE id = '" . $detail_id . "' ";
	$ok_del = $db->query($conn, $sql_ee2);
	if ($ok_del) {
		$msg['msg_success'] = "Role has been removed.";
	}
}
$first_name = "";
if ($cmd == 'edit' && isset($id)) {
	$sql_ee 	= "SELECT a.* FROM super_admin a WHERE a.id = '" . $id . "' ";
	$result_ee 	= $db->query($conn, $sql_ee);
	$row_ee 	= $db->fetch($result_ee);

	$username 			= $row_ee[0]['username'];
	$first_name 		= $row_ee[0]['first_name'];
	$middle_name 		= $row_ee[0]['middle_name'];
	$last_name 			= $row_ee[0]['last_name'];
	$a_password 		= $row_ee[0]['a_password'];
	$email 				= $row_ee[0]['email'];
	$phone_no 			= $row_ee[0]['phone_no'];
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
		$sql1_2 		= "	SELECT * FROM super_admin_user_roles WHERE  user_id = '" . $id . "' AND role_id = '" . $add_role_id . "' ";
		$result1_2 	= $db->query($conn, $sql1_2);
		$count2_2 	= $db->counter($result1_2);
		if ($count2_2 > 0) {
			$error['msg'] = "Sorry! This Role is already exist";
		} else {
			$sql1_1 = "INSERT INTO super_admin_user_roles (user_id, role_id, add_date, add_by, add_ip)
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
	if (isset($a_password) && strlen($a_password) < 6) {
		$error['msg'] 	= "Password should be greater than 5 characters.";
	}
	if (isset($a_password) && $a_password == "") {
		$error['msg'] 	= "Password is Required";
	}
	if (isset($username) && $username == "") {
		$error['msg'] 	= "Username is Required";
	}
	if (isset($last_name) && $last_name == "") {
		$error['msg'] 	= "Last Name is Required";
	}
	if (isset($first_name) && $first_name == "") {
		$error['msg'] 	= "Full Name is Required";
	}

	if ($cmd == 'add') {
		$sql1 		= "	SELECT * FROM super_admin WHERE username = '" . $username . "' ";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['msg'] 	= "Sorry! This username is not available, try another.";
		}
		$sql1 		= "	SELECT * FROM super_admin WHERE email = '" . $email . "' ";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['msg'] 	= "Sorry! This email is not available, try another.";
		}
	} else if ($cmd == 'edit') {
		$sql1 		= "	SELECT * FROM super_admin WHERE email = '" . $email . "' AND id != '" . $id . "' ";
		$result1 	= $db->query($conn, $sql1);
		$count2 	= $db->counter($result1);
		if ($count2 > 0) {
			$error['msg'] 	= "Sorry! This email is not available, try another.";
		}
	}
	if (empty($error)) {
		if ($cmd == 'add') {
			$sql6 = "INSERT INTO super_admin (username, a_password, email, phone_no, user_type, first_name, middle_name, last_name, add_date, add_by, add_ip)
					VALUES('" . $username . "', '" . $a_password . "', '" . $email . "', '" . $phone_no . "', 'Sub Super Admin', '" . $first_name . "', '" . $middle_name . "', '" . $last_name . "', '" . $add_date . "', '" . $_SESSION['username_super_admin'] . "', '" . $add_ip . "')";
			$ok = $db->query($conn, $sql6);
			if ($ok) {
				$msg['msg_success'] = "Account Has been created Successfully.";
				$first_name 		= "";
				$username 		= "";
				$a_password 	= "";
				$user_type 		= "";
			} else {
				$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
			}
		} else if ($cmd == 'edit') {
			$sql_c_up = "UPDATE super_admin SET 	first_name 			= '" . $first_name . "',
													middle_name 		= '" . $middle_name . "',
													last_name 			= '" . $last_name . "',
													a_password 			= '" . $a_password . "',
													email 				= '" . $email . "',
													phone_no 			= '" . $phone_no . "',
								 
													update_date 		= '" . $add_date . "',
													update_by 			= '" . $_SESSION['username_super_admin'] . "',
													update_ip 			= '" . $add_ip . "'
						WHERE id = '" . $id . "'";
			$ok = $db->query($conn, $sql_c_up);
			if ($ok) {
				$msg['msg_success'] = "Record Updated Successfully.";
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
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
						<div class="row">

							<div class="input-field col m4 s12">
								<input id="first_name" type="text" name="first_name" value="<?php if (isset($first_name)) {
																								echo $first_name;
																							} ?>">
								<label for="first_name">First Name</label>
							</div>
							<div class="input-field col m4 s12">
								<input id="middle_name" type="text" name="middle_name" value="<?php if (isset($middle_name)) {
																									echo $middle_name;
																								} ?>">
								<label for="middle_name">Middle Name</label>
							</div>
							<div class="input-field col m4 s12">
								<input id="last_name" type="text" name="last_name" value="<?php if (isset($last_name)) {
																								echo $last_name;
																							} ?>">
								<label for="last_name">Last Name</label>
							</div>

						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<input id="username" type="text" <?php if ($cmd == 'edit') { ?> readonly <?php } ?> name="username" value="<?php if (isset($username)) {
																																				echo $username;
																																			} ?>">
								<label for="username">Username</label>
							</div>
							<div class="input-field col m4 s12">
								<input id="a_password" type="password" name="a_password" value="<?php if (isset($a_password)) {
																									echo $a_password;
																								} ?>">
								<label for="a_password">Password</label>
							</div>
							<div class="input-field col m4 s12">
								<input id="email" type="text" name="email" value="<?php if (isset($email)) {
																						echo $email;
																					} ?>">
								<label for="email">Email</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m4 s12">
								<input id="phone_no" type="text" name="phone_no" value="<?php if (isset($phone_no)) {
																							echo $phone_no;
																						} ?>">
								<label for="username">Phone</label>
							</div>
						</div>
						<div class="row">
							<div class="col m12 s12"><br><br></div>
							<div class="col m4 s12"></div>
							<div class="col m4 s12">
								<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12" type="submit" name="action">
									<?php echo $button_val; ?>
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
											$sql_c 		= " SELECT * FROM super_admin_roles a WHERE a.enabled = 1 AND a.role_name != 'Super Admin'  "; //echo $sql_c;
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
				$sql_cl = "	SELECT b.*, a.role_name
						FROM super_admin_roles a
						INNER JOIN super_admin_user_roles b ON b.role_id = a.id
						WHERE a.enabled = 1 AND b.enabled = 1
						AND b.user_id = '" . $id . "' ";
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
				<?php } ?>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div><br><br>
	<!-- END: Page Main-->