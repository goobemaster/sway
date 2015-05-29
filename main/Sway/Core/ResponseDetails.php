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

use Sway\Helpers\StringTools;

final class ResponseDetails {
  private $code;
  private $name;
  private $message;

  public function __construct($code, $name, $message) {
    $this->code = $code;
    $this->name = $name;
    $this->message = $message;
  }

  public function code() {
    return $this->code;
  }

  public function name() {
    return $this->name;
  }

  public function commit() {
    if (empty($this->message)) {
      print_r('{ "' . $this->code . '": "' . $this->name . '" }');
    } else if (StringTools::doesStartsWith($this->message, 'Entity:')) {
      print_r(substr($this->message, 7));
    } else {
      print_r('{ "' . $this->code . ' - ' . $this->name . '": "' . $this->message . '" }');
    }
    header('HTTP/1.1 ' . $this->code . ' ' . $this->name);
  }
}