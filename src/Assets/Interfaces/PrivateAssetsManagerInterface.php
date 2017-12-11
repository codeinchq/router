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
// Time:     16:50
// Project:  lib-gui
//
namespace CodeInc\GUI\Assets\Interfaces;
use CodeInc\GUI\Assets\Exception\PrivateAssetNotFound;
use CodeInc\GUI\Assets\Exception\PrivateAssetsBasePathNotFound;


/**
 * Interface PrivateAssetManagerInterface
 *
 * @package CodeInc\GUI\Assets\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface PrivateAssetsManagerInterface {
	/**
	 * PrivateAssetManagerInterface constructor.
	 *
	 * @param string $privateAssetBasePath
	 * @throws PrivateAssetsBasePathNotFound
	 */
	public function __construct(string $privateAssetBasePath);

	/**
	 * @param string $asset
	 * @param string|null $className
	 * @param string|null $baseNamespace
	 * @return string
	 * @throws PrivateAssetNotFound
	 */
	public function getAssetPath(string $asset, string $className = null, string $baseNamespace = null):string;
}