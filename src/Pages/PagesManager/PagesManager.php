<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.co for more information about licensing.  |
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
namespace CodeInc\GUI\Pages\PagesManager;
use CodeInc\GUI\Pages\Interfaces\MultiURIPageInterface;
use CodeInc\GUI\Pages\Interfaces\PageInterface;
use CodeInc\GUI\Pages\PagesManager\Exceptions\NotAPageException;
use CodeInc\GUI\Pages\PagesManager\Exceptions\PageRenderingException;


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
	 * @throws NotAPageException
	 */
	public function registerNotFoundPage(string $pageClass) {
		$this->registerPage($pageClass);
		$this->notFoundPageClass = $pageClass;
	}

	/**
	 * @param string $pageClass
	 * @throws NotAPageException
	 */
	public function registerPage(string $pageClass) {
		/** @var PageInterface $pageClass */
		if (!is_subclass_of($pageClass, PageInterface::class)) {
			throw new NotAPageException($pageClass);
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
	 * Returns the not found page's class or NULL if not defined.
	 *
	 * @return false|string
	 */
	public function getNotFoundPageClass() {
		return $this->notFoundPageClass ?: false;
	}

	/**
	 * @param string $URI
	 * @return string|false
	 */
	public function getPageClassByURI(string $URI) {
		if (array_key_exists($URI, $this->pages)) {
			return $this->pages[$URI];
		}
		else {
			return false;
		}
	}

	/**
	 * @param string $URI
	 * @return PageInterface|false
	 */
	public function getPageByURI(string $URI) {
		if (($pageClass = $this->getPageClassByURI($URI)) !== false) {
			return new $pageClass($this);
		}
		return false;
	}

	/**
	 * @return PageInterface|false
	 */
	public function getNotFoundPage() {
		if (($pageClass = $this->getNotFoundPageClass()) !== false) {
			return new $pageClass($this);
		}
		return false;
	}

	/**
	 * @param string $URI
	 * @param bool $allowNotFound
	 * @return bool
	 * @throws PageRenderingException
	 */
	public function renderPageByURI(string $URI, bool $allowNotFound = null):bool {
		if (($page = $this->getPageByURI($URI)) === false) {
			if ($allowNotFound === false || ($page = $this->getNotFoundPage()) === false) {
				return false;
			}
		}
		try {
			$page->render();
			return true;
		}
		catch (\Exception $exception) {
			throw new PageRenderingException($page, $URI, null, $exception);
		}
	}

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator():\ArrayIterator {
		return new \ArrayIterator($this->pages);
	}
}