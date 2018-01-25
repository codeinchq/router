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
namespace CodeInc\GUI\Pages;
use CodeInc\GUI\Pages\Exceptions\PageRenderingException;
use CodeInc\GUI\Pages\Exceptions\PagesManagerDuplicatedPageException;
use CodeInc\GUI\Pages\Exceptions\PagesManagerDuplicatedUriException;
use CodeInc\GUI\Pages\Exceptions\PagesManagerNotFoundException;
use CodeInc\GUI\Pages\Exceptions\PagesManagerNotFoundNotSetException;
use CodeInc\GUI\Pages\Exceptions\PagesManagerUnknownUriException;
use CodeInc\GUI\Pages\Exceptions\PagesManagerUnregistredPageException;
use CodeInc\GUI\Pages\Interfaces\PageInterface;
use CodeInc\GUI\Pages\Exceptions\PagesManagerNotAPageException;


/**
 * Class PagesManager
 *
 * @package CodeInc\GUI\Pages
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PagesManager {
	const DEFAULT_404_URI = "error404.html";

	/**
	 * List of pages URIs (keys) matching pages classes (values)
	 *
	 * @var array
	 */
	protected $URIs = [];

	/**
	 * List of pages classes (key) matching pages URIs (values)
	 *
	 * @var array
	 */
	protected $pages = [];

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
	 * @throws PagesManagerNotAPageException
	 */
	public function registerNotFoundPage(string $pageClass) {
		if (!is_subclass_of($pageClass, PageInterface::class)) {
			throw new PagesManagerNotAPageException($pageClass);
		}
		$this->notFoundPageClass = $pageClass;
	}

	/**
	 * Registers a page.
	 *
	 * @param string $pageClass
	 * @param string $pageURI
	 * @param array|null $extraURIs
	 * @throws PagesManagerDuplicatedPageException
	 * @throws PagesManagerDuplicatedUriException
	 * @throws PagesManagerNotAPageException
	 * @throws PagesManagerUnregistredPageException
	 */
	public function registerPage(string $pageClass, string $pageURI, array $extraURIs = null) {
		// Testing
		if (!is_subclass_of($pageClass, PageInterface::class)) {
			throw new PagesManagerNotAPageException($pageClass);
		}
		if ($this->isUriRegistred($pageURI)) {
			throw new PagesManagerDuplicatedUriException($pageURI);
		}
		if ($this->isPageRegistred($pageClass)) {
			throw new PagesManagerDuplicatedPageException($pageClass);
		}

		// Registers the page
		$this->URIs[$pageURI] = $pageClass;
		$this->pages[$pageClass] = $pageURI;

		// Registering additionnal URIs
		if ($extraURIs) {
			foreach ($extraURIs as $extraURI) {
				$this->registerPageExtraURI($pageClass, $extraURI);
			}
		}
	}

	/**
	 * Regiters a page extra URI.
	 *
	 * @param string $pageClass
	 * @param string $pageExtraURI
	 * @throws PagesManagerDuplicatedUriException
	 * @throws PagesManagerUnregistredPageException
	 */
	public function registerPageExtraURI(string $pageClass, string $pageExtraURI) {
		if (!$this->isPageRegistred($pageClass)) {
			throw new PagesManagerUnregistredPageException($pageClass);
		}
		if ($this->isUriRegistred($pageExtraURI)) {
			throw new PagesManagerDuplicatedUriException($pageExtraURI);
		}
		$this->URIs[$pageExtraURI] = $pageClass;
	}

	/**
	 * Verifies if a page is registered
	 *
	 * @param string $pageClass
	 * @return bool
	 */
	public function isPageRegistred(string $pageClass):bool {
		return array_key_exists($pageClass, $this->pages);
	}

	/**
	 * Verifies if a URI is registered
	 *
	 * @param string $URI
	 * @return bool
	 */
	public function isUriRegistred(string $URI):bool {
		return array_key_exists($URI, $this->URIs);
	}

	/**
	 * Returns a page URI.
	 *
	 * @param string $pageClass
	 * @return string
	 * @throws PagesManagerUnregistredPageException
	 */
	public function getPageUri(string $pageClass):string {
		if (!$this->isPageRegistred($pageClass)) {
			throw new PagesManagerUnregistredPageException($pageClass);
		}
		return $this->pages[$pageClass];
	}

	/**
	 * Returns all the registred pages URI.
	 *
	 * @return array
	 */
	public function getRegisteredPages():array {
		return $this->URIs;
	}

	/**
	 * Returns the not found page class.
	 *
	 * @return string
	 * @throws PagesManagerNotFoundNotSetException
	 */
	public function getNotFoundPageClass():string {
		if (!$this->hasNotFoundPage()) {
			throw new PagesManagerNotFoundNotSetException();
		}
		return $this->notFoundPageClass ?: false;
	}

	/**
	 * Verifies if a not found page has been defined.
	 *
	 * @return bool
	 */
	public function hasNotFoundPage():bool {
		return $this->notFoundPageClass !== null;
	}

	/**
	 * Returns a page class for a given URI.
	 *
	 * @param string $URI
	 * @param bool $allowNotFound Default: TRUE
	 * @return string
	 * @throws PagesManagerNotFoundException
	 * @throws PagesManagerNotFoundNotSetException
	 */
	public function getPageClassByUri(string $URI, bool $allowNotFound = null):string {
		// returns the page's class
		if ($this->isUriRegistred($URI)) {
			return $this->URIs[$URI];
		}

		// returns the not found page class (if allowed)à
		elseif ($allowNotFound !== false) {
			return $this->getNotFoundPageClass();
		}

		// throws a not found exception
		else {
			throw new PagesManagerNotFoundException($URI);
		}
	}

	/**
	 * Render a page using it's URI. If $allowNotFound is at TRUE, the page is not found and a not found page
	 * has been defined the method will render the not found page, else a PagesManagerNotFoundException is thrown.
	 *
	 * @param string $URI
	 * @param bool|null $allowNotFound
	 * @throws PageRenderingException
	 * @throws PagesManagerNotFoundException
	 * @throws PagesManagerNotFoundNotSetException
	 * @throws \Throwable
	 */
	public function renderPageByURI(string $URI, bool $allowNotFound = null) {
		// Obtaining the page class
		$pageClass = $this->getPageClassByUri($URI, $allowNotFound);

		// Renders the page
		try {
			/** @var PageInterface $page */
			$page = new $pageClass();
			$page->render();
		}
		catch (\Throwable $exception) {
			if (!$exception instanceof PageRenderingException) {
				throw new PageRenderingException($page, 0, $exception);
			}
			else {
				throw $exception;
			}
		}
	}

	/**
	 * Renders the current page using $_SERVER['REQUEST_URI'] a the current URI.
	 *
	 * @param bool|null $allowNotFound
	 * @throws PageRenderingException
	 * @throws PagesManagerNotFoundException
	 * @throws PagesManagerNotFoundNotSetException
	 * @throws PagesManagerUnknownUriException
	 * @throws \Throwable
	 */
	public function renderCurrentPage(bool $allowNotFound = null) {
		if (!isset($_SERVER['REQUEST_URI'])) {
			throw new PagesManagerUnknownUriException();
		}
		$this->renderPageByURI($_SERVER['REQUEST_URI'], $allowNotFound);
	}
}