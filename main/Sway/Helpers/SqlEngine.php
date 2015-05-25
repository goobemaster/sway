<?php

namespace Sway\Helpers;

use Sway\Core\Model;

class SqlEngine {
  private $dbHost;
  private $dbPort;
  private $dbUsername;
  private $dbPassword;
  private $dbName;
  private $sqlQueryFactory;

  public function __construct($dbHost, $dbPort, $dbUsername, $dbPassword, $dbName) {
    $this->dbHost = $dbHost;
    $this->dbPort = $dbPort;
    $this->dbUsername = $dbUsername;
    $this->dbPassword = $dbPassword;
    $this->dbName = $dbName;
    $this->sqlQueryFactory = new SqlQueryFactory();
  }

  public function insert(Model $model) {
    $mysqli = new \mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
    if ($mysqli->connect_error) {
      $mysqli->close();
      return false;
    }

    if ($mysqli->query($this->sqlQueryFactory->insert($model)) === FALSE) {
      $mysqli->close();
      return false;
    }

    $mysqli->close();
    return true;
  }

  public function select(Model $model, $fields) {
    $mysqli = new \mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
    if ($mysqli->connect_error) {
      $mysqli->close();
      return false;
    }

    if ($result = $mysqli->query($this->sqlQueryFactory->select($model, $fields))) {
      $r = $result->fetch_all(MYSQLI_ASSOC);
      $result->close();
      $mysqli->close();
      return $r;
    }

    $mysqli->close();
    return false;
  }
}