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
namespace CodeInc\GUI\Templates;


/**
 * Class BlankHTMLTemplate
 *
 * @package CodeInc\GUI\Templates
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class BlankHTMLTemplate extends AbstractHTMLTemplate {
	/**
	 * <html> tag language
	 *
	 * @var string
	 */
	protected $language = "en-US";

	/**
	 * Page's charset
	 *
	 * @var string
	 */
	protected $charset = "UTF-8";

	/**
	 * Page's title
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Renders the header
	 */
	public function renderHeader() {
		?>
		<!DOCTYPE html>
		<html lang="<?=htmlspecialchars($this->language)?>">
			<head>
				<meta charset="<?=htmlspecialchars($this->charset)?>">
				<title><?=$this->title?></title>
				<?=$this->getHeadersAsString()?>
			</head>

			<body>
		<?
	}

	/**
	 * Renders the footer
	 */
	public function renderFooter() {
		?>
			</body>
		</html>
		<?
	}
}