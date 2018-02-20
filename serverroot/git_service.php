<?php

class Git {

  private $path;
  private $logging = false;
  private $logger;

  public function __construct() {

  }

  public function enable_logging($logging_instance) {
    $this->logging = true;
    $this->logger = $logging_instance;
    $this->log('Logging activated.');
  }

  private function log($message) {
    if ($this->logging) $this->logger->log($message, 'GIT_SERVICE');
    else echo "\n[GIT_SERVICE] ".$message."\n\n";
  }

  private function error_handling($error) {
    $this->log('ERROR: '.$error->getMessage());
  }

}



 ?>
