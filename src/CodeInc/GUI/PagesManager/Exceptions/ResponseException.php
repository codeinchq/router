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
// Time:     15:39
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Exceptions;
use CodeInc\GUI\PagesManager\PagesManagerInterface;
use CodeInc\GUI\PagesManager\Response\ResponseInterface;
use Throwable;


/**
 * Class ResponseException
 *
 * @package CodeInc\GUI\PagesManager\Response\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ResponseException extends PagesManagerException {
	/**
	 * @var ResponseInterface
	 */
	private $response;

	/**
	 * ResponseException constructor.
	 *
	 * @param string $message
	 * @param ResponseInterface $response
	 * @param PagesManagerInterface $pagesManager
	 * @param null|Throwable $previous
	 */
	public function __construct(string $message, ResponseInterface $response, PagesManagerInterface $pagesManager,
		?Throwable $previous = null) {

		$this->response = $response;
		parent::__construct($message, $pagesManager, $previous);
	}

	/**
	 * @return ResponseInterface
	 */
	public function getResponse():ResponseInterface {
		return $this->response;
	}
}