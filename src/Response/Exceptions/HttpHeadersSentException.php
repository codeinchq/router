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
// Time:     18:40
// Project:  lib-router
//
namespace CodeInc\Router\Response\Exceptions;
use CodeInc\Router\Response\ResponseInterface;
use Throwable;


/**
 * Class HttpHeadersSentException
 *
 * @package CodeInc\Router\Response\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class HttpHeadersSentException extends ResponseException {
	/**
	 * HttpHeadersSentException constructor.
	 *
	 * @param ResponseInterface $response
	 * @param null|Throwable $previous
	 */
	public function __construct(ResponseInterface $response, Throwable $previous = null) {
		parent::__construct("Unable to send the response, the HTTP headers have been sent",
			$response, $previous);
	}
}