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
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class StaticRouter
 *
 * @package CodeInc\Router\StaticRouter
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class StaticRouter extends AbstractStaticRouter
{
    /**
     * @var string[]
     */
    private $handlers = [];

    /**
     * Adds a request handler.
     *
     * @param string $route URI path (supports shell patterns)
     * @param string $requestHandlerClass
     * @throws RouterException
     */
    public function addRequestHandler(string $route, string $requestHandlerClass):void
    {
        if (!is_subclass_of($requestHandlerClass, RequestHandlerInterface::class)) {
            throw RouterException::notARequestHandler($requestHandlerClass);
        }
        $realRoute = strtolower($route);
        if (isset($this->handlers[$realRoute])) {
            throw RouterException::duplicateRoute($route);
        }
        $this->handlers[$realRoute] = $requestHandlerClass;
    }

    /**
     * @inheritdoc
     * @return iterable|string[]
     */
    public function getHandlers():iterable
    {
        return $this->handlers;
    }

    /**
     * @param string $handlerClass
     * @return RequestHandlerInterface
     * @throws RouterException
     */
    protected function instantiateHandler(string $handlerClass):RequestHandlerInterface
    {
        if (!class_exists($handlerClass)) {
            throw RouterException::handlerClassNotFound($handlerClass);
        }
        $handler = new $handlerClass;
        if ($handler instanceof RequestHandlerInterface) {
            throw RouterException::notARequestHandler($handlerClass);
        }
        return $handler;
    }
}