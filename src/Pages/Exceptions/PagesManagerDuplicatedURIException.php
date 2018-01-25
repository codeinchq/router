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
// Date:     25/01/2018
// Time:     13:10
// Project:  lib-gui
//
namespace CodeInc\GUI\Pages\Exceptions;
use Throwable;


/**
 * Class PagesManagerDuplicatedURIException
 *
 * @package CodeInc\GUI\Pages\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PagesManagerDuplicatedURIException extends PagesManagerException {
	/**
	 * @var string
	 */
	private $pageURI;

	/**
	 * PagesManagerDuplicatedURIException constructor.
	 *
	 * @param string $pageURI
	 * @param Throwable|null $previous
	 */
	public function __construct(string $pageURI, Throwable $previous = null) {
		$this->pageURI = $pageURI;
		parent::__construct("The URI \"$pageURI\" is already registered", 0, $previous);
	}

	/**
	 * @return string
	 */
	public function getPageURI():string {
		return $this->pageURI;
	}
}