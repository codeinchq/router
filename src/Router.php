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
use CodeInc\Router\Exceptions\DuplicateRouteException;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class Router
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Router extends AbstractInstantiatorRouter
{
    /**
     * @var string[]
     */
    private $routes = [];

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
     * @param ServerRequestInterface $request
     * @return null|string
     */
    protected function getControllerClass(ServerRequestInterface $request):?string
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

        return null;
    }
}