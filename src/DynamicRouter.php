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
 * Class DynamicRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class DynamicRouter implements RouterInterface
{
    /**
     * @var string
     */
    private $requestHandlersNamespace;

    /**
     * @var string
     */
    private $uriPrefix;

    /**
     * DynamicRouter constructor.
     *
     * @param string $requestHandlersNamespace
     * @param string $uriPrefix
     * @throws RouterException
     */
    public function __construct(string $requestHandlersNamespace, string $uriPrefix)
    {
        if (empty($uriPrefix)) {
            throw RouterException::emptyUriPrefix();
        }
        if (empty($requestHandlersNamespace)) {
            throw RouterException::emptyRequestHandlersNamespace();
        }
        $this->requestHandlersNamespace = $requestHandlersNamespace;
        $this->uriPrefix = $uriPrefix;
    }

    /**
     * Returns the router's URI prefix.
     *
     * @return string
     */
    public function getUriPrefix():string
    {
        return $this->uriPrefix;
    }

    /**
     * Returns the requests handler's base namespace.
     *
     * @return string
     */
    public function getRequestHandlersNamespace():string
    {
        return $this->requestHandlersNamespace;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return null|string
     * @throws RouterException
     */
    public function getHandlerClass(ServerRequestInterface $request):?string
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
                return $handlerClass;
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