<?php 
class WebhookHandler {

  public function handle() {

    $input = file_get_contents('php://input');

    parse_str($input, $data);
    
    $json = json_encode($data);
  
    $this->log($json);
  }

  public function log($data) {

   file_put_contents('logs/webhooks.log', $data . PHP_EOL, FILE_APPEND);

  }
}
