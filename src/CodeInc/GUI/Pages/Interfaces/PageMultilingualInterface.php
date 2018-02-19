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
// Date:     25/01/2018
// Time:     14:58
// Project:  lib-gui
//
namespace CodeInc\GUI\Pages\Interfaces;


/**
 * Interface PageMultilingualInterface
 *
 * @package CodeInc\GUI\Pages\Interfaces
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface PageMultilingualInterface extends PageInterface {
	/**
	 * Returns the page URL for a given language.
	 *
	 * @param string $language
	 * @return string
	 */
	public static function getLanguagePath(string $language):string;

	/**
	 * Returns the list of supported language in an array.
	 *
	 * @return array
	 */
	public static function getSupportedLanguages():array;
}