# PSR-7 & PSR-15 router library

This library provides a [PSR-7](https://www.php-fig.org/psr/psr-7/) and [PSR-15](https://www.php-fig.org/psr/psr-15/) compatible router. The router is a component in charge or selecting the appropriate [PSR-15 request handler](https://www.php-fig.org/psr/psr-15/#11-request-handlers) ([`RequestHandlerInterface`](https://github.com/http-interop/http-middleware/blob/master/src/RequestHandlerInterface.php)) for a given [PSR-7 server request](https://www.php-fig.org/psr/psr-7/#321-psrhttpmessageserverrequestinterface) ([`ServerRequestInterface`](https://github.com/php-fig/http-message/blob/master/src/ServerRequestInterface.php)). It behaves as a [PSR-15 middleware](https://www.php-fig.org/psr/psr-15/#12-middleware) ([`MiddlewareInterface`](https://github.com/http-interop/http-middleware/blob/master/src/MiddlewareInterface.php)). 

## Resolvers
In order to select a request handler, the router relies on a handler resolver (implementing [`HandlerResolverInterface`](src/Resolvers/HandlerResolverInterface.php)). Resolvers are in charge of matching a URI path (called a route) to a request handler's class and vice versa, mapping a request handler's class to a route. Two resolvers are provided, [`StaticResolver`](src/Resolvers/StaticHandlerResolver.php) which uses a list of preset patterns (shell patterns resolved by [`fnmatch()`](http://php.net/manual/function.fnmatch.php)) matching handler's classes and [`DynamicResolver`](src/Resolvers/DynamicHandlerResolver.php) which dynamically calculates the routes of namespaces base on a PHP namespace and a base URI. You can pair multiple resolvers using [`HandlerResolverAggregator`](src/Resolvers/HandlerResolverAggregator.php).

## Instantiator
In order to instantiate a request handler, the router uses an instantiator (implementing [`HandlerInstantiatorInterface`](src/Instantiator/HandlerInstantiatorInterface.php)). The instantiator component is app specific in order to pass the required services and managers to the request handler's constructor method. You can pair multiple instantiators using [`HandlerInstantiatorAggregator`](src/Instantiator/HandlerInstantiatorAggregator.php). 


## Usage

### Basic usage
```php
<?php
use CodeInc\Router\Router;
use CodeInc\Router\Instantiator\HandlerInstantiatorInterface;
use CodeInc\Router\Resolvers\StaticHandlerResolver;
use Psr\Http\Server\RequestHandlerInterface;

// dummy classes 
final class MyInstantiator implements HandlerInstantiatorInterface {}
final class HomePage implements RequestHandlerInterface {}
final class License implements RequestHandlerInterface {}
final class Article implements RequestHandlerInterface {}

// instantiating the router
$myRouter = new Router(
    new StaticHandlerResolver([
        '/' => HomePage::class,
        '/license.txt' => License::class,
        '/article-[0-9]/*' => Article::class
    ]),
    new MyInstantiator()
);

// controller lookup (assuming the URI of the request is "/article-2456/a-great-article.html") 
$myRouter->handle($aPsr7ServerRequest); // <-- returns 'ArticleController'
```


### Working with multiple resolvers and multiple instantiators 
```php
<?php
use CodeInc\Router\Router;
use CodeInc\Router\Instantiator\HandlerInstantiatorInterface;
use CodeInc\Router\Instantiator\HandlerInstantiatorAggregator;
use CodeInc\Router\Resolvers\StaticHandlerResolver;
use Psr\Http\Server\RequestHandlerInterface;
use CodeInc\Router\Resolvers\HandlerResolverAggregator;
use CodeInc\Router\Resolvers\DynamicHandlerResolver

// dummy classes 
final class MyFirstInstantiator implements HandlerInstantiatorInterface {}
final class MySecondInstantiator implements HandlerInstantiatorInterface {}
final class HomePage implements RequestHandlerInterface {}
final class License implements RequestHandlerInterface {}
final class Article implements RequestHandlerInterface {}

// instantiating the router
$myRouter = new Router(
    new HandlerResolverAggregator([
        new StaticHandlerResolver([
            '/' => HomePage::class,
            '/license.txt' => License::class,
            '/article-[0-9]/*' => Article::class
        ]),
        new DynamicHandlerResolver(
            'MyApp\\MyHandlers', // <-- handlers base namespace
            '/my-app/' // <-- handlers base URI
        )
    ]),
    new HandlerInstantiatorAggregator([
        new MyFirstInstantiator(),
        new MySecondInstantiator(),
    ])
);

// controller lookup (assuming the URI of the request is "/article-2456/a-great-article.html") 
$myRouter->handle($aPsr7ServerRequest); // <-- returns 'ArticleController'
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/router) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/router
```

## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).