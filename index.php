<?php

namespace Sway;

require_once 'autoload.php';

use Sway\Config\Environments;
use Sway\Core\Application;

class MyApplication extends Application {
  public function __construct() {
    // Params: Environment, Models, Allowed Methods
    parent::__construct(Environments::DEV(), ['Book'], ['GET', 'POST', 'PUT', 'DELETE']);
  }
}

$my_application = new MyApplication();