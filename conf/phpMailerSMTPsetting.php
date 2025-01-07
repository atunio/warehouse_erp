<?php
/*
$mail->isSMTP(); 
$mail->SMTPSecure 	= 'tls';                         // Enable TLS encryption, `ssl` also accepted
$mail->Port 		= 587;      
$mail->Host 		= 'smtp.gmail.com';                    // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                            // Enable SMTP authentication
$mail->Username = 'aaaa@gmail.com';           // SMTP username
$mail->Password = '#$@';                            // TCP port to connect t
*/

$mail->SMTPDebug = 2;
$mail->IsSMTP();
$mail->Host = 'localhost';
$mail->SMTPAuth = false;
//	$mail->Host = 'relay-hosting.secureserver.net';
