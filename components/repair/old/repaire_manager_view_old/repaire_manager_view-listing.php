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
if (isset($cmd) && ($cmd == 'delete') && access("delete_perm") == 0) {
	$error['msg'] = "You do not have edit permissions.";
} else {
	if (isset($cmd) && $cmd == 'delete') {
		$sql_c_upd = "DELETE FROM users_bin_for_repair  WHERE id = '" . $detail_id . "' ";
		$enabe_ok = $db->query($conn, $sql_c_upd);
		if ($enabe_ok) {
			$msg2['msg_success'] = "Record has been deleted.";
		} else {
			$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
		}
	}
}

$sql_cl			= " SELECT  COUNT(b2.id) AS total_qty, g.product_uniqueid, h.category_name, b2.stock_grade, b2.sub_location, 
							i.sub_location_name, i.sub_location_type 
					FROM product_stock b2 
					LEFT JOIN products g ON g.id = b2.product_id
					LEFT JOIN product_categories h ON h.id = g.product_category 
					LEFT JOIN warehouse_sub_locations i ON i.id = b2.sub_location
					WHERE b2.p_total_stock > 0
					AND b2.p_inventory_status = 19 ";
if (isset($flt_product_id) && $flt_product_id != "") {
	$sql_cl 	.= " AND a.product_uniqueid = '" . $flt_product_id . "' ";
}
if (isset($flt_bin_id) && $flt_bin_id != "") {
	$sql_cl 	.= " AND b2.sub_location = '" . $flt_bin_id . "' ";
}
$sql_cl			.= "GROUP BY b2.sub_location, g.product_uniqueid, b2.stock_grade 
					ORDER BY  b2.sub_location, g.product_uniqueid, b2.stock_grade "; // echo $sql_cl;
$result_cl		= $db->query($conn, $sql_cl);
$count_cl		= $db->counter($result_cl);

$sql_cl2			= " SELECT DISTINCT a3.id, a3.category_name, 
							COUNT(a.id) AS qty, IFNULL(devices_per_user_per_day, 0) AS devices_per_user_per_day,  IFNULL(no_of_employees, 0) AS no_of_employees, 
							IFNULL((COUNT(a.id) / (devices_per_user_per_day*no_of_employees)), 0) AS estimated_time_hours
						FROM product_stock a 
						INNER JOIN  products a2 ON a2.id = a.product_id
						INNER JOIN product_categories a3 ON a3.id = a2.product_category
						INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location
						LEFT JOIN formula_category c ON c.product_category = a2.product_category AND c.formula_type = 'Repair' AND c.enabled = 1
						WHERE a.p_total_stock > 0
						AND a.p_inventory_status = 19
						GROUP BY a3.id ";
