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
use CodeInc\GUI\Templates\AbstractTemplate;


/**
 * Class AbstractHtmlTemplate
 *
 * @package CodeInc\GUI\Templates
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractHtmlTemplate extends AbstractTemplate implements HtmlTemplateInterface {
	/**
	 * Global headers
	 *
	 * @var array
	 */
	private $headers = [];

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
	public function addHeader(string $header):void {
		$this->headers[] = $header;
	}

	/**
	 * Adds a CSS link to the <head>.
	 *
	 * @param string $url
	 */
	public function addCss(string $url):void {
		$this->addHeader('<link rel="stylesheet" type="text/css" href="'.htmlspecialchars($url).'">');
	}

	/**
	 * Adds inline CSS code to the <head>.
	 *
	 * @param string $css
	 */
	public function addInlineCss(string $css):void {
		$this->addHeader('<style type="text/css">'.$css.'</style>');
	}

	/**
	 * Adds a JS link to the <head>.
	 *
	 * @param string $url
	 * @param string|null $integrity
	 * @param string|null $crossorigin
	 */
	public function addJs(string $url, ?string $integrity = null, ?string $crossorigin = null):void {
		$tag = '<script src="'.htmlspecialchars($url).'"';
		if ($integrity) {
			$tag .= ' integrity="'.htmlspecialchars($integrity).'"';
		}
		if ($crossorigin) {
			$tag .= ' crossorigin="'.htmlspecialchars($crossorigin).'"';
		}
		$tag .= '></script>';
		$this->addHeader($tag);
	}

	/**
	 * Adds inline JS code to the <head>.
	 *
	 * @param string $js
	 */
	public function addInlineJs(string $js):void {
		$this->addHeader('<script>'.$js.'</script>');
	}
}