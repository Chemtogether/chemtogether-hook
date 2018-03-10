<?php

class Git {

  private $logging = false;
  private $logger;

  public function __construct() {

  }


  public function perform_git($path, $cmd) {

    $this->log('Git path: '.$path);
    $this->summarize('Git path: '.$path, 0);

    // check if directory exists and exit if this is not the case
    if(!file_exists($path)) {
      $this->log('ERROR: Target directory does not exist! Aborting.');
      $this->summarize('ERROR: Target directory does not exist!', 1);
      return;
    }

    // build command for git operation
    $cmd_pull = "cd ".$path." && ".$cmd." pull";
    $this->log("EXEC: ".$cmd_pull);
    $this->summarize("\nEXEC: ".$cmd_pull, 0);

    // perform git operation
    $output_pull;
    $exit_code_pull;
    exec($cmd_pull, $output_pull, $exit_code_pull);
    $this->log(implode("\n",$output_pull));
    $this->log("EXIT: ".$exit_code_pull);
    $this->summarize(implode("\n",$output_pull), $exit_code_pull);
    $this->summarize("EXIT: ".$exit_code_pull, $exit_code_pull);

    // get current git status
    $cmd_status = "cd ".$path." && ".$cmd." status -s";
    $this->log("EXEC: ".$cmd_status);
    $this->summarize("\nEXEC: ".$cmd_status, 0);
    $output_status;
    $exit_code_status;
    exec($cmd_status, $output_status, $exit_code_status);
    $this->log(implode("\n",$output_status));
    $this->log("EXIT: ".$exit_code_status);
    $this->summarize(implode("\n",$output_status), $exit_code_status);
    $this->summarize("EXIT: ".$exit_code_status, !empty($output_status));

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

  private function summarize($message, $status) {
    if ($this->logging) $this->logger->collect_summary($message, $status, 'git');
    else echo "\n[GIT_SERVICE] ".$message."\n\n";
  }

  private function error_handling($error) {
    $this->log('ERROR: '.$error->getMessage());
  }

}



?>
