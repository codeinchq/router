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
// Time:     14:57
// Project:  lib-psr15router
//
declare(strict_types = 1);
namespace CodeInc\Router\Middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class SimpleMiddleware
 *
 * @package CodeInc\Router\Middleware
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class SimpleMiddleware implements MiddlewareInterface {
	/**
	 * @inheritdoc
	 * @param ServerRequestInterface $request
	 * @param RequestHandlerInterface $handler
	 * @return ResponseInterface
	 * @throws MiddlewareException
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
	{
		try {
			return $handler->handle($request);
		}
		catch (\Throwable $exception) {
			throw new MiddlewareException(
				sprintf("Error while processing the request \"%s\" using the handler %s",
					$request->getUri(), get_class($handler)),
				$this
			);
		}
	}
}