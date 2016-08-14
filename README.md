# slim-magic
A bootstrapper/classmapper/loader for Slim Framework 3x. Keeping your Slim bootstrapping clean no matter how many routes, dependencies and middleware you have, allowing you to build really large applications in a structured manner. 

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install SlimMagic.

```bash
$ composer require atlantic8-web/slim-magic "^1.0"
```

This will install SlimMagic and all required dependencies. SlimMagic requires PHP 5.5.0 or newer.

## Usage

Create a Slim config.php and add the following SlimMagic configuration - [See code example](https://github.com/atlantic8-web/slim-magic-example-simple):


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
