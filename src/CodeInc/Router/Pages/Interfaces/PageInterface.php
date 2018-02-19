<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
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
// Date:     22/11/2017
// Time:     17:09
// Project:  lib-router
//
namespace CodeInc\Router\Pages\Interfaces;
use CodeInc\Router\RouterInterface;
use CodeInc\Router\Request\RequestInterface;
use CodeInc\Router\Responses\ResponseInterface;


/**
 * Interface PageInterface
 *
 * @package CodeInc\GUI\Pages\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface PageInterface {
	/**
	 * PageInterface constructor.
	 *
	 * @param RouterInterface $router
	 * @param RequestInterface $request
	 */
	public function __construct(RouterInterface $router, RequestInterface $request);

	/**
	 * Renders the view.
	 *
	 * @throws
	 * @return ResponseInterface
	 */
	public function process():ResponseInterface;

	/**
	 * Returns the parent request
	 *
	 * @return RequestInterface
	 */
	public function getRequest():RequestInterface;

	/**
	 * Returns the page router.
	 *
	 * @return RouterInterface
	 */
	public function getRouter():RouterInterface;
}