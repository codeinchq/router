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
// Date:     04/03/2018
// Time:     13:15
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RouterException
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RouterException extends \Exception
{
    public const CODE_DUPLICATE_ROUTE = 1;
    public const CODE_EMPTY_URI = 2;
    public const CODE_NOT_A_REQUEST_HANDLER = 3;
    public const CODE_NOT_A_ROUTABLE_REQUEST_HANDLER = 4;
    public const CODE_NOT_WITHIN_NAMESPACE = 5;
    public const CODE_NO_ROUTE_FOUND = 6;
    public const CODE_NOT_A_ROUTER = 7;
    public const CODE_NOT_A_MIDDLEWARE = 8;
    public const CODE_EMPTY_ROUTE = 9;
    public const CODE_INVALID_ROUTE = 9;

    /**
     * @param string $route
     * @return RouterException
     */
	public static function duplicateRoute(string $route):self
    {
        return new self(sprintf("A controller already exists for the route '%s'.", $route),
            self::CODE_DUPLICATE_ROUTE);
    }

    /**
     * @return RouterException
     */
    public static function emptyUriPrefix():self
    {
        return new self("The dynamic router's URI prefix can not be empty.",
            self::CODE_EMPTY_URI);
    }

    /**
     * @return RouterException
     */
    public static function emptyRequestHandlersNamespace():self
    {
        return new self("The dynamic router's request handler namespace can not be empty.",
            self::CODE_EMPTY_URI);
    }

    /**
     * @param string $requestHandlerClass
     * @return RouterException
     */
    public static function notARequestHandler(string $requestHandlerClass):self
    {
        return new self(sprintf("The class %s is not a PSR-7 request handler. "
            ."All PSR-7 request handlers must implement %s.", $requestHandlerClass, RequestHandlerInterface::class),
            self::CODE_NOT_A_REQUEST_HANDLER);
    }

    /**
     * @param string $requestHandlerClass
     * @param string $controllersBaseNamespace
     * @return RouterException
     */
    public static function notWithinNamespace(string $requestHandlerClass, string $controllersBaseNamespace):self
    {
        return new self(sprintf("The PSR-7 request handler %s is not within the base namespace '%s'.",
            $requestHandlerClass, $controllersBaseNamespace), self::CODE_NOT_WITHIN_NAMESPACE);
    }

    /**
     * @param string $requestHandlerClass
     * @return RouterException
     */
    public static function noRouteFound(string $requestHandlerClass):self
    {
        return new self(sprintf("No route is available for the request handler '%s'.", $requestHandlerClass),
            self::CODE_NO_ROUTE_FOUND);
    }

    /**
     * @param mixed|object|string $item
     * @return RouterException
     */
    public static function notARouter($item):self
    {
        return new self(sprintf("The item '%s' is not a router. All routers must implement '%s'.",
            is_object($item) ? get_class($item) : (string)$item, RouterInterface::class),
            self::CODE_NOT_A_ROUTER);
    }

    /**
     * @param mixed|object|string $item
     * @return RouterException
     */
    public static function notAMiddleware($item):self
    {
        return new self(sprintf("The item '%s' is not a middleware. All middleware must implement '%s'.",
            is_object($item) ? get_class($item) : (string)$item, MiddlewareInterface::class),
            self::CODE_NOT_A_MIDDLEWARE);
    }

    /**
     * @param string $requestHandlerClass
     * @return RouterException
     */
    public static function emptyRoute(string $requestHandlerClass):self
    {
        return new self(sprintf("The route to the request handler '%s' can not be empty.", $requestHandlerClass),
            self::CODE_EMPTY_ROUTE);
    }

    /**
     * @param mixed $route
     * @param string $requestHandlerClass
     * @return RouterException
     */
    public static function invalidRoute($route, string $requestHandlerClass):self
    {
        return new self(sprintf("The route '%s' to the request handler '%s' is not valid.",
            (string)$route, $requestHandlerClass), self::CODE_INVALID_ROUTE);
    }
}