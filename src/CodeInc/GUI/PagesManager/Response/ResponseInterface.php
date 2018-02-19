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
// Time:     10:41
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Response;
use CodeInc\GUI\Pages\Interfaces\PageInterface;


/**
 * Interface ResponseInterface
 *
 * @package CodeInc\GUI\PagesManager\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface ResponseInterface {
	/**
	 * Returns the parent page.
	 *
	 * @return PageInterface
	 */
	public function getPage():PageInterface;

	/**
	 * Returns the HTTP status code.
	 */
	public function getHttpStatusCode():int;

	/**
	 * Returns the page's content.
	 *
	 * @return null|string
	 */
	public function getContent():?string;

	/**
	 * Verifies if a header is set.
	 *
	 * @param string $header
	 * @return bool
	 */
	public function hasHeader(string $header):bool;

	/**
	 * Returns a header value of null if not set.
	 *
	 * @param string $header
	 * @return null|string
	 */
	public function getHeader(string $header):?string;

	/**
	 * Sets a header.
	 *
	 * @param string $header
	 * @param string $value
	 */
	public function setHeader(string $header, string $value):void;

	/**
	 * Removes a header.
	 *
	 * @param string $header
	 */
	public function removeHeader(string $header):void;

	/**
	 * Removes all headers.
	 */
	public function removeAllHeaders():void;

	/**
	 * Returns all headers in an assoc array.
	 *
	 * @return array
	 */
	public function getHeaders():array;

	/**
	 * Creates a new cookie.
	 *
	 * @param ResponseCookie $cookie
	 */
	public function addCookie(ResponseCookie $cookie):void;

	/**
	 * Verifies if a new cookie is set.
	 *
	 * @param string $cookieName
	 * @return bool
	 */
	public function hasCookie(string $cookieName):bool;

	/**
	 * Returns a new cookie or null if not set.
	 *
	 * @param string $cookieName
	 * @return ResponseCookie|null
	 */
	public function getCookie(string $cookieName):?ResponseCookie;

	/**
	 * Deletes a cookie.
	 *
	 * @param string $cookieName
	 */
	public function deleteCookie(string $cookieName):void;

	/**
	 * Verifies if a cookie si deleted.
	 *
	 * @param string $cookieName
	 * @return bool
	 */
	public function isCookieDeleted(string $cookieName):bool;

	/**
	 * Cancels a cookie deletion.
	 *
	 * @param string $cookieName
	 */
	public function cancelCookieDeletion(string $cookieName):void;

	/**
	 * Retruns the new cookies in an array.
	 *
	 * @return ResponseCookie[]
	 */
	public function getCookies():array;

	/**
	 * Returns the deleted cookies name.
	 *
	 * @return array
	 */
	public function getDeletedCookies():array;
}