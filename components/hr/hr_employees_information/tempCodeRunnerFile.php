<?php
$sql = "INSERT INTO ".$selected_db_name.".employee_education(school_admin_id, e_institution_name, e_school_date_from, e_school_date_to, e_degree, e_study_area, add_date, add_by, add_ip)
						VALUES('".$school_admin_id."', '".$e_institution_name."', '".$e_school_date_from."', '".$e_school_date_to."', '".$e_degree."', '".$e_study_area."', '".$add_date."', '".$_SESSION['username']."', '".$add_ip."')";
				
				$ok = $db->query($conn, $sql);