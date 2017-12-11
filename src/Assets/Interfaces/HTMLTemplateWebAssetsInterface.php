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
// Date:     28/11/2017
// Time:     12:40
// Project:  lib-gui
//
namespace CodeInc\GUI\Assets\Interfaces;
use CodeInc\GUI\Assets\Exception\AssetsException;
use CodeInc\GUI\Templates\Interfaces\HTMLTemplateInterface;


/**
 * Interface TemplateWebAssetsInterface to be used with views mostly in order for them to add the required web assets
 * to a template.
 *
 * @package CodeInc\GUI\Assets\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface HTMLTemplateWebAssetsInterface extends WebAssetsInterface {
	/**
	 * @param HTMLTemplateInterface $template
	 * @return void
	 * @throws AssetsException
	 */
	public static function addTemplateAssets(HTMLTemplateInterface $template);
}