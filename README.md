# Code Inc. router library

`lib-router` is a [PSR7](https://www.php-fig.org/psr/psr-7/) router library written in PHP 7. A router is a component in charge of determining which code to execute to answer a request, to execute the selected code and then to stream the HTTP response to the web browser. It knowns a list of routes and their matching handlers. A handler can be anything [`callable`](http://php.net/manual/en/language.types.callable.php). 

**Here is how the router works :**
1. The router receives a request (often the request is built form the current web browser request using `ServerRequest::fromGlobals()`)
2. The router searches for a compatible handler to process the request, in order to do so the path of the request's URI is compared with the known routes using `fnmatch()`;
3. The router calls the handler linked to the route with the [PSR7 `Request`](https://www.php-fig.org/psr/psr-7/#32-psrhttpmessagerequestinterface) object as parameter;
4. The router receives a [PSR7 `Response`](https://www.php-fig.org/psr/psr-7/#33-psrhttpmessageresponseinterface) object returned by the handler;
5. The router send the response to the web browser.


## Usage

### Using the router

The `Router` class is able to mix various handlers to process routes. A handler is either a class or an instantiated object implementing `RoutableInterface` or a [`callable`](http://php.net/manual/en/language.types.callable.php). The `callable` will received the PSR7 `Request` object as parameter and should return PSR7 `Response` object.

The routes are evaluated in order using [`fnmatch()`](http://php.net/manual/en/function.fnmatch.php) and are compatible with [standard shell patterns](https://www.gnu.org/software/findutils/manual/html_node/find_html/Shell-Pattern-Matching.html). A directly matching route will always be winning over a pattern. For instance for the `/article-3.html` request,
the `/article-3.html` route will win over the `/article-[0-9].html` pattern route even if added after.

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

// an object implementing RoutableInterface
$myRouter->addRoute("/license.txt", new LicensePage); 

// or even a callable
$myRouter->addRoute("/error404.html", function(RequestInterface $request):ResponseInterface { 
	return new Response(404, ["Content-Type" => "text/plain"], 
	    sprintf("The page %s is not found!", $request->getUri()->getPath())
    );
});

// routes are compatible with standard shell patterns
$myRouter->addRoute("/article-[0-9]/*", ArticlePage::class); 

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



### Routable classes

A routable is a class implementing the `RoutableInterface`. Any routable can be plugged into a router. A router itself is routable. However, if you need to aggregate multiple routers you should use the `RouterAggregate` class ([see below](https://github.com/CodeIncHQ/lib-router#aggregating-routers)).

```php
<?php
use CodeInc\Router\RoutableInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Response;

// an example routable class 
class HomePage implements RoutableInterface {
	public function processRequest(RequestInterface $request):ResponseInterface {
        return new Response(200, ["Content-Type" => "text/plain; charset=utf-8"], 
            "Hello world!\nThe is the home page"
        );
    }
}

// another routable class sending a local file
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

You can aggregate multiple router usign the `RouterAggregate` class. This can come handy for large projects with independent modules having their own internal router or to mix different router types, for instance a web page router and a web asset router.

The order in which you aggregate routers is important. When asked to process a request, `RouterAggregate` will call the first router capable of processing the request (the first returning `true` for `canProcessRequest()`).  

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
A router is a routable, so you can aggregate a router directly in another router. In this case the behavior is different than when using `RouterAggregate`: the sub router will be called only (and always) for it's matching route (the parent router will never asked the sub router if it can process the route through `canProcessRequest()`)

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

* this library requires [PHP 7.2](http://php.net/releases/7_2_0.php)
* it is using [`psr/http-message`](https://packagist.org/packages/psr/http-message) for the standard PSR7 objects interfaces ;
* it is using [`guzzlehttp/psr7`](https://packagist.org/packages/guzzlehttp/psr7) is it's PSR7 implementation through the `Request`, `ServerRequest` and `Response` objects.

**Recommended libraries:**
* the [`hansott/psr7-cookies`](https://packagist.org/packages/hansott/psr7-cookies) ibrary is strongly recommended to add cookies to the PSR7 responses.


## License 
This library is published under the MIT license (see the [`LICENSE`](https://github.com/codeinchq/lib-gui/blob/master/LICENSE) file).


