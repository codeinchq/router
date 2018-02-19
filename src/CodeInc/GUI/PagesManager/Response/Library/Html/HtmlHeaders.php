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
// Date:     19/02/2018
// Time:     20:55
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Response\Library\Html;


/**
 * Class HtmlHeaders
 *
 * @package CodeInc\GUI\PagesManager\Response\Library\Html
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class HtmlHeaders implements \Iterator {
	/**
	 * Global headers
	 *
	 * @var array
	 */
	private $htmlHeaders = [];

	/**
	 * @var int
	 */
	private $iteratorIndex;

	/**
	 * Adds an header to the <head>.
	 *
	 * @param string $header
	 */
	public function addHeader(string $header):void {
		$this->htmlHeaders[] = $header;
	}

	/**
	 * Adds a CSS link to the <head>.
	 *
	 * @param string $url
	 */
	public function addCssHeader(string $url):void {
		$this->addHeader('<link rel="stylesheet" type="text/css" href="'.htmlspecialchars($url).'">');
	}

	/**
	 * Adds inline CSS code to the <head>.
	 *
	 * @param string $css
	 */
	public function addInlineCssHeader(string $css):void {
		$this->addHeader('<style type="text/css">'.$css.'</style>');
	}

	/**
	 * Adds a JS link to the <head>.
	 *
	 * @param string $url
	 * @param string|null $integrity
	 * @param string|null $crossorigin
	 */
	public function addJsHeader(string $url, ?string $integrity = null, ?string $crossorigin = null):void {
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
	public function addInlineJsHeader(string $js):void {
		$this->addHeader('<script>'.$js.'</script>');
	}

	/**
	 * Returns the HTML headers.
	 *
	 * @return array
	 */
	public function getHeaders():array {
		return $this->htmlHeaders;
	}

	/**
	 * Returns the HTML headers as a string or null if no header is set.
	 *
	 * @param string|null $glue
	 * @return null|string
	 */
	public function getHeadersAsString(string $glue = null):?string {
		return $this->htmlHeaders ? implode($glue ?: "\n", $this->htmlHeaders) : null;
	}

	/**
	 * @inheritdoc
	 */
	public function rewind():void {
		$this->iteratorIndex = 0;
	}

	/**
	 * @inheritdoc
	 * @return string
	 */
	public function current():string {
		return $this->htmlHeaders[$this->iteratorIndex];
	}

	/**
	 * @inheritdoc
	 * @return string
	 */
	public function key():string {
		return $this->htmlHeaders[$this->iteratorIndex];
	}

	/**
	 * @inheritdoc
	 */
	public function next():void {
		$this->iteratorIndex++;
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function valid():bool {
		return isset($this->htmlHeaders[$this->iteratorIndex]);
	}
}