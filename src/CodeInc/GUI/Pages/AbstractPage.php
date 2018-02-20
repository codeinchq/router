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
// Time:     19:13
// Project:  lib-router
//
namespace CodeInc\GUI\Pages;
use CodeInc\Router\Request\Request;
use CodeInc\Router\RouterInterface;


/**
 * Class AbstractPage
 *
 * @package CodeInc\GUI\Pages
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractPage implements PageInterface {
	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * AbstractPage constructor.
	 *
	 * @param RouterInterface $router
	 * @param Request $request
	 */
	public function __construct(RouterInterface $router, Request $request) {
		$this->router = $router;
		$this->request = $request;
	}

	/**
	 * @inheritdoc
	 * @return Request
	 */
	public function getRequest():Request {
		return $this->request;
	}

	/**
	 * @inheritdoc
	 * @return RouterInterface
	 */
	public function getRouter():RouterInterface {
		return $this->router;
	}
}