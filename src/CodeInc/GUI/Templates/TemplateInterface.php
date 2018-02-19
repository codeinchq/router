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
// Date:     10/11/2017
// Time:     14:21
// Project:  lib-gui
//
namespace CodeInc\GUI\Templates;
use CodeInc\GUI\Pages\Interfaces\PageInterface;


/**
 * Interface TemplateInterface
 *
 * @package CodeInc\GUI\Templates\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface TemplateInterface {
	/**
	 * Returns the parent page.
	 *
	 * @return PageInterface
	 */
	public function getPage():PageInterface;

	/**
	 * Renders the template's header.
	 *
	 * @return void
	 */
	public function renderHeader();

	/**
	 * Renders the template's footer.
	 *
	 * @return void
	 */
	public function renderFooter();
}