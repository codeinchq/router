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
// Date:     27/09/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router;
use CodeInc\Router\Resolvers\ResolverInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RouterMiddleware
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Router implements RouterInterface
{
    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @var ControllerInstantiatorInterface
     */
    private $controllerInstantiator;

    /**
     * RouterMiddleware constructor.
     *
     * @param ResolverInterface $resolver
     * @param ControllerInstantiatorInterface $controllerInstantiator
     */
    public function __construct(ResolverInterface $resolver, ControllerInstantiatorInterface $controllerInstantiator)
    {
        $this->resolver = $resolver;
        $this->controllerInstantiator = $controllerInstantiator;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        if ($controllerClass = $this->resolver->getControllerClass($request)) {
            return $this->controllerInstantiator->instantiateController($request, $controllerClass)->getResponse();
        }
        else {
            return $handler->handle($request);
        }
    }
}