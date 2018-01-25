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
// Time:     13:33
// Project:  lib-gui
//
namespace CodeInc\GUI\Pages\Manager;
use CodeInc\GUI\Pages\Manager\Exceptions\DuplicatedUriException;
use CodeInc\GUI\Pages\Manager\Exceptions\TranslatredUriNotFoundException;
use CodeInc\GUI\Pages\Manager\Exceptions\UnregistredPageException;


/**
 * Class PagesMultilingualManager
 *
 * @package CodeInc\GUI\Pages\Manager
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MultilingualPagesManager extends PagesManager {

	/**
	 * List of translated pages.
	 *
	 * @see PagesManager::registerPageTranslatedUri()
	 * @see PagesManager::getPageTranslatedUri()
	 * @var array
	 */
	protected $translatedURIs = [];

	/**
	 * Registers a page translation.
	 *
	 * @param string $pageClass
	 * @param string $language
	 * @param string $translatedURI
	 * @throws DuplicatedUriException
	 * @throws UnregistredPageException
	 */
	public function registerPageTranslatedUri(string $pageClass, string $language, string $translatedURI) {
		$this->registerPageExtraURI($pageClass, $translatedURI);
		$this->translatedURIs[$pageClass][$language] = $translatedURI;
	}

	/**
	 * Retruns the translated URI of a page for a given language.
	 *
	 * @param string $pageClass
	 * @param string $language
	 * @return string
	 * @throws TranslatredUriNotFoundException
	 */
	public function getPageTranslatedUri(string $pageClass, string $language):string {
		if (!isset($this->translatedURIs[$pageClass][$language])) {
			throw new TranslatredUriNotFoundException($pageClass, $language);
		}
		return $this->translatedURIs[$pageClass][$language];
	}
}