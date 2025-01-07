<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//require 'vendor/phpmailer/phpmailer/src/Exception.php';
//require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
try {

	//Recipients
	$mail->setFrom('no-reply@aaaa.com', 'ABC');
	$mail->addAddress($email, 'Test');     // Add a recipient
	//$mail->addAddress('tlp@gmail.com', 'Aftab');     // Add a recipient
	//$mail->addAddress('zzzzz@gmail.com', 'Aftab');     // Add a recipient
	$mail->addReplyTo('aaaa@gmail.com', 'Information');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('bcc@example.com');

	// Attachments
	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	// Content
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Subject = 'Here is the subject' . date('d-m-Y h:i:s');
	$mail->Body    = 'This is the HTML message body <b>in bold!</b>' . date('d-m-Y h:i:s');
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients' . date('d-m-Y h:i:s');

	$mail->send();
	echo 'Message has been sent' . date('d-m-Y h:i:s');
} catch (Exception $e) {
	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
