<?php

namespace Sway\Core;

class Request {
  public $method;
  public $path;
  public $form;
  public $query;
  public $headers;

  public function __construct() {
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->path = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
    $this->form = $_POST;
    $this->query = $_GET;
    $this->headers = getallheaders();
  }
}