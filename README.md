# PSR-7 & PSR-15 router library

This library provides a PSR-7 and PSR-15 compatible router. The router is a component in charge or selecting the appropriate request handler for a given PSR-7 request. It behaves as a PSR-15 middleware. 

## Resolvers
In order to select a request handler, the router relies a handler resolver (implementing `HandlerResolverInterface`). Resolvers are in charge of matching a URI path (called a route) to a request handler's class and vice versa, mapping a request handler's class to a route. Two resolvers are provided, `StaticResolver` which uses a list of preset patterns (shell patterns resolved by `fnmatch()`) matching handler's classes and `DynamicResolver` which dynamically calculates the routes of namespaces base on a PHP namespace and a base URI. You can pair multiple resolvers using `HandlerResolverAggregator`.

## Instantiator
In order to instantiate a request handler, the router uses an instantiator (implementing `HandlerInstantiatorInterface`). The instantiator component is app specific in order to pass the required services and managers to the request handler's constructor method. You can pair multiple instantiators using `HandlerInstantiatorAggregator`. 


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


### With multiple resolvers using `HandlerResolverAggregator` and multiple instantiators using `HandlerInstantiatorAggregator`
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