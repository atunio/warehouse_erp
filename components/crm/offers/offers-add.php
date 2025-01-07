<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$vender_id		= "1";
	$offer_date 	= date('d/m/Y');
	$offer_desc		= "offer_desc: " . date('Ymd');
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd2 == 'add') {
	$product_id			= "2001";
	$product_qty		= "1";
	$product_price		= "1100";
	$product_offer_desc	= "product_offer_desc: " . date('Ymd');
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

if (!isset($is_Submit) && $cmd == 'edit' && isset($msg['msg_success']) && isset($id)) {
	echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id));
}
if (isset($cmd3) && $cmd3 == 'disabled') {
	$sql_c_upd = "UPDATE offer_detail set enabled = 0,
											update_date = '" . $add_date . "' ,
											update_by 	= '" . $_SESSION['username'] . "' ,
											update_ip 	= '" . $add_ip . "'
				WHERE id = '" . $detail_id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg2['msg_success'] = "Record has been disabled.";
	} else {
		$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
	}
}
if (isset($cmd3) && $cmd3 == 'enabled') {
	$sql_c_upd = "UPDATE offer_detail set 	enabled 	= 1,
										update_date = '" . $add_date . "' ,
										update_by 	= '" . $_SESSION['username'] . "' ,
										update_ip 	= '" . $add_ip . "'
				WHERE id = '" . $detail_id . "' ";
	$enabe_ok = $db->query($conn, $sql_c_upd);
	if ($enabe_ok) {
		$msg2['msg_success'] = "Record has been enabled.";
	}
}

if ($cmd == 'edit') {
	$title_heading = "Update Offer";
	$button_val = "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Add Offer";
	$button_val 	= "Add";
	$id 			= "";
}

if ($cmd2 == 'edit') {
	$title_heading2  = "Update Product";
	$button_val2 	= "Save";
}
if ($cmd2 == 'add') {
	$title_heading2	= "Add Product";
	$button_val2 	= "Add";
	$detail_id		= "";
}

