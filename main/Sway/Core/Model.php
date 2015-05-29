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

  private function filterFields($fields, $filterPrimaryKey = true) {
    $filteredFields = array();

    foreach ($fields as $key => $value) {
      // TODO: In the future, 'real fields' should be of a certain class, so inside the foreach a mere type checking would be suffice
      if ($filterPrimaryKey) $primaryKeyClause = ($this->$key != 'primaryKey'); else $primaryKeyClause = true;
      if (property_exists($this, $key) && !in_array($value, $this->filteredValues) && !in_array($key, $this->filteredKeys) && $primaryKeyClause) {
        $filteredFields[$key] = $value;
      }
    }

    return $filteredFields;
  }

  private function fieldExist($field) {
    $filtered = $this->filterFields(array($field => 'check'), false);
    if (!empty($filtered)) {
      return property_exists($this, $field);
    } else {
      return false;
    }
  }

  // Operations
  public function insert() {
    return $this->sqlEngine->insert($this);
  }

  public function select($fields) {
    // TODO: Check fields beforehand, and return FIELD_DOES_NOT_EXIST similarly to update()
    if ($dbFields = $this->sqlEngine->select($this, $this->filterFields($fields, false))) {
      return json_encode($dbFields, JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);
    } else {
      return $dbFields;
    }
  }

  public function delete($fields) {
    return $this->sqlEngine->delete($this, $this->filterFields($fields));
  }

  public function update($whereFields, $fields) {
    foreach ($fields as $key => $value) {
      if (!$this->fieldExist($key)) return Response::FIELD_DOES_NOT_EXIST($key);
    }

    if ($records = $this->select($whereFields)) {
      return $this->sqlEngine->update($this, $whereFields, $fields);
    } else if (is_bool($records)) {
      return Response::NO_MATCHING_RECORD();
    } else {
      return Response::INTERNAL_SERVER_ERROR('Database transaction failed!');
    }
  }
}