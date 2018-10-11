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
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws ControllerHandlingException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        if ($controller = $this->getRequestHandler($request)) {
            try {
                $controller->handle($request);
            }
            catch (\Throwable $exception) {
                throw new ControllerHandlingException($controller, 0, $exception);
            }
        }
        return ($this->getRequestHandler($request) ?? $handler)->handle($request);
    }

    /**
     * Returns the handler in charge of handling a given PSR-7 request.
     *
     * @param ServerRequestInterface $request
     * @return null|RequestHandlerInterface
     * @throws ControllerInstantiatingException
     */
    public function getRequestHandler(ServerRequestInterface $request):?RequestHandlerInterface
    {
        return $this->getRouteHandler($request->getUri()->getPath());
    }

    /**
     * Returns the handler in charge of handling a given route.
     *
     * @param string $route
     * @return null|RequestHandlerInterface
     */
    public function getRouteHandler(string $route):?RequestHandlerInterface
    {
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
     * Returns the parent handler's resolver.
     *
     * @return HandlerResolverInterface
     */
    public function getResolver():HandlerResolverInterface
    {
        return $this->resolver;
    }
}