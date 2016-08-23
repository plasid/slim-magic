# slim-magic
A bootstrapper/classmapper/loader for Slim Framework 3x. Keeping your Slim bootstrapping clean no matter how many routes, dependencies and middleware you have, allowing you to build really large applications in a structured manner. 

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install SlimMagic.

```bash
$ composer require atlantic8-web/slim-magic "^1.0"
// or add to your existing composer.json file
```

This will install SlimMagic and all required dependencies. SlimMagic requires PHP 5.5.0 or newer.

## Configuration

```bash
'slim_magic_settings' => [
        'debug'=>false,
        'routes' => [
            //Home
            '/' => [
                'methods' => ['GET'], //Can be an array of methods, or ommit for default GET
                'classmap' => 'app\Home:index', //String resolver app
                'middleware' => [], //Middleware to load for this app
                'arguments' => [],//Arguments to pass to this app
                'name' => 'home' //App name, also used to generate URL's $slim->setName(...)
            ],
            '/admin/dashboard' => [
                'methods' => ['GET'],
                'classmap' => 'app\Admin:dashboard',
                'middleware' => ['AuthValidation', 'GrapPreload'],
                'arguments' => ['isAdmin'],
                'name' => 'admin_dashboard'
            ]
        ],
        //This will be applied to all routes/apps
        'all' => [
            'middleware' => ['Test', 'Session'],//See Slim docs for importance of order               
            'service' => ['SessionHelper', 'Twig', 'notFoundHandler'] //Service dependencies
        ]
    ]
        
```

## Directory structure
In order to keep things clean we can use folders and service bootstrapper classes - [See code example](https://github.com/atlantic8-web/slim-magic-example-simple) 

```bash
MyNewApp
    -app
    -config
        -slim.php
    -service
        -Dependency
        -Middleware
    -view
    -model
-.htaccess
-index.php
```
 
## Usage

Create a Slim config.php and add SlimMagic route setup and configuration - [See code example](https://github.com/atlantic8-web/slim-magic-example-simple)

Create an index.php file with the following contents:

```php
<?php

require 'vendor/autoload.php';

$container = new \Slim\Container(require 'config/slim.php');

$service = new \SlimMagic\ServiceContainer(new \Slim\App($container));
new \SlimMagic\Mapper($service);

$service->getSlim()->run();
```

See code example, configuration and architecture
[Example](https://github.com/atlantic8-web/slim-magic-example-simple)

## License

The SlimMagic is licensed under the MIT license. See [License File](LICENSE.md) for more information.
