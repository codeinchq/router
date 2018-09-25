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
namespace CodeInc\Router;
use CodeInc\Router\RequestHandlersInstantiator\RequestHandlersInstantiatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class StaticInstantiatorRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class StaticInstantiatorRouter implements MiddlewareInterface
{
    /**
     * @var string[]
     */
    private $routes = [];

    /**
     * @var RequestHandlersInstantiatorInterface
     */
    private $requestHandlersInstantiator;

    /**
     * StaticInstantiatorRouter constructor.
     *
     * @param RequestHandlersInstantiatorInterface $requestHandlersInstantiator
     */
    public function __construct(RequestHandlersInstantiatorInterface $requestHandlersInstantiator)
    {
        $this->requestHandlersInstantiator = $requestHandlersInstantiator;
    }

    /**
     * Adds a request handler.
     *
     * @param string $route
     * @param string $requestHandlerClass
     * @throws RouterException
     */
    public function addRequestHandler(string $route, string $requestHandlerClass):void
    {
        if (!is_subclass_of($requestHandlerClass, RoutableRequestHandlerInterface::class)) {
            throw RouterException::notARequestHandler($requestHandlerClass);
        }
        $realRoute = strtolower($route);
        if (isset($this->routes[$realRoute])) {
            throw RouterException::duplicateRoute($route);
        }
        $this->routes[$realRoute] = $requestHandlerClass;
    }

    /**
     * Adds a routable request handler.
     *
     * @param string $routableRequestHandlerClass
     * @throws RouterException
     */
    public function addRoutableRequestHandler(string $routableRequestHandlerClass):void
    {
        if (!is_subclass_of($routableRequestHandlerClass, RoutableRequestHandlerInterface::class)) {
            throw RouterException::notARoutableRequestHandler($routableRequestHandlerClass);
        }
        /** @var RoutableRequestHandlerInterface $routableRequestHandlerClass */
        /** @noinspection PhpStrictTypeCheckingInspection */
        $this->addRequestHandler($routableRequestHandlerClass::getRoute(), $routableRequestHandlerClass);
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        $requestRoute = strtolower($request->getUri()->getPath());

        // if there is a direct route matching the request
        if (isset($this->routes[$requestRoute])) {
            $handler = $this->requestHandlersInstantiator->instantiate($this->routes[$requestRoute]);
        }

        // if there is a pattern route matching the request
        else {
            foreach ($this->routes as $route => $requestHandlerClass) {
                if (fnmatch($route, $requestRoute)) {
                    $handler = $this->requestHandlersInstantiator->instantiate($requestHandlerClass);
                    break;
                }
            }
        }

        return $handler->handle($request);
    }
}