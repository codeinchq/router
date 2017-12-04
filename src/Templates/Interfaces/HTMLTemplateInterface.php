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
// Time:     17:18
// Project:  lib-gui
//
namespace CodeInc\GUI\Templates\Interfaces;


/**
 * Interface HTMLTemplateInterface
 *
 * @package CodeInc\GUI\Templates\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface HTMLTemplateInterface extends TemplateInterface {
	/**
	 * Sets all the page classes (added to the <html> tag).
	 *
	 * @param string $class
	 */
	public function setHTMLClass(string $class);

	/**
	 * Returns all the page classes (added to the <html> tag).
	 *
	 * @return string
	 */
	public function getHTMLClass():string;

	/**
	 * Adds a CSS link to the <head>.
	 *
	 * @param string $uri
	 */
	public function addCSS(string $uri);

	/**
	 * Adds inline CSS code to the <head>.
	 *
	 * @param string $css
	 */
	public function addInlineCSS(string $css);

	/**
	 * Adds a JS link to the <head>.
	 *
	 * @param string $uri
	 */
	public function addJS(string $uri);

	/**
	 * Adds inline JS code to the <head>.
	 *
	 * @param string $js
	 */
	public function addInlineJs(string $js);

	/**
	 * Adds an header to the <head>.
	 *
	 * @param string $header
	 */
	public function addHeader(string $header);
}