<?php
if (isset($test_on_local) && $test_on_local == 1) {
	if (isset($cmd) && $cmd != 'edit') {
	}
}
if (!isset($module)) {
	require_once('../../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db 					= new mySqlDB;
$selected_db_name 		= $_SESSION["db_name"];
$subscriber_users_id 	= $_SESSION["subscriber_users_id"];
$user_id 				= $_SESSION["user_id"];
if (!isset($_SESSION['csrf_session'])) {
	$_SESSION['csrf_session'] = session_id();
}

if (!isset($cmd2)) {
	$cmd2 = "add";
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

extract($_POST);
foreach ($_POST as $key => $value) {
	if (!is_array($value)) {
		$data[$key] = remove_special_character(trim(htmlspecialchars(strip_tags(stripslashes($value)), ENT_QUOTES, 'UTF-8')));
		$$key = $data[$key];
	}
}

include('tab1_code.php');
include('tab2_code.php');

$button_val = "Create";
if (isset($cmd) && $cmd == 'edit') {
	$title_heading = "RMA Detail";
	$button_val = "Update";
}
if (!isset($cmd)) {
	$title_heading 	= "Create New RMA";
	$button_val = "Create";
}
if (isset($cmd) && $cmd == 'add') {
	$title_heading 	= "Create New RMA";
	$button_val = "Create";
}
if (isset($cmd) && $cmd == 'add' && !(isset($cmd3))) {
	$title_heading 	= "Create New RMA";
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
						<div class="col l3 s12">
							<!-- tabs  -->
							<div class="card-panel">
								<?php include("tabs.php") ?>
							</div>
						</div>
						<div class="col l9 s12">
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
								<?php } ?>
							</div>
							<!-- tabs content -->
							<!--General Tab Begin-->
							<?php
							include('tab1_html.php'); ?>
							<?php include('tab2_html.php'); ?>
						</div>
				</section>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
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