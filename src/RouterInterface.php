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
// Date:     14/02/2018
// Time:     16:01
// Project:  lib-router
//
declare(strict_types=1);
namespace CodeInc\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Interface RouterInterface
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface RouterInterface extends MiddlewareInterface {
	/**
	 * Verifies if a handler is avaialble to process a request.
	 *
	 * @param ServerRequestInterface $request
	 * @return bool
	 */
	public function canProcess(ServerRequestInterface $request):bool;

	/**
	 * Processes the current request and sends the response to the web browser.
	 *
	 * @param RequestHandlerInterface|null $handler
	 * @return ResponseInterface
	 */
	public function processCurrent(?RequestHandlerInterface $handler = null):ResponseInterface;
}