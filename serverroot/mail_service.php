<?php

function mail_service($mail, $credentials) {
  // SMTP needs accurate times, and the PHP time zone MUST be set
  date_default_timezone_set('Europe/Zurich');
  require 'phpmailer/PHPMailerAutoload.php';

  // Create a new PHPMailer instance
  $phpmail = new PHPMailer;

  // Tell PHPMailer to use SMTP
  $phpmail->isSMTP();

  // Disable SMTP debugging
  $phpmail->SMTPDebug = 0;

  // Ask for HTML-friendly debug output
  $phpmail->Debugoutput = 'html';

  // Set the hostname of the mail server
  $phpmail->Host = 'smtp.gmail.com';

  // Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
  $phpmail->Port = 587;

  // Set the encryption system to use - ssl (deprecated) or tls
  $phpmail->SMTPSecure = 'tls';

  // Whether to use SMTP authentication
  $phpmail->SMTPAuth = true;

  // Username to use for SMTP authentication - use full email address for gmail
  $phpmail->Username = $credentials['mailaddress'];

  // Password to use for SMTP authentication
  $phpmail->Password = $credentials['mailpassword'];

  // Set who the message is to be sent from
  $phpmail->setFrom($mail['from'], $mail['from_name']);

  // Set who the message is to be sent to
  $phpmail->addAddress($mail['to']);

  // Set the subject line
  $phpmail->Subject = $mail['subject'];

  // Set the message body
  $phpmail->Body = $mail['body'];

  // Send the message, return according to success
  if(!$phpmail->send()) {
    return 1;
  } else {
    return 0;
  }
}

?>
