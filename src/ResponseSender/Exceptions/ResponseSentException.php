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
// Time:     21:23
// Project:  lib-router
//
declare(strict_types=1);
namespace CodeInc\Router\ResponseSender\Exceptions;
use CodeInc\Router\ResponseSender\ResponseSenderInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;


/**
 * Class ResponseSentException
 *
 * @package CodeInc\Router\ResponseSender\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ResponseSentException extends ResponsSenderException {
	/**
	 * @var ResponseInterface
	 */
	private $response;

	/**
	 * ResponseSentException constructor.
	 *
	 * @param ResponseInterface $response
	 * @param ResponseSenderInterface $responseSender
	 * @param int|null $code
	 * @param null|Throwable $previous
	 */
	public function __construct(ResponseInterface $response, ResponseSenderInterface $responseSender,
		?int $code = null, ?Throwable $previous = null)
	{
		$this->response = $response;
		parent::__construct("A response have already been sent to the web browser",
			$responseSender, $code, $previous);
	}

	/**
	 * @return ResponseInterface
	 */
	public function getResponse():ResponseInterface
	{
		return $this->response;
	}
}