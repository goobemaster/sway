<?php

/*
  Copyright 2015 Gabor Major

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
*/

namespace Sway\Helpers;

use Sway\Core\Model;

class SqlEngine {
  private $dbHost;
  private $dbPort;
  private $dbUsername;
  private $dbPassword;
  private $dbName;
  private $mysqli;
  private $sqlQuery;

  public function __construct($dbHost, $dbPort, $dbUsername, $dbPassword, $dbName) {
    $this->dbHost = $dbHost;
    $this->dbPort = $dbPort;
    $this->dbUsername = $dbUsername;
    $this->dbPassword = $dbPassword;
    $this->dbName = $dbName;
    $this->mysqli = null;
    $this->sqlQuery = new SqlQuery();
  }

  private function connect() {
    $this->mysqli = new \mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
    return $this->mysqli->connect_error;
  }

  public function status() {
    $status = !$this->connect();
    $this->mysqli->close();
    return $status;
  }

  public function insert(Model $model) {
    if (!$this->connect()) {
      $this->mysqli->close();
      return false;
    }

    if ($this->mysqli->query($this->sqlQuery->insert($model)) === FALSE) {
      $this->mysqli->close();
      return false;
    }

    $this->mysqli->close();
    return true;
  }

  public function select(Model $model, $fields) {
    if (!$this->connect()) {
      $this->mysqli->close();
      return false;
    }

    if (!$result = $this->mysqli->query($this->sqlQuery->select($model, $fields))) return false;

    $r = $result->fetch_all(MYSQLI_ASSOC);
    $result->close();
    $this->mysqli->close();
    if (count($r) == 0) {
      return null;
    } else {
      return $r;
    }

  }

  public function delete(Model $model, $fields) {
    if (!$this->connect()) {
      $this->mysqli->close();
      return false;
    }

    if ($this->mysqli->query($this->sqlQuery->delete($model, $fields))) {
      $affectedRows = $this->mysqli->affected_rows;
      $this->mysqli->close();
      if ($affectedRows == 0) return null;
      return $affectedRows;
    } else {
      $this->mysqli->close();
      return false;
    }
  }

  public function update(Model $model, $whereFields, $fields) {
    if (!$this->connect()) {
      $this->mysqli->close();
      return false;
    }

    if ($this->mysqli->query($this->sqlQuery->update($model, $whereFields, $fields))) {
      $affectedRows = $this->mysqli->affected_rows;
      $this->mysqli->close();
      if ($affectedRows == 0) return null;
      return $affectedRows;
    } else {
      $this->mysqli->close();
      return false;
    }
  }
}