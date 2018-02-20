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
// Time:     13:33
// Project:  lib-router
//
namespace CodeInc\Router\Response;


/**
 * Class Cookie
 *
 * @package CodeInc\GUI\PagesManager\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Cookie {
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string|null
	 */
	private $value;

	/**
	 * @var \DateTime|null
	 */
	private $expire;

	/**
	 * @var string|null
	 */
	private $path;

	/**
	 * @var string|null
	 */
	private $domain;

	/**
	 * @var bool|null
	 */
	private $secure;

	/**
	 * @var bool|null
	 */
	private $httpOnly;

	/**
	 * @var bool
	 */
	private $deleted = false;

	/**
	 * Cookie constructor.
	 *
	 * @param string $name
	 * @param null|string $value
	 */
	public function __construct(string $name, ?string $value = null) {
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getName():string {
		return $this->name;
	}

	/**
	 * @return null|string
	 */
	public function getValue():?string {
		return $this->value;
	}

	/**
	 * @param null|string $value
	 */
	public function setValue(?string $value):void {
		$this->value = $value;
	}

	/**
	 * @return \DateTime|null
	 */
	public function getExpire():?\DateTime {
		return $this->expire;
	}

	/**
	 * @param \DateTime|null $expire
	 */
	public function setExpire(?\DateTime $expire):void {
		$this->expire = $expire;
	}

	/**
	 * @return null|string
	 */
	public function getPath():?string {
		return $this->path;
	}

	/**
	 * @param null|string $path
	 */
	public function setPath(?string $path):void {
		$this->path = $path;
	}

	/**
	 * @return null|string
	 */
	public function getDomain():?string {
		return $this->domain;
	}

	/**
	 * @param null|string $domain
	 */
	public function setDomain(?string $domain):void {
		$this->domain = $domain;
	}

	/**
	 * @return bool
	 */
	public function isSecure():bool {
		return $this->secure === true;
	}

	/**
	 * @param bool|null $secure
	 */
	public function setSecure(?bool $secure):void {
		$this->secure = $secure;
	}

	/**
	 * @return bool
	 */
	public function isHttpOnly():bool {
		return $this->httpOnly === true;
	}

	/**
	 * @param bool|null $httpOnly
	 */
	public function setHttpOnly(?bool $httpOnly):void {
		$this->httpOnly = $httpOnly;
	}

	/**
	 * @return bool
	 */
	public function isDeleted():bool {
		return $this->deleted;
	}

	/**
	 * @param bool|null $deleted (default: true)
	 */
	public function setDeleted(?bool $deleted = null):void {
		$this->deleted = $deleted ?? true;
	}
}