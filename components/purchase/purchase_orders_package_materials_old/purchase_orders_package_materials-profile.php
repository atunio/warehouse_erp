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
if (!isset($_SESSION['uniq_session_id'])) {
	$_SESSION['uniq_session_id'] = uniqid() . session_id();
}
$uniq_session_id     = $_SESSION['uniq_session_id'];

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
if (!isset($cmd8)) {
	$cmd8 = "";
}
if (!isset($cmd9)) {
	$cmd9 = "";
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
include('tab5_code.php');

$button_val = "Create";
if (isset($cmd) && $cmd == 'edit') {
	$title_heading = "Purchase Order (Package / Part) Profile";
	$button_val = "Update";
}
if (!isset($cmd)) {
	$title_heading 	= "Create New Purchase Order (Package / Part)";
	$button_val = "Create";
}
if (isset($cmd) && $cmd == 'add') {
	$title_heading 	= "Create New Purchase Order (Package / Part)";
	$button_val = "Create";
}
if (isset($cmd) && $cmd == 'add' && !(isset($cmd3))) {
	$title_heading 	= "Create New Purchase Order (Package / Part)";
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
							<div class="card-panel">
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
							<?php
							include('tab1_html.php'); ?>
							<?php include('tab2_html.php'); ?>
							<?php include('tab5_html.php'); ?>
							<?php /*?>
								<div id="other_expenses" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab2_2')) {
																				echo "block";
																			} else {
																				echo "none";
																			} ?>;">
									<div class="card-panel">
										<div class="row">
											<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
												<!-- Search for small screen-->
												<div class="container">
													<?php
													if (!isset($id)) { ?>
														<div class="card-alert card red">
															<div class="card-content white-text">
																<p>Please Add trip first</p>
															</div>
															<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">×</span>
															</button>
														</div>
													<?php } ?>
													<div class="row">
														<div class="col s10 m12 l8">
															<h5 class="breadcrumbs mt-0 mb-0"><span>Other Expenses</span></h5>
														</div>
													</div>
												</div>
											</div>
										</div>

										<?php
										if (isset($id)) {  ?>
											<div class="row">
												<div class="col s12 m7">
													<div class="display-flex media">
														<div class="media-body">
															<h6 class="media-heading"><span class=""><?php echo "<b>Staff Name: </b>" . $statff_name; ?></span></h6>
															<h6 class="media-heading"><span class=""><?php echo "<b>Purchase Order No: </b>" . $po_no; ?></span></h6>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
										<form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&id=" . $id . "&cmd2_2=" . $cmd2_2 . "&detail_id=" . $detail_id . "&active_tab=tab2_2") ?>" method="post">
											<input type="hidden" name="is_Submit_tab2_2" value="Y" />
											<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
											<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
											<input type="hidden" name="cmd2_2" value="<?php if (isset($cmd2_2)) echo $cmd2_2; ?>" />
											<input type="hidden" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
											<input type="hidden" name="po_id" value="<?php if (isset($po_id)) echo $po_id; ?>" />
											<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																								echo encrypt($_SESSION['csrf_session']);
																							} ?>">
											<input type="hidden" name="active_tab" value="tab2_2" />
											<div class="row">
												<div class="input-field col m4 s12">
													<i class="material-icons prefix pt-2">info_outline</i>
													<input id="expense_type" type="text" required="" name="expense_type" value="<?php if (isset($expense_type)) {
																																	echo $expense_type;
																																} ?>" class="validate <?php if (isset($expense_type_valid)) {
																																							echo $expense_type_valid;
																																						} ?>">
													<label for="expense_type">Expense Name</label>
												</div>
												<div class="input-field col m4 s12">
													<i class="material-icons prefix pt-2">work</i>
													<input id="expense_amount" type="text" required="" name="expense_amount" value="<?php if (isset($expense_amount)) {
																																		echo $expense_amount;
																																	} ?>" class="twoDecimalNumber validate <?php if (isset($expense_amount_valid)) {
																																												echo $expense_amount_valid;
																																											} ?>">
													<label for="expense_amount">Expense Amount</label>
												</div>
												<div class="input-field col m4 s12">
													<i class="material-icons prefix pt-2">library_books</i>
													<input id="expense_details" type="text" required="" name="expense_details" value="<?php if (isset($expense_details)) {
																																			echo $expense_details;
																																		} ?>" class="validate <?php if (isset($expense_details_valid)) {
																																									echo $expense_details_valid;
																																								} ?>">
													<label for="expense_details">Expense Details</label>
												</div>

											</div>
											<div class="row">
												<div class="input-field col m4 s12"></div>
												<div class="input-field col m4 s12">
													<?php if (isset($id) && $id > 0 && ($cmd2_2 == 'add' && access("add_perm") == 1)  || ($cmd2_2 == 'edit' && access("edit_perm") == 1)) { ?>
														<button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add"><?php echo $btn2_2; ?></button>
													<?php } ?>
												</div>
												<div class="input-field col m4 s12"></div>
											</div>

										</form>
									</div>

									<div class="section section-data-tables">
										<div class="row">
											<div class="col m12 s12">
												<div class="card">
													<div class="card-content">
														<div class="row">
															<div class="col m12 s12">
																<?php
																if (isset($id)) {
																	$sql_cl1 = " SELECT * FROM expenses WHERE trip_id = '" . $id . "'";
																	//echo $sql_cl1;
																	$result_cl1 	= $db->query($conn, $sql_cl1);
																	$count_cl1 		= $db->counter($result_cl1);
																	if ($count_cl1 > 0) { ?>
																		<table class="display2">
																			<thead>
																				<tr>
																					<th>Expense Name</th>
																					<th>Expense Amount</th>
																					<th>Expense Detail</th>
																					<th>Action</th>
																				</tr>
																			</thead>
																			<tbody>
																				<?php
																				$i = 0;
																				if ($count_cl1 > 0) {
																					$row_cl1 = $db->fetch($result_cl1);
																					foreach ($row_cl1 as $data) { ?>
																						<tr>
																							<td><?php echo $data['expense_type']; ?></td>
																							<td><?php echo $data['expense_amount']; ?></td>
																							<td><?php echo $data['expense_details']; ?></td>
																							<td>
																								<?php if (access("edit_perm") == 1) { ?>
																									<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&cmd2_2=edit&active_tab=tab2_2&id=" . $id . "&detail_id=" . $data['id']); ?>">
																										<i class="material-icons dp48">edit</i>
																									</a>
																								<?php } ?>
																							</td>
																						</tr>
																				<?php
																						$i++;
																					}
																				} ?>
																			</tbody>
																		</table>
																<?php }
																} ?>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="content-overlay"></div>
										<!-- Multi Select -->
									</div>
								</div>
								<div id="assessment" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab3')) {
																			echo "block";
																		} else {
																			echo "none";
																		} ?>;">
									<div class="card-panel">
										<div class="row">
											<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
												<!-- Search for small screen-->
												<div class="container">
													<div class="row">
														<?php
														if (!isset($id)) { ?>
															<div class="card-alert card red">
																<div class="card-content white-text">
																	<p>Please Add trip first</p>
																</div>
																<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
																	<span aria-hidden="true">×</span>
																</button>
															</div>
														<?php } ?>
														<div class="col s10 m12 l8">
															<h5 class="breadcrumbs mt-0 mb-0"><span>Assessment</span></h5>
														</div>
													</div>
												</div>
												<?php
												if (isset($id)) {  ?>
													<div class="row">
														<div class="col s12 m7">
															<div class="display-flex media">
																<div class="media-body">
																	<h6 class="media-heading"><span class=""><?php echo "Staff Name : " . $statff_name; ?></span></h6>
																	<h6 class="media-heading"><span class=""><?php echo "Purchase Order No : " . $po_no; ?></span></h6>
																</div>
															</div>
														</div>
													</div>
												<?php } ?>
												<div class="col s12 m12 l6">
													<div id="Form-advance" class="card card card-default scrollspy">
														<div class="card-content">

															<h4 class="card-title">Manager Assessment</h4>
															<form class="infovalidate" method="post">
																<input type="hidden" name="is_Submit_tab3" value="Y" />
																<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
																<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
																<input type="hidden" name="cmd3" value="<?php if (isset($cmd3)) echo $cmd3; ?>" />
																<input type="hidden" name="staff_ids" value="<?php if (isset($staff_ids)) echo $staff_ids; ?>" />
																<input type="hidden" name="po_id" value="<?php if (isset($po_id)) echo $po_id; ?>" />
																<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																													echo encrypt($_SESSION['csrf_session']);
																												} ?>">
																<input type="hidden" name="active_tab" value="tab3" />
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">assignment_turned_in</i>
																		<input id="schedule" type="text" min="0" max="5" required="" name="schedule" value="<?php if (isset($schedule)) {
																																								echo $schedule;
																																							} ?>" class="oneDecimalNumber validate <?php if (isset($schedule_valid)) {
																																																		echo $schedule_valid;
																																																	} ?>">
																		<label for="schedule">Schedule</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">assignment_turned_in</i>
																		<input id="preparation" type="text" min="0" max="5" required="" name="preparation" value="<?php if (isset($preparation)) {
																																										echo $preparation;
																																									} ?>" class="oneDecimalNumber validate <?php if (isset($preparation_valid)) {
																																																				echo $preparation_valid;
																																																			} ?>">
																		<label for="preparation">Preparation</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">assignment_turned_in</i>
																		<input id="communication" type="text" min="0" max="5" required="" name="communication" value="<?php if (isset($communication)) {
																																											echo $communication;
																																										} ?>" class="oneDecimalNumber validate <?php if (isset($communication_valid)) {
																																																					echo $communication_valid;
																																																				} ?>">
																		<label for="communication">Communication</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">assignment_turned_in</i>
																		<input id="behavior" type="text" min="0" max="5" required="" name="behavior" value="<?php if (isset($behavior)) {
																																								echo $behavior;
																																							} ?>" class="oneDecimalNumber validate <?php if (isset($behavior_valid)) {
																																																		echo $behavior_valid;
																																																	} ?>">
																		<label for="behavior">Behavior</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">assignment_turned_in</i>
																		<input id="report" type="text" min="0" max="5" required="" name="report" value="<?php if (isset($report)) {
																																							echo $report;
																																						} ?>" class="oneDecimalNumber validate <?php if (isset($report_valid)) {
																																																	echo $report_valid;
																																																} ?>">
																		<label for="report">Report</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">description</i>
																		<input id="manager_comments" type="text" required="" name="manager_comments" value="<?php if (isset($manager_comments)) {
																																								echo $manager_comments;
																																							} ?>" class="validate <?php if (isset($manager_comments_valid)) {
																																														echo $manager_comments_valid;
																																													} ?>">
																		<label for="manager_comments">Manager Comments</label>
																	</div>
																</div>

																<div class="row">
																	<div class="input-field col m8 s12">
																		<?php if (isset($id) && $id > 0 && ($cmd3 == 'add' && access("add_perm") == 1)  || ($cmd3 == 'edit' && access("edit_perm") == 1)) { ?>
																			<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $btn3; ?>
																				<i class="material-icons right">send</i>
																			</button>
																		<?php } ?>
																	</div>
																</div>
															</form>
														</div>
													</div>
												</div>
												<div class="col s12 m6 l6">
													<div id="Form-advance" class="card card card-default scrollspy">
														<div class="card-content">
															<h4 class="card-title">Factory Assessment</h4>
															<form method="post" autocomplete="off">
																<input type="hidden" name="is_Submit_tab4" value="Y" />
																<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
																<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
																<input type="hidden" name="cmd4" value="<?php if (isset($cmd4)) echo $cmd4; ?>" />
																<input type="hidden" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
																<input type="hidden" name="active_tab" value="tab3" />

																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">assignment_turned_in</i>
																		<input id="time_in_factory" type="text" min="0" max="5" required="" name="time_in_factory" value="<?php if (isset($time_in_factory)) {
																																												echo $time_in_factory;
																																											} ?>" class="oneDecimalNumber validate <?php if (isset($time_in_factory_valid)) {
																																																						echo $time_in_factory_valid;
																																																					} ?>">
																		<label for="time_in_factory">Time in Factory</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">assignment_turned_in</i>
																		<input id="preparation2" type="text" min="0" max="5" required="" name="preparation2" value="<?php if (isset($preparation2)) {
																																										echo $preparation2;
																																									} ?>" class="oneDecimalNumber validate <?php if (isset($preparation2_valid)) {
																																																				echo $preparation2_valid;
																																																			} ?>">
																		<label for="preparation2">Preparation</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">assignment_turned_in</i>
																		<input id="communication2" type="text" min="0" max="5" required="" name="communication2" value="<?php if (isset($communication2)) {
																																											echo $communication2;
																																										} ?>" class="oneDecimalNumber validate <?php if (isset($communication2_valid)) {
																																																					echo $communication_valid;
																																																				} ?>">
																		<label for="communication2">Communication</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">assignment_turned_in</i>
																		<input id="work_ethics" type="text" min="0" max="5" required="" name="work_ethics" value="<?php if (isset($work_ethics)) {
																																										echo $work_ethics;
																																									} ?>" class="oneDecimalNumber validate <?php if (isset($work_ethics_valid)) {
																																																				echo $work_ethics_valid;
																																																			} ?>">
																		<label for="work_ethics">Work Ethics</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">assignment_turned_in</i>
																		<input id="skills" type="text" min="0" max="5" required="" name="skills" value="<?php if (isset($skills)) {
																																							echo $skills;
																																						} ?>" class="oneDecimalNumber validate <?php if (isset($skills_valid)) {
																																																	echo $skills_valid;
																																																} ?>">
																		<label for="skills">Skills</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m12 s12">
																		<i class="material-icons prefix pt-2">description</i>
																		<input id="surveyer_comments" type="text" required="" name="surveyer_comments" value="<?php if (isset($surveyer_comments)) {
																																									echo $surveyer_comments;
																																								} ?>" class="validate <?php if (isset($surveyer_comments_valid)) {
																																															echo $surveyer_comments_valid;
																																														} ?>">
																		<label for="surveyer_comments">Surveyer Comments</label>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m8 s12">
																		<?php if (isset($id) && $id > 0 && ($cmd4 == 'add' && access("add2_perm") == 1)  || ($cmd4 == 'edit' && access("edit2_perm") == 1)) { ?>
																			<button class="btn cyan waves-effect waves-light right" type="submit" name="action"><?php echo $btn4; ?>
																				<i class="material-icons right">send</i>
																			</button>
																		<?php } ?>
																	</div>
																</div>
															</form>
														</div>
														<?php
														//include('sub_files/right_sidebar.php');
														?>
													</div>
												</div>
												<?php
												if (isset($id)) {
													$manager_assessment 			= " SELECT a.* , b.id as trip_id, c.id as staff_main_id, c.english_name, c.personal_email
																					FROM manager_assessment a
																					LEFT JOIN trip b ON b.id=a.trip_id
																					LEFT JOIN staff_main c ON c.id=a.staff_main_id
																					WHERE a.trip_id = '" . $id . "'";
													$result_manager_assessment 		= $db->query($conn, $manager_assessment);
													$count_manager_assessment 		= $db->counter($result_manager_assessment);
													if ($count_manager_assessment > 0) { ?>
														<div class="section section-data-tables">
															<div class="row">
																<div class="col m12 s12">
																	<div class="card">
																		<div class="card-content">
																			<div class="row">
																				<div class="col m12 s12">
																					<h5>Manager Assessment</h5>
																					<table class="display2">
																						<thead>
																							<tr>
																								<th>M1</th>
																								<th>M2 </th>
																								<th>M3</th>
																								<th>M4</th>
																								<th>M5</th>
																								<th>Comment</th>
																								<th>Action</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							$i = 0;
																							if ($count_manager_assessment > 0) {
																								$row_cl1 = $db->fetch($result_manager_assessment);
																								foreach ($row_cl1 as $data) {
																									$detail_id = $data['id']; ?>
																									<tr>
																										<td><?php echo $data['schedule']; ?></td>
																										<td><?php echo $data['preparation']; ?></td>
																										<td><?php echo $data['communication']; ?></td>
																										<td><?php echo $data['behavior']; ?></td>
																										<td><?php echo $data['report']; ?></td>
																										<td><?php echo $data['manager_comments']; ?></td>
																										<td>
																											<?php if (access("edit_perm") == 1) { ?>
																												<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=" . $cmd . "&cmd3=edit&active_tab=tab3&id=" . $id . "&detail_id=" . $detail_id) ?>">
																													<i class="material-icons dp48" style="color:green;">edit</i>
																												</a>
																											<?php } ?>
																										</td>
																									</tr>
																							<?php
																									$i++;
																								}
																							} ?>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="content-overlay"></div>
															<!-- Multi Select -->
														</div>
												<?php }
												} ?>
												<?php
												if (isset($id)) {
													$sql_cl2 = " SELECT a.* , b.id as trip_id, c.id as staff_main_id, c.english_name
															FROM factory_assessment a
															LEFT JOIN trip b ON b.id=a.trip_id
															LEFT JOIN staff_main c ON c.id=a.staff_main_id
																					WHERE a.trip_id = '" . $id . "'";
													$result_cl2 	= $db->query($conn, $sql_cl2);
													$count_cl2 		= $db->counter($result_cl2);
													if ($count_cl2 > 0) { ?>
														<div class="section section-data-tables">
															<div class="row">
																<div class="col m12 s12">
																	<div class="card">
																		<div class="card-content">
																			<div class="row">
																				<div class="col m12 s12">
																					<h5>Factory Assessment</h5>
																					<table class="display2">
																						<thead>
																							<tr>
																								<th>F1</th>
																								<th>F2 </th>
																								<th>F3</th>
																								<th>F4</th>
																								<th>F5</th>
																								<th>Comments</th>
																								<th>Action</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							$i = 0;
																							if ($count_cl2 > 0) {
																								$row_cl1 = $db->fetch($result_cl2);
																								foreach ($row_cl1 as $data) {
																									$detail_id = $data['id']; ?>
																									<tr>
																										<td><?php echo $data['time_in_factory']; ?></td>
																										<td><?php echo $data['preparation']; ?></td>
																										<td><?php echo $data['communication']; ?></td>
																										<td><?php echo $data['work_ethics']; ?></td>
																										<td><?php echo $data['skills']; ?></td>
																										<td><?php echo $data['surveyer_comments']; ?></td>
																										<td>
																											<?php
																											if (access("add2_perm") == 1) { ?>
																												<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=" . $cmd . "&id=" . $id . "&cmd4=edit&active_tab=tab3&detail_id=" . $detail_id) ?>">
																													<i class="material-icons dp48" style="color:green;">edit</i>
																												</a>
																											<?php } ?>
																										</td>
																									</tr>
																							<?php
																									$i++;
																								}
																							} ?>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="content-overlay"></div>
															<!-- Multi Select -->
														</div>
												<?php }
												} ?>
											</div>
										</div>
										<!--Info Tab End-->
									</div>
								</div>

								<div id="greenlight" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab4')) {
																			echo "block";
																		} else {
																			echo "none";
																		} ?>;">
									<div class="card-panel">
										<div class="row">
											<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
												<!-- Search for small screen-->
												<div class="container">
													<div class="row">
														<?php
														if (!isset($id)) { ?>
															<div class="card-alert card red">
																<div class="card-content white-text">
																	<p>Please Add trip first</p>
																</div>
																<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
																	<span aria-hidden="true">×</span>
																</button>
															</div>
														<?php } ?>
													</div>
												</div>
												<?php
												$statff_name 	= $staff_email = "";
												if (isset($id)) {
													$sql_cl_staff 	= "	SELECT a.* , c.english_name,c.personal_email 
																		FROM trip a 
																		LEFT JOIN staff_main c ON c.id = a.staff_id 
																		WHERE a.id = '" . $id . "'";
													$result_staff 	= $db->query($conn, $sql_cl_staff);
													$count_staff	= $db->counter($result_staff);
													if ($count_staff > 0) {
														$row_staff 	 = $db->fetch($result_staff);
														$statff_name = $row_staff[0]['english_name'];
														$staff_email = $row_staff[0]['personal_email'];
														$staff_ids    = $row_staff[0]['staff_id'];
													} ?>
													<div class="row">
														<div class="col s12 m4">
															<div class="display-flex media">
																<div class="media-body">
																	<h5 class="breadcrumbs mt-0 mb-0"><span>Green Light Checklist</span></h5>
																	<h6 class="media-heading"><span class=""><?php echo "Purchase Order No : " . $po_no; ?></span></h6>
																</div>
															</div>
														</div>
														<?php if (isset($green_light) && $green_light == '1') { ?>
															<div class="col s12 m4">
																<div class="card-alert card green lighten-5">
																	<div class="card-content green-text">
																		<h5 style="color: green; text-align: center;">Green Light</h5>
																	</div>
																</div>
															</div>
														<?php } else { ?>
															<div class="col s12 m4">
																<div class="card-alert card red lighten-5">
																	<div class="card-content red-text">
																		<h5 style="color: red; text-align: center;">Not ready to Start</h5>
																	</div>
																</div>
															</div>
														<?php } ?>
													</div>
												<?php } ?>
												<div class="col s12 m12 ">
													<div id="Form-advance" class="card card card-default scrollspy">
														<div class="card-content">
															<form method="post" autocomplete="off">
																<input type="hidden" name="is_Submit_tab5" value="Y" />
																<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
																<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
																<input type="hidden" name="cmd4" value="<?php if (isset($cmd4)) echo $cmd4; ?>" />
																<input type="hidden" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
																<input type="hidden" name="active_tab" value="tab4" />

																<div class="row">
																	<div class="input-field col m2 s12">
																		<?php
																		$field_name 	= "factory_exact_location";
																		$field_label	= "Factory Exact Location";
																		?>
																		<?= $field_label; ?><br>
																		<p class="mb-1 custom_radio">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="Yes" <?php if (isset(${$field_name}) && ${$field_name} == "Yes") { ?> checked="" <?php } ?>>
																				<span>Yes</span>
																			</label>
																		</p>
																		<p class="mb-1 custom_radio">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php if (isset(${$field_name}) && ${$field_name} == "No") { ?> checked="" <?php } ?>>
																				<span>No</span>
																			</label>
																		</p>
																	</div>
																	<div class="input-field col m3 s12">
																		<?php
																		$field_name 	= "factory_exact_location_detail";
																		$field_label	= "Exact Location Detail";
																		?>
																		<div id="<?= $field_name; ?>_div" style="<?php if (!isset($factory_rough_location) || (isset($factory_rough_location) && $factory_rough_location != 'Yes')) { ?> display: none;<?php } ?>">
																			<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name}) && ${$field_name} != "") {
																																								echo ${$field_name};
																																							} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
																			<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
																		</div>
																	</div>
																	<div class="input-field col m1 s12">&nbsp;</div>
																	<div class="input-field col m2 s12">
																		<?php
																		$field_name 	= "service_timing_confirmed";
																		$field_label	= "Service Timing Confirmed";
																		?>
																		<?= $field_label; ?><br>
																		<p class="mb-1 custom_radio">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="Yes" <?php if (isset(${$field_name}) && ${$field_name} == "Yes") { ?> checked="" <?php } ?>>
																				<span>Yes</span>
																			</label>
																		</p>
																		<p class="mb-1 custom_radio">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php if (isset(${$field_name}) && ${$field_name} == "No") { ?> checked="" <?php } ?>>
																				<span>No</span>
																			</label>
																		</p>
																	</div>
																	<div class="input-field col m3 s12">
																		<?php
																		$field_name 	= "service_timing_confirmed_detail";
																		$field_label	= "Service Timing Detail";
																		?>
																		<div id="<?= $field_name; ?>_div" style="<?php if (!isset($tax_information) || (isset($tax_information) && $tax_information != 'Yes')) { ?> display: none;<?php } ?>">
																			<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name}) && ${$field_name} != "") {
																																								echo ${$field_name};
																																							} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
																			<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
																		</div>
																	</div>
																</div>
																<div class="row">
																	<div class="input-field col m6 s12">
																		<?php
																		$field_name 	= "final_task_information_confirmed";
																		$field_label	= "Final Task Information Confirmed";
																		?>
																		<?= $field_label; ?><br>
																		<p class="mb-1 custom_radio25">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="Yes" <?php if (isset(${$field_name}) && ${$field_name} == "Yes") { ?> checked="" <?php } ?>>
																				<span>Yes</span>
																			</label>
																		</p>
																		<p class="mb-1 custom_radio25">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php if (isset(${$field_name}) && ${$field_name} == "No") { ?> checked="" <?php } ?>>
																				<span>No</span>
																			</label>
																		</p>
																		<p class="mb-1 custom_radio50">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="Same" <?php if (isset(${$field_name}) && ${$field_name} == "Same") { ?> checked="" <?php } ?>>
																				<span>Same as Project Info</span>
																			</label>
																		</p>
																	</div>
																	<div class="input-field col m6 s12">
																		<?php
																		$field_name 	= "final_task_information_confirmed_detail";
																		$field_label	= "Final Task Information";
																		?>
																		<div id="<?= $field_name; ?>_div" style="<?php if (!isset($factory_rough_location) || (isset($factory_rough_location) && $factory_rough_location != 'Yes')) { ?> display: none;<?php } ?>">
																			<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name}) && ${$field_name} != "") {
																																								echo ${$field_name};
																																							} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
																			<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
																		</div>
																	</div>
																</div>

																<div class="row">
																	<div class="input-field col m2 s12">
																		<?php
																		$field_name 	= "engineer_availability";
																		$field_label	= "Engineer Availability";
																		?>
																		<?= $field_label; ?><br>
																		<p class="mb-1 custom_radio">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="Yes" <?php if (isset(${$field_name}) && ${$field_name} == "Yes") { ?> checked="" <?php } ?>>
																				<span>Yes</span>
																			</label>
																		</p>
																		<p class="mb-1 custom_radio">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php if (isset(${$field_name}) && ${$field_name} == "No") { ?> checked="" <?php } ?>>
																				<span>No</span>
																			</label>
																		</p>
																	</div>
																	<div class="input-field col m3 s12">
																		<?php
																		$field_name 	= "engineer_availability_detail";
																		$field_label	= "Engineer Availability Detail";
																		?>
																		<div id="<?= $field_name; ?>_div" style="<?php if (!isset($tax_information) || (isset($tax_information) && $tax_information != 'Yes')) { ?> display: none;<?php } ?>">
																			<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name}) && ${$field_name} != "") {
																																								echo ${$field_name};
																																							} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
																			<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
																		</div>
																	</div>
																	<div class="input-field col m1 s12">&nbsp;</div>
																	<div class="input-field col m2 s12">
																		<?php
																		$field_name 	= "engineer_trained";
																		$field_label	= "Engineer Trained";
																		?>
																		<?= $field_label; ?><br>
																		<p class="mb-1 custom_radio">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="Yes" <?php if (isset(${$field_name}) && ${$field_name} == "Yes") { ?> checked="" <?php } ?>>
																				<span>Yes</span>
																			</label>
																		</p>
																		<p class="mb-1 custom_radio">
																			<label>
																				<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="radio" value="No" <?php if (isset(${$field_name}) && ${$field_name} == "No") { ?> checked="" <?php } ?>>
																				<span>No</span>
																			</label>
																		</p>
																	</div>
																	<div class="input-field col m3 s12">
																		<?php
																		$field_name 	= "engineer_trained_detail";
																		$field_label	= "Engineer Trained Detail";
																		?>
																		<div id="<?= $field_name; ?>_div" style="<?php if (!isset($factory_rough_location) || (isset($factory_rough_location) && $factory_rough_location != 'Yes')) { ?> display: none;<?php } ?>">
																			<input type="text" id="<?= $field_name; ?>" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name}) && ${$field_name} != "") {
																																								echo ${$field_name};
																																							} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																														echo ${$field_name . "_valid"};
																																													} ?>">
																			<label for="<?= $field_name; ?>"><?= $field_label; ?></label>
																		</div>
																	</div>
																</div>

																<div class="row">
																	<div class="input-field col m2 s12">&nbsp;</div>
																	<div class="input-field col m4 s12">
																		<?php if (isset($id) && $id > 0 && ($cmd4 == 'add' && access("add2_perm") == 1)  || ($cmd4 == 'edit' && access("edit2_perm") == 1)) { ?>
																			<button class="btn cyan waves-effect waves-light right" type="submit" name="action">Update
																				<i class="material-icons right">send</i>
																			</button>
																		<?php } ?>
																	</div>
																</div>
															</form>
														</div>
														<?php
														//include('sub_files/right_sidebar.php');
														?>
													</div>
												</div>
												<?php
												if (isset($id)) {
													$manager_assessment 			= " SELECT a.* , b.id as trip_id, c.id as staff_main_id, c.english_name, c.personal_email
																					FROM manager_assessment a
																					LEFT JOIN trip b ON b.id=a.trip_id
																					LEFT JOIN staff_main c ON c.id=a.staff_main_id
																					WHERE a.trip_id = '" . $id . "'";
													$result_manager_assessment 		= $db->query($conn, $manager_assessment);
													$count_manager_assessment 		= $db->counter($result_manager_assessment);
													if ($count_manager_assessment > 0) { ?>
														<div class="section section-data-tables">
															<div class="row">
																<div class="col m12 s12">
																	<div class="card">
																		<div class="card-content">
																			<div class="row">
																				<div class="col m12 s12">
																					<h5>Manager Assessment</h5>
																					<table class="display2">
																						<thead>
																							<tr>
																								<th>M1</th>
																								<th>M2 </th>
																								<th>M3</th>
																								<th>M4</th>
																								<th>M5</th>
																								<th>Comment</th>
																								<th>Action</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							$i = 0;
																							if ($count_manager_assessment > 0) {
																								$row_cl1 = $db->fetch($result_manager_assessment);
																								foreach ($row_cl1 as $data) {
																									$detail_id = $data['id']; ?>
																									<tr>
																										<td><?php echo $data['schedule']; ?></td>
																										<td><?php echo $data['preparation']; ?></td>
																										<td><?php echo $data['communication']; ?></td>
																										<td><?php echo $data['behavior']; ?></td>
																										<td><?php echo $data['report']; ?></td>
																										<td><?php echo $data['manager_comments']; ?></td>
																										<td>
																											<?php if (access("edit_perm") == 1) { ?>
																												<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=" . $cmd . "&cmd3=edit&active_tab=tab3&id=" . $id . "&detail_id=" . $detail_id) ?>">
																													<i class="material-icons dp48" style="color:green;">edit</i>
																												</a>
																											<?php } ?>
																										</td>
																									</tr>
																							<?php
																									$i++;
																								}
																							} ?>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="content-overlay"></div>
															<!-- Multi Select -->
														</div>
													<?php }
												}
												if (isset($id)) {
													$sql_cl2 = " SELECT a.* , b.id as trip_id, c.id as staff_main_id, c.english_name
															FROM factory_assessment a
															LEFT JOIN trip b ON b.id=a.trip_id
															LEFT JOIN staff_main c ON c.id=a.staff_main_id
																					WHERE a.trip_id = '" . $id . "'";
													$result_cl2 	= $db->query($conn, $sql_cl2);
													$count_cl2 		= $db->counter($result_cl2);
													if ($count_cl2 > 0) { ?>
														<div class="section section-data-tables">
															<div class="row">
																<div class="col m12 s12">
																	<div class="card">
																		<div class="card-content">
																			<div class="row">
																				<div class="col m12 s12">
																					<h5>Factory Assessment</h5>
																					<table class="display2">
																						<thead>
																							<tr>
																								<th>F1</th>
																								<th>F2 </th>
																								<th>F3</th>
																								<th>F4</th>
																								<th>F5</th>
																								<th>Comments</th>
																								<th>Action</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							$i = 0;
																							if ($count_cl2 > 0) {
																								$row_cl1 = $db->fetch($result_cl2);
																								foreach ($row_cl1 as $data) {
																									$detail_id = $data['id']; ?>
																									<tr>
																										<td><?php echo $data['time_in_factory']; ?></td>
																										<td><?php echo $data['preparation']; ?></td>
																										<td><?php echo $data['communication']; ?></td>
																										<td><?php echo $data['work_ethics']; ?></td>
																										<td><?php echo $data['skills']; ?></td>
																										<td><?php echo $data['surveyer_comments']; ?></td>
																										<td>
																											<?php
																											if (access("add2_perm") == 1) { ?>
																												<a class="" href="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=" . $cmd . "&id=" . $id . "&cmd4=edit&active_tab=tab3&detail_id=" . $detail_id) ?>">
																													<i class="material-icons dp48" style="color:green;">edit</i>
																												</a>
																											<?php } ?>
																										</td>
																									</tr>
																							<?php
																									$i++;
																								}
																							} ?>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="content-overlay"></div>
															<!-- Multi Select -->
														</div>
												<?php }
												} ?>
											</div>
										</div>
										<!--Info Tab End-->
									</div>
								</div>
								<div id="schedule_s" style="display: <?php if (!isset($active_tab) || (isset($active_tab) && $active_tab == 'tab5')) {
																			echo "block";
																		} else {
																			echo "none";
																		} ?>;">

									<div class="card-panel">
										<div class="row">
											<div class="breadcrumbs-dark pb-0" id="breadcrumbs-wrapper">
												<!-- Search for small screen-->
												<div class="container">
													<?php
													if (!isset($id)) { ?>
														<div class="card-alert card red">
															<div class="card-content white-text">
																<p>Please Add trip first</p>
															</div>
															<button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
																<span aria-hidden="true">×</span>
															</button>
														</div>
													<?php } ?>
													<div class="row">
														<div class="col s10 m12 l8">
															<h5 class="breadcrumbs mt-0 mb-0"><span>Schedule Days</span></h5>
														</div>
													</div>
												</div>
											</div>
										</div>
										<form class="infovalidate" action="?string=<?php echo encrypt("module=" . $module . "&module_id=" . $module_id . "&page=add&cmd=edit&id=" . $id . "&cmd5=" . $cmd5 . "&detail_id=" . $detail_id . "&active_tab=tab5") ?>" method="post">
											<input type="hidden" name="is_Submit_tab6" value="Y" />
											<input type="hidden" name="cmd" value="<?php if (isset($cmd)) echo $cmd; ?>" />
											<input type="hidden" name="id" value="<?php if (isset($id)) echo $id; ?>" />
											<input type="hidden" name="cmd5" value="<?php if (isset($cmd5)) echo $cmd5; ?>" />
											<input type="hidden" name="detail_id" value="<?php if (isset($detail_id)) echo $detail_id; ?>" />
											<input type="hidden" name="po_id" value="<?php if (isset($po_id)) echo $po_id; ?>" />
											<input type="hidden" name="csrf_token" value="<?php if (isset($_SESSION['csrf_session'])) {
																								echo encrypt($_SESSION['csrf_session']);
																							} ?>">
											<input type="hidden" name="active_tab" value="tab5" />

											<div class="row">
												<?php
												for ($i = 1; $i <= $number_of_days; $i++) { ?>
													<div class="input-field col m12 s12">
														<div class="row">
															<div class="input-field col m2 s12">
																<?php
																$field_name = "schedule_days";
																$field_label = "Day: " . $i; ?>
																<p class="mb-1 custom_radio">
																	<label>
																		<input class="day_checkbox" name="<?= $field_name; ?>[]" class="<?= $field_name; ?>[]" id="<?= $field_name; ?>[]" type="checkbox" value="<?= $i; ?>" <?php if (isset(${$field_name}) && in_array($i, ${$field_name})) { ?> checked="" <?php } ?>>
																		<span><b><?= $field_label; ?></b></span>
																	</label>
																</p>
															</div>
															<div class="input-field col m2 s12">
																<?php
																$field_name = "day_travel_" . $i;
																$field_name2 = "schedule_days";
																$field_label = "Travel"; ?>
																<p class="mb-1 custom_radio work_and_travel_<?= $i; ?>" style="<?php if (isset(${$field_name2}) && in_array($i, ${$field_name2})) { ?>  <?php } else { ?> display:
																																															none; <?php } ?>">
																	<label>
																		<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="checkbox" value="1" <?php if (isset(${$field_name}) && ${$field_name} == "1") { ?> checked="" <?php } ?>>
																		<span><b><?= $field_label; ?></b></span>
																	</label>
																</p>
															</div>
															<div class="input-field col m2 s12">
																<?php
																$field_name = "day_work_" . $i;
																$field_label = "Work"; ?>
																<p class="mb-1 custom_radio work_and_travel_<?= $i; ?>" style="<?php if (isset(${$field_name2}) && in_array($i, ${$field_name2})) { ?>  <?php } else { ?> display:
																																															none; <?php } ?>">
																	<label>
																		<input name="<?= $field_name; ?>" class="<?= $field_name; ?>" id="<?= $field_name; ?>" type="checkbox" value="1" <?php if (isset(${$field_name})  && ${$field_name} == "1") { ?> checked="" <?php } ?>>
																		<span><b><?= $field_label; ?></b></span>
																	</label>
																</p>
															</div>
															<div class="input-field col m6 s12 work_and_travel_<?= $i; ?>" style="<?php if (isset(${$field_name2}) && in_array($i, ${$field_name2})) { ?>  <?php } else { ?> display:
																																															none; <?php } ?>">
																<?php
																$field_name 	= "day_desc_" . $i;
																$field_label 	= "Description"; ?>
																<i class="material-icons prefix pt-2">description</i>
																<input id="<?= $field_name; ?>" type="text" name="<?= $field_name; ?>" value="<?php if (isset(${$field_name})) {
																																					echo ${$field_name};
																																				} ?>" class="validate <?php if (isset(${$field_name . "_valid"})) {
																																											echo ${$field_name . "_valid"};
																																										} ?>">
																<label for="<?= $field_name; ?>"><b><?= $field_label; ?></b> <?php if (isset($error5["day_desc_" . $i])) {
																																	echo "<spans style='color: red;'>(" . $error5["day_desc_" . $i] . ")</span>";
																																} ?></label>
															</div>
														</div>
													</div>
												<?php } ?>
											</div>
											<div class="row">
												<div class="input-field col m4 s12"></div>
												<div class="input-field col m4 s12">
													<?php if (isset($id) && $id > 0 && ($cmd5 == 'add' && access("add_perm") == 1)  || ($cmd5 == 'edit' && access("edit_perm") == 1)) { ?>
														<button class="waves-effect waves-light  btn gradient-45deg-purple-deep-orange box-shadow-none border-round mr-1 mb-1" type="submit" name="add"><?php echo $btn5; ?></button>
													<?php } ?>
												</div>
												<div class="input-field col m4 s12"></div>
											</div>

										</form>
									</div>
								</div>
							<?php */ ?>
				</section>
				<?php include('sub_files/right_sidebar.php'); ?>
			</div>
		</div>
	</div>
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
<?php include("sub_files/add_repair_type_js_code.php") ?>