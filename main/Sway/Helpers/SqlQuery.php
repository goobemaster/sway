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

use Sway\Core\Model;

class SqlQuery {
  // Helpers
  private function tableName(Model $model) {
    return StringTools::snakeCaseMe(explode('\\', get_class($model))[2]);
  }

  private function constructWhere($fields) {
    $where = array();
    foreach ($fields as $key => $value) {
      $fields[$key] = '"' . $value . '"';
      array_push($where, ' ' . $key . '="' . $value . '"');
    }
    return $where;
  }

  // Operations
  public function insert(Model $model) {
    $table = $this->tableName($model);
    $columns = array();
    $values = array();

    foreach ($model as $key => $value) {
      if ($value != 'primaryKey') {
        array_push($columns, $key);
        array_push($values, '"' . $value . '"');
      }
    }

    return 'INSERT INTO ' . $table . ' (' . implode(',', $columns) . ') VALUES (' . implode(',', $values) . ');';
  }

  public function select(Model $model, $fields) {
    $table = $this->tableName($model);
    $where = $this->constructWhere($fields);

    if (count($where) > 1) {
      return 'SELECT * FROM ' . $table . ' WHERE ' . implode(' AND', $where) . ';';
    } else {
      return 'SELECT * FROM ' . $table . ' WHERE ' . $where[0] . ';';
    }
  }

  public function delete(Model $model, $fields) {
    $table = $this->tableName($model);
    $where = $this->constructWhere($fields);

    if (count($where) > 1) {
      return 'DELETE FROM ' . $table . ' WHERE ' . implode(' AND', $where) . ';';
    } else {
      return 'DELETE FROM ' . $table . ' WHERE ' . $where[0] . ';';
    }
  }

  public function update(Model $model, $whereFields, $fields) {
    $table = $this->tableName($model);
    $where = $this->constructWhere($whereFields);
    $set = $this->constructWhere($fields);

    if (count($where) > 1) {
      $where = implode(' AND', $where) . ';';
    } else {
      $where = $where[0];
    }

    if (count($set) > 1) {
      $set = implode(',', $set) . ';';
    } else {
      $set = $set[0];
    }

    return 'UPDATE ' . $table . ' SET ' . $set . ' WHERE ' . $where . ';';
  }
}