<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.co for more information about licensing.  |
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
// Date:     29/11/2017
// Time:     12:34
// Project:  lib-gui
//
namespace CodeInc\GUI\Assets\Interfaces;
use CodeInc\GUI\Assets\AssetException;


/**
 * Interface AssetsInterface to be used with views, templates and pages to access public available assets.
 *
 * @package CodeInc\GUI\Assets\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface WebAssetsInterface {
	/**
	 * Returns a public asset URI.
	 *
	 * @param string $asset
	 * @return string
	 * @throws AssetException
	 */
	public static function getAssetURI(string $asset):string;
}