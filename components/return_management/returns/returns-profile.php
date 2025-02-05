<?php
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
if (isset($test_on_local) && $test_on_local == 1) {
	if (isset($cmd) && $cmd != 'edit') {
	}
}
if (!isset($cmd2_2)) {
	$cmd2_2 = "add";
}
if (!isset($cmd3)) {
	$cmd3 = "";
}
if (!isset($cmd4)) {
	$cmd4 = "add";
}
if (!isset($cmd5)) {
	$cmd5 = "";
}
if (!isset($cmd6)) {
	$cmd6 = "";
}
if (!isset($cmd7)) {
	$cmd7 = "";
}
if (!isset($cmd8)) {
	$cmd8 = "";
}
if (!isset($cmd9)) {
	$cmd9 = "";
}

$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];

// if (!isset($is_Submit) && $cmd == 'edit' && isset($msg['msg_success']) && isset($id)) {
// 	echo redirect_to_page("?string=" . encrypt('module=' . $module . '&module_id=' . $module_id . '&page=add&cmd=edit&cmd2=add&id=' . $id));
// }
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
	$title_heading 	= "Update Purchase Order";
	$button_val 	= "Save";
}
if ($cmd == 'add') {
	$title_heading 	= "Create Purchase Order";
	$button_val 	= "Create";
	$id 			= "";
}

$title_heading2	= "Add Order Product";
$button_val2 	= "Add";
if (isset($cmd2) && $cmd2 == 'edit') {
	$title_heading2  = "Update Order Product";
	$button_val2 	= "Save";
}

if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}
if (!isset($_SESSION['uniq_session_id'])) {
	$_SESSION['uniq_session_id'] = uniqid() . session_id();
}
$uniq_session_id     = $_SESSION['uniq_session_id'];

if (!isset($is_Submit_tab8)) {
	$sql = "DELETE FROM temp_po_pricing WHERE uniq_session_id = '" . $uniq_session_id . "' AND po_id = '" . $id . "'";
	$db->query($conn, $sql);
}

$btn2 = $btn2_2 = $btn3 = $btn4 = $btn5 = $btn6 = "Add";
if (isset($cmd2) && $cmd2 == 'edit') {
	$btn2 = "Update";
}
if (isset($cmd2_2) && $cmd2_2 == 'edit') {
	$btn2_2 = "Update";
}
if (isset($cmd3) && $cmd3 == 'edit') {
	$btn3 = "Update";
}
if (isset($cmd4) &&  $cmd4 == 'edit') {
	$btn4 = "Update";
}
if (isset($cmd5) &&  $cmd5 == 'edit') {
	$btn5 = "Update";
}
if (isset($cmd6) &&  $cmd6 == 'edit') {
	$btn6 = "Update";
}
if (isset($btn7) &&  $btn7 == 'edit') {
	$btn7 = "Update";
}
if (isset($btn8) &&  $btn8 == 'edit') {
	$btn8 = "Update";
}
if (isset($btn9) &&  $btn9 == 'edit') {
	$btn9 = "Update";
}

extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}

include('tab1_code.php');
include('tab2_code.php');
include('tab3_code.php');
 include('tab5_code.php');
 include('tab6_code.php');
// include('tab7_code.php');
// include('tab8_code.php');

