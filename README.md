# Code Inc. PSR7 & PSR15 router library

`lib-psr15router` is a [PSR15](https://www.php-fig.org/psr/psr-15/) router library written in PHP 7, processing [PSR7](https://www.php-fig.org/psr/psr-7/) [requests](https://www.php-fig.org/psr/psr-7/#32-psrhttpmessagerequestinterface) and [responses](https://www.php-fig.org/psr/psr-7/#33-psrhttpmessageresponseinterface). A router is a component in charge of determining which handler to call to answer a request, to call the selected handler and then to returns the HTTP response to the web browser. It knowns a list of routes and their matching handlers. A handler can be any class implementing [PSR7 `ServerRequestInterface`](https://www.php-fig.org/psr/psr-7/#321-psrhttpmessageserverrequestinterface) or even a 

**Here is how the router works :**
1. The router receives a PSR7 request (implementing [PSR7 `ServerRequestInterface`](https://www.php-fig.org/psr/psr-7/#321-psrhttpmessageserverrequestinterface), often the request is built form the current web browser request using `ServerRequest::fromGlobals()`)
2. The router searches for a compatible handler (implementing [PSR15 `RequestHandlerInterface`](https://www.php-fig.org/psr/psr-15/#21-psrhttpserverrequesthandlerinterface)) to process the request, in order to do so the path of the request's URI is compared with the known routes using `fnmatch()`;
4. The router instantiate (if required) the PSR15 handler.
5. The router passes the PSR15 handler and the PSR7 request to a middleware in charge of processing the request and building the PSR7 response. 
6. The router passes the PSR7 response to a PSR7 response sender in charge of streaming the response to the web browser.



## Usage

### Using the router

The `Router` class is able to mix various handlers to process routes. A handler is either a class or an instantiated object implementing `RequestHandlerInterface`. It also can be a [`callable`](http://php.net/manual/en/language.types.callable.php) through the `CallableRequestHandler` class. The `callable` will received the PSR7 `Request` object as parameter and should return PSR7 `Response` object.

The routes are evaluated in order using [`fnmatch()`](http://php.net/manual/en/function.fnmatch.php) and are compatible with [standard shell patterns](https://www.gnu.org/software/findutils/manual/html_node/find_html/Shell-Pattern-Matching.html). A directly matching route will always be winning over a pattern. For instance for the `/article-3.html` request,
the `/article-3.html` route will win over the `/article-[0-9].html` pattern route even if the later one has been added first.

Note: you can also design you own router by implementing `RouterInterface`.

```php
<?php
use CodeInc\Router\Router;
use CodeInc\Router\ResponseSender\ResponseSender;
use CodeInc\Router\RequestHandlers\CallableRequestHandler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
 
$myRouter = new Router();

// a page can be a class name implementing RequestHandlerInterface
$myRouter->addRoute("/", HomePage::class); 

// an object implementing RequestHandlerInterface
$myRouter->addRoute("/license.txt", new LicensePage); 

// or even a callable through the CallableRequestHandler class
$myRouter->addRoute("/error404.html", new CallableRequestHandler(function(RequestInterface $request):ResponseInterface { 
    return new Response(404, ["Content-Type" => "text/plain"], 
        sprintf("The page %s is not found!", $request->getUri()->getPath())
     );
}));

// routes are compatible with standard shell patterns
$myRouter->addRoute("/article-[0-9]/*", ArticlePage::class); 

// is is possible to define where not found request should be routed
$myRouter->setNotFoundRoute("/error404.html");

// processing and sending the response
$request = ServerRequest::fromGlobals();
$response = $myRouter->handle($request);
(new ResponseSender())->sendResponse($response, $request);

// Note: passing the request object to the send response 
// method allows the router to make sure the response 
// will be sent using the same version of the HTTP protocol
```

### Aggregating routers

You can aggregate multiple router usign the `RouterAggregate` class. This can come handy for large projects with independent modules having their own internal router or to mix different router types, for instance a web page router and a web asset router.

The order in which you aggregate routers is important. When asked to process a request, `RouterAggregate` will call the first router capable of processing the request (the first returning `true` for `canProcessRequest()`).  

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
$response = $routerAggregte2->process($request);
(new ResponseSender())->sendResponse($response, $request);
```
A router is a routable, so you can aggregate a router directly in another router. In this case the behavior is different than when using `RouterAggregate`: the sub router will be called only (and always) for it's matching route (the parent router will never asked the sub router if it can process the route through `canProcessRequest()`)

```php
<?php 
use CodeInc\Router\Router;
use CodeInc\Router\ResponseSender\ResponseSender;
use GuzzleHttp\Psr7\ServerRequest;

$parentRouter = new Router();
$imageRouter = new Router();
$parentRouter->addRoute("/images/*.jpg", $imageRouter);
$parentRouter->addRoute("/images/*.png", $imageRouter); // you also can add multiple routes to the same target

$request = ServerRequest::fromGlobals();
$response = $parentRouter->handle($request);
(new ResponseSender())->sendResponse($response, $request);
```

## Installation

This library is available through [Packagist](https://packagist.org/packages/codeinchq/lib-router) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinchq/lib-url
```


## Dependencies 

* [PHP 7.2](http://php.net/releases/7_2_0.php)
* [`psr/http-message`](https://packagist.org/packages/psr/http-message) for the standard PSR7 objects interfaces ;
* [`psr/http-server-middleware`](https://packagist.org/packages/psr/http-server-middleware) for the PSR15 middleware interface ;
* [`psr/http-server-handler`](https://packagist.org/packages/psr/http-server-handler) for the PSR15 request handler interface ;
* [`guzzlehttp/psr7`](https://packagist.org/packages/guzzlehttp/psr7) is it's PSR7 implementation of the PSR7 `Request`, `ServerRequest` and `Response` objects.

**Recommended library:**
* [`codeinchq/lib-psr7responsesender`](https://packagist.org/packages/codeinchq/lib-psr7responsesender) recommended to stream the PSR7 responses to the web browser ;
* [`hansott/psr7-cookies`](https://packagist.org/packages/hansott/psr7-cookies) recommended to add cookies to the PSR7. responses.


## License 
This library is published under the MIT license (see the [`LICENSE`](https://github.com/codeinchq/lib-gui/blob/master/LICENSE) file).


