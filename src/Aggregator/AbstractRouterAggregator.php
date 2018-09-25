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
// Date:     25/09/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\Aggregator;
use CodeInc\MiddlewareDispatcher\AbstractDispatcher;
use CodeInc\MiddlewareDispatcher\Dispatcher;
use CodeInc\MiddlewareDispatcher\DispatcherMiddlewareWrapper;
use CodeInc\MiddlewareDispatcher\MiddlewareWrapper\AbstractDispatcherMiddlewareWrapper;
use CodeInc\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RouterAggregator
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractRouterAggregator implements RouterInterface
{
    /**
     * Returns the routers iterator.
     *
     * @return iterable|RouterInterface[]
     */
    abstract protected function getRouters():iterable;

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \CodeInc\MiddlewareDispatcher\DispatcherException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        return (new DispatcherMiddlewareWrapper(
            new Dispatcher(
                $this->getRouters()
            )
        ))->process($request, $handler);
    }

    /**
     * @inheritdoc
     * @param string $requestHandlerClass
     * @return null|string
     */
    public function getHandlerUri(string $requestHandlerClass):?string
    {
        foreach ($this->getRouters() as $router) {
            if ($handlerUri = $router->getHandlerUri($requestHandlerClass)) {
                return $handlerUri;
            }
        }
        return null;
    }
}