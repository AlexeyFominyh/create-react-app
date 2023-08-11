<?php 


class Bitrix
{
  private $config;

  public function __construct($config) {
    $this->config = $config['bitrix24']; 
  }

  public function call($method, $params = []) {
    return CRest::call($method, $params);
  }

  public function callBatch($commands) {
    return CRest::callBatch($commands);
  }

  public function getDeals() {
    return $this->call('crm.deal.list');
  }

  public function getTasks() {
    return $this->call('tasks.task.list'); 
  }

  // другие методы для вызова API  


}