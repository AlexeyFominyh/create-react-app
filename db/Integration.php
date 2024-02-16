<?php 

class Integration
{

  private $db = null;

  public function __construct($config) {
    $db = new PDO ('mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'], $config['db']['user'], $config['db']['password']);  
    $db->query('SET character_set_connection = '. $config['db']['charset'] . ';');
    $db->query('SET character_set_client = '. $config['db']['charset'] . ';');
    $db->query('SET character_set_results = '. $config['db']['charset'] . ';');
    
    $this->db = $db;
  }
  public function createProject($data) {

    $sql = 'INSERT IGNORE INTO projects(`created`, `name`, `trello_id`, `todoist_id`) VALUES (:created, :name, :trello_id, :todoist_id)';
    $res = $this->db->prepare($sql);
    $res->execute([
      'created' => date('Y-m-d H:i:s'),
      'name' => $data['name'],
      'trello_id' => $data['board_id'],
      'todoist_id' => $data['label_id'],
    ]);

    return $this->db->lastInsertId();
  } 
  public function createTask($idTodoist, $idBitrix, $titleTodoist, $titleBitrix) {

    // $checklist = 0;
    // if($checklistId != null){
    //   $checklist = 1;
    // }

    $res = $this->db->prepare("INSERT IGNORE INTO tasks (`id_todoist`, `id_bitrix`, `title_todoist`, `title_bitrix`)
    VALUES (:id_todoist, :id_bitrix, :title_todoist, :title_bitrix)");
    $res->execute([
      ':id_todoist' => $idTodoist,
      ':id_bitrix' => $idBitrix,
      ':title_todoist' => $titleTodoist,
      ':title_bitrix' => $titleBitrix,
    ]);


  }

  public function updateProject($id, $name)
  {
    $res = $this->db->prepare("UPDATE projects SET `name` = :name WHERE id = :id");
    $res->execute([
      ':name' => $name,
      ':id' => $id
    ]);
  }
 
  public function updateTask($id, $query) {

    $res = $this->db->prepare("UPDATE tasks SET `name` = :name, `due_date` = :due_date WHERE id = :id");
    $res->execute([
      ':name' => $query['name'],
      ':due_date' => date('Y-m-d H:i:s', strtotime($query['due_date'])),
      ':id' => $id,
    ]);


  }
  public function updTask($id, $query) {

    $res = $this->db->prepare("UPDATE tasks SET `name` = :name, WHERE id = :id");
    $res->execute([
      ':name' => $query['name'],
      ':id' => $id,
    ]);


  }

  public function getProject($id) {
      $res = $this->db->query( "SELECT * FROM projects WHERE trello_id = '{$id}'");

      return $res->fetch();
  }
  
  public function getTask($id) {
    $res = $this->db->query("SELECT * FROM tasks WHERE id_bitrix = '{$id}'");
    
    return $res->fetch();
  }

  public function getIncompleteTasks(){
    $res = $this->db->query("SELECT * FROM tasks WHERE todoist_status = 'incomplete'");

    return $res->fetchAll();
  }

  public function setTaskComplete($id, $date) {
    $date = date('Y-m-d H:i:s', $date);

    $res = $this->db->prepare("UPDATE tasks SET trello_status = :trello_status, trello_updated = :trello_updated, todoist_status = :todoist_status, todoist_updated = :todoist_updated WHERE id = :id");
    $res->execute([
        ':trello_status' => 'complete',
        ':trello_updated' => $date,
        ':todoist_status' => 'complete',
        ':todoist_updated' => $date,
        ':id' => $id,
   ]);
  }


}