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
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use CodeInc\Router\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class RouterAggregator
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RouterAggregator implements RouterInterface {
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
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 */
	public function handle(ServerRequestInterface $request):ResponseInterface
	{
		if ($router = $this->getRouter($request)) {
			return $router->handle($request);
		}
		return new Response(404);
	}
}