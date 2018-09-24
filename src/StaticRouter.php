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
use CodeInc\Router\Interfaces\RouterInterface;
use CodeInc\Router\Interfaces\ControllerInstantiatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class StaticRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class StaticRouter implements RouterInterface
{
    /**
     * @var string[]
     */
    private $routes = [];

    /**
     * @var ControllerInstantiatorInterface
     */
    private $controllerInstantiator;

    /**
     * @var string
     */
    private $uriPrefix;

    /**
     * StaticRouter constructor.
     *
     * @param ControllerInstantiatorInterface $controllerInstantiator
     * @param null|string $uriPrefix Prefix added to the controllers' URI
     */
    public function __construct(ControllerInstantiatorInterface $controllerInstantiator, ?string $uriPrefix = null)
    {
        $this->controllerInstantiator = $controllerInstantiator;
        $this->uriPrefix = $uriPrefix;
    }

    /**
     * @return string
     */
    public function getUriPrefix():string
    {
        return $this->uriPrefix;
    }

    /**
     * Adds a route to a controller.
     *
     * @param string $uri
     * @param string $controllerClass
     * @throws RouterException
     */
    public function addController(string $uri, string $controllerClass):void
    {
        $fullUri = strtolower($this->uriPrefix.$uri);
        if (isset($this->routes[$fullUri])) {
            throw RouterException::duplicateRoute($uri);
        }
        $this->routes[$fullUri] = $controllerClass;
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
            $handler = $this->controllerInstantiator->instantiate($this->routes[$requestRoute]);
        }

        // if there is a pattern route matching the request
        else {
            foreach ($this->routes as $route => $controllerClass) {
                if (fnmatch($route, $requestRoute)) {
                    $handler = $this->controllerInstantiator->instantiate($controllerClass);
                    break;
                }
            }
        }

        return $handler->handle($request);
    }
}