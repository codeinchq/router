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
// Time:     13:02
// Project:  lib-router
//
namespace CodeInc\Router\Request\Components;
use CodeInc\Router\Request\Exceptions\EmptyParameterNameException;
use CodeInc\Router\Request\RequestInterface;
use CodeInc\Router\Request\Exceptions\MissingRequiredParameterException;


/**
 * Class RequestQuery
 *
 * @package CodeInc\GUI\Services\PagesManager\Request
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RequestQuery implements \ArrayAccess, \Iterator {
	/**
	 * @var RequestInterface
	 */
	private $request;

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var string
	 */
	private $gpcArray;

	/**
	 * @var array
	 */
	private $iteratorKeys;

	/**
	 * @var int
	 */
	private $iteratorIndex;

	/**
	 * RequestQuery constructor.
	 *
	 * @param RequestInterface $request
	 * @param array $data
	 * @param string $gpcArray
	 */
	public function __construct(RequestInterface $request, array &$data, string $gpcArray) {
		$this->request = $request;
		$this->data = $data;
		$this->gpcArray = $gpcArray;
	}

	/**
	 * @return RequestInterface
	 */
	public function getRequest():RequestInterface {
		return $this->request;
	}

	/**
	 * @param RequestInterface $request
	 * @return RequestQuery
	 */
	public static function fromGet(RequestInterface $request):RequestQuery {
		return new RequestQuery($request, $_GET, "GET");
	}

	/**
	 * @param RequestInterface $request
	 * @return RequestQuery
	 */
	public static function fromPost(RequestInterface $request):RequestQuery {
		return new RequestQuery($request, $_POST, "POST");
	}

	/**
	 * @param RequestInterface $request
	 * @return RequestQuery
	 */
	public static function fromCookie(RequestInterface $request):RequestQuery {
		return new RequestQuery($request, $_COOKIE, "COOKIE");
	}

	/**
	 * @param string $varName
	 * @return bool
	 * @throws EmptyParameterNameException
	 */
	public function hasVar(string $varName):bool {
		if (empty($varName)) {
			throw new EmptyParameterNameException($this);
		}
		return isset($_GET[$varName]);
	}

	/**
	 * @param string $varName
	 * @return null|string|array
	 * @throws EmptyParameterNameException
	 */
	public function getVar(string $varName) {
		if (empty($varName)) {
			throw new EmptyParameterNameException($this);
		}
		return $_GET[$varName] ?? null;
	}

	/***
	 * @param string $varName
	 * @return array|null|string
	 * @throws EmptyParameterNameException
	 * @throws MissingRequiredParameterException
	 */
	public function requireVar(string $varName) {
		if (($value = $this->getVar($varName)) === null) {
			throw new MissingRequiredParameterException($varName, $this->gpcArray, $this);
		}
		return $value;
	}

	/**
	 * Verifies if the source array is empty.
	 *
	 * @return bool
	 */
	public function isEmpty():bool {
		return empty($this->data);
	}

	/**
	 * Count the variables.
	 *
	 * @return int
	 */
	public function count():int {
		return count($this->data);
	}

	/**
	 * Returns all the variables.
	 *
	 * @return array
	 */
	public function getAll():array {
		return $this->data;
	}

	/**
	 * @inheritdoc
	 * @param string $offset
	 * @return bool
	 * @throws EmptyParameterNameException
	 */
	public function offsetExists($offset):bool {
		return $this->hasVar((string)$offset);
	}

	/**
	 * @inheritdoc
	 * @param mixed $offset
	 * @return array|null|string
	 * @throws EmptyParameterNameException
	 */
	public function offsetGet($offset) {
		return $this->getVar((string)$offset);
	}

	/**
	 * Disabled (this class is write only)
	 *
	 * @inheritdoc
	 * @param string $offset
	 */
	public function offsetUnset($offset):void {
		return;
	}

	/**
	 * Disabled (this class is write only)
	 *
	 * @inheritdoc
	 * @param string $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value) {
		return;
	}

	/**
	 * @inheritdoc
	 */
	public function rewind():void {
		$this->iteratorIndex = 0;
		$this->iteratorKeys = array_keys($this->data);
	}

	/**
	 * @inheritdoc
	 * @return string|array
	 */
	public function current() {
		return $this->data[$this->iteratorKeys[$this->iteratorIndex]];
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
		return isset($this->iteratorKeys[$this->iteratorIndex]);
	}
}