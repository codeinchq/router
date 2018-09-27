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
use CodeInc\Router\Controllers\ControllerInterface;
use Psr\Http\Message\ServerRequestInterface;


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
    public const CODE_EMPTY_REQUEST_HANDLER_NAMESPACE = 3;
    public const CODE_NOT_A_CONTROLLER = 4;
    public const CODE_NOT_WITHIN_NAMESPACE = 5;
    public const CODE_NO_REQUEST_HANDLER_FOUND = 6;
    public const CODE_CLASS_NOT_FOUND = 7;

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
    public static function emptyControllersNamespace():self
    {
        return new self("The controllers namespace can not be empty.",
            self::CODE_EMPTY_REQUEST_HANDLER_NAMESPACE);
    }

    /**
     * @param string $class
     * @return RouterException
     */
    public static function notAController(string $class):self
    {
        return new self(sprintf("The class %s is not a controller. "
            ."All controllers must implement %s.", $class, ControllerInterface::class),
            self::CODE_NOT_A_CONTROLLER);
    }

    /**
     * @param string $controllerClass
     * @param string $controllersNamespace
     * @return RouterException
     */
    public static function notWithinNamespace(string $controllerClass, string $controllersNamespace):self
    {
        return new self(sprintf("The controller '%s' is not within the namespace '%s'.",
            $controllerClass, $controllersNamespace),
            self::CODE_NOT_WITHIN_NAMESPACE);
    }

    /**
     * @param ServerRequestInterface $request
     * @return RouterException
     */
    public static function noRequestHandlerFound(ServerRequestInterface $request):self
    {
        return new self(sprintf("No controllers has been found to handle the request '%s'",
            $request->getUri()->getPath()),
            self::CODE_NO_REQUEST_HANDLER_FOUND);
    }
}