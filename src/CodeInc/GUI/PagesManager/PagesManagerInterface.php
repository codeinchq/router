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
// Date:     14/02/2018
// Time:     16:01
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager;
use CodeInc\GUI\PagesManager\Exceptions\PagesManagerException;
use CodeInc\GUI\PagesManager\Request\RequestInterface;
use CodeInc\GUI\PagesManager\Response\ResponseInterface;
use CodeInc\Url\Url;


/**
 * Interface PagesManagerInterface
 *
 * @package CodeInc\GUI\Services\PagesManager
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface PagesManagerInterface {
	/**
	 * Registers a page.
	 *
	 * @param string $path
	 * @param string $pageClass
	 * @throws PagesManagerException
	 */
	public function registerPage(string $path, string $pageClass):void;

	/**
	 * @param string $pageClass
	 * @param array|null $queryParameters
	 * @return Url
	 * @throws PagesManagerException
	 */
	public function getPageUrl(string $pageClass, ?array $queryParameters = null):Url;

	/**
	 * Render a page using it's URI. If $allowNotFound is at TRUE, the page is not found and a not found page
	 * has been defined the method will render the not found page, else a PagesManagerNotFoundException is thrown.
	 *
	 * @param RequestInterface|null $request
	 * @throws PagesManagerException
	 */
	public function processRequest(?RequestInterface $request = null):void;

	/**
	 * Sends the response.
	 *
	 * @param RequestInterface $request
	 * @param ResponseInterface $response
	 */
	public function sendResponse(RequestInterface $request, ResponseInterface $response):void;
}