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