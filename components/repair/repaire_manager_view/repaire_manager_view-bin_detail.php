<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
$module_status = 19;
if (isset($cmd) && ($cmd == 'delete') && access("delete_perm") == 0) {
	$error['msg'] = "You do not have edit permissions.";
} else {
	if (isset($cmd) && $cmd == 'delete') {
		$sql_c_upd = "DELETE FROM users_bin_for_processing  WHERE id = '" . $detail_id . "' ";
		$enabe_ok = $db->query($conn, $sql_c_upd);
		if ($enabe_ok) {
			$msg2['msg_success'] = "Record has been deleted.";
		} else {
			$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
		}
	}
}

$sql_cl			= " SELECT count(*) as total_qty, a2.product_uniqueid, a3.category_name, a.stock_grade, a.sub_location, 
							b.sub_location_name, b.sub_location_type
					FROM product_stock a 
					INNER JOIN  products a2 ON a2.id = a.product_id
					INNER JOIN product_categories a3 ON a3.id = a2.product_category
 					INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location
					WHERE a.p_total_stock > 0
					AND a.p_inventory_status = '$module_status' ";
if (isset($flt_product_id) && $flt_product_id != "") {
	$sql_cl 	.= " AND a2.product_uniqueid = '" . $flt_product_id . "' ";
}
if (isset($flt_product_category) && $flt_product_category != "") {
	$sql_cl .= " AND a3.id = '" . $flt_product_category . "' ";
}
$sql_cl			.= "GROUP BY a.sub_location, a2.product_uniqueid, a.stock_grade 
					ORDER BY  a.sub_location, a2.product_uniqueid, a.stock_grade "; // echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);

$page_heading 	= "Bin Details ( Manager View)";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="col m6 l6">
						<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $page_heading; ?></span></h5>
						<ol class="breadcrumbs mb-0">
							<li class="breadcrumb-item"><a href="home">Home</a>
							</li>
							</li>
							<li class="breadcrumb-item active">List</li>
						</ol>
					</div>
					<div class="col s2 m6 l6">
						<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
							Manager View
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12">
			<div class="container">
				<div class="section section-data-tables">
					<!-- Page Length Options -->
					<div class="row">
						<div class="col s12">
							<div class="card">
								<div class="card-content">
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
									<h4 class="card-title"><?php echo $page_heading; ?></h4>
									<form method="post" autocomplete="off" enctype="multipart/form-data" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page) ?>">
										<input type="hidden" name="is_Submit" value="Y" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<div class="row">
											<?php
											$field_name = "flt_product_id";
											$field_label = "Product ID";
											?>
											<div class="input-field col m3 s12">
												<i class="material-icons prefix">person_outline</i>
												<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>">
												<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
											</div>
											<div class="input-field col m3 s12">
												<i class="material-icons prefix">question_answer</i>
												<div class="select2div">
													<?php
													$field_name     = "flt_product_category";
													$field_label    = "Product Category";
													$sql11           = " SELECT a3.id,a3.category_name
																		FROM product_stock a 
																		INNER JOIN  products a2 ON a2.id = a.product_id
																		INNER JOIN product_categories a3 ON a3.id = a2.product_category 
																		WHERE a.p_total_stock > 0
																		AND a.p_inventory_status = '$module_status'
																		GROUP BY a3.id ";
													$result11        = $db->query($conn, $sql11);
													$count11         = $db->counter($result11);
													?>
													<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
														<option value="">All</option>
														<?php
														if ($count11 > 0) {
															$row11    = $db->fetch($result11);
															foreach ($row11 as $data12) { ?>
																<option value="<?php echo $data12['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data12['id']) { ?> selected="selected" <?php } ?>><?php
																																																						echo $data12['category_name']; ?>
																</option>
														<?php }
														} ?>
													</select>
												</div>
											</div>
											<div class="input-field col m3 s12">
												<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button>
												&nbsp;&nbsp;
												<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "") ?>">All</a>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display">
												<thead>
													<tr>
														<?php
														$headings = '<th class="sno_width_60">S.No</th>
																	<th>Location / Bin</th> 
																	<th>ProductID</th> 
																	<th>Category</th> 
																	<th>Condtion</th> 
																	<th>Qty</th> ';
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
															$id = $data['sub_location']; ?>
															<tr>
																<td style="text-align: center;"><?php echo $i + 1; ?></td>
																<td>
																	<?php
																	echo $data['sub_location_name'];
																	if ($data['sub_location_type'] != "") {
																		echo "(" . ucwords(strtolower($data['sub_location_type'])) . ")";
																	} ?>
																</td>
																<td><?php echo $data['product_uniqueid']; ?></td>
																<td><?php echo $data['category_name']; ?></td>
																<td><?php echo $data['stock_grade']; ?></td>
																<td><?php echo $data['total_qty']; ?></td>
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

			<div class="content-overlay"></div>
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>