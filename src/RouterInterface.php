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
// Date:     14/02/2018
// Time:     16:01
// Project:  lib-router
//
declare(strict_types=1);
namespace CodeInc\Router;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


/**
 * Interface RouterInterface
 *
 * @package CodeInc\Router
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface RouterInterface extends RoutableInterface {
	/**
	 * Verifies if the router can process a given request.
	 *
	 * @param RequestInterface $request
	 * @return bool
	 */
	public function canProcessRequest(RequestInterface $request):bool;

	/**
	 * Sends a PSR response to the web browser.
	 *
	 * @param ResponseInterface $response
	 * @param RequestInterface $request If set the protocol version of the response is modified to match the request's version
	 */
	public function sendResponse(ResponseInterface $response, ?RequestInterface $request = null):void;
}