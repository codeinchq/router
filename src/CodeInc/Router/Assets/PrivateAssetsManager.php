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
// Time:     16:56
// Project:  lib-router
//
namespace CodeInc\Router\Assets;
use CodeInc\Router\Assets\Exception\PrivateAssetNotFound;
use CodeInc\Router\Assets\Exception\PrivateAssetsBasePathNotFound;
use CodeInc\Router\Assets\Interfaces\PrivateAssetsManagerInterface;


/**
 * Class PrivateAssetsManager
 *
 * @package CodeInc\GUI\Assets
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PrivateAssetsManager extends AbstractAssetsManager implements PrivateAssetsManagerInterface {
	/**
	 * Private assets base path.
	 *
	 * @var string
	 */
	private $privateAssetBasePath;

	/**
	 * PrivateAssetsManager constructor.
	 *
	 * @param string $privateAssetBasePath
	 * @throws PrivateAssetsBasePathNotFound
	 */
	public function __construct(string $privateAssetBasePath) {
		$this->setPrivateAssetBasePath($privateAssetBasePath);
	}

	/**
	 * Sets the private assets base path.
	 *
	 * @param string $privateAssetBasePath
	 * @throws PrivateAssetsBasePathNotFound
	 */
	protected function setPrivateAssetBasePath(string $privateAssetBasePath) {
		if (!is_dir($privateAssetBasePath) || ($privateAssetBasePath = realpath($privateAssetBasePath)) === false) {
			throw new PrivateAssetsBasePathNotFound($privateAssetBasePath);
		}
		$this->privateAssetBasePath = $privateAssetBasePath;
	}

	/**
	 * Returns the private assets base path.
	 *
	 * @return string
	 */
	public function getPrivateAssetBasePath():string {
		return $this->privateAssetBasePath;
	}

	/**
	 * Returns a private asset path.
	 *
	 * @param string $asset
	 * @param string|null $className
	 * @param string|null $baseNamespace
	 * @return string
	 * @throws PrivateAssetNotFound
	 */
	public function getAssetPath(string $asset, string $className = null, string $baseNamespace = null):string {
		$assetPath = $this->privateAssetBasePath.DIRECTORY_SEPARATOR;
		if ($className) {
			$assetPath .= self::getClassRelativePath($className, $baseNamespace,DIRECTORY_SEPARATOR)
				.DIRECTORY_SEPARATOR;
		}
		$assetPath .= $asset;
		if (!file_exists($assetPath)) {
			throw new PrivateAssetNotFound($asset, $assetPath);
		}
		return $assetPath;
	}
}