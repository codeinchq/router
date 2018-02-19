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
// Date:     16/02/2018
// Time:     12:39
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Response;
use CodeInc\GUI\Pages\Interfaces\PageInterface;


/**
 * Class AbstractResponse
 *
 * @package CodeInc\GUI\PagesManager\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractResponse implements ResponseInterface {
	/**
	 * @var array
	 */
	private $httpHeaders = [];

	/**
	 * @var ResponseCookie[]
	 */
	private $newCookies = [];

	/**
	 * @var array
	 */
	private $deletedCookies = [];

	/**
	 * @var int
	 */
	private $httpStatusCode = 200;

	/**
	 * @var PageInterface
	 */
	private $page;

	/**
	 * AbstractResponse constructor.
	 *
	 * @param PageInterface $page
	 */
	public function __construct(PageInterface $page) {
		$this->page = $page;
	}

	/**
	 * @inheritdoc
	 * @return PageInterface
	 */
	public function getPage():PageInterface {
		return $this->page;
	}

	/**
	 * @inheritdoc
	 * @param int $httpStatusCode
	 */
	public function setHttpStatusCode(int $httpStatusCode):void {
		$this->httpStatusCode = $httpStatusCode;
	}

	/**
	 * @inheritdoc
	 * @return int
	 */
	public function getHttpStatusCode():int {
		return $this->httpStatusCode;
	}

	/**
	 * Verifies if a HTTP header is set.
	 *
	 * @param string $header
	 * @return bool
	 */
	public function hasHttpHeader(string $header):bool {
		return isset($this->httpHeaders[$header]);
	}

	/**
	 * Returns a HTTP header value of null if not set.
	 *
	 * @param string $header
	 * @return null|string
	 */
	public function getHttpHeader(string $header):?string {
		return $this->httpHeaders[$header] ?? null;
	}

	/**
	 * Sets a HTTP header.
	 *
	 * @param string $header
	 * @param string $value
	 */
	public function setHttpHeader(string $header, string $value):void {
		$this->httpHeaders[$header] = $value;
	}

	/**
	 * Removes a HTTP header.
	 *
	 * @param string $header
	 */
	public function removeHttpHeader(string $header):void {
		unset($this->httpHeaders[$header]);
	}

	/**
	 * Removes all HTTP headers.
	 */
	public function removeHttpHeaders():void {
		$this->httpHeaders = [];
	}

	/**
	 * Returns all headers in an assoc array.
	 *
	 * @return array
	 */
	public function getHttpHeaders():array {
		return $this->httpHeaders;
	}

	/**
	 * Creates a new cookie.
	 *
	 * @param ResponseCookie $cookie
	 */
	public function addCookie(ResponseCookie $cookie):void {
		$this->newCookies[$cookie->getName()] = $cookie;
	}

	/**
	 * Verifies if a new cookie is set.
	 *
	 * @param string $cookieName
	 * @return bool
	 */
	public function hasCookie(string $cookieName):bool {
		return isset($this->newCookies[$cookieName]);
	}

	/**
	 * Returns a new cookie or null if not set.
	 *
	 * @param string $cookieName
	 * @return ResponseCookie|null
	 */
	public function getCookie(string $cookieName):?ResponseCookie {
		return $this->newCookies[$cookieName] ?? null;
	}

	/**
	 * Deletes a cookie.
	 *
	 * @param string $cookieName
	 */
	public function deleteCookie(string $cookieName):void {
		$this->deletedCookies[] = $cookieName;
	}

	/**
	 * Verifies if a cookie si deleted.
	 *
	 * @param string $cookieName
	 * @return bool
	 */
	public function isCookieDeleted(string $cookieName):bool {
		return in_array($cookieName, $this->deletedCookies);
	}

	/**
	 * Cancels a cookie deletion.
	 *
	 * @param string $cookieName
	 */
	public function cancelCookieDeletion(string $cookieName):void {
		if (($key = array_search($cookieName, $this->deletedCookies)) !== false) {
			unset($this->deletedCookies[$key]);
		}
	}

	/**
	 * Retruns the new cookies in an array.
	 *
	 * @return ResponseCookie[]
	 */
	public function getCookies():array {
		return $this->newCookies;
	}

	/**
	 * Returns the deleted cookies name.
	 *
	 * @return array
	 */
	public function getDeletedCookies():array {
		return $this->deletedCookies;
	}
}