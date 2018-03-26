<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     05/03/2018
// Time:     12:13
// Project:  Router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use CodeInc\Psr7Responses\NotFoundResponse;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class RouterAggregator
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RouterAggregator implements RouterInterface
{
    use MiddlewaresTrait;

	/**
	 * @var RouterInterface[]
	 */
	private $routers = [];

    /**
	 * Adds a router
	 *
	 * @param RouterInterface $router
	 */
	public function addRouter(RouterInterface $router):void
	{
		$this->routers[] = $router;
	}

	/**
	 * @inheritdoc
	 */
	public function canHandle(ServerRequestInterface $request):bool
	{
		return $this->getRouter($request) !== null;
	}

	/**
	 * Returns the first router capable of handeling the request.
	 *
	 * @param ServerRequestInterface $request
	 * @return RouterInterface|null
	 */
	public function getRouter(ServerRequestInterface $request):?RouterInterface
	{
		foreach ($this->routers as $router) {
			if ($router->canHandle($request)) {
				return $router;
			}
		}
		return null;
	}

    /**
     * @inheritdoc
     * @param ServerRequestInterface $request
     * @param bool $bypassMiddlewares
     * @return ResponseInterface
     * @throws \TypeError
     */
	public function handle(ServerRequestInterface $request,
        bool $bypassMiddlewares = false):ResponseInterface
	{
        // if some middlewares are set
        if (!$bypassMiddlewares && isset($this->middlewares[$this->middlewaresIndex])) {
            return $this->middlewares[$this->middlewaresIndex++]->process($request, $this);
        }

        // else returne the controller's response
        else {
            if ($this->middlewaresIndex) {
                $this->middlewaresIndex = 0;
            }
            if ($router = $this->getRouter($request)) {
                return $router->handle($request);
            }
            return new NotFoundResponse();
        }
	}

    /**
     * Alias of handle() for the current request. The current request is built using
     * Guzzle PSR-7 implementation (ServerRequest::fromGlobals()).
     *
     * @param bool $bypassMiddlewares
     * @return ResponseInterface
     */
    public function handleCurrentRequest(bool $bypassMiddlewares = false):ResponseInterface
    {
        return $this->handle(ServerRequest::fromGlobals(), $bypassMiddlewares);
    }
}