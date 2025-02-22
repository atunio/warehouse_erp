<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

$sql_cl			= " SELECT a.id, a.location_id, b.sub_location_name, b.sub_location_type, 
							GROUP_CONCAT(DISTINCT CONCAT( '', COALESCE(d.first_name, ''), ' ', COALESCE(d.middle_name, ''), ' ', COALESCE(d.last_name, ''), ' (', COALESCE(d.username, ''), ')') ) AS task_user_details,
							a.add_date
					FROM users_bin_for_processing a
					INNER JOIN warehouse_sub_locations b ON b.id = a.location_id
					INNER JOIN users d ON d.id = a.bin_user_id 
					WHERE 1 = 1 ";
if (po_permisions("ALL Bins") != '1') {
	$sql_cl	.= " AND a.bin_user_id = '" . $_SESSION['user_id'] . "' ";
}
$sql_cl	.= "GROUP BY a.id
			ORDER BY a.id DESC";
// echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);
$page_heading 	= "Bins For Processing";
?>
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
								<?php
								if (isset($error['msg'])) { ?>
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
								<?php } else if (isset($msg['msg_success'])) { ?>
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
								<?php } ?>
								<br>
								<div class="row">
									<div class="text_align_right">
										<?php 
										$table_columns	= array('SNo', 'Location / Bin','Task Users','Assign Date','Actions');
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
												$i = 0;
												if ($count_cl > 0) {
													$row_cl = $db->fetch($result_cl);
													foreach ($row_cl as $data) {
														$id = $data['id']; ?>
														<tr>
															<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
															<td class="col-<?= set_table_headings($table_columns[1]);?>">
																<?php
																echo $data['sub_location_name'];
																if ($data['sub_location_type'] != "") {
																	echo "(" . ucwords(strtolower($data['sub_location_type'])) . ")";
																} ?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[2]);?>">
																<?php
																if ($data['task_user_details'] != "   ()") {
																	echo "" . $data['task_user_details'] . "";
																} ?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[3]);?>"><?php echo "" . dateformat2($data['add_date']) . ""; ?></td>
															<td class="text-align-center col-<?= set_table_headings($table_columns[4]);?>">
																<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&detail_id=" . $id . "&cmd2=add") ?>">
																	<i class="material-icons dp48">list</i>
																</a>&nbsp;&nbsp;
																<a href="components/<?php echo $module_folder; ?>/<?php echo $module; ?>/bin_processing_print.php?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&id=" . $id) ?>" target="_blank">
																	<i class="material-icons dp48">print</i>
																</a>
															</td>
														</tr>
												<?php $i++;
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