# PSR-7 & PSR-15 router library

This PHP 7.1 library provides two [PSR-7](https://www.php-fig.org/psr/psr-7/) and [PSR-15](https://www.php-fig.org/psr/psr-15/) compatible routers.  

A router is a `class` implementing [`RouterInterface`](src/RouterInterface.php) in charge of matching a controller to a request. It has two methods: `getControllerClass()` which returns the class corresponding to PSR-7 server request and `getControllerUri()` which returns the URI of a controller. 

A controller is a `class` implementing [`ControllerInterface`](src/ControllerInterface.php) which takes a PSR-7 server request object ([`ServerRequestInterface`](https://github.com/php-fig/http-message/blob/master/src/ServerRequestInterface.php)) as a constructor parameter and returns a PSR-7 response through the `getResponse()` method.  

 Two routers are supplied: 
 * a static router ([`Router`](src/Router.php)) which works with a list of predefined routes (unix patterns) and their corresponding controllers;
 * a dynamic router ([`DynamicRouter`](src/DynamicRouter.php)) which computes the controller's class name base on the request URI. 
 
 Simple routers are not capable of instantiating controllers. In order to be able to instantiate controllers at router's level, you must implement [`InstantiatingRouterInterface`](src/InstantiatingRouterInterface.php).

The routers can be used either as PSR-15 request handlers (using [`RouterRequestHandler`](src/Psr15Wrapper/RouterRequestHandler.php) implementing [`RequestHandlerInterface`](https://github.com/http-interop/http-middleware/blob/master/src/RequestHandlerInterface.php)) or as PSR-15 middleware (using [`RouterMiddleware`](src/Psr15Wrapper/RouterMiddleware.php) implementing [`MiddlewareInterface`](https://github.com/http-interop/http-middleware/blob/master/src/MiddlewareInterface.php)).

## Usage

### Basic usage
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
$myRouter->getControllerClass($aPsr7ServerRequest); // <-- returns 'ArticleController'

// URI lookup
$myRouter->getControllerUri(LicenseController::class); // <-- returns "/license.txt"
```

### Dynamic router
```php
<?php
use CodeInc\Router\DynamicRouter;

$myRouter = new DynamicRouter(
    'MyApp\\Controllers', // <-- the controllers base namespace
    '/MyAppPages/' // <-- the base URI
    );

// controller lookup (assuming the URI of the request is "/MyAppPages/User/Account") 
$myRouter->getControllerClass($aPsr7ServerRequest); // <-- returns 'MyApp\Controllers\User\Account'

// URI lookup 
$myRouter->getControllerUri(MyApp\Controllers\Shop\Basket); // <-- returns "/MyAppPages/Shop/Basket"
```

### Instantiating routers
```php
<?php
use CodeInc\Router\Router;
use CodeInc\Router\ControllerInterface;
use CodeInc\Router\InstantiatingRouterInterface;
use Psr\Http\Message\ServerRequestInterface;

// as an instantiating router
class MyInstantiatingRouter extends Router implements InstantiatingRouterInterface
{
    public function getController(ServerRequestInterface $request):?ControllerInterface
    {
        // instantiating the controller
        if ($controllerClass = $this->getControllerClass($request)) {
            return new $controllerClass($request);
        }
        return null;
    }
}

$myInstantiatingRouter = new MyInstantiatingRouter();
$myInstantiatingRouter->addRoute("/", HomeController::class); 
$myInstantiatingRouter->addRoute("/license.txt", LicenseController::class); 
$myInstantiatingRouter->addRoute("/article-[0-9]/*", ArticleController::class); 

// controller lookup (assuming the URI of the request is "/article-2456/a-great-article.html") 
$myInstantiatingRouter->getControllerClass($aPsr7ServerRequest); // <-- returns 'ArticleController'
$myInstantiatingRouter->getController($aPsr7ServerRequest); // <-- returns an instance of 'ArticleController'
```

### As a PSR-15 request handler and middleware

You can use instantiating routers as PSR-15 request handler or middleware.

```php
<?php
use CodeInc\Router\Psr15Wrappers\RouterRequestHandler;
use CodeInc\Router\Psr15Wrappers\RouterMiddleware;

// as a PSR-15 request handler
$requestHandler = new RouterRequestHandler($myInstantiatingRouter);
$requestHandler->handle($aPsr7ServerRequest);

// as a PSR-15 middleware
$middleware = new RouterMiddleware($myInstantiatingRouter);
$middleware->process($aPsr7ServerRequest, $aPsr7RequestHandler);
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/router) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/router
```

## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).