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
use CodeInc\GUI\Pages\Exceptions\PagesManagerNotFoundException;
use CodeInc\GUI\Pages\Exceptions\PagesManagerNotFoundNotSetException;
use CodeInc\GUI\Pages\Interfaces\MultiURIPageInterface;
use CodeInc\GUI\Pages\Interfaces\PageInterface;
use CodeInc\GUI\Pages\Exceptions\PagesManagerNotAPageException;


/**
 * Class PagesManager
 *
 * @package CodeInc\GUI\Pages
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PagesManager implements \IteratorAggregate {
	/**
	 * @var array
	 */
	private $pages = [];

	/**
	 * @var string|null
	 */
	private $notFoundPageClass;

	/**
	 * @param string $pageClass
	 * @throws PagesManagerNotAPageException
	 */
	public function registerNotFoundPage(string $pageClass) {
		$this->registerPage($pageClass);
		$this->notFoundPageClass = $pageClass;
	}

	/**
	 * @param string $pageClass
	 * @throws PagesManagerNotAPageException
	 */
	public function registerPage(string $pageClass) {
		/** @var PageInterface $pageClass */
		if (!is_subclass_of($pageClass, PageInterface::class)) {
			throw new PagesManagerNotAPageException($pageClass);
		}

		// Registering the main URI
		$this->pages[$pageClass::getURI()] = $pageClass;

		// Registering additionnal URIs
		if (is_subclass_of($pageClass, MultiURIPageInterface::class)) {
			/** @var MultiURIPageInterface $pageClass */
			foreach ($pageClass::getAdditionnalURIs() as $additionnalURI) {
				$this->pages[$additionnalURI] = $pageClass;
			}
		}
	}

	/**
	 * Returns all the registred pages URI.
	 *
	 * @return array
	 */
	public function getRegisteredPages():array {
		return $this->pages;
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
	 * Verifies if a page exists for a given URI.
	 *
	 * @param string $URI
	 * @return bool
	 */
	public function hasPage(string $URI):bool {
		return array_key_exists($URI, $this->pages);
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
	public function getPageClassByURI(string $URI, bool $allowNotFound = null):string {
		if ($this->hasPage($URI)) { // returns the page's class
			return $this->pages[$URI];
		}
		elseif ($allowNotFound !== false) { // returns the not found page class (if allowed)à
			return $this->getNotFoundPageClass();
		}
		else { // throws a not found exception
			throw new PagesManagerNotFoundException($URI);
		}
	}

	/**
	 * Returns a page object for a given URI.
	 *
	 * @param string $URI
	 * @param bool $allowNotFound Default: TRUE
	 * @return PageInterface
	 * @throws PagesManagerNotFoundException
	 * @throws PagesManagerNotFoundNotSetException
	 */
	public function getPageByURI(string $URI, bool $allowNotFound = null) {
		$pageClass = $this->getPageClassByURI($URI, $allowNotFound);
		return new $pageClass();
	}

	/**
	 * Returns the not found page object.
	 *
	 * @return PageInterface
	 * @throws PagesManagerNotFoundNotSetException
	 */
	public function getNotFoundPage() {
		$pageClass = $this->getNotFoundPageClass();
		return new $pageClass($this);
	}

	/**
	 * Render a page using it's URI. If $allowNotFound is at TRUE, the page is not found and a not found page
	 * has been defined the method will render the not found page, else a PagesManagerNotFoundException is thrown.
	 *
	 * @param string $URI
	 * @param bool $allowNotFound
	 * @throws PagesManagerNotFoundException
	 * @throws PagesManagerNotFoundNotSetException
	 * @throws PageRenderingException
	 */
	public function renderPageByURI(string $URI, bool $allowNotFound = null) {
		// Obtaining the page object
		$page = $this->getPageByURI($URI, $allowNotFound);

		// Renders the page
		try {
			$page->render();
		}
		catch (\Exception $exception) {
			if (!$exception instanceof PageRenderingException) {
				throw new PageRenderingException($page, 0, $exception);
			}
			else {
				throw $exception;
			}
		}
	}

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator():\ArrayIterator {
		return new \ArrayIterator($this->pages);
	}
}