<?php

class Zip {

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
    if ($this->logging) $this->logger->log($message, 'ZIPPER_SERVICE');
    else echo "\n[ZIPPER_SERVICE] ".$message."\n\n";
  }

  private function error_handling($error) {
    $this->log('ERROR: '.$error->getMessage());
  }

}



 ?>
