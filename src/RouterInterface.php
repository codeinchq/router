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
// Time:     11:55
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Interface RouterInterface
 *
 * @package CodeInc\Router\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface RouterInterface extends RequestHandlerInterface
{
	/**
	 * Verifies if the router can handle a request.
	 *
	 * @param ServerRequestInterface $request
	 * @return bool
	 */
	public function canHandle(ServerRequestInterface $request):bool;

	/**
	 * @inheritdoc
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 */
	public function handle(ServerRequestInterface $request):ResponseInterface;
}