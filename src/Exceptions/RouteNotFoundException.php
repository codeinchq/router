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
// Time:     20:01
// Project:  lib-router
//
namespace CodeInc\Router\Exceptions;
use CodeInc\Router\Request\Request;
use CodeInc\Router\RouterInterface;
use Throwable;


/**
 * Class RouteNotFoundException
 *
 * @package CodeInc\Router\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RouteNotFoundException extends RouterException {
	/**
	 * @var Request
	 */
	private $request;

	/**
	 * RouteNotFoundException constructor.
	 *
	 * @param Request $request
	 * @param RouterInterface|null $router
	 * @param Throwable|null $previous
	 */
	public function __construct(Request $request, RouterInterface $router = null, Throwable $previous = null) {
		$this->request = $request;
		parent::__construct("Not route found to process the request \"{$request->getUrl()}\"",
			$router, $previous);
	}

	/**
	 * @return Request
	 */
	public function getRequest():Request {
		return $this->request;
	}
}