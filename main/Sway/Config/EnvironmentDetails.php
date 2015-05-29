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

namespace Sway\Config;

final class EnvironmentDetails {
  private $dbEngine;
  private $dbHost;
  private $dbPort;
  private $dbUsername;
  private $dbPassword;
  private $dbName;
  private $phpErrorReporting;

  public function __construct($dbEngine = "mysql", $dbHost = "127.0.0.1", $dbPort = "3306", $dbUsername = "root", $dbPassword = "", $dbName = "sway", $phpErrorReporting) {
    $this->dbEngine = $dbEngine;
    $this->dbHost = $dbHost;
    $this->dbPort = $dbPort;
    $this->dbUsername = $dbUsername;
    $this->dbPassword = $dbPassword;
    $this->dbName = $dbName;
    $this->phpErrorReporting = $phpErrorReporting; error_reporting($phpErrorReporting);
  }

  public function dbEngine() {
    return $this->dbEngine;
  }

  public function dbHost() {
    return $this->dbHost;
  }

  public function dbPort() {
    return $this->dbPort;
  }

  public function dbUsername() {
    return $this->dbUsername;
  }

  public function dbPassword() {
    return $this->dbPassword;
  }

  public function dbName() {
    return $this->dbName;
  }

  public function phpErrorReporting() {
    return $this->phpErrorReporting;
  }
}