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
// Time:     15:32
// Project:  lib-router
//
namespace CodeInc\GUI\Views;
use CodeInc\GUI\GuiException;
use Throwable;


/**
 * Class ViewException
 *
 * @package CodeInc\GUI\Views
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ViewException extends GuiException {
	/**
	 * @var ViewInterface|null
	 */
	private $view;

	/**
	 * ViewException constructor.
	 *
	 * @param string $message
	 * @param ViewInterface|null $view
	 * @param null|Throwable $previous
	 */
	public function __construct(string $message, ?ViewInterface $view =null, ?Throwable $previous = null) {
		$this->view = $view;
		parent::__construct($message, $previous);
	}

	/**
	 * @return ViewInterface|null
	 */
	public function getView():?ViewInterface {
		return $this->view;
	}
}