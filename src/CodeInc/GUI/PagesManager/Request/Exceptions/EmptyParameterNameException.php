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
// Time:     12:03
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Request\Exceptions;
use CodeInc\GUI\PagesManager\Request\RequestInterface;
use Throwable;


/**
 * Class EmptyParameterNameException
 *
 * @package CodeInc\GUI\PagesManager\Request\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class EmptyParameterNameException extends RequestException {
	/**
	 * EmptyParameterNameException constructor.
	 *
	 * @param RequestInterface|null $request
	 * @param null|Throwable $previous
	 */
	public function __construct(?RequestInterface $request = null, ?Throwable $previous = null) {
		parent::__construct("The request query parameter can not be empty", $request, $previous);
	}
}