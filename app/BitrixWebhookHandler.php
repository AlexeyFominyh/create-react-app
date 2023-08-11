
<?php 
require_once './WebhookHandler.php'; 

class BitrixWebhookHandler extends WebhookHandler {

public function handle($data) {

  if($data['event'] == 'ONLEADADD') {
    // ...обработка добавления лида
    
  } else if ($data['event'] == 'ONCONTACTADD') {
    // ...обработка добавления контакта  
  }

  parent::log($data); // Логируем данные

}

}