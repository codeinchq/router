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
// Date:     13/02/2018
// Time:     13:13
// Project:  lib-router
//
namespace CodeInc\Router\Exceptions;
use CodeInc\Router\Request\Request;
use Throwable;


/**
 * Class RequestException
 *
 * @package CodeInc\GUI\PagesManager\Request\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RequestException extends RouterException {
	/**
	 * @var Request|null
	 */
	private $request;

	/**
	 * RequestException constructor.
	 *
	 * @param string $message
	 * @param Request|null $request
	 * @param null|Throwable $previous
	 */
	public function __construct(string $message, ?Request $request = null, ?Throwable $previous = null) {
		$this->request = $request;
		parent::__construct($message, $request ? $request->getRouter() : null, $previous);
	}

	/**
	 * @return Request|null
	 */
	public function getRequest():?Request {
		return $this->request;
	}
}