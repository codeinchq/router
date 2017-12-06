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
// Date:     28/11/2017
// Time:     16:35
// Project:  lib-gui
//
namespace CodeInc\GUI\Pages\Exceptions;
use CodeInc\GUI\GUIException;
use CodeInc\GUI\Pages\Interfaces\PageInterface;


/**
 * Class PageException
 *
 * @package CodeInc\GUI\Pages
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PageException extends GUIException {
	/**
	 * @var PageInterface
	 */
	private $parentPage;

	/**
	 * PageException constructor.
	 *
	 * @param PageInterface $parentPage
	 * @param string $message
	 * @param int $code
	 * @param \Throwable|null $previous
	 */
	public function __construct(PageInterface $parentPage, string $message = "", int $code = 0, \Throwable $previous = null) {
		$this->parentPage = $parentPage;
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Retruns the parent page.
	 *
	 * @return PageInterface|null
	 */
	public function getParentPage() {
		return $this->parentPage;
	}
}