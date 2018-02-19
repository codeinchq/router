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
// Time:     20:26
// Project:  lib-router
//
namespace CodeInc\Router\Responses\Components;
use CodeInc\Router\Responses\Components\Cookie;
use CodeInc\Router\Responses\ResponseInterface;
use CodeInc\Router\Exceptions\HttpHeadersSentException;


/**
 * Class Cookies
 *
 * @package CodeInc\GUI\PagesManager\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Cookies implements \Iterator {
	/**
	 * @var ResponseInterface
	 */
	private $response;

	/**
	 * @var Cookie[]
	 */
	private $cookies;

	/**
	 * @var int
	 */
	private $iteratorIndex;

	/**
	 * @var array
	 */
	private $iteratorKeys;

	/**
	 * ResponseCookies constructor.
	 *
	 * @param ResponseInterface $response
	 */
	public function __construct(ResponseInterface $response) {
		$this->response = $response;
	}

	/**
	 * Adds a cookie.
	 *
	 * @param Cookie $cookie
	 */
	public function addCookie(Cookie $cookie) {
		$this->cookies[$cookie->getName()] = $cookie;
	}

	/**
	 * Verifies if a cookie is set.
	 *
	 * @param string $cookieName
	 * @return bool
	 */
	public function hasCookie(string $cookieName):bool {
		return isset($this->cookies[$cookieName]);
	}

	/**
	 * Returns a cookie or null if not set.
	 *
	 * @param string $cookieName
	 * @return Cookie|null
	 */
	public function getCookie(string $cookieName):?Cookie {
		return $this->cookies[$cookieName] ?? null;
	}

	/**
	 * Sends the cookies.
	 *
	 * @throws HttpHeadersSentException
	 */
	public function send():void {
		if (headers_sent()) {
			throw new HttpHeadersSentException($this->response);
		}
		foreach ($this->cookies as $cookie) {
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
	 * @inheritdoc
	 */
	public function rewind():void {
		$this->iteratorIndex = 0;
		$this->iteratorKeys = array_keys($this->cookies);
	}

	/**
	 * @inheritdoc
	 * @return Cookie
	 */
	public function current():Cookie {
		return $this->cookies[$this->iteratorKeys[$this->iteratorIndex]];
	}

	/**
	 * @inheritdoc
	 * @return string
	 */
	public function key():string {
		return $this->iteratorKeys[$this->iteratorIndex];
	}

	/**
	 * @inheritdoc
	 */
	public function next():void {
		$this->iteratorIndex++;
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function valid():bool {
		return isset($this->iteratorKeys[$this->iteratorIndex],
			$this->cookies[$this->iteratorKeys[$this->iteratorIndex]]);
	}
}