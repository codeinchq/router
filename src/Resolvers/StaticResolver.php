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
use CodeInc\Router\Exceptions\ControllerDuplicateRouteException;
use CodeInc\Router\Exceptions\NotARequestHandlerException;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class StaticResolver
 *
 * @package CodeInc\Router\Resolvers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class StaticResolver implements ResolverInterface
{
    /**
     * @var string[]
     */
    private $routes = [];

    /**
     * Adds a controller.
     *
     * @param string $route URI path (supports shell patterns)
     * @param string $controllerClass
     */
    public function addRoute(string $route, string $controllerClass):void
    {
        if (!is_subclass_of($controllerClass, RequestHandlerInterface::class)) {
            throw new NotARequestHandlerException($controllerClass);
        }
        $realRoute = strtolower($route);
        if (isset($this->routes[$realRoute])) {
            throw new ControllerDuplicateRouteException($route, $controllerClass);
        }
        $this->routes[$realRoute] = $controllerClass;
    }

    /**
     * Returns the controller in charge of handling a given request.
     *
     * @param string $route
     * @return null|string
     */
    public function getHandlerClass(string $route):?string
    {
        foreach ($this->routes as $controllerRoute => $controllerClass) {
            if (fnmatch($controllerRoute, $route, FNM_CASEFOLD)) {
                return $controllerClass;
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
        foreach ($this->routes as $route => $knownControllerClass) {
            if ($handlerClass == $knownControllerClass) {
                return $route;
            }
        }
        return null;
    }
}