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
// Date:     13/02/2018
// Time:     13:06
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Exceptions;
use CodeInc\GUI\GuiException;
use CodeInc\GUI\PagesManager\PagesManagerInterface;
use Throwable;


/**
 * Class PagesManagerException
 *
 * @package CodeInc\GUI\PagesManager\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PagesManagerException extends GuiException {
	/**
	 * @var PagesManagerInterface
	 */
	private $pagesManager;

	/**
	 * PagesManagerException constructor.
	 *
	 * @param string $message
	 * @param PagesManagerInterface $pagesManager
	 * @param null|Throwable $previous
	 */
	public function __construct(string $message, PagesManagerInterface $pagesManager, ?Throwable $previous = null) {
		$this->pagesManager = $pagesManager;
		parent::__construct($message, $previous);
	}

	/**
	 * @return PagesManagerInterface
	 */
	public function getPagesManager():PagesManagerInterface {
		return $this->pagesManager;
	}
}