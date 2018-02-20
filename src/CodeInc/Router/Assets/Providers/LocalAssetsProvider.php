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
// Time:     13:52
// Project:  lib-router
//
declare(strict_types=1);
namespace CodeInc\Router\Assets\Providers;


/**
 * Class LocalAssetsProvider
 *
 * @package CodeInc\Router\Assets\Providers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class LocalAssetsProvider extends AbstractAssetsProvider {
	/**
	 * @var array
	 */
	private $localAssets = [];

	public function mapDirectory(string $localDirectory, string $assetsPath = null):bool {
		if (is_dir($localDirectory) && is_readable($localDirectory)) {
			foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($localDirectory)) as $item) {
				/** @var $item \SplFileInfo */
				if ($item->isFile()) {
					echo substr($item->getPath(), strlen($localDirectory))."\n";
				}
			}
			return true;
		}
		return false;
	}

	public function mapFile(string $localFile, string $assetPath):bool {
		if (is_file($localFile) && is_readable($localFile) && ($realPath = realpath($localFile)) !== false) {
			$this->localAssets[$assetPath] = $localFile;
			return true;
		}
		return false;
	}

	/**
	 * @return array
	 */
	public function getLocalAssets():array {
		return $this->localAssets;
	}

	public function hasAsset(string $assetPath):bool {
		return isset($this->localAssets[$assetPath]);
	}

	public function getAssetLocalPath(string $assetPath):?string {
		return $this->localAssets[$assetPath] ?? null;
	}

	public function getUrl(string $asset):?string {
		// TODO: Implement getUrl() method.
	}

}