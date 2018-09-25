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
namespace CodeInc\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RouterRequestHandlerWrapper
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RouterRequestHandlerWrapper implements RequestHandlerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var null|RequestHandlerInterface
     */
    private $notFoundRequestHandler;

    /**
     * RouterRequestHandlerWrapper constructor.
     *
     * @param RouterInterface $router
     * @param null|RequestHandlerInterface $notFoundRequestHandler
     */
    public function __construct(RouterInterface $router, RequestHandlerInterface $notFoundRequestHandler)
    {
        $this->router = $router;
        $this->notFoundRequestHandler = $notFoundRequestHandler;
    }

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request):ResponseInterface
    {
        return $this->router->process(
            $request,
            $this->notFoundRequestHandler
        );
    }
}