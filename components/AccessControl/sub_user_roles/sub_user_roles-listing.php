<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
extract($_REQUEST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($cmd) && ($cmd == 'delete') && access("delete_perm") == 0) {
	$error['msg'] = "You do not have edit permissions.";
} else {
	if (isset($cmd) && $cmd == 'delete') {
		$sql_check 		= "	SELECT * FROM sub_users_role_permissions a 
							WHERE a.enabled = 1 
							AND a.role_id = '" . $id . "' ";
		$result_check 	= $db->query($conn, $sql_check);
		$count_check 	= $db->counter($result_check);
		if ($count_check == 0) {
			$sql_ee2 	= " UPDATE sub_users_roles SET enabled = 0 WHERE id = '" . $id . "' ";
			$ok_del = $db->query($conn, $sql_ee2);
			if ($ok_del) {
				$msg['msg_success'] = "<span class='color-green'>Role has been deleted.";
			}
		} else {
			$error['msg'] = "This Role is in use.";
		}
	}
}
//if(isset($is_filtered) && $is_filtered == 'Y'){
if (empty($error)) {
	$sql_cl = "	SELECT a.* FROM sub_users_roles a 
					WHERE a.enabled = 1 AND a.subscriber_users_id='" . $subscriber_users_id . "' "; // AND a.role_name	!= 'Super Admin'
	if (isset($role_name) && $role_name != "") {
		$sql_cl .= " AND a.role_name like '%" . $role_name . "%'";
	}
	$result_cl 	= $db->query($conn, $sql_cl);
	$count_cl 	= $db->counter($result_cl);
	if ($count_cl == 0) {
		$error['msg'] = "Sorry!, No role found, Please create role first and set permisions.";
	}
}
//}
$page_heading = "Create Role or Set Permissions For Sub User "; ?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<div class="section section-data-tables">
				<div class="row">
					<div class="col s12">
						<div class="card custom_margin_card_table_top">
							<div class="card-content custom_padding_card_content_table_top_bottom"> 
								<div class="row">
									<div class="input-field col m6 s12" style="margin-top: 3px; margin-bottom: 3px;">
										<h6 class="media-heading">
											<?php echo $page_heading; ?>
										</h6>
									</div>
									<div class="input-field col m6 s12" style="text-align: right; margin-top: 3px; margin-bottom: 3px;">
										<?php  
										if (access("add_perm") == 1) { ?>
											<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=add&cmd2=add") ?>">
												New
											</a>
										<?php }?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Page Length Options -->
				<div class="row">
					<div class="col s12">
						<div class="card custom_margin_card_table_top">
							<div class="card-content custom_padding_card_content_table_top">
								<br>
								<div class="row">
									<div class="text_align_right">
										<?php 
										$table_columns	= array('Role Name', 'Actions');
										$k 				= 0;
										foreach($table_columns as $data_c1){?>
											<label>
												<input type="checkbox" value="<?= $k?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1)?>" checked="checked">
												<span><?= $data_c1?></span>
											</label>&nbsp;&nbsp;
										<?php 
											$k++;
										}?> 
									</div>
								</div>
								<div class="row">
									<div class="col s12">
										<table id="page-length-option" class="display pagelength50_3">
											<thead>
												<tr>
													<?php
													$headings = "";
													foreach($table_columns as $data_c){
														if($data_c == 'SNo'){
															$headings .= '<th class="sno_width_60 col-'.set_table_headings($data_c).'">'.$data_c.'</th>';
														}
														else{
															$headings .= '<th class="col-'.set_table_headings($data_c).'">'.$data_c.'</th> ';
														}
													} 
													echo $headings;
													?>
												</tr>
											</thead>
											<tbody>
												<?php
												if ($count_cl > 0) {
													$i = 0;
													$row_cl = $db->fetch($result_cl);
													foreach ($row_cl as $data) {
														$i = $i + 1; ?>
														<tr>
															<td class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $data['role_name']; ?></td>
															<td class="text-align-center col-<?= set_table_headings($table_columns[1]);?>">
																<?php if (access("view_perm") == 1) { ?>
																	<a class="waves-effect waves-light green darken-1  btn gradient-45deg-light-green-cyan box-shadow-none border-round mr-1 mb-1" title="Set Permissions" class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=permisions&cmd=edit&id=" . $data['id']) ?>">
																		<i class="material-icons dp48">folder</i>
																	</a>
																<?php } ?>
																<?php if ($data['edit_lock'] != 1 && access("view_perm") == 1) { ?>
																	<a class="waves-effect waves-light  btn gradient-45deg-light-blue-cyan box-shadow-none border-round mr-1 mb-1" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&id=" . $data['id']) ?>">
																		<i class="material-icons dp48">edit</i>
																	</a>
																	<?php
																	$sql1 		= " SELECT * FROM sub_users_role_permissions 
																					WHERE enabled = 1 
																					AND role_id = '" . $data['id'] . "' ";
																	$result1 	= $db->query($conn, $sql1);
																	$count1 	= $db->counter($result1);
																	if ($count1 == 0) { ?>
																		<?php if (access("delete_perm") == 1) { ?>
																			<a class="waves-effect waves-light  btn gradient-45deg-red-pink box-shadow-none border-round mr-1 mb-1" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=delete&id=" . $data['id']) ?>" onclick="return confirm('Are you sure! You want to Delete Role?')">
																				<i class="material-icons dp48">delete</i>
																			</a>
																		<?php } ?>
																<?php }
																} ?>
															</td>
														</tr>
												<?php }
												} ?>
											<tfoot>
												<tr>
													<th class="text-align-center">Role Name</th>
													<th class="text-align-center">Actions</th>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Multi Select -->
			</div><!-- START RIGHT SIDEBAR NAV -->
			<?php include('sub_files/right_sidebar.php'); ?>
		</div>
	</div>
</div>