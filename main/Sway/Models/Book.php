<?php

namespace Sway\Models;

require_once 'autoload.php';

use Sway\Core\Model;
use Sway\Config\EnvironmentDetails;

class Book extends Model {
  public $id = 'primaryKey';
  public $title = '';
  public $author = '';
  public $published = '';
  public $edition = '';

  public function __construct(EnvironmentDetails $environment) {
    parent::__construct($environment, ['GET', 'PUT', 'DELETE', 'POST']);
  }
}