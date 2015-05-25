<?php

namespace Sway\Core;

require_once 'autoload.php';

use Sway\Config\EnvironmentDetails;

class Application {
  const environment = null;
  private $modelManager;
  private $request;
  private $response;

  public function __construct(EnvironmentDetails $environment, $models = array(), $allowedMethods = array('GET', 'POST', 'PUT', 'DELETE')) {
    $this->environment = $environment;
    $this->modelManager = new ModelManager($environment, $models);
    $this->request = new Request();

    $requestMethod = $this->request->method;
    if (in_array($requestMethod, $allowedMethods)) {
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
    } else {
      $this->response = Response::INTERNAL_SERVER_ERROR('Database transaction failed (no results)!');
    }
  }

  // Update
  private function POST() {
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
  }

}