<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material is strictly forbidden unless prior    |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     05/03/2018
// Time:     11:53
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router\Resolvers;
use CodeInc\Router\Exceptions\DuplicateRouteException;
use CodeInc\Router\Exceptions\NotARequestHandlerException;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class StaticHandlerResolver
 *
 * @package CodeInc\Router\Resolvers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class StaticHandlerResolver implements HandlerResolverInterface
{
    /**
     * @var string[]
     */
    private $routes = [];

    /**
     * StaticHandlerResolver constructor.
     *
     * @param iterable|null $routes
     */
    public function __construct(?iterable $routes = null)
    {
        if ($routes !== null) {
            $this->addRoutes($routes);
        }
    }

    /**
     * Adds multiple routes to request handlers.
     *
     * @param iterable $routes
     */
    public function addRoutes(iterable $routes):void
    {
        foreach ($routes as $route => $handlerClass) {
            $this->addRoute($route, $handlerClass);
        }
    }

    /**
     * Adds a route to a request handler.
     *
     * @param string $route URI path (supports shell patterns)
     * @param string $handlerClass
     */
    public function addRoute(string $route, string $handlerClass):void
    {
        if (!is_subclass_of($handlerClass, RequestHandlerInterface::class)) {
            throw new NotARequestHandlerException($handlerClass);
        }
        if (isset($this->routes[$route])) {
            throw new DuplicateRouteException($route, $handlerClass);
        }
        $this->routes[$route] = $handlerClass;
    }

    /**
     * @return string[]
     */
    public function getRoutes():array
    {
        return $this->routes;
    }

    /**
     * @inheritdoc
     * @param string $route
     * @return null|string
     */
    public function getHandlerClass(string $route):?string
    {
        foreach ($this->routes as $handlerRoute => $handlerClass) {
            if (fnmatch($handlerRoute, $route, FNM_CASEFOLD)) {
                return $handlerClass;
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     * @param string $handlerClass
     * @return null|string
     */
    public function getHandlerRoute(string $handlerClass):?string
    {
        foreach ($this->routes as $route => $class) {
            if ($handlerClass == $class) {
                return $route;
            }
        }
        return null;
    }
}