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

require_once 'autoload.php';

final class ApplicationConfig {
  private $environmentDetails;
  private $models;
  private $allowedMethods;

  public function __construct() {
    $this->environmentDetails = Environments::DEV();
    $this->models = array();
    $this->allowedMethods = array('GET', 'POST', 'PUT', 'DELETE');
  }

  public function setEnvironment(EnvironmentDetails $environmentDetails) {
    $this->environmentDetails = $environmentDetails;
    return $this;
  }

  public function environment() {
    return $this->environmentDetails;
  }

  public function setModels(array $models) {
    $this->models = $models;
    return $this;
  }

  public function models() {
    return $this->models;
  }

  public function setAllowedMethods(array $allowedMethods) {
    $this->allowedMethods = $allowedMethods;
    return $this;
  }

  public function allowedMethods() {
    return $this->allowedMethods;
  }
}