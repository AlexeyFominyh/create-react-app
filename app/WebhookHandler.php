<?php 
$data = [];
$input = file_get_contents('php://input');
// Декодируем строку запроса
parse_str($input, $data); 

// Преобразуем в JSON
$json = json_encode($data);

file_put_contents('webhooks.log', $json . PHP_EOL, FILE_APPEND);

header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);