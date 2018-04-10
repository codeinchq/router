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
// Date:     14/03/2018
// Time:     12:16
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use Psr\Http\Server\MiddlewareInterface;


/**
 * Trait MiddlewaresTrait
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
trait MiddlewaresTrait
{
    /**
     * @var MiddlewareInterface[]
     */
    private $middlewares = [];

    /**
     * @var int
     */
    private $middlewaresPointer = 0;

    /**
     * Add a middleware
     *
     * @param MiddlewareInterface $middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware):void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return MiddlewareInterface[]
     */
    public function getMiddlewares():array
    {
        return $this->middlewares;
    }

    /**
     * Returns the next middleware from withing the internal stack.
     *
     * @return null|MiddlewareInterface
     */
    private function getNextMiddleware():?MiddlewareInterface
    {
        if (isset($this->middlewares[$this->middlewaresPointer])) {
            return $this->middlewares[$this->middlewaresPointer++];
        }
        return null;
    }

    /**
     * Resets the internal middlewares stack's pointer.
     */
    private function resetMiddlewarePointer():void
    {
        $this->middlewaresPointer = 0;
    }
}