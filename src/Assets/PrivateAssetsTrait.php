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
// Time:     15:19
// Project:  lib-gui
//
namespace CodeInc\GUI\Assets;
use CodeInc\GUI\Assets\Exception\PrivateAssetNotFoundException;
use CodeInc\GUI\Assets\Exception\PrivateAssetsBasePathNotSetException;
use CodeInc\GUI\Assets\Interfaces\PrivateAssetsInterface;


/**
 * Trait PrivateAssetsTrait
 *
 * @package CodeInc\GUI\Assets
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @see PrivateAssetsInterface
 */
trait PrivateAssetsTrait {
	/**
	 * Private assets base path.
	 *
	 * @var string
	 */
	private static $privateAssetsBasePath;

	/**
	 * Sets the private assets base path
	 *
	 * @param string $basePath
	 */
	protected static function setPrivateAssetsBasePath(string $basePath) {
		self::$privateAssetsBasePath = $basePath;
	}

	/**
	 * Return the private assets base path
	 *
	 * @return string
	 * @throws PrivateAssetsBasePathNotSetException
	 */
	protected static function getPrivateAssetsBasePath():string {
		if (!self::$privateAssetsBasePath) {
			throw new PrivateAssetsBasePathNotSetException();
		}
		return self::$privateAssetsBasePath;
	}

	/**
	 * Returns a private asset path. Throws a PrivateAssetsBasePathNotSetException if the base path is not set
	 * and a PrivateAssetNotFoundException if the private asset does not exist.
	 *
	 * @param string $asset
	 * @return string
	 * @throws PrivateAssetsBasePathNotSetException
	 * @throws PrivateAssetNotFoundException
	 */
	public static function getPrivateAssetPath(string $asset) {
		$assetPath = self::getPrivateAssetsBasePath().$asset;
		if (!file_exists($assetPath)) {
			throw new PrivateAssetNotFoundException($asset, $assetPath);
		}
		return $assetPath;
	}
}