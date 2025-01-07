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


if (isset($test_on_local) && $test_on_local == 1 && $cmd == 'add') {
	$vender_id					= "1";
	$po_date 					= date('d/m/Y');
	$estimated_receive_date 	= date('d/m/Y');
	$po_desc					= "purchase order desc : " . date('YmdHis');
	$is_tested_po				= "Yes";
	$is_wiped_po				= "Yes";
	$is_imaged_po				= "Yes";
	$order_status				= "0";
}
if (isset($test_on_local) && $test_on_local == 1 && (!isset($$cmd2) || (isset($$cmd2) && $cmd2 == 'add'))) {
	$product_id					= "2001";
	$order_qty					= "1";
	$order_price				= "500";
	$product_po_desc			= "product_po_desc: " . date('YmdHis');
	$is_tested					= "Yes";
	$is_wiped					= "Yes";
	$is_imaged					= "Yes";
	$product_condition			= "A Grade";
	$warranty_period_in_days	= "15";
	$vender_invoice_no			= date('YmdHis');
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

if ($cmd == 'edit' && isset($id) && $id > 0) {
	$sql_ee					= "SELECT a.* FROM purchase_orders a WHERE a.id = '" . $id . "' "; // echo $sql_ee;
	$result_ee				= $db->query($conn, $sql_ee);
	$row_ee					= $db->fetch($result_ee);
	$vender_id				= $row_ee[0]['vender_id'];
	$po_no					= $row_ee[0]['po_no'];
	$po_desc				= $row_ee[0]['po_desc'];
	$vender_invoice_no		= $row_ee[0]['vender_invoice_no'];
	$is_tested_po			= $row_ee[0]['is_tested_po'];
	$is_wiped_po			= $row_ee[0]['is_wiped_po'];
	$is_imaged_po			= $row_ee[0]['is_imaged_po'];
	$po_date				= str_replace("-", "/", convert_date_display($row_ee[0]['po_date']));
	$estimated_receive_date	= str_replace("-", "/", convert_date_display($row_ee[0]['estimated_receive_date']));
}
if (isset($cmd2) &&  $cmd2 == 'edit' && isset($detail_id) && $detail_id > 0) {
	$sql_ee						= "SELECT a.* FROM purchase_order_detail a WHERE a.id = '" . $detail_id . "' "; // echo $sql_ee;
	$result_ee					= $db->query($conn, $sql_ee);
	$row_ee						= $db->fetch($result_ee);
	$product_id					= $row_ee[0]['product_id'];
	$order_qty					= $row_ee[0]['order_qty'];
	$order_price				= $row_ee[0]['order_price'];
	$product_po_desc			= $row_ee[0]['product_po_desc'];
	$is_tested					= $row_ee[0]['is_tested'];
	$is_wiped					= $row_ee[0]['is_wiped'];
	$is_imaged					= $row_ee[0]['is_imaged'];
	$product_condition			= $row_ee[0]['product_condition'];
	$warranty_period_in_days	= $row_ee[0]['warranty_period_in_days'];
	$package_id			= $row_ee[0]['package_id'];
	$package_material_qty		= $row_ee[0]['package_material_qty'];
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
include('tab7_code.php');
include('tab8_code.php');

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
} ?>
<!-- BEGIN: Page Main-->
<div id="main" class="<?php echo $page_width; ?>">
	<div class="row">
		<div class="content-wrapper-before gradient-45deg-indigo-purple"></div>
		<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
			<!-- Search for small screen-->
			<div class="container">
				<div class="row">
					<div class="row">
						<div class="col s10 m10 20">
							<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $title_heading; ?></span></h5>
							<ol class="breadcrumbs mb-0">
								<li class="breadcrumb-item"><?php echo $title_heading; ?>
								</li>
								<li class="breadcrumb-item"><a href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>">List</a></li>
							</ol>
						</div>
						<div class="col m2 s12 m2 4">
							<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=listing") ?>" data-target="dropdown1">
								List
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col m12 s12">
			<div class="container">
				<!-- Account settings -->
				<section class="tabs-vertical mt-1 section">
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

									<div class="col 24 s12"><br>
										<div class="card-alert card red lighten-5">
											<div class="card-content red-text">
												<p><?php echo $error['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } else if (isset($msg['msg_success'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card green lighten-5">
											<div class="card-content green-text">
												<p><?php echo $msg['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } ?>
								<?php
								if (isset($error2['msg'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card red lighten-5">
											<div class="card-content red-text">
												<p><?php echo $error2['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } else if (isset($msg2['msg_success'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card green lighten-5">
											<div class="card-content green-text">
												<p><?php echo $msg2['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php }
								if (isset($error2_2['msg'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card red lighten-5">
											<div class="card-content red-text">
												<p><?php echo $error2_2['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } else if (isset($msg2_2['msg_success'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card green lighten-5">
											<div class="card-content green-text">
												<p><?php echo $msg2_2['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php }
								if (isset($error3['msg'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card red lighten-5">
											<div class="card-content red-text">
												<p><?php echo $error3['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } else if (isset($msg3['msg_success'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card green lighten-5">
											<div class="card-content green-text">
												<p><?php echo $msg3['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php }
								if (isset($error3_2['msg'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card red lighten-5">
											<div class="card-content red-text">
												<p><?php echo $error3_2['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } else if (isset($msg3_2['msg_success'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card green lighten-5">
											<div class="card-content green-text">
												<p><?php echo $msg3_2['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php }
								if (isset($error4['msg'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card red lighten-5">
											<div class="card-content red-text">
												<p><?php echo $error4['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } else if (isset($msg4['msg_success'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card green lighten-5">
											<div class="card-content green-text">
												<p><?php echo $msg4['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php }
								if (isset($error5['msg'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card red lighten-5">
											<div class="card-content red-text">
												<p><?php echo $error5['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } else if (isset($msg5['msg_success'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card green lighten-5">
											<div class="card-content green-text">
												<p><?php echo $msg5['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php }
								if (isset($error6['msg'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card red lighten-5">
											<div class="card-content red-text">
												<p><?php echo $error6['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } else if (isset($msg6['msg_success'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card green lighten-5">
											<div class="card-content green-text">
												<p><?php echo $msg6['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php }
								if (isset($error7['msg'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card red lighten-5">
											<div class="card-content red-text">
												<p><?php echo $error7['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } else if (isset($msg7['msg_success'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card green lighten-5">
											<div class="card-content green-text">
												<p><?php echo $msg7['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php }
								if (isset($error8['msg'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card red lighten-5">
											<div class="card-content red-text">
												<p><?php echo $error8['msg']; ?></p>
											</div>
											<button type="button" class="close red-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } else if (isset($msg8['msg_success'])) { ?>
									<div class="col 24 s12"><br>
										<div class="card-alert card green lighten-5">
											<div class="card-content green-text">
												<p><?php echo $msg8['msg_success']; ?></p>
											</div>
											<button type="button" class="close green-text" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div><br>
									</div>
								<?php } ?>
							</div>
							<!-- tabs content -->
							<!--General Tab Begin-->
							<?php include('tab1_html.php'); ?>
							<?php include('tab2_html.php'); ?>
							<?php include('tab3_html.php'); ?>
							<?php include('tab4_html.php'); ?>
							<?php include('tab5_html.php'); ?>
							<?php include('tab6_html.php'); ?>
							<?php include('tab7_html.php'); ?>
							<?php include('tab8_html.php'); ?>
						</div>
					</div>
				</section>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div>
	<?php include("sub_files/add_repair_type_modal.php") ?>
	<?php include("sub_files/add_product_modal.php") ?>
	<?php include("sub_files/add_vender_modal.php") ?>
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
<?php include("sub_files/add_repair_type_js_code.php") ?>
<?php include("sub_files/add_product_js_code.php") ?>
<?php include("sub_files/add_vender_js_code.php") ?>