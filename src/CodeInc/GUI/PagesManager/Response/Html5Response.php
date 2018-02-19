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
namespace CodeInc\GUI\PagesManager\Response;
use CodeInc\GUI\Pages\Interfaces\PageInterface;


/**
 * Class Html5Response
 *
 * @package CodeInc\GUI\PagesManager\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Html5Response extends AbstractResponse {
	public const DEFAULT_CHARSET = "UTF-8";
	public const DEFAULT_LANGUAGE = "en-US";

	/**
	 * Page's charset
	 *
	 * @var string
	 */
	protected $pageCharset;

	/**
	 * <html> tag language
	 *
	 * @var string
	 */
	protected $pageLanguage;

	/**
	 * Page's title
	 *
	 * @var string|null
	 */
	protected $pageTitle;

	/**
	 * Global headers
	 *
	 * @var array
	 */
	private $htmlHeaders = [];

	/**
	 * @var string
	 */
	private $htmlBody;

	/**
	 * BlankHtml5Template constructor.
	 *
	 * @param PageInterface $page
	 * @param string $title
	 * @param string|null $charset
	 * @param string|null $language
	 */
	public function __construct(PageInterface $page, ?string $title = null, ?string $charset = null, ?string $language = null) {
		parent::__construct($page);
		$this->pageTitle = $title;
		$this->setPageCharset($charset ?: self::DEFAULT_CHARSET);
		$this->pageLanguage = $language ?: self::DEFAULT_LANGUAGE;
	}

	/**
	 * @return null|string
	 */
	public function getPageTitle():?string {
		return $this->pageTitle;
	}

	/**
	 * @param string $pageTitle
	 */
	public function setPageTitle(string $pageTitle):void {
		$this->pageTitle = $pageTitle;
	}

	/**
	 * @return string
	 */
	public function getPageCharset():string {
		return $this->pageCharset;
	}

	/**
	 * @param string $pageCharset
	 */
	public function setPageCharset(string $pageCharset):void {
		$this->pageCharset = $pageCharset;
		$this->setHttpHeader("Content-Type", "text/html; charset=$pageCharset");
	}

	/**
	 * @return string
	 */
	public function getPageLanguage():string {
		return $this->pageLanguage;
	}

	/**
	 * @param string $pageLanguage
	 */
	public function setPageLanguage(string $pageLanguage):void {
		$this->pageLanguage = $pageLanguage;
	}

	/**
	 * Adds an header to the <head>.
	 *
	 * @param string $header
	 */
	public function addHtmlHeader(string $header):void {
		$this->htmlHeaders[] = $header;
	}

	/**
	 * Adds a CSS link to the <head>.
	 *
	 * @param string $url
	 */
	public function addCssHeader(string $url):void {
		$this->addHtmlHeader('<link rel="stylesheet" type="text/css" href="'.htmlspecialchars($url).'">');
	}

	/**
	 * Adds inline CSS code to the <head>.
	 *
	 * @param string $css
	 */
	public function addInlineCssHeader(string $css):void {
		$this->addHtmlHeader('<style type="text/css">'.$css.'</style>');
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
		$this->addHtmlHeader($tag);
	}

	/**
	 * Adds inline JS code to the <head>.
	 *
	 * @param string $js
	 */
	public function addInlineJsHeader(string $js):void {
		$this->addHtmlHeader('<script>'.$js.'</script>');
	}

	/**
	 * Returns the HTML headers.
	 *
	 * @return array
	 */
	public function getHtmlHeaders():array {
		return $this->htmlHeaders;
	}

	/**
	 * Returns the HTML <body> content.
	 *
	 * @return string
	 */
	public function getHtmlBody():string {
		return $this->htmlBody;
	}

	/**
	 * Sets the HTML <body> content.
	 *
	 * @param string $htmlBody
	 */
	public function setHtmlBody(string $htmlBody):void {
		$this->htmlBody = $htmlBody;
	}

	/**
	 * Adds content to the HTML <body>.
	 *
	 * @param string $html
	 */
	public function addHtmlBody(string $html):void {
		$this->htmlBody .= $html;
	}

	/**
	 * Renders the header
	 */
	public function getContent():string {
		ob_start();
		?>
		<!DOCTYPE html>
			<html lang="<?=htmlspecialchars($this->getPageLanguage())?>">
			<head>
				<meta charset="<?=htmlspecialchars($this->getPageCharset())?>">
				<title><?=htmlspecialchars($this->getPageTitle())?></title>
				<?=implode("\n", $this->getHtmlHeaders())?>
			</head>

			<body>
				<?=$this->getHtmlBody()?>
			</body>
		</html>
		<?
		return ob_get_clean();
	}
}