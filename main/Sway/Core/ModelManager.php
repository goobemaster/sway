<?php

namespace Sway\Core;

require_once 'autoload.php';

use Sway\Config\EnvironmentDetails;
use Sway\Helpers\StringTools;

class ModelManager {
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
}