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
// Date:     23/02/2018
// Time:     13:44
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router\ResponseSender;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * Class SimpleResponseSender
 *
 * @package CodeInc\Router\ResponseSender
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class SimpleResponseSender implements ResponseSenderInterface {
	/**
	 * @inheritdoc
	 * @param ResponseInterface $response
	 * @param null|RequestInterface $request
	 * @throws ResponsSenderException
	 */
	public function send(ResponseInterface $response, ?RequestInterface $request = null):void
	{
		// checking if a reponse is sent
		if (headers_sent()) {
			throw new ResponsSenderException("A response have already been sent to the web browser",
				$this);
		}

		// making the response compatible with the request
		if ($request && $response->getProtocolVersion() != $request->getProtocolVersion()) {
			$response = $response->withProtocolVersion($request->getProtocolVersion());
		}

		// sending the headers
		$this->sendResponseHeaders($response);
		$this->sendReponseBody($response);
	}

	/**
	 * Sedns the response headers.
	 *
	 * @param ResponseInterface $response
	 */
	protected function sendResponseHeaders(ResponseInterface $response):void
	{
		header("HTTP/{$response->getProtocolVersion()} {$response->getStatusCode()} "
			."{$response->getReasonPhrase()}", true);
		foreach ($response->getHeaders() as $header => $values) {
			header("$header: ".implode(", ", $values));
		}
	}

	/**
	 * Sends the response body.
	 *
	 * @param ResponseInterface $response
	 */
	protected function sendReponseBody(ResponseInterface $response):void
	{
		$body = $response->getBody();
		if (!$response->hasHeader("Content-Length") && ($size = $body->getSize()) !== null) {
			header("Content-Length: $size");
		}
		while (!$body->eof()) {
			if ($line = \GuzzleHttp\Psr7\readline($body, 1024)) {
				echo $line;
				flush();
			}
		}
	}
}