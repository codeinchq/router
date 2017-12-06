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
// Date:     06/12/2017
// Time:     19:06
// Project:  lib-gui
//
namespace CodeInc\GUI\Views;


/**
 * Interface ViewGetInterface
 *
 * @package CodeInc\GUI\Views
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface ViewGetInterface extends ViewInterface {
	/**
	 * Returns the view's HTML code.
	 *
	 * @throws ViewException
	 * @return string
	 */
	public function get():string;

	/**
	 * Alias of get()
	 *
	 * @see ViewInterface::get()
	 * @throws ViewException
	 * @return string
	 */
	public function __toString():string;
}