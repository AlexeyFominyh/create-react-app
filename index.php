<?php 
require_once 'config.php';
require 'crest.php';
require_once 'api/Bitrix24API.php';
require_once 'api/TodoistAPI.php';
require_once 'db/Integration.php';
 require_once 'app/WebhookHandler.php';
// require_once 'app/BitrixWebhookHandler.php';

$bx24 = new Bitrix($config);
$todoist = new Todoist($config);
$integration = new Integration($config);

$handler = new WebhookHandler(); 
$handler->handle();
$tasksTodoist = $todoist->getTasks();


print_r($tasksTodoist);