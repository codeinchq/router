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
// Time:     19:22
// Project:  lib-router
//
declare(strict_types = 1);
namespace CodeInc\Router\Responses;


/**
 * Class NotFoundResponse
 *
 * @package CodeInc\Router\Responses
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class NotFoundResponse extends HtmlResponse {
	/**
	 * NotFoundResponse constructor.
	 *
	 * @param string|null $html
	 * @param null|string $charset
	 * @param array $headers
	 * @param null $body
	 * @param string $version
	 * @param null|string $reason
	 */
	public function __construct(string $html = null, ?string $charset = null, array $headers = [],
		$body = null, string $version = '1.1', ?string $reason = null)
	{
		parent::__construct($html, $charset, 404, $headers, $body, $version, $reason);
	}
}