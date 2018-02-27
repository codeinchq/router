# PSR-7 & PSR-15 router library

`lib-PSR-15router` is a [PSR-15](https://www.php-fig.org/psr/psr-15/) router library written in PHP 7, processing [PSR-7](https://www.php-fig.org/psr/psr-7/) requests and responses. A router is a component in charge of determining which handler to call to answer a request, to call the selected handler and then to returns the HTTP response to the web browser. It knowns a list of routes and their matching handlers. 

A router is technically a PSR-15 request handler (it implements [`RequestHandlerInterface`](https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface)).

**Here is how the router works :**
1. The router receives a PSR-7 request (implementing [`ServerRequestInterface`](https://www.php-fig.org/psr/psr-7/#321-psrhttpmessageserverrequestinterface), often built using `ServerRequest::fromGlobals()`)
2. The router searches for a compatible PSR-15 handler (implementing [`RequestHandlerInterface`](https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface)) in its internal stack to process the request, in order to do so the path of the request's URI is compared with the known routes using [`fnmatch()`](http://php.net/manual/fr/function.fnmatch.php);
4. The router calls (and instantiate if required) the PSR-15 handler with the PSR-7 request. The handler returns a PSR-7 response (implementing [`ResponseInterface`](https://www.php-fig.org/psr/psr-7/#33-psrhttpmessageresponseinterface)).
7. The response can be passed to a PSR-15 Middleware or directly streamed to the browser using a [response sender](#streaming-responses).



## Usage

### Using the router

The `Router` class is able to mix various handlers to process routes. A handler is either a class or an instantiated object implementing [`RequestHandlerInterface`](https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface). It also can be a [`callable`](http://php.net/manual/en/language.types.callable.php) or a [`Closure`](http://php.net/manual/fr/class.closure.php) through the `CallableRequestHandler` and `ClosureRequestHandler` classes. 

The routes are evaluated in order using [`fnmatch()`](http://php.net/manual/en/function.fnmatch.php) and are compatible with [standard shell patterns](https://www.gnu.org/software/findutils/manual/html_node/find_html/Shell-Pattern-Matching.html). A directly matching route will always be winning over a pattern. For instance for the `/article-3.html` request,
the `/article-3.html` route will win over the `/article-[0-9].html` pattern route even if the later one has been added first.

Note: you can also design you own router by implementing `RouterInterface`.

```php
<?php
use CodeInc\Router\Router;
use CodeInc\PSR7ResponseSender\ResponseSender; // from the lib-psr7responsesender package
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use CodeInc\Router\RequestHandlers\ClosureRequestHandler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use CodeInc\Psr7Responses\TextResponse;
 
// example pages
final class HomePage implements RequestHandlerInterface { 
	public function handle(ServerRequestInterface $request): ResponseInterface { return new Response(); }
} 
final class LicensePage implements RequestHandlerInterface { 
	public function handle(ServerRequestInterface $request): ResponseInterface { return new Response(); }
} 
final class ArticlePage implements RequestHandlerInterface { 
	public function handle(ServerRequestInterface $request): ResponseInterface { return new Response(); }
} 

$myRouter = new Router();

// a page can be a class name implementing RequestHandlerInterface
$myRouter->addRoute("/", HomePage::class); 

// an object implementing RequestHandlerInterface
$myRouter->addRoute("/license.txt", new LicensePage); 

// or even a callable through the CallableRequestHandler class
$myRouter->addRoute("/error404.html", new ClosureRequestHandler(function(RequestInterface $request):ResponseInterface { 
    return new TextResponse(sprintf("The page %s is not found!", $request->getUri()->getPath()), null, 404);
}));

// routes are compatible with standard shell patterns
$myRouter->addRoute("/article-[0-9]/*", ArticlePage::class); 

// is is possible to define where not found request should be routed
$myRouter->setNotFoundRoute("/error404.html");

// processing and sending the response
$request = ServerRequest::fromGlobals();
$response = $myRouter->handle($request);
(new ResponseSender())->send($response);
```

### Using the router with PSR-15 middlewares

A router being a PSR-15 request handler, you can use it with any PSR-15 middlewares (anything implementing [`MiddlewareInterface`](https://www.php-fig.org/psr/psr-15/#22-psrhttpservermiddlewareinterface)) in order to modify the PSR-7 request or response. 

```php
<?php 
use CodeInc\Router\Router;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\ServerRequest;
use CodeInc\Psr7ResponseSender\ResponseSender; 

class MyMiddleware implements MiddlewareInterface {
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface 
	{
		$response = $handler->handle($request);
		return $response->withHeader("X-Powered-By", "This example");
    }
}

$myRouter = new Router();
$myMiddleware = new MyMiddleware();

$request = ServerRequest::fromGlobals();
$response = $myMiddleware->process($request, $myRouter);
(new ResponseSender())->send($response);
```

### Aggregating routers

You can aggregate multiple router usign the `RouterAggregate` class. This can come handy for large projects with independent modules having their own internal router or to mix different router types, for instance a web page router and a web asset router.

The order in which you aggregate routers matters. When asked to process a request, `RouterAggregate` will call the first router capable of processing the request (the first returning `true` for `canHandle($request)`).  

`RouterAggregate` is also a router (implement `RouterInterface`), so you can aggregate both aggregators and routers within a `RouterAggregate`.

```php
<?php
use CodeInc\Router\Router;
use CodeInc\Router\RouterAggregate\RouterAggregate;
use CodeInc\PSR7ResponseSender\ResponseSender;
use GuzzleHttp\Psr7\ServerRequest;

// creating routers 
$router1 = new Router();
$router2 = new Router();
$router3 = new Router();

// creating a first aggregate
$routerAggregte1 = new RouterAggregate();
$routerAggregte1->addRouter($router1);
$routerAggregte1->addRouter($router2);

// creating a second aggregate
$routerAggregte2 = new RouterAggregate();
$routerAggregte2->addRouter($router3);
$routerAggregte2->addRouter($routerAggregte1);

// calling 
$request = ServerRequest::fromGlobals();
$response = $routerAggregte2->handle($request);
(new ResponseSender())->send($response);
```
A router is a routable, so you can aggregate a router directly in another router. In this case the behavior is different than when using `RouterAggregate`: the sub router will be called only (and always) for it's matching route (the parent router will never asked the sub router if it can process the route through `canHandle()`)

```php
<?php 
use CodeInc\Router\Router;
use CodeInc\PSR7ResponseSender\ResponseSender;
use GuzzleHttp\Psr7\ServerRequest;

$parentRouter = new Router();
$imageRouter = new Router();
$parentRouter->addRoute("/images/*.jpg", $imageRouter);
$parentRouter->addRoute("/images/*.png", $imageRouter); 
// Note: you can add multiple routes to the same target

$request = ServerRequest::fromGlobals();
$response = $parentRouter->handle($request);
(new ResponseSender())->send($response);
```

### Streaming responses

A companion library [`lib-psr7responsesender`](https://github.com/CodeIncHQ/lib-psr7responsesender) is available to stream PSR-7 response to the web browser. The library also provides a standard interface for PSR-7 response senders.
```php
<?php 
use CodeInc\PSR7ResponseSender\ResponseSender;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

// an example request
$request = ServerRequest::fromGlobals();

// any response implementing ResponseInterface
$response = new Response();

// sending 
$sender = new ResponseSender();
$sender->send($response); 
// Note: passing the request object to the response sender 
// allows the sender to make sure the response will be sent
// using the same version of the HTTP protocol
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinchq/lib-router) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinchq/lib-router
```

## Recommended libraries
* [`codeinchq/lib-psr7responsesender`](https://packagist.org/packages/codeinchq/lib-psr7responsesender) recommended to stream the PSR-7 responses to the web browser ;
* [`codeinchq/lib-psr7responses`](https://packagist.org/packages/codeinchq/lib-psr7responses) provides a collection of PSR-7 responses ;
* [`codeinchq/lib-psr15middlewares`](https://packagist.org/packages/codeinchq/lib-psr15middlewares) provides a collection PSR-15 middlewares ;
* [`middlewares/psr15-middlewares`](https://github.com/middlewares/psr15-middlewares) provides a collection PSR-15 middlewares ;
* [`hansott/psr7-cookies`](https://packagist.org/packages/hansott/psr7-cookies) recommended to add cookies to the PSR-7. responses.


## License 
This library is published under the MIT license (see the [`LICENSE`](https://github.com/codeinchq/lib-gui/blob/master/LICENSE) file).


