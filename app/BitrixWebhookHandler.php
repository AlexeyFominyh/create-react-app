<?php
require_once '../config.php';
require '../crest.php';
require_once '../api/Bitrix24API.php';
require_once '../api/TodoistAPI.php';
require_once '../db/Integration.php';


$bx24 = new Bitrix($config);
$todoist = new Todoist($config);
$integration = new Integration($config);



// Получение содержимого переменной $_POST
$postData = $_POST;


// Параметры подключения к базе данных
$dsn = 'mysql:host=localhost;dbname=bitrix24-todoist;charset=utf8mb4';
$username = 'root';
$password = '23-*l-B(gI3EoxS/';

try {
    // Подключение к базе данных
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // При создании задачи в Bitrix24
    if ($_POST['event'] === 'ONTASKADD') {
      $taskId = $_POST['data']['FIELDS_AFTER']['ID'];
      $taskTitle = $bx24->call('tasks.task.get', [
          'taskId' => $taskId,
      ])['result']['task']['title'];

        // Проверяем, есть ли запись в базе данных
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id_bitrix = ?");
        $stmt->execute([$taskId]);
        $row = $stmt->fetch();

        if (!$row) {

          
            // Создаем задачу в Todoist
            $tasktodoist = $todoist->addTask(['content' => $taskTitle]);

            // Добавляем запись в базу данных
            $stmt = $pdo->prepare("INSERT IGNORE INTO tasks (id_bitrix, title_bitrix, id_todoist, title_todoist) VALUES (?, ?, ?, ?)");
            $stmt->execute([$taskId, $taskTitle, $tasktodoist['id'], $tasktodoist['content']]);
        // } else {
        //     // Обновляем запись в БД с добавлением ID Bitrix24
        //     $stmt = $pdo->prepare("UPDATE tasks SET id_bitrix = ? WHERE title_bitrix = ?");
        //     $stmt->execute([$taskId, $taskTitle]);
        // }
  }}
  if ($_POST['event'] === 'ONTASKDELETE') {


    $taskId = $_POST['data']['FIELDS_BEFORE']['ID'];



    // Поиск существующей связи и удаление записи 
  $stmt = $pdo->prepare("DELETE FROM tasks WHERE id_bitrix = ?");
  $stmt->execute([$taskId]);
  }
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}

if ($data['event_name'] === 'item:completed'){
  file_put_contents("log.txt", 'Выполнена задача Todoist: '. $data['event_data']['content'], FILE_APPEND);
}
// Преобразование массива в строку для логирования
$logMessage = date('Y-m-d H:i:s') . " - Received POST data:\n" . print_r($postData, true) . "\n\n";

// Путь к лог-файлу
$logFilePath = 'bitrix_webhook_log.txt';

// Добавление данных в лог-файл
error_log($logMessage, 3, $logFilePath);

?>
