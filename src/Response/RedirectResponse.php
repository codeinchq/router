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
use CodeInc\Router\Response\Exceptions\ResponseSendingException;
use CodeInc\Router\Response\Exceptions\ResponseSentException;
use CodeInc\Url\Url;


/**
 * Class RedirectResponse
 *
 * @package CodeInc\Router\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class RedirectResponse extends AbstractResponse {
	// default HTTP response code value
	public const DEFAULT_HTTP_RESPONSE_CODE = 302;

	/**
	 * @var Url
	 */
	public $redirectUrl;

	/**
	 * @var bool
	 */
	private $sent = false;

	/**
	 * RedirectResponse constructor.
	 *
	 * @param Url $redirectUrl
	 * @param int|null $httpResponseCode
	 */
	public function __construct(Url $redirectUrl, int $httpResponseCode = null) {
		parent::__construct();
		$this->redirectUrl = $redirectUrl;
		$this->getHttpHeaders()->setResponseCode($httpResponseCode ?? self::DEFAULT_HTTP_RESPONSE_CODE);
	}

	/**
	 * Returns the redirect URL.
	 *
	 * @return Url
	 */
	public function getRedirectUrl():Url {
		return $this->redirectUrl;
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function isSent():bool {
		return $this->sent;
	}

	/**
	 * Sends the response.
	 *
	 * @throws ResponseSendingException
	 * @throws ResponseSentException
	 */
	public function send():void {
		if ($this->isSent()) {
			throw new ResponseSentException($this);
		}
		try {
			$this->getHttpHeaders()->addHeader("Location", $this->getRedirectUrl()->getUrl());
			$this->getHttpHeaders()->send();
			$this->getCookies()->send();
			$this->sent = true;
		}
		catch (\Throwable $exception) {
			throw new ResponseSendingException($this, $exception);
		}
	}
}