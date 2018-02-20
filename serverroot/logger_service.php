<?php
class Logger {

  private $path;

  public function __construct($path_parameter = '../logs/main.log') {
    $this->path = $path_parameter;
  }

  public function log($message, $id = '') {
    if ($id != '') $id = '['.$id.'] ';
    file_put_contents($this->path, $id.$message . "\n", FILE_APPEND);
  }

}

?>
