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
// Time:     21:00
// Project:  lib-router
//
namespace CodeInc\Router\Responses\HTML;


/**
 * Class SimpleHtmlResponse
 *
 * @package CodeInc\GUI\Responses\HTML
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class SimpleHtmlResponse extends AbstractHtmlResponse {
	/**
	 * @var string
	 */
	protected $htmlBody;

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
	 * Sends the content.
	 */
	public function sendContent():void {
		?>
		<!DOCTYPE html>
		<html lang="<?=htmlspecialchars($this->getHtmlLanguage())?>">
			<head>
				<meta charset="<?=htmlspecialchars($this->getHtmlCharset())?>">
				<title><?=htmlspecialchars($this->getHtmlTitle())?></title>
				<?=$this->getHtmlHeaders()->getHeadersAsString()?>
			</head>

			<body>
				<?=$this->getHtmlBody()?>
			</body>
		</html>
		<?
	}
}