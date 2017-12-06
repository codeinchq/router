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
// Time:     14:20
// Project:  lib-gui
//
namespace CodeInc\GUI\Templates;
use CodeInc\GUI\Templates\Interfaces\HTMLTemplateInterface;
use CodeInc\GUI\Views\HTMLTag;
use CodeInc\GUI\Views\ViewException;

/**
 * Class AbstractTemplate
 *
 * @package CodeInc\GUI\Templates
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractTemplate implements HTMLTemplateInterface {
	/**
	 * Global headers
	 *
	 * @var array
	 */
	private $headers = [];

	/**
	 * Classes added to the <html> tag.
	 *
	 * @var string
	 */
	private $HTMLClasses;

	/**
	 * Sets all the page classes (added to the <html> tag).
	 *
	 * @param string $class
	 */
	public function setHTMLClass(string $class) {
		$this->HTMLClasses = $class;
	}

	/**
	 * Returns all the page classes (added to the <html> tag).
	 *
	 * @return string
	 */
	public function getHTMLClass():string {
		return $this->HTMLClasses;
	}

	/**
	 * Returns all the page's headers
	 *
	 * @return array
	 */
	protected function getHeaders():array {
		return $this->headers;
	}

	/**
	 * Returns all headers as a string.
	 *
	 * @param string|null $glue
	 * @return string
	 */
	protected function getHeadersAsString(string $glue = null):string {
		if ($this->headers) {
			return implode($glue ?? "\n", $this->headers);
		}
		return '';
	}

	/**
	 * Adds an header to the <head>.
	 *
	 * @param string $header
	 */
	public function addHeader(string $header) {
		$this->headers[] = $header;
	}

	/**
	 * Adds a CSS link to the <head>.
	 *
	 * @param string $uri
	 * @throws ViewException
	 */
	public function addCSS(string $uri) {
		$this->addHeader((new HTMLTag('link', ['rel' => 'stylesheet', 'type' => 'text/css', 'href' => $uri]))->get());
	}

	/**
	 * Adds inline CSS code to the <head>.
	 *
	 * @param string $css
	 */
	public function addInlineCSS(string $css) {
		$this->addHeader('<style type="text/css">'.$css.'</style>');
	}

	/**
	 * Adds a JS link to the <head>.
	 *
	 * @param string $uri
	 * @param string|null $integrity
	 * @param string|null $crossorigin
	 * @throws ViewException
	 */
	public function addJS(string $uri, string $integrity = null, string $crossorigin = null) {
		$tag = new HTMLTag('script', ['src' => $uri]);
		if ($integrity) $tag['integrity'] = $integrity;
		if ($crossorigin) $tag['crossorigin'] = $crossorigin;
		$this->addHeader($tag->get().$tag->getClosure());
	}

	/**
	 * Adds inline JS code to the <head>.
	 *
	 * @param string $js
	 */
	public function addInlineJs(string $js) {
		$this->addHeader('<script>'.$js.'</script>');
	}
}