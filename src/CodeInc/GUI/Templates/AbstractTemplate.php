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
// Time:     14:00
// Project:  lib-gui
//
namespace CodeInc\GUI\Templates;
use CodeInc\GUI\Pages\Interfaces\PageInterface;


/**
 * Class AbstractTemplate
 *
 * @package CodeInc\GUI\Templates
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractTemplate implements TemplateInterface {
	/**
	 * Parent page.
	 *
	 * @var PageInterface
	 */
	private $page;

	/**
	 * AbstractTemplate constructor.
	 *
	 * @param PageInterface $page
	 */
	public function __construct(PageInterface $page) {
		$this->page = $page;
	}

	/**
	 * @inheritdoc
	 */
	public function getPage():PageInterface {
		return $this->page;
	}
}