$button_val = "Create";
if (isset($cmd) && $cmd == 'edit') {
	$title_heading = "Purchase Order Profile";
	$button_val = "Update";
}
if (!isset($cmd)) {
	$title_heading 	= "Create New Purchase Order";
	$button_val = "Create";
}
if (isset($cmd) && $cmd == 'add') {
	$title_heading 	= "Create New Purchase Order";
	$button_val = "Create";
}
if (isset($cmd) && $cmd == 'add' && !(isset($cmd3))) {
	$title_heading 	= "Create New Purchase Order";
	$button_val 	= "Create";
}
if ((isset($cmd2) && $cmd2 == 'edit') || (isset($cmd2_2) && $cmd2_2 == 'edit') || (isset($cmd3) && $cmd3 == 'edit') || (isset($cmd4) && $cmd4 == 'edit') || (isset($cmd5) && $cmd5 == 'edit') || (isset($cmd6) && $cmd6 == 'edit')) {
	$button_val = "Save";
}
$general_heading = "Return"; ?>
<style>
	table.addproducttable td {
		padding-top: 2px !important;
		padding-bottom: 2px !important;
		padding-left: 10px !important;
		padding-right: 10px !important;
		border: 1px solid rgba(0, 0, 0, .12) !important;
		font-size: 12px !important;
	}

	table.addproducttable th {
		padding-top: 5px !important;
		padding-bottom: 5px !important;
		padding-left: 10px !important;
		padding-right: 10px !important;
		border: 1px solid rgba(0, 0, 0, .12) !important;
	}

	table.addproducttable td input {
		font-size: 12px !important;
	}

	table.addproducttable tr {
		line-height: 1.5 !important;
		/* or you can use other values like 1.2, 2, etc. */
	}

	.custom_padding_section {
		padding-top: 5px !important;
		padding-bottom: 5px !important;
	}

	.custom_margin_section {
		margin-top: 2px !important;
		margin-bottom: 2px !important;
	}

	.custom_input {
		border: 1px solid rgba(0, 0, 0, .12) !important;
		border-radius: 5px !important;
		margin-top: 6px !important;
		margin-right: 6px !important;
		padding: 6px !important;
		height: 15px !important;
		width: 85% !important;
		line-height: 1;
	}

	select.custom_condition_class {
		display: block;
		width: 100%;
		/* Full width to fit the table cell */
		height: 28px;
		/* Set the height to match the design */
		padding: 0 30px 0 5px;
		/* Increase padding to create space for the arrow */
		border: 1px solid #bdbdbd;
		/* Light border color */
		border-radius: 4px;
		/* Slightly rounded corners */
		background-color: #fff;
		/* White background */
		background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBvbHlnb24gcG9pbnRzPSIxMiAxNiA3IDExIDE3IDExIDEyIDE2IiBzdHlsZT0iZmlsbDpyZ2IoMTMyLCAxMjcsIDEyNyk7Ii8+PC9zdmc+');
		/* Base64-encoded SVG for larger down arrow with specified color */
		background-repeat: no-repeat;
		background-position: right 10px center;
		background-size: 18px;
		/* Increase size of the arrow */
		appearance: none;
		/* Remove default browser arrow */
		outline: #bdbdbd;
		font-size: 12px !important
			/* Adjust font size */
			line-height: 1;
		/* Set line height */
		color: #333;
		/* Text color */
	}

	/* Hover and focus states for better interaction feedback */
	select.custom_condition_class:hover {
		border-color: #bdbdbd;
		/* Darker border on hover */
	}

	select.custom_condition_class:focus {
		border-color: #2196f3;
		/* Blue border on focus */
		box-shadow: 0 0 5px rgba(33, 150, 243, 0.5);
		/* Blue glow */
	}

	.padding_custom_msg {
		padding-top: 0px !important;
		padding-bottom: 0px !important;
	}

	.padding_custom_msg2 {
		padding-top: 5px !important;
		padding-bottom: 5px !important;
	}

	.add-more,
	.remove-row,
	.remove-row-part {
		font-size: 20px !important;
		/* display: inline-flex !important; */
		align-items: center !important;
		justify-content: center !important;
		width: 25px !important;
		height: 25px !important;
		padding: 0px !important;
		border-radius: 15% !important;
		text-decoration: none !important;
	}

	.add-more i,
	.remove-row i,
	.remove-row-part i {
		font-size: inherit !important;
		/* Inherit font size from parent anchor tag */
		line-height: 0 !important;
		Ensure icons are vertically centered */
	}

	.custom_btn_size {
		height: 30px !important;
		line-height: 28px !important;
		padding: 0 1rem !important;
	}

	.custom_margin_bottom_col {
		margin-bottom: 0px !important;
	}

	.custom_input_heigh {
		height: 35px !important;
	}
