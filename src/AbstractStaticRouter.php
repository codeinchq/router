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
abstract class AbstractStaticRouter implements RouterInterface
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
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        $requestRoute = $request->getUri()->getPath();
        foreach ($this->getHandlers() as $route => $handlerClass) {
            if (fnmatch($route, $requestRoute, FNM_CASEFOLD)) {
                $handler = $this->instantiate($handlerClass);
                break;
            }
        }

        return $handler->handle($request);
    }

    /**
     * @inheritdoc
     * @param string $requestHandlerClass
     * @return string
     * @throws RouterException
     */
    public function getHandlerUri(string $requestHandlerClass):string
    {
        foreach ($this->getHandlers() as $route => $handlerClass) {
            if ($requestHandlerClass == $handlerClass) {
                return $route;
            }
        }
        throw RouterException::noRouteFound($requestHandlerClass);
    }
}