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

namespace Sway\Core;

require_once 'autoload.php';

use Sway\Config\EnvironmentDetails;
use Sway\Helpers\StringTools;

final class ModelManager {
  private $environment;
  private $models;

  public function __construct(EnvironmentDetails $environment, $models = array()) {
    $this->environment = $environment;
    if (!empty($models)) $this->models = $models;
  }

  private function count() {
    return count($this->models);
  }

  public function getEmptyModel($model) {
    $model = StringTools::camelizeMe($model, false);

    if ($this->count() > 0 && in_array($model, $this->models) && $this->isModelExists($model)) {
      $model = 'Sway\\Models\\' . $model;
      return new $model($this->environment);
    } else {
      return null;
    }
  }

  private function isModelExists($model) {
    foreach(rglob('main') as $directory) {
      $filename = $directory . '\\' . $model . '.php';
      if(file_exists($filename)) return true;
    }
    return false;
  }

  public function hasMethodHandler($model, $method) {
    return method_exists($this->getEmptyModel($model), $method);
  }
}