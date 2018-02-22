<?php
date_default_timezone_set('Europe/Zurich');

require('./config_service.php');
require('./logger_service.php');
require('./mail_service.php');

// initiate an instance for logging
$log_service = new Logger();

// log current call of main.php with time stamp
$log_service->log("\n\n=== main.php called at ".date('d.m.Y H:i:s')." ===");


// read config and set up its logging
$config_service = new Config();
$config_service->enable_logging($log_service);

// set up mail service and its config + logging
$mail_service = new Mail();
$mail_service->enable_logging($log_service);
$mail_service->set_parameters($config_service);

$log_service->collect_summary('Test', 0, 'mail');
$log_service->collect_summary(array('Test1','Test2','Test3'), 0, 'zip');
$log_service->collect_summary(array('Test1','Test2','Test3'), 1, 'aha');

$result = $log_service->log_summary();

var_dump($result);


?>
