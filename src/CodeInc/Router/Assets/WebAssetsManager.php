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
// Time:     16:54
// Project:  lib-router
//
namespace CodeInc\Router\Assets;
use CodeInc\Router\Assets\Interfaces\WebAssetsManagerInterface;


/**
 * Class WebAssetsManager
 *
 * @package CodeInc\GUI\Assets
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class WebAssetsManager extends AbstractAssetsManager implements WebAssetsManagerInterface {
	/**
	 * Web assets base URI.
	 *
	 * @var string
	 */
	private $webAssetsBaseURI;

	/**
	 * WebAssetsManager constructor.
	 *
	 * @param string $webAssetsBaseURI
	 */
	public function __construct(string $webAssetsBaseURI) {
		$this->setWebAssetsBaseURI($webAssetsBaseURI);
	}

	/**
	 * Sets the web assets base URI.
	 *
	 * @param string $webAssetsBaseURI
	 */
	protected function setWebAssetsBaseURI(string $webAssetsBaseURI) {
		$this->webAssetsBaseURI = $webAssetsBaseURI;
	}

	/**
	 * Returns the web assets base URI.
	 *
	 * @return string
	 */
	public function getWebAssetsBaseURI():string {
		return $this->webAssetsBaseURI;
	}

	/**
	 * Returns a web asset URI.
	 *
	 * @param string $asset
	 * @param string|null $className
	 * @param string|null $baseNamespace
	 * @return string
	 */
	public function getAssetURI(string $asset, string $className = null, string $baseNamespace = null):string {
		$assetURI = self::getWebAssetsBaseURI();
		if ($className) {
			$assetURI .= $this->getClassRelativePath($className, $baseNamespace, "/")."/";
		}
		return $assetURI.$asset;
	}
}