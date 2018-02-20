<?php
date_default_timezone_set('Europe/Zurich');

require('./config_service.php');
require('./logger_service.php');

// initiate an instance for logging
$logger = new Logger();

// log current call of main.php with time stamp
$logger->log("\n\n=== main.php called at ".date('d.m.Y h:i:s')." ===");


// read config and set up its logging
$config = new Config();
$config->enable_logging($logger);



?>
