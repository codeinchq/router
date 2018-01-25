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


/**
 * Class PagesMultilingualManager
 *
 * @package CodeInc\GUI\Pages\Manager
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class MultilingualPagesManager extends PagesManager {

	/**
	 * List of translated pages (keys are page classes, values are a sub array of languages matching URIs).
	 *
	 * @see MultilingualPagesManager::registerMultilingualPageURI()
	 * @see MultilingualPagesManager::getPageTranslatedURI()
	 * @var array
	 */
	protected $multilingualPages = [];

	/**
	 * List of multilingual URIs with their corresponding language.
	 *
	 * @var array
	 */
	protected $multilingualURIs = [];

	/**
	 * @param string $pageClass
	 * @param string $language
	 * @param string $pageURI
	 * @throws PagesManagerException
	 */
	public function registerMultilingualPage(string $pageClass, string $language, string $pageURI) {
		$this->registerPage($pageClass, $pageURI);
		if (!isset($this->multilingualPages[$pageClass][$language])) {
			$this->multilingualPages[$pageClass][$language] = $pageURI;
		}
		$this->multilingualURIs[$pageURI] = $language;
	}

	/**
	 * Verifies if a page is registered and is a multilingual page.
	 *
	 * @param string $pageClass
	 * @return bool
	 */
	public function isMultilingualPageRegistered(string $pageClass):bool {
		return isset($this->multilingualPages[$pageClass]);
	}

	/**
	 * Verifies if a given language is registered for a multilingual page.
	 *
	 * @param string $pageClass
	 * @param string $language
	 * @return bool
	 */
	public function isMultilingualPageLanguageRegistered(string $pageClass, string $language):bool {
		return isset($this->multilingualPages[$pageClass][$language]);
	}

	/**
	 * Returns the language of a multilingual page using it's URI or FALSE if the URI does not belong
	 * to a multilingual page or is not registered.
	 *
	 * @param string $pageURI
	 * @return false|string
	 */
	public function getPageLanguageByURI(string $pageURI) {
		if (isset($this->multilingualURIs[$pageURI])) {
			return $this->multilingualPages[$pageURI];
		}
		return false;
	}

	/**
	 * Returns the language of the current multilingual page using it's URI or FALSE if the URI does not belong
	 * to a multilingual page or is not registered.
	 *
	 * @return false|string
	 */
	public function getCurrentPageLanguage() {
		if (isset($_SERVER['REQUEST_URI'])) {
			return $this->getPageClassByURI($_SERVER['REQUEST_URI']);
		}
		return false;
	}

	/**
	 * Returns a multilingual page URI for a given language.
	 *
	 * @param string $pageClass
	 * @param string $language
	 * @return string|false
	 */
	public function getPageURIByLanguage(string $pageClass, string $language) {
		if (isset($this->multilingualPages[$pageClass][$language])) {
			return $this->multilingualPages[$pageClass][$language];
		}
		return false;
	}
}