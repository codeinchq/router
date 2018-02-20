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
// Date:     20/02/2018
// Time:     15:30
// Project:  lib-router
//
namespace CodeInc\GUI\Templates\HTML;
use CodeInc\GUI\Templates\TemplateInterface;


/**
 * Class AbstractHtmlTemplate
 *
 * @package CodeInc\GUI\Templates\HTML
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractHtmlTemplate implements TemplateInterface {
	public const DEFAULT_CHARSET = "UTF-8";
	public const DEFAULT_LANGUAGE = "en-US";

	/**
	 * Page's charset
	 *
	 * @var string
	 */
	protected $charset = self::DEFAULT_CHARSET;

	/**
	 * <html> tag language
	 *
	 * @var string
	 */
	protected $language = self::DEFAULT_LANGUAGE;

	/**
	 * Page's title
	 *
	 * @var string|null
	 */
	protected $title;

	/**
	 * HTML headers
	 *
	 * @var HtmlHeaders
	 */
	private $headers;

	/**
	 * AbstractHtmlTemplate constructor.
	 *
	 * @param HtmlHeaders|null $headers
	 */
	public function __construct(?HtmlHeaders $headers = null) {
		$this->headers = $headers ?? new HtmlHeaders();
	}

	/**
	 * Returns the HTML headers manager.
	 *
	 * @return HtmlHeaders
	 */
	public function getHeaders():HtmlHeaders {
		return $this->headers;
	}

	/**
	 * Returns the HTML title
	 *
	 * @return null|string
	 */
	public function getTitle():?string {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle(string $title):void {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getCharset():string {
		return $this->charset;
	}

	/**
	 * @param string $htmlCharset
	 */
	public function setCharset(string $htmlCharset):void {
		$this->charset = $htmlCharset;
	}

	/**
	 * @return string
	 */
	public function getLanguage():string {
		return $this->language;
	}

	/**
	 * @param string $language
	 */
	public function setLanguage(string $language):void {
		$this->language = $language;
	}
}