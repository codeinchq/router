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
// Time:     21:21
// Project:  lib-router
//
namespace CodeInc\Router\Request\Components;


/**
 * Class HttpHeaders
 *
 * @package CodeInc\Router\Request\Components
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class HttpHeaders implements \Iterator, \ArrayAccess {
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
	 * HttpHeaders constructor.
	 *
	 * @param array|null $headers
	 */
	public function __construct(array $headers = null) {
		$this->headers = $headers ?: [];
	}

	/**
	 * Configures the headers using $_SERVER data.
	 *
	 * @return HttpHeaders
	 */
	public static function factoryFromGlobals():HttpHeaders {
		$headers = new HttpHeaders();
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers->headers[str_replace(' ', '-',
					ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
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
	 * Returns all headers.
	 *
	 * @return array
	 */
	public function getAll():array {
		return $this->headers;
	}

	/**
	 * @inheritdoc
	 */
	public function offsetSet($offset, $value):void {
		return;
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
		return;
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