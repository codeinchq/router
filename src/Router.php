<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     05/03/2018
// Time:     11:53
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use CodeInc\Psr7Responses\NotFoundResponse;
use CodeInc\Router\Exceptions\ControllerHandlingException;
use CodeInc\Router\Exceptions\DuplicateRouteException;
use CodeInc\Router\Instantiators\DefaultInstantiator;
use CodeInc\Router\Instantiators\InstantiatorInterface;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class Router
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Router implements RouterInterface
{
    use MiddlewaresTrait;

    /**
     * @var string[]
     */
    private $routes = [];

    /**
     * @var string|RequestHandlerInterface
     */
    private $notFoundController;

    /**
     * @var InstantiatorInterface
     */
    private $instantiator;

    /**
     * Router constructor.
     *
     * @param InstantiatorInterface|null $instantiator
     */
    public function __construct(?InstantiatorInterface $instantiator = null)
    {
        $this->instantiator = $instantiator;
    }

    /**
     * Returns the instantiator.
     *
     * @return InstantiatorInterface
     */
    private function getInstantiator():InstantiatorInterface
    {
        if (!$this->instantiator instanceof InstantiatorInterface) {
            $this->instantiator = new DefaultInstantiator();
        }
        return $this->instantiator;
    }

    /**
     * Sets the not found controller class.
     *
     * @param string|RequestHandlerInterface $notFoundController
     */
    public function setNotFoundController($notFoundController):void
    {
        $this->notFoundController = $notFoundController;
    }

    /**
     * Adds a route to a controller.
     *
     * @param string $route
     * @param string|RequestHandlerInterface $controller
     * @throws DuplicateRouteException
     */
    public function addRoute(string $route, $controller):void
    {
        if (isset($this->routes[$route])) {
            throw new DuplicateRouteException($route, $this);
        }
        $this->routes[strtolower($route)] = $controller;
    }

    /**
     * Adds multiple routes using an iterable.
     *
     * @param iterable $routes
     * @throws DuplicateRouteException
     */
    public function addRoutes(iterable $routes):void
    {
        foreach ($routes as $route => $controller) {
            $this->addRoute($route, $controller);
        }
    }

    /**
     * @inheritdoc
     */
    public function canHandle(ServerRequestInterface $request):bool
    {
        return $this->getController($request) !== null;
    }

    /**
     * Processes a controller
     *
     * @param ServerRequestInterface $request
     * @return null|string|RequestHandlerInterface
     */
    private function getController(ServerRequestInterface $request)
    {
        $requestRoute = strtolower($request->getUri()->getPath());

        // if there is a direct route matching the request
        if (isset($this->routes[$requestRoute])) {
            return $this->routes[$requestRoute];
        }

        // if there is a pattern route matching the request
        foreach ($this->routes as $route => $controller) {
            if (fnmatch($route, $requestRoute)) {
                return $controller;
            }
        }

        // not found controller
        if ($this->notFoundController) {
            return $this->notFoundController;
        }

        return null;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param bool $bypassMiddlewares
     * @throws ControllerHandlingException
     */
    public function handle(ServerRequestInterface $request,
        bool $bypassMiddlewares = false):ResponseInterface
    {
        // if some middlewares are set
        if (!$bypassMiddlewares && isset($this->middlewares[$this->middlewaresIndex])) {
            return $this->middlewares[$this->middlewaresIndex++]->process($request, $this);
        }

        // else returns the controller's response
        else {
            if ($this->middlewaresIndex) {
                $this->middlewaresIndex = 0;
            }
            try {
                if ($controller = $this->getController($request)) {
                    // instantiating the controller if not instantiated
                    if (!$controller instanceof RequestHandlerInterface) {
                        $controller = $this->getInstantiator()->instantiate($controller);
                    }

                    // handles the request with the controller
                    return $controller->handle($request);
                }
                return new NotFoundResponse();
            }
            catch (\Throwable $exception) {
                throw new ControllerHandlingException(
                    $controller ?? null,
                    $this,
                    null,
                    $exception
                );
            }
        }
    }

    /**
     * Alias of handle() for the current request. The current request is built using
     * Guzzle PSR-7 implementation (ServerRequest::fromGlobals()).
     *
     * @param bool $bypassMiddlewares
     * @return ResponseInterface
     * @throws ControllerHandlingException
     */
    public function handleCurrentRequest(bool $bypassMiddlewares = false):ResponseInterface
    {
        return $this->handle(ServerRequest::fromGlobals(), $bypassMiddlewares);
    }
}