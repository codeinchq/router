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
// Time:     16:21
// Project:  lib-gui
//
namespace CodeInc\GUI\Pages\Manager;
use CodeInc\GUI\Pages\Interfaces\PageInterface;
use CodeInc\GUI\Pages\Interfaces\PageMultilingualInterface;
use CodeInc\GUI\Pages\Interfaces\PageMultipleURIInterface;


/**
 * Class PagesManager
 *
 * @package CodeInc\GUI\Pages\Manager
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PagesManager {
	const DEFAULT_404_URI = "error404.html";

	/**
	 * List of pages URIs (keys) matching pages classes (values)
	 *
	 * @var array
	 */
	protected $pageURIs = [];

	/**
	 * List of pages classes (key) matching pages URIs (values)
	 *
	 * @var array
	 */
	protected $pageClasses = [];

	/**
	 * Not found page class.
	 *
	 * @var string|null
	 */
	protected $notFoundPageClass;

	/**
	 * Registers the not found page.
	 *
	 * @param string $pageClass
	 * @throws PagesManagerException
	 */
	public function registerNotFoundPage(string $pageClass) {
		if (!is_subclass_of($pageClass, PageInterface::class)) {
			throw new PagesManagerException("The class \"$pageClass\" is not a page and "
				."can not be used as a not found page");
		}
		$this->notFoundPageClass = $pageClass;
	}

	/**
	 * Registers a page.
	 *
	 * @param string $pageClass
	 * @throws PagesManagerException
	 */
	public function registerPage(string $pageClass) {
		// Testing
		if (array_key_exists($pageClass, $this->pageClasses)) {
			throw new PagesManagerException("The page \"$pageClass\" is already registered");
		}

		// Registering the page
		$pageURI = $this->getPageURI($pageClass);
		$this->pageClasses[$pageClass] = $pageURI;
		$this->pageURIs[$pageURI] = $pageClass;

		// Adding extra URIs
		if (is_subclass_of($pageClass, PageMultipleURIInterface::class)) {
			/** @var PageMultipleURIInterface $pageClass */
			foreach ($pageClass::getExtraURIs() as $extraURI) {
				$this->pageURIs[$extraURI] = $pageClass;
			}
		}

		// Adding multilingual pages URLs
		if (is_subclass_of($pageClass, PageMultilingualInterface::class)) {
			/** @var PageMultilingualInterface $pageClass */
			foreach ($pageClass::getSupportedLanguages() as $language) {
				$this->pageURIs[$pageClass::getLanguageURI($language)] = $pageClass;
			}
		}
	}

	/**
	 * Returns a page URI.
	 *
	 * @param string $pageClass
	 * @return string
	 * @throws PagesManagerException
	 */
	protected function getPageURI(string $pageClass):string {
		if (!is_subclass_of($pageClass, PageInterface::class)) {
			throw new PagesManagerException("The class \"$pageClass\" is not a page and "
				."can not be registered.");
		}
		/** @var PageInterface $pageClass */
		return $pageClass::getURI();
	}

	/**
	 * Returns the not found page class or FALSE is the not found is not defined.
	 *
	 * @return string|false
	 */
	public function getNotFoundPageClass() {
		return $this->notFoundPageClass ?: false;
	}

	/**
	 * Returns a page class for a given URI or FALSE if the page does not exist.
	 *
	 * @param string $pageURI
	 * @param bool $allowNotFound Default: TRUE
	 * @return string|false
	 */
	public function getPageClassByURI(string $pageURI, bool $allowNotFound = null) {
		// returns the page's class
		if (array_key_exists($pageURI, $this->pageURIs)) {
			return $this->pageURIs[$pageURI];
		}

		// returns the not found page class (if allowed)à
		elseif ($allowNotFound !== false) {
			return $this->getNotFoundPageClass();
		}

		return false;
	}

	/**
	 * Render a page using it's URI. If $allowNotFound is at TRUE, the page is not found and a not found page
	 * has been defined the method will render the not found page, else a PagesManagerNotFoundException is thrown.
	 *
	 * @param string $pageURI
	 * @param bool|null $allowNotFound
	 * @throws PagesManagerException
	 */
	public function renderPageByURI(string $pageURI, bool $allowNotFound = null) {
		// Obtaining the page class
		if (($pageClass = $this->getPageClassByURI($pageURI, $allowNotFound)) === false) {
			throw new PagesManagerException("The page \"$pageURI\" is not registered and can not be rendered");
		}

		// Renders the page
		try {
			/** @var PageInterface $page */
			$page = new $pageClass();
			$page->render();
		}
		catch (\Throwable $exception) {
			throw new PagesManagerException("Error while rendering the page \"$pageClass\"", null, $exception);
		}
	}

	/**
	 * Renders the current page using $_SERVER['REQUEST_URI'] a the current URI.
	 *
	 * @param bool|null $allowNotFound
	 * @throws PagesManagerException
	 */
	public function renderCurrentPage(bool $allowNotFound = null) {
		if (!isset($_SERVER['REQUEST_URI'])) {
			throw new PagesManagerException("The current page's URL can not be found in the \$_SERVER array, "
				."unable to render the current page");
		}
		if (($pos = strpos($_SERVER['REQUEST_URI'], '?')) !== false) {
			$this->renderPageByURI(substr($_SERVER['REQUEST_URI'], 0, $pos), $allowNotFound);
		}
		else {
			$this->renderPageByURI($_SERVER['REQUEST_URI'], $allowNotFound);
		}
	}
}