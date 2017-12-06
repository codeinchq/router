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
// Date:     04/12/2017
// Time:     17:35
// Project:  lib-codeinclib
//
namespace CodeInc\GUI\Pages\PagesManager\Exceptions;
use CodeInc\GUI\Pages\Interfaces\PageInterface;
use Throwable;


/**
 * Class PageRenderingException
 *
 * @package CodeInc\GUI\Pages\PagesManager\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PageRenderingException extends PagesManagerException {
	/**
	 * @var PageInterface
	 */
	private $page;

	/**
	 * @var string
	 */
	private $URI;

	/**
	 * PageRenderingException constructor.
	 *
	 * @param PageInterface $page
	 * @param string $URI
	 * @param int $code
	 * @param Throwable|null $previous
	 */
	public function __construct(PageInterface $page, string $URI, int $code = 0, Throwable $previous = null) {
		$this->page = $page;
		$this->URI = $URI;
		parent::__construct("Erreur while rendering the page ".get_class($page)." for the URI \"$URI\"",
			$code, $previous);
	}

	/**
	 * @return PageInterface
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @return string
	 */
	public function getURI():string {
		return $this->URI;
	}
}