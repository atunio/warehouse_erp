<?php
include('path.php');
include($directory_path."conf/session_start.php");
include($directory_path."conf/connection.php");
include($directory_path."conf/functions.php");
$db 				= new mySqlDB;
$selected_db_name 	= $_SESSION["db_name"];
$school_admin_id 	= $_SESSION["school_admin_id"];
$teacher_user_id 	= $_SESSION["user_id"]; 
$parm1 = ""; $parm2 = "";  $parm3 = "";
check_session_exist4($db, $conn, $_SESSION["user_id"], $_SESSION["username"], $_SESSION["user_type"], $_SESSION["db_name"], $parm2, $parm3);

$module = "";
extract($_POST);
$check_module_permission = "";
$check_module_permission = check_module_permission_user($db, $conn, $module, $_SESSION["user_id"], $_SESSION["school_admin_id"], $_SESSION["user_type"], $_SESSION["db_name"]);
$pageTitle 	= $check_module_permission;
if($check_module_permission == ""){
	header( "location: ".$directory_path."signout");
}

$sql_ee1 	= "	SELECT g.school_name, g.school_logo, h.school_group_name 
				FROM school_admin g 
				INNER JOIN school_owner h ON h.id = g.owner_user_id
				WHERE g.id = '".$school_admin_id."' "; //echo $sql_ee1;die;
$result_ee1 	= $db->query($conn, $sql_ee1);
$count_ee1 	= $db->counter($result_ee1);
if($count_ee1 >0){
	$row_ee1						= $db->fetch($result_ee1);
	$school_name					= $row_ee1[0]['school_name'];
	$school_logo					= $row_ee1[0]['school_logo'];
	$school_group_name				= $row_ee1[0]['school_group_name'];
 } 

 $date_from1 	= convert_date_mysql_slash($date_from);
 $date_to1 		= convert_date_mysql_slash($date_to);
 
 $date_from2 	= str_replace("-", "", $date_from1);
 $date_to2 		= str_replace("-", "", $date_to1); 
 
 $date_from3 	= dateformat2($date_from1);
 $date_to3 		= dateformat2($date_to1);
 
$sql_cl 	= "	SELECT  a.*, c.department_name, d.designation, e.att_date, e.is_absent, e.att_time_in, e.att_time_out
				FROM ".$selected_db_name.".employee_profile a
				INNER JOIN ".$selected_db_name.".hr_emp_employment_history b ON a.id = b.emp_id  
				AND b.id = ( SELECT MAX(id) FROM ".$selected_db_name.".hr_emp_employment_history WHERE enabled = 1 AND emp_id = b.emp_id)
				INNER JOIN ".$selected_db_name.".departments c ON c.id = b.dept_id
				INNER JOIN ".$selected_db_name.".designations d ON d.id = b.designation_id
				INNER JOIN ".$selected_db_name.".emp_attendance e ON e.emp_id = a.id 
				WHERE e.enabled = 1 AND a.enabled = 1 AND a.emp_status = 'Active' 
				AND a.school_admin_id = '".$school_admin_id."' ";
if(isset($emp_id) && $emp_id !=""){
	$sql_cl .= "AND a.id ='".$emp_id."' ";  
} 
$sql_cl 	.= " AND DATE_FORMAT(e.att_date, '%Y%m%d') BETWEEN '".$date_from2."' AND '".$date_to2."' ";
$sql_cl 	.= "	ORDER BY e.att_date DESC, c.department_name, a.e_full_name ";  
//echo $sql_cl;die;
$result_cl 	= $db->query($conn, $sql_cl);
$count_cl 	= $db->counter($result_cl);

