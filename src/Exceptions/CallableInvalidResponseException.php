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
// Date:     20/02/2018
// Time:     20:09
// Project:  lib-router
//
declare(strict_types=1);
namespace CodeInc\Router\Exceptions;
use CodeInc\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;


/**
 * Class CallableInvalidResponse
 *
 * @package CodeInc\Router\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class CallableInvalidResponseException extends RouterException {
	/**
	 * CallableInvalidResponseException constructor.
	 *
	 * @param RouterInterface|null $router
	 * @param null|Throwable $previous
	 */
	public function __construct(RouterInterface $router, ?Throwable $previous = null) {
		parent::__construct(
			sprintf(
				"The response of the callable must be an object implementing %s",
				ResponseInterface::class
			),
			$router, $previous);
	}
}