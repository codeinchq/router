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
// Date:     22/11/2017
// Time:     17:09
// Project:  lib-gui
//
namespace CodeInc\GUI\Views;


/**
 * Interface ViewInterface
 *
 * @package CodeInc\GUI\Views\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface ViewInterface {
	/**
	 * Renders the view.
	 *
	 * @throws ViewException
	 * @return void
	 */
	public function render();

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