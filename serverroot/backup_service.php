<?php

class Backup {

  private $logging = false;
  private $logger;

  public function __construct() {

  }

  public function create_backup($path_from, $path_to, $backup_cmd, $extension = '.backup') {

    // build path for backup file
    $targetfile = $path_to."/".date('Y-m-d_H-i-s').$extension;
    $this->log('Target file for backup is '.$targetfile);
    $this->summarize('Target file for backup is '.$targetfile, 0);

    // check if target already exists and exit if this is the case
    if(file_exists($targetfile)) {
      $this->log('ERROR: Target file already exists! Aborting.');
      $this->summarize('ERROR: Target file already exists!', 1);
      return;
    }

    // build command
    $cmd = $backup_cmd." ".$targetfile." ".$path_from;
    $this->log("EXEC: ".$cmd);
    $this->summarize("\nEXEC: ".$cmd, 0);

    // perform backup
    $output;
    $exit_code;
    exec($cmd, $output, $exit_code);
    $this->log(implode("\n",$output));
    $this->log("EXIT: ".$exit_code);
    $this->summarize(implode("\n",$output), $exit_code);
    $this->summarize("EXIT: ".$exit_code, $exit_code);

    // check how many backups exist and how much space they occupy
    $dir_scan = scandir($path_to);
    $backup_amount = sizeof($dir_scan)-2; //substract '.' and '..'
    $dir_size = shell_exec('du -sh '.$path_to);
    $dir_size = substr($dir_size, 0, strpos($dir_size, "\t")); //truncate output after tab
    $this->log('Amount of backup files: '.$backup_amount);
    $this->log('Amount of space occupied: '.$dir_size);
    $this->summarize('Amount of backup files: '.$backup_amount, 0);
    $this->summarize('Amount of space occupied: '.$dir_size, 0);

    // check if target now does not exist
    if(!file_exists($targetfile)) {
      $this->log('ERROR: Target file not created! Aborting.');
      $this->summarize('ERROR: Target file not created!', 1);
      return;
    }

    $this->log('Target file was created.');
    $this->summarize('Target file was created.', 0);
    $file_size = shell_exec('du -sh '.$targetfile);
    $file_size = substr($file_size, 0, strpos($file_size, "\t")); //truncate output after tab
    $this->log('Target file size: '.$file_size);
    $this->summarize('Target file size: '.$file_size, 0);
    return;
  }


  public function enable_logging($logging_instance) {
    $this->logging = true;
    $this->logger = $logging_instance;
    $this->log('Logging activated.');
  }

  private function log($message) {
    if ($this->logging) $this->logger->log($message, 'ZIPPER_SERVICE');
    else echo "\n[BACKUP_SERVICE] ".$message."\n\n";
  }

  private function summarize($message, $status) {
    if ($this->logging) $this->logger->collect_summary($message, $status, 'backup');
    else echo "\n[BACKUP_SERVICE] ".$message."\n\n";
  }

  private function error_handling($error) {
    $this->log('ERROR: '.$error->getMessage());
  }

}



?>
