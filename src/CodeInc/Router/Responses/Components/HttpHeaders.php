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
// Time:     20:38
// Project:  lib-router
//
namespace CodeInc\Router\Responses\Components;
use CodeInc\Router\Responses\Exceptions\HttpHeadersSentException;
use CodeInc\Router\Responses\ResponseInterface;


/**
 * Class ResponseHeaders
 *
 * @package CodeInc\GUI\PagesManager\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @todo finir la doc
 */
class HttpHeaders implements \Iterator, \ArrayAccess {
	/**
	 * Parent response.
	 *
	 * @var ResponseInterface
	 */
	private $response;

	/**
	 * @var int
	 */
	private $httpResponseCode = 200;

	/**
	 * @var array
	 */
	private $headers = [];

	/**
	 * @var array
	 */
	private $iteratorKeys;

	/**
	 * @var int
	 */
	private $iteratorIndex;

	/**
	 * ResponseHttpHeaders constructor.
	 *
	 * @param ResponseInterface $response
	 */
	public function __construct(ResponseInterface $response) {
		$this->response = $response;
	}

	/**
	 * @return int
	 */
	public function getHttpResponseCode():int {
		return $this->httpResponseCode;
	}

	/**
	 * @param int $httpResponseCode
	 */
	public function setHttpResponseCode(int $httpResponseCode):void {
		$this->httpResponseCode = $httpResponseCode;
	}

	/**
	 * @param string $header
	 * @param string $value
	 */
	public function addHeader(string $header, string $value):void {
		$this->headers[$header] = $value;
	}

	/**
	 * @param string $header
	 * @return bool
	 */
	public function hasHeader(string $header):bool {
		return isset($this->headers[$header]);
	}

	/**
	 * @param string $header
	 * @return null|string
	 */
	public function getHeader(string $header):?string {
		return $this->headers[$header] ?? null;
	}

	/**
	 * @param string $header
	 */
	public function removeHeader(string $header):void {
		unset($this->headers[$header]);
	}

	/**
	 * @return array
	 */
	public function getHeaders():array {
		return $this->headers;
	}

	/**
	 * Sends all the headers.
	 *
	 * @throws HttpHeadersSentException
	 */
	public function send():void {
		if (headers_sent()) {
			throw new HttpHeadersSentException($this->response);
		}
		http_response_code($this->httpResponseCode);
		foreach ($this->headers as $header => $value) {
			header("$header: $value", true);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function offsetSet($offset, $value):void {
		$this->addHeader((string)$offset, (string)$value);
	}

	/**
	 * @inheritdoc
	 * @return null|string
	 */
	public function offsetGet($offset):?string {
		return $this->getHeader((string)$offset);
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function offsetExists($offset):bool {
		return $this->hasHeader((string)$offset);
	}

	/**
	 * @inheritdoc
	 */
	public function offsetUnset($offset):void {
		$this->removeHeader((string)$offset);
	}

	/**
	 * @inheritdoc
	 */
	public function rewind():void {
		$this->iteratorIndex = 0;
		$this->iteratorKeys = array_keys($this->headers);
	}

	/**
	 * @inheritdoc
	 * @return string
	 */
	public function current():string {
		return $this->headers[$this->iteratorKeys[$this->iteratorIndex]];
	}

	/**
	 * @inheritdoc
	 * @return string
	 */
	public function key():string {
		return $this->iteratorKeys[$this->iteratorIndex];
	}

	/**
	 * @inheritdoc
	 */
	public function next():void {
		$this->iteratorIndex++;
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function valid():bool {
		return isset($this->iteratorKeys[$this->iteratorIndex],
			$this->headers[$this->iteratorKeys[$this->iteratorIndex]]);
	}
}