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
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use CodeInc\Psr7Responses\NotFoundResponse;
use CodeInc\Router\Exceptions\ControllerHandlingException;
use CodeInc\Router\Exceptions\DuplicateRouteException;
use CodeInc\Router\Exceptions\NotAControllerException;
use CodeInc\Router\Instantiators\DefaultInstantiator;
use CodeInc\Router\Instantiators\InstantiatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;


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
        $this->instantiator = $instantiator ?? new DefaultInstantiator();
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
        foreach ($routes as $route => $controllerClass) {
            $this->addRoute($route, $controllerClass);
        }
    }

    /**
     * @inheritdoc
     */
    public function canHandle(ServerRequestInterface $request):bool
    {
        return $this->getControllerClass($request) !== null;
    }

    /**
     * Processes a controller
     *
     * @param ServerRequestInterface $request
     * @return null|string
     */
    private function getControllerClass(ServerRequestInterface $request):?string
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
        if ($this->notFoundControllerClass) {
            return $this->notFoundControllerClass;
        }

        return null;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param bool $bypassMiddlewares
     * @throws NotAControllerException
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
                if ($controllerClass = $this->getControllerClass($request)) {
                    return $this->instantiator
                        ->instantiate($controllerClass, $request)
                        ->getResponse();
                }
                return new NotFoundResponse();
            }
            catch (\Throwable $exception) {
                throw new ControllerHandlingException(
                    $controllerClass ?? null,
                    $this,
                    null,
                    $exception
                );
            }
        }
    }
}