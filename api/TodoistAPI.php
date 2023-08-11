<?php 

class Todoist
{

  private $apiData = [
    'token' => '',
  ];
  public function __construct($config)
  {
    $this->apiData = $config['todoist'];
  }

  public function get($url) 
{    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $this->apiData['token']]);

    $output = curl_exec($ch);
    curl_close($ch);

    //обработка ошибок

    return $output;
  }

  public function post($url, $query) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $this->apiData['token']]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    $output = curl_exec($ch);

    // echo "POST Request URL: " . $url . PHP_EOL;
    // echo "POST Request Data: " . json_encode($query) . PHP_EOL;
    // echo "POST Response: " . $output . PHP_EOL;

    curl_close($ch);
    //обработка ошибок

    return $output;

  }

  public function getTasks() {
    $output = $this->get('https://api.todoist.com/rest/v2/tasks');
    $output = json_decode($output);

    return (array) $output;
  }
  
  public function getTaskCompleteDate($tasks, $taskId){
    foreach ($tasks AS $task){
      if($task->id == $taskId){
        return ['status' => 'incomplete'];
      }
    }
    return ['status' => 'complete', 'date' => time()];
  }

  public function addTask($query) {
    $output = $this->post('https://api.todoist.com/rest/v2/tasks', json_encode($query, JSON_NUMERIC_CHECK));
    $output = json_decode($output);

    return ( array) $output;
  }

  public function addLabel($query){
    $output = $this->post('https://api.todoist.com/rest/v2/labels', json_encode($query));
    $output = json_decode($output);
    return (array) $output;
  }

  public function updateTask($id, $query) {
    $this->post('https://api.todoist.com/rest/v2/tasks/' . $id, json_encode($query));
  }
    
  public function updateLabel($id, $query) 
    {
      $this->post('https://api.todoist.com/rest/v2/labels/' . $id, json_encode($query));
    }  
}