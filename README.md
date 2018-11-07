# PSR-7 & PSR-15 router library

This library provides a [PSR-7](https://www.php-fig.org/psr/psr-7/) and [PSR-15](https://www.php-fig.org/psr/psr-15/) compatible router. The router is a component in charge or selecting the appropriate controller (implementing [`ControllerInterface`](src/ControllerInterface.php)). It behaves as a [PSR-15 middleware](https://www.php-fig.org/psr/psr-15/#12-middleware) ([`MiddlewareInterface`](https://github.com/http-interop/http-middleware/blob/master/src/MiddlewareInterface.php)). 

## Resolvers
In order to select a controller, the router relies on a resolver (implementing [`ResolverInterface`](src/Resolvers/ResolverInterface.php)). Resolvers are in charge of matching a URI path (called a route) to a controller and vice versa, mapping a controller to a route. Two resolvers are provided, [`StaticResolver`](src/Resolvers/StaticResolver.php) which uses a list of preset patterns (shell patterns resolved by [`fnmatch()`](http://php.net/manual/function.fnmatch.php)) matching handler's classes and [`DynamicResolver`](src/Resolvers/DynamicResolver.php) which dynamically calculates the routes of namespaces base on a PHP namespace and a base URI. You can pair multiple resolvers using [`ResolverAggregator`](src/Resolvers/ResolverAggregator.php). External packages provides resolvers based on [Doctrine's annotations](https://github.com/CodeIncHQ/RouterAnnotationResolver) or on [interfaces](https://github.com/CodeIncHQ/RouterRoutableResolver).

## Usage

### Basic usage
```php
<?php
use CodeInc\Router\Router;
use CodeInc\Router\ControllerInterface;
use CodeInc\Router\Resolvers\StaticResolver;

// dummy classes 
final class MyInstantiator implements ControllerInterface {}
final class HomePage implements ControllerInterface {}
final class License implements ControllerInterface {}
final class Article implements ControllerInterface {}

// instantiating the router
$myRouter = new Router(
    new StaticResolver([
        '/' => HomePage::class,
        '/license.txt' => License::class,
        '/article-[0-9]/*' => Article::class
    ])
);

// controller lookup (assuming the URI of the request is "/article-2456/a-great-article.html") 
$myRouter->process($aPsr7ServerRequest, $aFallbackHandler); // <-- returns 'ArticleController'
```


### Working with multiple resolvers and multiple instantiators 
```php
<?php
use CodeInc\Router\Router;
use CodeInc\Router\ControllerInterface;
use Doctrine\Instantiator\InstantiatorInterface;
use CodeInc\Router\Resolvers\ResolverAggregator;
use CodeInc\Router\Resolvers\StaticResolver;
use CodeInc\Router\Resolvers\DynamicResolver;

// dummy classes 
final class MyFirstInstantiator implements InstantiatorInterface {}
final class MySecondInstantiator implements InstantiatorInterface {}
final class HomePage implements ControllerInterface {}
final class License implements ControllerInterface {}
final class Article implements ControllerInterface {}

// instantiating the router
$myRouter = new Router(
    new ResolverAggregator([
        new StaticResolver([
            '/' => HomePage::class,
            '/license.txt' => License::class,
            '/article-[0-9]/*' => Article::class
        ]),
        new DynamicResolver(
            'MyApp\\MyHandlers', // <-- handlers base namespace
            '/my-app/' // <-- handlers base URI
        )
    ])
);

// processing the response
$myRouter->process($aPsr7ServerRequest, $aFallbackHandler); 
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/router) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/router
```

## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).