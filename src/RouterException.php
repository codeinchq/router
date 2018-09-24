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
    public const CODE_NOT_A_CONTROLLER = 3;
    public const CODE_NOT_WITHIN_NAMESPACE = 4;

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
    public static function notAController(string $controllerClass):self
    {
        return new self(sprintf("The class %s is not a controller. All controller must implement %s.",
            $controllerClass, RequestHandlerInterface::class), self::CODE_NOT_A_CONTROLLER);
    }

    /**
     * @param string $controllerClass
     * @param string $controllersBaseNamespace
     * @return RouterException
     */
    public static function notWithinNamespace(string $controllerClass, string $controllersBaseNamespace):self
    {
        return new self(sprintf("The controller %s is not within the controllers base namespace '%s'.",
            $controllerClass, $controllersBaseNamespace), self::CODE_NOT_WITHIN_NAMESPACE);
    }
}