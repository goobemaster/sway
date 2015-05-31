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

class Request {
  private $method;
  private $path;
  private $form;
  private $query;
  private $headers;
  private $basicAuthUsername;
  private $basicAuthPassword;

  public function __construct() {
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->path = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
    $this->form = $_POST;
    $this->query = $_GET;
    $this->headers = getallheaders();
    $this->basicAuthUsername = $_SERVER['PHP_AUTH_USER'];
    $this->basicAuthPassword = $_SERVER['PHP_AUTH_PW'];
  }

  public function method() {
    return $this->method;
  }

  public function path() {
    return $this->path;
  }

  public function form() {
    return $this->form;
  }

  public function query() {
    return $this->query;
  }

  public function headers() {
    return $this->headers;
  }

  public function basicAuthUsername() {
    return ($this->basicAuthUsername == null ? '' : $this->basicAuthUsername);
  }

  public function basicAuthPassword() {
    return ($this->basicAuthPassword == null ? '' : $this->basicAuthPassword);
  }
}