<?php

namespace Sway\Config;

require_once 'autoload.php';

use Sway\Core\EnvironmentDetails;

// Root problem: Lack of native enums in PHP
// Problem 1. - Expression is not allowed as constant value
// Problem 2. - Only scalar values allowed when defining constants via define()
// Problem 3. - While SPL is enabled by default since PHP 5.0, SplEnum (a subset) is not!
// Problem 4. - Properties cannot be declared as final.

// My Workaround

final class Environments {
  public static function DEV() {
    return new EnvironmentDetails("mysql", "127.0.0.1", 3306, "root", "", "sway", 0);
  }
}