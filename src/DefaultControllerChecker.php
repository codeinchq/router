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
// Date:     07/03/2018
// Time:     19:58
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router;
use CodeInc\Router\Interfaces\ControllerCheckerInterface;
use CodeInc\Router\Interfaces\ControllerInterface;


/**
 * Class DefaultControllerChecker
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class DefaultControllerChecker implements ControllerCheckerInterface {
	/**
	 * @inheritdoc
	 */
	public function isAController(string $controllerClass):bool
	{
		return is_subclass_of($controllerClass, ControllerInterface::class);
	}
}