$css = "
	<style>
		h1 {
			color: navy;
			font-size: 24pt;
			text-decoration: underline;
		}
		h2 {
			color: #000066;
			font-size: 16pt;
			text-align: center;
		}
		h3 {
			color: #000066;
			font-size: 10pt;
		}
		table{
			font-family: helvetica;
		}
		table.top{
			width: 100%;
			font-size: 11px;
		}
		table.second{
			color: #00000;
			font-size: 14px;
			width: 100%;
			font-weight: bold;
		}
		table.first {
			color: #000066;
			font-size: 10px;
			width: 100%;
		}
		.first th {
			border: 1px solid #999;
			background-color: #EAEDEE;
			text-align: center;
			vertical-align:middle;
		}
		.first td {
			border: 1px solid #999;
			text-align: center;
			font-weight: bold;
		} 
		.student_info_section{
			background-color: #EAEDEE;
			border: 3px solid #999;
			border-radius: 5px;
			width: 100%;
			padding: 5px 20px;
		}   
		.promotion_statment{
			font-size: 11px;
		}
	</style>";
$report_data = '<table class="top" cellpadding="5">
					<tbody>
						<tr>  
							<td width="10%">
								<img src="../../app-assets/images/logo/'.$school_logo.'" style="width: 100px;" alt="'.$school_name.'" title="'.$school_name.'">
							</td>
							<td width="90%">
								<h2>'.$school_name.'</h2>
								<h2><i>'.$school_group_name.'<i></h2>
							</td>
						</tr>
					</tbody>
				</table>
				<h2>Attendace Detail Report</h2>';
$report_data .=' <div class="student_info_section">
					<table class="top" cellpadding="5">
						<tbody>
							<tr>  
								<td width="50%">
									<table class="top" cellpadding="5">
										<tbody>
											<tr>   
												<td><b>Date From: </b>'.$date_from3.' => <b>Date To:  </b>'.$date_to3.'</td>
											</tr> 
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<br>
				<table class="first" cellpadding="5">
					<thead>
						<tr> 
							<th width="5%">S.No</th>
							<th width="10%">EmpID</th>
							<th width="12%">EmpCode</th>
							<th width="30%">Emp Detail</th>
							<th width="13%">Date</th>
							<th width="10%">Attendance</th>
							<th width="10%">Time In</th>
							<th width="10%">Time Out</th>
						</tr>
					</thead>
					<tbody>';
					$i = 1;
					if($count_cl >0){
						$row_cl = $db->fetch($result_cl);
						foreach($row_cl as $data_detail){
							$e_full_name 				= $data_detail['e_full_name'];
							$department_name			= $data_detail['department_name'];
							$designation 				= $data_detail['designation'];
							$emp_id 					= $data_detail['id'];
							$att_date 					= $data_detail['att_date'];
							$is_absent 					= $data_detail['is_absent'];
							$att_time_in 				= $data_detail['att_time_in'];
							$att_time_out 				= $data_detail['att_time_out'];
							$emp_code 					= $data_detail['emp_code'];
							if($is_absent == "Absent" || $is_absent == "Leave"){
								$att_time_in = "";
								$att_time_out = "";
							}
							$report_data .=" <tr nobr='true'> 
												<td align='center'><b>".$i."</b></td>
												<td align='left'><b>".$emp_id."</b></td>
												<td align='left'><b>".$emp_code."</b></td>
												<td align='left'>
													<b>".$e_full_name."</b><br>
													<b>".$designation."</b><br>
													<b>".$department_name."</b>
												</td>
												<td align='center'><b>".dateformat2($att_date)."</b></td>
												<td align='center'><b>".$is_absent."</b></td>
												<td align='center'><b>".$att_time_in."</b></td>
												<td align='center'><b>".$att_time_out."</b></td> 
											</tr>"; 
							$i++;
						}
					}
$subject_name = " (Emp)";					
$report_data .= '</tbody></table> '.$css;  //echo $report_data;die;
require_once '../../mpdf/vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
$mpdf->SetTitle('Attendance Report '.$subject_name);
$file_name = "Attendance Report ".$subject_name.".pdf";
$mpdf->AddPage('P','','','','',5,5,5,5,0,0);
$mpdf->writeHTML($report_data);
$mpdf->output($file_name, 'I');
?>