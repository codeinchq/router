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
// Time:     17:52
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router\Responses;
use CodeInc\Router\RouterLibException;
use Psr\Http\Message\ResponseInterface;
use Throwable;


/**
 * Class ResponseException
 *
 * @package CodeInc\Router\Responses
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ResponseException extends RouterLibException {
	/**
	 * @var ResponseInterface
	 */
	private $response;

	/**
	 * ResponseException constructor.
	 *
	 * @param string $message
	 * @param ResponseInterface $response
	 * @param int|null $code
	 * @param null|Throwable $previous
	 */
	public function __construct(string $message, ResponseInterface $response, ?int $code = null, ?Throwable $previous = null)
	{
		$this->response = $response;
		parent::__construct($message, $code, $previous);
	}

	/**
	 * @return ResponseInterface
	 */
	public function getResponse():ResponseInterface
	{
		return $this->response;
	}
}