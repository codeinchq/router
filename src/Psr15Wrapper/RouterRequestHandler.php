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
namespace CodeInc\Router\Psr15Wrappers;
use CodeInc\Router\ControllerInterface;
use CodeInc\Router\RouterException;
use CodeInc\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RouterRequestHandler
 *
 * @package CodeInc\Router\Psr15Wrappers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class RouterRequestHandler implements RequestHandlerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestHandlerInterface
     */
    private $notFoundRequestHandler;

    /**
     * RouterRequestHandler constructor.
     *
     * @param RouterInterface $router
     * @param RequestHandlerInterface $notFoundRequestHandler
     */
    public function __construct(RouterInterface $router, RequestHandlerInterface $notFoundRequestHandler)
    {
        $this->router = $router;
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
        if ($controller = $this->router->getController($request)) {
            try {
                return $controller->getResponse();
            }
            catch (\Throwable $exception) {
                throw RouterException::controllerProcessingError($controller, $exception);
            }
        }
        return $this->notFoundRequestHandler->handle($request);
    }
}