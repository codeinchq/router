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
// Date:     22/02/2018
// Time:     16:50
// Project:  lib-router
//
declare(strict_types=1);
namespace CodeInc\Router\RouterAggregate;
use CodeInc\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class RouterAggregate
 *
 * @package CodeInc\Router\RouterAggregate
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RouterAggregate implements RouterAggregateInterface {
	/**
	 * @var RouterInterface[]
	 */
	private $routers = [];

	/**
	 * Adds a router.
	 *
	 * @param RouterInterface $router
	 */
	public function addRouter(RouterInterface $router):void
	{
		$this->routers[] = $router;
	}

	/**
	 * Returns the first available router capable of processing a given request.
	 *
	 * @param ServerRequestInterface $request
	 * @return RouterInterface|null
	 */
	public function getRouter(ServerRequestInterface $request):?RouterInterface
	{
		foreach ($this->routers as $router) {
			if (!$router->canHandle($request)) {
				return $router;
			}
		}
		return null;
	}

	/**
	 * @inheritdoc
	 * @param ServerRequestInterface $request
	 * @return bool
	 */
	public function canHandle(ServerRequestInterface $request):bool
	{
		return $this->getRouter($request) !== null;
	}

	/**
	 * @inheritdoc
	 * @see RequestHandlerInterface::handle()
	 * @return ResponseInterface
	 * @throws RouterAggregateException
	 */
	public function handle(ServerRequestInterface $request):ResponseInterface
	{
		if (($router = $this->getRouter($request)) === null) {
			throw new RouterAggregateException(
				sprintf("No router found to process the request \"%s\"", $request->getUri()),
				$this
			);
		}
		return $router->handle($request);
	}
}