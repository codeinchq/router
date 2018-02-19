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
// Time:     21:13
// Project:  lib-router
//
namespace CodeInc\Router\Responses\Redirect;
use CodeInc\Router\Pages\Interfaces\PageInterface;


/**
 * Class PageRedirectResponse
 *
 * @package CodeInc\GUI\PagesManager\Response\Library\Redirect
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PageRedirectResponse extends RedirectResponse {
	/**
	 * PageRedirectResponse constructor.
	 *
	 * @param PageInterface $page
	 * @param string $pageClass
	 * @param array|null $queryParams
	 * @throws \CodeInc\Router\Exceptions\RouterException
	 */
	public function __construct(PageInterface $page, string $pageClass, ?array $queryParams = null) {
		parent::__construct($page, $page->getRouter()->buildPageUrl($pageClass, $queryParams));
	}
}