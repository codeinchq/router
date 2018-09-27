# PSR-7 & PSR-15 router library

This PHP 7.1 library provides two [PSR-7](https://www.php-fig.org/psr/psr-7/) and [PSR-15](https://www.php-fig.org/psr/psr-15/) compatible routers.  

A router is a simple piece of software in charge of matching a controller to a request. All routers must implement [`RouterInterface`](src/RouterInterface.php). A controller is a `class` implementing [`ControllerInterface`](src/ControllerInterface.php) which takes a PSR-7 server request object ([`ServerRequestInterface`](https://github.com/php-fig/http-message/blob/master/src/ServerRequestInterface.php)) as a constructor parameter and returns a PSR-7 response through the `getResponse()` method.  
 
 Two routers are supplied: 
 * a static router ([`Router`](src/Router.php)) which works with a list of predefined routes (unix patterns) and their corresponding controllers a
 * a dynamic router ([`DynamicRouter`](src/DynamicRouter.php)) which computes the controller's class name base on the request URI. 

The routers can be used either as PSR-15 request handlers (using [`RouterRequestHandler`](src/Psr15Wrapper/RouterRequestHandler.php) implementing [`RequestHandlerInterface`](https://github.com/http-interop/http-middleware/blob/master/src/RequestHandlerInterface.php)) or as PSR-15 middleware (using [`RouterMiddleware`](src/Psr15Wrapper/RouterMiddleware.php) implementing [`MiddlewareInterface`](https://github.com/http-interop/http-middleware/blob/master/src/MiddlewareInterface.php)).

## Usage

```php
<?php
use CodeInc\Router\Router;
use CodeInc\Router\ControllerInterface;

// some request handlers...
final class HomeController implements ControllerInterface { } 
final class LicenseController implements ControllerInterface { } 
final class ArticleController implements ControllerInterface { } 

// adding routes
$myRouter = new Router();
$myRouter->addRoute("/", HomeController::class); 
$myRouter->addRoute("/license.txt", LicenseController::class); 
$myRouter->addRoute("/article-[0-9]/*", ArticleController::class); 

// controller lookup (assuming the URI of the request is "/article-2456/a-great-article.html") 
$myRouter->getController($aPsr7ServerRequest); // <-- returns an instance of ArticleController

// URI lookup
$myRouter->getControllerUri(LicenseController::class); // <-- returns "/license.txt"
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/router) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/router
```

## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).