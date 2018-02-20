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
// Time:     13:39
// Project:  lib-router
//
namespace CodeInc\Router\Assets\Providers;


/**
 * Interface ProviderInterface
 *
 * @package CodeInc\Router\Assets\Providers
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface AssetsProviderInterface {
	/**
	 * Returns an asset URL or null if the asset does not exist.
	 *
	 * @param string $asset
	 * @return string|null
	 */
	public function getUrl(string $asset):?string;
}