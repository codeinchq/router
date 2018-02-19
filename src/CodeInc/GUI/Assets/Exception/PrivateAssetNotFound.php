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
// Time:     16:47
// Project:  lib-gui
//
namespace CodeInc\GUI\Assets\Exception;
use Throwable;


/**
 * Class PrivateAssetNotFound
 *
 * @package CodeInc\GUI\Assets\Exception
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PrivateAssetNotFound extends AssetsManagerException {
	public function __construct(string $asset, string $assetPath, Throwable $previous = null) {
		parent::__construct("The private asset \"$asset\" does not exist at the location \"$assetPath\"",
			0, $previous);
	}
}