<?php

namespace Sway\Core;

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
    http_response_code($this->code);
    if (empty($this->message)) {
      print_r('{ "' . $this->code . '": "' . $this->name . '" }');
    } else {
      print_r('{ "' . $this->code . ' - ' . $this->name . '": "' . $this->message . '" }');
    }
  }
}