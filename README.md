# sway

This project is defunct. It is "feature complete", however it will never receive any more updates and improvements. Use it at your own risk, and preferably not in live projects.

# Overview

Sway in its current form is a bare minimum RESTful Web Service written in PHP, in a 100% object oriented fashion (actually there's one object in the global space supposed to be implemented by the user).

Assume you have a MySQL database, and you need a GUI to do the most basic transactions. All you have to do is:
- Install Postman
- Write model classes
- Add a new environment definition
- Configure your Sway application

And boom, using postman you can select, create, update, delete records in any table given it has a corresponding model class.

Of course there's much more to Sway than the trivial use case above. You'll likely to going to build upon it to implement your own web service / backend / whatever.

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

## Custom Method Handlers

So you don't like the stock GET, POST etc. handlers, or you would like to implement lets say PATCH for a given model?

The core application first checks the requested model for the presence of method handlers, and calls them. Don't forget to enable the method on both global and model level.

The request is exposed in these hook methods, and the only criteria to write a valid handler is to return with a ResponseDetails object. For the sake of readability, Response class has a bunch of static methods.

```php
class Book extends Model {
  // ...
  public function PATCH(Request $request) {
    return Response::NOT_IMPLEMENTED();
  }
}
```

# Environments

By default Sway is configured to run on 'DEV' environment which is basically your typical localhost.

# Sway Application

- Your application of Sway should be in the root of apache htdocs folder.

Simply implement your own application class

```php
class MyApplication extends Application {
  public function __construct() {
    $config = new ApplicationConfig();

    parent::__construct($config->setEnvironment(Environments::DEV())
                               ->setModels(['Book'])
                               ->setAllowedMethods(['GET', 'POST', 'PUT', 'DELETE']));
  }
}

$my_application = new MyApplication();
```

# Methods

The first path segment after hostname refers to a model.

[Example.sql](https://github.com/goobemaster/sway/blob/master/resources/Example.sql)

[Example.postman_dump](https://github.com/goobemaster/sway/blob/master/resources/Example.postman_dump)

## GET

Each parameter is a WHERE clause.

{{host}}/book?title=War %26 Peace

{{host}}/book?title=War %26 Peace&edition=1

## PUT

Fields are extracted from headers.

{{host}}/book

## DELETE

Fields are extracted from headers, and each one is a WHERE clause.

{{host}}/book

## POST

Used to update an existing record. Each URL parameter is a WHERE clause. Values should be sent as form data (x-www-form-urlencoded).

{{host}}/book?title=War %26 Peace
