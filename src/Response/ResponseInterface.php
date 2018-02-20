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
// Time:     10:41
// Project:  lib-router
//
namespace CodeInc\Router\Response;
use CodeInc\Router\Response\Exceptions\ResponseSendingException;
use CodeInc\Router\Response\Exceptions\ResponseSentException;


/**
 * Interface ResponseInterface
 *
 * @package CodeInc\GUI\PagesManager\Response
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface ResponseInterface {
	/**
	 * Verifies if the response is set.
	 *
	 * @return bool
	 */
	public function isSent():bool;

	/**
	 * Sends the response.
	 *
	 * @throws ResponseSendingException
	 * @throws ResponseSentException
	 */
	public function send():void;
}