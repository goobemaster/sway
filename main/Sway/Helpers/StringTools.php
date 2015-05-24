<?php

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
}