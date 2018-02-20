<?php

class config {

  private $path;
  public $config;

  public function __construct($path_parameter = './') {
    $this->path = $path_parameter;
    $this->config = parse_ini_file($path.'config.ini_default', true);
  }

  public function read_config($path_parameter) {
    $this->path = $path_parameter;
    $this->config = parse_ini_file($path, true);
  }

}
?>
