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
namespace CodeInc\Router\Response\Components;
use CodeInc\Router\Response\Exceptions\HttpHeadersSentException;
use CodeInc\Router\Response\ResponseInterface;


/**
 * Class HttpHeaders
 *
 * @package CodeInc\Router\Response\Components
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class HttpHeaders implements \Iterator, \ArrayAccess {
	/**
	 * Parent response.
	 *
	 * @var ResponseInterface
	 */
	private $response;

	/**
	 * @var array
	 */
	private $headers = [];

	/**
	 * HTTP response code.
	 *
	 * @var int
	 */
	private $responseCode = 200;

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
	 * Sets the HTTP response code.
	 *
	 * @param int $httpResponseCode
	 */
	public function setResponseCode(int $httpResponseCode):void {
		$this->responseCode = $httpResponseCode;
	}

	/**
	 * Returns the HTTP response code.
	 *
	 * @return int
	 */
	public function getResponseCode():int {
		return $this->responseCode;
	}

	/**
	 * Adds a header.
	 *
	 * @param string $header
	 * @param string $value
	 */
	public function addHeader(string $header, string $value):void {
		$this->headers[$header] = $value;
	}

	/**
	 * Verifies if a header is set.
	 *
	 * @param string $header
	 * @return bool
	 */
	public function hasHeader(string $header):bool {
		return isset($this->headers[$header]);
	}

	/**
	 * Returns a header value of null if not set.
	 *
	 * @param string $header
	 * @return null|string
	 */
	public function getHeader(string $header):?string {
		return $this->headers[$header] ?? null;
	}

	/**
	 * Removes a header.
	 *
	 * @param string $header
	 */
	public function removeHeader(string $header):void {
		unset($this->headers[$header]);
	}

	/**
	 * Returns all headers in an array.
	 *
	 * @return array
	 */
	public function getAll():array {
		return $this->headers;
	}

	/**
	 * Sends the headers.
	 *
	 * @throws HttpHeadersSentException
	 */
	public function send():void {
		if (headers_sent()) {
			throw new HttpHeadersSentException($this->response);
		}
		http_response_code($this->getResponseCode());
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