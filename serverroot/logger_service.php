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
    $this->summary_body[$id] = $this->summary_body[$id]."\n".$message;
  }

  public function log_summary() {
    if ((bool) $this->summary_status) {
      $subject = '[FAILURE] Deployment via Webhook failed due to '.array_search(1,$this->summary_status);
    } else {
      $subject = '[SUCCESS] Deployment via Webhook succeeded';
    }

    $summary = "Deployment script was run at ".date('d.m.Y H:i:s').".\n\n";
    foreach ($this->summary_body as $summary_key => $key_message) {
      if ($this->summary_status[$summary_key]) $text_string = 'FAILURE'; else $text_string = 'SUCCESS';
      $summary = $summary."\n===   ".$summary_key.": ".$text_string."   ===\n\n".$key_message."\n";
    }

    return array($subject, $summary);
  }

}

?>
