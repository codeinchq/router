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
// Date:     11/12/2017
// Time:     15:08
// Project:  lib-gui
//
namespace CodeInc\GUI\Pages\Manager\Exceptions;
use Throwable;


/**
 * Class NotFoundPageNotSetException
 *
 * @package CodeInc\GUI\Pages\Manager\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class NotFoundPageNotSetException extends PagesManagerException {
	/**
	 * PagesManagerNotFoundNotSetException constructor.
	 *
	 * @param Throwable|null $previous
	 */
	public function __construct(Throwable $previous = null) {
		parent::__construct("The not found page is not set", 0, $previous);
	}
}