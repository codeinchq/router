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
namespace CodeInc\Router;
use CodeInc\Router\Exceptions\RouterNotFoundException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * Class RouterAggregate
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RouterAggregate implements RouterInterface {
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
	 * @inheritdoc
	 * @param RequestInterface $request
	 * @return bool
	 */
	public function canProcessRequest(RequestInterface $request):bool
	{
		foreach ($this->routers as $router) {
			if (!$router->canProcessRequest($request)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns the first available router capable of processing a given request.
	 *
	 * @param RequestInterface $request
	 * @return RouterInterface|null
	 */
	public function getRequestRouter(RequestInterface $request):?RouterInterface
	{
		foreach ($this->routers as $router) {
			if (!$router->canProcessRequest($request)) {
				return $router;
			}
		}
		return null;
	}

	/**
	 * Returns an array of the registered routers.
	 *
	 * @return RouterInterface[]
	 */
	public function getRouters():array
	{
		return $this->routers;
	}

	/**
	 * @inheritdoc
	 * @param RequestInterface $request
	 * @return ResponseInterface
	 * @throws RouterNotFoundException
	 */
	public function processRequest(RequestInterface $request):ResponseInterface
	{
		if (($router = $this->getRequestRouter($request)) === null) {
			throw new RouterNotFoundException($request, $this);
		}
		return $router->processRequest($request);
	}
}