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
     * @var ResolverInterface[]
     */
    private $resolvers = [];

    /**
     * @var ControllerInstantiatorInterface
     */
    private $controllerInstantiator;

    /**
     * Router constructor.
     *
     * @param ControllerInstantiatorInterface $controllerInstantiator
     */
    public function __construct(ControllerInstantiatorInterface $controllerInstantiator)
    {
        $this->controllerInstantiator = $controllerInstantiator;
    }

    /**
     * @param ResolverInterface $resolver
     */
    public function addResolver(ResolverInterface $resolver):void
    {
        $this->resolvers[] = $resolver;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        if ($controller = $this->getController($request)) {
            try {
                $controller->handle($request);
            }
            catch (\Throwable $exception) {
                throw new ControllerHandlingException($controller);
            }
        }
        return ($this->getController($request) ?? $handler)->handle($request);
    }

    /**
     * Returns the controller in charge of handling a given request.
     *
     * @param ServerRequestInterface $request
     * @return null|RequestHandlerInterface
     */
    public function getController(ServerRequestInterface $request):?RequestHandlerInterface
    {
        $requestRoute = $request->getUri()->getPath();
        foreach ($this->resolvers as $resolver) {
            if (($controllerClass = $resolver->getHandlerClass($requestRoute)) !== null) {
                return $this->controllerInstantiator->instantiate($controllerClass);
            }
        }
        return null;
    }
}