<?php

namespace Sway\Core;

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
}