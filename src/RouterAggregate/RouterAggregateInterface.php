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
// Time:     14:02
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router\RouterAggregate;
use CodeInc\Router\RouterInterface;
use Psr\Http\Message\ServerRequestInterface;


/**
 * Interface RouterAggregateInterface
 *
 * @package CodeInc\Router\RouterAggregate
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface RouterAggregateInterface extends RouterInterface {
	/**
	 * Returns the first available router capable of processing a given request.
	 *
	 * @param ServerRequestInterface $request
	 * @return RouterInterface|null
	 */
	public function getRouter(ServerRequestInterface $request):?RouterInterface;
}