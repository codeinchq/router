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
// Time:     12:37
// Project:  lib-gui
//
namespace CodeInc\GUI;
use Throwable;


/**
 * Class GuiException
 *
 * @package CodeInc\GUI
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class GuiException extends \Exception {
	/**
	 * GUIException constructor.
	 *
	 * @param string $message
	 * @param Throwable|null $previous
	 */
	public function __construct(string $message, Throwable $previous = null) {
		parent::__construct($message, 0, $previous);
	}
}