$result_cl2		= $db->query($conn, $sql_cl2);
$count_cl2		= $db->counter($result_cl2);
$page_heading 	= "List of Bins For Repair ( Manager View)";
?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="col m10 l10">
						<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $page_heading; ?></span></h5>
						<ol class="breadcrumbs mb-0">
							<li class="breadcrumb-item"><a href="home">Home</a>
							</li>
							</li>
							<li class="breadcrumb-item active">List</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12">
			<div class="container">
				<div class="section section-data-tables">
					<div class="row">
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<h4 class="card-title"><?php echo $page_heading; ?></h4>
									<form method="post" autocomplete="off" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page) ?>" enctype="multipart/form-data">
										<input type="hidden" name="is_Submit2" value="Y" />
										<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																							echo encrypt($_SESSION['csrf_session']);
																						} ?>">
										<?php
										if ($count_cl2 > 0) {
											$row_cl = $db->fetch($result_cl2);
											$i = 1;
											foreach ($row_cl as $data) {
												$id 					= $data['id'];
												$category_name			= $data['category_name'];
												$qty 					= $data['qty'];
												$estimated_time_hours	= $data['estimated_time_hours'];
												$field_name 			= "category[" . $id . "]";
												${$field_name} 			= $qty;
												$field_id 				= "category" . $i;
												$field_label 			= $category_name;
												$estimated_time[$id] 	= $estimated_time_hours;
										?>
												<div class="row">
													<div class="input-field col m3 s12">
														<i class="material-icons prefix">apps</i>
														<input id="<?= $field_id; ?>" readonly type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																					echo ${$field_name};
																																				} ?>">
														<label for="<?= $field_id; ?>"><?= $field_label; ?></label>
													</div>
													<?php
													$field_name		= "estimated_time[" . $id . "]";
													$field_id 		= "estimated_time" . $i;
													$field_label 	= "Estimated Time(Hours)";
													?>
													<div class="input-field col m3 s12">
														<i class="material-icons prefix">access_time</i>
														<input id="<?= $field_id; ?>" readonly type="text" name="<?= $field_name; ?>" value="<?php if (isset($estimated_time[$id])) {
																																					echo $estimated_time[$id];
																																				} ?>" class="twoDecimalNumber validate">
														<label for="<?= $field_id; ?>"><?= $field_label; ?></label>

													</div>
												</div>
										<?php
												$i++;
											}
										} ?>
									</form>
								</div>
							</div>
						</div>
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

											<div class="input-field col m3 s12">
												<i class="material-icons prefix">question_answer</i>
												<div class="select2div">
													<?php
													$field_name     = "flt_bin_id";
													$field_label    = "Bin/Location";

													$sql1           = " SELECT b.id,b.sub_location_name, b.sub_location_type
																		FROM product_stock a 
																		INNER JOIN  products a2 ON a2.id = a.product_id
																		INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location 
																		WHERE a.p_total_stock > 0
																		AND a.p_inventory_status = 19
																		GROUP BY b.id ";
													$result1        = $db->query($conn, $sql1);
													$count1         = $db->counter($result1);
													?>
													<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
														<option value="">All</option>
														<?php
														if ($count1 > 0) {
															$row1    = $db->fetch($result1);
															foreach ($row1 as $data2) { ?>
																<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php
																																																					echo $data2['sub_location_name'];
																																																					if ($data2['sub_location_type'] != "") {
																																																						echo "(" . ucwords(strtolower($data2['sub_location_type'])) . ")";
																																																					} ?></option>
														<?php }
														} ?>
													</select>
												</div>
											</div>
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
												<button class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange " type="submit" name="action">Search</button>
												&nbsp;&nbsp;
												<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">All</a>
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
		<div class="col s12">
			<div class="container">
				<div class="section section-data-tables">
					<!-- Page Length Options -->
					<div class="row">
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<input type="hidden" name="module_id" id="module_id" value="<?= $module_id; ?>">
									<?php
									if (isset($error2['msg'])) { ?>
										<div class="row">
											<div class="col 24 s12">
												<div class="card-alert card red lighten-5">
													<div class="card-content red-text">
														<p><?php echo $error2['msg']; ?></p>
													</div>
													<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
											</div>
										</div>
									<?php } else if (isset($msg2['msg_success'])) { ?>
										<div class="row">
											<div class="col 24 s12">
												<div class="card-alert card green lighten-5">
													<div class="card-content green-text">
														<p><?php echo $msg2['msg_success']; ?></p>
													</div>
													<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">×</span>
													</button>
												</div>
											</div>
										</div>
									<?php } ?>
									<h4 class="card-title"><?php echo $page_heading; ?></h4>
									<style>
										.user-list-container {
											display: flex;
											flex-direction: column;
											margin: 10px;
										}

										.user-list {
											display: flex;
											gap: 10px;
											border: 2px dashed #ccc;
											padding: 10px;
											flex-wrap: wrap;
										}

										.user {
											background-color: #f0f0f0;
											padding: 10px;
											cursor: grab;
											border: 1px solid #ddd;
											border-radius: 4px;
										}

										.user1 {
											background-color: #f0f0f0;
											padding: 10px;
											border: 1px solid #ddd;
											border-radius: 4px;
										}

										.drop-row {
											display: flex;
											justify-content: space-between;
											margin: 10px 0;
											align-items: center;
										}

										.location-column {
											width: 40%;
											padding: 10px;
											text-align: center;
											background-color: #f0f0f0;
											border: 1px solid #ddd;
											border-radius: 4px;
										}

										.drop-column {
											width: 55%;
										}

										.drop-box {
											border: 2px dashed #ccc;
											min-height: 100px;
											padding: 10px;
											background-color: #ffffff;
										}

										.drop-box.dragover {
											background-color: #d9f7be;
										}
									</style>
									<div class="row">
										<div class="col s12">
											<div>
												<?php
												// Fetch user assignments
												$sql0 = "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS full_name FROM users u WHERE u.enabled = 1";
												$result0 = $db->query($conn, $sql0);
												$count0	= $db->counter($result0);
												if ($count0 > 0) {
													$users 	= $db->fetch($result0);  ?>
													<div class="user-list-container">
														<div class="user-list" id="user-list">
															<?php
															foreach ($users as $user_data) {
																echo '<div class="user" draggable="true" data-id="' . $user_data['id'] . '">' . $user_data['full_name'] . '</div>';
															} ?>
														</div>
													</div>
												<?php } ?>
												<div>
													<?php
													$sql1 = "SELECT b.id, b.sub_location_name, b.sub_location_type, count(a.id) as qty
														FROM product_stock a 
														INNER JOIN products a2 ON a2.id = a.product_id
														INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location
														WHERE a.p_total_stock > 0
														AND a.p_inventory_status = 19
														GROUP BY b.id";
													$result1 = $db->query($conn, $sql1);
													$count1 = $db->counter($result1);
													if ($count1 > 0) {
														$locations 	= array();
														$row 		= $db->fetch($result1);
														$locations 	= $row;
													}
													foreach ($locations as $location_data) {
														$bin_qty = $location_data['qty']; ?>
														<div class="drop-row">
															<div class="location-column">
																<?php echo $location_data['sub_location_name']; ?>
																<?php if (isset($location_data['sub_location_type'])) echo " (" . $location_data['sub_location_type'] . ")"; ?>
																=> Qty: <?php echo $bin_qty; ?>
															</div>
															<div class="drop-column" style="text-align: center;">
																<div class="drop-box" data-location-id="<?php echo $location_data['id']; ?>">
																	<?php
																	$sql1 = "SELECT b.id, CONCAT(a.first_name, ' ', a.last_name) AS user_full_name, a.profile_pic,  b.location_id, b.bin_user_id
																		FROM users a
																		LEFT JOIN users_bin_for_repair b ON a.id = b.bin_user_id AND b.is_processing_done = '0'
																		WHERE b.location_id = '" . $location_data['id'] . "' ";
																	$result2 = $db->query($conn, $sql1);
																	$count2	= $db->counter($result2);
																	if ($count2 > 0) {
																		$row2 	= $db->fetch($result2);
																		foreach ($row2 as $data_2) {

																			$detail_id2 			= $data_2['id'];
																			$bin_user_id 			= $data_2['bin_user_id'];
																			$location_id 			= $data_2['location_id'];
																			$total_estimated_time 	= 0;

																			$sql_time = "SELECT b.`sub_location_name`, a3.category_name, IFNULL(COUNT(a.id), 0) AS qty, d.bin_user_id, d.location_id, 
																						IFNULL((COUNT(a.id) / e.devices_per_user_per_day), 0) AS estimated_time
																					FROM product_stock a 
																					INNER JOIN  products a2 ON a2.id = a.product_id
																					INNER JOIN product_categories a3 ON a3.id = a2.product_category
																					INNER JOIN warehouse_sub_locations b ON b.id = a.sub_location
																					INNER JOIN `users_bin_for_repair` d ON d.`location_id` = a.`sub_location` AND d.`is_processing_done` = 0
																					LEFT JOIN `formula_category` e ON e.product_category = a2.product_category AND e.formula_type = 'Repair' AND e.enabled = 1
																					WHERE 1=1
																					AND a.p_total_stock > 0
																					AND a.p_inventory_status = 19
																					AND d.bin_user_id = '$bin_user_id' 
																					AND d.location_id = '$location_id' 
																					GROUP BY a.sub_location, a3.category_name";
																			$result_time	= $db->query($conn, $sql_time);
																			$count_time	= $db->counter($result_time);
																			if ($count_time > 0) {
																				$row_time = $db->fetch($result_time);
																				foreach ($row_time as $data_time) {
																					$total_estimated_time += $data_time['estimated_time']; ?>
																			<?php }
																			} ?>
																			<div class="user1" data-id="<?= $data_2['id']; ?>">
																				<img src="app-assets/images/logo/<?php echo $data_2['profile_pic']; ?>" alt="images" style="height:100px !important;" class="circle z-depth-2 responsive-img" />
																				<h5 class=" lighten-4"><?php echo $data_2['user_full_name']; ?>
																					<a class="red-text" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=" . $page . "&cmd=delete&detail_id=" . $detail_id2) ?>" onclick="return confirm('Are you sure, You want to delete the bin from user?')">
																						<i class="material-icons dp48">delete</i>
																					</a>
																				</h5>
																				<h6 class=" lighten-4"><?php echo $total_estimated_time; ?></h6>
																			</div>
																	<?php }
																	}  ?>
																</div>
															</div>
														</div>
													<?php } ?>
												</div>
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
	<script>
		$(document).ready(function() {
			let originalDropBox = null;
			// Drag start event
			$('.user').on('dragstart', function(e) {
				e.originalEvent.dataTransfer.setData('text/plain', $(this).data('id'));
				originalDropBox = $(this).closest('.drop-box');
			});
			// Drag over event
			$('.drop-box, #user-list').on('dragover', function(e) {
				e.preventDefault(); // Allow dropping
				$(this).addClass('dragover');
			});
			// Drag leave event
			$('.drop-box, #user-list').on('dragleave', function() {
				$(this).removeClass('dragover');
			});
			// Drop event
			$('.drop-box, #user-list').on('drop', function(e) {
				e.preventDefault();
				$(this).removeClass('dragover');
				const userId = e.originalEvent.dataTransfer.getData('text/plain');
				var module_id = $("#module_id").val();

				const originalUserElement = $(`[data-id="${userId}"]`);
				if (originalUserElement.length) {
					const newDropBox = $(this);
					const newLocationId = newDropBox.data('location-id') || null;
					const originalLocationId = originalDropBox ? originalDropBox.data('location-id') : 0;
					// Remove existing clones of the user in this drop box

					if (newDropBox.html().trim() == '') {
						// Clone the user element and append it to the new drop box
						if (newLocationId) {
							// Prepare AJAX request data
							let dataString;
							if (originalLocationId && newLocationId !== originalLocationId) {
								dataString = `module_id=${module_id}&type=update_bin_repair&new_bin_id=${newLocationId}&old_bin_id=${originalLocationId}&bin_user_id=${userId}`;
							} else {
								dataString = `module_id=${module_id}&type=assign_bin_repair&bin_id=${newLocationId}&bin_user_id=${userId}`;
							}
							// AJAX request to handle backend logic
							$.ajax({
								type: "POST",
								url: "ajax/ajax_add_entries.php",
								data: dataString,
								success: function(response) {
									newDropBox.html(response);
								},
								error: function() {
									alert('Error processing request.');
								}
							});
						}
					}
				}
			});
		});
	</script>