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
// Time:     20:04
// Project:  lib-router
//
namespace CodeInc\Router\Exceptions;
use CodeInc\Router\RouterInterface;
use Throwable;


/**
 * Class UnknownTargetTypeException
 *
 * @package CodeInc\Router\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class UnknownTargetTypeException extends RouterException {
	/**
	 * @var mixed
	 */
	private $target;

	/**
	 * UnknownTargetTypeException constructor.
	 *
	 * @param mixed $target
	 * @param RouterInterface|null $router
	 * @param null|Throwable $previous
	 */
	public function __construct($target, ?RouterInterface $router = null, ?Throwable $previous = null) {
		$this->target = $target;
		parent::__construct("The target type ".gettype($target)." can not be processed",
			$router, $previous);
	}

	/**
	 * @return mixed
	 */
	public function getTarget() {
		return $this->target;
	}
}