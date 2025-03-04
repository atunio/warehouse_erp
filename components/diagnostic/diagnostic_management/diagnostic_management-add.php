<?php
if (!isset($module)) {
	require_once('../../../../conf/functions.php');
	disallow_direct_school_directory_access();
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];


$sql_cl 		= "	SELECT DISTINCT a2.id, a2.assignment_no, b.po_no, a.po_id, b2.sub_location_name, b2.sub_location_type,
							GROUP_CONCAT(DISTINCT CONCAT( '', COALESCE(c2.first_name, ''), ' ', COALESCE(c2.middle_name, ''), ' ', COALESCE(c2.last_name, ''), ' (', COALESCE(c2.username, ''), ')') ) AS task_user_details
					FROM users_bin_for_diagnostic a2
					INNER JOIN warehouse_sub_locations b2 ON b2.id = a2.location_id
					INNER JOIN users c2 ON c2.id = a2.bin_user_id 
					INNER JOIN purchase_order_detail_receive a ON a.sub_location_id  = b2.id
					INNER JOIN purchase_orders b ON b.id = a.po_id
					WHERE a.enabled = 1
					AND a2.id = '" . $detail_id . "' 
					GROUP BY a.po_id ";
$result_cl 		= $db->query($conn, $sql_cl);
$count_cl 		= $db->counter($result_cl);
$page_heading 	= "Diagnostic";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col s12">
			<!-- <div class="container"> -->
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
											<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">
												List
											</a>
										<?php } ?>
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
								<?php } ?><br>
								<div class="row">
									<div class="text_align_right">
										<?php
										$table_columns	= array('SNo', 'AssignmentNo', 'PO No', 'Location', 'User Detail', 'Actions');
										$k 				= 0;
										foreach ($table_columns as $data_c1) { ?>
											<label>
												<input type="checkbox" value="<?= $k ?>" name="table_columns[]" class="filled-in toggle-column" data-column="<?= set_table_headings($data_c1) ?>" checked="checked">
												<span><?= $data_c1 ?></span>
											</label>&nbsp;&nbsp;
										<?php
											$k++;
										} ?>
									</div>
								</div>
								<div class="row">
									<div class="col s12">
										<table id="page-length-option" class="display pagelength50_3">
											<thead>
												<tr>
													<?php
													$headings = "";
													foreach ($table_columns as $data_c) {
														if ($data_c == 'SNo') {
															$headings .= '<th class="sno_width_60 col-' . set_table_headings($data_c) . '">' . $data_c . '</th>';
														} else {
															$headings .= '<th class="col-' . set_table_headings($data_c) . '">' . $data_c . '</th> ';
														}
													}
													echo $headings;
													?>
												</tr>
											</thead>
											<tbody>
												<?php
												$i = 0;
												$col_no = 0;
												if ($count_cl > 0) {
													$row_cl = $db->fetch($result_cl);
													foreach ($row_cl as $data) {
														$detail_id2 			= $data['po_id'];
														$bin_for_diagnostic_id 	= $data['id'];?>
														<tr>
															<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[$col_no]); ?>">
																<?php echo $i + 1;
																$col_no++; ?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[$col_no]);?>">
																<?php echo $data['assignment_no'];  
																$col_no++;?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[$col_no]); ?>"><?php echo $data['po_no'];
																																$col_no++; ?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[$col_no]);
																			$col_no++; ?>">
																<?php echo $data['sub_location_name']; ?>
																<?php if ($data['sub_location_type'] != "") echo " (" . $data['sub_location_type'] . ")"; ?>
															</td>
															<td class="col-<?= set_table_headings($table_columns[$col_no]); ?>"><?php echo $data['task_user_details'];
																																$col_no++; ?>
															</td>
															<td class="text-align-center col-<?= set_table_headings($table_columns[$col_no]); ?>">
																<?php
																if (po_permisions2("Diagnostic", 10) == 1) { ?>
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=10&page=profile&cmd=edit&id=" . $detail_id2 . "&assignment_id=" . $bin_for_diagnostic_id . "&active_tab=tab6") ?>">
																		<i class="material-icons dp48">list</i>
																	</a>
																<?php }
																$col_no++;
																?>
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
			<!-- </div> -->

			<!-- <div class="content-overlay"></div> -->
		</div>
	</div>
</div>