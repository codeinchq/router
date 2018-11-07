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
use CodeInc\Router\Exceptions\NotAControllerException;
use CodeInc\Router\Exceptions\RequestHandlingException;
use CodeInc\Router\Exceptions\ControllerInstantiatingException;
use CodeInc\Router\Resolvers\ResolverInterface;
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
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * Router constructor.
     *
     * @param ResolverInterface $resolver
     */
    public function __construct(ResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return ResolverInterface
     */
    public function getResolver():ResolverInterface
    {
        return $this->resolver;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws RequestHandlingException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        if ($controller = $this->getController($request)) {
            try {
                return $controller->createResponse();
            }
            catch (\Throwable $exception) {
                throw new RequestHandlingException($controller, $request, 0, $exception);
            }
        }
        return $handler->handle($request);
    }

    /**
     * Returns the handler in charge of handling a given route.
     *
     * @param ServerRequestInterface $request
     * @return ControllerInterface|null
     * @throws ControllerInstantiatingException
     */
    public function getController(ServerRequestInterface $request):?ControllerInterface
    {
        if (($controllerClass = $this->resolver->getControllerClass($request->getUri()->getPath())) !== null) {
            if (!is_subclass_of($controllerClass, ControllerInterface::class)) {
                throw new NotAControllerException($controllerClass);
            }
            try {
                return new $controllerClass($request);
            }
            catch (\Throwable $exception) {
                throw new ControllerInstantiatingException($controllerClass, $request, 0, $exception);
            }
        }

        return null;
    }

    /**
     * Returns the route to a request handler or NULL if the route can not be computed.
     * Alias of HandlerResolverInterface::getHandlerRoute().
     *
     * @uses ResolverInterface::getControllerRoute()
     * @param string $controllerClass
     * @return null|string
     */
    public function getRoute(string $controllerClass):?string
    {
        return $this->resolver->getControllerRoute($controllerClass);
    }
}