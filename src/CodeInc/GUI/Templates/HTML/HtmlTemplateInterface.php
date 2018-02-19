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
// Date:     13/02/2018
// Time:     18:01
// Project:  lib-gui
//
namespace CodeInc\GUI\Templates\HTML;
use CodeInc\GUI\Templates\TemplateInterface;


/**
 * Interface HtmlTemplateInterface
 *
 * @package CodeInc\GUI\Templates
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface HtmlTemplateInterface extends TemplateInterface {
	/**
	 * Adds a CSS link to the <head>.
	 *
	 * @param string $url
	 */
	public function addCss(string $url);

	/**
	 * Adds inline CSS code to the <head>.
	 *
	 * @param string $css
	 */
	public function addInlineCss(string $css);

	/**
	 * Adds a JS link to the <head>.
	 *
	 * @param string $url
	 */
	public function addJs(string $url);

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