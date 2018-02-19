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
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Request;
use CodeInc\GUI\PagesManager\PagesManagerInterface;
use CodeInc\Url\Url;


/**
 * Class Request
 *
 * @package CodeInc\GUI\PagesManager\Request
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Request implements RequestInterface {
	public const METHOD_GET = 'GET', METHOD_POST = 'POST', METHOD_UNKNOW = null;

	/**
	 * @var PagesManagerInterface
	 */
	private $pagesManager;

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
	 * @var array
	 */
	private $headers;

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
	 * @param PagesManagerInterface $pagesManager
	 * @param Url|null $url
	 */
	public function __construct(PagesManagerInterface $pagesManager, ?Url $url = null) {
		$this->url = $url ?: Url::fromCurrentUrl();
		$this->pagesManager = $pagesManager;

		// GPC
		$this->get = RequestQuery::fromGet($this);
		$this->post = RequestQuery::fromPost($this);
		$this->cookies = RequestQuery::fromCookie($this);

		// other parameters
		$this->method = $_SERVER['REQUEST_METHOD'] ?? self::METHOD_UNKNOW;
		$this->secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
		$this->remoteAddr = $_SERVER['REMOTE_ADDR'] ?? null;
		$this->remotePort = $_SERVER['REMOTE_PORT'] ?? null;
		$this->content = file_get_contents('php://input');

		// headers
		$this->headers = [];
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$this->headers[str_replace(' ', '-',
					ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
	}

	/**
	 * @inheritdoc
	 * @return PagesManagerInterface
	 */
	public function getPagesManager():PagesManagerInterface {
		return $this->pagesManager;
	}

	/**
	 * @inheritdoc
	 * @return RequestQuery
	 */
	public function getGet():RequestQuery {
		return $this->get;
	}

	/**
	 * @inheritdoc
	 * @return RequestQuery
	 */
	public function getPost():RequestQuery {
		return $this->post;
	}

	/**
	 * @inheritdoc
	 * @return RequestQuery
	 */
	public function getCookies():RequestQuery {
		return $this->cookies;
	}

	/**
	 * @inheritdoc
	 * @return Url
	 */
	public function getUrl():Url {
		return $this->url;
	}

	/**
	 * @inheritdoc
	 * @return null|string
	 */
	public function getMethod():?string {
		return $this->method;
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function isMethodPost():bool {
		return $this->method == $this::METHOD_POST;
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function isMethodGet():bool {
		return $this->method == $this::METHOD_GET;
	}

	/**
	 * @inheritdoc
	 * @return null|string
	 */
	public function getRemoteAddr():?string {
		return $this->remoteAddr;
	}

	/**
	 * @inheritdoc
	 * @return int|null
	 */
	public function getRemotePort():?int {
		return $this->remotePort;
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function isSecure():bool {
		return $this->secure;
	}

	/**
	 * @inheritdoc
	 * @return null|string
	 */
	public function getContent():?string {
		return $this->content;
	}

	/**
	 * @inheritdoc
	 * @param string $header
	 * @return bool
	 */
	public function hasHeader(string $header):bool {
		return isset($this->headers[$header]);
	}

	/**
	 * @inheritdoc
	 * @param string $header
	 * @return null|string
	 */
	public function getHeader(string $header):?string {
		return $this->headers[$header] ?? null;
	}

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function getHeaders():array {
		return $this->headers;
	}
}