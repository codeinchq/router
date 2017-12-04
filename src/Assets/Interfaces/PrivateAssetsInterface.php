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
// Time:     12:57
// Project:  lib-gui
//
namespace CodeInc\GUI\Assets\Interfaces;
use CodeInc\GUI\Assets\AssetException;


/**
 * Interface PrivateAssetsInterface to be used with templates, views and pages in order to access acces private
 * locally stored assets.
 *
 * @package CodeInc\GUI\Assets\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface PrivateAssetsInterface {
	/**
	 * Returns a private asset path.
	 *
	 * @param string $asset
	 * @return string
	 * @throws AssetException
	 */
	public static function getPrivateAssetPath(string $asset):string;
}