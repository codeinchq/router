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
// Date:     13/02/2018
// Time:     13:06
// Project:  lib-router
//
namespace CodeInc\Router;
use CodeInc\Router\Pages\Interfaces\PageInterface;
use CodeInc\Router\Pages\Interfaces\PageMultilingualInterface;
use CodeInc\Router\Exceptions\ExistingPageException;
use CodeInc\Router\Exceptions\NotAPageException;
use CodeInc\Router\Exceptions\PageProcessingException;
use CodeInc\Router\Exceptions\PageNotFoundException;
use CodeInc\Router\Exceptions\UnmappedPageException;
use CodeInc\Router\Request\Request;
use CodeInc\Router\Request\RequestInterface;
use CodeInc\Router\Responses\ResponseInterface;
use CodeInc\Url\Url;


/**
 * Class Router
 *
 * @package CodeInc\GUI\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Router implements RouterInterface {
	/**
	 * List of pages URIs (keys) matching pages classes (values)
	 *
	 * @var array
	 */
	private $routes = [];

	/**
	 * Not found page class.
	 *
	 * @var string|null
	 */
	private $notFoundPage;

	/**
	 * @inheritdoc
	 */
	public function mapNotFoundRoute(string $pageClass):void {
		if (!$this->isPage($pageClass)) {
			throw new NotAPageException($pageClass, $this);
		}
		$this->notFoundPage = $pageClass;
	}

	/**
	 * Verifies if a class is a page.
	 *
	 * @param string $pageClass
	 * @return bool
	 */
	protected function isPage(string $pageClass):bool {
		return is_subclass_of($pageClass, PageInterface::class);
	}

	/**
	 * @inheritdoc
	 * @throws ExistingPageException
	 */
	public function mapRoute(string $route, string $pageClass):void {
		// Testing
		if (!$this->isPage($pageClass)) {
			throw new NotAPageException($pageClass, $this);
		}
		if (in_array($pageClass, $this->routes)) {
			throw new ExistingPageException($pageClass, $this);
		}

		// Registering the page
		/** @var $pageClass PageInterface */
		$this->routes[$route] = $pageClass;

		// Adding multilingual pages URLs
		if (is_subclass_of($pageClass, PageMultilingualInterface::class)) {
			/** @var PageMultilingualInterface $pageClass */
			foreach ($pageClass::getSupportedLanguages() as $language) {
				$this->routes[$pageClass::getLanguagePath($language)] = $pageClass;
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function hasRoute(string $route):bool {
		return array_key_exists($route, $this->routes);
	}

	/**
	 * @inheritdoc
	 */
	public function buildPageUrl(string $pageClass, ?array $queryParameters = null):Url {
		if (($pagePath = array_search($pageClass, $this->routes)) === false) {
			throw new UnmappedPageException($pageClass, $this);
		}

		$url = new Url();
		$url->useCurrentHost();
		$url->useCurrentScheme();
		$url->setPath($pagePath);
		if ($queryParameters) {
			$url->addQueryParameters($queryParameters);
		}

		return $url;
	}

	/**
	 * Returns a new request.
	 *
	 * @return RequestInterface
	 */
	protected function requestFactory():RequestInterface {
		return new Request($this);
	}

	/**
	 * Returns a new page.
	 *
	 * @param string $pageClass
	 * @param RequestInterface $request
	 * @return PageInterface
	 */
	protected function pageFactory(string $pageClass, RequestInterface $request):PageInterface {
		return new $pageClass($this, $request);
	}

	/**
	 * Sends a response.
	 *
	 * @param ResponseInterface $response
	 */
	protected function sendResponse(ResponseInterface $response):void {
		if (!$response->isSent()) {
			$response->send();
		}
	}

	/**
	 * @inheritdoc
	 * @throws PageNotFoundException
	 */
	public function processRequest(RequestInterface $request = null):void {
		$request = $request ?: $this->requestFactory();
		$route = $request->getUrl()->getPath();

		// reading the page class
		if (isset($this->routes[$route])) {
			$pageClass = $this->routes[$route];
		}
		elseif ($this->notFoundPage) {
			$pageClass = $this->notFoundPage;
		}
		else {
			throw new PageNotFoundException($route, $this);
		}

		// processing the page
		try {
			$page = $this->pageFactory($pageClass, $request);
			$this->sendResponse($page->process());
		}
		catch (\Throwable $exception) {
			throw new PageProcessingException($pageClass, $this, $exception);
		}
	}
}