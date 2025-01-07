<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$product_desc		= "xyz";
	$address			= "address";
	$product_category	= "1";
	$inventory_status	= "1";
	$total_stock		= "1";
	$detail_desc		= "detail_desc";
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

$title_heading = "All Offers ";

if ($cmd == 'edit' && isset($id)) {
	$sql_ee				= "	SELECT a.*, b.category_name, c.status_name
							FROM products a
							INNER JOIN product_categories b ON b.id = a.product_category
							LEFT JOIN inventory_status c ON c.id = a.inventory_status
							WHERE 1=1 
							AND a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee			= $db->query($conn, $sql_ee);
	$row_ee				= $db->fetch($result_ee);
	$product_desc		= $row_ee[0]['product_desc'];
	$category_name		= $row_ee[0]['category_name'];
	$status_name		= $row_ee[0]['status_name'];
	$product_category	= $row_ee[0]['product_category'];
	$inventory_status	= $row_ee[0]['inventory_status'];
	$total_stock		= $row_ee[0]['total_stock'];
	$detail_desc		= $row_ee[0]['detail_desc'];
	$product_uniqueid	= $row_ee[0]['product_uniqueid'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
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
					<h4 class="card-title">Product Info</h4><br>
					<div class="row">
						<div class="input-field col m3 s12">
							<?php
							$field_name 	= "product_uniqueid";
							$field_label 	= "Product ID";
							?>
							<i class="material-icons prefix">description</i>
							<input id="<?= $field_name; ?>" type="text" disabled required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
							<label for="<?= $field_name; ?>">
								<?= $field_label; ?>
								<span class="color-red"> <?php
															if (isset($error[$field_name])) {
																echo $field_name;
															} ?>
								</span>
							</label>
						</div>
						<div class="input-field col m6 s12">
							<?php
							$field_name 	= "product_desc";
							$field_label 	= "Product Descripton";
							?>
							<i class="material-icons prefix">description</i>
							<input id="<?= $field_name; ?>" type="text" disabled required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																	echo ${$field_name};
																																} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																							echo ${$field_name . "_valid"};
																																						} ?>">
							<label for="<?= $field_name; ?>">
								<?= $field_label; ?>
								<span class="color-red">* <?php
															if (isset($error[$field_name])) {
																echo $field_name;
															} ?>
								</span>
							</label>
						</div>
						<div class="input-field col m3 s12">
							<?php
							$field_name 	= "product_category";
							$field_label 	= "Category";
							$sql1 			= "SELECT * FROM product_categories WHERE enabled = 1 ORDER BY category_name ";
							$result1 		= $db->query($conn, $sql1);
							$count1 		= $db->counter($result1);
							?>
							<i class="material-icons prefix">question_answer</i>
							<div class="select2div">
								<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" disabled class=" <?php if (isset(${$field_name . "_valid"})) {
																													echo ${$field_name . "_valid"};
																												} ?>">
									<option value="">Select</option>
									<?php
									if ($count1 > 0) {
										$row1	= $db->fetch($result1);
										foreach ($row1 as $data2) { ?>
											<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['category_name']; ?></option>
									<?php }
									} ?>
								</select>
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $field_name;
																} ?>
									</span>
								</label>
							</div>
						</div>
						<div class="input-field col m12 s12">
							<?php
							$field_name 	= "detail_desc";
							$field_label 	= "Detail Description";
							?>
							<i class="material-icons prefix">description</i>
							<textarea id="<?= $field_name; ?>" name="<?= $field_name; ?>" disabled class="materialize-textarea validate "><?php if (isset(${$field_name})) {
																																				echo ${$field_name};
																																			} ?></textarea>
							<label for="<?= $field_name; ?>">
								<?= $field_label; ?>
								<span class="color-red"> <?php
															if (isset($error[$field_name])) {
																echo $field_name;
															} ?>
								</span>
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col s12">
			<div class="container">
				<div class="section section-data-tables">
					<!-- Page Length Options -->
					<h4 class="card-title">List of All Offers</h4>
					<div class="row">
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<?php
									$sql_cl		= "	SELECT a.*, a1.product_qty, a1.product_price, a1.product_offer_desc, b.product_desc, c.vender_name
													FROM offers a
													left JOIN offer_detail a1 ON a1.offer_id = a.id
													left JOIN products b ON b.id = a1.product_id
													INNER JOIN venders c ON c.id = a.vender_id
													WHERE 1=1 
													AND a1.product_id = '" . $id . "'
													ORDER BY a.enabled DESC, a.id DESC, a1.id DESC   "; // echo $sql_cl;
									$result_cl	= $db->query($conn, $sql_cl);
									$count_cl	= $db->counter($result_cl);
									?>
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display">
												<thead>
													<tr>
														<?php
														$headings = '<th class="sno_width_60">S.No</th>
																	<th>Offer ID</th>
																	<th>Offer Date</th>
																	<th>Vender</th>
																	<th>Quantity</th>
																	<th>Price</th>
																	<th>Detail</th>
																	<th>Action</th>';
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
															$detail_id = $data['id'];  ?>
															<tr>
																<?php
																// product_desc product_categories inventory_status total_stock detail_desc
																?>
																<td style="text-align: center;"><?php echo $i + 1; ?></td>
																<td><?php echo $data['offer_no']; ?></td>
																<td><?php echo dateformat2($data['offer_date']); ?></td>
																<td><?php echo ucwords(strtolower($data['vender_name'])); ?></td>
																<td><?php echo $data['product_qty']; ?></td>
																<td><?php echo $data['product_price']; ?></td>
																<td>
																	<?php
																	$detail_desc = $data['product_offer_desc'];
																	if ($detail_desc != '') {
																		echo substr($detail_desc, 0, 50) . "";
																		if (strlen($detail_desc) > 50) {
																			echo "...";
																		}
																	} ?>
																</td>
																<td class="text-align-center">
																	<?php
																	if (access("view_perm") == 1) { ?>
																		<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=offer_detail&cmd=edit&id=" . $id . "&detail_id=" . $detail_id) ?>">
																			<i class="material-icons dp48">list</i>
																		</a> &nbsp;&nbsp;
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
			</div>

			<div class="content-overlay"></div>
		</div>

	</div><br><br><br><br>
	<!-- END: Page Main-->