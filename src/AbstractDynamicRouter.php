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
// Date:     24/09/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class AbstractDynamicRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractDynamicRouter implements RouterInterface
{
    /**
     * Returns the router's URI prefix.
     *
     * @return string
     */
    abstract public function getUriPrefix():string;

    /**
     * Returns the requests handler's base namespace.
     *
     * @return string
     */
    abstract public function getRequestHandlersNamespace():string;

    /**
     * Instantiates a request handler.
     *
     * @param string $handlerClass
     * @return RequestHandlerInterface
     */
    abstract protected function instantiateHandler(string $handlerClass):RequestHandlerInterface;

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return null|RequestHandlerInterface
     * @throws RouterException
     */
    public function getHandler(ServerRequestInterface $request):?RequestHandlerInterface
    {
        $requestRoute = $request->getUri()->getPath();
        $uriPrefix = $this->getUriPrefix();
        if (substr($requestRoute, 0, strlen($uriPrefix)) == $uriPrefix) {
            $handlerClass = $this->getRequestHandlersNamespace()
                .str_replace('/', '\\', substr($requestRoute, strlen($uriPrefix)));
            if (class_exists($handlerClass)) {
                if (is_subclass_of($handlerClass, RequestHandlerInterface::class)) {
                    throw RouterException::notARequestHandler($handlerClass);
                }
                return $this->instantiateHandler($handlerClass);
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     * @param RequestHandlerInterface|string $requestHandler
     * @return string
     * @throws RouterException
     */
    public function getUri($requestHandler):string
    {
        $requestHandlersNamespace = $this->getRequestHandlersNamespace();
        if ($requestHandler instanceof RequestHandlerInterface) {
            $requestHandler = get_class($requestHandler);
        }
        else if (!is_subclass_of($requestHandler, RequestHandlerInterface::class)) {
            throw RouterException::notARequestHandler($requestHandler);
        }
        if (!substr($requestHandler, 0, strlen($requestHandlersNamespace)) == $requestHandler) {
            throw RouterException::notWithinNamespace($requestHandler, $requestHandlersNamespace);
        }
        return $this->getUriPrefix()
            .str_replace('\\', '/', substr($requestHandler, strlen($requestHandlersNamespace)));
    }
}