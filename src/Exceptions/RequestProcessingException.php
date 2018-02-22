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
// Date:     19/02/2018
// Time:     18:34
// Project:  lib-router
//
declare(strict_types=1);
namespace CodeInc\Router\Exceptions;
use Psr\Http\Message\RequestInterface;
use CodeInc\Router\RouterInterface;
use Throwable;


/**
 * Class RouteProcessingException
 *
 * @package CodeInc\Router\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RequestProcessingException extends RouterException {
	/**
	 * @var RequestInterface
	 */
	private $request;

	/**
	 * RequestProcessingException constructor.
	 *
	 * @param RequestInterface $request
	 * @param RouterInterface $router
	 * @param string|null $message
	 * @param null|Throwable $previous
	 */
	public function __construct(RequestInterface $request, RouterInterface $router, string $message = null,
		?Throwable $previous = null) {
		$this->request = $request;
		parent::__construct($message ?? sprintf("Error while processing the request to \"%s\"", $request->getUri()),
			$router, $previous);
	}

	/**
	 * @return RequestInterface
	 */
	public function getRequest():RequestInterface {
		return $this->request;
	}
}