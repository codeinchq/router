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
// Date:     12/02/2018
// Time:     13:01
// Project:  lib-router
//
namespace CodeInc\Router\Request;
use CodeInc\Router\RouterInterface;
use CodeInc\Url\Url;


/**
 * Class Request
 *
 * @package CodeInc\GUI\PagesManager\Request
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Request {
	public const METHOD_GET = 'GET', METHOD_POST = 'POST', METHOD_UNKNOW = null;

	/**
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * @var HttpHeaders
	 */
	private $httpHeaders;

	/**
	 * @var RequestQuery
	 */
	private $get;

	/**
	 * @var RequestQuery
	 */
	private $post;

	/**
	 * @var RequestQuery
	 */
	private $cookies;

	/**
	 * @var Url
	 */
	private $url;

	/**
	 * @var string|null
	 */
	private $method;

	/**
	 * @var bool
	 */
	private $secure;

	/**
	 * @var string|null
	 */
	private $remoteAddr;

	/**
	 * @var int|null
	 */
	private $remotePort;

	/**
	 * @var string|null
	 */
	private $content;

	/**
	 * Request constructor.
	 *
	 * @param RouterInterface $router
	 * @param Url|null $url
	 */
	public function __construct(RouterInterface $router, ?Url $url = null) {
		$this->url = $url ?: Url::fromCurrentUrl();
		$this->router = $router;

		// submodules
		$this->httpHeaders = HttpHeaders::factoryFromGlobals();
		$this->get = RequestQuery::fromGet($this);
		$this->post = RequestQuery::fromPost($this);
		$this->cookies = RequestQuery::fromCookie($this);

		// other parameters
		$this->method = $_SERVER['REQUEST_METHOD'] ?? self::METHOD_UNKNOW;
		$this->secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
		$this->remoteAddr = $_SERVER['REMOTE_ADDR'] ?? null;
		$this->remotePort = $_SERVER['REMOTE_PORT'] ?? null;
		$this->content = file_get_contents('php://input');
	}

	/**
	 * @inheritdoc
	 * @return RouterInterface
	 */
	public function getRouter():RouterInterface {
		return $this->router;
	}

	/**
	 * Returns the HTTP headers iterator.
	 *
	 * @return HttpHeaders
	 */
	public function getHttpHeaders():HttpHeaders {
		return $this->httpHeaders;
	}

	/**
	 * Returns the get variables iterator.
	 *
	 * @return RequestQuery
	 */
	public function getGetQuery():RequestQuery {
		return $this->get;
	}

	/**
	 * Returs the post variables iterator.
	 *
	 * @return RequestQuery
	 */
	public function getPostQuery():RequestQuery {
		return $this->post;
	}

	/**
	 * Returns the cookies iterator.
	 *
	 * @return RequestQuery
	 */
	public function getCookies():RequestQuery {
		return $this->cookies;
	}

	/**
	 * Returns the request URL.
	 *
	 * @return Url
	 */
	public function getUrl():Url {
		return $this->url;
	}

	/**
	 * Returns the method type ('GET' or 'POST').
	 *
	 * @return null|string
	 */
	public function getMethod():?string {
		return $this->method;
	}

	/**
	 * Verifies if the method is POST.
	 *
	 * @return bool
	 */
	public function isMethodPost():bool {
		return $this->method == $this::METHOD_POST;
	}

	/**
	 * Verifies if the method is GET.
	 *
	 * @return bool
	 */
	public function isMethodGet():bool {
		return $this->method == $this::METHOD_GET;
	}

	/**
	 * Returns the remote IP address.
	 *
	 * @return null|string
	 */
	public function getRemoteAddr():?string {
		return $this->remoteAddr;
	}

	/**
	 * Returns the remote port waiting for the response.
	 *
	 * @return int|null
	 */
	public function getRemotePort():?int {
		return $this->remotePort;
	}

	/**
	 * Verifies if the request went trough a secure connection.
	 *
	 * @return bool
	 */
	public function isSecure():bool {
		return $this->secure;
	}

	/**
	 * Returns the request content or null if the request has no content.
	 *
	 * @return null|string
	 */
	public function getContent():?string {
		return $this->content;
	}
}