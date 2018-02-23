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
// Time:     18:35
// Project:  lib-router
//
declare(strict_types=1);
namespace CodeInc\Router\Exceptions;
use CodeInc\Router\RoutableInterface;
use CodeInc\Router\RouterInterface;
use Throwable;


/**
 * Class InvalidTargetException
 *
 * @package CodeInc\Router\Exceptions
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class InvalidTargetException extends RouterException {
	/**
	 * @var string
	 */
	private $targetClass;

	/**
	 * InvalidTargetException constructor.
	 *
	 * @param $target
	 * @param RouterInterface $router
	 * @param int|null $code
	 * @param null|Throwable $previous
	 */
	public function __construct($target, RouterInterface $router, ?int $code = null, ?Throwable $previous = null) {
		$this->targetClass = is_object($target) ? get_class($target) : (string)$target;
		parent::__construct(
			sprintf(
				"The class %s is not a page and (all route targets must implement %s)",
				$this->targetClass,
				RoutableInterface::class
			),
			$router, $code, $previous);
	}

	/**
	 * @return string
	 */
	public function getTargetClass():string {
		return $this->targetClass;
	}
}