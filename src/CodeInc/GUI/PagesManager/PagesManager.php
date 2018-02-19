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
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager;
use CodeInc\GUI\Pages\Interfaces\PageInterface;
use CodeInc\GUI\Pages\Interfaces\PageMultilingualInterface;
use CodeInc\GUI\PagesManager\Exceptions\ExistingPageException;
use CodeInc\GUI\PagesManager\Exceptions\HeaderSentException;
use CodeInc\GUI\PagesManager\Exceptions\NotAPageException;
use CodeInc\GUI\PagesManager\Exceptions\PageProcessingException;
use CodeInc\GUI\PagesManager\Exceptions\PageNotFoundException;
use CodeInc\GUI\PagesManager\Exceptions\ReponseSentException;
use CodeInc\GUI\PagesManager\Exceptions\UnregisteredPageException;
use CodeInc\GUI\PagesManager\Request\Request;
use CodeInc\GUI\PagesManager\Request\RequestInterface;
use CodeInc\GUI\PagesManager\Response\ResponseInterface;
use CodeInc\Url\Url;


/**
 * Class PagesManager
 *
 * @package CodeInc\GUI\Services\PagesManager
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class PagesManager implements PagesManagerInterface, NotFoundInterface {
	/**
	 * List of pages URIs (keys) matching pages classes (values)
	 *
	 * @var array
	 */
	private $pagePaths = [];

	/**
	 * Not found page class.
	 *
	 * @var string|null
	 */
	private $notFoundPageClass;

	/**
	 * @var bool
	 */
	private $responseSent = false;

	/**
	 * @inheritdoc
	 */
	public function registerNotFoundPage(string $pageClass):void {
		$this->validatePage($pageClass);
		$this->notFoundPageClass = $pageClass;
	}

	/**
	 * Verifies if a class is a page.
	 *
	 * @param string $pageClass
	 * @throws NotAPageException
	 */
	protected function validatePage(string $pageClass):void {
		if (!is_subclass_of($pageClass, PageInterface::class)) {
			throw new NotAPageException($pageClass, $this);
		}
	}

	/**
	 * @inheritdoc
	 * @throws ExistingPageException
	 */
	public function registerPage(string $path, string $pageClass):void {
		// Testing
		$this->validatePage($pageClass);
		if (in_array($pageClass, $this->pagePaths)) {
			throw new ExistingPageException($pageClass, $this);
		}

		// Registering the page
		/** @var $pageClass PageInterface */
		$this->pagePaths[$path] = $pageClass;

		// Adding multilingual pages URLs
		if (is_subclass_of($pageClass, PageMultilingualInterface::class)) {
			/** @var PageMultilingualInterface $pageClass */
			foreach ($pageClass::getSupportedLanguages() as $language) {
				$this->pagePaths[$pageClass::getLanguagePath($language)] = $pageClass;
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getPageUrl(string $pageClass, ?array $queryParameters = null):Url {
		if (($pagePath = array_search($pageClass, $this->pagePaths)) === false) {
			throw new UnregisteredPageException($pageClass, $this);
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
	 * Processes a request.
	 *
	 * @inheritdoc
	 * @throws PageNotFoundException
	 */
	public function processRequest(?RequestInterface $request = null):void {
		// building the request object (if not provided)
		if (!$request) {
			$request = new Request($this);
		}

		// reading the page class
		$reqPath = $request->getUrl()->getPath();
		if (isset($this->pagePaths[$reqPath])) {
			$pageClass = $this->pagePaths[$reqPath];
		}
		elseif ($this->notFoundPageClass) {
			$pageClass = $this->notFoundPageClass;
		}
		else {
			throw new PageNotFoundException($request, $this);
		}

		// processing the page
		try {
			/** @var PageInterface $page */
			$page = new $pageClass($this, $request);
			$response = $page->process();
			if (!$this->isResponseSent()) {
				$this->sendResponse($request, $response);
			}
		}
		catch (\Throwable $exception) {
			throw new PageProcessingException($pageClass, $this, $exception);
		}
	}

	/**
	 * Verifies if the response is sent.
	 *
	 * @inheritdoc
	 */
	public function isResponseSent():bool {
		return $this->responseSent;
	}

	/**
	 * Sends a repsonse.
	 *
	 * @inheritdoc
	 * @param RequestInterface $request
	 * @param ResponseInterface $response
	 * @throws HeaderSentException
	 * @throws ReponseSentException
	 */
	public function sendResponse(RequestInterface $request, ResponseInterface $response):void {
		// checking
		if ($this->isResponseSent()) throw new ReponseSentException($response, $this);
		if (headers_sent()) throw new HeaderSentException($response, $this);

		// sending headers
		http_response_code($response->getHttpStatusCode());
		foreach ($response->getHeaders() as $header => $value) {
			header("$header: $value", true);
		}

		// sending cookies
		foreach ($response->getCookies() as $cookie) {
			if (!$response->isCookieDeleted($cookie->getName())) {
				setcookie($cookie->getName(), $cookie->getValue(),
					($cookie->getExpire() ? $cookie->getExpire()->getTimestamp() : null),
					$cookie->getPath(), $cookie->getDomain(), $cookie->isSecure(), $cookie->isHttpOnly());
			}
		}
		foreach ($response->getDeletedCookies() as $cookieName) {
			setcookie($cookieName, null, -1);
		}

		// sending content
		if (($content = $response->getContent()) !== null) {
			echo $content;
		}
	}
}