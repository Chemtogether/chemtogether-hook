<?php

// read config
$config = parse_ini_file('./config.ini', true);

$credentials = $config['credentials'];
$mail = $config['mail'];

$mail['subject'] = 'test';
$mail['body'] = 'Test';

include('mail_service.php');

echo(mail_service($mail, $credentials));


 ?>