if ($cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee				= "SELECT a.* FROM offers a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee			= $db->query($conn, $sql_ee);
	$row_ee				= $db->fetch($result_ee);
	$vender_id			=  $row_ee[0]['vender_id'];
	$offer_desc			= $row_ee[0]['offer_desc'];
	$offer_date			= str_replace("-", "/", convert_date_display($row_ee[0]['offer_date']));
}
if ($cmd2 == 'edit' && isset($detail_id) && $detail_id > 0) {
	echo  $sql_ee				= "SELECT a.* FROM offer_detail a WHERE a.id = '" . $detail_id . "' "; // echo $sql_ee;
	$result_ee			= $db->query($conn, $sql_ee);
	$row_ee				= $db->fetch($result_ee);
	$product_id			= $row_ee[0]['product_id'];
	$product_qty		= $row_ee[0]['product_qty'];
	$product_price		= $row_ee[0]['product_price'];
	$product_offer_desc	= $row_ee[0]['product_offer_desc'];
}
extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {

	$field_name = "offer_desc";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "vender_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "offer_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		$offer_date1 = "0000-00-00";
		if (isset($offer_date) && $offer_date != "") {
			$offer_date1 = convert_date_mysql_slash($offer_date);
		}
		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM offers a 
								WHERE a.vender_id	= '" . $vender_id . "'
								AND a.offer_date	= '" . $offer_date1 . "'
								AND a.offer_desc	= '" . $offer_desc . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".offers(subscriber_users_id, vender_id, offer_date, offer_desc, add_date, add_by, add_ip)
							 VALUES('" . $subscriber_users_id . "', '" . $vender_id . "', '" . $offer_date1  . "', '" . $offer_desc  . "',  '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id			= mysqli_insert_id($conn);
						$offer_no	= "1-" . $id;

						$sql6		= " UPDATE offers SET offer_no = '" . $offer_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						$msg['msg_success'] = "Offer has been created successfully.";
						echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id . "&msg_success=" . $msg['msg_success']));
						$vender_id = $offer_desc =  $offer_date = "";
					} else {
						$error['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
				}
			}
		} else if ($cmd == 'edit') {
			if (access("edit_perm") == 0) {
				$error['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM offers a 
								WHERE a.vender_id	= '" . $vender_id . "'
								AND a.offer_date	= '" . $offer_date1 . "'
								AND a.offer_desc	= '" . $offer_desc . "' 
								AND a.id		   != '" . $id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE offers SET 	vender_id		= '" . $vender_id . "',
													offer_date		= '" . $offer_date1 . "', 
													offer_desc		= '" . $offer_desc . "', 
													
													update_date		= '" . $add_date . "',
													update_by		= '" . $_SESSION['username'] . "',
													update_ip		= '" . $add_ip . "'
								WHERE id = '" . $id . "'   ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg['msg_success'] = "Record Updated Successfully.";
					} else {
						$error['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error['msg'] = "This record is already exist.";
				}
			}
		}
	}
}
if (isset($is_Submit2) && $is_Submit2 == 'Y') {
	$field_name = "product_price";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "product_qty";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "product_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {


		if ($cmd2 == 'add') {
			if (access("add_perm") == 0) {
				$error2['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM offer_detail a 
								WHERE a.offer_id		= '" . $id . "'
								AND a.product_id		= '" . $product_id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".offer_detail(subscriber_users_id, offer_id, product_id, product_qty, product_price, product_offer_desc, add_date, add_by, add_ip)
							 VALUES('" . $subscriber_users_id . "', '" . $id . "', '" . $product_id . "', '" . $product_qty  . "', '" . $product_price  . "', '" . $product_offer_desc  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						if (isset($error2['msg'])) unset($error2['msg']);
						$msg2['msg_success'] = "Record has been added successfully.";
						$product_id = $product_price = $product_qty = $product_offer_desc = "";
					} else {
						$error2['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$error2['msg'] = "This record is already exist.";
				}
			}
		} else if ($cmd2 == 'edit') {
			if (access("edit_perm") == 0) {
				$error2['msg'] = "You do not have edit permissions.";
			} else {
				$sql_dup	= " SELECT a.* FROM offer_detail a 
								WHERE a.offer_id		= '" . $id . "'
								AND a.product_id		= '" . $product_id . "'
 								AND a.id			   != '" . $detail_id . "'";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE offer_detail SET 	product_id			= '" . $product_id . "', 
															product_qty			= '" . $product_qty . "', 
															product_price		= '" . $product_price . "', 
															product_offer_desc	= '" . $product_offer_desc . "', 
															
															update_date		= '" . $add_date . "',
															update_by		= '" . $_SESSION['username'] . "',
															update_ip		= '" . $add_ip . "'
								WHERE id = '" . $detail_id . "'   ";
					$ok = $db->query($conn, $sql_c_up);
					if ($ok) {
						$msg2['msg_success'] = "Record Updated Successfully.";
					} else {
						$error2['msg'] = "There is Error, record does not update, Please check it again OR contact Support Team.";
					}
				} else {
					$error2['msg'] = "This record is already exist.";
				}
			}
		}
	}
}
if (isset($is_Submit3) && $is_Submit3 == 'Y') {
	$field_name = "offer_detail_ids";
	if (!isset(${$field_name}) || (isset(${$field_name}) && sizeof(${$field_name}) == 0)) {
		$error3['msg'] = "Select atleast one record.";
	} else {
		foreach ($offer_detail_ids as $data1) {
			if (isset($product_qty_order[$data1]) && $product_qty_order[$data1] <= 0) {
				$error3["product_qty_order_" . $data1] = "Required >0";
			}
			if (isset($product_product_price[$data1]) && $product_product_price[$data1] <= 0) {
				$error3["product_product_price_" . $data1] = "Required >0";
			}
			if (!isset($is_tested[$data1]) || (isset($is_tested[$data1]) && $is_tested[$data1] == "")) {
				$error3["is_tested_" . $data1] = " <br>Required";
			}
		}
	}
	if (empty($error3)) {
		if (access("add_perm") == 0) {
			$error3['msg'] = "You do not have add permissions.";
		} else {
			// echo "<br><br><br><br><br><br><br>";
			foreach ($offer_detail_ids as $data) {
				// echo "<br><br> aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa: " . ($order_product_id);
				$sql_dup2			= " SELECT c.offer_id, c.product_id, d.vender_id
										FROM offer_detail c 
										INNER JOIN offers d ON d.id = c.offer_id
										WHERE c.id = '" . $data . "' ";
				$result_dup2		= $db->query($conn, $sql_dup2);
				$row_ee2			= $db->fetch($result_dup2);
				$offer_id			= $row_ee2[0]['offer_id'];
				$vender_id1			= $row_ee2[0]['vender_id'];
				$order_product_id	= $row_ee2[0]['product_id'];

				$sql_dup	= " SELECT DISTINCT a.id
								FROM purchase_orders a
								INNER JOIN offers b ON b.id = a.offer_id
								INNER JOIN offer_detail c ON c.offer_id = b.id
								WHERE 1=1
								AND c.id = '" . $data . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_orders(subscriber_users_id, offer_id, po_date, vender_id, add_date, add_by, add_ip)
						VALUES('" . $subscriber_users_id . "', '" . $offer_id . "', '" . date('Y-m-d') . "', '" . $vender_id1 . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$po_id = mysqli_insert_id($conn);

						$po_no	= "PO" . $po_id;
						$sql6	= " UPDATE purchase_orders SET po_no = '" . $po_no . "' WHERE id = '" . $po_id . "' ";
						$db->query($conn, $sql6);

						$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_order_detail(po_id, offer_detail_id, product_id, order_qty, order_price, is_tested, add_date, add_by, add_ip)
								VALUES('" . $po_id . "', '" . $data . "', '" . $order_product_id . "','" . $product_qty_order[$data] . "', '" . $product_product_price[$data] . "', '" . $is_tested[$data] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
						$ok = $db->query($conn, $sql6);

						if (isset($error3['msg'])) unset($error3['msg']);
						$msg3['msg_success'] = "Order has been created successfully.";
					} else {
						$error3['msg'] = "There is Error, Please check it again OR contact Support Team.";
					}
				} else {
					$row_main	= $db->fetch($result_dup);
					$po_id		= $row_main[0]['id'];

					$sql_dup	= " SELECT a.id
									FROM purchase_order_detail a
									WHERE 1=1
									AND a.po_id 			= '" . $po_id . "'
									AND a.offer_detail_id 	= '" . $data . "' ";
					$result_dup	= $db->query($conn, $sql_dup);
					$count_dup	= $db->counter($result_dup);
					if ($count_dup == 0) {
						$sql6 = "INSERT INTO " . $selected_db_name . ".purchase_order_detail(po_id, offer_detail_id, product_id, order_qty, order_price, is_tested, add_date, add_by, add_ip)
								VALUES('" . $po_id . "', '" . $data . "', '" . $order_product_id . "','" . $product_qty_order[$data] . "', '" . $product_product_price[$data] . "', '" . $is_tested[$data] . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "')";
						$ok = $db->query($conn, $sql6);

						if (isset($error3['msg'])) unset($error3['msg']);
						$msg3['msg_success'] = "Product has been added in offer order successfully.";
					} else {
						$error3['msg'] = "This product has already been included in the purchase order for the offer.";
					}
				}
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
	</div>
	<div class="row">
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
					<h4 class="card-title">Offer Master Info</h4><br>
					<form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id); ?>">
						<input type="hidden" name="is_Submit" value="Y" />
						<input type="hidden" id="cmd" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
						<input type="hidden" id="id" name="id" value="<?php if (isset($id)) echo $id; ?>" />
						<div class="row">
							<?php
							$field_name 	= "offer_date";
							$field_label 	= "Offer Date (d/m/Y)";
							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">date_range</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																													echo ${$field_name};
																												} ?>" class="datepicker validate <?php if (isset(${$field_name . "_valid"})) {
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
									<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																										echo ${$field_name . "_valid"};
																																									} ?>">
										<option value="">Select</option>
										<?php
										if ($count1 > 0) {
											$row1	= $db->fetch($result1);
											foreach ($row1 as $data2) { ?>
												<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['vender_name']; ?> - Phone: <?php echo $data2['phone_no']; ?></option>
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
								<a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#vender_add_modal">Add New Vender</a>
							</div>
						</div>
						<div class="row">
							<div class="input-field col m12 s12">
								<?php
								$field_name 	= "offer_desc";
								$field_label 	= "Description";
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
								<?php if (($cmd == 'add' && access("add_perm") == 1)  || ($cmd == 'edit' && access("edit_perm") == 1)) { ?>
									<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val; ?>
										<i class="material-icons right">send</i>
									</button>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
		if (isset($cmd) && $cmd == 'edit') { ?>
			<div class="col s12 m12 l12">
				<div id="Form-advance2" class="card card card-default scrollspy">
					<div class="card-content">
						<?php
						if (isset($error2['msg'])) { ?>
							<div class="card-alert card red lighten-5">
								<div class="card-content red-text">
									<p><?php echo $error2['msg']; ?></p>
								</div>
								<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
						<?php } else if (isset($msg2['msg_success'])) { ?>
							<div class="card-alert card green lighten-5">
								<div class="card-content green-text">
									<p><?php echo $msg2['msg_success']; ?></p>
								</div>
								<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
						<?php } ?>
						<h4 class="card-title"><?php echo $title_heading2; ?></h4><br>
						<form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=' . $cmd . '&cmd2=' . $cmd2 . '&id=' . $id . '&detail_id=' . $detail_id); ?>">
							<input type="hidden" name="is_Submit2" value="Y" />
							<input type="hidden" id="cmd" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
							<input type="hidden" id="cmd2" name="cmd2" value="<?php if (isset($cmd2)) echo $cmd2; ?>" />
							<input type="hidden" id="id" name="id" value="<?php if (isset($id)) echo $id; ?>" />
							<input type="hidden" id="detail_id" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
							<div class="row">
								<div class="input-field col m6 s12">
									<?php
									$field_name 	= "product_id";
									$field_label 	= "Product";
									$sql1 			= " SELECT a.*, b.category_name
														FROM products a
														INNER JOIN product_categories b ON b.id = a.product_category
														WHERE a.enabled = 1 
														ORDER BY a.product_desc ";
									$result1 		= $db->query($conn, $sql1);
									$count1 		= $db->counter($result1);
									?>
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class=" select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1	= $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['product_desc']; ?> (<?php echo $data2['category_name']; ?>) - <?php echo $data2['product_uniqueid']; ?></option>
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
									<a class="waves-effect waves-light btn modal-trigger mb-2 mr-1" href="#product_add_modal">Add New Product</a>
								</div>
								<div class="input-field col m2 s12">
									<?php
									$field_name 	= "product_qty";
									$field_label 	= "Quantity";
									?>
									<i class="material-icons prefix">description</i>
									<input id="<?= $field_name; ?>" type="number" required="" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
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
									$field_name 	= "product_price";
									$field_label 	= "Unit Price";
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
							</div>
							<div class="row">
								<div class="input-field col m12 s12">
									<?php
									$field_name 	= "product_offer_desc";
									$field_label 	= "Description";
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
									<?php if (($cmd2 == 'add' && access("add_perm") == 1)  || ($cmd2 == 'edit' && access("edit_perm") == 1)) { ?>
										<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val2; ?>
											<i class="material-icons right">send</i>
										</button>
									<?php } ?>
								</div>
								<div class="input-field col m2 s12">
									<?php if ($cmd2 == 'edit' && access("add_perm") == 1) { ?>
										<a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&id=" . $id) ?>">Add New Product</a>
									<?php } ?>
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
										if (isset($error3['msg'])) { ?>
											<div class="card-alert card red lighten-5">
												<div class="card-content red-text">
													<p><?php echo $error3['msg']; ?></p>
												</div>
												<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										<?php } else if (isset($msg3['msg_success'])) { ?>
											<div class="card-alert card green lighten-5">
												<div class="card-content green-text">
													<p><?php echo $msg3['msg_success']; ?></p>
												</div>
												<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
													<span aria-hidden="true">×</span>
												</button>
											</div>
										<?php } ?>
										<?php
										$sql_cl		= "	SELECT a.*, c.product_uniqueid, c.product_desc, d.category_name
														FROM offer_detail a 
														INNER JOIN offers b ON b.id = a.offer_id
														INNER JOIN products c ON c.id = a.product_id
														INNER JOIN product_categories d ON d.id = c.product_category
														WHERE 1=1 
														AND a.offer_id = '" . $id . "' 
														ORDER BY a.enabled DESC, a.id DESC "; // echo $sql_cl;
										$result_cl	= $db->query($conn, $sql_cl);
										$count_cl	= $db->counter($result_cl);
										?>
										<form method="post" autocomplete="off">
											<input type="hidden" name="is_Submit3" value="Y" />
											<div class="row">
												<div class="col s12">
													<table id="page-length-option" class="display">
														<thead>
															<tr>
																<?php
																$headings = '	<th class="sno_width_60">S.No</th>
																				<th class="sno_width_60">
																					<label>
																						<input type="checkbox" id="all_checked" class="filled-in" name="all_checked" value="1"   ';
																if (isset($all_checked) && $all_checked == '1') {
																	$headings .= ' checked ';
																}
																$headings .= ' 			/>
																						<span>Select All</span>
																					</label>
																				</th>
																				<th>Product ID</th>
																				<th>Product Description / Category</th>
 																				<th>Offer Qty</th>
																				<th>Order Qty</th>
																				<th>OfferPrice</th>
																				<th>Order Price</th>
																				<th>To Be Tested</th>
																				<th>Action</th>';
																$headings2 = '	<th class="sno_width_60">S.No</th>
																				<th></th>
																				<th>Product ID</th>
																				<th>Product Description / Category</th>
																				<th>Offer Qty</th>
																				<th>Order Qty</th>
																				<th>OfferPrice</th>
																				<th>Order Price</th>
																				<th>To Be Tested</th>
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
																	$detail_id 	= $data['id'];
																	$product_qty 	= $data['product_qty'];
																	$product_price 	= $data['product_price']; ?>
																	<tr>
																		<td style="text-align: center;">
																			<?php echo $i + 1; ?>
																		</td>
																		<td style="text-align: center;">
																			<label style="margin-left: 25px;">
																				<input type="checkbox" name="offer_detail_ids[]" id="offer_detail_ids[]" value="<?= $detail_id; ?>" <?php
																																													if (isset($offer_detail_ids) && in_array($detail_id, $offer_detail_ids)) {
																																														echo "checked";
																																													} ?> class="checkbox filled-in" />
																				<span></span>
																			</label>
																		</td>
																		<td><?php echo $data['product_uniqueid']; ?></td>
																		<td>
																			<?php echo ucwords(strtolower($data['product_desc'])); ?> (<?php echo $data['category_name']; ?>)<br>
																			<b>Descriptions: </b><?php echo $data['product_offer_desc']; ?>
																		</td>
																		<td><?php echo $product_qty; ?></td>
																		<td>
																			<input type="number" name="product_qty_order[<?= $detail_id; ?>]" value="<?php if (!isset($product_qty_order[$detail_id])) {
																																							echo $product_qty;
																																						} else {
																																							echo $product_qty_order[$detail_id];
																																						} ?>" style="width: 80px;">
																			<span class="color-red">
																				<b>
																					<?php
																					if (isset($error3["product_qty_order_" . $detail_id])) {
																						echo $error3["product_qty_order_" . $detail_id];
																					} ?>
																				</b>
																			</span>
																		</td>
																		<td><?php echo $product_price; ?></td>
																		<td>
																			<input type="number" name="product_product_price[<?= $detail_id; ?>]" value="<?php if (!isset($product_product_price[$detail_id])) {
																																								echo $product_price;
																																							} else {
																																								echo $product_product_price[$detail_id];
																																							} ?>" style="width: 120px;">
																			<span class="color-red">
																				<b>
																					<?php
																					if (isset($error3["product_product_price_" . $detail_id])) {
																						echo $error3["product_product_price_" . $detail_id];
																					} ?>
																				</b>
																			</span>
																		</td>
																		<td>
																			<?php
																			$field_name 	= "is_tested";
																			$field_label 	= "To Be Tested";
																			?>
																			<p class="mb-1 custom_radio">
																				<label>
																					<input name="<?= $field_name; ?>[<?= $detail_id; ?>]" id="<?= $field_name; ?>[<?= $detail_id; ?>]" type="radio" value="Yes" <?php
																																																				if (isset($is_tested[$detail_id]) && $is_tested[$detail_id] == 'Yes') {
																																																					echo 'checked=""';
																																																				} ?>>
																					<span>Yes</span>
																				</label> &nbsp;&nbsp;
																				<label>
																					<input name="<?= $field_name; ?>[<?= $detail_id; ?>]" id="<?= $field_name; ?>[<?= $detail_id; ?>]" type="radio" value="No" <?php
																																																				if (isset($is_tested[$detail_id]) && $is_tested[$detail_id] == 'No') {
																																																					echo 'checked=""';
																																																				} ?>>
																					<span>No</span>
																				</label>
																			</p>
																			<span class="color-red">
																				<b>
																					<?php
																					if (isset($error3["is_tested_" . $detail_id])) {
																						echo $error3["is_tested_" . $detail_id];
																					} ?>
																				</b>
																			</span>
																		</td>
																		<td class="text-align-center">
																			<?php
																			if ($data['enabled'] == 1 && access("view_perm") == 1) { ?>
																				<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=edit&id=" . $id . "&detail_id=" . $detail_id) ?>">
																					<i class="material-icons dp48">edit</i>
																				</a> &nbsp;&nbsp;
																			<?php }
																			if ($data['enabled'] == 0 && access("edit_perm") == 1) { ?>
																				<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&cmd3=enabled&id=" . $id . "&detail_id=" . $detail_id) ?>">
																					<i class="material-icons dp48">add</i>
																				</a> &nbsp;&nbsp;
																			<?php } else if ($data['enabled'] == 1 && access("delete_perm") == 1) { ?>
																				<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=add&cmd3=disabled&id=" . $id . "&detail_id=" . $detail_id) ?>" onclick="return confirm('Are you sure, You want to delete this record?')">
																					<i class="material-icons dp48">delete</i>
																				</a> &nbsp;&nbsp;
																			<?php } ?>
																		</td>
																	</tr>
															<?php $i++;
																}
															} ?>
														<tfoot>
															<tr><?php echo $headings2; ?></tr>
														</tfoot>
													</table>
												</div>
											</div>
											<div class="row">
												<div class="col s2">
													<button class="btn cyan waves-effect waves-light right" type="submit" name="action">
														Create Order<i class="material-icons right">send</i>
													</button>
												</div>
											</div>
										</form>
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
		<?php } ?>
	</div>
	<?php include("sub_files/add_product_modal.php") ?>
	<?php include("sub_files/add_vender_modal.php") ?>
</div>
<br><br><br><br>
<!-- END: Page Main-->
<!-- END: Page Main-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<?php include("sub_files/add_product_js_code.php") ?>
<?php include("sub_files/add_vender_js_code.php") ?>