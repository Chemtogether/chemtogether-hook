<?php

class Mail {

  private $path;
  private $logging = false;
  private $logger;

  private $username;
  private $password;
  private $from;
  private $fromname;
  private $recipient;


  public function __construct() {
    // Credentials to use for SMTP authentication, fallback to be overwritten by config
    $this->username = 'myaccount@gmail.com';
    $this->password = 'mypassword';

    // Set who the message is to be sent from, fallback to be overwritten by config
    $this->from = 'myaccount@gmail.com';
    $this->fromname = 'FirstName LastName';

    // Set who the message is to be sent to, fallback to be overwritten by config
    $this->recipient = 'someRecipient@mail.com';
  }

  public function enable_logging($logging_instance) {
    $this->logging = true;
    $this->logger = $logging_instance;
    $this->log('Logging activated.');
  }

  public function  set_parameters($config_instance) {
    // Credentials to use for SMTP authentication, fallback to be overwritten by config
    $this->username = $config_instance->get_value('credentials','mailaddress');
    $this->password = $config_instance->get_value('credentials','mailpassword');

    // Set who the message is to be sent from, fallback to be overwritten by config
    $this->from = $config_instance->get_value('mail','from');
    $this->fromname = $config_instance->get_value('mail','from_name');

    // Set who the message is to be sent to, fallback to be overwritten by config
    $this->recipient = $config_instance->get_value('mail','to');

    // log successfull reading of the config file
    $this->log('Config read.');
  }

  public function send_mail($subject, $message) {

    // log mail set up
    $this->log('Attempting to set up mail deployment.');

    // set up phpmailer dependency
    require 'phpmailer/PHPMailerAutoload.php';

    // create a new PHPMailer instance
    $phpmail = new PHPMailer;

    // tell PHPMailer to use SMTP
    $phpmail->isSMTP();

    // disable SMTP debugging
    $phpmail->SMTPDebug = 0;

    // set the hostname of the mail server
    $phpmail->Host = 'smtp.gmail.com';

    // set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $phpmail->Port = 587;

    // set the encryption system to use - ssl (deprecated) or tls
    $phpmail->SMTPSecure = 'tls';

    // whether to use SMTP authentication
    $phpmail->SMTPAuth = true;

    // username to use for SMTP authentication - use full email address for gmail
    $phpmail->Username = $this->username;

    // password to use for SMTP authentication
    $phpmail->Password = $this->password;

    // set who the message is to be sent from
    $phpmail->setFrom($this->from, $this->fromname);

    // set who the message is to be sent to
    $phpmail->addAddress($this->recipient);

    // set the subject line
    $phpmail->Subject = $subject;

    // set the message body
    $phpmail->Body = $message;

    // attempt to send the message and log result
    if(!$phpmail->send()) $this->log('ERROR: Unable to send mail.');
    else $this->log('Mail successfully send.');
  }

  private function log($message) {
    if ($this->logging) $this->logger->log($message, 'MAILER_SERVICE');
    else echo "\n[MAILER_SERVICE] ".$message."\n\n";
  }

  private function error_handling($error) {
    $this->log('ERROR: '.$error->getMessage());
  }

}


?>
