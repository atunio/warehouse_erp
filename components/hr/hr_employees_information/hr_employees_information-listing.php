<?php
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db = new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"]; 
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 		= $_SESSION["user_id"];

$sql_cl			= "	SELECT a.*
					FROM " . $selected_db_name . ".employee_profile a
					WHERE a.subscriber_users_id = '" . $subscriber_users_id . "'
					ORDER BY a.enabled DESC, a.id DESC "; //echo $sql_cl;
$result_cl 		= $db->query($conn, $sql_cl);
$count_cl 		= $db->counter($result_cl);
$page_heading = "Employees"; ?>
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
											<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=add&active_tab=tab1") ?>">
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
										$table_columns	= array('SNo', 'Emp ID','Emp Code','Full Name / Parent Name','Gender / Date Of Birth','Phone Number / Email','Status','Actions');
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
															$headings .= '<th width="5%" class="col-'.set_table_headings($data_c).'">'.$data_c.'</th>';
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
												$i = 0;
												if ($count_cl > 0) {
													$row_cl = $db->fetch($result_cl);
													foreach ($row_cl as $data) {
														$id	= $data['id']; ?>
														<tr data-id="<?php echo $id; ?>">
															<td class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
															<td class="col-<?= set_table_headings($table_columns[1]);?>"><?php echo $id; ?></td>
															<td class="col-<?= set_table_headings($table_columns[2]);?>"><?php echo $data['emp_code']; ?></td>
															<td class="col-<?= set_table_headings($table_columns[3]);?>"><?php echo $data['e_full_name']; ?><br><?php echo $data['parent_name']; ?></td>
															<td class="col-<?= set_table_headings($table_columns[4]);?>"><?php echo $data['e_gender']; ?><br><?php echo dateformat2($data['e_birth_date']); ?></td>
															<td class="col-<?= set_table_headings($table_columns[5]);?>"><?php echo $data['e_phone']; ?><br><?php echo $data['e_email']; ?></td>
															<td class="col-<?= set_table_headings($table_columns[6]);?>">
																<?php
																if ($data['emp_status'] == 'Active') { ?>
																	<span class="chip green lighten-5">
																		<span class="green-text"><?php echo $data['emp_status']; ?></span>
																	</span>
																<?php } else { ?>
																	<span class="chip red lighten-5"><span class="red-text"><?php echo $data['emp_status']; ?></span></span>
																<?php } ?>
															</td>
															<td class="text-align-center col-<?= set_table_headings($table_columns[7]);?>">
																<a href="javascript:void(0)" class="<?php if ($data['enabled'] == '1') { ?>green-text<?php } else { ?>red-text<?php } ?>" onclick="change_status(this,'<?php echo $id ?>')"><?php echo ($data['enabled'] == '1') ? 'Enable' : 'Disable'; ?></a>
																&nbsp;&nbsp;
																<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add_edit&cmd=edit&active_tab=tab1&id=" . $data['id']) ?>">
																	<i class="material-icons dp48">edit</i>
																</a>
															</td>
														</tr>
												<?php
														$i++;
													}
												}
												if (isset($cmd) && $cmd == 'delete' && isset($id)) {
													$sql_del 			= "	DELETE FROM " . $selected_db_name . ".employee_profile WHERE id = '" . $id . "' ";
													$ok = $db->query($conn, $sql_del);
													if ($ok) {
														$error['msg'] = "Record Deleted Successfully";
													} else {
														$error['msg'] = "There is Error, record did not delete, Please check it again OR contact Support Team.";
													}
												} ?>
											<tfoot>
													<tr>
														<?php echo $headings; ?>
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