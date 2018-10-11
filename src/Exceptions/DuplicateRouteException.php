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
// Date:     28/09/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\Exceptions;

/**
 * Class DuplicateRouteException
 *
 * @package CodeInc\Router\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
final class DuplicateRouteException extends \LogicException implements RouterException
{
    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $handlerClass;

    /**
     * DuplicateRouteException constructor.
     *
     * @param string $route
     * @param string $handlerClass
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $route, string $handlerClass, int $code = 0, \Throwable $previous = null)
    {
        $this->route = $route;
        $this->handlerClass = $handlerClass;
        parent::__construct(
            sprintf("A handler is already registered for the route '%s'.", $route),
            $code,
            $previous
        );
    }

    /**
     * @return string
     */
    public function getRoute():string
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getHandlerClass():string
    {
        return $this->handlerClass;
    }
}