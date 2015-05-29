# sway

This documentation will be more complete as the project progresses. Sway is still a very young project, and hence stay away from using it in live projects.

# Overview

Sway in its current form is a bare minimum RESTful Web Service written in PHP, in a 100% object oriented fashion (actually there's one object in the global space supposed to be implemented by the user).

Assume you have a MySQL database, and you need a GUI to do the most basic transactions. All you have to do is:
- Install Postman
- Write model classes
- Add a new environment definition
- Configure your Sway application

And boom, using postman you can select, create, update, delete records in any table given it has a corresponding model class.

# Models

- Models are very simple classes inherited from Sway\\Core\\Model.
- Each model maps to an SQL table, and each property to a field.
- Put models in main/Sway/Models folder.
- The class name must match the SQL table intended to wired to.
- Call the parent constructor, and pass an array or HTTP methods indicating which operations are enabled for the model.

```php
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
```

# Environments

By default Sway is configured to run on 'DEV' environment which is basically your typical localhost.

# Sway Application

- Your application of Sway should be in the root of apache htdocs folder.

index.php

```php
<?php

namespace Sway;

require_once 'autoload.php';

use Sway\Config\Environments;
use Sway\Core\Application;

class MyApplication extends Application {
  public function __construct() {
    // Params: Environment, Models, Allowed Methods
    parent::__construct(Environments::DEV(), ['Book'], ['GET', 'POST', 'PUT', 'DELETE']);
  }
}

$my_application = new MyApplication();
```