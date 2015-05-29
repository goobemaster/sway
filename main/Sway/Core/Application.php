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

use Sway\Config\ApplicationConfig;

class Application {
  const environment = null;
  private $modelManager;
  private $request;
  private $response;

  public function __construct(ApplicationConfig $applicationConfig) {
    $this->environment = $applicationConfig->environment();
    $this->modelManager = new ModelManager($applicationConfig->environment(), $applicationConfig->models());
    $this->request = new Request();

    $requestMethod = $this->request->method;
    if (in_array($requestMethod, $applicationConfig->allowedMethods())) {
      $this->$requestMethod();
    } else {
      $this->response = Response::METHOD_NOT_ALLOWED('Due to global config');
    }

    $this->response->commit();
  }

  // Fetch
  private function GET() {
    $model = $this->modelManager->getEmptyModel($this->request->path[0]);

    if ($model == null) {
      $this->response = Response::MODEL_NOT_FOUND();
      return;
    }

    if (!in_array('GET', $model->allowedMethods())) {
      $this->response = Response::METHOD_NOT_ALLOWED('Due to model config');
      return;
    }

    if (empty($this->request->query)) {
      $this->response = Response::NO_FIELDS_PROVIDED();
      return;
    }

    if ($json = $model->select($this->request->query)) {
      $this->response = Response::OK('Entity:' . $json);
      // Hack, because NULL == FALSE
      return;
    } else if (is_bool($json)) {
      $this->response = Response::INTERNAL_SERVER_ERROR('Database transaction failed!');
    } else {
      $this->response = Response::NO_RESULTS();
    }
  }

  // Update
  private function POST() {
    $model = $this->modelManager->getEmptyModel($this->request->path[0]);

    if ($model == null) {
      $this->response = Response::MODEL_NOT_FOUND();
      return;
    }

    if (!in_array('POST', $model->allowedMethods())) {
      $this->response = Response::METHOD_NOT_ALLOWED('Due to model config');
      return;
    }

    $records = $model->update($this->request->query, $this->request->form);

    if ($records instanceof ResponseDetails) {
      $this->response = $records;
      return;
    } else if ($records) {
      $this->response = Response::OK($records . ' record(s) updated');
    } else {
      $this->response = Response::INTERNAL_SERVER_ERROR('Database transaction failed!');
    }

  }

  // Create
  private function PUT() {
    $model = $this->modelManager->getEmptyModel($this->request->path[0]);

    if ($model == null) {
      $this->response = Response::MODEL_NOT_FOUND();
      return;
    }

    if (!in_array('PUT', $model->allowedMethods())) {
      $this->response = Response::METHOD_NOT_ALLOWED('Due to model config');
      return;
    }

    if (!$model->populate($this->request->headers)) {
      $this->response = Response::MODEL_CANNOT_BE_POPULATED();
      return;
    }

    if ($model->insert()) {
      $this->response = Response::OK('Created');
    } else {
      $this->response = Response::INTERNAL_SERVER_ERROR('Database transaction failed!');
    }
  }

  // Remove
  private function DELETE() {
    $model = $this->modelManager->getEmptyModel($this->request->path[0]);

    if ($model == null) {
      $this->response = Response::MODEL_NOT_FOUND();
      return;
    }

    if (!in_array('DELETE', $model->allowedMethods())) {
      $this->response = Response::METHOD_NOT_ALLOWED('Due to model config');
      return;
    }

    if (empty($this->request->headers)) {
      $this->response = Response::NO_FIELDS_PROVIDED();
      return;
    }

    if ($records = $model->delete($this->request->headers)) {
      $this->response = Response::OK($records . ' record(s) deleted');
      // Hack, because NULL == FALSE
      return;
    } else if (is_bool($records)) {
      $this->response = Response::INTERNAL_SERVER_ERROR('Database transaction failed!');
    } else {
      $this->response = Response::NO_MATCHING_RECORD();
    }
  }

}