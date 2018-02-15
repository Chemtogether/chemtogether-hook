<?php
// Path to script parent directory
$path = getenv('github_webhook_path');


// the shared secret, used to sign the POST data (using HMAC with SHA1)
// Not documented anywhere, generate random password with high entropy and set as environment variable on server via 'export github_webhook_secret=<password>'
// Remember to also change the webhook secret in Github
$secret = getenv('github_webhook_secret');


// where to log errors and successful requests
define('LOGFILE', $path.'/logs/webhook.log');


// receive POST data for signature calculation
$post_data = file_get_contents('php://input');
$signature = hash_hmac('sha1', $post_data, $secret);

// required data in POST body
$required_data = array(
	'ref' => 'refs/heads/live',
	'repository' => array(
		'name' => 'chemtogether-web',
	),
);

// required data in headers
$required_headers = array(
  'REQUEST_METHOD' => 'POST',
  'HTTP_X_GITHUB_EVENT' => 'push',
  'HTTP_USER_AGENT' => 'GitHub-Hookshot/*',
  'HTTP_X_HUB_SIGNATURE' => 'sha1='.$signature,
);



error_reporting(0);

function log_msg($msg) {
  if(LOGFILE != '') {
    file_put_contents(LOGFILE, $msg . "\n", FILE_APPEND);
  }
}

function match_header($have, $should, $name = 'array') {
  $return = true;

  if(is_array($have)) {
    foreach($should as $key => $value) {
      if(!array_key_exists($key, $have)) {
        log_msg("Missing: $key");
        $return = false;
      }
      else if(is_array($value) && is_array($have[$key])) {
        $return &= array_matches($have[$key], $value);
      }
      else if(is_array($value) || is_array($have[$key])) {
        log_msg("Type mismatch: $key");
        $return = false;
      }
      else if(!fnmatch($value, $have[$key])) {
        log_msg("Failed comparison: $key={$have[$key]} (expected $value)");
        $return = false;
      }
    }
  }
  else {
    log_msg("Not an array: $name");
    $return = false;
  }
  return $return;
}


log_msg("\n\n=== Received request from {$_SERVER['REMOTE_ADDR']} ===");
header("Content-Type: text/plain");

$data = json_decode($post_data, true);

// First do all checks and then report back in order to avoid timing attacks
$headers_ok = array_matches($_SERVER, $required_headers, '$_SERVER');
$data_ok = array_matches($data, $required_data, '$data');

if($headers_ok && $data_ok) {
  log_msg('Request successful.');
  // execute git script
} else {
  log_msg('Request denied.');
  http_response_code(403);
  die("Forbidden\n");
}

?>
