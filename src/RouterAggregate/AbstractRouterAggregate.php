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
use CodeInc\Router\Exceptions\RouterNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Class AbstractRouterAggregate
 *
 * @package CodeInc\Router\RouterAggregate
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractRouterAggregate implements RouterAggregateInterface {
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
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 * @throws RouterNotFoundException
	 */
	public function handle(ServerRequestInterface $request):ResponseInterface
	{
		if (($router = $this->getRouter($request)) === null) {
			throw new RouterNotFoundException($request, $this);
		}
		return $router->handle($request);
	}
}