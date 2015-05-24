<?php

namespace Sway\Core;

class Model {
  private $sqlEngine;

  public function __construct(EnvironmentDetails $environment) {
    $this->sqlEngine = new SqlEngine($environment->dbHost(), $environment->dbPort(), $environment->dbUsername(), $environment->dbPassword(), $environment->dbName());
  }

  public function populate($fields) {
    if (empty($fields)) return false;

    foreach ($fields as $key => $value) {
      if (property_exists($this, $key) && $this->$key != 'primaryKey') {
        $this->{$key} = $value;
      }
    }

    return true;
  }

  public function insert() {
    return $this->sqlEngine->insert($this);
  }

  public function toJSON() {
    $json = "{\n";
    foreach($this as $key => $value) { $json .= '"' . $key . '": ' . '"' . $value . "\",\n"; }
    return rtrim($json, ",\n") . "\n}";
  }
}