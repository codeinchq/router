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
namespace CodeInc\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class AbstractRouterAggregator
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractRouterAggregator extends AbstractRouter
{
    /**
     * Returns the routers iterator.
     *
     * @return iterable|RouterInterface[]
     */
    abstract protected function getRouters():iterable;

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return null|RequestHandlerInterface
     */
    public function getHandler(ServerRequestInterface $request):?RequestHandlerInterface
    {
        foreach ($this->getRouters() as $router) {
            if (($handler = $router->getHandler($request))) {
                return $handler;
            }
        }
        return null;
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