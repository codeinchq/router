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
// Time:     14:34
// Project:  lib-psr15router
//
declare(strict_types = 1);
namespace CodeInc\Router\RequestHandlers;
use CodeInc\Router\RouterLibException;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;


/**
 * Class RequestHandlerException
 *
 * @package CodeInc\Router\RequestHandlers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RequestHandlerException extends RouterLibException {
	/**
	 * @var RequestHandlerInterface
	 */
	private $requestHandler;

	/**
	 * RequestHandlerException constructor.
	 *
	 * @param string $message
	 * @param RequestHandlerInterface $requestHandler
	 * @param int|null $code
	 * @param Throwable|null $previous
	 */
	public function __construct(string $message, RequestHandlerInterface $requestHandler,
		?int $code = null, Throwable $previous = null)
	{
		$this->requestHandler = $requestHandler;
		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return RequestHandlerInterface
	 */
	public function getRequestHandler():RequestHandlerInterface
	{
		return $this->requestHandler;
	}
}