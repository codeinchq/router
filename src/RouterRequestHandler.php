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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RouterRequestHandler
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class RouterRequestHandler implements RequestHandlerInterface
{
    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @var RequestHandlerInterface
     */
    private $notFoundRequestHandler;

    /**
     * RouterRequestHandler constructor.
     *
     * @param ResolverInterface $resolver
     * @param RequestHandlerInterface $notFoundRequestHandler
     */
    public function __construct(ResolverInterface $resolver, RequestHandlerInterface $notFoundRequestHandler)
    {
        $this->resolver = $resolver;
        $this->notFoundRequestHandler = $notFoundRequestHandler;
    }

    /**
     * Instantiates a controller.
     *
     * @param ServerRequestInterface $request
     * @param string $controllerClass
     * @return ControllerInterface
     */
    abstract protected function instantiate(ServerRequestInterface $request,
        string $controllerClass):ControllerInterface;

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws RouterException
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        if ($controllerClass = $this->resolver->getControllerClass($request)) {
            try {
                $controller = $this->instantiate($request, $controllerClass);
            }
            catch (\Throwable $exception) {
                throw RouterException::controllerInstantiatingError($controllerClass, $exception);
            }
            try {
                return $controller->getResponse();
            }
            catch (\Throwable $exception) {
                throw RouterException::controllerProcessingError($controllerClass, $exception);
            }
        }
        return $this->notFoundRequestHandler->handle($request);
    }
}