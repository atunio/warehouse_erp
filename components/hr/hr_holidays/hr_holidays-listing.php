<?php
if (!isset($module)) {
	require_once('../../conf/functions.php');
	disallow_direct_school_directory_access();
}
$db = new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$school_admin_id 	= $_SESSION["school_admin_id"];
$user_id 	= $_SESSION["user_id"];

$sql_cl 		= "	SELECT a.*
					FROM " . $selected_db_name . ".emp_holiday a
					WHERE a.school_admin_id = '" . $school_admin_id . "'
					ORDER BY a.enabled DESC, a.holiday_name "; //echo $sql_cl;
$result_cl 		= $db->query($conn, $sql_cl);
$count_cl 		= $db->counter($result_cl);
$page_heading = "Holidays";
function custom_echo($x, $length)
{
	$x = strip_tags($x);
	if (strlen($x) <= $length) {
		echo $x;
	} else {
		$y = substr($x, 0, $length) . '...';
		echo $y;
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
						<h5 class="breadcrumbs-title mt-0 mb-0"><span><?php echo $page_heading; ?></span></h5>
						<ol class="breadcrumbs mb-0">
							<li class="breadcrumb-item"><a href="home">Home</a>
							</li>
							</li>
							<li class="breadcrumb-item active">List</li>
						</ol>
					</div>
					<div class="col s2 m6 l6">
						<a class="btn waves-effect waves-light green darken-1 breadcrumbs-btn right" href="?string=<?php echo encrypt("module=" . $module . "&page=add&cmd=add") ?>" data-target="dropdown1">
							Add New
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
									<h4 class="card-title"><?php echo $page_heading; ?></h4>
									<div class="row">
										<div class="col s12">
											<table id="page-length-option" class="display">
												<thead>
													<tr>
														<th width="5%">S.No</th>
														<th>Holiday Name</th>
														<th>Duration</th>
														<th>Description</th>
														<th width="20%">Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i = 0;
													if ($count_cl > 0) {
														$row_cl = $db->fetch($result_cl);
														foreach ($row_cl as $data) {
															$id	= $data['id']; ?>
															<tr data-id="<?php echo $id; ?>">
																<td><?php echo $i + 1; ?></td>
																<td><?php echo $data['holiday_name']; ?></td>
																<td><?php echo dateformat2($data['holiday_from']); ?> - <?php echo dateformat2($data['holiday_to']); ?></td>
																<td><?php echo $data['hol_description']; ?></td>
																<td class="text-align-center">
																	<a href="javascript:void(0)" class="<?php if ($data['enabled'] == '1') { ?>green-text<?php } else { ?>red-text<?php } ?>" onclick="change_status(this,'<?php echo $id ?>')"><?php echo ($data['enabled'] == '1') ? 'Enable' : 'Disable'; ?></a>
																	&nbsp;&nbsp;
																	<a class="" href="?string=<?php echo encrypt("module=" . $module . "&page=add&cmd=edit&id=" . $data['id']) ?>">
																		<i class="material-icons dp48">edit</i>
																	</a> &nbsp;&nbsp;
																</td>
															</tr>
													<?php
															$i++;
														}
													} ?>
												<tfoot>
													<tr>
														<th width="5%">S.No</th>
														<th>Holiday Name</th>
														<th>Duration</th>
														<th>Description</th>
														<th width="20%">Actions</th>
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