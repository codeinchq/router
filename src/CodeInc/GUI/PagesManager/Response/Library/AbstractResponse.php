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
// Date:     19/02/2018
// Time:     21:05
// Project:  lib-gui
//
namespace CodeInc\GUI\PagesManager\Response\Library;
use CodeInc\GUI\Pages\Interfaces\PageInterface;
use CodeInc\GUI\PagesManager\Response\Exceptions\ReponseSentException;
use CodeInc\GUI\PagesManager\Response\Exceptions\ResponseException;
use CodeInc\GUI\PagesManager\Response\Cookies;
use CodeInc\GUI\PagesManager\Response\HttpHeaders;
use CodeInc\GUI\PagesManager\Response\ResponseInterface;


/**
 * Class AbstractResponse
 *
 * @package CodeInc\GUI\PagesManager\Response\Library
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractResponse implements ResponseInterface {
	/**
	 * @var PageInterface
	 */
	private $page;

	/**
	 * @var HttpHeaders
	 */
	private $httpHeaders;

	/**
	 * @var Cookies
	 */
	private $cookies;

	/**
	 * @var bool
	 */
	protected $sent = false;

	/**
	 * AbstractResponse constructor.
	 *
	 * @param PageInterface $page
	 * @param HttpHeaders|null $httpHeaders
	 * @param Cookies|null $cookies
	 */
	public function __construct(PageInterface $page, HttpHeaders $httpHeaders = null,
		Cookies $cookies = null) {
		$this->page = $page;
		$this->httpHeaders = $httpHeaders ?: new HttpHeaders($this);
		$this->cookies = $cookies ?: new Cookies($this);
	}

	/**
	 * Returns the parent page.
	 *
	 * @return PageInterface
	 */
	public function getPage():PageInterface {
		return $this->page;
	}

	/**
	 * @inheritdoc
	 */
	public function httpHeaders():HttpHeaders {
		return $this->httpHeaders;
	}

	/**
	 * @inheritdoc
	 */
	public function cookies():Cookies {
		return $this->cookies;
	}

	/**
	 * @inheritdoc
	 */
	public function isSent():bool {
		return $this->sent;
	}

	/**
	 * @inheritdoc
	 * @throws ResponseException
	 */
	public function send():void {
		try {
			if ($this->isSent()) {
				throw new ReponseSentException($this);
			}
			$this->httpHeaders()->send();
			$this->cookies()->send();
			$this->sendContent();
			$this->sent = true;
		}
		catch (\Throwable $exception) {
			throw new ResponseException("Error while sending the response for the page "
				."\"".get_class($this->getPage())."\"", $this, $exception);
		}
	}

	/**
	 * Sends the content of the response.
	 *
	 * @throws
	 */
	abstract protected function sendContent():void;
}