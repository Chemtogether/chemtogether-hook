<?php
date_default_timezone_set('Europe/Zurich');

require('./config_service.php');
require('./logger_service.php');
require('./mail_service.php');
require('./backup_service.php');
require('./git_service.php');

// initiate an instance for logging
$log_service = new Logger();

// log current call of main.php with time stamp
$log_service->log("\n\n=== main.php called at ".date('d.m.Y H:i:s')." ===");

// read config and set up its logging
$log_service->log('Set up of config service.');
$config_service = new Config();
$config_service->enable_logging($log_service);

// set up mail service and its config + logging
$log_service->log('Set up of mail service.');
$mail_service = new Mail();
$mail_service->enable_logging($log_service);
$mail_service->set_parameters($config_service);

// set up backup service and its logging
$log_service->log('Set up of backup service.');
$backup_service = new Backup();
$backup_service->enable_logging($log_service);

// set up git service and its logging
$log_service->log('Set up of git service.');
$git_service = new Git();
$git_service->enable_logging($log_service);

// create backup
$backup_service->create_backup($config_service->get_value('zip','backup_src'), $config_service->get_value('zip','backup_dest'), $config_service->get_value('zip','backup_cmd'), '.tar.xz');

// perform git operation
$git_service->perform_git($config_service->get_value('git','repo_path'), $config_service->get_value('git','repo_cmd'));

// get summary of operations and send via mail
$result = $log_service->log_summary();
$mail_service->send_mail($result[0], $result[1]);


?>
