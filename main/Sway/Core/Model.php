<?php

namespace Sway\Core;

use Sway\Config\EnvironmentDetails;
use Sway\Helpers\SqlEngine;

class Model {
  private $sqlEngine;
  private $allowedMethods;

  public function __construct(EnvironmentDetails $environment, $allowedMethods = array('GET', 'POST', 'PUT', 'DELETE')) {
    $this->sqlEngine = new SqlEngine($environment->dbHost(), $environment->dbPort(), $environment->dbUsername(), $environment->dbPassword(), $environment->dbName());
    $this->allowedMethods = $allowedMethods;
  }

  public function allowedMethods() {
    return $this->allowedMethods;
  }

  public function populate($fields) {
    if (empty($fields)) return false;

    foreach ($fields as $key => $value) {
      $filteredKeys = array('sqlEngine', 'allowedMethods');
      $filteredValues = array();

      if (property_exists($this, $key) && !in_array($value, $filteredValues) && !in_array($key, $filteredKeys) && $this->$key != 'primaryKey') {
        $this->{$key} = $value;
      }
    }

    return true;
  }

  public function insert() {
    return $this->sqlEngine->insert($this);
  }

  public function select($fields) {
    $dbFields = $this->sqlEngine->select($this, $fields);
    if (!empty($dbFields)) {
      return json_encode($dbFields, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
    } else {
      return false;
    }
  }

  public function toJSON() {
    $json = "{\n";
    foreach($this as $key => $value) { $json .= '"' . $key . '": ' . '"' . $value . "\",\n"; }
    return rtrim($json, ",\n") . "\n}";
  }
}