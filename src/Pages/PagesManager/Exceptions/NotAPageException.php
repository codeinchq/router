<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.co for more information about licensing.  |
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
namespace CodeInc\GUI\Pages\PagesManager\Exceptions;


/**
 * Class NotAPageException
 *
 * @package CodeInc\GUI\Pages\PagesManager\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class NotAPageException extends PagesManagerException {
	private $class;

	/**
	 * NotAPageException constructor.
	 *
	 * @param string $class
	 * @param int|null $code
	 * @param \Throwable|null $previous
	 */
	public function __construct(string $class, int $code = null, \Throwable $previous = null) {
		$this->class = $class;
		parent::__construct("The class \"$class\" is not a page and can not be registered",
			$code, $previous);
}

	/**
	 * @return string
	 */
	public function getClass():string {
		return $this->class;
	}
}