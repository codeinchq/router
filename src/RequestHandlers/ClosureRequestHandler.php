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
// Date:     23/02/2018
// Time:     15:13
// Project:  lib-psr15router
//
declare(strict_types = 1);
namespace CodeInc\Router\RequestHandlers;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class ClosureRequestHandler
 *
 * @package CodeInc\Router\RequestHandlers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ClosureRequestHandler implements RequestHandlerInterface {
	/**
	 * @var \Closure
	 */
	private $closure;

	/**
	 * CallableRequestHandler constructor.
	 *
	 * @param \Closure $closure
	 */
	public function __construct(\Closure $closure)
	{
		$this->closure = $closure;
	}

	/**
	 * @inheritdoc
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 * @throws RequestHandlerException
	 */
	public function handle(ServerRequestInterface $request):ResponseInterface
	{
		$response = $this->closure->call(new ClosureParent($request), $request);
		if (!$response instanceof RequestInterface) {
			throw new RequestHandlerException(
				sprintf(
					"The response of the closure must be an object implementing %s",
					ResponseInterface::class
				),
				$this
			);
		}
		return $response;
	}
}