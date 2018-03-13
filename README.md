# PSR-7 & PSR-15 router library

`lib-PSR-15router` is a [PSR-15](https://www.php-fig.org/psr/psr-15/) router library written in PHP 7, processing [PSR-7](https://www.php-fig.org/psr/psr-7/) requests and responses. A router is a component in charge of determining which controller to call to answer a request, to call the selected controller and then to returns the PSR-7 response. It knowns a list of routes and their matching controllers. A controller is defined by the `ControllerInterface` interface. 

A router is technically a PSR-15 request handler (it implements [`RequestHandlerInterface`](https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface)) and can be used with any [PSR-15 middlewares](https://www.php-fig.org/psr/psr-15/#22-psrhttpservermiddlewareinterface). 

**Here is how the router works :**
1. The router receives a PSR-7 request (implementing [`ServerRequestInterface`](https://www.php-fig.org/psr/psr-7/#321-psrhttpmessageserverrequestinterface))
2. The router searches for a controller in its internal routes stack to process the request, in order to do so the path of the request's URI is compared with the known routes using [`fnmatch()`](http://php.net/manual/fr/function.fnmatch.php);
4. The router instantiate the controller with the PSR-7 request and calls the controller `process()` method. The controller returns a PSR-7 response (implementing [`ResponseInterface`](https://www.php-fig.org/psr/psr-7/#33-psrhttpmessageresponseinterface)).
7. The response can be passed to a PSR-15 Middleware or directly streamed to the browser using a [response sender](#streaming-responses).



## Usage

```php
<?php
use CodeInc\Router\Router;
use CodeInc\PSR7ResponseSender\ResponseSender; // from the lib-psr7responsesender package
use GuzzleHttp\Psr7\ServerRequest;
use CodeInc\Router\ControllerInterface; 

// example controllers
final class HomeController implements ControllerInterface { 
} 
final class LicenseController implements ControllerInterface { 
} 
final class ArticleController implements ControllerInterface { 
} 

// adding routes
$myRouter = new Router();
$myRouter->addRoute("/", HomeController::class); 
$myRouter->addRoute("/license.txt", LicenseController::class); 
$myRouter->addRoute("/article-[0-9]/*", ArticleController::class); 

// is is possible to define where not found request should be routed
$myRouter->setNotFoundController("/error404.html");

// processing and the response
$request = ServerRequest::fromGlobals();
$response = $myRouter->handle($request);

// sending the response to the web browser using codeinchq/lib-psr7responsesender
(new ResponseSender())->send($response);
```

### Defining your own instantiator
Sometimes you've extra information you need to pass to the controller in order to instantiate it. To do so you can define your own controller instantiator class by implementing `ControllerInstantiatorInterface`:

```php
<?php
use CodeInc\Router\Instantiators\InstantiatorInterface;
use CodeInc\Router\ControllerInterface;
use CodeInc\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

class MyInstantiator implements InstantiatorInterface {
    public function instanciate(string $controllerClass, ServerRequestInterface $request):ControllerInterface {
    	// for instance your could pass Doctrine's EntityManager to your controllers
        return new $controllerClass($request, $this->doctrineEntityManager);        
    }
}

$router = new Router(new MyInstantiator);
```

### Using the router with PSR-15 middlewares

You can add PSR-15 middlewares (anything implementing [`MiddlewareInterface`](https://www.php-fig.org/psr/psr-15/#22-psrhttpservermiddlewareinterface)) to the router in order to modify the PSR-7 request or response. 

```php
<?php 
use CodeInc\Router\Router;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\ServerRequest;
use CodeInc\Psr7ResponseSender\ResponseSender; 

class MyFirstMiddleware implements MiddlewareInterface {
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface 
	{
		$response = $handler->handle($request);
		return $response->withHeader("X-Powered-By", "This example");
    }
}
class MySecondMiddleware implements MiddlewareInterface {}

$myRouter = new Router();
$myRouter->addMiddleware(new MyFirstMiddleware());
$myRouter->addMiddleware(new MySecondMiddleware());

$request = ServerRequest::fromGlobals();
(new ResponseSender())->send($response);
```

### Aggregating routers

You can aggregate multiple router usign the `RouterAggregate` class. This can come handy for large projects with independent modules having their own internal router or to mix different router types, for instance a web page router and a web asset router.

The order in which you aggregate routers matters. When asked to process a request, `RouterAggregate` will call the first router capable of processing the request (the first returning `true` for `canHandle($request)`).  

`RouterAggregate` is also a router (implement `RouterInterface` and therefor `RequestHandlerInterface`), so you can aggregate both aggregators and routers within a `RouterAggregate`.

```php
<?php
use CodeInc\Router\Router;
use CodeInc\Router\RouterAggregator;
use GuzzleHttp\Psr7\ServerRequest;

// creating routers 
$router1 = new Router();
$router2 = new Router();
$router3 = new Router();

// creating a first aggregate
$aggregator1 = new RouterAggregator();
$aggregator1->addRouter($router1);
$aggregator1->addRouter($router2);

// creating a second aggregate
$aggregator2 = new RouterAggregator();
$aggregator2->addRouter($router3);
$aggregator2->addRouter($aggregator1); // you can aggregate an aggregator 

// you also can add middlewares to RouterAggregator
$aggregator2->addMiddleware(new MyFirstMiddleware());
$aggregator2->addMiddleware(new MySecondMiddleware());

// handling a request 
$request = ServerRequest::fromGlobals();
$response = $aggregator2->handle($request);
```

### Streaming responses

A companion library [`lib-psr7responsesender`](https://github.com/CodeIncHQ/lib-psr7responsesender) is available to stream PSR-7 response to the web browser. The library also provides a standard interface `ResponseSenderInterface` for PSR-7 response senders.
```php
<?php 
use CodeInc\PSR7ResponseSender\ResponseSender;
use GuzzleHttp\Psr7\Response;

// any PSR-7 response 
$response = new Response();

// sending 
$sender = new ResponseSender();
$sender->send($response); 
// Note: passing the request object to the response sender 
// allows the sender to make sure the response will be sent
// using the same version of the HTTP protocol
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinc/router) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/router
```

## Recommended libraries
* [`codeinchq/lib-psr7responsesender`](https://packagist.org/packages/codeinchq/lib-psr7responsesender) recommended to stream the PSR-7 responses to the web browser ;
* [`codeinchq/lib-psr15middlewares`](https://packagist.org/packages/codeinchq/lib-psr15middlewares) provides a collection PSR-15 middlewares ;
* [`middlewares/psr15-middlewares`](https://github.com/middlewares/psr15-middlewares) provides an even bigger collection PSR-15 middlewares ;


## License 
This library is published under the MIT license (see the [`LICENSE`](LICENSE) file).


