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
namespace CodeInc\GUI\Pages;
use CodeInc\Router\Interfaces\RoutableInterface;
use CodeInc\Router\Request\Request;
use CodeInc\Router\RouterInterface;


/**
 * Interface PageInterface
 *
 * @package CodeInc\GUI\Pages\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface PageInterface extends RoutableInterface {
	/**
	 * PageInterface constructor.
	 *
	 * @param RouterInterface $router
	 * @param Request $request
	 */
	public function __construct(RouterInterface $router, Request $request);

	/**
	 * Returns the parent request
	 *
	 * @return Request
	 */
	public function getRequest():Request;

	/**
	 * Returns the page router.
	 *
	 * @return RouterInterface
	 */
	public function getRouter():RouterInterface;
}