# PSR-7 & PSR-15 router library

`codeinc/router` is a [PSR-15](https://www.php-fig.org/psr/psr-15/) router library written in PHP 7, processing [PSR-7](https://www.php-fig.org/psr/psr-7/) requests and responses. A router is a [PSR-15](https://www.php-fig.org/psr/psr-15/) middleware implementing [`MiddlewareInterface`](https://www.php-fig.org/psr/psr-15/#22-psrhttpservermiddlewareinterface) in charge of determining which request handler to call to answer a request, to call the selected controller and then to returns the PSR-7 response. All routers must implement the [`RouterInterface`](src/ResolverInterface.php) interface. 

Two types of routers are supplied in the current package: a static router which takes a list of predefined routes and request handlers classes and a dynamic router which computes the routes of the request handlers base on the request handler's namespace (compatible with [PSR-0](https://www.php-fig.org/psr/psr-0/) or [PSR-4](https://www.php-fig.org/psr/psr-4/) naming conventions). Both are available as `final` ([`StaticRouter`](src/StaticRouter.php) and [`DynamicRouter`](src/DynamicRouter/DynamicRouter.php)) and `abstract` classes ([`AbstractStaticRouter`](src/StaticRouter/AbstractStaticRouter.php) and [`AbstractDynamicRouter`](src/DynamicRouter/DynamicRouter.php)).

## Usage

### Static router
```php
<?php
use CodeInc\Router\StaticRouter\StaticRouter;
use CodeInc\PSR7ResponseSender\ResponseSender; 
use Psr\Http\Server\RequestHandlerInterface;

// some request handlers...
final class HomeController implements RequestHandlerInterface { } 
final class LicenseController implements RequestHandlerInterface { } 
final class ArticleController implements RequestHandlerInterface { } 

// adding routes
$myRouter = new StaticRouter();
$myRouter->addRequestHandler("/", HomeController::class); 
$myRouter->addRequestHandler("/license.txt", LicenseController::class); 
$myRouter->addRequestHandler("/article-[0-9]/*", ArticleController::class); 

// processing and the response
// --> $aPsr7ServerRequest must implement ServerRequestInterface 
// --> $notFoundRequestHandler must implement RequestHandlerInterface 
$response = $myRouter->process($aPsr7ServerRequest, $notFoundRequestHandler);

// sending the response using codeinc/psr7-response-sender
// --> see https://packagist.org/packages/codeinc/psr7-response-sender
(new ResponseSender())->send($response);
```

In order to instantiate the request handlers, you can pass an object implementing [`RequestHandlerInstantiatorInterface`](src/RequestHandlerInstantiator/RequestHandlerFactoryInterface.php) to the `StaticRouter` constructor.


### Dynamic router 

```php
<?php
use CodeInc\Router\DynamicRouter\DynamicRouter;
use CodeInc\PSR7ResponseSender\ResponseSender; 

// in the current example a class named MyApp\Controllers\User\MyAccount
// will be available at /User/MyAccount
$myRouter = new DynamicRouter(
    'MyApp\\Controllers', // <-- namespace to the request handler
    '/' // <-- base URI of the request handlers
);

// processing and the response
// --> $aPsr7ServerRequest must implement ServerRequestInterface 
// --> $notFoundRequestHandler must implement RequestHandlerInterface 
$response = $myRouter->process($aPsr7ServerRequest, $notFoundRequestHandler);

// sending the response using codeinc/psr7-response-sender
// --> see https://packagist.org/packages/codeinc/psr7-response-sender
(new ResponseSender())->send($response);
```

### As a PSR-15 request handler

The router can behave as a PSR-15 request handler (implementing `RequestHandlerInterface`) using [`RouterRequestHandlerWrapper`](src/RouterRequestHandlerWrapper.php):
 ```php
 <?php
 use CodeInc\Router\DynamicRouter\DynamicRouter;
 use CodeInc\Router\RouterRequestHandlerWrapper;
 
 // in the current example a class named MyApp\Controllers\User\MyAccount
 // will be available at /User/MyAccount
 $myRouter = new DynamicRouter('MyApp\\Controllers', '/');
 
 // the router is now a PSR-15 request handler implementing RequestHandlerInterface
 // --> $notFoundRequestHandler must implement RequestHandlerInterface 
 $myRouterAsARequestHandler = new RouterRequestHandlerWrapper($myRouter, $notFoundRequestHandler);
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/router) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/router
```

## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).