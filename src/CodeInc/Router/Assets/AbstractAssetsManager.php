<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
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
// Date:     11/12/2017
// Time:     16:55
// Project:  lib-router
//
namespace CodeInc\Router\Assets;


/**
 * Class AbstractAssetsManager
 *
 * @package CodeInc\GUI\Assets
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractAssetsManager {
	/**
	 * Returns the class relative path.
	 *
	 * @param string $className
	 * @param string|null $baseNamespace
	 * @param string|null $pathSeparator
	 * @return string
	 */
	protected function getClassRelativePath(string $className, string $baseNamespace = null, string $pathSeparator = null):string {
		if ($baseNamespace) {
			$className = substr($className, strlen($baseNamespace) + 1);
		}
		if ($pathSeparator != "\\") {
			$className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
		}
		return $className;
	}
}