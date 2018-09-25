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
use CodeInc\Router\RequestHandlersInstantiator\RequestHandlersInstantiatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class DynamicRouter
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class DynamicRouter implements MiddlewareInterface
{
    /**
     * @var string
     */
    private $requestHandlersBaseNamespace;

    /**
     * @var string
     */
    private $uriPrefix;

    /**
     * @var RequestHandlersInstantiatorInterface
     */
    private $controllerInstantiator;

    /**
     * DynamicRouter constructor.
     *
     * @param string $requestHandlersBaseNamespace
     * @param RequestHandlersInstantiatorInterface $requestHandlersInstantiator
     * @param string $uriPrefix
     * @throws RouterException
     */
    public function __construct(string $requestHandlersBaseNamespace,
        RequestHandlersInstantiatorInterface $requestHandlersInstantiator, string $uriPrefix = '/')
    {
        if (empty($uriPrefix)) {
            throw RouterException::emptyBaseUri();
        }
        $this->requestHandlersBaseNamespace = $requestHandlersBaseNamespace;
        $this->controllerInstantiator = $requestHandlersInstantiator;
        $this->uriPrefix = $uriPrefix;
    }

    /**
     * @return string
     */
    public function getUriPrefix():string
    {
        return $this->uriPrefix;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws RouterException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        $requestUri = $request->getUri()->getPath();
        if (substr($requestUri, 0, strlen($this->uriPrefix)) == $this->uriPrefix) {
            $controllerClass = $this->requestHandlersBaseNamespace
                .str_replace('/', '\\', substr($requestUri, strlen($this->uriPrefix)));
            if (class_exists($controllerClass)) {
                if (is_subclass_of($controllerClass, RequestHandlerInterface::class)) {
                    throw RouterException::notARequestHandler($controllerClass);
                }
                $handler = $this->controllerInstantiator->instantiate($controllerClass);
            }
        }

        return $handler->handle($request);
    }

    /**
     * Returns the URI for a given controller within the current namespace.
     *
     * @param string|RequestHandlerInterface $requestHandler
     * @return string
     * @throws RouterException
     */
    public function getControllerUri($requestHandler):string
    {
        if ($requestHandler instanceof RequestHandlerInterface) {
            $requestHandler = get_class($requestHandler);
        }
        else if (!is_subclass_of($requestHandler, RequestHandlerInterface::class)) {
            throw RouterException::notARequestHandler($requestHandler);
        }
        if (!substr($requestHandler, 0, strlen($this->requestHandlersBaseNamespace)) == $this->requestHandlersBaseNamespace) {
            throw RouterException::notWithinNamespace($requestHandler, $this->requestHandlersBaseNamespace);
        }
        return $this->uriPrefix
            .str_replace('\\', '/', substr($requestHandler, strlen($this->requestHandlersBaseNamespace)));
    }
}