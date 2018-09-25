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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class AbstractStaticRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractStaticRouter extends AbstractRouter
{
    /**
     * Returns all the requests handler and their associated routes.
     *
     * @return iterable|string[]
     */
    abstract public function getHandlers():iterable;

    /**
     * Instantiates a request handler.
     *
     * @param string $requestHandlerClass
     * @return RequestHandlerInterface
     */
    abstract protected function instantiate(string $requestHandlerClass):RequestHandlerInterface;

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return null|RequestHandlerInterface
     */
    public function getHandler(ServerRequestInterface $request):?RequestHandlerInterface
    {
        $requestRoute = $request->getUri()->getPath();
        foreach ($this->getHandlers() as $route => $handlerClass) {
            if (fnmatch($route, $requestRoute, FNM_CASEFOLD)) {
                return $this->instantiate($handlerClass);
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     * @param string $requestHandlerClass
     * @return string|null
     */
    public function getHandlerUri(string $requestHandlerClass):?string
    {
        foreach ($this->getHandlers() as $route => $handlerClass) {
            if ($requestHandlerClass == $handlerClass) {
                return $route;
            }
        }
        return null;
    }
}