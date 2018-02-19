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
// Time:     20:09
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Response\Library\Html;
use CodeInc\GUI\Pages\Interfaces\PageInterface;
use CodeInc\GUI\PagesManager\Response\Library\AbstractResponse;


/**
 * Class AbstractHtmlResponse
 *
 * @package CodeInc\GUI\PagesManager\Response\Library\Html
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractHtmlResponse extends AbstractResponse {
	public const DEFAULT_CHARSET = "UTF-8";
	public const DEFAULT_LANGUAGE = "en-US";

	/**
	 * Page's charset
	 *
	 * @var string
	 */
	protected $htmlCharset;

	/**
	 * <html> tag language
	 *
	 * @var string
	 */
	protected $htmlLanguage;

	/**
	 * Page's title
	 *
	 * @var string|null
	 */
	protected $htmlTitle;

	/**
	 * @var HtmlHeaders
	 */
	private $htmlHeaders;

	/**
	 * BlankHtml5Template constructor.
	 *
	 * @param PageInterface $page
	 * @param string $title
	 * @param string|null $charset
	 * @param string|null $language
	 */
	public function __construct(PageInterface $page, ?string $title = null, ?string $charset = null,
		?string $language = null) {

		parent::__construct($page);
		$this->htmlHeaders = new HtmlHeaders();

		if ($title) $this->setHtmlTitle($title);
		$this->setHtmlCharset($charset ?: self::DEFAULT_CHARSET, true);
		$this->setHtmlLanguage($language ?: self::DEFAULT_LANGUAGE);
	}

	/**
	 * Returns the HTML headers manager.
	 *
	 * @return HtmlHeaders
	 */
	public function getHtmlHeaders():HtmlHeaders {
		return $this->htmlHeaders;
	}

	/**
	 * Returns the HTML title
	 *
	 * @return null|string
	 */
	public function getHtmlTitle():?string {
		return $this->htmlTitle;
	}

	/**
	 * @param string $htmlTitle
	 */
	public function setHtmlTitle(string $htmlTitle):void {
		$this->htmlTitle = $htmlTitle;
	}

	/**
	 * @return string
	 */
	public function getHtmlCharset():string {
		return $this->htmlCharset;
	}

	/**
	 * @param string $htmlCharset
	 * @param bool|null $setHttpHeader
	 */
	public function setHtmlCharset(string $htmlCharset, bool $setHttpHeader = null):void {
		$this->htmlCharset = $htmlCharset;
		if ($setHttpHeader !== false) {
			$this->getHttpHeaders()->addHeader("Content-Type", "text/html; charset=$htmlCharset");
		}
	}

	/**
	 * @return string
	 */
	public function getHtmlLanguage():string {
		return $this->htmlLanguage;
	}

	/**
	 * @param string $htmlLanguage
	 */
	public function setHtmlLanguage(string $htmlLanguage):void {
		$this->htmlLanguage = $htmlLanguage;
	}
}