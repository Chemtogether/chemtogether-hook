<?php
class Config {

  private $path;
  public $config;
  private $logging = false;
  private $logger;

  public function __construct($path_parameter = '../config/config.ini') {
    $this->path = $path_parameter;
    $this->config = parse_ini_file($this->path, true);
  }

  public function enable_logging($logging_instance) {
    $this->logging = true;
    $this->logger = $logging_instance;
    $this->log('Logging activated.');
  }

  private function log($message) {
    if ($this->logging) $this->logger->log($message, 'CONFIG_SERVICE');
    else echo "\n[CONFIG_SERVICE] ".$message."\n\n";
  }

  public function get_value($section, $name) {
    try {
      if (!isset($this->config[$section][$name])) throw new Exception('Config value '.$name.' in section '.$section.' not set.');
      return $this->config[$section][$name];
    } catch (Exception $e) {
      $this->error_handling($e);
      exit();
    }
  }

  private function error_handling($error) {
    $this->log('ERROR: '.$error->getMessage());
  }

}

?>
