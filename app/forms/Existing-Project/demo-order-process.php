<?php
if( isset($_POST['name']) )
{
	require 'PHPMailer-master/PHPMailerAutoload.php';
	
	$mail = new PHPMailer;
	
	$mail->setFrom('messages@upfoundry.com');
	$mail->addAddress('taylor@upfoundry.com'); // Replace with your email
	$mail->Subject = 'Form: Existing Website Work Requested'; // Replace with your subject if you need
	
	$to = 'taylor@upfoundry.com'; // Replace with your email
	$subject = 'Form: Existing Website Work Requested'; // Replace with your subject if you need
	$mail->Body = 'Name: ' . $_POST['name'] . "\n" .
						 'Company: ' . $_POST['company']. "\n" .
						 'E-mail: ' . $_POST['email']. "\n" .
						 'Phone: ' . $_POST['phone']. "\n\n" .
						 'Expected start date: ' . $_POST['start']. "\n" .
						 'Expected finish date: ' . $_POST['finish']. "\n" .
						 'About project: ' . $_POST['comment'];
	
	if( isset($_FILES['file']['tmp_name']) )
	{
		$mail->addAttachment($_FILES['file']['tmp_name'], $_FILES['file']['name']);
	}
		
	if(!$mail->send())
	{
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	}
	else
	{
		echo 'Message has been sent';
	}
}
?>