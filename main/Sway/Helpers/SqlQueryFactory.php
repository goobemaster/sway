<?php

namespace Sway\Helpers;

use Sway\Core\Model;

class SqlQueryFactory {
  function insert(Model $model) {
    $table = StringTools::snakeCaseMe(explode('\\', get_class($model))[2]);
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

  function select(Model $model, $fields) {
    $table = StringTools::snakeCaseMe(explode('\\', get_class($model))[2]);
    $where = array();

    foreach ($fields as $key => $value) {
      $fields[$key] = '"' . $value . '"';
      array_push($where, ' ' . $key . '="' . $value . '"');
    }

    return 'SELECT * FROM ' . $table . ' WHERE ' . implode(' AND', $where) . ';';
  }
}