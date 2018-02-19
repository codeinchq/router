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
// Date:     12/02/2018
// Time:     13:25
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Response\Library;
use CodeInc\GUI\Pages\Interfaces\PageInterface;


/**
 * Class Response
 *
 * @package CodeInc\GUI\PagesManager\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class SimpleContentResponse extends AbstractResponse {
	/**
	 * @var string|null
	 */
	private $content;

	/**
	 * Response constructor.
	 *
	 * @param PageInterface $page
	 * @param null|string $content
	 */
	public function __construct(PageInterface $page, ?string $content = null) {
		parent::__construct($page);
		$this->setContent($content);
	}

	/**
	 * @inheritdoc
	 */
	public function sendContent():void {
		echo $this->content;
	}

	/**
	 * @inheritdoc
	 */
	public function setContent(?string $content):void {
		$this->content = $content;
	}

	/**
	 * @inheritdoc
	 */
	public function addContent(string $content):void {
		$this->content .= $content;
	}
}