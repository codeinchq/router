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
// Date:     25/01/2018
// Time:     13:24
// Project:  lib-gui
//
namespace CodeInc\GUI\Pages\Manager\Exceptions;
use Throwable;


/**
 * Class TranslatredUriNotFoundException
 *
 * @package CodeInc\GUI\Pages\Manager\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class TranslatredUriNotFoundException extends PagesManagerException {
	/**
	 * @var string
	 */
	private $pageClass;

	/**
	 * @var string
	 */
	private $language;

	/**
	 * PagesManagerTranslatredUriNotFoundException constructor.
	 *
	 * @param string $pageClass
	 * @param string $language
	 * @param Throwable|null $previous
	 */
	public function __construct(string $pageClass, string $language, Throwable $previous = null) {
		$this->pageClass = $pageClass;
		$this->language = $language;
		parent::__construct("There is not translation of the page \"$pageClass\" for the language \"$language\"",
			0, $previous);
	}

	/**
	 * @return string
	 */
	public function getPageClass():string {
		return $this->pageClass;
	}

	/**
	 * @return string
	 */
	public function getLanguage():string {
		return $this->language;
	}
}