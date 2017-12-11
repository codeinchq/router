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
// Time:     15:46
// Project:  lib-gui
//
namespace CodeInc\GUI\Assets;


/**
 * Trait AssetsTrait
 *
 * @package CodeInc\GUI\Assets
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
trait AssetsTrait {
	/**
	 * Assets base namespace.
	 *
	 * @var string
	 */
	protected static $assetsBaseNamespace;

	/**
	 * Sets the assets base namespace.
	 *
	 * @param string $baseNamespace
	 */
	protected static function setAssetBaseNamespace(string $baseNamespace) {
		self::$assetsBaseNamespace = $baseNamespace;
	}

	/**
	 * Returns the class assets relative path.
	 *
	 * @param string|null $pathSeparator
	 * @return string
	 */
	protected static function getClassAssetsRelativePath(string $pathSeparator = null):string {
		$relPath = self::$assetsBaseNamespace ?
			substr(get_called_class(), strlen(self::$assetsBaseNamespace) + 1) :
			get_call_stack();
		if ($pathSeparator != "\\") {
			$relPath = str_replace("\\", $pathSeparator, $relPath);
		}
		return $relPath;
	}
}