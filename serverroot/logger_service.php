<?php
class Logger {

  private $path;
  private $mailing = false;
  private $mail;

  public $summary_body = '';
  public $summary_subject = '';
  public $summary_status = array();

  public function __construct($path_parameter = '../logs/main.log') {
    $this->path = $path_parameter;
  }

  public function log($message, $id = '') {
    if ($id != '') $id = '['.$id.'] ';
    file_put_contents($this->path, $id.$message . "\n", FILE_APPEND);
  }

  public function enable_mail($mail_instance) {
    $this->mailing = true;
    $this->mail = $mailing_instance;
    $this->log('Mail functionality activated.');
  }



  public function collect_summary($message, $status, $id) {
    $this->summary_status[$id] = $status;
    $this->$summary_body[$id] = $message;
  }


  public function log_summary() {
    if (max($this->summary_status) > 0) {
      $this->summary_subject = '[FAILURE] Deployment via Webhook failed due to '.array_search(1,$this->summary_status);
    } else {
      $this->summary_subject = '[SUCCESS] Deployment via Webhook succeded';
    }

  }

}

?>
