<?php

set_include_path(get_include_path() . PATH_SEPARATOR . getcwd());

function rglob($root_path) {
  $paths = glob($root_path . '*', GLOB_MARK | GLOB_ONLYDIR | GLOB_NOSORT);
  $files = glob($root_path . '*', GLOB_ONLYDIR);
  foreach ($paths as $path) {
    $files = array_merge($files, rglob($path, GLOB_ONLYDIR));
  }
  return $files;
}

spl_autoload_register(function($class_name) {
  foreach(rglob('main') as $directory) {
    $filename = $directory . '\\' . $class_name . '.php';
    if(file_exists($filename)) {
      require_once($filename);
      return;
    }
  }
});
