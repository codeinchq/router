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
// Time:     13:25
// Project:  lib-router
//
namespace CodeInc\Router\Response;
use CodeInc\Router\Response\Exceptions\ResponseException;
use CodeInc\Router\Response\Exceptions\ResponseSendingException;
use CodeInc\Router\Response\Exceptions\ResponseSentException;


/**
 * Class SimpleResponse
 *
 * @package CodeInc\Router\Responses
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class Response extends AbstractResponse {
	/**
	 * @var string|null
	 */
	private $content;

	/**
	 * SimpleResponse constructor.
	 *
	 * @param string|null $content
	 */
	public function __construct(string $content = null) {
		parent::__construct();
		$this->setContent($content);
	}

	/**
	 * Return the response content.
	 *
	 * @return string
	 */
	public function getContent():string {
		return $this->content;
	}

	/**
	 * Sets the response content.
	 *
	 * @param null|string $content
	 */
	public function setContent(?string $content):void {
		$this->content = $content;
	}

	/**
	 * Adds to the response content.
	 *
	 * @param string $content
	 */
	public function addContent(string $content):void {
		$this->content .= $content;
	}

	/**
	 * @throws ResponseException
	 */
	public function send():void {
		if ($this->sent) {
			throw new ResponseSentException($this);
		}
		try {
			$this->getHttpHeaders()->send();
			$this->getCookies()->send();
			echo $this->content;
			$this->sent = true;
		}
		catch (\Throwable $exception) {
			throw new ResponseSendingException($this, $exception);
		}
	}
}