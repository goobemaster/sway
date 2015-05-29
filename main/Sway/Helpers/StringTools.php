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

class StringTools {
  private $subject;

  public function __construct($subject = '') {
    $this->subject = $subject;
  }

  public function camelize($lcfirst = true) {
    $value = preg_replace("/([_-\s]?([a-z0-9]+))/e", "ucwords('\\2')", $this->subject);
    return ($lcfirst ? strtolower($value[0]) : strtoupper($value[0])) . substr($value, 1);
  }

  public static function camelizeMe($subject, $lcfirst = true) {
    $stringTool = new StringTools($subject);
    return $stringTool->camelize($lcfirst);
  }

  public function snakeCase() {
    if (preg_match('/[A-Z]/', $this->subject) === 0) return $this->subject;
    $pattern = '/([a-z])([A-Z])/';
    $r = strtolower(preg_replace_callback($pattern, function ($a) {
      return $a[1] . '_' . strtolower($a[2]);
    }, $this->subject));
    return $r;
  }

  public static function snakeCaseMe($subject) {
    $stringTool = new StringTools($subject);
    return $stringTool->snakeCase();
  }

  public function startsWith($needle) {
    return substr($this->subject, 0, strlen($needle)) === $needle;
  }

  public static function doesStartsWith($subject, $needle) {
    $stringTool = new StringTools($subject);
    return $stringTool->startsWith($needle);
  }
}