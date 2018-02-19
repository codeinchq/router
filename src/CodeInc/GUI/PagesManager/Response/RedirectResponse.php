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
// Time:     11:45
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Response;
use CodeInc\GUI\Pages\Interfaces\PageInterface;
use CodeInc\Url\Url;


/**
 * Class RedirectResponse
 *
 * @package CodeInc\GUI\PagesManager\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RedirectResponse extends AbstractResponse {
	/**
	 * @var Url
	 */
	public $redirectUrl;

	/**
	 * RedirectResponse constructor.
	 *
	 * @param PageInterface $page
	 * @param Url $redirectUrl
	 */
	public function __construct(PageInterface $page, Url $redirectUrl) {
		parent::__construct($page);
		$this->setHttpStatusCode(302);
		$this->getHeaders()->setHeader("Location", $redirectUrl->getUrl());
	}

	/**
	 * @return null|string
	 */
	public function getContent():?string {
		return null;
	}
}