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
// Time:     15:16
// Project:  lib-gui
//
namespace CodeInc\GUI\Assets;
use CodeInc\GUI\Assets\Exception\WebAssetsBaseURINotSetException;
use CodeInc\GUI\Assets\Interfaces\WebAssetsInterface;


/**
 * Trait WebAssetTrait
 *
 * @package CodeInc\GUI\Assets
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @see WebAssetsInterface
 */
trait WebAssetTrait {
	/**
	 * Web assets base URI.
	 *
	 * @var string
	 */
	protected static $webAssetBaseURI;

	/**
	 * Sets the web assets base URI.
	 *
	 * @param string $baseURI
	 */
	protected static function setWebAssetBaseURI(string $baseURI) {
		self::$webAssetBaseURI = $baseURI;
	}

	/**
	 * Returns the web assets base URI.
	 *
	 * @return string
	 * @throws WebAssetsBaseURINotSetException
	 */
	protected static function getWebAssetBaseURI():string {
		if (!self::$webAssetBaseURI) {
			throw new WebAssetsBaseURINotSetException();
		}
		return self::$webAssetBaseURI ?: "";
	}

	/**
	 * Returns a web asset URI.
	 *
	 * @param string $asset
	 * @return string
	 * @throws WebAssetsBaseURINotSetException
	 */
	public static function getAssetURI(string $asset):string {
		return self::getWebAssetBaseURI().$asset;
	}
}