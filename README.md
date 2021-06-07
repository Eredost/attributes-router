# Attributes router

[![Build Status](https://travis-ci.com/Eredost/attributes-router.svg?branch=main)](https://travis-ci.com/Eredost/attributes-router)
[![Maintainability](https://api.codeclimate.com/v1/badges/73fa249c8e3ddb42263c/maintainability)](https://codeclimate.com/github/Eredost/attributes-router/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/73fa249c8e3ddb42263c/test_coverage)](https://codeclimate.com/github/Eredost/attributes-router/test_coverage)

Attributes router is a light library allowing to set up a router and
to define routes via the attributes of PHP 8.

## Installation

Before, you can download the library you must first have a version of
PHP ~8.0 and a recent version of Composer.

1. First, as the library is not referenced on Packagist, you will have
  to add the repository in the configuration of your composer.json

   ```json
   "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/Eredost/attributes-router.git"
      }
   ]
   ```

2. Then, all you have to do is install the library using the following command:

   ```shell
   composer require eredost/attributes-router
   ```

## Usage

Simple usage of the router:

- Define the routes in your controller with the Route attribute

   ```php
   <?php

  namespace App\Controller;

  use AttributesRouter\Attribute\Route;

  class MainController
  {
      #[Route('/', name: 'homepage', methods: ['GET', 'POST'])]
      public function home()
      {
      }

      #[Route('/article/{slug}/comment/{id<\d+>}', name: 'article-comment')]
      public function comment()
      {
      }
  }
   ```

- Create the router passing as argument the controllers on which you have
  defined routes attributes.

   ```php
   <?php

   use App\Controller\MainController;
   use AttributesRouter\Router;

   require 'vendor/autoload.php';

   $router = new Router([MainController::class]);

   // If there is a match, he will return the class and method associated
   // to the request as well as route parameters
   if ($match = $router->match()) {
       $controller = new $match['class']();
       $controller->{$match['method']}($match['params']);
   }
   ```

### Add controllers

You have the possibility after instantiating the Router object to be able
to add new controllers, these will be added with those already stored.

```php
$router->addRoutes([AnotherController::class]);
```

### Define a base URI

It can be interesting in certain cases, such as for example when your project
is called from a sub-directory, to define a base URI so that this one is
ignored when the router compares the routes with the current request. You can
define it either via the constructor or via the setter.

```php
// Via the Router constructor
$router = new Router([MainController::class], '/dir/sub-dir');

// Via the associated setter
$router->setBaseURI('/dir/sub-dir');
```

### Generate a URL from route name

You have the possibility from the name of the route, to generate a URL.

```php
$router->generateUrl('article-comment', ['slug' => 'hello-world', 'id' => 15]);
```

## Contributing

See the [Contributing.md](CONTRIBUTING.md) file for more information
on how to contribute to the project.
