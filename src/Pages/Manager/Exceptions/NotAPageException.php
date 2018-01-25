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
// Time:     17:33
// Project:  lib-codeinclib
//
namespace CodeInc\GUI\Pages\Manager\Exceptions;


/**
 * Class NotAPageException
 *
 * @package CodeInc\GUI\Pages\Manager\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class NotAPageException extends PagesManagerException {
	private $pageClass;

	/**
	 * NotAPageException constructor.
	 *
	 * @param string $pagaClass
	 * @param int|null $code
	 * @param \Throwable|null $previous
	 */
	public function __construct(string $pagaClass, int $code = null, \Throwable $previous = null) {
		$this->pageClass = $pagaClass;
		parent::__construct("The class \"$pagaClass\" is not a page and can not be registered",
			$code, $previous);
}

	/**
	 * @return string
	 */
	public function getPageClass():string {
		return $this->pageClass;
	}
}