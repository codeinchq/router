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
// Date:     16/02/2018
// Time:     11:45
// Project:  lib-router
//
namespace CodeInc\Router\Response;
use CodeInc\Router\Response\ResponseInterface;
use CodeInc\Url\Url;


/**
 * Class RedirectResponse
 *
 * @package CodeInc\GUI\PagesManager\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RedirectResponse implements ResponseInterface {
	public const DEFAULT_HTTP_RESPONSE_CODE = 302;

	/**
	 * @var Url
	 */
	public $redirectUrl;

	/**
	 * @var int
	 */
	private $httpResponseCode;

	/**
	 * RedirectResponse constructor.
	 *
	 * @param Url $redirectUrl
	 * @param int|null $httpResponseCode
	 */
	public function __construct(Url $redirectUrl, int $httpResponseCode = null) {
		$this->redirectUrl = $redirectUrl;
		$this->httpResponseCode = $httpResponseCode ?? self::DEFAULT_HTTP_RESPONSE_CODE;
	}

	/**
	 * @param int $httpResponseCode
	 */
	public function setHttpResponseCode(int $httpResponseCode):void {
		$this->httpResponseCode = $httpResponseCode;
	}

	/**
	 * @return int
	 */
	public function getHttpResponseCode():int {
		return $this->httpResponseCode;
	}

	/**
	 * @return Url
	 */
	public function getRedirectUrl():Url {
		return $this->redirectUrl;
	}

	/**
	 * Sends the response
	 */
	public function send():void {
		http_response_code($this->getHttpResponseCode());
		header('Location: '.$this->getRedirectUrl()->getUrl(), true);
		exit;
	}
}