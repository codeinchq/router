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
// Time:     15:21
// Project:  lib-router
//
namespace CodeInc\GUI\Templates\HTML;


/**
 * Class SimpleHtml5Template
 *
 * @package CodeInc\GUI\Templates\HTML
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class SimpleHtml5Template extends AbstractHtmlTemplate {
	/**
	 * @inheritdoc
	 * @return string
	 */
	public function getHeader():string {
		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="<?=htmlspecialchars($this->getLanguage())?>">
			<head>
				<meta charset="<?=htmlspecialchars($this->getCharset())?>">
				<title><?=htmlspecialchars($this->getTitle())?></title>
				<?=$this->getHeaders()->getAsString()?>
			</head>

			<body>
		<?
		return ob_get_clean();
	}

	/**
	 * @inheritdoc
	 * @return string
	 */
	public function getFooter():string {
		ob_start();
		?>
			</body>
		</html>
		<?
		return ob_get_clean();
	}
}