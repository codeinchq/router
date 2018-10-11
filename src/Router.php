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
// Date:     08/10/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router;
use CodeInc\Router\Exceptions\ControllerHandlingException;
use CodeInc\Router\Exceptions\ControllerInstantiatingException;
use CodeInc\Router\Instantiator\HandlerInstantiatorInterface;
use CodeInc\Router\Resolvers\HandlerResolverInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class Router
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Router implements MiddlewareInterface
{
    /**
     * @var HandlerResolverInterface
     */
    private $resolver;

    /**
     * @var HandlerInstantiatorInterface
     */
    private $instantiator;

    /**
     * Router constructor.
     *
     * @param HandlerResolverInterface|null $resolver
     * @param HandlerInstantiatorInterface $instantiator
     */
    public function __construct(HandlerResolverInterface $resolver, HandlerInstantiatorInterface $instantiator)
    {
        $this->resolver = $resolver;
        $this->instantiator = $instantiator;
    }

    /**
     * @return HandlerResolverInterface
     */
    public function getResolver():HandlerResolverInterface
    {
        return $this->resolver;
    }

    /**
     * @return HandlerInstantiatorInterface
     */
    public function getInstantiator():HandlerInstantiatorInterface
    {
        return $this->instantiator;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws ControllerHandlingException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        if ($controller = $this->getHandler($request)) {
            try {
                return $controller->handle($request);
            }
            catch (\Throwable $exception) {
                throw new ControllerHandlingException($controller, 0, $exception);
            }
        }
        return $handler->handle($request);
    }

    /**
     * Returns the handler in charge of handling a given route.
     *
     * @param string|ServerRequestInterface $route
     * @return null|RequestHandlerInterface
     */
    public function getHandler($route):?RequestHandlerInterface
    {
        if ($route instanceof ServerRequestInterface) {
            $route = $route->getUri()->getPath();
        }
        else {
            $route = (string)$route;
        }

        if (($controllerClass = $this->resolver->getHandlerClass($route)) !== null) {
            try {
                return $this->instantiator->instantiate($controllerClass);
            }
            catch (\Throwable $exception) {
                throw new ControllerInstantiatingException($controllerClass, 0, $exception);
            }
        }
        return null;
    }

    /**
     * Returns the route to a request handler or NULL if the route can not be computed.
     * Alias of HandlerResolverInterface::getHandlerRoute().
     *
     * @uses HandlerResolverInterface::getHandlerRoute()
     * @param string $handlerClass
     * @return null|string
     */
    public function getRoute(string $handlerClass):?string
    {
        return $this->resolver->getHandlerRoute($handlerClass);
    }
}