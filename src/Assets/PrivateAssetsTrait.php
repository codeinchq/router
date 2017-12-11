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
use CodeInc\GUI\Assets\Exception\PrivateAssets\PrivAssetNotFound;
use CodeInc\GUI\Assets\Exception\PrivateAssets\PrivAssetsBaseDirNotSet;
use CodeInc\GUI\Assets\Exception\PrivateAssets\PrivAssetsInvalidBaseDir;
use CodeInc\GUI\Assets\Interfaces\PrivateAssetsInterface;


/**
 * Trait PrivateAssetsTrait
 *
 * @package CodeInc\GUI\Assets
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @see PrivateAssetsInterface
 */
trait PrivateAssetsTrait {
	use AssetsTrait;

	/**
	 * Private assets base direcotry path.
	 *
	 * @var string
	 */
	protected static $privateAssetsBaseDir;

	/**
	 * Returns the private assets base directory path.
	 *
	 * @return string
	 * @throws PrivAssetsBaseDirNotSet
	 * @throws PrivAssetsInvalidBaseDir
	 */
	protected static function getPrivateAssetsBaseDir():string {
		if (!self::$privateAssetsBaseDir) {
			throw new PrivAssetsBaseDirNotSet();
		}
		if (!is_dir(self::$privateAssetsBaseDir) || ($path = realpath(self::$privateAssetsBaseDir)) === false) {
			throw new PrivAssetsInvalidBaseDir(self::$privateAssetsBaseDir);
		}
		return $path;
	}

	/**
	 * Returns a private asset path. Throws a PrivateAssetsBasePathNotSetException if the base path is not set
	 * and a PrivateAssetNotFoundException if the private asset does not exist.
	 *
	 * @param string $asset
	 * @return string
	 * @throws PrivAssetsBaseDirNotSet
	 * @throws PrivAssetsInvalidBaseDir
	 * @throws PrivAssetNotFound
	 */
	public static function getPrivateAssetPath(string $asset) {
		$assetPath = self::getPrivateAssetsBaseDir()
			.DIRECTORY_SEPARATOR
			.self::getClassAssetsRelativePath(DIRECTORY_SEPARATOR)
			.DIRECTORY_SEPARATOR
			.$asset;
		if (!file_exists($assetPath)) {
			throw new PrivAssetNotFound($asset, $assetPath);
		}
		return $assetPath;
	}
}