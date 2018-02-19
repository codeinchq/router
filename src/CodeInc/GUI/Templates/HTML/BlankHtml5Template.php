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
// Date:     24/01/2018
// Time:     12:22
// Project:  lib-gui
//
namespace CodeInc\GUI\Templates\HTML;
use CodeInc\GUI\Pages\Interfaces\PageInterface;


/**
 * Class BlankHTMLTemplate
 *
 * @package CodeInc\GUI\Templates
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class BlankHtml5Template extends AbstractHtmlTemplate {
	public const DEFAULT_CHARSET = "UTF-8";
	public const DEFAULT_LANGUAGE = "en-US";

	/**
	 * Page's charset
	 *
	 * @var string
	 */
	protected $charset;

	/**
	 * <html> tag language
	 *
	 * @var string
	 */
	protected $language;

	/**
	 * Page's title
	 *
	 * @var string|null
	 */
	protected $title;

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
		$this->title = $title;
		$this->charset = $charset ?: self::DEFAULT_CHARSET;
		$this->language = $language ?: self::DEFAULT_LANGUAGE;
	}

	/**
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
	 * @param string $charset
	 */
	public function setCharset(string $charset):void {
		$this->charset = $charset;
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

	/**
	 * Renders the header
	 */
	public function renderHeader():void {
		?>
		<!DOCTYPE html>
		<html lang="<?=htmlspecialchars($this->getLanguage())?>">
			<head>
				<meta charset="<?=htmlspecialchars($this->getCharset())?>">
				<title><?=$this->getTitle()?></title>
				<?=$this->getHeadersAsString()?>
			</head>

			<body>
		<?
	}

	/**
	 * Renders the footer
	 */
	public function renderFooter():void {
		?>
			</body>
		</html>
		<?
	}
}