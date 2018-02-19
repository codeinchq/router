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
use CodeInc\GUI\PagesManager\Response\Cookies;


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
	 * Returns the cookies manager.
	 *
	 * @return Cookies
	 */
	public function cookies():Cookies;

	/**
	 * Returns the HTTP headers manager.
	 *
	 * @return HttpHeaders
	 */
	public function httpHeaders():HttpHeaders;

	/**
	 * Sends the response.
	 */
	public function send():void;

	/**
	 * Verifies if the response is sent.
	 *
	 * @return bool
	 */
	public function isSent():bool;
}