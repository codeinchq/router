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
    public static function emptyBaseUri():self
    {
        return new self("The router's base URI can not be empty.",
            self::CODE_EMPTY_URI);
    }

    /**
     * @param string $controllerClass
     * @return RouterException
     */
    public static function notARequestHandler(string $controllerClass):self
    {
        return new self(sprintf("The class %s is not a PSR-7 request handler. "
            ."All PSR-7 request handlers must implement %s.", $controllerClass, RequestHandlerInterface::class),
            self::CODE_NOT_A_REQUEST_HANDLER);
    }

    /**
     * @param string $controllerClass
     * @return RouterException
     */
    public static function notARoutableRequestHandler(string $controllerClass):self
    {
        return new self(sprintf("The class %s is not a routable PSR-7 request handler. "
            ."All routable PSR-7 request handlers must implement %s.",
            $controllerClass, RoutableRequestHandlerInterface::class),
            self::CODE_NOT_A_ROUTABLE_REQUEST_HANDLER);
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
}