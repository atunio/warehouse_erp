<?php

	require_once('conf/functions.php');
	require_once('conf/connection.php');

	$db 	= new mySqlDB;



	header("Content-Type: application/xls");    
	header("Content-Disposition: attachment; filename=customer.xls");  
	header("Pragma: no-cache"); 
	header("Expires: 0");
		
	
	$output = "";
	
	$output .="
		<table>
			<thead>
				<tr>
					<th>S.no</th>
					<th>Customer Name</th>
					<th>Country</th>
					<th>Contact Person</th>
					<th>Email Address</th>
				</tr>
			<tbody>
	";
	
	$sql_cl = "	SELECT b.country_name, a.* 
	FROM customer a 
	LEFT JOIN countries b ON b.id = a.country_id
	ORDER BY a.enabled ASC, a.id ASC ";
	$result_cl 	= $db->query($conn, $sql_cl);

	$count_cl 	= $db->counter($result_cl);
	$row_cl = $db->fetch($result_cl);

	if ($count_cl > 0) {
	foreach ($row_cl as $data) { 
	$output .= "
				<tr>
					<td>".$data['id']."</td>
					<td>".$data['customer_name']."</td>
					<td>".$data['contact_person']."</td>
					<td>".$data['contact_person_email']."</td>
				</tr>
	";
	}
}
	$output .="
			</tbody>
			
		</table>
	";
	
	echo $output;
	
	
?>