</style>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="col m12 s12">
			<div class="container">
				<!-- Account settings -->
				<section class="tabs-vertical section">
					<div class="row">
						<div class="col m12 s12">
							<!-- tabs  -->
							<div class="card-panel" style="padding-top: 5px; padding-bottom: 5px;">
								<?php include("tabs.php") ?>
							</div>
						</div>
						<div class="col m12 s12">
							<div>
								<?php
								if (isset($error['msg'])) { ?>

									<div class="col 24 s12">
										<div class="card-alert card red lighten-5 padding_custom_msg">
											<div class="card-content red-text padding_custom_msg2">
												<p><?php echo $error['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } else if (isset($msg['msg_success'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card green lighten-5 padding_custom_msg">
											<div class="card-content green-text padding_custom_msg2">
												<p><?php echo $msg['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } ?>
								<?php
								if (isset($error2['msg'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card red lighten-5 padding_custom_msg">
											<div class="card-content red-text padding_custom_msg2">
												<p><?php echo $error2['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } else if (isset($msg2['msg_success'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card green lighten-5 padding_custom_msg">
											<div class="card-content green-text padding_custom_msg2">
												<p><?php echo $msg2['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php }
								if (isset($error2_2['msg'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card red lighten-5 padding_custom_msg">
											<div class="card-content red-text padding_custom_msg2">
												<p><?php echo $error2_2['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } else if (isset($msg2_2['msg_success'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card green lighten-5 padding_custom_msg">
											<div class="card-content green-text padding_custom_msg2">
												<p><?php echo $msg2_2['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php }
								if (isset($error3['msg'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card red lighten-5 padding_custom_msg">
											<div class="card-content red-text padding_custom_msg2">
												<p><?php echo $error3['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } else if (isset($msg3['msg_success'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card green lighten-5 padding_custom_msg">
											<div class="card-content green-text padding_custom_msg2">
												<p><?php echo $msg3['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php }
								if (isset($error3_2['msg'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card red lighten-5 padding_custom_msg">
											<div class="card-content red-text padding_custom_msg2">
												<p><?php echo $error3_2['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } else if (isset($msg3_2['msg_success'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card green lighten-5 padding_custom_msg">
											<div class="card-content green-text padding_custom_msg2">
												<p><?php echo $msg3_2['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php }
								if (isset($error4['msg'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card red lighten-5 padding_custom_msg">
											<div class="card-content red-text padding_custom_msg2">
												<p><?php echo $error4['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } else if (isset($msg4['msg_success'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card green lighten-5 padding_custom_msg">
											<div class="card-content green-text padding_custom_msg2">
												<p><?php echo $msg4['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php }
								if (isset($error5['msg'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card red lighten-5 padding_custom_msg">
											<div class="card-content red-text padding_custom_msg2">
												<p><?php echo $error5['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } else if (isset($msg5['msg_success'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card green lighten-5 padding_custom_msg">
											<div class="card-content green-text padding_custom_msg2">
												<p><?php echo $msg5['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php }
								if (isset($error6['msg'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card red lighten-5 padding_custom_msg">
											<div class="card-content red-text padding_custom_msg2">
												<p><?php echo $error6['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } else if (isset($msg6['msg_success'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card green lighten-5 padding_custom_msg">
											<div class="card-content green-text padding_custom_msg2">
												<p><?php echo $msg6['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php }
								if (isset($error7['msg'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card red lighten-5 padding_custom_msg">
											<div class="card-content red-text padding_custom_msg2">
												<p><?php echo $error7['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } else if (isset($msg7['msg_success'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card green lighten-5 padding_custom_msg">
											<div class="card-content green-text padding_custom_msg2">
												<p><?php echo $msg7['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php }
								if (isset($error8['msg'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card red lighten-5 padding_custom_msg">
											<div class="card-content red-text padding_custom_msg2">
												<p><?php echo $error8['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } else if (isset($msg8['msg_success'])) { ?>
									<div class="col 24 s12">
										<div class="card-alert card green lighten-5 padding_custom_msg">
											<div class="card-content green-text padding_custom_msg2">
												<p><?php echo $msg8['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									</div>
								<?php } ?>
							</div>
							<!-- tabs content -->
							<!--General Tab Begin-->
							<?php 
							include('tab1_html.php'); 
							include('tab2_html.php');
							include('tab3_html.php');
							include('tab4_html.php');
							include('tab5_html.php'); 
							// include('tab6_html.php');
							// include('tab7_html.php');
							// include('tab8_html.php'); ?>
						</div>
					</div>
				</section>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div>
	<?php include("sub_files/add_store_modal.php") ?>
	<?php include("sub_files/add_vender_modal.php") ?>
	<?php include("sub_files/add_package_modal.php") ?>
	<?php include("sub_files/add_repair_type_modal.php") ?>
</div>
<br><br>
<!-- END: Page Main-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script>
	$(document).ready(function() {
		$('#remove_expense_status_date').click(function() {
			$("#expense_status_date").val('');
		});
		$('#remove_paid_date').click(function() {
			$("#paid_date").val('');
		});
		$('.day_checkbox').click(function() {
			var day_val = $(this).val();
			if ($(this).prop("checked")) {
				$(".work_and_travel_" + day_val).show();
			} else {
				$(".work_and_travel_" + day_val).hide();
				$("#day_desc_" + day_val).val('');
			}
		});
		// 
	});
</script>
<?php include("sub_files/add_store_js_code.php") ?>
<?php include("sub_files/add_vender_js_code.php") ?>
<?php include("sub_files/add_package_js_code.php") ?>
<?php include("sub_files/add_repair_type_js_code.php") ?>