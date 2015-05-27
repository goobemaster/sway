<?php

namespace Sway\Core;

use Sway\Config\EnvironmentDetails;
use Sway\Helpers\SqlEngine;

class Model {
  private $sqlEngine;
  private $allowedMethods;
  private $filteredKeys = array('sqlEngine', 'allowedMethods', 'filteredKeys', 'filteredValues');
  private $filteredValues = array(''); // empty for now

  public function __construct(EnvironmentDetails $environment, $allowedMethods = array('GET', 'POST', 'PUT', 'DELETE')) {
    $this->sqlEngine = new SqlEngine($environment->dbHost(), $environment->dbPort(), $environment->dbUsername(), $environment->dbPassword(), $environment->dbName());
    $this->allowedMethods = $allowedMethods;
  }

  // Helpers
  public function allowedMethods() {
    return $this->allowedMethods;
  }

  public function populate($fields) {
    if (empty($fields)) return false;

    foreach ($this->filterFields($fields) as $key => $value) $this->{$key} = $value;

    return true;
  }

  private function filterFields($fields) {
    $filteredFields = array();

    foreach ($fields as $key => $value) {
      // TODO: In the future, 'real fields' should be of a certain class, so inside the foreach a mere type checking would be suffice
      if (property_exists($this, $key) && !in_array($value, $this->filteredValues) && !in_array($key, $this->filteredKeys) && $this->$key != 'primaryKey') {
        $filteredFields[$key] = $value;
      }
    }

    return $filteredFields;
  }

  // Operations
  public function insert() {
    return $this->sqlEngine->insert($this);
  }

  public function select($fields) {
    if ($dbFields = $this->sqlEngine->select($this, $this->filterFields($fields))) {
      return json_encode($dbFields, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
    } else {
      return $dbFields;
    }
  }

  public function delete($fields) {
    return $this->sqlEngine->delete($this, $this->filterFields($fields));
  }
}