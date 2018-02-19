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
// Date:     16/02/2018
// Time:     10:57
// Project:  lib-router
//
namespace CodeInc\Router\Request\Exceptions;
use CodeInc\Router\Request\RequestInterface;
use Throwable;


/**
 * Class RequestQueryMissingRequiredParameterException
 *
 * @package CodeInc\GUI\PagesManager\Request\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MissingRequiredParameterException extends RequestException {
	/**
	 * @var string
	 */
	private $paramName;

	/**
	 * @var string
	 */
	private $gpcArray;

	/**
	 * MissingRequiredParameterException constructor.
	 *
	 * @param string $paramName
	 * @param string $GPCArray
	 * @param RequestInterface|null $request
	 * @param null|Throwable $previous
	 */
	public function __construct(string $paramName, string $GPCArray, ?RequestInterface $request = null,
		?Throwable $previous = null) {

		$this->paramName = $paramName;
		$this->gpcArray = $GPCArray;

		parent::__construct(
			"The required parameter \"$paramName\" from the GPC array \"$GPCArray\" is missing",
			$request,
			$previous
		);
	}

	/**
	 * @return string
	 */
	public function getParamName():string {
		return $this->paramName;
	}

	/**
	 * @return string
	 */
	public function getGpcArray():string {
		return $this->gpcArray;
	}
}