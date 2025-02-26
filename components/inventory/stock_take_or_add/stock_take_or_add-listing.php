<?php
if (!isset($module)) {
	require_once('../../../../conf/functions.php');
	disallow_direct_school_directory_access();
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

if (isset($cmd) && ($cmd == 'disabled' || $cmd == 'enabled') && access("delete_perm") == 0) {
	$error['msg'] = "You do not have edit permissions.";
} else {
	if (isset($cmd) && $cmd == 'disabled') {
		$sql_ee				= "SELECT a.* FROM stock_take_or_add a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
		$result_ee			= $db->query($conn, $sql_ee);
		$row_ee				= $db->fetch($result_ee);
		$entry_type			= $row_ee[0]['entry_type'];
		$stock_id			= $row_ee[0]['stock_id'];
 
		$sql_c_upd = "UPDATE stock_take_or_add set 	enabled 	= 0,
													update_date = '" . $add_date . "' ,
													update_by 	= '" . $_SESSION['username'] . "' ,
													update_ip 	= '" . $add_ip . "'
					WHERE id = '" . $id . "' ";
		$enabe_ok = $db->query($conn, $sql_c_upd);
		if ($enabe_ok) {
			if($entry_type == 'Add'){
				$p_total_stock = 0;
			}
			else if($entry_type == 'Take'){
				$p_total_stock = 1;
			}
			$sql_c_up = "UPDATE product_stock	SET p_total_stock 				= $p_total_stock, 
 													update_date					= '" . $add_date . "',
													update_by					= '" . $_SESSION['username'] . "',
													update_by_user_id			= '" . $_SESSION['user_id'] . "',
													update_ip					= '" . $add_ip . "',
													update_timezone				= '" . $timezone . "',
													update_from_module_id		= '" . $module_id . "'
						WHERE id = '" . $stock_id . "' ";
			$db->query($conn, $sql_c_up);
 
			$msg['msg_success'] = "Record has been disabled.";
		} else {
			$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
		}
	}
	if (isset($cmd) && $cmd == 'enabled') {

		$sql_ee				= "SELECT a.* FROM stock_take_or_add a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
		$result_ee			= $db->query($conn, $sql_ee);
		$row_ee				= $db->fetch($result_ee);
		$entry_type			= $row_ee[0]['entry_type'];
		$stock_id			= $row_ee[0]['stock_id'];

		$sql_c_upd = "UPDATE stock_take_or_add set enabled = 1,
											update_date = '" . $add_date . "' ,
											update_by 	= '" . $_SESSION['username'] . "' ,
											update_ip 	= '" . $add_ip . "'
					WHERE id = '" . $id . "' ";
		$enabe_ok = $db->query($conn, $sql_c_upd);
		if ($enabe_ok) {
			if($entry_type == 'Add'){
				$p_total_stock = 1;
			}
			else if($entry_type == 'Take'){
				$p_total_stock = 0;
			}
			$sql_c_up = "UPDATE product_stock	SET p_total_stock 				= $p_total_stock, 
 													update_date					= '" . $add_date . "',
													update_by					= '" . $_SESSION['username'] . "',
													update_by_user_id			= '" . $_SESSION['user_id'] . "',
													update_ip					= '" . $add_ip . "',
													update_timezone				= '" . $timezone . "',
													update_from_module_id		= '" . $module_id . "'
						WHERE id = '" . $stock_id . "' ";
			$db->query($conn, $sql_c_up);
			$msg['msg_success'] = "Record has been enabled.";
		}
	}
}

$sql_cl 		= "	SELECT  a.*, a2.serial_no,
							b.sub_location_name as sub_location_name, b.sub_location_type as sub_location_type, 
 							b2.product_uniqueid, b2.product_desc, c2.category_name, c2.category_type
					FROM stock_take_or_add a 
					INNER JOIN product_stock a2 ON a2.id = a.stock_id
					INNER JOIN products b2 ON b2.id = a2.product_id
					INNER JOIN product_categories c2 ON c2.id = b2.product_category
 					LEFT JOIN warehouse_sub_locations b ON b.id = a.location_id
					WHERE a.enabled = 1
 					ORDER BY a.enabled DESC, a.id DESC  ";  //echo $sql_cl;
$result_cl 		= $db->query($conn, $sql_cl);
$count_cl 		= $db->counter($result_cl);
$page_heading 	= "List of " . $main_menu_name;
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
											<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=add&cmd2=add") ?>">
												New
											</a>
										<?php } ?>
										<?php
										if (access("add_perm") == 1) { ?>
											<a class="btn cyan waves-effect waves-light custom_btn_size" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=import") ?>">
												Import
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
								<?php } ?>
								<br>
								<div class="row">
									<div class="text_align_right">
										<?php 
										$table_columns	= array('SNo', 'ProductID','Description','Category', 'SerialNo', 'Stock Type', 'Add Location', 'Take/In Date','Actions');
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
														$id = $data['id'];  
														$col_no = 1;?>
														<tr> 
															<td style="text-align: center;" class="col-<?= set_table_headings($table_columns[0]);?>"><?php echo $i + 1; ?></td>
															<td class="col-<?= set_table_headings($table_columns[$col_no]);?>"><?php echo $data['product_uniqueid']; $col_no++;?></td>
															<td class="col-<?= set_table_headings($table_columns[$col_no]);?>"><?php echo $data['product_desc']; $col_no++;?></td>
															<td class="col-<?= set_table_headings($table_columns[$col_no]);?>"><?php echo $data['category_name']; $col_no++;?></td>
															<td class="col-<?= set_table_headings($table_columns[$col_no]);?>"><?php echo $data['serial_no']; $col_no++;?></td>
															<td class="col-<?= set_table_headings($table_columns[$col_no]);?>"><?php echo $data['entry_type']; $col_no++;?></td>
															<td class="col-<?= set_table_headings($table_columns[$col_no]);?>">
																<?php 
																$col_no++;
																echo $data['sub_location_name']; ?>
																<?php if($data['sub_location_type'] != "") echo " (".$data['sub_location_type'].")"; ?>
															</td>
 															<td class="col-<?= set_table_headings($table_columns[$col_no]);?>"><?php echo dateformat2_2($data['add_date']);  $col_no++; ?></td>
 															<td class="text-align-center col-<?= set_table_headings($table_columns[$col_no]);?>">
																<?php
																 $col_no++; 
																if ($data['enabled'] == 0 && access("edit_perm") == 1) { ?>
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=enabled&id=" . $id) ?>">
																		<i class="material-icons dp48">add</i>
																	</a> &nbsp;&nbsp;
																<?php } else if ($data['enabled'] == 1 && access("delete_perm") == 1) { ?>
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing&cmd=disabled&id=" . $id) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																		<i class="material-icons dp48">delete</i>
																	</a>&nbsp;&nbsp;
																<?php } ?>
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
			<!-- </div>

			<div class="content-overlay"></div> -->
		</div>
	</div>
</div>