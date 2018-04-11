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
use CodeInc\Router\Exceptions\ControllerHandlingException;
use CodeInc\Router\Exceptions\DuplicateRouteException;
use CodeInc\Router\Instantiators\DefaultInstantiator;
use CodeInc\Router\Instantiators\InstantiatorInterface;
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
    /**
     * @var string[]
     */
    private $routes = [];

    /**
     * @var string
     */
    private $notFoundControllerClass;

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
     * @param string $notFoundControllerClass
     */
    public function setNotFoundController(string $notFoundControllerClass):void
    {
        $this->notFoundControllerClass = $notFoundControllerClass;
    }

    /**
     * Adds a route to a controller.
     *
     * @param string $route
     * @param string $controllerClass
     * @throws DuplicateRouteException
     */
    public function addRoute(string $route, string $controllerClass):void
    {
        if (isset($this->routes[$route])) {
            throw new DuplicateRouteException($route, $this);
        }
        $this->routes[strtolower($route)] = $controllerClass;
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
    public function canProcess(ServerRequestInterface $request):bool
    {
        return $this->getController($request) !== null;
    }

    /**
     * Processes a controller
     *
     * @param ServerRequestInterface $request
     * @return null|string
     */
    private function getController(ServerRequestInterface $request):?string
    {
        $requestRoute = strtolower($request->getUri()->getPath());

        // if there is a direct route matching the request
        if (isset($this->routes[$requestRoute])) {
            return $this->routes[$requestRoute];
        }

        // if there is a pattern route matching the request
        foreach ($this->routes as $route => $controllerClass) {
            if (fnmatch($route, $requestRoute)) {
                return $controllerClass;
            }
        }

        // not found controller
        if ($this->notFoundControllerClass) {
            return $this->notFoundControllerClass;
        }

        return null;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @throws ControllerHandlingException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        try {
            // if a controller is available for the given request
            if ($controllerClass = $this->getController($request)) {
                // instantiating the controller & processing the request
                return $this->getInstantiator()->instantiate($controllerClass, $request)->process();
            }
        }
        catch (\Throwable $exception) {
            throw new ControllerHandlingException(
                isset($controllerClass)
                    ? (is_object($controllerClass) ? get_class($controllerClass) : $controllerClass)
                    : null,
                $this, 0, $exception
            );
        }

        // else using the given handler
        return $handler->handle($request);
    }
}