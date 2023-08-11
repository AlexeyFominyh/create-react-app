<?php
require_once './WebhookHandler.php'; 

class TodoistWebhookHandler extends WebhookHandler {

public function handle($data) {
  
  if($data['event_name'] == 'item:added') {
    // ...обработка добавления задачи  

  } else if ($data['event_name'] == 'item:deleted') {
   // ...обработка удаления задачи   
  }

  parent::log($data);
}

}