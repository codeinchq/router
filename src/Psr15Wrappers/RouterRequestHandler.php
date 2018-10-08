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
use CodeInc\Router\Exceptions\ControllerProcessingException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RouterRequestHandler
 *
 * @package CodeInc\Router\Psr15Wrappers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RouterRequestHandler implements RequestHandlerInterface
{
    /**
     * @var InstantiatingRouterInterface
     */
    private $router;

    /**
     * @var null|ResponseFactoryInterface
     * @see RouterRequestHandler::getNotFoundResponseFactory()
     */
    private $notFoundResponseFactory;

    /**
     * RouterRequestHandler constructor.
     *
     * @param InstantiatingRouterInterface $router
     * @param null|ResponseFactoryInterface $notFoundResponseFactory
     */
    public function __construct(InstantiatingRouterInterface $router,
        ?ResponseFactoryInterface $notFoundResponseFactory = null)
    {
        $this->router = $router;
        $this->notFoundResponseFactory = $notFoundResponseFactory;
    }

    /**
     * Returns the not found request handler.
     *
     * @return ResponseFactoryInterface
     */
    public function getNotFoundResponseFactory():ResponseFactoryInterface
    {
        return $this->notFoundResponseFactory ?? new NotFoundResponseFactory();
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        if ($controller = $this->router->getController($request)) {
            try {
                return $controller->createResponse();
            }
            catch (\Throwable $exception) {
                throw new ControllerProcessingException($controller, 0, $exception);
            }
        }
        return $this->getNotFoundResponseFactory()->createResponse(404);
    }
}