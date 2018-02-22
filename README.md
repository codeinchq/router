# Code Inc. router library

`lib-router` is a [PSR7](https://www.php-fig.org/psr/psr-7/) router library written in PHP 7.

A router is a component in charge of determining which code to execute to answer a request, to execute the selected code and then to stream the HTTP response to the web browser. 

**Here is how the router works :**
1. The router receives a request, often the request is built form the current web browser request using `ServerRequest::fromGlobals()`
2. The router searches for a compatible handler to process the request, in order to do so the path of the request's URI is compared with the known routes using `fnmatch()` ;
3. The router calls the code linked to the route with the PSR7 `Request` object as parameter ;
4. The router receives a PSR7 `Response` object returned by the executed code ;
5. The router send the response to the web browser.


## Usage

### Using the router

The `Router` class included in the library is able to mix various handlers to process routes. A handler is either a class or an instantiated object implementing `RoutableInterface` or a callable. The callable will received the PSR7 `Request` object as parameter and should return PSR7 `Response` object.

The routes are evaluated using [fnmatch()](http://php.net/manual/en/function.fnmatch.php) and are compatible with standard shell patterns. 

Note: you can also design you own router by implementing `RoutableInterface`.

```php
<?php
use CodeInc\Router\Router;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
 
$myRouter = new Router();

// a page can be a class name implementing RoutableInterface
$myRouter->addRoute("/", HomePage::class); 

// also an object implementing RoutableInterface
$myRouter->addRoute("/license.txt", new LicensePage); 

// routes are compatible with standard shell patterns
$myRouter->addRoute("/article-[0-9]/*", ArticlePage::class); 

// or even a callable
$myRouter->addRoute("/error404.html", function(RequestInterface $request):ResponseInterface { 
	return new Response(404, ["Content-Type" => "text/plain"], 
	    sprintf("The page %s is not found!", $request->getUri()->getPath())
    );
});

// is is possible to define where not found request should be routed
$myRouter->setNotFoundRoute("/error404.html");

// processing and sending the response
$request = ServerRequest::fromGlobals();
$response = $myRouter->processRequest($request);
$myRouter->sendResponse($response, $request); 

// Note: passing the request object to the send response 
// method allows the router to make sure the response 
// will be sent using the same version of the HTTP protocol
```



### Routable

A routable is any class implementing the `RoutableInterface`. Any routable can be plugged into a router. A router itself is routable. However if you can to route multiple routers you need to use the `RouterAggregate` class.

```php
<?php
use CodeInc\Router\RoutableInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Response;

// An example routable class 
class HomePage implements RoutableInterface {
	public function processRequest(RequestInterface $request):ResponseInterface {
        return new Response(200, ["Content-Type" => "text/plain; charset=utf-8"], 
            "Hello world!\nThe is the home page"
        );
    }
}

// Another routable class sending a local file
class LicensePage implements RoutableInterface {
    public function processRequest(RequestInterface $request):ResponseInterface {
        return new Response(200,
            ["Content-Disposition" => "attachment; filename=\"license.txt\"",
                "Content-Type" => "text/plain; charset=utf-8"],
            \GuzzleHttp\Psr7\stream_for("/path/to/an/example/file.txt")
        );
    }
}
```

### Aggregating routers

You can aggregate multiple router usign the `RouterAggregate` class. This can come handy for large project with independent modules having their own internal router or to mix different routers, for instance a web page router and a web asset router. 

```php
<?php
use CodeInc\Router\Router;
use CodeInc\Router\RouterAggregate;
use GuzzleHttp\Psr7\ServerRequest;

// creating multiple routers 
$router1 = new Router();
$router2 = new Router();
$router3 = new Router();

$routerAggregte1 = new RouterAggregate();
$routerAggregte1->addRouter($router1);
$routerAggregte1->addRouter($router2);

$routerAggregte2 = new RouterAggregate();
$routerAggregte2->addRouter($routerAggregte1);

$request = ServerRequest::fromGlobals();
$response = $routerAggregte2->processRequest($request);
$routerAggregte2->sendResponse($response, $request);
```

You can also aggregate a router directly in another route. In this case only the attribute route will be passed to the router. Unlike the `RouterAggregate` class, a router will no call the `canProcessRequest()` before calling `processRequest()`  and all the requests matching the route will be passed to the sub router.

```php
<?php 
use CodeInc\Router\Router;
use GuzzleHttp\Psr7\ServerRequest;

$parentRouter = new Router();
$imageRouter = new Router();
$parentRouter->addRoute("/images/*.jpg", $imageRouter);
$parentRouter->addRoute("/images/*.png", $imageRouter); // you also can add multiple routes to the same target

$request = ServerRequest::fromGlobals();
$response = $parentRouter->processRequest($request);
$parentRouter->sendResponse($response, $request);
```


## Dependencies 

* [`psr/http-message`](https://packagist.org/packages/psr/http-message) is providing the standard PSR7 objets interfaces ;
* [`guzzlehttp/psr7`](https://packagist.org/packages/guzzlehttp/psr7) is providing the PSR `Request`, `ServerRequest` and `Response` objects.

**Recommended libraries:**
* [`hansott/psr7-cookies`](https://packagist.org/packages/hansott/psr7-cookies) is recommended to add cookie to the PSR7 responses.

The PSR7 `Request` and `Response` objects used by the library are the [Guzzle implementation](https://github.com/guzzle/psr7).


## License 
This library is published under the MIT license (see the [`LICENSE`](https://github.com/codeinchq/lib-gui/blob/master/LICENSE) file).


