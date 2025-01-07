<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$product_id		= "1";
	$vender_id		= "1";
	$product_qty	= "2";
	$product_price	= "500";
	$detail_desc	= "xyz";
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
$title_heading 			= " Offer Info";
if ($cmd == 'edit' && isset($detail_id)) {
	$sql_ee				= "SELECT a.* FROM offers a WHERE a.id = '" . $detail_id . "' "; // echo $sql_ee;
	$result_ee			= $db->query($conn, $sql_ee);
	$row_ee				= $db->fetch($result_ee);
	$offer_id			= $row_ee[0]['id'];
	$offer_id_disp		= $row_ee[0]['offer_no'];
	$product_id			= $row_ee[0]['product_id'];
	$vender_id			=  $row_ee[0]['vender_id'];
	$product_qty		= $row_ee[0]['product_qty'];
	$product_price		= $row_ee[0]['product_price'];
	$detail_desc		= $row_ee[0]['offer_desc'];
	$offer_date			= dateformat2($row_ee[0]['offer_date']);
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
					<div class="col s8 m6 l6">
						<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $title_heading; ?></span></h5>
						<ol class="breadcrumbs mb-0">
							<li class="breadcrumb-item"><?php echo $title_heading; ?>
							</li>
							<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">List</a>
							</li>
						</ol>
					</div>
					<div class="col s2 m3 l3">
						<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
							Products List
						</a>
					</div>
					<div class="col s2 m3 l3">
						<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=offers&cmd=" . $cmd . "&id=" . $id) ?>" data-target="dropdown1">
							Offers
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col s12 m12 l12">
			<div id="Form-advance" class="card card card-default scrollspy">
				<div class="card-content">
					<h4 class="card-title">Detail Form</h4><br>
					<form method="post" autocomplete="off">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" id="cmd" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" id="id" name="id" value="<?php if (isset($id)) echo $id; ?>" />

						<div class="row">
							<div class="input-field col m2 s12">
								<?php
								$field_name 	= "offer_id_disp";
								$field_label 	= "Offer ID";
								?>
								<i class="material-icons prefix">date_range</i>
								<input id="<?= $field_name; ?>" type="text" required="" disabled name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																		echo ${$field_name};
																																	} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																								echo ${$field_name . "_valid"};
																																							} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m2 s12">
								<?php
								$field_name 	= "offer_date";
								$field_label 	= "Offer Date";
								?>
								<i class="material-icons prefix">date_range</i>
								<input id="<?= $field_name; ?>" type="text" required="" disabled name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																		echo ${$field_name};
																																	} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																								echo ${$field_name . "_valid"};
																																							} ?>">
								<label for="<?= $field_name; ?>">
									<?= $field_label; ?>
									<span class="color-red">* <?php
																if (isset($error[$field_name])) {
																	echo $error[$field_name];
																} ?>
									</span>
								</label>
							</div>
							<div class="input-field col m6 s12">
								<?php
								$field_name 	= "vender_id";
								$field_label 	= "Vender";
								$sql1 			= "SELECT * FROM venders WHERE enabled = 1 ORDER BY vender_name ";
								$result1 		= $db->query($conn, $sql1);
								$count1 		= $db->counter($result1);
								?>
								<i class="material-icons prefix">question_answer</i>
								<div class="select2div">
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" disabled class="<?php if (isset(${$field_name . "_valid"})) {
																													echo ${$field_name . "_valid"};
																												} ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1	= $db->fetch($result1);
											foreach ($row1 as $data2) { ?>
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['vender_name']; ?></option>
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
							<div class="input-field col m2 s12">
								<?php
								$check_module_permission = check_module_permission($db, $conn, '9', $_SESSION["user_id"], $_SESSION["user_type"]);
								if ($check_module_permission != "") {
									if (access("edit_perm") == 1) { ?>
										<a href="?string=<?php echo encrypt("module=offers&module_id=9&page=add&cmd=edit&cmd2=add&id=" . $offer_id) ?>" class="btn cyan waves-effect waves-light right" type="submit" name="action">
											Create Purchase Order <i class="material-icons right">send</i>
										</a>
								<?php }
								} ?>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m12 s12">
								<?php
								$field_name 	= "detail_desc";
								$field_label 	= "Detail";
								?>
								<i class="material-icons prefix">description</i>
								<textarea id="<?= $field_name; ?>" name="<?= $field_name; ?>" disabled class="materialize-textarea validate "><?php if (isset(${$field_name})) {
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
					</form>
				</div>
				<?php //include('sub_files/right_sidebar.php');
				?>
			</div>
		</div>

		<div class="col s12">
			<div class="container">
				<div class="section section-data-tables">
					<!-- Page Length Options -->
					<h4 class="card-title">Offer Details</h4>
					<div class="row">
						<div class="col s12">
							<div class="card">
								<div class="card-content">
									<?php
									$sql_cl		= "	SELECT a.*, c.product_desc, d.category_name, c.product_uniqueid
														FROM offer_detail a 
														INNER JOIN offers b ON b.id = a.offer_id
														INNER JOIN products c ON c.id = a.product_id
														INNER JOIN product_categories d ON d.id = c.product_category
														WHERE 1=1 
														AND a.offer_id = '" . $detail_id . "' 
														ORDER BY a.enabled DESC, a.id DESC "; // echo $sql_cl;
									$result_cl	= $db->query($conn, $sql_cl);
									$count_cl	= $db->counter($result_cl);
									?>
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display">
												<thead>
													<tr>
														<?php
														$headings = '	<th class="sno_width_60">S.No</th>
																			<th>Product ID</th>
																			<th>Product Description</th>
																			<th>Category</th>
																			<th>Quantity</th>
																			<th>Price</th>
																			<th>Detail</th>';
														echo $headings; ?>
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
																<td style="text-align: center;"><?php echo $i + 1; ?></td>
																<td><?php echo $data['product_uniqueid']; ?></td>
																<td><?php echo ucwords(strtolower($data['product_desc'])); ?></td>
																<td><?php echo $data['category_name']; ?></td>
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
	<div name="product_add_modal" id="product_add_modal" role="dialog" aria-hidden="true" class="modal fade modal" data-focus="false" style=" max-height: 70%;  height: 100%; ">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Product</h4>
			</div>
		</div>
		<div class="row">
			<div class="input-field col m12 s12">

				<?php
				$field_name 	= "product_desc";
				$field_label 	= "Item Descripton";
				?>
				<i class="material-icons prefix">description</i>
				<input id="<?= $field_name; ?>" type="text" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																												echo ${$field_name};
																											} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																		echo ${$field_name . "_valid"};
																																	} ?>">
				<label for="<?= $field_name; ?>">
					<?= $field_label; ?>
					<span class="color-red">* <?php
												if (isset($error[$field_name])) {
													echo $error[$field_name];
												} ?>
					</span>
				</label>
			</div>
		</div><br>
		<div class="row">
			<div class="input-field col m12 s12">
				<?php
				$field_name 	= "product_category";
				$field_label 	= "Category";
				$sql1 			= "SELECT * FROM product_categories WHERE enabled = 1 ORDER BY category_name ";
				$result1 		= $db->query($conn, $sql1);
				$count1 		= $db->counter($result1);
				?>
				<i class="material-icons prefix">question_answer</i>
				<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" validate  <?php if (isset(${$field_name . "_valid"})) {
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
													echo $error[$field_name];
												} ?>
					</span>
				</label>
			</div>
		</div><br>
		<div class="row">
			<div class="input-field col m12 s12">
				<?php
				$field_name 	= "detail_desc";
				$field_label 	= "Detail Description";
				?>
				<i class="material-icons prefix">description</i>
				<textarea id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
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
				<a href="#" name="add_product_btn" id="add_product_btn" class="btn modal-close cyan waves-effect waves-light right">
					Add<i class="material-icons right">send</i>
				</a>
			</div>
			<div class="input-field col m6 s12">
				<a href="#" name="close_product_btn" class="btn modal-close waves-red" />Close</a>
			</div>
		</div>
		<br><br>
	</div>
	<div name="vender_add_modal" id="vender_add_modal" role="dialog" aria-hidden="true" class="modal fade modal" data-focus="false" style=" max-height: 70%;  height: 100%; ">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Add Vender</h4>
			</div>
		</div>
		<div class="row">
			<div class="input-field col m12 s12">
				<?php
				$field_name 	= "vender_name";
				$field_label 	= "Vender Name";
				?>
				<i class="material-icons prefix pt-2">person_outline</i>
				<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																									echo ${$field_name};
																								} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																															echo ${$field_name . "_valid"};
																														} ?>">
				<label for="<?= $field_name; ?>">
					<?= $field_label; ?>
					<span class="color-red">* <?php
												if (isset($error[$field_name])) {
													echo $error[$field_name];
												} ?>
					</span>
				</label>
			</div>
		</div> <br>
		<div class="row">
			<div class="input-field col m12 s12">
				<?php
				$field_name 	= "phone_no";
				$field_label 	= "Vender Phone";
				?>
				<i class="material-icons prefix pt-2">phone</i>
				<input type="text" id="<?= $field_name; ?>" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
		</div>
		<div class="row">
			<div class="input-field col m12 s12">
				<?php
				$field_name 	= "address";
				$field_label 	= "Address";
				?>
				<i class="material-icons prefix">add_location</i>
				<input type="text" id="<?= $field_name; ?>" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																												echo ${$field_name};
																											} ?>">
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
			<div class="input-field col m12 s12">
				<?php
				$field_name 	= "note_about_vender";
				$field_label 	= "Note About Vender";
				?>
				<i class="material-icons prefix">description</i>
				<textarea id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="materialize-textarea validate "><?php if (isset(${$field_name})) {
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
				<a href="#" name="add_vender_btn" id="add_vender_btn" class="btn modal-close cyan waves-effect waves-light right">
					Add<i class="material-icons right">send</i>
				</a>
			</div>
			<div class="input-field col m6 s12">
				<a href="#" name="close_vender_btn" class="btn modal-close waves-red" />Close</a>
			</div>
		</div>
		<br><br>
	</div>
</div>
<br><br><br><br>
<!-- END: Page Main-->
<!-- END: Page Main-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script>
	$(document).on('click', '#add_product_btn', function(e) {
		var product_desc = $("#product_desc").val();
		var product_category = $("#product_category").val();
		var detail_desc = $("#detail_desc").val();

		var cmd = $("#cmd").val();
		var id = $("#id").val();
		var dataString = 'type=add_product&product_desc=' + product_desc + '&product_category=' + product_category + '&detail_desc=' + detail_desc + '&cmd=' + cmd + '&id=' + id;
		$.ajax({
			type: "POST",
			url: "ajax/ajax_add_entries.php",
			data: dataString,
			cache: false,
			success: function(data) {
				if (data != 'Select') {
					$("#product_desc").val("");
					$("#product_category").val("");
					$("#detail_desc").val("");
					$("#product_id").append(data);
				}
			},
			error: function() {
				;
			}
		});
	});
	$(document).on('click', '#add_vender_btn', function(e) {
		var vender_name = $("#vender_name").val();
		var phone_no = $("#phone_no").val();
		var address = $("#address").val();
		var note_about_vender = $("#note_about_vender").val();
		var cmd = $("#cmd").val();
		var id = $("#id").val();
		var dataString = 'type=add_vender&vender_name=' + vender_name + '&phone_no=' + phone_no + '&address=' + address + '&note_about_vender=' + note_about_vender + '&cmd=' + cmd + '&id=' + id;
		$.ajax({
			type: "POST",
			url: "ajax/ajax_add_entries.php",
			data: dataString,
			cache: false,
			success: function(data) {
				if (data != 'Select') {
					$("#vender_name").val("");
					$("#phone_no").val("");
					$("#address").val("");
					$("#note_about_vender").val("");
					$("#vender_id").append(data);
				}
			},
			error: function() {
				;
			}
		});
	});
</script>