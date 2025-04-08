<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$sale_order_id				= "1";
	$inv_date 					= date('d/m/Y');
}
if (isset($test_on_local) && $test_on_local == 1 && isset($cmd2) &&  $cmd2 == 'add') {
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

if (isset($cmd2) && $cmd2 == 'delete' && isset($detail_id) && $detail_id > 0) {
	$sql_ee1 = "DELETE FROM sale_order_invoice_details WHERE id = '" . $detail_id . "'";
	$ok = $db->query($conn, $sql_ee1);
	if ($ok) {
		$msg3['msg_success'] = "Other payments / deductions has been removed successfully.";
	}
}
if (isset($cmd3) && $cmd3 == 'disabled') {
	$sql_c_upd = "UPDATE purchase_order_detail set 	enabled = 0,
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
	$sql_c_upd = "UPDATE purchase_order_detail set 	enabled 	= 1,
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
	$title_heading 	= "Update Sale Invoice";
	$button_val 	= "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Create Sale Invoice";
	$button_val 	= "Create";
	$id 			= "";
}

if (isset($cmd2) &&  $cmd2 == 'edit') {
	$title_heading2  = "Update Order Product";
	$button_val2 	= "Save";
} else {
	$title_heading2	= "Add Order Product";
	$button_val2 	= "Add";
	$detail_id		= "";
}

if (isset($cmd) && $cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee			= "SELECT a.* FROM sale_order_invoices a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee		= $db->query($conn, $sql_ee);
	$row_ee			= $db->fetch($result_ee);
	$invoice_no		= $row_ee[0]['invoice_no'];
	$sales_order_id	=  $row_ee[0]['sales_order_id'];
	$is_posted		=  $row_ee[0]['is_posted'];
	$inv_date		= str_replace("-", "/", convert_date_display($row_ee[0]['invoice_date']));

	$sql_ee					= "SELECT a.* FROM sale_order_invoice_details a WHERE a.invoice_id = '" . $id . "' ";
	$result_ee				= $db->query($conn, $sql_ee);
	$count_ee2				= $db->counter($result_ee);
	if ($count_ee2 > 0) {
		$row_ee					= $db->fetch($result_ee);
		$payment_deduction_id 	= $row_ee[0]['deduction_id'];
		$amount 				= $row_ee[0]['total_amount'];
		foreach ($row_ee as $data) {
			$invoiceItems[]	= $data['shipped_id'] . "^" . $data['total_amount'];
		}
	}
}

extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}
if (isset($is_Submit) && $is_Submit == 'Y') {

	$field_name = "sales_order_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "inv_date";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error)) {
		$inv_date1 = "0000-00-00";
		if (isset($inv_date) && $inv_date != "") {
			$inv_date1 = convert_date_mysql_slash($inv_date);
		}

		if ($cmd == 'add') {
			if (access("add_perm") == 0) {
				$error['msg'] = "You do not have add permissions.";
			} else {
				$sql_dup	= " SELECT a.* 
								FROM sale_order_invoices a 
								WHERE a.sales_order_id	= '" . $sales_order_id . "'
								AND a.invoice_date		= '" . $inv_date1 . "'";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql6 = "INSERT INTO " . $selected_db_name . ".sale_order_invoices( sales_order_id, invoice_date,  add_date, add_by, add_by_user_id, add_ip, add_timezone)
							 VALUES('" . $sales_order_id . "',  '" . $inv_date1  . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $_SESSION['user_id'] . "', '" . $add_ip . "', '" . $timezone . "')";
					$ok = $db->query($conn, $sql6);
					if ($ok) {
						$id			= mysqli_insert_id($conn);
						$invoice_no		= "INV-0000" . $id;
						$sql6		= " UPDATE sale_order_invoices SET invoice_no = '" . $invoice_no . "' WHERE id = '" . $id . "' ";
						$db->query($conn, $sql6);

						$msg['msg_success'] = "Sale Invoice has been created successfully.";
						echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id . "&msg_success=" . $msg['msg_success']));
						$sales_order_id = $inv_date = "";
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
				$sql_dup	= " SELECT a.* FROM purchase_orders a 
								WHERE a.vender_id	= '" . $vender_id . "'
								AND a.po_date	= '" . $po_date1 . "'
								AND a.po_desc	= '" . $po_desc . "' 
								AND a.id		   != '" . $id . "' ";
				$result_dup	= $db->query($conn, $sql_dup);
				$count_dup	= $db->counter($result_dup);
				if ($count_dup == 0) {
					$sql_c_up = "UPDATE purchase_orders SET	vender_id				= '" . $vender_id . "',
															po_date					= '" . $po_date1 . "',
															estimated_receive_date 	= '" . $estimated_receive_date1 . "', 
															po_desc					= '" . $po_desc . "', 
															vender_invoice_no		= '" . $vender_invoice_no . "', 
															is_tested_po			= '" . $is_tested_po . "', 
															is_wiped_po				= '" . $is_wiped_po . "', 
															is_imaged_po			= '" . $is_imaged_po . "', 

															update_date				= '" . $add_date . "',
															update_by				= '" . $_SESSION['username'] . "',
															update_by_user_id		= '" . $_SESSION['user_id'] . "',
															update_ip				= '" . $add_ip . "',
															update_timezone			= '" . $timezone . "'
								WHERE id = '" . $id . "' ";
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
	$processedItems = [];
	if (isset($invoiceItems) && is_array($invoiceItems)) {
		foreach ($invoiceItems as $item) {
			$processedItems[] = explode('^', $item);
		}
	}
	if (empty($processedItems)) {
		$error2['msg'] = "Select at least one record to add in shipment";
	}
	if (empty($error2)) {
		if (access("add_perm") == 0) {
			$error2['msg'] = "You do not have add permissions.";
		} else {
			$sql_ee1 = "DELETE FROM sale_order_invoice_details WHERE invoice_id = '" . $id . "' AND shipped_id != 0";
			$db->query($conn, $sql_ee1);
			$k = 0;
			if (is_array($processedItems) && sizeof($processedItems) != 0) {
				foreach ($processedItems as $shipment) {
					$shipment_id = $shipment[0];
					$total_amount = $shipment[1];
					$sql6 = "INSERT INTO sale_order_invoice_details(shipped_id, invoice_id, total_amount, add_date, add_by, add_ip, add_timezone, added_from_module_id)
                             VALUES('" . $shipment_id . "', '" . $id . "', '" . $total_amount . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "', '" . $module_id . "')";
					$db->query($conn, $sql6);
					$k++;
				}
			}
			$msg2['msg_success'] = "Sale order invoice detail has been added successfully.";
		}
	}
}

if (isset($is_Submit3) && $is_Submit3 == 'Y') {

	$field_name = "payment_deduction_id";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error3[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	$field_name = "other_deduction_amount";
	if (isset(${$field_name}) && ${$field_name} == "") {
		$error3[$field_name] 		= "Required";
		${$field_name . "_valid"} 	= "invalid";
	}
	if (empty($error3)) {
		if (access("add_perm") == 0) {
			$error3['msg'] = "You do not have add permissions.";
		} else {
			$sql_dup	= " SELECT a.* 
							FROM sale_order_invoice_details a 
							WHERE a.deduction_id	= '" . $payment_deduction_id . "'
							AND a.invoice_id		= '" . $id . "'";
			$result_dup	= $db->query($conn, $sql_dup);
			$count_dup	= $db->counter($result_dup);
			if ($count_dup == 0) {
				$sql6 = "INSERT INTO sale_order_invoice_details(deduction_id, total_amount,invoice_id, add_date, add_by, add_ip, add_timezone,added_from_module_id)
						 VALUES('" . $payment_deduction_id . "', '" . $other_deduction_amount . "' ,'" . $id . "', '" . $add_date . "', '" . $_SESSION['username'] . "', '" . $add_ip . "', '" . $timezone . "','" . $module_id . "')";
				$ok = $db->query($conn, $sql6);
				if ($ok) {
					$msg3['msg_success'] = "Other payments or deductions has been added successfully.";
					$payment_deduction_id = $other_deduction_amount = "";
				}
			} else {
				$error3['msg'] = "Other payments or deductions already exists";
			}
		}
	}
}
?>
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
					<h4 class="card-title">Order Invoice Info</h4><br>
					<form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=' . $cmd . '&cmd2=add&id=' . $id); ?>">
						<input type="hidden" name="is_Submit" value="Y" />
						<div class="row">
							<?php
							if (isset($cmd) && $cmd == 'edit') { ?>
								<?php
								$field_name 	= "invoice_no";
								$field_label 	= "Invoice No";
								?>
								<div class="input-field col m2 s12">
									<i class="material-icons prefix">question_answer</i>
									<input id="<?= $invoice_no; ?>" readonly type="text" value="<?php if (isset(${$field_name})) {
																									echo ${$field_name};
																								} ?>">
									<label for="<?= $invoice_no; ?>">
										<?= $field_label; ?>
										<span class="color-red"> * </span>
									</label>
								</div>
							<?php
							}
							$field_name 	= "inv_date";
							$field_label 	= "Invoice Date (d/m/Y)";
							?>
							<div class="input-field col m2 s12">
								<i class="material-icons prefix">date_range</i>
								<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" <?php if (isset($cmd) && $cmd == 'edit') echo "disabled readonly " ?> value="<?php if (isset(${$field_name})) {
																																														echo ${$field_name};
																																													} ?>" class=" datepicker validate <?php if (isset(${$field_name . "_valid"})) {
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
							<div class="row">
								<div class="input-field col m3 s12">
									<?php
									$field_name 	= "sales_order_id";
									$field_label 	= "Sale Order No";
									$sql1 			= "SELECT * FROM sales_orders WHERE enabled = 1 ORDER BY so_no ";
									$result1 		= $db->query($conn, $sql1);
									$count1 		= $db->counter($result1);
									?>
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" <?php if (isset($cmd) && $cmd == 'edit') echo "disabled readonly " ?> class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																																												echo ${$field_name . "_valid"};
																																																											} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1	= $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['so_no']; ?></option>
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
							</div>
							<div class="row">
								<div class="input-field col m6 s12">
									<?php if (($cmd == 'add' && access("add_perm") == 1)) { ?>
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
			<form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id); ?>">
				<input type="hidden" name="is_Submit2" value="Y" />
				<div class="col s12 m12 l12">
					<div id="Form-advance2" class="card card card-default scrollspy">
						<div class="card-content">
							<?php
							$sql_cl		= "	SELECT a.*, c.product_desc, d.category_name,b.order_status, 
												c.product_uniqueid, b1.order_qty,b1.order_price,b1.product_so_desc,
												b1.product_stock_id,bb.shipment_no,bb.shipment_sent_date,bb.expected_delivery_date,bb.shipment_tracking_no, bb.id AS shipped_id,
												cc.courier_name, bb2.id as shipment_detail_id,c1.serial_no
											FROM sales_order_shipment_detail bb2
											INNER JOIN sales_order_shipments bb ON bb2.`shipment_id` = bb.id
											INNER JOIN sales_order_detail_packing a ON a.id = bb2.`packed_id`
											INNER JOIN sales_orders b ON b.id = a.sale_order_id
											INNER JOIN product_stock c1 ON c1.id = a.product_stock_id
											INNER JOIN `sales_order_detail` b1 ON b.id = b1.sales_order_id AND b1.product_stock_id = a.product_stock_id
											INNER JOIN products c ON c.id = c1.product_id
											LEFT JOIN product_categories d ON d.id = c.product_category
											LEFT JOIN couriers cc ON cc.id = bb.shipment_courier_id
											WHERE 1=1
											AND a.is_shipped = 1  ";  //echo $sql_cl;
							$result_cl	= $db->query($conn, $sql_cl);
							$count_cl	= $db->counter($result_cl);
							?>
							<h5>Order Invoice Details</h5>
							<div class="section section-data-tables">
								<div class="row">
									<div class="col s12">
										<table id="page-length-option" class=" display pagelength50 dataTable dtr-inline ">
											<thead>
												<tr>
													<th style="text-align: center;">
														<label>
															<input type="checkbox" id="all_checked" class="filled-in order-checkbox" name="all_checked" <?php if (isset($is_posted) && $is_posted == 1) echo "disabled"; ?> value="1" <?php if (isset($all_checked) && $all_checked == '1') {
																																																											echo "checked";
																																																										} ?> />
															<span></span>
														</label>
													</th>
													<?php
													$headings  = '<th>Product ID</th>
																	<th>Product Detail</th>
																	<th>Serial No</th>
																	<th>Price</th>';
													echo $headings;
													?>
												</tr>
											</thead>
											<tbody>
												<?php
												$i = $total_sum = 0;
												if ($count_cl > 0) {
													$row_cl = $db->fetch($result_cl);
													foreach ($row_cl as $data) {
														$detail_id1           = $data['id'];
														$detail_id2           = $data['shipment_detail_id'];
														$order_qty            = $data['order_qty'] ?? 1;
														$order_price          = $data['order_price'];
														$price				  = ($order_qty * $order_price);
												?>
														<tr>
															<td style="text-align: center;">
																<?php
																if (access("delete_perm") == 1) { ?>
																	<label style="margin-left: 25px;">
																		<input type="checkbox" name="invoiceItems[]" id="invoiceItems[]" data-price="<?= $order_price; ?>" <?php if (isset($is_posted) && $is_posted == 1) echo "disabled"; ?> value="<?= $detail_id2; ?>^<?= $order_price; ?>" <?php if (isset($invoiceItems) && in_array($detail_id2 . "^" . $order_price, $invoiceItems)) {
																																																																									echo "checked";
																																																																									$total_sum += $order_price;
																																																																								} ?> class="checkbox filled-in order-checkbox" />
																		<span></span>
																	</label>
																<?php } ?>
															</td>
															<td><?php echo "" . $data['product_uniqueid']; ?></td>
															<td>
																<?php echo ucwords(strtolower($data['product_desc'])); ?>
																<?php
																if ($data['category_name'] != "") {
																	echo  " (" . $data['category_name'] . ")";
																} ?>
															</td>
															<td><?php echo $data['serial_no']; ?></td>
															<td><?php echo $order_price; ?></td>
															<input type="hidden" id="total_amount" name="total_amount[]" value="<?php echo $order_price; ?>">

														</tr>
												<?php $i++;
													}
												} ?>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col m8 s12"></div>
									<div class="col m3 s12">
										<?php
										$i = 0;
										$sql_cl1 	= " SELECT  a.invoice_id, a.total_amount,b.invoice_no,c.other_payment_deduction,a.id AS detail_id, a.deduction_id
														FROM sale_order_invoice_details a
														INNER JOIN sale_order_invoices AS b ON b.id = a.invoice_id
														INNER JOIN other_payments_deductions c ON c.id = a.deduction_id";
										$result_cl1	= $db->query($conn, $sql_cl1);
										$count_cl1	= $db->counter($result_cl1);
										$col = 0;
										if (isset($is_posted) && $is_posted == 0) {
											$col = 2;
										}
										?>
										<table id="page-length-option1" class="bordered">
											<tr>
												<th>Total Price:</th>
												<th colspan="<?= $col ?>"><?= number_format($total_sum, 2); ?></th>
											</tr>
											<?php
											$total_amount2 = 0;
											$total = $total_sum;
											if ($count_cl1 > 0) {
												$row_cl1 = $db->fetch($result_cl1);

												foreach ($row_cl1 as $data1) {
													$id    						= $data1['invoice_id'];
													$detail_id    				= $data1['detail_id'];
													$other_payment_deduction    = $data1['other_payment_deduction'];
													$invoice_no            		= $data1['invoice_no'];
													$total_amount         		= $data1['total_amount'];
													if ($data1['deduction_id'] == 3) {
														$total = ($total_sum - $total_amount);
													} else {
														$total = ($total_sum + $total_amount);
													} ?>
													<tr>
														<th><?= $other_payment_deduction; ?></th>
														<th class="deduction"><?= number_format($total_amount, 2); ?></th>
														<?php
														if (isset($is_posted) && $is_posted == 0) {
														?>
															<th>
																<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2=delete&id=" . $id . "&detail_id=" . $detail_id) ?>">
																	<i class="material-icons dp48">delete</i>
																</a>
															</th>
														<?php
														} ?>
													</tr>
											<?php
												}
											} ?>
											<tr>
												<th>Total:</th>
												<th id="total" colspan="<?= $col ?>"><?= number_format($total, 2); ?></th>
											</tr>
										</table>
									</div>
									<div class="col m1 s12"></div>
								</div>
							</div>
							<div class="row">
								<div class="input-field col m6 s12">
									<?php if (access("add_perm") == 1  && isset($is_posted) && $is_posted == 0 || (isset($cmd2) &&  $cmd2 == 'edit' && access("edit_perm") == 1) && isset($is_posted) && $is_posted == 0) { ?>
										<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $button_val2; ?>
											<i class="material-icons right">send</i>
										</button>
									<?php } ?>
								</div>
							</div>

						</div>
						<?php //include('sub_files/right_sidebar.php'); 
						?>
					</div>
				</div>
			</form>
			<div class="col s12 m12 l12">
				<div id="Form-advance" class="card card card-default scrollspy">
					<div class="card-content">
						<h4 class="card-title">Other Payments / Deductions</h4><br>
						<form method="post" autocomplete="off" action="<?php echo "?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id); ?>">
							<input type="hidden" name="is_Submit3" value="Y" />
							<input type="hidden" id="cmd" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
							<div class="row">

								<div class="input-field col m3 s12">
									<?php
									$field_name 	= "payment_deduction_id";
									$field_label 	= "Other Payments / Deductions";
									$sql1 			= "SELECT * FROM other_payments_deductions WHERE enabled = 1 ORDER BY other_payment_deduction ";
									$result1 		= $db->query($conn, $sql1);
									$count1 		= $db->counter($result1);
									?>
									<i class="material-icons prefix">question_answer</i>
									<div class="select2div">
										<select id="<?= $field_name; ?>" name="<?= $field_name; ?>" class="select2 browser-default select2-hidden-accessible validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
											<option value="">Select</option>
											<?php
											if ($count1 > 0) {
												$row1	= $db->fetch($result1);
												foreach ($row1 as $data2) { ?>
													<option value="<?php echo $data2['id']; ?>" <?php if (isset(${$field_name}) && ${$field_name} == $data2['id']) { ?> selected="selected" <?php } ?>><?php echo $data2['other_payment_deduction']; ?></option>
											<?php }
											} ?>
										</select>
										<label for="<?= $field_name; ?>">
											<?= $field_label; ?>
											<span class="color-red">* <?php
																		if (isset($error3[$field_name])) {
																			echo $error3[$field_name];
																		} ?>
											</span>
										</label>
									</div>
								</div>
								<?php
								$field_name 	= "other_deduction_amount";
								$field_label 	= "Other Payment / Deduction  Amoumt";
								?>
								<div class="input-field col m3 s12">
									<i class="material-icons prefix">date_range</i>
									<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																														echo ${$field_name};
																													} ?>" class=" validate <?php if (isset(${$field_name . "_valid"})) {
																																				echo ${$field_name . "_valid"};
																																			} ?>">
									<label for="<?= $field_name; ?>">
										<?= $field_label; ?>
										<span class="color-red">* <?php
																	if (isset($error3[$field_name])) {
																		echo $error3[$field_name];
																	} ?>
										</span>
									</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col m6 s12">
									<?php if (($cmd == 'edit' && access("add_perm") == 1) && isset($is_posted) && $is_posted == 0) { ?>
										<button class="btn cyan waves-effect waves-light right" type="submit" name="action">Add
											<i class="material-icons right">send</i>
										</button>
									<?php } ?>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>

		<?php } ?>
	</div>

</div>
<br><br><br><br>
<!-- END: Page Main-->
<!-- END: Page Main-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> -->