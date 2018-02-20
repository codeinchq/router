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
use CodeInc\Router\Exceptions\HttpHeadersSentException;
use CodeInc\Router\Exceptions\ResponseSentException;
use CodeInc\Router\Exceptions\ResponseException;


/**
 * Class AbstractResponse
 *
 * @package CodeInc\GUI\PagesManager\Response\Library
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
	 * @inheritdoc
	 */
	public function httpHeaders():HttpHeaders {
		return $this->httpHeaders;
	}

	/**
	 * @inheritdoc
	 */
	public function cookies():Cookies {
		return $this->cookies;
	}

	/**
	 * @inheritdoc
	 */
	public function isSent():bool {
		return $this->sent;
	}

	/**
	 * Returns the response content.
	 *
	 * @return null|string
	 */
	abstract public function getContent():?string;

	/**
	 * @throws ResponseException
	 */
	public function send():void {
		try {
			if ($this->sent) {
				throw new ResponseSentException($this);
			}
			if (headers_sent()) {
				throw new HttpHeadersSentException($this);
			}
			$this->sendHeaders();
			$this->sendCookies();
			$this->sendContent();
			$this->sent = true;
		}
		catch (\Throwable $exception) {
			throw new ResponseException("Error while sending the response", $this, $exception);
		}
	}

	/**
	 * Sends the response headers.
	 */
	private function sendHeaders():void {
		http_response_code($this->httpHeaders()->getHttpResponseCode());
		foreach ($this->httpHeaders() as $header => $value) {
			header("$header: $value", true);
		}
	}

	/**
	 * Sends the response cookies.
	 */
	private function sendCookies():void {
		foreach ($this->cookies() as $cookie) {
			// sending the cooke
			if (!$cookie->isDeleted()) {
				setcookie($cookie->getName(), $cookie->getValue(),
					($cookie->getExpire() ? $cookie->getExpire()->getTimestamp() : null),
					$cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
			}

			// deleting the cookie
			else {
				setcookie($cookie->getName(), null, -1, $cookie->getPath(), $cookie->getDomain());
			}
		}
	}

	/**
	 * Sends the response content.
	 */
	private function sendContent():void {
		if (($content = $this->getContent()) !== null) {
			echo $content;
		}
	}
}