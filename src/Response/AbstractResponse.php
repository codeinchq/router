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
// Time:     21:05
// Project:  lib-router
//
namespace CodeInc\Router\Response;
use CodeInc\Router\Response\Components\Cookies;
use CodeInc\Router\Response\Components\HttpHeaders;


/**
 * Class AbstractResponse
 *
 * @package CodeInc\Router\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractResponse implements ResponseInterface {
	/**
	 * @var HttpHeaders
	 */
	private $httpHeaders;

	/**
	 * @var Cookies
	 */
	private $cookies;

	/**
	 * @var bool
	 */
	protected $sent = false;

	/**
	 * AbstractResponse constructor.
	 *
	 * @param HttpHeaders|null $httpHeaders
	 * @param Cookies|null $cookies
	 */
	public function __construct(HttpHeaders $httpHeaders = null, Cookies $cookies = null) {
		$this->httpHeaders = $httpHeaders ?: new HttpHeaders($this);
		$this->cookies = $cookies ?: new Cookies($this);
	}

	/**
	 * Returns the HTTP headers manager.
	 *
	 * @return HttpHeaders
	 */
	public function getHttpHeaders():HttpHeaders {
		return $this->httpHeaders;
	}

	/**
	 * Returns the cookies manager.
	 *
	 * @return Cookies
	 */
	public function getCookies():Cookies {
		return $this->cookies;
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function isSent():bool {
		return $this->sent;
	}
}