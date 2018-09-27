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
// Date:     25/09/2018
// Project:  Router
//
declare(strict_types=1);
namespace CodeInc\Router\Psr15Wrappers;
use CodeInc\Router\InstantiatingRouterInterface;
use CodeInc\Router\RouterException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RouterRequestHandlerWrapper. Allows an instantiating router to behave as a PSR-15 request handler.
 *
 * @package CodeInc\Router\Psr15Wrappers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RouterRequestHandlerWrapper implements RequestHandlerInterface
{
    /**
     * @var InstantiatingRouterInterface
     */
    private $router;

    /**
     * RouterRequestHandlerWrapper constructor.
     *
     * @param InstantiatingRouterInterface $router
     */
    public function __construct(InstantiatingRouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws RouterException
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        if ($controller = $this->router->getController($request)) {
            return $controller->getResponse();
        }
        throw RouterException::noRequestHandlerFound($request);
    }
}