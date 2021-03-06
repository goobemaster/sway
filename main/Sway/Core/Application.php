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
use Sway\Helpers\SqlEngine;

class Application {
  const config = null;
  private $modelManager;
  private $request;
  private $response;

  public function __construct(ApplicationConfig $applicationConfig) {
    $this->config = $applicationConfig;
    $this->modelManager = new ModelManager($applicationConfig->environment(), $applicationConfig->models());
    $this->request = new Request();
    $requestMethod = $this->request->method();
    $requestModel = $this->request->path()[0];

    if ($this->config->basicAuthEnabled()) {
      if (!($this->request->basicAuthUsername() == $this->config->environment()->basicAuthUsername() && $this->request->basicAuthPassword() == $this->config->environment()->basicAuthPassword())) {
        $this->response = Response::UNAUTHORIZED();
        $this->response->commit();
        return;
      }
    }

    if ($requestModel == 'status' && $requestMethod == 'GET') {
      $this->status();
      $this->response->commit();
      return;
    }

    $this->executeMethodHandler($requestMethod, $requestModel);
    $this->response->commit();
  }

  private final function executeMethodHandler($requestMethod, $requestModel) {
    if (in_array($requestMethod, $this->config->allowedMethods())) {
      if ($this->modelManager->hasMethodHandler($requestModel, $requestMethod)) {
        $model = $this->modelManager->getEmptyModel($requestModel);
        if (!in_array($requestMethod, $model->allowedMethods())) {
          $this->response = Response::METHOD_NOT_ALLOWED('Due to model config');
        } else {
          $this->response = $model->$requestMethod($this->request);
        }
      } else {
        if (method_exists($this, $requestMethod)) {
          $this->$requestMethod();
        } else {
          $this->response = Response::MODEL_CANNOT_HANDLE_METHOD();
        }
      }
    } else {
      $this->response = Response::METHOD_NOT_ALLOWED('Due to global config');
    }
  }

  private final function getModel() {
    $model = $this->modelManager->getEmptyModel($this->request->path()[0]);

    if ($model == null) {
      return Response::MODEL_NOT_FOUND();
    }

    if (!in_array('GET', $model->allowedMethods())) {
      return Response::METHOD_NOT_ALLOWED('Due to model config');
    }

    return $model;
  }

  private function status() {
    $modelCount = $this->modelManager->count();

    if ($this->config->environment()->dbEngine() == 'mysql') {
      $dbConnection = new SqlEngine($this->config->environment()->dbHost(), $this->config->environment()->dbPort(), $this->config->environment()->dbUsername(), $this->config->environment()->dbPassword(), $this->config->environment()->dbName());
      $dbConnectionStatus = $dbConnection->status();
    } else {
      $dbConnectionStatus = null;
    }

    $json = "{\"modelCount\": " . $modelCount . ", \"dbConnection\": " . ($dbConnectionStatus ? 'true' : 'false')  . '}';

    if ($modelCount > 0 && $dbConnectionStatus == true) {
      $this->response = Response::OK('Entity:' . $json);
    } else {
      $this->response = Response::SERVICE_UNAVAILABLE('Entity:' . $json);
    }
  }

  // Fetch
  private function GET() {
    $model = $this->getModel();

    if (is_a($model, 'Sway\\Core\\ResponseDetails')) {
      $this->response = $model;
      return;
    }

    if (empty($this->request->query())) {
      $this->response = Response::NO_FIELDS_PROVIDED();
      return;
    }

    if ($json = $model->select($this->request->query())) {
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
    $model = $this->getModel();

    if (is_a($model, 'Sway\\Core\\ResponseDetails')) {
      $this->response = $model;
      return;
    }

    $records = $model->update($this->request->query(), $this->request->form());

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
    $model = $this->getModel();

    if (is_a($model, 'Sway\\Core\\ResponseDetails')) {
      $this->response = $model;
      return;
    }

    if (!$model->populate($this->request->headers())) {
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
    $model = $this->getModel();

    if (is_a($model, 'Sway\\Core\\ResponseDetails')) {
      $this->response = $model;
      return;
    }

    if (empty($this->request->headers())) {
      $this->response = Response::NO_FIELDS_PROVIDED();
      return;
    }

    if ($records = $model->delete($this->request->headers())) {
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